<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-500 text-white">
                    <i class="fa-solid fa-puzzle-piece text-lg"></i>
                </span>
                <span class="font-bold text-gray-900 text-lg hidden sm:block">Mainan Anak SAW</span>
            </a>

            {{-- Center menu (desktop) --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ url('/') }}#beranda" class="px-4 py-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition duration-300">Beranda</a>
                <a href="{{ url('/') }}#produk" class="px-4 py-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition duration-300">Produk</a>
            </div>

            {{-- Auth (desktop) --}}
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-2xl font-semibold text-gray-700 hover:bg-gray-100 transition duration-300">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-2xl font-semibold text-white bg-primary-500 shadow-md hover:bg-primary-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">Daftar</a>
            </div>

            {{-- Hamburger (mobile) --}}
            <button type="button" id="nav-toggle" class="md:hidden flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 transition" aria-label="Menu">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile drawer --}}
<div id="nav-overlay" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 md:hidden" aria-hidden="true"></div>
<div id="nav-drawer" class="nav-drawer md:hidden" aria-hidden="true">
    <div class="flex h-16 items-center justify-between px-4 border-b border-gray-100">
        <span class="font-bold text-gray-900">Menu</span>
        <button type="button" id="nav-close" class="flex h-10 w-10 items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100" aria-label="Tutup">
            <i class="fa-solid fa-times text-xl"></i>
        </button>
    </div>
    <div class="p-6 space-y-1">
        <a href="{{ url('/') }}#beranda" class="nav-drawer-link block px-4 py-3 rounded-xl text-gray-700 font-medium hover:bg-gray-50">Beranda</a>
        <a href="{{ url('/') }}#produk" class="nav-drawer-link block px-4 py-3 rounded-xl text-gray-700 font-medium hover:bg-gray-50">Produk</a>
        <hr class="border-gray-100 my-4">
        <a href="{{ route('login') }}" class="nav-drawer-link block px-4 py-3 rounded-xl text-gray-700 font-medium hover:bg-gray-50">Masuk</a>
        <a href="{{ route('register') }}" class="nav-drawer-link block px-4 py-3 rounded-xl font-semibold text-white bg-primary-500 text-center mt-2">Daftar</a>
    </div>
</div>
