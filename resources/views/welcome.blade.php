@extends('layouts.public')

@section('title', 'NSE 59th AGM & International Conference 2026 — Register Now')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 1 — HERO
     Authority-first. NSE Green 700 background.
     Dual countdown timers (Early Bird + Conference).
     Two primary CTAs.
     Mobile-first: single column, centered. Desktop: constrained max-width.
═══════════════════════════════════════════════════════════════════ --}}
<section
    class="bg-nse-green-700 text-white relative overflow-hidden"
    aria-label="Conference overview and registration"
>
    {{-- Subtle texture overlay — institutional, not decorative --}}
    <div class="absolute inset-0 opacity-[0.03]" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-10 py-14 sm:py-20 lg:py-24 text-center">

        {{-- Official event badge --}}
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white/90 text-xs sm:text-sm font-medium px-4 py-2 rounded-full mb-6 sm:mb-8">
            <span class="w-2 h-2 bg-nse-gold-500 rounded-full flex-shrink-0" aria-hidden="true"></span>
            59th Annual General Meeting &amp; International Conference
        </div>

        {{-- Primary heading --}}
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight text-white mb-3 sm:mb-4">
            Engineering Nigeria's Future
        </h1>
        <p class="text-lg sm:text-xl lg:text-2xl text-white/80 font-medium mb-2 leading-snug">
            Innovation, Resilience &amp; Sustainable Development
        </p>
        <p class="text-sm sm:text-base text-white/60 mb-10 sm:mb-12 flex items-center justify-center gap-2 flex-wrap">
            <span>&#128205; Maiduguri, Borno State</span>
            <span class="text-white/30 hidden sm:inline" aria-hidden="true">&middot;</span>
            <span>&#128197; November 1 – 4, 2026</span>
        </p>

        {{-- Dual Countdown Timers --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-2xl mx-auto mb-10 sm:mb-12">

            {{-- Early Bird Countdown --}}
            <div
                x-data="{
                    days: '00', hours: '00', mins: '00', secs: '00',
                    urgency: 'gold',
                    init() {
                        this.tick();
                        setInterval(() => this.tick(), 1000);
                    },
                    tick() {
                        const diff = new Date('2026-04-28T23:59:59').getTime() - Date.now();
                        if (diff <= 0) { this.days = this.hours = this.mins = this.secs = '00'; return; }
                        const d = Math.floor(diff / 86400000);
                        this.days  = String(d).padStart(2, '0');
                        this.hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                        this.mins  = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                        this.secs  = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                        this.urgency = d < 2 ? 'red' : (d < 7 ? 'orange' : 'gold');
                    }
                }"
                class="bg-white/10 border border-white/20 rounded-lg p-4 sm:p-5 text-center"
                role="timer"
                aria-label="Early bird registration deadline countdown"
            >
                <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-3">Early Bird Ends In</p>
                <div class="flex justify-center gap-2 sm:gap-3 mb-3">
                    <div class="text-center">
                        <span x-text="days"
                            class="block text-3xl sm:text-4xl font-bold"
                            :class="{
                                'text-red-400':       urgency === 'red',
                                'text-amber-400':     urgency === 'orange',
                                'text-nse-gold-500':  urgency === 'gold'
                            }">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Days</span>
                    </div>
                    <span class="text-white/30 text-2xl font-light self-start pt-1" aria-hidden="true">:</span>
                    <div class="text-center">
                        <span x-text="hours"
                            class="block text-3xl sm:text-4xl font-bold"
                            :class="{
                                'text-red-400':       urgency === 'red',
                                'text-amber-400':     urgency === 'orange',
                                'text-nse-gold-500':  urgency === 'gold'
                            }">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Hours</span>
                    </div>
                    <span class="text-white/30 text-2xl font-light self-start pt-1" aria-hidden="true">:</span>
                    <div class="text-center">
                        <span x-text="mins"
                            class="block text-3xl sm:text-4xl font-bold"
                            :class="{
                                'text-red-400':       urgency === 'red',
                                'text-amber-400':     urgency === 'orange',
                                'text-nse-gold-500':  urgency === 'gold'
                            }">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Mins</span>
                    </div>
                    <span class="text-white/30 text-2xl font-light self-start pt-1" aria-hidden="true">:</span>
                    <div class="text-center">
                        <span x-text="secs"
                            class="block text-3xl sm:text-4xl font-bold"
                            :class="{
                                'text-red-400':       urgency === 'red',
                                'text-amber-400':     urgency === 'orange',
                                'text-nse-gold-500':  urgency === 'gold'
                            }">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Secs</span>
                    </div>
                </div>
                <p class="text-white/50 text-xs">Deadline: April 28, 2026</p>
            </div>

            {{-- Conference Countdown --}}
            <div
                x-data="{
                    days: '00', hours: '00', mins: '00', secs: '00',
                    init() { this.tick(); setInterval(() => this.tick(), 1000); },
                    tick() {
                        const diff = new Date('2026-11-01T08:00:00').getTime() - Date.now();
                        if (diff <= 0) { this.days = this.hours = this.mins = this.secs = '00'; return; }
                        this.days  = String(Math.floor(diff / 86400000)).padStart(2, '0');
                        this.hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                        this.mins  = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                        this.secs  = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                    }
                }"
                class="bg-white/10 border border-white/20 rounded-lg p-4 sm:p-5 text-center"
                role="timer"
                aria-label="Conference start countdown"
            >
                <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-3">Conference Begins In</p>
                <div class="flex justify-center gap-2 sm:gap-3 mb-3">
                    <div class="text-center">
                        <span x-text="days"  class="block text-3xl sm:text-4xl font-bold text-white">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Days</span>
                    </div>
                    <span class="text-white/30 text-2xl font-light self-start pt-1" aria-hidden="true">:</span>
                    <div class="text-center">
                        <span x-text="hours" class="block text-3xl sm:text-4xl font-bold text-white">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Hours</span>
                    </div>
                    <span class="text-white/30 text-2xl font-light self-start pt-1" aria-hidden="true">:</span>
                    <div class="text-center">
                        <span x-text="mins"  class="block text-3xl sm:text-4xl font-bold text-white">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Mins</span>
                    </div>
                    <span class="text-white/30 text-2xl font-light self-start pt-1" aria-hidden="true">:</span>
                    <div class="text-center">
                        <span x-text="secs"  class="block text-3xl sm:text-4xl font-bold text-white">00</span>
                        <span class="text-white/50 text-[10px] uppercase tracking-wide">Secs</span>
                    </div>
                </div>
                <p class="text-white/50 text-xs">November 1, 2026 &middot; Maiduguri</p>
            </div>
        </div>

        {{-- CTAs --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center">
            <a
                href="{{ route('register') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150 min-w-[180px]"
            >
                Register Now
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <a
                href="/programme"
                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-transparent text-white text-base font-semibold rounded-md border-2 border-white/50 hover:border-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150 min-w-[180px]"
            >
                View Programme
            </a>
        </div>

        {{-- Early bird note --}}
        <p class="mt-6 text-white/50 text-xs">
            <span class="inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-nse-gold-500 rounded-full" aria-hidden="true"></span>
                Early bird pricing locks at registration date &mdash; register now to secure your rate
            </span>
        </p>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 2 — KEY STATS BAR
     White bg with NSE dividers. Credibility markers.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-b border-nse-neutral-200" aria-label="Event statistics">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-nse-neutral-200">

            <div class="py-8 px-4 sm:px-8 text-center border-b lg:border-b-0 border-nse-neutral-200">
                <div class="text-3xl sm:text-4xl font-bold text-nse-green-700 mb-1">59<span class="text-nse-gold-500 text-2xl font-bold">th</span></div>
                <div class="text-nse-neutral-400 text-sm font-medium">Annual Edition</div>
            </div>
            <div class="py-8 px-4 sm:px-8 text-center border-b lg:border-b-0 border-nse-neutral-200">
                <div class="text-3xl sm:text-4xl font-bold text-nse-green-700 mb-1">4</div>
                <div class="text-nse-neutral-400 text-sm font-medium">Conference Days</div>
            </div>
            <div class="py-8 px-4 sm:px-8 text-center">
                <div class="text-3xl sm:text-4xl font-bold text-nse-green-700 mb-1">2,000<span class="text-xl font-semibold">+</span></div>
                <div class="text-nse-neutral-400 text-sm font-medium">Expected Engineers</div>
            </div>
            <div class="py-8 px-4 sm:px-8 text-center">
                <div class="text-3xl sm:text-4xl font-bold text-nse-green-700 mb-1">20<span class="text-xl font-semibold">+</span></div>
                <div class="text-nse-neutral-400 text-sm font-medium">Speakers &amp; Sessions</div>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 3 — ABOUT THE AGM
     Gray-50 background. Institutional tone.
     Two-column: copy left, highlights panel right.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-16 sm:py-20" aria-labelledby="about-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            <div>
                <p class="text-nse-gold-700 text-xs font-bold uppercase tracking-widest mb-3">About the 59th AGM</p>
                <h2 id="about-heading" class="text-2xl sm:text-3xl font-bold text-nse-neutral-900 mb-4 leading-tight">
                    Engineering Nigeria's Future:<br>
                    <span class="text-nse-green-700">Innovation, Resilience &amp; Sustainable Development</span>
                </h2>
                <p class="text-nse-neutral-600 text-base leading-relaxed mb-4">
                    The Nigerian Society of Engineers (NSE) Annual General Meeting and International Conference is the nation's premier gathering of engineering professionals — spanning civil, mechanical, electrical, petroleum, structural, and all other engineering disciplines.
                </p>
                <p class="text-nse-neutral-600 text-base leading-relaxed mb-4">
                    The 59th edition convenes in Maiduguri, Borno State, bringing together engineers from across Nigeria and the diaspora to deliberate on policy, showcase innovation, and advance the profession's role in national development.
                </p>
                <p class="text-nse-neutral-600 text-base leading-relaxed mb-6">
                    Earn your Continuing Professional Development (CPD) units, network with industry leaders, and be part of decisions that shape engineering practice in Nigeria.
                </p>
                <a
                    href="/about"
                    class="inline-flex items-center text-nse-green-700 font-semibold text-sm hover:text-nse-green-900 transition-colors group focus:outline-none focus:underline"
                >
                    Learn more about the AGM
                    <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-lg border border-nse-neutral-200 p-6 sm:p-8 shadow-sm">
                <h3 class="text-base font-semibold text-nse-neutral-900 mb-6 pb-4 border-b border-nse-neutral-200">Event Highlights</h3>
                <ul class="space-y-5" role="list">
                    @php $highlights = [
                        ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                         'title' => 'Technical Sessions & Keynotes',
                         'sub'   => '4 days · Multiple tracks across engineering disciplines'],
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                         'title' => 'CPD Units & Certificates',
                         'sub'   => 'Issued digitally · Publicly verifiable · Attendance-tracked'],
                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                         'title' => 'Exhibition & Networking',
                         'sub'   => 'Industry exhibitors · Sponsors showcase · Peer networking'],
                        ['icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.82V15.18a1 1 0 01-1.447.91L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
                         'title' => 'Virtual Attendance Option',
                         'sub'   => 'Live stream · Attend from anywhere in Nigeria or abroad'],
                    ] @endphp

                    @foreach($highlights as $h)
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-nse-green-50 rounded-md flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-4 h-4 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $h['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-nse-neutral-900">{{ $h['title'] }}</p>
                            <p class="text-xs text-nse-neutral-400 mt-0.5">{{ $h['sub'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 4 — PARTICIPATION TYPES
     White bg. Two feature cards.
     4px top border differentiates card type (design spec: Feature Card).
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-16 sm:py-20 border-b border-nse-neutral-200" aria-labelledby="participation-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <div class="text-center mb-10 sm:mb-12">
            <p class="text-nse-gold-700 text-xs font-bold uppercase tracking-widest mb-2">How to Attend</p>
            <h2 id="participation-heading" class="text-2xl sm:text-3xl font-bold text-nse-neutral-900">Choose Your Participation Type</h2>
            <p class="text-nse-neutral-400 text-base mt-3 max-w-xl mx-auto">Both pathways provide certificate eligibility and full access to conference content.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

            {{-- Physical Card --}}
            <article class="bg-white border border-nse-neutral-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="h-1.5 bg-nse-green-700" aria-hidden="true"></div>
                <div class="p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-nse-green-50 rounded-lg flex items-center justify-center flex-shrink-0" aria-hidden="true">
                            <svg class="w-5 h-5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-nse-neutral-900">Physical Attendance</h3>
                            <span class="inline-flex text-xs font-medium text-nse-green-700 bg-nse-green-50 px-2 py-0.5 rounded-full mt-0.5">On-site · Maiduguri</span>
                        </div>
                    </div>
                    <p class="text-nse-neutral-400 text-sm mb-6 leading-relaxed">
                        Attend in person at the conference venue. Full access to all sessions, networking events, and the exhibition floor.
                    </p>
                    <ul class="space-y-3 mb-8" role="list" aria-label="Physical attendance benefits">
                        @foreach(['Access to all plenary and technical sessions', 'Networking events and exhibition floor', 'Official QR-based accreditation ticket', 'CPD certificate upon attendance', 'NSE member pricing available'] as $b)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-nse-green-700 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-nse-neutral-600">{{ $b }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <a
                        href="{{ route('register') }}?type=physical"
                        class="block w-full text-center px-6 py-3 bg-nse-green-700 text-white text-sm font-semibold rounded-md hover:bg-nse-green-900 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 transition-colors duration-150"
                    >
                        Register — Physical
                    </a>
                </div>
            </article>

            {{-- Virtual Card --}}
            <article class="bg-white border border-nse-neutral-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="h-1.5 bg-nse-gold-500" aria-hidden="true"></div>
                <div class="p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-nse-gold-50 rounded-lg flex items-center justify-center flex-shrink-0" aria-hidden="true">
                            <svg class="w-5 h-5 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82V15.18a1 1 0 01-1.447.91L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-nse-neutral-900">Virtual Attendance</h3>
                            <span class="inline-flex text-xs font-medium text-nse-gold-700 bg-nse-gold-50 px-2 py-0.5 rounded-full mt-0.5">Online · Attend from anywhere</span>
                        </div>
                    </div>
                    <p class="text-nse-neutral-400 text-sm mb-6 leading-relaxed">
                        Join live via our secure online platform. Watch keynotes and sessions, earn your CPD certificate — no travel required.
                    </p>
                    <ul class="space-y-3 mb-8" role="list" aria-label="Virtual attendance benefits">
                        @foreach(['Live stream via Zoom, Jitsi, or YouTube', 'Secure access gated to registered participants', 'Attend from anywhere in Nigeria or abroad', 'CPD certificate upon 10-minute verified session', 'Lower participation fee'] as $b)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-nse-gold-700 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-nse-neutral-600">{{ $b }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <a
                        href="{{ route('register') }}?type=virtual"
                        class="block w-full text-center px-6 py-3 bg-transparent text-nse-green-700 text-sm font-semibold rounded-md border-2 border-nse-green-700 hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 transition-colors duration-150"
                    >
                        Register — Virtual
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 5 — PRICING PREVIEW BAND
     Compact CTA. NSE neutral-50. Early bird badge. Links to /pricing.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 border-b border-nse-neutral-200 py-10 sm:py-12" aria-label="Pricing preview">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <div class="mb-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-nse-gold-50 text-nse-gold-700 text-xs font-semibold border border-nse-gold-500 rounded-full">
                        <span class="w-1.5 h-1.5 bg-nse-gold-500 rounded-full" aria-hidden="true"></span>
                        Early Bird Active
                    </span>
                </div>
                <p class="text-nse-neutral-900 text-lg font-semibold">Registration fees vary by category &amp; attendance type</p>
                <p class="text-nse-neutral-400 text-sm mt-1">Early bird pricing locks at registration date &middot; Deadline: April 28, 2026</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0 w-full sm:w-auto">
                <a
                    href="{{ route('pricing') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-nse-green-700 text-white text-sm font-semibold rounded-md hover:bg-nse-green-900 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 transition-colors"
                >
                    View Full Pricing
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a
                    href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-transparent text-nse-green-700 text-sm font-semibold rounded-md border-2 border-nse-green-700 hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 transition-colors"
                >
                    Register Now
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 6 — SPONSORS (MANDATORY)
     White bg. Static grid — no carousel (design spec).
     5 sponsor placeholder slots. Replace with <img> when logos provided.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-14 sm:py-16" aria-labelledby="sponsors-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <div class="text-center mb-10">
            <p class="text-nse-neutral-400 text-xs font-semibold uppercase tracking-widest mb-1">Our Sponsors</p>
            <h2 id="sponsors-heading" class="text-xl sm:text-2xl font-bold text-nse-neutral-900">Proudly Supported By</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 items-center justify-items-center" role="list" aria-label="Conference sponsors">
            @forelse($sponsors as $sponsor)
            <div
                role="listitem"
                class="w-full flex items-center justify-center p-4 border border-nse-neutral-200 rounded-lg h-20 bg-nse-neutral-50 hover:border-nse-green-700 hover:bg-nse-green-50 transition-colors duration-200 group"
            >
                @if(!empty($sponsor->logo_url))
                    <img src="{{ $sponsor->logo_url }}"
                         alt="{{ $sponsor->name }}"
                         class="max-h-10 max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-200">
                @else
                    <span class="text-nse-neutral-400 text-xs font-medium text-center group-hover:text-nse-green-700 transition-colors">{{ $sponsor->name }}</span>
                @endif
            </div>
            @empty
                @foreach(['Sponsor One', 'Sponsor Two', 'Sponsor Three', 'Sponsor Four', 'Sponsor Five'] as $placeholderSponsor)
                    <div
                        role="listitem"
                        class="w-full flex items-center justify-center p-4 border border-nse-neutral-200 rounded-lg h-20 bg-nse-neutral-50"
                    >
                        <span class="text-nse-neutral-400 text-xs font-medium text-center">{{ $placeholderSponsor }}</span>
                    </div>
                @endforeach
            @endforelse
        </div>

        <p class="text-center mt-8 text-nse-neutral-400 text-sm">
            Interested in sponsorship opportunities?
            <a href="/contact" class="text-nse-green-700 font-medium hover:underline focus:outline-none focus:underline">Contact us</a>
        </p>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 7 — BOTTOM CTA BAND
     NSE Green 700. Final conversion push before footer.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-green-700 py-14 sm:py-16 text-center" aria-label="Registration call to action">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">
            Ready to participate in the 59th NSE AGM?
        </h2>
        <p class="text-white/70 text-base mb-8 max-w-xl mx-auto">
            Registration is open. Secure your early bird rate before April 28, 2026.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a
                href="{{ route('register') }}"
                class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
            >
                Register Now
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
