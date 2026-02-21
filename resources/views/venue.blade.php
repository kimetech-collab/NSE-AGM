@extends('layouts.public')

@section('title', 'Venue & Travel — NSE 59th AGM & Conference')

@section('content')
@php
    $eventStartAt = \App\Support\EventDates::get('event_start_at');
    $eventEndAt = \App\Support\EventDates::get('event_end_at');
@endphp

{{-- ═══════════════════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-gradient-to-b from-nse-green-700 to-nse-green-900 py-16 sm:py-20 text-white" aria-labelledby="venue-hero">
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

{{-- ═══════════════════════════════════════════════════════════════════
     VENUE DETAILS
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="venue-Details">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

            {{-- Left: Details --}}
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
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700">
                                <svg class="w-5 h-5 text-nse-green-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Large auditorium (1200+ capacity)
                            </li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700">
                                <svg class="w-5 h-5 text-nse-green-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                8+ breakout rooms (20–100 capacity)
                            </li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700">
                                <svg class="w-5 h-5 text-nse-green-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Exhibition floor (2500+ sqm)
                            </li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700">
                                <svg class="w-5 h-5 text-nse-green-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                On-site dining (capacity 800+)
                            </li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700">
                                <svg class="w-5 h-5 text-nse-green-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                High-speed WiFi & AV technology
                            </li>
                            <li class="flex gap-2.5 text-sm text-nse-neutral-700">
                                <svg class="w-5 h-5 text-nse-green-700 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Ample parking & accessibility
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            {{-- Right: Map Placeholder --}}
            <div class="bg-nse-neutral-100 rounded-lg border-2 border-dashed border-nse-neutral-300 overflow-hidden aspect-square md:aspect-auto md:h-96 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-nse-neutral-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="font-semibold text-nse-neutral-600">Venue Map</p>
                    <p class="text-xs text-nse-neutral-500 mt-1">Interactive map coming soon</p>
                </div>
            </div>

        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     TRAVEL & ACCOMMODATION
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 class="text-2xl font-bold text-nse-neutral-900 mb-10">Getting Here & Accommodation</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Air Travel --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-bold text-nse-neutral-900">By Air</h3>
                </div>
                <div class="space-y-3 text-sm text-nse-neutral-700">
                    <p><strong>Maiduguri International Airport (MJI)</strong></p>
                    <p>Connected by flights from Lagos (1.5 hrs), Abuja (1.5 hrs), and other Nigerian cities.</p>
                    <p class="text-xs text-nse-neutral-600"><strong>Ground transport:</strong> Taxis and ride-sharing available. ~20 min to venue.</p>
                </div>
            </div>

            {{-- Road Travel --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-nse-gold-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l-4-4m0 0l4-4m-4 4h16"/></svg>
                    </div>
                    <h3 class="font-bold text-nse-neutral-900">By Road</h3>
                </div>
                <div class="space-y-3 text-sm text-nse-neutral-700">
                    <p><strong>Inter-state travel:</strong> Maiduguri is accessible via major highways from Lagos, Abuja, and Kano.</p>
                    <p>Luxury bus operators service these routes. Journey times: Abuja (8–10 hrs), Lagos (12+ hrs).</p>
                    <p class="text-xs text-nse-neutral-600"><strong>Advice:</strong> Flying is recommended for long distances.</p>
                </div>
            </div>

            {{-- Hotels --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="font-bold text-nse-neutral-900">Partner Hotels</h3>
                </div>
                <div class="space-y-3 text-sm text-nse-neutral-700">
                    <p><strong>Negotiated rates available</strong> at premium and mid-range hotels.</p>
                    <p>Details sent to registered participants 4 weeks before the event.</p>
                    <p class="text-xs text-nse-neutral-600"><strong>Options:</strong> 3–5 star properties within 5–15 min drive of venue.</p>
                </div>
            </div>

        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     SECURITY & PROTOCOL
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="security">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="security" class="text-2xl font-bold text-nse-neutral-900 mb-6">Safety & Security</h2>

        <div class="bg-nse-green-50 rounded-lg p-6 border border-nse-green-200 space-y-4">
            <p class="leading-relaxed text-nse-neutral-800">
                <strong>Maiduguri and the 59th NSE AGM</strong> — The NSE has worked closely with federal and state authorities to ensure a safe, secure, and welcoming environment for all participants. The Pinnacle Function Centre operates under comprehensive security protocols.
            </p>
            <ul class="space-y-2 text-sm text-nse-neutral-700">
                <li class="flex gap-2.5">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">✓</span>
                    <span>Armed security personnel on-site 24/7</span>
                </li>
                <li class="flex gap-2.5">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">✓</span>
                    <span>CCTV surveillance throughout venue</span>
                </li>
                <li class="flex gap-2.5">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">✓</span>
                    <span>Emergency protocols coordinated with local authorities</span>
                </li>
                <li class="flex gap-2.5">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">✓</span>
                    <span>Medical services on standby</span>
                </li>
                <li class="flex gap-2.5">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">✓</span>
                    <span>Secure accreditation system (QR tickets)</span>
                </li>
            </ul>
            <p class="text-xs text-nse-neutral-600 pt-2">Participants should confirm travel arrangements with relevant agencies and follow official travel advisories.</p>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     VISA & DOCUMENTATION
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 class="text-2xl font-bold text-nse-neutral-900 mb-6">International Travel & Visas</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-lg p-6 border border-nse-neutral-200">
                <h3 class="font-bold text-nse-neutral-900 mb-3">Nigerian Visa</h3>
                <p class="text-sm text-nse-neutral-700 mb-4">
                    International participants may require a Nigerian visa depending on citizenship.
                </p>
                <ul class="text-xs text-nse-neutral-600 space-y-2">
                    <li>✓ Check <strong>nigeriaimmigration.gov.ng</strong> for visa requirements</li>
                    <li>✓ Visa runs 3–12 months depending on type</li>
                    <li>✓ Express processing available for business visas</li>
                    <li>✓ NSE can provide invitation letters if needed</li>
                </ul>
            </div>

            <div class="bg-white rounded-lg p-6 border border-nse-neutral-200">
                <h3 class="font-bold text-nse-neutral-900 mb-3">Documentation</h3>
                <p class="text-sm text-nse-neutral-700 mb-4">
                    Required documents for entry to Nigeria:
                </p>
                <ul class="text-xs text-nse-neutral-600 space-y-2">
                    <li>✓ Valid passport (6+ months validity)</li>
                    <li>✓ Return ticket or travel itinerary</li>
                    <li>✓ Proof of accommodation</li>
                    <li>✓ Travel insurance (recommended)</li>
                </ul>
            </div>

        </div>

        <div class="mt-6 p-4 bg-nse-gold-50 border border-nse-gold-200 rounded-lg text-sm text-nse-neutral-700">
            <p>
                <strong>✉️ Visa Assistance:</strong> Register with your full details and we can provide invitation letters and guidance. Contact 
                <a href="mailto:conference@nse.org.ng" class="text-nse-green-700 underline">conference@nse.org.ng</a>
            </p>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     CTA
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-green-700 py-12 sm:py-14 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Register Now</h2>
        <p class="text-white/80 text-base mb-8">Secure your accommodation and travel arrangements early.</p>
        <a
            href="{{ route('register') }}"
            class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
        >
            Register for the Conference
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</section>

@endsection
