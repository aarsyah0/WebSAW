@extends('layouts.app')

@section('title', 'Konsultasi Rekomendasi SAW')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Konsultasi Rekomendasi</h2>
        <p class="mt-1 text-base text-gray-500">Isi profil anak dan prioritas kriteria. Sistem akan merekomendasikan mainan terbaik dengan metode SAW.</p>
    </div>

    <form method="POST" action="{{ route('recommendation.result') }}" class="space-y-6">
        @csrf

        {{-- Step 1: Profil anak --}}
        <x-card>
            <h3 class="text-xl font-semibold text-gray-900 mb-1">Profil anak</h3>
            <p class="text-sm text-gray-500 mb-6">Rentang usia dan budget yang Anda inginkan.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input label="Usia minimal (tahun)" name="age_min" type="number" value="{{ old('age_min', 3) }}" min="0" max="18" required />
                <x-input label="Usia maksimal (tahun)" name="age_max" type="number" value="{{ old('age_max', 6) }}" min="0" max="18" required />
                <x-input label="Budget minimal (Rp)" name="budget_min" type="number" value="{{ old('budget_min', 50000) }}" min="0" required />
                <x-input label="Budget maksimal (Rp)" name="budget_max" type="number" value="{{ old('budget_max', 300000) }}" min="0" required />
            </div>
        </x-card>

        {{-- Step 2: Prioritas kriteria --}}
        <x-card>
            <h3 class="text-xl font-semibold text-gray-900 mb-1">Prioritas kriteria</h3>
            <p class="text-sm text-gray-500 mb-6">Geser 1–5 (1 = rendah, 5 = tinggi). Nilai akan dipakai sebagai bobot SAW.</p>
            <div class="space-y-6">
                @forelse(($criterias ?? collect()) as $c)
                    @php
                        $hint = $c->type === 'cost'
                            ? 'Cost – semakin kecil semakin baik'
                            : 'Benefit – semakin besar semakin baik';
                        $val = old('priorities.' . $c->id, 3);
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="priorities_{{ $c->id }}" class="text-sm font-medium text-gray-700">{{ $c->name }}</label>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="text-sm font-bold text-primary-600 tabular-nums" id="val-{{ $c->id }}">{{ $val }}</span>
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gray-100 text-gray-400 cursor-help text-xs font-bold" title="{{ $hint }}" aria-label="Info">?</span>
                            </span>
                        </div>
                        <input
                            type="range"
                            id="priorities_{{ $c->id }}"
                            name="priorities[{{ $c->id }}]"
                            value="{{ $val }}"
                            min="1"
                            max="5"
                            step="1"
                            class="w-full h-3 rounded-full appearance-none bg-gray-200 accent-primary-500 cursor-pointer"
                            title="{{ $hint }}"
                        >
                        <p class="text-xs text-gray-400 mt-0.5">{{ $hint }}</p>
                    </div>
                @empty
                    <x-alert variant="warning">
                        Kriteria belum tersedia. Tambahkan kriteria di panel admin terlebih dahulu.
                    </x-alert>
                @endforelse
            </div>
        </x-card>

        <x-button type="submit" variant="primary" size="lg" class="w-full sm:w-auto">
            <i class="fa-solid fa-lightbulb"></i> Dapatkan Rekomendasi
        </x-button>
    </form>
</div>
@endsection
