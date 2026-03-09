@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Produk</h2>
        <x-button href="{{ route('admin.products.create') }}" variant="primary" size="md">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </x-button>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-3">
        <x-input name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-64" />
        <x-button type="submit" variant="outline" size="md">Cari</x-button>
    </form>

    <x-card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Gambar</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Nama</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Harga</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Stok</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Usia</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $p)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                @if($p->image)
                                    <img src="{{ Storage::url($p->image) }}" alt="" class="w-12 h-12 object-cover rounded-xl">
                                @else
                                    <span class="inline-flex w-12 h-12 items-center justify-center rounded-xl bg-gray-100 text-2xl">🧸</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $p->name }}</td>
                            <td class="px-6 py-4 text-gray-700">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $p->stock }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $p->age_range ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.products.edit', $p) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-primary-600 hover:bg-primary-50 font-medium transition">Edit</a>
                                <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline ml-1" data-confirm="Hapus produk?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50 font-medium transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex justify-center">{{ $products->links() }}</div>
    </x-card>
</div>
@endsection
