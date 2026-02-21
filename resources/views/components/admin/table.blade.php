@props([
    'class' => '',
    'tableClass' => 'min-w-full text-sm',
])

<x-admin.panel {{ $attributes->merge(['class' => 'overflow-x-auto ' . $class]) }}>
    <table class="{{ $tableClass }}">
        {{ $slot }}
    </table>
</x-admin.panel>
