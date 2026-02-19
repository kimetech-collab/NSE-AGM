@extends('layouts.public')

@section('title', 'Sponsors — NSE 59th AGM Portal')

@section('content')
<section class="bg-white py-16 sm:py-20" aria-labelledby="sponsors-page-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="text-center mb-10">
            <p class="text-nse-neutral-500 text-xs font-semibold uppercase tracking-widest mb-2">Partners & Sponsors</p>
            <h1 id="sponsors-page-heading" class="text-3xl font-bold text-nse-neutral-900">Our Sponsors</h1>
            <p class="text-sm text-nse-neutral-600 mt-3">Organizations supporting the NSE 59th AGM & International Conference.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6" role="list" aria-label="Sponsors list">
            @forelse($sponsors as $sponsor)
                <article role="listitem" class="bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-5 min-h-[128px] flex flex-col items-center justify-center text-center gap-3">
                    @if($sponsor->logo_url)
                        <img src="{{ $sponsor->logo_url }}" alt="{{ $sponsor->name }}" class="max-h-12 max-w-full object-contain">
                    @else
                        <div class="text-sm font-semibold text-nse-neutral-900">{{ $sponsor->name }}</div>
                    @endif

                    @if($sponsor->website_url)
                        <a href="{{ $sponsor->website_url }}" target="_blank" rel="noopener" class="text-xs text-nse-green-700 hover:underline">Visit website</a>
                    @endif
                </article>
            @empty
                <div class="col-span-full text-center text-sm text-nse-neutral-500 py-10">
                    Sponsors will be announced soon.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
