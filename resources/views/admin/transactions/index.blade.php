@extends('layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Transaksi</h2>
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
            <select name="status" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                <option value="">Semua status</option>
                @foreach(['pending','paid','processing','shipped','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <x-button type="submit" variant="outline" size="sm">Filter</x-button>
            <x-button href="{{ route('admin.transactions.export', request()->query()) }}" variant="outline" size="sm" class="!border-secondary-200 !text-secondary-700 hover:!bg-secondary-50">Export CSV</x-button>
        </form>
    </div>

    <x-card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Kode</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Customer</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Total</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Status</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $t->code }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $t->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.transactions.status', $t) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs font-medium focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                                        @foreach(['pending','paid','processing','shipped','completed','cancelled'] as $s)
                                            <option value="{{ $s }}" {{ $t->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right text-gray-400">—</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex justify-center">{{ $transactions->links() }}</div>
    </x-card>
</div>
@endsection
