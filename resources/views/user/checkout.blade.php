@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-900 tracking-tight mb-6">Checkout</h2>

    <form method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @csrf
        <x-card>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Pengambilan di Toko</h3>
            <div class="space-y-6">
                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-1.5">Alamat toko</p>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800">
                        {{ config('store.address') }}
                    </div>
                </div>
                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-1.5">No. telepon toko</p>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800">
                        {{ config('store.phone') }}
                    </div>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">No. telepon pembeli *</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="08..." autocomplete="tel" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal ambil *</label>
                        <input type="date" id="pickup_date" name="pickup_date" value="{{ old('pickup_date', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    </div>
                    <div>
                        <label for="pickup_time" class="block text-sm font-medium text-gray-700 mb-1.5">Jam ambil *</label>
                        <input type="time" id="pickup_time" name="pickup_time" value="{{ old('pickup_time', $pickupStart) }}" min="{{ $pickupStart }}" max="{{ $pickupEnd }}" step="1800" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    </div>
                </div>
                <p class="text-xs text-gray-500">Jam operasional pengambilan: {{ $pickupStart }} - {{ $pickupEnd }}. Pesanan otomatis dibatalkan jika melewati jam pengambilan.</p>
            </div>
        </x-card>
        <div class="space-y-6">
            <x-card>
                <h3 class="font-semibold text-gray-900 mb-4">Ringkasan pesanan</h3>
                <ul class="space-y-3">
                    @foreach($items as $item)
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->product->name }} × {{ $item->quantity }}</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-4 pt-4 border-t border-gray-100 font-bold text-gray-900 text-lg">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>
            </x-card>
            <x-button type="submit" variant="primary" size="lg" class="w-full">Buat pesanan</x-button>
        </div>
    </form>
</div>
@endsection
