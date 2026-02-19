@extends('layouts.public')

@section('title', 'Certificate')

@section('content')
    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10 py-10">
            <h1 class="text-2xl font-bold text-nse-neutral-900">Certificate</h1>
            <p class="text-sm text-nse-neutral-600 mt-2">Download your CPD certificate once eligible.</p>

            @if (!empty($error))
                <div class="mt-6 p-4 bg-red-100 text-red-800 rounded">
                    {{ $error }}
                </div>
            @else
                <div class="mt-6 p-4 bg-nse-neutral-50 border border-nse-neutral-200 rounded">
                    <p class="text-sm text-nse-neutral-700"><strong>Participant:</strong> {{ $registration->name }}</p>
                    <p class="text-sm text-nse-neutral-700"><strong>Status:</strong>
                        @if ($certificate)
                            Certificate issued ({{ $certificate->certificate_id }})
                        @elseif ($eligible)
                            Eligible — available after {{ $eventEndAt->format('M d, Y H:i') }}
                        @else
                            Not eligible yet (complete 10 minutes virtual or physical check-in)
                        @endif
                    </p>
                </div>

                @if ($certificate || $eligible)
                    <form method="POST" action="{{ route('certificate.download') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="token" value="{{ request('token') }}">
                        <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Download Certificate</button>
                    </form>
                @endif

                @if ($certificate)
                    <p class="mt-4 text-sm text-nse-neutral-600">
                        Public verification:
                        <a href="{{ route('certificate.verify', $certificate->certificate_id) }}" class="text-nse-green-700 underline">
                            {{ route('certificate.verify', $certificate->certificate_id) }}
                        </a>
                    </p>
                @endif
            @endif
        </div>
    </section>
@endsection
