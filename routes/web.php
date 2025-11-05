<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;

// === LOGIN & LOGOUT ===
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === ROUTE YANG BUTUH LOGIN ===
Route::middleware(['auth'])->group(function () {

    // === DASHBOARD (HANYA ADMIN) ===
    Route::middleware('role:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('category', CategoryController::class);

        // laporan (khusus admin)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak-bulanan', [LaporanController::class, 'cetakBulanan'])->name('laporan.cetakBulanan');
        // menajemen akun user
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/laporan/statistik-bulanan', [LaporanController::class, 'getStatistikBulan']);
        Route::delete('/laporan/hapus-bulanan', [LaporanController::class, 'hapusBulanan']);
        Route::post('/users/{id}/force-logout', [UserController::class, 'forceLogout'])
            ->name('users.force-logout')
            ->middleware(['auth', 'role:admin']);
    });

    // === MENU YANG BISA DIAKSES ADMIN DAN KASIR ===
    Route::middleware('role:admin,kasir')->group(function () {
        // kasir
        Route::resource('kasir', KasirController::class);
        Route::get('kasir/struk/{id}', [KasirController::class, 'struk'])->name('kasir.struk');
        // data barang (hanya lihat jika kasir, full akses jika admin)
        Route::resource('products', ProductController::class);
        // update akun sendiri
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    });
});
