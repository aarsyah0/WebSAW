@extends('layouts.guest')

@section('title', 'Rekomendasi Mainan Anak - SAW')

@section('content')
{{-- Hero --}}
<section id="beranda" class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-secondary-50" aria-hidden="true"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight leading-tight">
                    Rekomendasi Mainan Anak dengan Metode SAW
                </h1>
                <p class="text-base lg:text-lg text-gray-600 max-w-xl leading-relaxed">
                    Dapatkan rekomendasi mainan yang sesuai usia, budget, dan prioritas Anda. Sistem cerdas berbasis Simple Additive Weighting (SAW).
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl font-semibold text-white bg-primary-500 shadow-md hover:bg-primary-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fa-solid fa-rocket"></i> Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl font-semibold text-gray-700 border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition duration-300">
                        Masuk
                    </a>
                </div>
            </div>
            <div class="hidden lg:flex justify-center">
                <div class="relative w-80 h-80 rounded-3xl bg-white/80 border border-gray-100 shadow-xl flex items-center justify-center text-8xl">
                    🧸
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Products --}}
<section id="produk" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-2 mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Produk Pilihan</h2>
            <p class="text-base text-gray-500 max-w-xl mx-auto">Jelajahi mainan berkualitas untuk si kecil.</p>
        </div>
        @if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts->take(4) as $product)
                    <x-product-card
                        :name="$product->name"
                        :price="$product->price"
                        :age-range="$product->age_range"
                        :stock="$product->stock"
                        :image="$product->image ? asset('storage/' . $product->image) : null"
                    >
                        <a href="{{ route('login') }}" class="block w-full text-center py-2.5 rounded-xl font-semibold text-white bg-primary-500 hover:bg-primary-600 transition duration-300 mt-2">
                            Lihat
                        </a>
                    </x-product-card>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl font-semibold text-primary-600 hover:bg-primary-50 transition duration-300">
                    Daftar untuk belanja <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        @else
            <div class="text-center py-12 rounded-2xl bg-gray-50 border border-gray-100">
                <p class="text-gray-500">Belum ada produk. Daftar untuk mengakses setelah admin menambah produk.</p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 mt-4 px-6 py-2.5 rounded-2xl font-semibold text-white bg-primary-500 hover:bg-primary-600 transition">Daftar</a>
            </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight mb-2">Siap dapatkan rekomendasi?</h2>
        <p class="text-gray-500 mb-6">Daftar gratis dan isi prioritas Anda.</p>
        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl font-semibold text-white bg-primary-500 shadow-md hover:bg-primary-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <i class="fa-solid fa-puzzle-piece"></i> Daftar Sekarang
        </a>
    </div>
</section>

<footer class="border-t border-gray-100 bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
        Sistem Pendukung Keputusan Rekomendasi Produk Mainan Anak — Metode SAW
    </div>
</footer>
@endsection
