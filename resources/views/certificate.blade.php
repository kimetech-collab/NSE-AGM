@extends('layouts.public')

@section('title', 'Certificate')

@section('content')
    <section class="bg-nse-neutral-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 py-10">
            <h1 class="text-2xl font-bold text-nse-neutral-900">Certificate</h1>
            <p class="text-sm text-nse-neutral-600 mt-2">Preview and download your CPD certificate once eligible.</p>

            @if (!empty($error))
                <div class="mt-6 p-4 bg-red-100 text-red-800 rounded">
                    {{ $error }}
                </div>
            @else
                <div class="mt-6 p-4 bg-white border border-nse-neutral-200 rounded-lg">
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

                @if ($certificate)
                    <div class="mt-6 bg-white border-2 border-nse-green-700 rounded-xl p-6 shadow-sm overflow-hidden">
                        <div class="border border-yellow-600/70 rounded-lg p-6">
                            <div class="text-center text-xs uppercase tracking-wide text-nse-neutral-600 font-english-gothic">Nigerian Society of Engineers</div>
                            <h2 class="text-center text-3xl md:text-4xl font-bold text-nse-green-700 mt-2">Certificate of Participation</h2>
                            <p class="text-center text-sm text-nse-neutral-700 mt-1">NSE 59th Annual General Meeting & International Conference {{ \App\Support\EventDates::get('event_end_at')->year }}</p>

                            <div class="mt-6 text-center">
                                <p class="text-sm text-nse-neutral-600">This certificate is proudly presented to</p>
                                <p class="text-2xl md:text-3xl font-bold text-nse-neutral-900 mt-2">{{ $registration->name }}</p>
                                <div class="w-2/3 mx-auto border-t border-yellow-600 mt-2"></div>
                                <p class="text-sm text-nse-neutral-700 mt-4 max-w-3xl mx-auto">
                                    for successful participation in the NSE 59th AGM & International Conference,
                                    having satisfied the attendance requirements for Continuing Professional Development (CPD).
                                </p>
                            </div>

                            <div class="mt-6 text-center text-sm text-nse-neutral-700">
                                <span class="font-semibold text-nse-neutral-900">Certificate ID:</span> {{ $certificate->certificate_id }}
                                <span class="mx-2">•</span>
                                <span class="font-semibold text-nse-neutral-900">Date Issued:</span> {{ optional($certificate->issued_at)->format('M d, Y') }}
                                @if (!empty($registration->membership_number))
                                    <span class="mx-2">•</span>
                                    <span class="font-semibold text-nse-neutral-900">Membership No:</span> {{ $registration->membership_number }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if ($certificate || $eligible)
                    <div class="mt-6 flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('certificate.download') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ request('token') }}">
                            <button class="px-4 py-2 bg-nse-green-700 text-white rounded-lg">Download Certificate (PDF)</button>
                        </form>

                        @if ($certificate)
                            <a href="{{ route('certificate.verify', $certificate->certificate_id) }}" class="px-4 py-2 border border-nse-neutral-300 rounded-lg text-nse-neutral-700 hover:bg-nse-neutral-100">
                                Public Verification Page
                            </a>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
