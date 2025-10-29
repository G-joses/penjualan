<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Bulanan</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }

        .report-title {
            font-size: 14px;
            color: #34495e;
            margin: 5px 0;
        }

        .period {
            font-size: 12px;
            color: #7f8c8d;
            margin: 0;
        }

        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            flex-wrap: wrap;
        }

        .card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 8px 12px;
            flex: 1;
            margin: 0 5px;
            min-width: 120px;
        }

        .card-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }

        .card-label {
            font-size: 9px;
            color: #7f8c8d;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }

        th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #2c3e50;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bg-success {
            background-color: #d4edda !important;
        }

        .bg-warning {
            background-color: #fff3cd !important;
        }

        .total-row {
            background-color: #e3f2fd;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #7f8c8d;
        }

        .page-number:before {
            content: "Halaman " counter(page);
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin: 40px 0 5px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="company-name">NAMA PERUSAHAAN ANDA</h1>
        <h2 class="report-title">LAPORAN PENJUALAN BULANAN</h2>
        <p class="period">Periode: {{ $bulan }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card">
            <div class="card-value">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
            <div class="card-label">Total Transaksi</div>
        </div>
        <div class="card">
            <div class="card-value">{{ number_format($totalItemTerjual, 0, ',', '.') }}</div>
            <div class="card-label">Item Terjual</div>
        </div>
        <div class="card">
            <div class="card-value">Rp {{ number_format($rataTransaksi, 0, ',', '.') }}</div>
            <div class="card-label">Rata-rata Transaksi</div>
        </div>
        <div class="card">
            <div class="card-value">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</div>
            <div class="card-label">Total Penjualan</div>
        </div>
    </div>

    <!-- Produk Terlaris -->
    <h3 style="margin: 15px 0 5px 0; font-size: 11px;">PRODUK TERLARIS</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="35%">Nama Produk</th>
                <th width="20%">Kategori</th>
                <th width="15%">Jumlah Terjual</th>
                <th width="15%">Rata-rata Harga</th>
                <th width="20%" class="text-right">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanProduk as $index => $item)
            <tr class="{{ $index < 3 ? 'bg-success' : '' }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item['nama_produk'] }}</td>
                <td>{{ $item['kategori'] }}</td>
                <td class="text-center">{{ number_format($item['jumlah_terjual'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['rata_harga'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3"><strong>TOTAL KESELURUHAN</strong></td>
                <td class="text-center"><strong>{{ number_format(array_sum(array_column($laporanProduk, 'jumlah_terjual')), 0, ',', '.') }}</strong></td>
                <td class="text-right">-</td>
                <td class="text-right"><strong>Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Ringkasan Transaksi (Simplified) -->
    <h3 style="margin: 20px 0 5px 0; font-size: 11px;">RINGKASAN TRANSAKSI</h3>
    <table>
        <thead>
            <tr>
                <th width="20%">Tanggal</th>
                <th width="20%">No. Transaksi</th>
                <th width="20%">Jumlah Item</th>
                <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $dailySummary = $transactions->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });
            @endphp

            @foreach($dailySummary as $date => $dailyTransactions)
            <tr style="background-color: #f8f9fa;">
                <td colspan="5"><strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong></td>
            </tr>
            @foreach($dailyTransactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('H:i') }}</td>
                <td>{{ $transaction->transaction_code ?? $transaction->id }}</td>
                <td class="text-center">{{ $transaction->details->sum('qty') }}</td>
                <td class="text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Disiapkan Oleh</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Disetujui Oleh</p>
        </div>
    </div>

    <div class="footer">
        <div style="float: left;">
            Dicetak pada: {{ $tanggalCetak }}
        </div>
        <div style="float: right;" class="page-number"></div>
        <div style="clear: both;"></div>
    </div>
</body>

</html>