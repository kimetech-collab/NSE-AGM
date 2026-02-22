@extends('layouts.public')

@section('title', 'Venue & Travel — NSE 59th AGM & Conference')

@section('content')
@php
    $eventStartAt = \App\Support\EventDates::get('event_start_at');
    $eventEndAt = \App\Support\EventDates::get('event_end_at');
@endphp

<section class="bg-linear-to-b from-nse-green-700 to-nse-green-900 py-16 sm:py-20 text-white" aria-labelledby="venue-hero">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="max-w-3xl">
            <p class="text-nse-gold-400 text-sm font-bold uppercase tracking-widest mb-2">Venue Information</p>
            <h1 id="venue-hero" class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">
                Venue & Travel
            </h1>
            <p class="text-lg text-white/80 leading-relaxed">
                The Pinnacle Function Centre in Maiduguri. World-class facilities for 1000+ participants. {{ $eventStartAt->format('F j') }}–{{ $eventEndAt->format('j, Y') }}.
            </p>
        </div>
    </div>
</section>

<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="venue-details">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <div>
                <h2 id="venue-details" class="text-2xl font-bold text-nse-neutral-900 mb-6">The Pinnacle Function Centre</h2>

                <div class="space-y-6">
                    <div>
                        <p class="text-xs text-nse-neutral-500 uppercase tracking-widest font-semibold mb-2">Address</p>
                        <p class="text-nse-neutral-900 font-semibold leading-relaxed">
                            Maiduguri Ring Road<br/>
                            Maiduguri, Borno State<br/>
                            Nigeria
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-nse-neutral-500 uppercase tracking-widest font-semibold mb-2">Contact</p>
                        <p class="text-nse-neutral-900 font-semibold">+234 (0) 76 XXXX XXXX</p>
                        <p class="text-nse-neutral-600 text-sm">info@pinnaclemdu.com</p>
                    </div>

                    <div>
                        <p class="text-xs text-nse-neutral-500 uppercase tracking-widest font-semibold mb-3">Facilities</p>
                        <ul class="space-y-2">
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700"><span class="text-nse-green-700 font-bold">✓</span> Large auditorium (1200+ capacity)</li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700"><span class="text-nse-green-700 font-bold">✓</span> 8+ breakout rooms (20–100 capacity)</li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700"><span class="text-nse-green-700 font-bold">✓</span> Exhibition floor (2500+ sqm)</li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700"><span class="text-nse-green-700 font-bold">✓</span> On-site dining (capacity 800+)</li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700"><span class="text-nse-green-700 font-bold">✓</span> High-speed WiFi & AV technology</li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700"><span class="text-nse-green-700 font-bold">✓</span> Ample parking & accessibility</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-nse-neutral-100 rounded-lg border-2 border-dashed border-nse-neutral-300 overflow-hidden aspect-square md:aspect-auto md:h-96 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-nse-neutral-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="font-semibold text-nse-neutral-600">Venue Map</p>
                    <p class="text-xs text-nse-neutral-500 mt-1">Interactive map coming soon</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl font-bold text-nse-neutral-900 mb-10">Getting Here & Accommodation</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <h3 class="font-bold text-nse-neutral-900 mb-3">By Air</h3>
                <p class="text-sm text-nse-neutral-700">Maiduguri International Airport (MJI) with connections from major Nigerian cities. ~20 mins to venue.</p>
            </div>

            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <h3 class="font-bold text-nse-neutral-900 mb-3">By Road</h3>
                <p class="text-sm text-nse-neutral-700">Accessible by major highways from Abuja, Lagos, and Kano. Flying is recommended for long-distance participants.</p>
            </div>

            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <h3 class="font-bold text-nse-neutral-900 mb-3">Partner Hotels</h3>
                <p class="text-sm text-nse-neutral-700">Negotiated rates available at select hotels near the venue for registered participants.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="security">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 id="security" class="text-2xl font-bold text-nse-neutral-900 mb-6">Safety & Security</h2>

        <div class="bg-nse-green-50 rounded-lg p-6 border border-nse-green-200 space-y-4">
            <p class="leading-relaxed text-nse-neutral-800">
                The NSE has coordinated with relevant authorities to ensure a safe and secure conference environment.
            </p>
            <ul class="space-y-2 text-sm text-nse-neutral-700">
                <li class="flex gap-2.5"><span class="text-nse-green-700 font-bold">✓</span> Security personnel on-site</li>
                <li class="flex gap-2.5"><span class="text-nse-green-700 font-bold">✓</span> CCTV coverage</li>
                <li class="flex gap-2.5"><span class="text-nse-green-700 font-bold">✓</span> Emergency response coordination</li>
                <li class="flex gap-2.5"><span class="text-nse-green-700 font-bold">✓</span> Medical support on standby</li>
                <li class="flex gap-2.5"><span class="text-nse-green-700 font-bold">✓</span> Secure QR accreditation</li>
            </ul>
        </div>
    </div>
</section>

<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl font-bold text-nse-neutral-900 mb-6">International Travel & Visas</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg p-6 border border-nse-neutral-200">
                <h3 class="font-bold text-nse-neutral-900 mb-3">Nigerian Visa</h3>
                <p class="text-sm text-nse-neutral-700">International participants may require a visa depending on citizenship. Invitation letters are available on request.</p>
            </div>
            <div class="bg-white rounded-lg p-6 border border-nse-neutral-200">
                <h3 class="font-bold text-nse-neutral-900 mb-3">Documentation</h3>
                <p class="text-sm text-nse-neutral-700">Bring a valid passport, travel itinerary, and accommodation details where applicable.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-nse-green-700 py-12 sm:py-14 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Register Now</h2>
        <p class="text-white/80 text-base mb-8">Secure your accommodation and travel arrangements early.</p>
        <a
            href="{{ route('register') }}"
            class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50"
        >
            Register for the Conference
        </a>
    </div>
</section>

@if(isset($venueItems) && $venueItems->count() > 0)
    <section class="bg-white py-10 border-t border-nse-neutral-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
            <h2 class="text-xl font-semibold text-nse-neutral-900 mb-4">Additional Venue Updates</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($venueItems as $item)
                    <article class="bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-4">
                        <p class="text-xs text-nse-neutral-500 uppercase tracking-wide">{{ $item->section }}</p>
                        <h3 class="font-semibold text-nse-neutral-900 mt-1">{{ $item->title }}</h3>
                        @if($item->content)
                            <p class="text-sm text-nse-neutral-700 mt-2">{{ $item->content }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
