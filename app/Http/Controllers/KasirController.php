<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $products = Product::all();
        return view('kasir.index', compact('products'));
    }

    /**
     * store
     *
     * @return void
     */
    public function store(Request $request)
    {
        $cart = $request->cart;
        $payment = $request->payment;
        $tax = $request->tax ?? 0;
        $discount = $request->discount ?? 0;
        $customer_name = $request->customer_name ?? 'Pelanggan';

        // Hitung Subtotal
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);

        // Hitung Pajak
        $tax_amount = ($subtotal * $tax) / 100;

        // Hitung Total setelah diskon dan pajak

        $total = $subtotal - $discount + $tax_amount;

        // Validasi pembayaran
        if ($payment < $total) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran Kurang !'
            ]);
        }

        // Simpan data transaksi utama
        $transaction = Transaction::create([
            'invoice' => 'INV-' . time(),
            'customer_name' => $customer_name,
            'subtotal' => $subtotal,
            'tax' => $tax_amount,
            'discount' => $discount,
            'total' => $total,
            'payment' => $payment,
            'change_amount' => $payment - $total
        ]);

        // Simpan detail transaksi dan kurangi stok
        foreach ($cart as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price']
            ]);

            Product::where('id', $item['id'])->decrement('stock', $item['qty']);
        }
        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id,
        ]);
    }
    /**
     * struk
     *
     * @return void
     */
    public function struk($id)
    {
        $transaction = Transaction::with('details.product')->findOrFail($id);
        return view('kasir.struk', compact('transaction'));
    }
}
