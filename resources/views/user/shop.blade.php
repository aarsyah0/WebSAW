@extends('layouts.app')

@section('title', 'Belanja Mainan')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    {{-- Sidebar filter (desktop) / Collapse (mobile) --}}
    <aside class="lg:w-64 shrink-0">
        <div class="lg:sticky lg:top-24">
            <div class="md:hidden mb-4">
                <button type="button" id="filter-toggle" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl border border-gray-200 bg-white font-medium text-gray-700 hover:bg-gray-50 transition">
                    <span>Filter</span>
                    <i class="fa-solid fa-chevron-down transition-transform" id="filter-icon"></i>
                </button>
            </div>
            <div id="filter-panel" class="hidden md:block">
                <x-card :padding="true">
                    <form method="GET" action="{{ route('shop.index') }}" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk..." class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Usia</label>
                            <select name="age_range" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                                <option value="">Semua usia</option>
                                <option value="0-3" {{ request('age_range') == '0-3' ? 'selected' : '' }}>0-3 tahun</option>
                                <option value="3-6" {{ request('age_range') == '3-6' ? 'selected' : '' }}>3-6 tahun</option>
                                <option value="6-12" {{ request('age_range') == '6-12' ? 'selected' : '' }}>6-12 tahun</option>
                                <option value="12-18" {{ request('age_range') == '12-18' ? 'selected' : '' }}>12-18 tahun</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select name="category" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                                <option value="">Semua</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c }}" {{ request('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <x-button type="submit" variant="primary" size="sm" class="flex-1">Terapkan</x-button>
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl font-semibold text-gray-600 border border-gray-200 hover:bg-gray-50 text-sm">Reset</a>
                        </div>
                    </form>
                </x-card>
            </div>
        </div>
    </aside>

    {{-- Product grid --}}
    <div class="flex-1 min-w-0">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($products as $product)
                <x-product-card
                    :name="$product->name"
                    :price="$product->price"
                    :age-range="$product->age_range"
                    :stock="$product->stock"
                    :image="$product->image ? Storage::url($product->image) : null"
                >
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <x-button type="submit" variant="primary" size="sm" class="w-full mt-2">Tambah ke Keranjang</x-button>
                    </form>
                </x-product-card>
            @empty
                <div class="col-span-full">
                    <x-card class="text-center py-12">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 mb-4">
                            <i class="fa-solid fa-search text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Tidak ada produk</h3>
                        <p class="text-gray-500 mt-1">Coba ubah filter atau kata kunci.</p>
                        <a href="{{ route('shop.index') }}" class="inline-flex mt-4 font-medium text-primary-600 hover:text-primary-700">Reset filter</a>
                    </x-card>
                </div>
            @endforelse
        </div>
        @if($products->hasPages())
            <div class="mt-8 flex justify-center">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection
