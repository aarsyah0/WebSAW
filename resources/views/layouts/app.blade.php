<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-600 antialiased">
    <div class="min-h-screen flex">
        @auth
            <div id="sidebar-overlay" class="sidebar-overlay md:hidden" aria-hidden="true" role="button" tabindex="-1" aria-label="Tutup menu"></div>

            {{-- Sidebar: light for user, dark for admin --}}
            @php $isAdmin = auth()->user()->isAdmin(); @endphp
            <aside id="sidebar" class="sidebar fixed md:static inset-y-0 left-0 z-50 flex flex-col w-72 flex-shrink-0 transform md:transform-none transition-all duration-300 ease-out -translate-x-full md:translate-x-0 {{ $isAdmin ? 'bg-gray-900 text-white' : 'bg-white border-r border-gray-100 shadow-sm' }}" data-collapsed="false">
                <div class="h-16 flex items-center justify-between gap-2 px-4 border-b {{ $isAdmin ? 'border-white/10' : 'border-gray-100' }}">
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $isAdmin ? 'bg-primary-500' : 'bg-primary-500 text-white' }}">
                            <i class="fa-solid fa-puzzle-piece text-sm"></i>
                        </span>
                        <span class="sidebar-label font-bold {{ $isAdmin ? 'text-white' : 'text-gray-900' }} text-base truncate hidden lg:block">Mainan SAW</span>
                    </a>
                    <div class="flex items-center gap-1">
                        <button type="button" data-action="sidebar-collapse" class="hidden lg:flex h-9 w-9 items-center justify-center rounded-lg {{ $isAdmin ? 'text-white/70 hover:bg-white/10' : 'text-gray-500 hover:bg-gray-100' }} transition" aria-label="Ciutkan sidebar">
                            <i class="fa-solid fa-sidebar sidebar-collapse-icon"></i>
                        </button>
                        <button type="button" data-action="sidebar-toggle" class="md:hidden flex h-9 w-9 items-center justify-center rounded-lg {{ $isAdmin ? 'text-white/70 hover:bg-white/10' : 'text-gray-500 hover:bg-gray-100' }}" aria-label="Tutup menu">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>

                <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                    @if($isAdmin)
                        <x-sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" :dark="$isAdmin"><i class="fa-solid fa-gauge w-5 text-center shrink-0"></i><span class="sidebar-label">Dashboard</span></x-sidebar-link>
                        <x-sidebar-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" :dark="$isAdmin"><i class="fa-solid fa-cube w-5 text-center shrink-0"></i><span class="sidebar-label">Produk</span></x-sidebar-link>
                        <x-sidebar-link :href="route('admin.criterias.index')" :active="request()->routeIs('admin.criterias.*')" :dark="$isAdmin"><i class="fa-solid fa-list-check w-5 text-center shrink-0"></i><span class="sidebar-label">Kriteria</span></x-sidebar-link>
                        <x-sidebar-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')" :dark="$isAdmin"><i class="fa-solid fa-receipt w-5 text-center shrink-0"></i><span class="sidebar-label">Transaksi</span></x-sidebar-link>
                    @else
                        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" :dark="false"><i class="fa-solid fa-gauge w-5 text-center shrink-0"></i><span class="sidebar-label">Dashboard</span></x-sidebar-link>
                        <x-sidebar-link :href="route('shop.index')" :active="request()->routeIs('shop.*')" :dark="false"><i class="fa-solid fa-bag-shopping w-5 text-center shrink-0"></i><span class="sidebar-label">Belanja</span></x-sidebar-link>
                        <x-sidebar-link :href="route('recommendation.index')" :active="request()->routeIs('recommendation.*')" :dark="false"><i class="fa-solid fa-lightbulb w-5 text-center shrink-0"></i><span class="sidebar-label">Rekomendasi</span></x-sidebar-link>
                        <x-sidebar-link :href="route('cart.index')" :active="request()->routeIs('cart.*')" :dark="false">
                            <i class="fa-solid fa-cart-shopping w-5 text-center shrink-0"></i><span class="sidebar-label">Keranjang</span>
                            @if(($cartCount = auth()->user()->carts()->count()) > 0)
                                <x-slot:badge><span class="ml-auto bg-primary-500 text-white text-xs font-bold rounded-full min-w-[1.25rem] h-5 px-1.5 flex items-center justify-center">{{ $cartCount }}</span></x-slot:badge>
                            @endif
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" :dark="false"><i class="fa-solid fa-clock-rotate-left w-5 text-center shrink-0"></i><span class="sidebar-label">Riwayat</span></x-sidebar-link>
                    @endif
                </nav>

                <div class="border-t {{ $isAdmin ? 'border-white/10' : 'border-gray-100' }} p-4">
                    <div class="flex items-center gap-3 px-3 py-2 rounded-xl {{ $isAdmin ? 'bg-white/5' : 'bg-gray-50' }} mb-3 sidebar-user">
                        <div class="w-9 h-9 rounded-xl bg-primary-500 flex items-center justify-center text-sm font-bold text-white shrink-0">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <div class="min-w-0 flex-1 sidebar-label">
                            <p class="font-semibold {{ $isAdmin ? 'text-white' : 'text-gray-900' }} truncate text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ $isAdmin ? 'Admin' : 'Pembeli' }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isAdmin ? 'text-white/70 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-100' }} transition">
                            <i class="fa-solid fa-right-from-bracket w-4 shrink-0"></i><span class="sidebar-label">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>
        @endauth

        <div class="flex-1 flex flex-col min-w-0">
            @auth
                <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-100 bg-white/95 backdrop-blur px-4 sm:px-6 lg:px-8 shadow-sm">
                    <button type="button" data-action="sidebar-toggle" class="md:hidden flex h-10 w-10 items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100" aria-label="Menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-900 truncate flex-1">@yield('title', 'Dashboard')</h1>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ auth()->user()->isAdmin() ? 'bg-primary-100 text-primary-700' : 'bg-secondary-100 text-secondary-700' }}">{{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}</span>
                </header>
            @endauth

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                    @if(session('success'))
                        <x-alert variant="success">{{ session('success') }}</x-alert>
                    @endif
                    @if(session('error'))
                        <x-alert variant="error">{{ session('error') }}</x-alert>
                    @endif
                    @if($errors->any())
                        <x-alert variant="warning">
                            <ul class="list-disc list-inside text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </x-alert>
                    @endif
                    @yield('content')
                </div>
            </main>

            <footer class="border-t border-gray-100 bg-white py-5">
                <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">Sistem Pendukung Keputusan Rekomendasi Mainan Anak — Metode SAW</div>
            </footer>
        </div>
    </div>
</body>
</html>
