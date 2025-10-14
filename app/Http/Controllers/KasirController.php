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

        // Hitung total harga
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);

        // Simpan data transaksi utama
        $transaction = Transaction::create([
            'invoice' => 'INV-' . time(),
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
