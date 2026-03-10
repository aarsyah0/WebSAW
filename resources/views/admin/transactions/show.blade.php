@extends('layouts.app')

@section('title', 'Invoice ' . $transaction->code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke daftar transaksi
        </a>
        <x-button type="button" variant="outline" size="sm" data-action="print">
            <i class="fa-solid fa-print"></i> Cetak Invoice
        </x-button>
    </div>

    <x-card :padding="false" class="print:bg-white print:shadow-none">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-500 tracking-wider uppercase">Invoice</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $transaction->code }}</h2>
                <p class="text-sm text-gray-500 mt-1">Tanggal: {{ $transaction->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div class="text-right space-y-2">
                <div>
                    <p class="text-xs font-semibold text-gray-500 tracking-wider uppercase">Status</p>
                    <x-badge :variant="$transaction->status === 'completed' || $transaction->status === 'paid' ? 'success' : ($transaction->status === 'cancelled' ? 'danger' : 'warning')">
                        {{ $transaction->status }}
                    </x-badge>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 tracking-wider uppercase">Total</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-gray-100">
            <div>
                <p class="text-xs font-semibold text-gray-500 tracking-wider uppercase mb-1">Dibayar oleh</p>
                <p class="text-sm font-medium text-gray-900">{{ $transaction->user->name ?? '-' }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ $transaction->address }}</p>
                <p class="text-sm text-gray-600 mt-1">Telp: {{ $transaction->phone }}</p>
            </div>
            <div class="sm:text-right">
                <p class="text-xs font-semibold text-gray-500 tracking-wider uppercase mb-1">Informasi Sistem</p>
                <p class="text-sm text-gray-700 font-medium">Sistem Rekomendasi Mainan Anak (SAW)</p>
                <p class="text-sm text-gray-500 mt-1">Invoice ini dihasilkan otomatis oleh sistem setelah pembayaran berhasil.</p>
            </div>
        </div>

        <div class="px-6 py-5">
            <p class="text-xs font-semibold text-gray-500 tracking-wider uppercase mb-3">Detail item</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-4 py-2 font-semibold text-gray-700">Produk</th>
                            <th class="text-right px-4 py-2 font-semibold text-gray-700">Qty</th>
                            <th class="text-right px-4 py-2 font-semibold text-gray-700">Harga</th>
                            <th class="text-right px-4 py-2 font-semibold text-gray-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($transaction->details as $d)
                            <tr>
                                <td class="px-4 py-2 text-gray-800">{{ $d->product->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-right text-gray-700">{{ $d->quantity }}</td>
                                <td class="px-4 py-2 text-right text-gray-700">Rp {{ number_format($d->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right font-medium text-gray-900">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-100">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </x-card>
</div>
@endsection

