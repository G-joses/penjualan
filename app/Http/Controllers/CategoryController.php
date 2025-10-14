<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * index
     *
     * @return void
     */

    public function index(): View
    {
        $category = Category::latest()->paginate(5);
        return view('category.category', compact('category'));
    }

    /**
     * create
     *
     * @return View
     */

    public function create(): View
    {
        return view('category.create');
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
            'name'          => 'required|min:5',
            'description'   => 'required|min:10',
        ]);

        //create category
        category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        //redirect to index
        return redirect()->route('category.index')->with(['success' => 'Data Berhasil Disimpan']);
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        $category = Category::findOrFail($id);

        return view('category.edit', compact('category'));
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
            'name'          => 'required|min:5',
            'description'   => 'required|min:10',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('category.index')->with(['success' => 'Data Berhasil Diganti']);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus');
    }
}
