@props([
    'name',
    'price',
    'ageRange' => null,
    'stock' => 0,
    'image' => null,
    'url' => null,
])

<div {{ $attributes->merge(['class' => 'group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1']) }}>
    <a href="{{ $url ?? '#' }}" class="block aspect-square bg-gray-50 overflow-hidden">
        @if($image)
            <img src="{{ $image }}" alt="{{ $name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
        @else
            <div class="h-full w-full flex items-center justify-center text-5xl">🧸</div>
        @endif
    </a>
    <div class="p-6 space-y-3">
        @if($ageRange)
            <x-badge variant="primary">{{ $ageRange }}</x-badge>
        @endif
        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 min-h-[2.5rem]">{{ $name }}</h3>
        <p class="text-xl font-bold text-primary-600">Rp {{ number_format($price, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-400">Stok: {{ $stock }}</p>
        {{ $slot }}
    </div>
</div>
