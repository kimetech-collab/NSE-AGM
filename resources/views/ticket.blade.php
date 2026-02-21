@extends('layouts.public')

@section('title', 'Your Accreditation Ticket — NSE 59th AGM & Conference')

@section('content')

@php
    $eventStartAt = \App\Support\EventDates::get('event_start_at');
    $eventEndAt = \App\Support\EventDates::get('event_end_at');
    $statusColor = match(strtolower($registration->payment_status ?? 'unknown')) {
        'paid' => ['bg' => 'nse-green-50', 'text' => 'nse-green-700', 'badge' => 'nse-green-100', 'label' => 'Valid'],
        'pending', 'processing', 'initialized' => ['bg' => 'nse-warning-bg', 'text' => 'nse-warning', 'badge' => 'nse-warning-bg', 'label' => 'Pending'],
        'refunded' => ['bg' => 'nse-neutral-50', 'text' => 'nse-neutral-600', 'badge' => 'nse-neutral-100', 'label' => 'Refunded'],
        default => ['bg' => 'nse-info-bg', 'text' => 'nse-info', 'badge' => 'nse-info-bg', 'label' => 'Pending'],
    };
@endphp

<section class="bg-nse-neutral-50 py-8 sm:py-12" aria-labelledby="ticket-heading">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-nse-gold-700 text-xs font-bold uppercase tracking-widest mb-1">Accreditation</p>
            <h1 id="ticket-heading" class="text-2xl sm:text-3xl font-bold text-nse-neutral-900 leading-tight">Your Accreditation Ticket</h1>
            <p class="text-nse-neutral-500 text-sm mt-2">NSE 59th Annual General Meeting & International Conference · {{ $eventStartAt->format('F j') }}–{{ $eventEndAt->format('j, Y') }}</p>
        </div>

        {{-- Main Ticket Card (QR + Details Two-column on desktop, stacked on mobile) --}}
        <div class="bg-white rounded-lg shadow-md border border-nse-neutral-200 overflow-hidden">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-0">

                {{-- Left Column: Participant Details --}}
                <div class="md:col-span-2 p-6 sm:p-8 space-y-6">

                    {{-- Status Badge --}}
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-1">Payment Status</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-{{ $statusColor['badge'] }} text-{{ $statusColor['text'] }} text-sm font-semibold rounded-full">
                                    <span class="w-2 h-2 rounded-full bg-current"></span>
                                    {{ $statusColor['label'] }}
                                </span>
                                @if($registration->payment_status === 'paid')
                                    <svg class="w-5 h-5 text-nse-green-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Participant Info --}}
                    <div class="space-y-4 pt-3 border-t border-nse-neutral-200">
                        <!-- Profile Photo -->
                        @if($registration->profile_photo)
                        <div class="flex items-center gap-4">
                            <img src="{{ $registration->profilePhotoUrl() }}" alt="{{ $registration->name }}" class="w-20 h-20 rounded-lg object-cover border-2 border-nse-green-200">
                            <div>
                                <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-1">Participant Photo</p>
                                <p class="text-sm text-nse-neutral-700">For identification purposes</p>
                            </div>
                        </div>
                        @endif

                        <div>
                            <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-1">Participant Name</p>
                            <p class="text-2xl font-bold text-nse-neutral-900">{{ $registration->name }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-1">Email Address</p>
                            <p class="text-sm text-nse-neutral-700 font-mono break-all">{{ $registration->email }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-1">Ticket ID</p>
                                <p class="font-mono text-sm font-semibold text-nse-neutral-900">NSE59-{{ str_pad((string) $registration->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-1">Attendance</p>
                                <p class="text-sm font-semibold text-nse-neutral-900 capitalize">{{ $registration->attendance ?? 'Physical' }}</p>
                            </div>
                        </div>

                        @if($registration->is_member)
                        <div>
                            <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-1">NSE Membership</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-nse-green-50 text-nse-green-700 text-xs font-semibold rounded">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    {{ ucfirst($registration->membership_category ?? 'Member') }}
                                </span>
                                @if($registration->membership_number)
                                    <code class="text-xs text-nse-neutral-600 font-mono">{{ $registration->membership_number }}</code>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Accreditation Instructions --}}
                    <div class="p-4 bg-nse-green-50 border border-nse-green-200 rounded-lg">
                        <p class="text-sm font-semibold text-nse-green-900 mb-1">📋 Check-In Instructions</p>
                        <p class="text-xs text-nse-green-800 leading-relaxed">
                            Present this ticket and your QR code at the registration desk on arrival. Your ticket will be scanned for accreditation. Please have a valid ID ready.
                        </p>
                    </div>

                </div>

                {{-- Right Column: QR Code --}}
                <div class="bg-nse-neutral-50 p-6 sm:p-8 flex flex-col items-center justify-center md:border-l border-nse-neutral-200">
                    <div class="w-full max-w-xs">
                        <p class="text-xs text-nse-neutral-500 uppercase tracking-widest mb-3 text-center font-semibold">Scan for Accreditation</p>

                        @if(!empty($registration->ticket_token))
                            {{-- QR Code Container --}}
                            <div class="bg-white p-4 border-2 border-nse-gold-500 rounded-lg mb-4 flex items-center justify-center" id="qr-container">
                                {{-- QR will be generated here via JavaScript --}}
                                <canvas id="qr-canvas" style="max-width: 100%; height: auto;"></canvas>
                            </div>

                            {{-- Ticket Token (small) --}}
                            <div class="bg-nse-neutral-100 rounded p-3 mb-4 break-all">
                                <p class="font-mono text-[10px] text-nse-neutral-600 text-center leading-tight">{{ $registration->ticket_token }}</p>
                            </div>

                            <p class="text-[10px] text-nse-neutral-500 text-center">Token ID</p>
                        @else
                            <div class="flex items-center justify-center w-full aspect-square bg-nse-neutral-100 rounded-lg border-2 border-dashed border-nse-neutral-300 mb-4">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-nse-neutral-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm text-nse-neutral-600 font-medium">QR not issued</p>
                                </div>
                            </div>
                            <p class="text-xs text-nse-neutral-500 text-center">Your QR code will be generated after payment is confirmed.</p>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            @if($registration->payment_status === 'paid' && !empty($registration->ticket_token))
            <form method="POST" action="{{ route('ticket.download') }}" class="flex">
                @csrf
                <input type="hidden" name="token" value="{{ $registration->ticket_token }}" />
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-nse-green-700 text-white text-sm font-semibold rounded-lg hover:bg-nse-green-900 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l-4-2m4 2l4-2"/></svg>
                    Download Ticket PDF
                </button>
            </form>
            @endif

            <a
                href="{{ route('home') }}"
                class="inline-flex items-center justify-center px-6 py-3 bg-nse-neutral-200 text-nse-neutral-900 text-sm font-semibold rounded-lg hover:bg-nse-neutral-300 focus:outline-none focus:ring-2 focus:ring-nse-neutral-700 focus:ring-offset-2 transition-colors"
            >
                Back to Home
            </a>
        </div>

        {{-- Additional Info --}}
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="p-5 bg-white rounded-lg border border-nse-neutral-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-nse-green-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-nse-neutral-900">Digital & Physical</h3>
                </div>
                <p class="text-xs text-nse-neutral-600 leading-relaxed">
                    Your ticket works both digitally and as a PDF printout. Keep this page or PDF on your phone for quick scanning.
                </p>
            </div>

            <div class="p-5 bg-white rounded-lg border border-nse-neutral-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-nse-gold-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-gold-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-nse-neutral-900">Event Dates</h3>
                </div>
                <p class="text-xs text-nse-neutral-600 leading-relaxed">
                    <strong>{{ $eventStartAt->format('F j') }}–{{ $eventEndAt->format('j, Y') }}</strong><br/>
                    Maiduguri, Borno State, Nigeria
                </p>
            </div>

            <div class="p-5 bg-white rounded-lg border border-nse-neutral-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-nse-info-bg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-nse-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-nse-neutral-900">Questions?</h3>
                </div>
                <p class="text-xs text-nse-neutral-600 leading-relaxed">
                    <a href="/faqs" class="text-nse-green-700 underline hover:no-underline">View FAQs</a> or <a href="/contact" class="text-nse-green-700 underline hover:no-underline">contact support</a>
                </p>
            </div>

        </div>

    </div>
</section>

<script>
    // Generate QR code using jsQR library
    @if(!empty($registration->ticket_token))
    (function() {
        // Use QR code generation library
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
        script.onload = function() {
            const container = document.getElementById('qr-container');
            const canvas = container.querySelector('canvas');
            if (canvas) {
                canvas.remove();
            }
            new QRCode(container, {
                text: '{{ $registration->ticket_token }}',
                width: 256,
                height: 256,
                colorDark: '#2B6B2B',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        };
        document.head.appendChild(script);
    })();
    @endif
</script>

@endsection
