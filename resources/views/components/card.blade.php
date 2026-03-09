@props([
    'padding' => true,
    'hover' => false,
])

@php
    $classes = 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-300';
    if ($hover) {
        $classes .= ' hover:shadow-md hover:-translate-y-0.5';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-gray-100">
            {{ $header }}
        </div>
    @endif
    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $footer }}
        </div>
    @endif
</div>
