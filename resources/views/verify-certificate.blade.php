@extends('layouts.public')

@section('title', 'Certificate Verification')

@section('content')
    @php
        $eventEndAt = \App\Support\EventDates::get('event_end_at');
    @endphp
    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10 py-10">
            <h1 class="text-2xl font-bold text-nse-neutral-900">Certificate Verification</h1>
            <p class="text-sm text-nse-neutral-600 mt-2">Validate certificate authenticity using the public certificate ID.</p>

            <form method="GET" action="{{ route('certificate.verify.lookup') }}" class="mt-6 flex gap-2">
                <input
                    type="text"
                    name="certificate_id"
                    value="{{ $lookup ?? '' }}"
                    placeholder="Enter certificate ID (e.g. NSE59-2026-ABC123)"
                    class="flex-1 border border-nse-neutral-200 rounded px-3 py-2 text-sm"
                >
                <button class="px-4 py-2 bg-nse-green-700 text-white rounded text-sm">Verify</button>
            </form>

            @if (!empty($error))
                <div class="mt-6 p-4 bg-red-100 text-red-800 rounded">
                    {{ $error }}
                </div>
            @elseif (! $certificate && !empty($lookup))
                <div class="mt-6 p-4 bg-red-100 text-red-800 rounded">
                    Certificate not found.
                </div>
            @elseif ($certificate)
                <div class="mt-6 p-4 bg-nse-neutral-50 border border-nse-neutral-200 rounded">
                    <p class="text-sm text-nse-neutral-700"><strong>Certificate ID:</strong> {{ $certificate->certificate_id }}</p>
                    <p class="text-sm text-nse-neutral-700"><strong>Participant:</strong> {{ $certificate->registration->name ?? 'Unknown' }}</p>
                    <p class="text-sm text-nse-neutral-700"><strong>Event:</strong> NSE 59th Annual General Meeting & International Conference {{ $eventEndAt->year }}</p>
                    <p class="text-sm text-nse-neutral-700"><strong>Issued:</strong> {{ optional($certificate->issued_at)->format('M d, Y') }}</p>
                    <p class="text-sm text-nse-neutral-700"><strong>Status:</strong> {{ ucfirst($certificate->status) }}</p>
                </div>
            @else
                <div class="mt-6 p-4 bg-nse-neutral-50 border border-nse-neutral-200 rounded text-sm text-nse-neutral-700">
                    Enter a certificate ID to begin verification.
                </div>
            @endif
        </div>
    </section>
@endsection
