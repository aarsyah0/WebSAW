@props([
    'variant' => 'info',
])

@php
    $variants = [
        'success' => 'bg-secondary-50 border-secondary-200 text-secondary-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'info' => 'bg-primary-50 border-primary-200 text-primary-800',
    ];
    $classes = 'rounded-2xl border px-5 py-4 ' . ($variants[$variant] ?? $variants['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes, 'role' => 'alert']) }}>
    {{ $slot }}
</div>
