@props([
    'title',
    'subtitle' => null,
])

<div class="mb-6 flex items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-nse-neutral-900">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-sm text-nse-neutral-600 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    @if(trim($slot ?? '') !== '')
        <div>{{ $slot }}</div>
    @endif
</div>
