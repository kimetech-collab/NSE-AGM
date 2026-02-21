@extends('layouts.public')

@section('title', 'Conference Programme — NSE 59th AGM & Conference')

@section('content')
@php
    $eventStartAt = \App\Support\EventDates::get('event_start_at');
    $eventEndAt = \App\Support\EventDates::get('event_end_at');
    $day2 = $eventStartAt->copy()->addDay();
    $day3 = $eventStartAt->copy()->addDays(2);
    $day4 = $eventStartAt->copy()->addDays(3);
@endphp

{{-- ═══════════════════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-gradient-to-b from-nse-gold-700 to-nse-gold-900 py-16 sm:py-20 text-white" aria-labelledby="programme-hero">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="max-w-3xl">
            <p class="text-white/70 text-sm font-bold uppercase tracking-widest mb-2">Conference Schedule</p>
            <h1 id="programme-hero" class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">
                59th NSE AGM Conference Programme
            </h1>
            <p class="text-lg text-white/80 leading-relaxed">
                Four days of keynote sessions, technical tracks, workshops, and networking. {{ $eventStartAt->format('F j') }}–{{ $eventEndAt->format('j, Y') }} at The Pinnacle Function Centre, Maiduguri.
            </p>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     OVERVIEW
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-8 sm:py-10 border-b border-nse-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div>
                <p class="text-2xl font-bold text-nse-gold-700">8+</p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Technical Tracks</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-nse-green-700">50+</p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Speakers</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-nse-gold-700">24</p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Hours of Content</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-nse-green-700">100+</p>
                <p class="text-xs sm:text-sm text-nse-neutral-600 font-medium mt-1">Sessions</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     DAILY BREAKDOWN
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="daily-schedule">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="daily-schedule" class="text-2xl font-bold text-nse-neutral-900 mb-10">Daily Schedule</h2>

        <div class="space-y-6" x-data="{ activeDay: 0 }">

            {{-- Day 1 --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden shadow-sm">
                <button
                    @click="activeDay = activeDay === 0 ? -1 : 0"
                    :aria-expanded="activeDay === 0"
                    class="w-full flex items-center justify-between px-6 py-4 bg-gradient-to-r from-nse-green-50 to-white hover:from-nse-green-100 transition-colors"
                >
                    <div class="text-left">
                        <p class="text-sm font-bold text-nse-gold-700 uppercase tracking-wide">Day 1</p>
                        <p class="text-lg font-bold text-nse-neutral-900">{{ $eventStartAt->format('F j, Y') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-nse-neutral-600 transition-transform duration-200" :class="{ 'rotate-180': activeDay === 0 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </button>
                <div x-show="activeDay === 0" x-transition class="px-6 py-6 border-t border-nse-neutral-200 bg-white space-y-4">
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-green-700">8:00 AM</p>
                            <p class="text-xs text-nse-neutral-500">2 hrs</p>
                        </div>
                        <div class="flex-1 pb-4 border-b border-nse-neutral-100">
                            <p class="font-bold text-nse-neutral-900">Registration & Breakfast</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Venue entrance · Coffee, refreshments</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-green-700">10:00 AM</p>
                            <p class="text-xs text-nse-neutral-500">1.5 hrs</p>
                        </div>
                        <div class="flex-1 pb-4 border-b border-nse-neutral-100">
                            <p class="font-bold text-nse-neutral-900">Opening Ceremony & Keynote</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Main Hall · Welcome address, NSE President's remarks</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-green-700">11:30 AM</p>
                            <p class="text-xs text-nse-neutral-500">1 hr</p>
                        </div>
                        <div class="flex-1 pb-4 border-b border-nse-neutral-100">
                            <p class="font-bold text-nse-neutral-900">Break & Exhibition</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Exhibition floor · Network with exhibitors</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-green-700">12:30 PM</p>
                            <p class="text-xs text-nse-neutral-500">1.5 hrs</p>
                        </div>
                        <div class="flex-1 pb-4 border-b border-nse-neutral-100">
                            <p class="font-bold text-nse-neutral-900">Technical Session 1</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">8+ concurrent tracks · Choose your sessions</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-green-700">2:00 PM</p>
                            <p class="text-xs text-nse-neutral-500">1 hr</p>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-nse-neutral-900">Lunch & Networking</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Main dining area</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Day 2 --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden shadow-sm">
                <button
                    @click="activeDay = activeDay === 1 ? -1 : 1"
                    :aria-expanded="activeDay === 1"
                    class="w-full flex items-center justify-between px-6 py-4 bg-gradient-to-r from-nse-gold-50 to-white hover:from-nse-gold-100 transition-colors"
                >
                    <div class="text-left">
                        <p class="text-sm font-bold text-nse-green-700 uppercase tracking-wide">Day 2</p>
                        <p class="text-lg font-bold text-nse-neutral-900">{{ $day2->format('F j, Y') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-nse-neutral-600 transition-transform duration-200" :class="{ 'rotate-180': activeDay === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </button>
                <div x-show="activeDay === 1" x-transition class="px-6 py-6 border-t border-nse-neutral-200 bg-white space-y-4">
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-gold-700">8:30 AM</p>
                            <p class="text-xs text-nse-neutral-500">2 hrs</p>
                        </div>
                        <div class="flex-1 pb-4 border-b border-nse-neutral-100">
                            <p class="font-bold text-nse-neutral-900">Keynote 2: Digital Transformation</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Main Hall · International speaker on AI & smart infrastructure</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-gold-700">10:30 AM</p>
                            <p class="text-xs text-nse-neutral-500">3 hrs</p>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-nse-neutral-900">Technical Sessions 2 & 3</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">8+ concurrent tracks throughout day</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Day 3 --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden shadow-sm">
                <button
                    @click="activeDay = activeDay === 2 ? -1 : 2"
                    :aria-expanded="activeDay === 2"
                    class="w-full flex items-center justify-between px-6 py-4 bg-gradient-to-r from-nse-green-50 to-white hover:from-nse-green-100 transition-colors"
                >
                    <div class="text-left">
                        <p class="text-sm font-bold text-nse-gold-700 uppercase tracking-wide">Day 3</p>
                        <p class="text-lg font-bold text-nse-neutral-900">{{ $day3->format('F j, Y') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-nse-neutral-600 transition-transform duration-200" :class="{ 'rotate-180': activeDay === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </button>
                <div x-show="activeDay === 2" x-transition class="px-6 py-6 border-t border-nse-neutral-200 bg-white space-y-4">
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-green-700">8:30 AM</p>
                            <p class="text-xs text-nse-neutral-500">1.5 hrs</p>
                        </div>
                        <div class="flex-1 pb-4 border-b border-nse-neutral-100">
                            <p class="font-bold text-nse-neutral-900">Keynote 3: Climate & Green Engineering</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Main Hall · Sustainability solutions for Africa</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-green-700">7:00 PM</p>
                            <p class="text-xs text-nse-neutral-500">2 hrs</p>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-nse-neutral-900">Gala Dinner & Awards Ceremony</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Banquet hall · Industry awards, networking</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Day 4 --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden shadow-sm">
                <button
                    @click="activeDay = activeDay === 3 ? -1 : 3"
                    :aria-expanded="activeDay === 3"
                    class="w-full flex items-center justify-between px-6 py-4 bg-gradient-to-r from-nse-gold-50 to-white hover:from-nse-gold-100 transition-colors"
                >
                    <div class="text-left">
                        <p class="text-sm font-bold text-nse-green-700 uppercase tracking-wide">Day 4</p>
                        <p class="text-lg font-bold text-nse-neutral-900">{{ $day4->format('F j, Y') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-nse-neutral-600 transition-transform duration-200" :class="{ 'rotate-180': activeDay === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </button>
                <div x-show="activeDay === 3" x-transition class="px-6 py-6 border-t border-nse-neutral-200 bg-white space-y-4">
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-gold-700">8:30 AM</p>
                            <p class="text-xs text-nse-neutral-500">1 hr</p>
                        </div>
                        <div class="flex-1 pb-4 border-b border-nse-neutral-100">
                            <p class="font-bold text-nse-neutral-900">Closing Keynote: The Future of Engineering</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Main Hall · Forward-looking address</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono font-bold text-nse-gold-700">10:00 AM</p>
                            <p class="text-xs text-nse-neutral-500">1 hr</p>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-nse-neutral-900">Closing Ceremony & Farewell</p>
                            <p class="text-xs text-nse-neutral-600 mt-1">Certificate presentation · Announcements for 60th AGM</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <p class="mt-8 text-sm text-nse-neutral-600 text-center border-t border-nse-neutral-200 pt-6">
            Detailed session descriptions, speaker bios, and room assignments will be available to registered participants 2 weeks before the conference.
        </p>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     TECHNICAL TRACKS
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="tracks">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="tracks" class="text-2xl font-bold text-nse-neutral-900 mb-8">Technical Tracks</h2>
        <p class="text-nse-neutral-700 mb-8 max-w-3xl">Choose from 8+ concurrent technical tracks covering all engineering disciplines and emerging specialties.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach([
                [
                    'name' => 'Civil & Structural Engineering',
                    'icon' => '🏗️',
                    'topics' => 'Infrastructure, Buildings, Bridges, Transportation',
                ],
                [
                    'name' => 'Mechanical & Manufacturing',
                    'icon' => '⚙️',
                    'topics' => 'Automotive, Industry 4.0, Robotics',
                ],
                [
                    'name' => 'Electrical & Energy',
                    'icon' => '⚡',
                    'topics' => 'Power systems, Renewables, Smart grids',
                ],
                [
                    'name' => 'Petroleum & Chemical',
                    'icon' => '🔬',
                    'topics' => 'Oil & gas, Process engineering, Safety',
                ],
                [
                    'name' => 'Software & ICT',
                    'icon' => '💻',
                    'topics' => 'AI, Cybersecurity, Digital infrastructure',
                ],
                [
                    'name' => 'Environmental & Water',
                    'icon' => '💧',
                    'topics' => 'Sustainability, Water treatment, Climate',
                ],
                [
                    'name' => 'Emerging Technologies',
                    'icon' => '🚀',
                    'topics' => 'IoT, Blockchain, Space technology',
                ],
                [
                    'name' => 'Professional Practice',
                    'icon' => '📋',
                    'topics' => 'Ethics, Standards, Governance',
                ],
            ] as $track)
            <div class="bg-nse-neutral-50 rounded-lg p-6 border border-nse-neutral-200 hover:shadow-md transition-shadow">
                <p class="text-3xl mb-3">{{ $track['icon'] }}</p>
                <h3 class="font-bold text-nse-neutral-900 mb-2">{{ $track['name'] }}</h3>
                <p class="text-xs text-nse-neutral-600">{{ $track['topics'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     CTA
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-green-700 py-12 sm:py-14 text-center" aria-label="Registration call to action">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Register to Attend</h2>
        <p class="text-white/80 text-base mb-8">Secure your place at the 59th NSE AGM and access all programming.</p>
        <a
            href="{{ route('register') }}"
            class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
        >
            Register Now
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</section>

@endsection
