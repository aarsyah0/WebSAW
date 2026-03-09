@extends('layouts.app')

@section('title', 'Hasil Rekomendasi SAW')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Hasil Rekomendasi</h2>
        <p class="mt-1 text-base text-gray-500">Top 5 produk sesuai prioritas Anda (metode SAW).</p>
    </div>

    @if($recommendations->isEmpty())
        <x-alert variant="warning">
            <p class="font-medium">Tidak ada produk yang cocok dengan filter usia dan budget.</p>
            <p class="mt-1 text-sm">Coba perlebar rentang budget atau usia.</p>
            <a href="{{ route('recommendation.index') }}" class="inline-flex mt-3 font-semibold text-primary-600 hover:text-primary-700">Ubah kriteria →</a>
        </x-alert>
    @else
        {{-- Hasil ranking (kartu produk) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendations as $index => $rec)
                <div class="animate-fade-in-up opacity-0 stagger-{{ $index + 1 }}" style="animation-delay: {{ $index * 0.08 }}s">
                    <x-card :hover="true" class="{{ $rec['rank'] === 1 ? 'ring-2 ring-primary-500 shadow-lg' : '' }}">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <x-badge variant="{{ $rec['rank'] === 1 ? 'primary' : 'default' }}">
                                #{{ $rec['rank'] }} {{ $rec['rank'] === 1 ? 'Best Choice' : '' }}
                            </x-badge>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Nilai SAW</p>
                                <div class="h-2 w-20 rounded-full bg-gray-100 overflow-hidden mt-0.5">
                                    <div class="h-full bg-primary-500 rounded-full transition-all duration-500" style="width: {{ min(100, $rec['score'] * 20) }}%"></div>
                                </div>
                                <p class="text-sm font-bold text-primary-600 mt-0.5">{{ number_format($rec['score'], 2) }}</p>
                            </div>
                        </div>
                        <div class="aspect-square rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden mb-4">
                            @if($rec['product']->image)
                                <img src="{{ Storage::url($rec['product']->image) }}" alt="{{ $rec['product']->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-5xl">🧸</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900">{{ $rec['product']->name }}</h3>
                        <p class="text-lg font-bold text-primary-600 mt-1">Rp {{ number_format($rec['product']->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $rec['explanation'] }}</p>
                        @if($rec['product']->stock > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $rec['product']->id }}">
                                <x-button type="submit" variant="primary" size="sm" class="w-full">Tambah ke Keranjang</x-button>
                            </form>
                        @else
                            <p class="mt-4 text-sm text-gray-400 font-medium">Stok habis</p>
                        @endif
                    </x-card>
                </div>
            @endforeach
        </div>

        {{-- Bagian matriks perhitungan SAW (untuk skripsi) --}}
        @if(!empty($criterias) && !empty($decision_matrix))
            <x-card class="mt-10">
                <h3 class="text-xl font-semibold text-gray-900 mb-1">Perhitungan Metode SAW</h3>
                <p class="text-sm text-gray-500 mb-6">Tampilan matriks keputusan, ternormalisasi, bobot, dan skor preferensi.</p>

                {{-- 1. Bobot Kriteria (W) --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">1. Bobot Kriteria (W)</h4>
                    <p class="text-sm text-gray-600 mb-3">Bobot dinormalisasi dari prioritas yang Anda pilih (jumlah = 1).</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Kriteria</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Tipe</th>
                                    <th class="text-right px-4 py-3 font-semibold text-gray-700">Bobot (W)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($criterias as $c)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $c['name'] }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $c['type'] === 'cost' ? 'Cost' : 'Benefit' }}</td>
                                        <td class="px-4 py-3 text-right font-mono">{{ number_format($c['weight'], 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 2. Matriks Keputusan (X) --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">2. Matriks Keputusan (X)</h4>
                    <p class="text-sm text-gray-600 mb-3">Nilai setiap alternatif (produk) pada tiap kriteria. Harga dalam Rp.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden min-w-[600px]">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-700 sticky left-0 bg-gray-50">Alternatif (Produk)</th>
                                    @foreach($criterias as $c)
                                        <th class="text-right px-4 py-3 font-semibold text-gray-700">{{ $c['name'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($decision_matrix as $row)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white">{{ $row['product_name'] }}</td>
                                        @foreach($criterias as $c)
                                            <td class="px-4 py-3 text-right">
                                                @if($c['name'] === 'Harga')
                                                    {{ number_format($row[$c['name']] ?? 0, 0, ',', '.') }}
                                                @else
                                                    {{ number_format($row[$c['name']] ?? 0, 2) }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. Matriks Ternormalisasi (R) --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">3. Matriks Ternormalisasi (R)</h4>
                    <p class="text-sm text-gray-600 mb-3">Cost: r = min/x. Benefit: r = x/max. Nilai 0–1.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden min-w-[600px]">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-700 sticky left-0 bg-gray-50">Alternatif (Produk)</th>
                                    @foreach($criterias as $c)
                                        <th class="text-right px-4 py-3 font-semibold text-gray-700">{{ $c['name'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($normalized_matrix as $row)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white">{{ $row['product_name'] }}</td>
                                        @foreach($criterias as $c)
                                            <td class="px-4 py-3 text-right font-mono">{{ number_format($row[$c['name']] ?? 0, 4) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 4. Skor Preferensi (Vi) dan Ranking --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">4. Skor Preferensi (Vi) dan Ranking</h4>
                    <p class="text-sm text-gray-600 mb-3">Vi = Σ (Wj × rij). Diurutkan dari tertinggi.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Ranking</th>
                                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Alternatif (Produk)</th>
                                    <th class="text-right px-4 py-3 font-semibold text-gray-700">Skor (Vi)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $sortedScores = collect($preference_scores)->sortDesc();
                                    $rank = 1;
                                @endphp
                                @foreach($sortedScores as $pid => $score)
                                    @php
                                        $name = collect($decision_matrix)->firstWhere('product_id', $pid)['product_name'] ?? 'Produk #'.$pid;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-bold text-primary-600">{{ $rank }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $name }}</td>
                                        <td class="px-4 py-3 text-right font-mono">{{ number_format($score, 4) }}</td>
                                    </tr>
                                    @php $rank++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>
        @endif
    @endif

    <a href="{{ route('recommendation.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
        <i class="fa-solid fa-arrow-left"></i> Ubah kriteria & dapatkan rekomendasi lagi
    </a>
</div>
@endsection
