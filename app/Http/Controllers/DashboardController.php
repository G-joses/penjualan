<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * index
     *
     * @return void
     */

    public function index(): View
    {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $data = [
            'jumlahBarang' => Product::count(),
            'jumlahKategori' => Category::count(),
            'jumlahTransaksi' => Transaction::count(),
            'jumlahUser' => User::count(),
            'totalPenjualanHariIni' => Transaction::whereDate('created_at', today())->sum('total'),
            'totalPenjualanBulanIni' => Transaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),
            'transaksiHariIni' => Transaction::whereDate('created_at', today())->count(),
            'bulan' => $bulanIndonesia[now()->month], // Nama bulan Bahasa Indonesia
            'bulan_tahun' => $bulanIndonesia[now()->month] . ' ' . now()->year, // Contoh: "November 2024"
            'transaksiBulanIni' => Transaction::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'monthlySales' => $this->getMonthlySales(),
            'transaksiTerbaru' => Transaction::withCount('details')
                ->latest()
                ->take(5)
                ->get(),
            'produkTerlaris' => TransactionDetail::select(
                'product_id',
                DB::raw('SUM(qty) as total_terjual'),
                DB::raw('AVG(price) as rata_harga')
            )
                ->with('product')
                ->groupBy('product_id')
                ->orderBy('total_terjual', 'desc')
                ->take(5)
                ->get(),
            'produkLowStock' => Product::where('stock', '<=', 10)
                ->orderBy('stock', 'asc')
                ->take(5)
                ->get(),
            'jumlahLowStock' => Product::where('stock', '<=', 10)->count(),
        ];
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return view('dashboard.dashboard', $data, $bulanIndonesia);
    }

    public function getMonthlySales()
    {
        $currentYear = date('Y');
        $sales = Transaction::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[] = $sales->has($i) ? $sales->get($i)->total : 0;
        }

        return $monthlySales;
    }
}
