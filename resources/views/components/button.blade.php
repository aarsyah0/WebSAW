@props([
    'tag' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $tag = $href ? 'a' : $tag;
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-2xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:pointer-events-none';
    $variants = [
        'primary' => 'bg-primary-500 text-white shadow-md hover:bg-primary-600 hover:shadow-lg hover:-translate-y-0.5 focus:ring-primary-500',
        'outline' => 'border-2 border-gray-200 text-gray-700 bg-white hover:border-gray-300 hover:bg-gray-50 focus:ring-gray-400',
        'secondary' => 'bg-secondary-500 text-white shadow-md hover:bg-secondary-600 hover:shadow-lg hover:-translate-y-0.5 focus:ring-secondary-500',
        'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-300',
    ];
    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-2.5 text-base',
        'lg' => 'px-8 py-3.5 text-base',
    ];
    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($tag === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $attributes->get('type', 'submit') }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
