@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-2xl space-y-6">
    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Tambah Produk</h2>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <x-card>
            <div class="space-y-4">
                <x-input label="Nama *" name="name" value="{{ old('name') }}" required />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Harga *" name="price" type="number" value="{{ old('price') }}" min="0" required />
                    <x-input label="Stok *" name="stock" type="number" value="{{ old('stock', 0) }}" min="0" required />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Rentang usia (contoh: 3-6)" name="age_range" value="{{ old('age_range') }}" placeholder="3-6" />
                    <x-input label="Kategori" name="category" value="{{ old('category') }}" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Gambar</label>
                    <input type="file" name="image" accept="image/*" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-primary-700 file:font-medium focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
                <h3 class="font-semibold text-gray-900 pt-2">Nilai Kriteria (0–5)</h3>
                <p class="text-sm text-gray-500 mb-2">Kriteria <strong>Harga</strong> diambil dari field Harga di atas. Kriteria lain mengikuti yang ada di admin.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($criterias as $c)
                        @if($c->name === 'Harga')
                            @continue
                        @endif
                        <div>
                            <x-input
                                :label="$c->name . ' *'"
                                :name="'criteria_values['.$c->id.']'"
                                type="number"
                                :value="old('criteria_values.'.$c->id, 3)"
                                min="0"
                                max="5"
                                step="0.5"
                                required
                            />
                            <p class="text-xs text-gray-400 mt-0.5">{{ $c->type === 'cost' ? 'Cost' : 'Benefit' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>
        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary" size="md">Simpan</x-button>
            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection
