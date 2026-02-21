@extends('layouts.app')

@section('title', 'My Registration Status — NSE 59th AGM & Conference')

@section('content')
<div class="py-8 max-w-3xl mx-auto px-4 sm:px-6">
    
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-nse-neutral-900">My Registration Status</h1>
        <p class="text-nse-neutral-600 mt-2">Track your journey through registration, payment, attendance, and certificates.</p>
    </div>

    {{-- Linear Status Tracker --}}
    <div class="bg-white rounded-lg border border-nse-neutral-200 p-8 mb-8">
        
        {{-- 4-Step Tracker --}}
        <div class="space-y-6">

            {{-- Step 1: Registered --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-nse-green-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @if ($nextStep > 1)
                        <div class="w-1 h-12 bg-nse-green-200 my-1"></div>
                    @else
                        <div class="w-1 h-12 bg-nse-neutral-200 my-1"></div>
                    @endif
                </div>
                <div class="flex-1 pt-1.5">
                    <h3 class="text-lg font-semibold text-nse-neutral-900">Registration Submitted</h3>
                    <p class="text-sm text-nse-neutral-600 mt-1">Your registration details have been recorded.</p>
                    <div class="mt-3 flex items-center gap-2 text-sm text-nse-neutral-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/></svg>
                        <span>{{ $registration->created_at->format('M d, Y · H:i A') }}</span>
                    </div>
                </div>
            </div>

            {{-- Step 2: Email Verified --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full {{ $registration->email_verified_at ? 'bg-nse-green-100' : 'bg-nse-neutral-100' }} flex items-center justify-center flex-shrink-0">
                        @if ($registration->email_verified_at)
                            <svg class="w-6 h-6 text-nse-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    @if ($nextStep > 2)
                        <div class="w-1 h-12 bg-nse-green-200 my-1"></div>
                    @else
                        <div class="w-1 h-12 bg-nse-neutral-200 my-1"></div>
                    @endif
                </div>
                <div class="flex-1 pt-1.5">
                    <h3 class="text-lg font-semibold text-nse-neutral-900">Email Verified</h3>
                    <p class="text-sm text-nse-neutral-600 mt-1">Your email address has been confirmed. You can now proceed to payment.</p>
                    @if ($registration->email_verified_at)
                        <div class="mt-3 flex items-center gap-2 text-sm text-nse-green-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/></svg>
                            <span>{{ \Carbon\Carbon::parse($registration->email_verified_at)->format('M d, Y · H:i A') }}</span>
                        </div>
                    @else
                        <div class="mt-3">
                            <a href="{{ route('register.verify.show', $registration->id) }}" class="text-sm text-nse-green-700 font-medium underline hover:no-underline">
                                Verify Email Now →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Step 3: Payment Confirmed --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full {{ $registration->payment_status === 'paid' ? 'bg-nse-green-100' : ($registration->payment_status === 'pending' ? 'bg-nse-warning-bg' : 'bg-nse-neutral-100') }} flex items-center justify-center flex-shrink-0">
                        @if ($registration->payment_status === 'paid')
                            <svg class="w-6 h-6 text-nse-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @elseif ($registration->payment_status === 'pending' || $registration->payment_status === 'processing')
                            <svg class="w-6 h-6 text-nse-warning animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        @else
                            <svg class="w-6 h-6 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    @if ($registration->payment_status === 'paid')
                        <div class="w-1 h-12 bg-nse-green-200 my-1"></div>
                    @else
                        <div class="w-1 h-12 bg-nse-neutral-200 my-1"></div>
                    @endif
                </div>
                <div class="flex-1 pt-1.5">
                    <h3 class="text-lg font-semibold text-nse-neutral-900">Payment Confirmed</h3>
                    <p class="text-sm text-nse-neutral-600 mt-1">Your registration fee has been securely processed. Your accreditation ticket is ready.</p>
                    @if ($registration->payment_status === 'paid')
                        <div class="mt-3 flex gap-3 flex-wrap">
                            <div class="flex items-center gap-2 text-sm text-nse-green-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/></svg>
                                <span>{{ optional($registration->payment_confirmed_at)->format('M d, Y · H:i A') }}</span>
                            </div>
                            <a href="{{ route('ticket.view', ['token' => $registration->qr_token->token ?? '']) }}" class="text-sm text-nse-green-700 font-medium underline hover:no-underline">
                                View Your Ticket →
                            </a>
                        </div>
                    @elseif ($registration->email_verified_at)
                        <div class="mt-3">
                            <a href="{{ route('payment.show') }}" class="text-sm text-nse-green-700 font-medium underline hover:no-underline">
                                Complete Payment Now →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Step 4: Attendance Confirmed --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full {{ in_array($registration->attendance_status, ['physical', 'virtual']) ? 'bg-nse-green-100' : 'bg-nse-neutral-100' }} flex items-center justify-center flex-shrink-0">
                        @if (in_array($registration->attendance_status, ['physical', 'virtual']))
                            <svg class="w-6 h-6 text-nse-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    @if (in_array($registration->attendance_status, ['physical', 'virtual']))
                        <div class="w-1 h-12 bg-nse-green-200 my-1"></div>
                    @endif
                </div>
                <div class="flex-1 pt-1.5">
                    <h3 class="text-lg font-semibold text-nse-neutral-900">Attendance Confirmed</h3>
                    @if (in_array($registration->attendance_status, ['physical', 'virtual']))
                        <p class="text-sm text-nse-neutral-600 mt-1">You have been recorded as attending the event. Your CPD certificate will be available shortly.</p>
                        <div class="mt-3 flex items-center gap-2 text-sm text-nse-green-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/></svg>
                            <span>{{ ucfirst($registration->attendance_status) }} attendance recorded</span>
                        </div>
                    @else
                        <p class="text-sm text-nse-neutral-600 mt-1">Attend the event physically or join an online session for at least 10 minutes to qualify for your certificate.</p>
                        @if ($registration->payment_status === 'paid')
                            <div class="mt-3">
                                <a href="{{ route('stream.show') }}" class="text-sm text-nse-green-700 font-medium underline hover:no-underline">
                                    Join Virtual Stream →
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Step 5: Certificate Issued --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full {{ $hasCertificate ? 'bg-nse-green-100' : 'bg-nse-neutral-100' }} flex items-center justify-center flex-shrink-0">
                        @if ($hasCertificate)
                            <svg class="w-6 h-6 text-nse-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <div class="flex-1 pt-1.5">
                    <h3 class="text-lg font-semibold text-nse-neutral-900">CPD Certificate</h3>
                    @if ($hasCertificate)
                        <p class="text-sm text-nse-neutral-600 mt-1">Your professional development certificate is ready for download.</p>
                        <div class="mt-4 p-4 bg-nse-green-50 border border-nse-green-200 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-semibold text-nse-neutral-900">{{ $certificate->certificate_id }}</p>
                                    <p class="text-xs text-nse-neutral-600">Issued: {{ $certificate->issued_at->format('M d, Y') }}</p>
                                </div>
                                <svg class="w-10 h-10 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('certificate.show', ['token' => $registration->ticket_token]) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-nse-green-700 text-white rounded-lg hover:bg-nse-green-800 transition text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview Certificate
                                </a>
                                <form method="POST" action="{{ route('certificate.download') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $registration->ticket_token }}">
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-nse-green-700 text-nse-green-700 rounded-lg hover:bg-nse-green-50 transition text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('certificate.verify', $certificate->certificate_id) }}" 
                               class="text-sm text-nse-neutral-600 hover:text-nse-neutral-900 underline hover:no-underline inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                View Public Verification Link
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-nse-neutral-600 mt-1">After the event concludes and attendance is verified, your certificate will be issued within 24 hours.</p>
                        @if (in_array($registration->attendance_status, ['physical', 'virtual']))
                            <div class="mt-3 text-sm text-nse-neutral-500">
                                ✓ Certificate issuance in progress...
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Action Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('ticket.view', ['token' => $registration->qr_token->token ?? '']) }}" class="block p-4 rounded-lg border border-nse-neutral-200 bg-white hover:border-nse-green-700 hover:shadow-md transition">
            <div class="text-sm font-semibold text-nse-green-700">QR Ticket</div>
            <p class="text-xs text-nse-neutral-600 mt-1">View and download your accreditation QR code.</p>
        </a>

        @if ($registration->payment_status === 'paid')
            <a href="{{ route('stream.show') }}" class="block p-4 rounded-lg border border-nse-neutral-200 bg-white hover:border-nse-green-700 hover:shadow-md transition">
                <div class="text-sm font-semibold text-nse-green-700">Join Online Stream</div>
                <p class="text-xs text-nse-neutral-600 mt-1">Access the virtual conference channel.</p>
            </a>
        @else
            <a href="{{ route('payment.show') }}" class="block p-4 rounded-lg border border-nse-neutral-200 bg-white hover:border-nse-green-700 hover:shadow-md transition">
                <div class="text-sm font-semibold text-nse-green-700">Complete Payment</div>
                <p class="text-xs text-nse-neutral-600 mt-1">Finalize your registration with a secure payment.</p>
            </a>
        @endif
    </div>

</div>
@endsection
