@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
@if($items->isEmpty())
    <x-card class="max-w-lg mx-auto text-center py-16">
        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 mb-4">
            <i class="fa-solid fa-cart-shopping text-3xl"></i>
        </div>
        <h2 class="text-xl font-semibold text-gray-900">Keranjang kosong</h2>
        <p class="mt-1 text-gray-500">Tambahkan mainan dari halaman belanja.</p>
        <x-button href="{{ route('shop.index') }}" variant="primary" size="lg" class="mt-6">Belanja Sekarang</x-button>
    </x-card>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <x-card>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left py-4 font-semibold text-gray-700">Produk</th>
                                <th class="text-left py-4 font-semibold text-gray-700">Harga</th>
                                <th class="text-left py-4 font-semibold text-gray-700">Jumlah</th>
                                <th class="text-right py-4 font-semibold text-gray-700">Subtotal</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="h-16 w-16 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                                @if($item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}" alt="" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center text-2xl">🧸</div>
                                                @endif
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-600">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                    <td class="py-4">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-16 rounded-lg border border-gray-200 px-2 py-2 text-center text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                                            <button type="submit" class="text-sm font-medium text-primary-600 hover:text-primary-700">Update</button>
                                        </form>
                                    </td>
                                    <td class="py-4 text-right font-semibold text-gray-900">Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</td>
                                    <td class="py-4">
                                        <form action="{{ route('cart.destroy', $item) }}" method="POST" data-confirm="Hapus dari keranjang?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" aria-label="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24">
                <x-card>
                    <h3 class="font-semibold text-gray-900 mb-4">Ringkasan</h3>
                    <div class="flex justify-between text-gray-600 mb-2"><span>Subtotal ({{ $items->count() }} item)</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                    <hr class="border-gray-100 my-4">
                    <div class="flex justify-between font-bold text-gray-900 text-lg mb-6"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                    <x-button href="{{ route('checkout.show') }}" variant="primary" size="lg" class="w-full">Checkout</x-button>
                </x-card>
            </div>
        </div>
    </div>
@endif
@endsection
