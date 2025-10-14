@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container">
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Dari Tanggal</label>
            <input type="date" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-4">
            <label>Sampai Tanggal</label>
            <input type="date" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
    <form method="GET" action="{{ route('laporan.cetak') }}" target="_blank" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="month" name="bulan" class="form-control" value="{{ date('Y-m') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-danger">Download PDF</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Jumlah Item</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
            <tr>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td>{{ $t->details->count() }}</td>
                <td>
                    <a href="{{ route('kasir.struk', $t->id) }}" class="btn btn-sm btn-success" target="_blank">
                        <i class="bi bi-printer"></i> Cetak Struk
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection