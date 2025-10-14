<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * index
     *
     * @return void
     */

    public function index(): View
    {
        $jumlahBarang = Product::count(); // menghitung total data di tabel products
        $jumlahKategori = Category::count(); // menghitung total data di tabel category
        return view('dashboard.dashboard', compact('jumlahBarang', 'jumlahKategori'));
    }
}
