<!DOCTYPE html>
<html>

<head>
    <title>Struk Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .struk {
            width: 300px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        .center {
            text-align: center;
        }

        .total {
            border-top: 1px dashed #000;
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="struk">
        <div class="center">
            <h4>Toko {{ config('app.name') }}</h4>
            <small>{{ now()->format('d/m/Y H:i') }}</small>
        </div>
        <hr>
        <table>
            @foreach ($transaction->details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}x</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">Total</td>
                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
        </table>
        <hr>
        <p class="center">Terima kasih telah berbelanja!</p>
    </div>
</body>

</html>