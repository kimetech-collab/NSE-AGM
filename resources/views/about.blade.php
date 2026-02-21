@extends('layouts.public')

@section('title', 'About the AGM — NSE 59th Annual General Meeting & Conference')

@section('content')
@php
    $eventStartAt = \App\Support\EventDates::get('event_start_at');
    $eventEndAt = \App\Support\EventDates::get('event_end_at');
@endphp

{{-- ═══════════════════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-gradient-to-b from-nse-green-700 to-nse-green-900 py-16 sm:py-20 text-white" aria-labelledby="about-hero">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="max-w-3xl">
            <p class="text-nse-gold-400 text-sm font-bold uppercase tracking-widest mb-2">About the Event</p>
            <h1 id="about-hero" class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">
                NSE 59th Annual General Meeting & International Conference
            </h1>
            <p class="text-lg text-white/80 leading-relaxed">
                A signature gathering of engineering professionals, thought leaders, and innovators. Join us for four days of plenary sessions, technical workshops, networking, and insight into the future of engineering in Nigeria and beyond.
            </p>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     QUICK FACTS BANNER
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-b border-nse-neutral-200 py-8" aria-label="Event Quick Facts">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            <div>
                <p class="text-2xl sm:text-3xl font-bold text-nse-green-700">59<span class="text-lg text-nse-neutral-400">th</span></p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Edition</p>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-bold text-nse-gold-700">4</p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Days</p>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-bold text-nse-green-700">1000+</p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Participants</p>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-bold text-nse-gold-700">50+</p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Speakers</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     EVENT INFORMATION
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

            {{-- Dates & Venue --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-nse-neutral-900">Dates & Venue</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <p><span class="font-semibold text-nse-neutral-900">{{ $eventStartAt->format('F j') }}–{{ $eventEndAt->format('j, Y') }}</span></p>
                    <p class="text-nse-neutral-600">The Pinnacle Function Centre</p>
                    <p class="text-nse-neutral-600">Maiduguri, Borno State</p>
                    <p class="text-nse-neutral-600">Nigeria</p>
                </div>
            </div>

            {{-- Attendance Options --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-nse-gold-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM4 20h16a2 2 0 002-2v-2a3 3 0 00-5.856-1.487M9 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-nse-neutral-900">Attendance</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <p><span class="font-semibold text-nse-neutral-900">Physical &</span></p>
                    <p class="text-nse-neutral-600">In-person at venue with full networking access</p>
                    <p class="mt-3"><span class="font-semibold text-nse-neutral-900">Virtual</span></p>
                    <p class="text-nse-neutral-600">Live stream access from anywhere globally</p>
                </div>
            </div>

            {{-- Organization --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-green-700" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-nse-neutral-900">Organization</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <p class="text-nse-neutral-600">Organized by the</p>
                    <p><span class="font-semibold text-nse-neutral-900 font-english-gothic">Nigerian Society of Engineers</span></p>
                    <p class="text-nse-neutral-600 text-xs mt-2">Established 1954 · Registered body of engineering professionals in Nigeria</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     ABOUT SECTION
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="about-overview">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="about-overview" class="text-2xl font-bold text-nse-neutral-900 mb-6">About the Conference</h2>

        <div class="prose prose-sm max-w-none space-y-6 text-nse-neutral-700">
            <p class="leading-relaxed">
                <span class="font-english-gothic">Nigerian Society of Engineers</span> Annual General Meeting and International Conference is the premier gathering of the engineering community in West Africa. For nearly seven decades, the NSE AGM has brought together engineering professionals, academics, industry leaders, government officials, and development partners to discuss critical issues affecting the engineering profession and the nation's development trajectory.
            </p>

            <p class="leading-relaxed">
                The <strong>59th Edition</strong> is a significant milestone in the Society's journey. This conference brings engineered solutions to the forefront of Nigeria's sustainable development agenda, with a focus on innovation, infrastructure resilience, digital transformation, and the green economy.
            </p>

            <h3 class="text-lg font-bold text-nse-neutral-900 mt-8 mb-3">Conference Themes</h3>
            <ul class="space-y-3">
                <li class="flex gap-3">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">▸</span>
                    <span><strong>Digital Transformation in Engineering</strong> — AI, IoT, and smart infrastructure in modern practice</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">▸</span>
                    <span><strong>Climate Resilience & Green Engineering</strong> — Sustainable solutions for environmental challenges</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">▸</span>
                    <span><strong>Infrastructure Development</strong> — Roads, power, water, and telecommunications for national growth</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">▸</span>
                    <span><strong>Professional Excellence & Ethics</strong> — Continuous learning and adherence to engineering standards</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-nse-green-700 font-bold flex-shrink-0">▸</span>
                    <span><strong>Youth & Innovation</strong> — Engaging the next generation of engineers in nation-building</span>
                </li>
            </ul>

        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     WHAT'S INCLUDED
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="whats-included">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="whats-included" class="text-2xl font-bold text-nse-neutral-900 mb-8">What to Expect</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Plenary Sessions --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2h-7l-4 4z"/></svg>
                    </div>
                    <h3 class="font-bold text-nse-neutral-900">Plenary Sessions</h3>
                </div>
                <p class="text-sm text-nse-neutral-700 mb-3">Daily keynote addresses from distinguished speakers including government officials, international experts, and industry leaders presenting insights on current engineering challenges and solutions.</p>
                <ul class="text-xs text-nse-neutral-600 space-y-1">
                    <li>✓ Opening & Closing ceremonies</li>
                    <li>✓ 4 days of keynote sessions</li>
                    <li>✓ Live Q&A with speakers</li>
                </ul>
            </div>

            {{-- Technical Tracks --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-nse-gold-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m6 2a2 2 0 11-4 0m0 0V4m0 2a2 2 0 11-4 0m0 0V4m6 2a2 2 0 11-4 0m0 0V4"/></svg>
                    </div>
                    <h3 class="font-bold text-nse-neutral-900">Technical Tracks</h3>
                </div>
                <p class="text-sm text-nse-neutral-700 mb-3">Concurrent sessions covering specialized disciplines: Civil, Mechanical, Electrical, Chemical, Software Engineering, and Emerging Technologies.</p>
                <ul class="text-xs text-nse-neutral-600 space-y-1">
                    <li>✓ 8+ parallel tracks daily</li>
                    <li>✓ Peer-reviewed papers</li>
                    <li>✓ Hands-on workshops</li>
                </ul>
            </div>

            {{-- Networking & Exhibition --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3a6 6 0 010-12h18a6 6 0 010 12h-3z"/></svg>
                    </div>
                    <h3 class="font-bold text-nse-neutral-900">Networking Events</h3>
                </div>
                <p class="text-sm text-nse-neutral-700 mb-3">Structured networking opportunities, cocktail receptions, and exhibition floor featuring 100+ engineering firms, technology vendors, and government agencies.</p>
                <ul class="text-xs text-nse-neutral-600 space-y-1">
                    <li>✓ Welcome reception</li>
                    <li>✓ Exhibition floor access</li>
                    <li>✓ Networking meals</li>
                </ul>
            </div>

            {{-- Professional Development --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-nse-gold-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747 0-5.502-4.5-10.747-10-10.747z"/></svg>
                    </div>
                    <h3 class="font-bold text-nse-neutral-900">CPD Credits</h3>
                </div>
                <p class="text-sm text-nse-neutral-700 mb-3">All participants receive Continuing Professional Development (CPD) certificates recognized by the Council for the Regulation of Engineering in Nigeria (COREN).</p>
                <ul class="text-xs text-nse-neutral-600 space-y-1">
                    <li>✓ CPD certificate on completion</li>
                    <li>✓ Credited hours for professionals</li>
                    <li>✓ Digital credential issuance</li>
                </ul>
            </div>

        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     ABOUT NSE
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="about-nse">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

            {{-- Story --}}
            <div>
                <h2 id="about-nse" class="text-2xl font-bold text-nse-neutral-900 mb-4">About the NSE</h2>
                <div class="prose prose-sm max-w-none space-y-4 text-nse-neutral-700">
                    <p>
                        The <strong><span class="font-english-gothic">Nigerian Society of Engineers</span> (NSE)</strong> was established in 1954 as a professional body to unite and advance the interests of engineering professionals in Nigeria. Today, with over 100,000 registered members spanning all engineering disciplines, the NSE is the recognized voice of the engineering profession in Nigeria.
                    </p>
                    <p>
                        The Society provides a platform for:
                    </p>
                    <ul class="list-disc list-inside space-y-2">
                        <li>Professional development and continuing education</li>
                        <li>Setting standards and promoting best practices</li>
                        <li>Advocacy for policy and infrastructure development</li>
                        <li>Mentorship and career advancement</li>
                        <li>International collaboration and networking</li>
                    </ul>
                </div>
            </div>

            {{-- Stats --}}
            <div class="bg-nse-green-50 rounded-lg p-8 border border-nse-green-200">
                <h3 class="text-lg font-bold text-nse-green-900 mb-6">By the Numbers</h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-3xl font-bold text-nse-green-700">70+</p>
                        <p class="text-sm text-nse-neutral-700 mt-1">Years of service to the profession</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-nse-green-700">100,000+</p>
                        <p class="text-sm text-nse-neutral-700 mt-1">Registered professional members</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-nse-green-700">36</p>
                        <p class="text-sm text-nse-neutral-700 mt-1">State branches nationwide</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-nse-green-700">15+</p>
                        <p class="text-sm text-nse-neutral-700 mt-1">Engineering disciplines represented</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     FREQUENTLY ASKED QUESTIONS
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="about-faq">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="about-faq" class="text-2xl font-bold text-nse-neutral-900 mb-8">Common Questions</h2>

        @php
        $faqs = [
            [
                'q' => 'Do I need to be an NSE member to attend?',
                'a' => 'No. The conference is open to all engineering professionals, academics, students, and industry professionals. Both members and non-members welcome. Members receive member pricing.',
            ],
            [
                'q' => 'What if I cannot travel to Maiduguri?',
                'a' => 'Virtual attendance is fully supported. You will receive a secure link to access all plenary sessions and keynotes via live stream. Recordings are also available post-event.',
            ],
            [
                'q' => 'Will I receive a CPD certificate?',
                'a' => 'Yes. All participants (physical and virtual) who complete the conference receive a CPD certificate recognized by COREN and the professional engineering bodies.',
            ],
            [
                'q' => 'Can I attend specific sessions only?',
                'a' => 'Conference passes include all sessions for the day(s) you register. You can attend as many sessions and tracks as you wish within your registration period.',
            ],
            [
                'q' => 'Is accommodation provided?',
                'a' => 'Accommodation is not included in registration. However, negotiated rates are available at partner hotels in Maiduguri. Details will be sent to registered participants.',
            ],
        ];
        @endphp

        <div class="divide-y divide-nse-neutral-200 border border-nse-neutral-200 rounded-lg overflow-hidden" x-data="{ open: null }">
            @foreach($faqs as $i => $faq)
            <div>
                <button
                    @click="open = open === {{ $i }} ? null : {{ $i }}"
                    :aria-expanded="open === {{ $i }}"
                    aria-controls="faq-answer-{{ $i }}"
                    class="w-full flex items-center justify-between px-5 py-4 text-left bg-white hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700 group"
                >
                    <span class="text-sm font-semibold text-nse-neutral-900 pr-4">{{ $faq['q'] }}</span>
                    <svg
                        class="w-4 h-4 text-nse-neutral-400 flex-shrink-0 transition-transform duration-200"
                        :class="{ 'rotate-180 text-nse-green-700': open === {{ $i }} }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div
                    id="faq-answer-{{ $i }}"
                    x-show="open === {{ $i }}"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="px-5 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200"
                    role="region"
                    :aria-hidden="open !== {{ $i }}"
                >
                    <p class="text-sm text-nse-neutral-700 leading-relaxed pt-4">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <p class="mt-6 text-sm text-nse-neutral-600 text-center">
            More questions? 
            <a href="/contact" class="text-nse-green-700 font-medium hover:underline">Contact us</a>
            or visit our
            <a href="/faqs" class="text-nse-green-700 font-medium hover:underline">full FAQ page</a>.
        </p>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     CTA SECTION
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-green-700 py-12 sm:py-14 text-center" aria-label="Registration call to action">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Ready to Join Us?</h2>
        <p class="text-white/80 text-base mb-8 max-w-xl mx-auto">
            Register now for the 59th NSE AGM & International Conference and secure your place at Africa's premier engineering event.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a
                href="{{ route('register') }}"
                class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
            >
                Register Now
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <a
                href="{{ route('pricing') }}"
                class="inline-flex items-center justify-center px-8 py-3.5 bg-transparent text-white text-base font-semibold rounded-md border-2 border-white/50 hover:border-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
            >
                View Pricing
            </a>
        </div>
    </div>
</section>

@endsection
