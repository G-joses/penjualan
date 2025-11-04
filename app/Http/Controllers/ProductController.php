<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * index
     *
     * @return void
     */

    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $perPage = $request->input('per_page', 8); // default 8 produk per halaman
        $priceSort = $request->input('price_sort');
        $category = $request->input('category', '');
        $stockSort = $request->input('stock_sort');

        $query = Product::query()->with('category');

        // cari barang
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        // filter berdasarkan kategori
        if ($category) {
            $query->where('category_id', $category);
        }

        // filter berdasarkan harga
        if ($priceSort) {
            $query->orderBy('final_price', $priceSort === 'price_high' ? 'desc' : 'asc');
        }

        // filter berdasarkan stok
        if ($stockSort) {
            $query->orderBy('stock', $stockSort === 'stock_high' ? 'desc' : 'asc');
        }

        // default filter
        if (!$priceSort && !$stockSort) {
            $query->orderBy('name', 'asc');
        }
        $products = $query->paginate($perPage);
        $categories = Category::all();

        // agar pagination tetap membawa parameter search dan per_page
        $products->appends([
            'search' => $keyword,
            'per_page' => $perPage,
            'price_sort' => $priceSort,
            'category' => $category,
            'stock_sort' => $stockSort
        ]);

        return view('products.index', compact('products', 'keyword', 'perPage', 'categories', 'priceSort', 'category', 'stockSort'));
    }

    /**
     * create
     *
     * @return View
     */

    public function create(): View
    {
        $categories = Category::all();
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return view('products.create', compact('categories'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'name'          => 'required|min:5',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
            'discount_value' => 'nullable|numeric|min:0'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('products', $image->hashName());

        //diskon
        $hasDiscount = $request->has('has_discount');
        $discountType = $request->discount_type;

        //create product
        Product::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'has_discount' => $hasDiscount,
            'discount' => $discountType === 'percent' ? $request->discount_value : 0,
            'discount_amount' => $discountType === 'amount' ? $request->discount_value : 0,
            'final_price' => $this->calculateFinalPrice($request)
        ]);
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan']);
    }

    /**
     * calculateFinalPrice
     *
     * @param  mixed $id
     * @return View
     */
    public function calculateFinalPrice($request)
    {
        $price = $request->price;
        $hasDiscount = $request->has('has_discount');
        $discountType = $request->discount_type;
        $discountValue = $request->discount_value ?? 0;

        if (!$hasDiscount) return $price;

        if ($discountType === 'percent') {
            return $price * (1 - ($discountValue / 100));
        } else {
            return max(0, $price - $discountValue);
        }
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        $products = Product::with('category')->findOrFail($id);
        return view('products.show', compact('products'));
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        $products = Product::findOrFail($id);
        $categories = Category::all();
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return view('products.edit', compact('products', 'categories'));
    }
    /**
     * update
     *
     * @param  mixed $id
     * @return View
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'name'          => 'required|min:5',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
            'discount_value' => 'nullable|numeric|min:0'
        ]);

        $products = Product::findOrFail($id);

        // Definisikan variabel yang diperlukan
        $hasDiscount = $request->has('has_discount') && $request->has_discount == '1';
        $discountType = $request->discount_type ?? 'percent';
        $discountValue = $request->discount_value ?? 0;

        // Hitung final price
        $finalPrice = $this->calculateFinalPrice($request);

        if ($request->hasFile('image')) {
            Storage::delete('public/products/' . $products->image);

            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            $products->update([
                'image'           => $image->hashName(),
                'name'            => $request->name,
                'category_id'     => $request->category_id,
                'description'     => $request->description,
                'price'           => $request->price,
                'stock'           => $request->stock,
                'has_discount'    => $hasDiscount,
                'discount'        => $discountType === 'percent' ? $discountValue : 0,
                'discount_amount' => $discountType === 'amount' ? $discountValue : 0,
                'final_price'     => $finalPrice
            ]);
        } else {
            $products->update([
                'name'            => $request->name,
                'category_id'     => $request->category_id,
                'description'     => $request->description,
                'price'           => $request->price,
                'stock'           => $request->stock,
                'has_discount'    => $hasDiscount,
                'discount'        => $discountType === 'percent' ? $discountValue : 0,
                'discount_amount' => $discountType === 'amount' ? $discountValue : 0,
                'final_price'     => $finalPrice
            ]);
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Diganti']);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $products = Product::findOrFail($id);
        Storage::delete('products/' . $products->image);
        $products->delete();
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return redirect()->route('products.index')->with(['success' => 'Data berhasil Dihapus !']);
    }
}
