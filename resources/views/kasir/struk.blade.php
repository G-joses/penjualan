<!DOCTYPE html>
<html>

<head>
    <title>Struk Penjualan</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
        }

        .struk {
            width: 280px;
            margin: auto;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="struk">
        <div class="center">
            <h4 style="margin: 5px 0;">Toko Jologo</h4>
            <small>INV: {{ $transaction->invoice }}</small><br>
            <small>{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <div class="left">
            <p>Pelanggan : {{ $transaction->customer_name }}</p>
        </div>

        <div class="line"></div>

        <table>
            @foreach ($transaction->details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}x</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>

        <div class="line"></div>

        <table>
            <tr>
                <td>Subtotal</td>
                <td class="right">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($transaction->tax > 0)
            <tr>
                <td>Pajak</td>
                <td class="right">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($transaction->discount > 0)
            <tr>
                <td>Diskon</td>
                <td class="right">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total">
                <td>TOTAL</td>
                <td class="right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="right">Rp {{ number_format($transaction->payment, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <div class="center">
            <p style="margin: 10px 0;">Terima kasih atas kunjungannya!</p>
            <small>*** Toko Jologo ***</small>
        </div>
    </div>
</body>

</html>