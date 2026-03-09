@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6">
    <x-card :padding="false">
        @forelse($transactions as $t)
            <a href="{{ route('transactions.show', $t) }}" class="flex flex-wrap items-center justify-between gap-4 p-6 hover:bg-gray-50/80 transition border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-4 min-w-0">
                    <span class="flex w-12 h-12 items-center justify-center rounded-xl bg-gray-100 text-gray-600 font-mono text-sm font-bold shrink-0">{{ substr($t->code, -6) }}</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900">{{ $t->code }}</p>
                        <p class="text-sm text-gray-500">{{ $t->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                    <span class="font-bold text-gray-900">Rp {{ number_format($t->total, 0, ',', '.') }}</span>
                    <x-badge :variant="$t->status === 'completed' ? 'success' : ($t->status === 'cancelled' ? 'danger' : 'warning')">{{ $t->status }}</x-badge>
                </div>
            </a>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 mb-4">
                    <i class="fa-solid fa-receipt text-3xl"></i>
                </div>
                <p class="font-medium text-gray-900">Belum ada transaksi</p>
                <p class="text-sm text-gray-500 mt-0.5">Riwayat pesanan akan muncul di sini</p>
                <x-button href="{{ route('shop.index') }}" variant="primary" size="md" class="mt-4">Belanja</x-button>
            </div>
        @endforelse
    </x-card>
    @if($transactions->hasPages())
        <div class="flex justify-center">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection
