@props([
    'label' => null,
    'id' => null,
    'error' => null,
])

@php
    $id = $id ?? $attributes->get('name', 'input-' . bin2hex(random_bytes(4)));
    $inputClass = 'w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition duration-200';
    if ($error) $inputClass .= ' border-red-300 focus:border-red-500 focus:ring-red-500/20';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1.5']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <input id="{{ $id }}" {{ $attributes->except('class')->merge(['class' => $inputClass]) }}>
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
