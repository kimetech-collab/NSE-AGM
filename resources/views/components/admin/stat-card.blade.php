@props([
    'label',
    'value',
    'valueClass' => 'text-nse-green-700',
    'meta' => null,
])

<x-admin.panel class="p-4">
    <div class="text-xs text-nse-neutral-600">{{ $label }}</div>
    <div class="text-3xl font-bold {{ $valueClass }}">{{ $value }}</div>
    @if($meta)
        <div class="text-xs text-nse-neutral-500 mt-1">{{ $meta }}</div>
    @endif
</x-admin.panel>
