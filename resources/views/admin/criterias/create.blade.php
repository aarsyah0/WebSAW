@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="max-w-md space-y-6">
    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Tambah Kriteria</h2>
    <form method="POST" action="{{ route('admin.criterias.store') }}" class="space-y-6">
        @csrf
        <x-card>
            <div class="space-y-4">
                <x-input label="Nama *" name="name" value="{{ old('name') }}" required />
                <x-select label="Tipe *" name="type" required>
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </x-select>
                <x-input label="Urutan bobot" name="weight_order" type="number" value="{{ old('weight_order', 0) }}" min="0" />
            </div>
        </x-card>
        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary" size="md">Simpan</x-button>
            <a href="{{ route('admin.criterias.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection
