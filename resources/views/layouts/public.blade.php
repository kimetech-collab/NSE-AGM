<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="NSE 59th Annual General Meeting & International Conference — Register, pay and manage your attendance online.">
    <title>@yield('title', 'NSE 59th AGM & International Conference')</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    {{-- Inter: superior screen readability for 24–65 age range (WCAG AA) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&display=swap" rel="stylesheet">

    <style>
        .font-english-gothic {
            font-family: 'UnifrakturCook', 'Old English Text MT', 'Blackletter', 'Cloister Black', serif;
            letter-spacing: 0.3px;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-white font-sans text-nse-neutral-900 antialiased">
@php
    $eventStartAt = \App\Support\EventDates::get('event_start_at');
    $eventEndAt = \App\Support\EventDates::get('event_end_at');
@endphp

{{-- ═══════════════════════════════════════════════════════
     STICKY NAVIGATION
     Mobile-first: hamburger below md, full nav at md+
     Alpine.js handles mobile menu toggle
═══════════════════════════════════════════════════════ --}}
<header
    x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
    :class="scrolled ? 'shadow-md' : 'shadow-sm'"
    class="sticky top-0 z-50 bg-white border-b border-nse-neutral-200 transition-shadow duration-200"
    role="banner"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
        <nav class="flex items-center justify-between h-16 lg:h-18" aria-label="Main navigation">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0" aria-label="NSE AGM Portal — Home">
                <div class="w-10 h-10 bg-nse-green-700 rounded-md flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <span class="text-white font-bold text-sm leading-none">NSE</span>
                </div>
                <div class="hidden sm:block">
                    <span class="block text-nse-green-700 font-bold text-sm leading-tight font-english-gothic">Nigerian Society of Engineers</span>
                    <span class="block text-nse-neutral-400 text-xs leading-tight">59th AGM &amp; Conference {{ $eventEndAt->year }}</span>
                </div>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden lg:flex items-center gap-1" role="list">
                <a href="{{ route('home') }}"          role="listitem" class="px-3 py-2 text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700">Home</a>
                <a href="/about"                        role="listitem" class="px-3 py-2 text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700">About</a>
                <a href="/programme"                    role="listitem" class="px-3 py-2 text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700">Programme</a>
                <a href="{{ route('pricing') }}"        role="listitem" class="px-3 py-2 text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700">Pricing</a>
                <a href="/venue"                        role="listitem" class="px-3 py-2 text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700">Venue</a>
                <a href="/sponsors"                     role="listitem" class="px-3 py-2 text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700">Sponsors</a>
                <a href="/faqs"                         role="listitem" class="px-3 py-2 text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700">FAQs</a>
            </div>

            {{-- Desktop CTA --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 transition-colors px-3 py-2">My Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-nse-neutral-600 hover:text-nse-green-700 transition-colors px-3 py-2">Sign In</a>
                @endauth
                <a
                    href="{{ route('register') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-nse-green-700 text-white text-sm font-semibold rounded-md hover:bg-nse-green-900 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 transition-colors duration-150 min-w-[120px] justify-center"
                >
                    Register Now
                </a>
            </div>

            {{-- Mobile: Register CTA (always visible) + Hamburger --}}
            <div class="flex items-center gap-3 lg:hidden">
                <a
                    href="{{ route('register') }}"
                    class="inline-flex items-center px-4 py-2 bg-nse-green-700 text-white text-sm font-semibold rounded-md hover:bg-nse-green-900 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-1 transition-colors duration-150"
                >
                    Register
                </a>
                <button
                    @click="open = !open"
                    :aria-expanded="open"
                    aria-controls="mobile-menu"
                    aria-label="Toggle navigation menu"
                    class="p-2 rounded-md text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-nse-green-700 transition-colors"
                >
                    {{-- Hamburger icon --}}
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    {{-- Close icon --}}
                    <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </nav>
    </div>

    {{-- Mobile Menu --}}
    <div
        id="mobile-menu"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="lg:hidden border-t border-nse-neutral-200 bg-white"
        role="navigation"
        aria-label="Mobile navigation"
    >
        <div class="px-4 py-3 space-y-1">
            <a @click="open = false" href="{{ route('home') }}"    class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">Home</a>
            <a @click="open = false" href="/about"                  class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">About AGM</a>
            <a @click="open = false" href="/programme"              class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">Programme</a>
            <a @click="open = false" href="{{ route('pricing') }}"  class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">Pricing</a>
            <a @click="open = false" href="/venue"                  class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">Venue</a>
            <a @click="open = false" href="/sponsors"               class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">Sponsors</a>
            <a @click="open = false" href="/faqs"                   class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">FAQs</a>
            <div class="border-t border-nse-neutral-200 pt-3 mt-3">
                @auth
                    <a @click="open = false" href="{{ url('/dashboard') }}" class="block px-4 py-3 text-base font-medium text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">My Dashboard</a>
                @else
                    <a @click="open = false" href="{{ route('login') }}"    class="block px-4 py-3 text-base font-medium text-nse-neutral-600 hover:text-nse-green-700 hover:bg-nse-green-50 rounded-md transition-colors">Sign In</a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- ═══════════════════════════════════════════════════════
     PAGE CONTENT
═══════════════════════════════════════════════════════ --}}
<main id="main-content" role="main">
    @if(session('success'))
        <div role="alert" class="border-l-4 border-nse-success bg-nse-success-bg text-nse-success px-6 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div role="alert" class="border-l-4 border-nse-error bg-nse-error-bg text-nse-error px-6 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @yield('content')
</main>

{{-- ═══════════════════════════════════════════════════════
     FOOTER
     NSE Green 900 background — institutional, authoritative
═══════════════════════════════════════════════════════ --}}
<footer class="bg-nse-green-900 text-white" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">

        {{-- Main footer grid --}}
        <div class="py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Brand column --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white/10 rounded-md flex items-center justify-center flex-shrink-0 border border-white/20">
                        <span class="text-white font-bold text-sm">NSE</span>
                    </div>
                    <div>
                        <span class="block text-white font-bold text-sm leading-tight font-english-gothic">Nigerian Society of Engineers</span>
                        <span class="block text-white/60 text-xs leading-tight">59th AGM {{ $eventEndAt->year }}</span>
                    </div>
                </div>
                <p class="text-white/70 text-sm leading-relaxed">
                    Official portal for registration, payments, and participation in the 59th Annual General Meeting &amp; International Conference.
                </p>
            </div>

            {{-- Event Links --}}
            <nav aria-label="Event navigation">
                <h3 class="text-white/50 text-xs font-semibold uppercase tracking-widest mb-4">Event</h3>
                <ul class="space-y-3">
                    <li><a href="/about"       class="text-white/80 text-sm hover:text-white transition-colors">About the AGM</a></li>
                    <li><a href="/programme"   class="text-white/80 text-sm hover:text-white transition-colors">Programme</a></li>
                    <li><a href="/sponsors"    class="text-white/80 text-sm hover:text-white transition-colors">Sponsors</a></li>
                    <li><a href="/venue"       class="text-white/80 text-sm hover:text-white transition-colors">Venue &amp; Travel</a></li>
                </ul>
            </nav>

            {{-- Portal Links --}}
            <nav aria-label="Portal navigation">
                <h3 class="text-white/50 text-xs font-semibold uppercase tracking-widest mb-4">Portal</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('register') }}"  class="text-white/80 text-sm hover:text-white transition-colors">Register</a></li>
                    <li><a href="{{ route('pricing') }}"   class="text-white/80 text-sm hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="{{ route('login') }}"     class="text-white/80 text-sm hover:text-white transition-colors">Participant Sign In</a></li>
                    <li><a href="/faqs"                    class="text-white/80 text-sm hover:text-white transition-colors">FAQs</a></li>
                </ul>
            </nav>

            {{-- Support --}}
            <nav aria-label="Support navigation">
                <h3 class="text-white/50 text-xs font-semibold uppercase tracking-widest mb-4">Support</h3>
                <ul class="space-y-3">
                    <li><a href="/contact"   class="text-white/80 text-sm hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="/terms"     class="text-white/80 text-sm hover:text-white transition-colors">Terms &amp; Privacy</a></li>
                </ul>
                <div class="mt-6">
                    <p class="text-white/50 text-xs mb-1">Event Dates</p>
                    <p class="text-white/90 text-sm font-medium">{{ $eventStartAt->format('F j') }} – {{ $eventEndAt->format('j, Y') }}</p>
                    <p class="text-white/60 text-xs mt-1">Maiduguri, Borno State</p>
                </div>
            </nav>
        </div>

        {{-- Footer bottom bar --}}
        <div class="border-t border-white/10 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-white/50 text-xs text-center sm:text-left">
                &copy; {{ date('Y') }} <span class="font-english-gothic">Nigerian Society of Engineers</span>. All rights reserved.
            </p>
            <p class="text-white/40 text-xs text-center">
                Secure portal powered by Paystack &middot; WCAG AA compliant
            </p>
        </div>
    </div>
</footer>

@fluxScripts
@stack('scripts')
</body>
</html>
