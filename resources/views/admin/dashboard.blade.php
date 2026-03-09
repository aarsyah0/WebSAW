@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard</h2>
        <p class="mt-1 text-gray-500">Ringkasan toko dan penjualan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <x-metric-card title="Total Produk" :value="$totalProducts" href="{{ route('admin.products.index') }}" icon='<i class="fa-solid fa-cube"></i>'>
            <p class="mt-1 text-sm font-medium text-primary-600">Kelola →</p>
        </x-metric-card>
        <x-metric-card title="Total Transaksi" :value="$totalTransactions" href="{{ route('admin.transactions.index') }}" icon='<i class="fa-solid fa-receipt"></i>'>
            <p class="mt-1 text-sm font-medium text-primary-600">Lihat →</p>
        </x-metric-card>
    </div>

    <x-card>
        <h3 class="font-semibold text-gray-900 mb-4">Penjualan 30 hari terakhir</h3>
        @if($salesChart->isEmpty())
            <div class="py-12 text-center text-gray-500">Belum ada data penjualan.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 font-semibold text-gray-700">Tanggal</th>
                            <th class="text-right px-6 py-3 font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($salesChart as $row)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-3 text-gray-700">{{ $row->date }}</td>
                                <td class="px-6 py-3 text-right font-medium text-gray-900">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</div>
@endsection
