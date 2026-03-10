<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = auth()->user()->carts()->with('product')->get();
        $total = $items->sum(fn ($item) => $item->quantity * $item->product->price);

        return view('user.cart', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $requestedQty = $request->quantity ?? 1;

        $cart = Cart::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $newQuantity = $cart->quantity + $requestedQty;

        if ($product->stock < $newQuantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart->quantity = $newQuantity;
        $cart->save();

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $request->validate(['quantity' => ['required', 'integer', 'min:1']]);

        if ($cart->product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart);
        $cart->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
