@props([
    'label' => null,
    'id' => null,
])

@php
    $id = $id ?? $attributes->get('name', 'select-' . bin2hex(random_bytes(4)));
    $selectClass = 'w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition duration-200';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1.5']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <select id="{{ $id }}" {{ $attributes->except('class')->merge(['class' => $selectClass]) }}>
        {{ $slot }}
    </select>
</div>
