@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Halo, {{ auth()->user()->name }}</h2>
        <p class="mt-1 text-gray-500">Kelola belanja dan dapatkan rekomendasi mainan terbaik.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <x-metric-card title="Total transaksi" :value="$totalTransactions" icon='<i class="fa-solid fa-receipt"></i>' />
        <x-metric-card title="Belanja" href="{{ route('shop.index') }}" icon='<i class="fa-solid fa-bag-shopping"></i>'>
            <p class="mt-1 text-sm font-medium text-primary-600">Jelajahi produk →</p>
        </x-metric-card>
        <x-metric-card title="Rekomendasi SAW" href="{{ route('recommendation.index') }}" icon='<i class="fa-solid fa-lightbulb"></i>'>
            <p class="mt-1 text-sm font-medium text-primary-600">Konsultasi →</p>
        </x-metric-card>
    </div>

    <x-card>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Transaksi terbaru</h3>
            <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Lihat semua</a>
        </div>
        @if($recentTransactions->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500">Belum ada transaksi.</p>
                <x-button href="{{ route('shop.index') }}" variant="outline" size="sm" class="mt-3">Belanja</x-button>
            </div>
        @else
            <ul class="divide-y divide-gray-50">
                @foreach($recentTransactions as $t)
                    <li class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                        <div>
                            <p class="font-mono font-medium text-gray-900">{{ $t->code }}</p>
                            <p class="text-sm text-gray-500">{{ $t->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-semibold text-gray-900">Rp {{ number_format($t->total, 0, ',', '.') }}</span>
                            <x-badge :variant="$t->status === 'completed' ? 'success' : ($t->status === 'cancelled' ? 'danger' : 'warning')">{{ $t->status }}</x-badge>
                            <a href="{{ route('transactions.show', $t) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Detail</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>
</div>
@endsection
