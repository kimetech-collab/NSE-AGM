@props([
    'class' => '',
])

<x-admin.panel {{ $attributes->merge(['class' => 'p-4 mb-4 ' . $class]) }}>
    {{ $slot }}
</x-admin.panel>
