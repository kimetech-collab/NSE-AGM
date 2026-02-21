@props([
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'bg-white border border-nse-neutral-200 rounded-lg ' . $class]) }}>
    {{ $slot }}
</div>
