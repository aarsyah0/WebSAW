@props([
    'title' => null,
    'value' => null,
    'icon' => null,
    'href' => null,
])

@php
    $wrapperClass = 'rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5';
    if ($href) $wrapperClass .= ' block';
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $wrapperClass }}">
@else
    <div {{ $attributes->merge(['class' => $wrapperClass]) }}>
@endif
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            @if($title)
                <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            @endif
            @if($value !== null)
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $value }}</p>
            @endif
            {{ $slot }}
        </div>
        @if($icon)
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-100 text-primary-600 text-xl">
                {!! $icon !!}
            </span>
        @endif
    </div>
@if($href)
    </a>
@else
    </div>
@endif
