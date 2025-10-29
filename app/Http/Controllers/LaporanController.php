<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{

    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        $query = Transaction::query();

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $transactions = $query->with('details.product')->orderBy('created_at', 'desc')->get();
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return view('laporan.index', compact('transactions', 'from', 'to'));
    }

    /**
     * cetak bulanan
     *
     * @return void
     */
    public function cetakBulanan(Request $request)
    {
        $bulan = $request->get('bulan') ?? now()->format('Y-m');

        $month = substr($bulan, 5, 2);
        $year = substr($bulan, 0, 4);

        // Data transactions dengan relasi yang tersedia
        $transactions = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with(['details.product']) // Hanya load relasi yang tersedia
            ->get();

        // Data untuk laporan produk
        $laporanProduk = [];
        $totalTransaksi = 0;
        $totalItemTerjual = 0;

        foreach ($transactions as $transaksi) {
            $totalTransaksi++;

            foreach ($transaksi->details as $detail) {
                $namaProduk = $detail->product->name ?? 'Produk Tidak Diketahui';
                $kategori = $detail->product->category->name ?? 'Tidak Berkategori';
                $jumlah = $detail->qty;
                $total = $detail->qty * $detail->price;

                $totalItemTerjual += $jumlah;

                if (!isset($laporanProduk[$namaProduk])) {
                    $laporanProduk[$namaProduk] = [
                        'nama_produk' => $namaProduk,
                        'kategori' => $kategori,
                        'jumlah_terjual' => 0,
                        'total_harga' => 0,
                        'rata_harga' => $detail->price,
                    ];
                }

                $laporanProduk[$namaProduk]['jumlah_terjual'] += $jumlah;
                $laporanProduk[$namaProduk]['total_harga'] += $total;
            }
        }

        // Hitung rata-rata transaksi
        $rataTransaksi = $totalTransaksi > 0 ? $transactions->avg('total') : 0;

        // Urutkan produk berdasarkan penjualan tertinggi
        usort($laporanProduk, function ($a, $b) {
            return $b['jumlah_terjual'] - $a['jumlah_terjual'];
        });

        $totalKeseluruhan = array_sum(array_column($laporanProduk, 'total_harga'));

        // Format nama bulan dalam Bahasa Indonesia
        $bulanIndonesia = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $namaBulan = $bulanIndonesia[$month] . ' ' . $year;

        $pdf = Pdf::loadView('laporan.bulanan', [
            'laporanProduk' => $laporanProduk,
            'bulan' => $namaBulan,
            'totalKeseluruhan' => $totalKeseluruhan,
            'totalTransaksi' => $totalTransaksi,
            'totalItemTerjual' => $totalItemTerjual,
            'rataTransaksi' => $rataTransaksi,
            'transactions' => $transactions,
            'tanggalCetak' => now()->translatedFormat('d F Y H:i:s'),
        ]);

        $namaFile = 'Laporan-Penjualan-' . $namaBulan . '.pdf';
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return $pdf->setPaper('a4', 'landscape')->download($namaFile);
    }

    /**
     * Hapus transaksi berdasarkan bulan
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hapusBulanan(Request $request)
    {
        $request->validate([
            'required|date_format:Y-m'
        ]);
        $bulan = $request->bulan;
        $currentMonth = now()->format('Y-m');

        // validasi tidak boleh hapus bulan saat ini
        if ($bulan === $currentMonth) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak Bisa hapus Trensaksi Bulan Ini !'
            ], 422);
        }

        // validasi tidak boleh hapus trnasaki bulan depan
        if ($bulan > $currentMonth) {
            return response()->json([
                'success' => false,
                'message' => 'Bulan Depan Belum Jalan ! Buat Apa Dihapus ?'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // ambil transaki yang mau dihapus
            $transactions = Transaction::whereYear('created_at', substr($bulan, 0, 4))
                ->whereMonth('created_at', substr($bulan, 5, 2))
                ->get();

            $jumlahTransaksi = $transactions->count();

            if ($jumlahTransaksi === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak Ada Transaki Pada Bulan Yang Dipilih !'
                ], 422);
            }

            // Kembalikan stok produk sebelum dihapus.
            foreach ($transactions as $transaction) {
                foreach ($transaction->details as $detail) {
                    // kembalikan stok produk
                    $detail->product->increment('stock', $detail->qty);
                }
            }

            // Hapus transaction_details terlebih dahulu (foreign key constraint)
            TransactionDetail::whereIn('transaction_id', $transaction->pluck('id'))->delete();

            // Hapus transactions
            $transactions = Transaction::whereYear('created_at', substr($bulan, 0, 4))
                ->whereMonth('created_at', substr($bulan, 5, 2))
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil Mengapus $jumlahTransaksi transaksi pada bulan" . $this->formatBulanIndonesia($bulan),
                'deleted_count' => $jumlahTransaksi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan Saat Hapus Transaksi' . $e->getMessage()
            ], 500);
        }
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
    }

    /**
     * Format bulan ke Bahasa Indonesia
     *
     * @param string $bulan (format: Y-m)
     * @return string
     */
    private function formatBulanIndonesia($bulan)
    {
        $bulanIndonesia = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $tahun = substr($bulan, 0, 4);
        $bulan = substr($bulan, 5, 2);

        return $bulanIndonesia[$bulan] . ' ' . $tahun;
    }

    /**
     * Get statistic for delete confirmation modal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistikBulan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m'
        ]);

        $bulan = $request->bulan;
        $currentMonth = now()->format('Y-m');

        // Validasi bulan berjalan
        if ($bulan === $currentMonth) {
            return response()->json([
                'success' => false,
                'is_current_month' => true,
                'message' => 'Bulan Berjalan Tidak Dapat Dihapus'
            ]);
        }

        $transaction = Transaction::whereYear('created_at', substr($bulan, 0, 4))
            ->whereMonth('created_at', substr($bulan, 5, 2))
            ->with('details')
            ->get();

        $static = [
            'total_transaksi' => $transaction->count(),
            'total_pendapatan' => $transaction->sum('total'),
            'total_item_terjual' => $transaction->sum(function ($transaction) {
                return $transaction->details->sum('qty');
            }),
            'periode' => $this->formatBulanIndonesia($bulan),
            'is_current_month' => false
        ];

        return response()->json([
            'success' => true,
            'statistik' => $statistik
        ]);
    }
}
