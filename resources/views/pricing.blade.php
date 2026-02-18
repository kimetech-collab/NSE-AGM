@extends('layouts.public')

@section('title', 'Registration Pricing — NSE 59th AGM & Conference 2026')

@section('content')

@php
/*
    PRICING DATA
    All amounts are in kobo (₦ * 100) internally but displayed as ₦.
    Values are ₦0 — admin-configurable via /admin/pricing after portal launch.
    Early bird deadline: April 28, 2026.
    Registration close: October 25, 2026.
*/
$categories = [
    [
        'name'        => 'Student Member',
        'abbr'        => 'STUDENT',
        'is_member'   => true,
        'description' => 'NSE student members currently enrolled in an accredited engineering programme at a Nigerian university or polytechnic.',
        'p_early'     => 0,
        'p_standard'  => 0,
        'v_early'     => 0,
        'v_standard'  => 0,
    ],
    [
        'name'        => 'Graduate Member',
        'abbr'        => 'GRADUATE',
        'is_member'   => true,
        'description' => 'NSE graduate members who have completed an accredited undergraduate engineering degree and are in the early stage of professional practice.',
        'p_early'     => 0,
        'p_standard'  => 0,
        'v_early'     => 0,
        'v_standard'  => 0,
    ],
    [
        'name'        => 'Corporate Member',
        'abbr'        => 'CORPORATE',
        'is_member'   => true,
        'description' => 'Full corporate members (MNSE) of the Nigerian Society of Engineers — professionals who have met the required years of post-qualification experience.',
        'p_early'     => 0,
        'p_standard'  => 0,
        'v_early'     => 0,
        'v_standard'  => 0,
    ],
    [
        'name'        => 'Fellow',
        'abbr'        => 'FELLOW',
        'is_member'   => true,
        'description' => 'Fellows of the Nigerian Society of Engineers (FNSE) — the highest membership grade, conferred in recognition of distinguished engineering contribution.',
        'p_early'     => 0,
        'p_standard'  => 0,
        'v_early'     => 0,
        'v_standard'  => 0,
    ],
    [
        'name'        => 'Non-Member',
        'abbr'        => 'NON_MEMBER',
        'is_member'   => false,
        'description' => 'Engineers, technical professionals, and industry guests who are not current NSE members. We encourage membership upgrade at the event.',
        'p_early'     => 0,
        'p_standard'  => 0,
        'v_early'     => 0,
        'v_standard'  => 0,
    ],
];

