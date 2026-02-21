@props([
    'paginator' => null,
    'class' => 'mt-4',
])

@if($paginator)
    <div {{ $attributes->merge(['class' => $class]) }}>
        {{ $paginator->links() }}
    </div>
@endif
