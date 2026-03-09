@extends('layouts.app')

@section('title', 'Manajemen Kriteria')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Kriteria</h2>
        <x-button href="{{ route('admin.criterias.create') }}" variant="primary" size="md">
            <i class="fa-solid fa-plus"></i> Tambah Kriteria
        </x-button>
    </div>

    <x-card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Nama</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Tipe</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-700">Urutan</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($criterias as $c)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $c->name }}</td>
                            <td class="px-6 py-4">
                                <x-badge :variant="$c->type === 'cost' ? 'warning' : 'success'">{{ $c->type }}</x-badge>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $c->weight_order }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.criterias.edit', $c) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-primary-600 hover:bg-primary-50 font-medium transition">Edit</a>
                                <form action="{{ route('admin.criterias.destroy', $c) }}" method="POST" class="inline ml-1" data-confirm="Hapus kriteria?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50 font-medium transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada kriteria.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