$earlyBirdDeadline = '2026-04-28';
$earlyBirdActive   = now()->lt(\Carbon\Carbon::parse($earlyBirdDeadline)->endOfDay());
@endphp


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 1 — PAGE HEADER
     Breadcrumb + H1 + compact Early Bird countdown
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 border-b border-nse-neutral-200" aria-labelledby="pricing-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10 py-10 sm:py-12">

        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="mb-4">
            <ol class="flex items-center gap-2 text-xs text-nse-neutral-400" role="list">
                <li><a href="{{ route('home') }}" class="hover:text-nse-green-700 transition-colors focus:outline-none focus:underline">Home</a></li>
                <li aria-hidden="true"><span class="text-nse-neutral-200">/</span></li>
                <li class="text-nse-neutral-600 font-medium" aria-current="page">Registration Pricing</li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

            {{-- Heading block --}}
            <div>
                <p class="text-nse-gold-700 text-xs font-bold uppercase tracking-widest mb-2">Registration Fees</p>
                <h1 id="pricing-heading" class="text-2xl sm:text-3xl font-bold text-nse-neutral-900 mb-1">
                    Pricing &amp; Registration Categories
                </h1>
                <p class="text-nse-neutral-400 text-sm sm:text-base">
                    NSE 59th AGM &amp; International Conference &middot; November 1–4, 2026
                </p>
            </div>

            {{-- Compact Early Bird Countdown --}}
            @if($earlyBirdActive)
            <div
                x-data="{
                    days: '00', hours: '00', mins: '00', secs: '00',
                    urgency: 'gold',
                    init() { this.tick(); setInterval(() => this.tick(), 1000); },
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
                class="flex-shrink-0 bg-white border border-nse-neutral-200 rounded-lg px-5 py-4 shadow-sm"
                role="timer"
                aria-label="Early bird deadline countdown"
            >
                <p class="text-nse-neutral-400 text-xs font-semibold uppercase tracking-widest mb-2 text-center">Early Bird Ends In</p>
                <div class="flex items-center gap-1 sm:gap-2">
                    <div class="text-center min-w-[2.5rem]">
                        <span x-text="days" class="block text-2xl font-bold"
                            :class="{ 'text-nse-error': urgency==='red', 'text-nse-warning': urgency==='orange', 'text-nse-gold-500': urgency==='gold' }">00</span>
                        <span class="text-nse-neutral-400 text-[9px] uppercase tracking-wide">Days</span>
                    </div>
                    <span class="text-nse-neutral-200 text-xl font-light pb-3" aria-hidden="true">:</span>
                    <div class="text-center min-w-[2.5rem]">
                        <span x-text="hours" class="block text-2xl font-bold"
                            :class="{ 'text-nse-error': urgency==='red', 'text-nse-warning': urgency==='orange', 'text-nse-gold-500': urgency==='gold' }">00</span>
                        <span class="text-nse-neutral-400 text-[9px] uppercase tracking-wide">Hrs</span>
                    </div>
                    <span class="text-nse-neutral-200 text-xl font-light pb-3" aria-hidden="true">:</span>
                    <div class="text-center min-w-[2.5rem]">
                        <span x-text="mins" class="block text-2xl font-bold"
                            :class="{ 'text-nse-error': urgency==='red', 'text-nse-warning': urgency==='orange', 'text-nse-gold-500': urgency==='gold' }">00</span>
                        <span class="text-nse-neutral-400 text-[9px] uppercase tracking-wide">Mins</span>
                    </div>
                    <span class="text-nse-neutral-200 text-xl font-light pb-3" aria-hidden="true">:</span>
                    <div class="text-center min-w-[2.5rem]">
                        <span x-text="secs" class="block text-2xl font-bold"
                            :class="{ 'text-nse-error': urgency==='red', 'text-nse-warning': urgency==='orange', 'text-nse-gold-500': urgency==='gold' }">00</span>
                        <span class="text-nse-neutral-400 text-[9px] uppercase tracking-wide">Secs</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 2 — IMPORTANT NOTICES
     Pricing transparency: key rules explained upfront.
     Reduces payment anxiety and pricing misunderstanding.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-b border-nse-neutral-200 py-4" aria-label="Pricing notices">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10 space-y-3">

        {{-- Early bird lock rule — most critical --}}
        @if($earlyBirdActive)
        <div role="alert" class="flex items-start gap-3 border-l-4 border-nse-gold-500 bg-nse-gold-50 px-4 py-3 rounded-r-md">
            <svg class="w-5 h-5 text-nse-gold-700 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-nse-gold-700">Early Bird Pricing Active &mdash; Deadline: April 28, 2026</p>
                <p class="text-xs text-nse-gold-700/80 mt-0.5">Your price is locked at your <strong>registration date</strong>, not your payment date. Register now to secure the early bird rate, then complete payment at any time before October 25, 2026.</p>
            </div>
        </div>
        @endif

        {{-- Placeholder notice --}}
        <div class="flex items-start gap-3 border-l-4 border-nse-info bg-nse-info-bg px-4 py-3 rounded-r-md">
            <svg class="w-5 h-5 text-nse-info flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-nse-info">
                <strong>Registration fees are being finalised.</strong> All amounts shown as &#8358;0 are placeholders and will be updated by the NSE Secretariat before the portal launch on February 28, 2026. Registered participants will be notified of confirmed prices.
            </p>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 3 — PRICING TABLE
     Design spec:
     - Desktop: Full table (rows = categories, cols = Physical | Virtual)
     - Mobile: Horizontal scroll with gradient scroll hint
     - Early Bird badge per eligible cell
     - Category tooltip (? icon, Alpine.js tap/hover)
     - Highlighted row for active early bird
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-10 sm:py-12 border-b border-nse-neutral-200" aria-labelledby="table-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <div class="mb-6">
            <h2 id="table-heading" class="text-lg sm:text-xl font-bold text-nse-neutral-900">Registration Fees by Category</h2>
            <p class="text-nse-neutral-400 text-sm mt-1">All prices in Nigerian Naira (&#8358;) &middot; Inclusive of all taxes and levies &middot; Tap <span class="font-semibold text-nse-neutral-600">?</span> for category details</p>
        </div>

        {{-- Column legend (desktop) --}}
        <div class="hidden sm:flex items-center gap-4 mb-4 flex-wrap">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex px-2 py-0.5 bg-nse-gold-50 text-nse-gold-700 text-[10px] font-bold border border-nse-gold-500 rounded">EARLY BIRD</span>
                <span class="text-nse-neutral-400 text-xs">Rate until April 28, 2026</span>
            </div>
            <span class="text-nse-neutral-200 text-xs" aria-hidden="true">|</span>
            <div class="flex items-center gap-1.5">
                <span class="inline-flex px-2 py-0.5 bg-nse-neutral-100 text-nse-neutral-400 text-[10px] font-medium border border-nse-neutral-200 rounded">STANDARD</span>
                <span class="text-nse-neutral-400 text-xs">Rate from April 29 to October 25, 2026</span>
            </div>
        </div>

        {{-- ── TABLE WRAPPER: Mobile horizontal scroll with gradient hint ── --}}
        <div class="relative" x-data="{ canScroll: false }"
             x-init="canScroll = $refs.tableWrap.scrollWidth > $refs.tableWrap.clientWidth;
                     $refs.tableWrap.addEventListener('scroll', () => canScroll = ($refs.tableWrap.scrollLeft < $refs.tableWrap.scrollWidth - $refs.tableWrap.clientWidth - 10))">

            {{-- Scroll hint (mobile only) --}}
            <div x-show="canScroll"
                 class="sm:hidden absolute right-0 top-0 bottom-0 w-10 bg-gradient-to-l from-white to-transparent pointer-events-none z-10 flex items-center justify-end pr-1">
                <svg class="w-4 h-4 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>

            <div x-ref="tableWrap" class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
                <table class="w-full min-w-[640px] border-collapse text-sm" role="table" aria-label="Registration pricing by category and attendance type">

                    {{-- ── TABLE HEAD ── --}}
                    <thead>
                        <tr>
                            {{-- Category column --}}
                            <th scope="col"
                                class="text-left py-3 px-4 text-xs font-semibold text-nse-neutral-400 uppercase tracking-wider bg-nse-neutral-50 border border-nse-neutral-200 w-48 sticky left-0 z-10">
                                Category
                            </th>

                            {{-- Physical columns group --}}
                            <th scope="colgroup" colspan="2"
                                class="text-center py-2 px-4 text-xs font-bold text-nse-neutral-900 uppercase tracking-wider bg-nse-green-50 border border-nse-neutral-200 border-b-0">
                                <span class="flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Physical Attendance
                                </span>
                            </th>

                            {{-- Virtual columns group --}}
                            <th scope="colgroup" colspan="2"
                                class="text-center py-2 px-4 text-xs font-bold text-nse-neutral-900 uppercase tracking-wider bg-nse-gold-50 border border-nse-neutral-200 border-b-0">
                                <span class="flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82V15.18a1 1 0 01-1.447.91L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Virtual Attendance
                                </span>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="bg-nse-neutral-50 border border-nse-neutral-200 py-2 px-4 sticky left-0 z-10"></th>
                            {{-- Physical sub-headers --}}
                            <th scope="col" class="text-center py-2 px-4 text-[11px] font-semibold text-nse-gold-700 bg-nse-green-50 border border-nse-neutral-200 whitespace-nowrap">
                                Early Bird
                            </th>
                            <th scope="col" class="text-center py-2 px-4 text-[11px] font-semibold text-nse-neutral-400 bg-nse-neutral-50 border border-nse-neutral-200 whitespace-nowrap">
                                Standard
                            </th>
                            {{-- Virtual sub-headers --}}
                            <th scope="col" class="text-center py-2 px-4 text-[11px] font-semibold text-nse-gold-700 bg-nse-gold-50 border border-nse-neutral-200 whitespace-nowrap">
                                Early Bird
                            </th>
                            <th scope="col" class="text-center py-2 px-4 text-[11px] font-semibold text-nse-neutral-400 bg-nse-neutral-50 border border-nse-neutral-200 whitespace-nowrap">
                                Standard
                            </th>
                        </tr>
                    </thead>

                    {{-- ── TABLE BODY ── --}}
                    <tbody>
                        @foreach($categories as $index => $cat)
                        @php $isEven = $index % 2 === 0; @endphp
                        <tr
                            x-data="{ tooltipOpen: false }"
                            class="group {{ $isEven ? 'bg-white' : 'bg-nse-neutral-50/40' }} hover:bg-nse-green-50/50 transition-colors duration-100"
                        >
                            {{-- Category name + tooltip --}}
                            <td class="py-4 px-4 border border-nse-neutral-200 sticky left-0 z-10 {{ $isEven ? 'bg-white group-hover:bg-nse-green-50/50' : 'bg-nse-neutral-50/40 group-hover:bg-nse-green-50/50' }} transition-colors duration-100">
                                <div class="flex items-start gap-2">
                                    <div class="flex-1 min-w-0">
                                        <span class="block text-sm font-semibold text-nse-neutral-900 leading-tight">{{ $cat['name'] }}</span>
                                        @if($cat['is_member'])
                                            <span class="inline-flex mt-1 items-center gap-1 text-[10px] font-medium text-nse-green-700 bg-nse-green-50 px-1.5 py-0.5 rounded">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                NSE Member
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Tooltip trigger --}}
                                    <div class="relative flex-shrink-0">
                                        <button
                                            @click.stop="tooltipOpen = !tooltipOpen"
                                            @mouseenter="tooltipOpen = true"
                                            @mouseleave="tooltipOpen = false"
                                            :aria-expanded="tooltipOpen"
                                            aria-label="Who qualifies as {{ $cat['name'] }}?"
                                            class="w-5 h-5 rounded-full bg-nse-neutral-200 text-nse-neutral-600 hover:bg-nse-green-700 hover:text-white transition-colors flex items-center justify-center text-[10px] font-bold focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-1 flex-shrink-0 mt-0.5"
                                        >?</button>

                                        {{-- Tooltip content --}}
                                        <div
                                            x-show="tooltipOpen"
                                            x-cloak
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            @click.away="tooltipOpen = false"
                                            class="absolute left-0 top-7 z-20 w-56 bg-nse-neutral-900 text-white text-xs leading-relaxed rounded-lg p-3 shadow-lg"
                                            role="tooltip"
                                        >
                                            <div class="absolute -top-1.5 left-2.5 w-3 h-3 bg-nse-neutral-900 rotate-45 rounded-sm" aria-hidden="true"></div>
                                            {{ $cat['description'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Physical Early Bird --}}
                            <td class="py-4 px-4 border border-nse-neutral-200 text-center align-top">
                                @if($earlyBirdActive)
                                    <span class="inline-flex mb-1.5 px-1.5 py-0.5 bg-nse-gold-50 text-nse-gold-700 text-[9px] font-bold border border-nse-gold-500 rounded uppercase tracking-wide">Early Bird</span>
                                @endif
                                <span class="block text-xl font-bold text-nse-neutral-900">
                                    &#8358;{{ number_format($cat['p_early']) }}
                                </span>
                            </td>

                            {{-- Physical Standard --}}
                            <td class="py-4 px-4 border border-nse-neutral-200 text-center align-top bg-nse-neutral-50/30">
                                <span class="block text-xs text-nse-neutral-400 mb-1.5 mt-0.5">After Apr 28</span>
                                <span class="block text-base font-medium text-nse-neutral-400">
                                    &#8358;{{ number_format($cat['p_standard']) }}
                                </span>
                            </td>

                            {{-- Virtual Early Bird --}}
                            <td class="py-4 px-4 border border-nse-neutral-200 text-center align-top">
                                @if($earlyBirdActive)
                                    <span class="inline-flex mb-1.5 px-1.5 py-0.5 bg-nse-gold-50 text-nse-gold-700 text-[9px] font-bold border border-nse-gold-500 rounded uppercase tracking-wide">Early Bird</span>
                                @endif
                                <span class="block text-xl font-bold text-nse-neutral-900">
                                    &#8358;{{ number_format($cat['v_early']) }}
                                </span>
                            </td>

                            {{-- Virtual Standard --}}
                            <td class="py-4 px-4 border border-nse-neutral-200 text-center align-top bg-nse-neutral-50/30">
                                <span class="block text-xs text-nse-neutral-400 mb-1.5 mt-0.5">After Apr 28</span>
                                <span class="block text-base font-medium text-nse-neutral-400">
                                    &#8358;{{ number_format($cat['v_standard']) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    {{-- ── TABLE FOOTER: Registration deadline note ── --}}
                    <tfoot>
                        <tr>
                            <td colspan="5" class="py-3 px-4 border border-nse-neutral-200 bg-nse-neutral-50">
                                <p class="text-xs text-nse-neutral-400">
                                    <span class="font-semibold text-nse-neutral-600">Registration closes:</span> October 25, 2026 &middot;
                                    <span class="font-semibold text-nse-neutral-600">Early bird deadline:</span> April 28, 2026 (based on registration timestamp) &middot;
                                    All fees are non-refundable after 7 days of registration unless otherwise determined by NSE.
                                </p>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>

        {{-- Mobile scroll hint label --}}
        <p class="sm:hidden mt-2 text-xs text-nse-neutral-400 text-center flex items-center justify-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
            Scroll to see all columns
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </p>

    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 4 — WHAT'S INCLUDED
     Clarifies what each registration type includes.
     Reduces post-payment confusion.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="included-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="included-heading" class="text-lg font-bold text-nse-neutral-900 mb-6">What's Included in Your Registration</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Physical --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4 pb-4 border-b border-nse-neutral-200">
                    <div class="w-7 h-7 bg-nse-green-50 rounded-md flex items-center justify-center flex-shrink-0" aria-hidden="true">
                        <svg class="w-4 h-4 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-nse-neutral-900">Physical Attendance Includes</h3>
                </div>
                <ul class="space-y-2.5" role="list">
                    @foreach([
                        'Full access to all plenary sessions (4 days)',
                        'Access to all technical tracks and workshops',
                        'Exhibition floor and networking events',
                        'Conference bag and materials',
                        'QR-based accreditation ticket',
                        'CPD certificate (upon confirmed attendance)',
                        'Access to online session recordings post-event',
                    ] as $item)
                    <li class="flex items-start gap-2.5 text-sm text-nse-neutral-600">
                        <svg class="w-4 h-4 text-nse-green-700 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Virtual --}}
            <div class="bg-white rounded-lg border border-nse-neutral-200 p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4 pb-4 border-b border-nse-neutral-200">
                    <div class="w-7 h-7 bg-nse-gold-50 rounded-md flex items-center justify-center flex-shrink-0" aria-hidden="true">
                        <svg class="w-4 h-4 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82V15.18a1 1 0 01-1.447.91L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-nse-neutral-900">Virtual Attendance Includes</h3>
                </div>
                <ul class="space-y-2.5" role="list">
                    @foreach([
                        'Live stream access (Zoom, Jitsi, or YouTube — admin-selectable)',
                        'Secure access link sent to registered email',
                        'Attend from anywhere in Nigeria or abroad',
                        'Session participation tracked server-side',
                        'CPD certificate (minimum 10 consecutive minutes required)',
                        'Digital accreditation ticket with QR code',
                        'Access to session recordings post-event',
                    ] as $item)
                    <li class="flex items-start gap-2.5 text-sm text-nse-neutral-600">
                        <svg class="w-4 h-4 text-nse-gold-700 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 5 — PRICING FAQ
     Alpine.js accordion. 5 common questions.
     Reduces support load. Reduces payment/pricing anxiety.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 sm:py-14 border-b border-nse-neutral-200" aria-labelledby="faq-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        <h2 id="faq-heading" class="text-lg font-bold text-nse-neutral-900 mb-6">Pricing &amp; Registration FAQs</h2>

        @php
        $faqs = [
            [
                'q' => 'When does early bird pricing end?',
                'a' => 'Early bird pricing is available for registrations completed before April 28, 2026 (11:59 PM). The date your registration is submitted — not the date you complete payment — determines your price tier. If you register before April 28, your early bird rate is locked even if you pay after that date.',
            ],
            [
                'q' => 'Can I register now and pay later?',
                'a' => 'Yes. Once you complete the registration form and verify your email, your registration is recorded and your price is locked. You can return to complete payment at any time before the registration close date of October 25, 2026.',
            ],
            [
                'q' => 'How do I prove my NSE membership?',
                'a' => 'NSE membership is self-declared during registration. You will be required to enter your NSE membership number (format: NSE-XXXXX) and select your membership category. Misrepresentation of membership status is a disciplinary matter under NSE rules.',
            ],
            [
                'q' => 'What is the refund policy?',
                'a' => 'Refund requests may be submitted within 7 days of payment. Refunds are processed via the same payment method used at registration. After 7 days, no refunds are provided unless otherwise determined by the NSE Secretariat. Approved refunds will revoke your accreditation ticket.',
            ],
            [
                'q' => 'Is there a group or corporate registration option?',
                'a' => 'Corporate group registrations (5 or more delegates from the same organisation) are not available through this portal at this time. Please contact the NSE Secretariat directly for group registration arrangements.',
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
                    <p class="text-sm text-nse-neutral-600 leading-relaxed pt-4">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <p class="mt-4 text-sm text-nse-neutral-400">
            More questions?
            <a href="/faqs" class="text-nse-green-700 font-medium hover:underline focus:outline-none focus:underline">View all FAQs</a>
            or
            <a href="/contact" class="text-nse-green-700 font-medium hover:underline focus:outline-none focus:underline">contact the Secretariat</a>.
        </p>

    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════════
     SECTION 6 — REGISTRATION CTA BAND
     NSE Green 700. Reinforces urgency. Primary + secondary CTA.
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-green-700 py-12 sm:py-14 text-center" aria-label="Registration call to action">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10">
        @if($earlyBirdActive)
            <span class="inline-flex items-center gap-1.5 mb-4 px-3 py-1 bg-nse-gold-50/20 text-white text-xs font-semibold border border-white/20 rounded-full">
                <span class="w-1.5 h-1.5 bg-nse-gold-500 rounded-full" aria-hidden="true"></span>
                Early Bird Active &mdash; Ends April 28, 2026
            </span>
        @endif
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">
            Ready to secure your place at the 59th NSE AGM?
        </h2>
        <p class="text-white/70 text-base mb-8 max-w-xl mx-auto">
            Register now. Your price is locked at registration — pay anytime before October 25, 2026.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a
                href="{{ route('register') }}"
                class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
            >
                Register Now
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center justify-center px-8 py-3.5 bg-transparent text-white text-base font-semibold rounded-md border-2 border-white/50 hover:border-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
            >
                Back to Home
            </a>
        </div>
    </div>
</section>

@endsection
