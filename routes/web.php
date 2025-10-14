<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanController;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('dashboard', DashboardController::class);
Route::resource('products', ProductController::class);
Route::resource('category', CategoryController::class);
Route::resource('kasir', KasirController::class);
Route::get('kasir/struk/{id}', [KasirController::class, 'struk'])->name('kasir.struk');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
