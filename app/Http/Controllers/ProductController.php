<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * index
     *
     * @return void
     */

    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(5);
        return view('products.index', compact('products'));
    }

    /**
     * create
     *
     * @return View
     */

    public function create(): View
    {
        $categories = Category::all();
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
            'stock'         => 'required|numeric'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('products', $image->hashName());

        //create product
        Product::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ]);

        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan']);
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
            'stock'         => 'required|numeric'
        ]);

        $products = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            Storage::delete('products/' . $products->image);

            $image = $request->file('image');
            $image->storeAs('products', $image->hashName());

            $products->update([
                'image'         => $image->hashName(),
                'name'          => $request->name,
                'category_id'   => $request->category_id,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        } else {
            $products->update([
                'name'          => $request->name,
                'category_id'   => $request->category_id,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
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
        return redirect()->route('products.index')->with(['success' => 'Data berhasil Dihapus !']);
    }
}
