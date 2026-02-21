@extends('layouts.public')

@section('title', 'Complete Payment — NSE 59th AGM & Conference')

@section('content')
@php
    $registrationWindowOpen = $registrationWindowOpen ?? \App\Support\EventDates::registrationWindowOpen();
    $registrationOpenAt = $registrationOpenAt ?? \App\Support\EventDates::registrationOpenAt();
    $registrationCloseAt = $registrationCloseAt ?? \App\Support\EventDates::registrationCloseAt();
@endphp

<section class="bg-nse-neutral-50 py-8 sm:py-12" aria-labelledby="payment-heading">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-nse-gold-700 text-xs font-bold uppercase tracking-widest mb-1">Payment</p>
            <h1 id="payment-heading" class="text-2xl sm:text-3xl font-bold text-nse-neutral-900 leading-tight">Complete Your Registration Payment</h1>
            <p class="text-nse-neutral-500 text-sm mt-2">Secure payment processing via Paystack. Your accreditation ticket will be issued immediately upon successful payment.</p>
        </div>

        @if(! $registrationWindowOpen)
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded" role="alert">
                <h3 class="font-semibold">Payment is currently unavailable</h3>
                <p class="text-sm mt-1">Configured registration window: {{ $registrationOpenAt->format('M j, Y g:i A') }} to {{ $registrationCloseAt->format('M j, Y g:i A') }}.</p>
            </div>
        @endif

        {{-- Registration Summary Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-nse-neutral-200 p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-nse-neutral-900 mb-6">Registration Summary</h2>

            <div class="space-y-4">
                <div class="flex justify-between items-start py-3 border-b border-nse-neutral-100">
                    <span class="text-nse-neutral-600 font-medium">Participant</span>
                    <span class="text-nse-neutral-900 font-semibold text-right">{{ $registration->name }}</span>
                </div>

                <div class="flex justify-between items-start py-3 border-b border-nse-neutral-100">
                    <span class="text-nse-neutral-600 font-medium">Email</span>
                    <span class="text-nse-neutral-900 font-mono text-right text-sm break-all max-w-xs">{{ $registration->email }}</span>
                </div>

                <div class="flex justify-between items-start py-3 border-b border-nse-neutral-100">
                    <span class="text-nse-neutral-600 font-medium">Attendance Type</span>
                    <span class="inline-flex px-3 py-1 bg-nse-green-50 text-nse-green-700 text-sm font-semibold rounded-full capitalize">
                        {{ ucfirst($registration->attendance ?? 'physical') }}
                    </span>
                </div>

                <div class="flex justify-between items-start py-3 border-b border-nse-neutral-100">
                    <span class="text-nse-neutral-600 font-medium">Membership</span>
                    <span class="text-nse-neutral-900">
                        @if($registration->is_member)
                            <span class="inline-flex items-center gap-1.5 text-nse-green-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                NSE {{ ucfirst($registration->membership_category ?? 'member') }}
                            </span>
                        @else
                            <span class="text-nse-neutral-500">Non-member</span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-start py-3">
                    <span class="text-nse-neutral-600 font-medium">Registration Date</span>
                    <span class="text-nse-neutral-900 font-mono text-sm">{{ $registration->created_at->format('M d, Y · H:i') }}</span>
                </div>
            </div>

            {{-- Amount Due --}}
            <div class="mt-8 p-5 bg-nse-green-50 rounded-lg border border-nse-green-200">
                <div class="flex justify-between items-baseline">
                    <span class="text-nse-neutral-600 font-medium">Amount Due</span>
                    <div>
                        <span class="text-3xl font-bold text-nse-green-700">₦{{ number_format($registration->price_cents / 100, 0) }}</span>
                        <p class="text-xs text-nse-neutral-500 mt-1">{{ $registration->currency }} · Inclusive of all taxes and levies</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Status Alert --}}
        @if(session('error'))
            <div role="alert" class="mb-6 border-l-4 border-nse-error bg-nse-error-bg text-nse-error px-4 py-3 rounded-r-md flex gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Payment Method --}}
        <div class="bg-white rounded-lg shadow-sm border border-nse-neutral-200 p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-nse-neutral-900 mb-4">Payment Method</h2>

            <div class="flex items-center gap-3 p-4 rounded-lg bg-nse-neutral-50 border border-nse-neutral-200 mb-4">
                <div class="w-12 h-8 bg-gradient-to-r from-[#20C997] to-[#15B395] rounded flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h10v2H7z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-nse-neutral-900">Paystack</p>
                    <p class="text-xs text-nse-neutral-500">Secure payment gateway · Cards, Bank Transfer, USSD</p>
                </div>
            </div>

            <p class="text-sm text-nse-neutral-600 mb-6">
                Payments are processed securely by <strong>Paystack</strong>, Nigeria's leading payment platform. Your card details are encrypted and never stored on our servers. You will be redirected to the Paystack checkout page.
            </p>

            {{-- Payment Form --}}
            <form id="payment-form" method="POST" action="{{ route('payment.initiate') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $registration->id }}" />

                <button
                    id="pay-btn"
                    type="submit"
                    @disabled(! $registrationWindowOpen)
                    class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-nse-green-700 text-white text-base font-semibold rounded-lg hover:bg-nse-green-900 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 transition-colors duration-150 disabled:bg-nse-neutral-200 disabled:cursor-not-allowed min-h-[52px]"
                >
                    <span id="btn-text" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Proceed to Secure Checkout
                    </span>
                    <span id="btn-spinner" class="hidden flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        Processing...
                    </span>
                </button>

                <p class="text-xs text-nse-neutral-500 text-center">
                    By clicking "Proceed", you agree to our <a href="/terms" target="_blank" rel="noopener" class="text-nse-green-700 underline hover:no-underline">Terms & Conditions</a>
                </p>
            </form>
        </div>

        {{-- Sandbox Notice (if needed) --}}
        @if(env('APP_ENV') === 'local' || env('PAYSTACK_PUBLIC_KEY') === null)
        <div role="alert" class="border-l-4 border-nse-warning bg-nse-warning-bg text-nse-warning px-4 py-3 rounded-r-md">
            <p class="font-semibold text-sm mb-1">🧪 Sandbox Mode</p>
            <p class="text-xs leading-relaxed">Use test card <strong>4111 1111 1111 1111</strong> · CVV: <strong>123</strong> · Any future date · Any OTP</p>
        </div>
        @endif

        {{-- Security Footer --}}
        <div class="mt-8 flex items-center justify-center gap-4 text-xs text-nse-neutral-500">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                SSL Encrypted
            </div>
            <span aria-hidden="true">•</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                PCI DSS Compliant
            </div>
        </div>

    </div>
</section>

<script>
    document.getElementById('payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('pay-btn');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');

        btn.disabled = true;
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');

        try {
            const formData = new FormData(this);
            const response = await fetch('{{ route("payment.initiate") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const result = await response.json();
            if (result.success && result.checkout_url) {
                // Redirect to Paystack checkout
                window.location.href = result.checkout_url;
            } else {
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
                alert('Error initiating payment: ' + (result.message || 'Please try again.'));
            }
        } catch (error) {
            btn.disabled = false;
            btnText.classList.remove('hidden');
            btnSpinner.classList.add('hidden');
            console.error('Payment error:', error);
            alert('Payment error. Please try again or contact support.');
        }
    });
</script>

@endsection
