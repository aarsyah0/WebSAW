<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $items = auth()->user()->carts()->with('product')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }
        $total = $items->sum(fn ($item) => $item->quantity * $item->product->price);
        return view('user.checkout', compact('items', 'total'));
    }

    public function process(CheckoutRequest $request)
    {
        $user = auth()->user();
        $items = $user->carts()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        foreach ($items as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Stok {$item->product->name} tidak mencukupi.");
            }
        }

        $total = $items->sum(fn ($item) => $item->quantity * $item->product->price);
        $code = 'TRX-' . strtoupper(Str::random(8));

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'code' => $code,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => 'pending',
            'total' => $total,
        ]);

        foreach ($items as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'subtotal' => $item->quantity * $item->product->price,
            ]);
            $item->product->decrement('stock', $item->quantity);
        }

        $user->carts()->delete();

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Pesanan berhasil. Kode: ' . $code);
    }
}
