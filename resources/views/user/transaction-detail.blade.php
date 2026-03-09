@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-2xl space-y-6">
    <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke riwayat
    </a>

    <x-card :padding="false">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $transaction->code }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $transaction->created_at->format('d F Y, H:i') }}</p>
            </div>
            <x-badge :variant="$transaction->status === 'completed' ? 'success' : ($transaction->status === 'cancelled' ? 'danger' : 'warning')">{{ $transaction->status }}</x-badge>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</p>
                <p class="text-gray-800 mt-0.5">{{ $transaction->address }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</p>
                <p class="text-gray-800 mt-0.5">{{ $transaction->phone }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 px-6 py-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Item pesanan</p>
            <ul class="space-y-3">
                @foreach($transaction->details as $d)
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-700">{{ $d->product->name }} × {{ $d->quantity }}</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="mt-4 pt-4 border-t border-gray-100 font-bold text-gray-900 text-lg">Total: Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
        </div>
    </x-card>
</div>
@endsection
