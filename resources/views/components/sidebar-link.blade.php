@props([
    'href',
    'active' => false,
    'dark' => false,
])

@php
    $base = 'flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium';
    if ($dark) {
        $activeClasses = 'bg-primary-500 text-white shadow-lg';
        $inactiveClasses = 'text-white/70 hover:text-white hover:bg-white/10';
    } else {
        $activeClasses = 'bg-primary-100 text-primary-700 font-semibold';
        $inactiveClasses = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
    }
    $classes = $base . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    <span class="flex-1 min-w-0 truncate flex items-center gap-3">{{ $slot }}</span>
    @isset($badge)
        {{ $badge }}
    @endisset
</a>
