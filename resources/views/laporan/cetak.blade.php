<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2,
        h4 {
            text-align: center;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h2>Laporan Penjualan Bulanan</h2>
    <h4>Bulan: {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th>Tanggal Transaksi</th>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($transactions as $t)
            @foreach($t->details as $d)
            <tr>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $d->product->name ?? '-' }}</td>
                <td>{{ $d->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $d->subtotal; @endphp
            @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <strong>Total Penjualan: Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
    </div>
</body>

</html>