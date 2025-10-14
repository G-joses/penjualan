<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{

    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $from = $request->get('fromm');
        $to = $request->get('to');

        $query = Transaction::query();

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $transactions = $query->with('details.product')->orderBy('created_at', 'desc')->get();

        return view('laporan.index', compact('transactions', 'from', 'to'));
    }

    /**
     * cetak
     *
     * @return void
     */
    public function cetak(Request $request)
    {
        $bulan = $request->get('bulan');
        if (!$bulan) {
            $bulan = date('Y-m');
        }

        $from = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $to = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $transactions = Transaction::whereBetween('created_at', [$from, $to])
            ->with('details.product')
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('laporan.cetak', [
            'transactions' => $transactions,
            'bulan' => $bulan,
            'from' => $from,
            'to' => $to
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("Laporan_Penjualan_{$bulan}.pdf");
    }
}
