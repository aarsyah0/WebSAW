<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
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
        $pickupStart = config('store.pickup_start', '19:00');
        $pickupEnd = config('store.pickup_end', '21:00');

        return view('user.checkout', compact('items', 'total', 'pickupStart', 'pickupEnd'));
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

        $pickupDateTime = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $pickupStartDateTime = Carbon::parse($request->pickup_date . ' ' . config('store.pickup_start', '19:00'));
        $pickupEndDateTime = Carbon::parse($request->pickup_date . ' ' . config('store.pickup_end', '21:00'));

        if ($pickupDateTime->lt(now())) {
            return back()->withInput()->withErrors([
                'pickup_time' => 'Jam pengambilan harus lebih besar dari waktu saat ini.',
            ]);
        }

        if ($pickupDateTime->lt($pickupStartDateTime) || $pickupDateTime->gt($pickupEndDateTime)) {
            $pickupStart = config('store.pickup_start', '07:00');
            $pickupEnd = config('store.pickup_end', '21:00');
            return back()->withInput()->withErrors([
                'pickup_time' => "Jam pengambilan hanya tersedia antara jam {$pickupStart} sampai {$pickupEnd}.",
            ]);
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'code' => $code,
            'address' => config('store.address'),
            'phone' => $request->phone,
            'pickup_at' => $pickupDateTime,
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
