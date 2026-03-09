@props([
    'id' => 'toast-container',
])

<div id="{{ $id }}" class="fixed top-6 right-6 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none" aria-live="polite"></div>
