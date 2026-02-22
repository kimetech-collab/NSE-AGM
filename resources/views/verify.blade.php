@extends('layouts.public')

@section('title', 'Verify Your Email — NSE 59th AGM & International Conference')

@section('content')

<section class="bg-nse-neutral-50 py-8 sm:py-12 min-h-[70vh]" aria-labelledby="verify-heading">
    <div class="max-w-lg mx-auto px-4 sm:px-6">

        {{-- ── Flow progress indicator ── --}}
        <div class="mb-8" aria-label="Registration progress">
            <ol class="flex items-center" role="list">

                {{-- Step 1: Registration (completed) --}}
                <li class="flex-1 flex flex-col items-center" role="listitem">
                    <div class="w-9 h-9 rounded-full bg-nse-green-700 flex items-center justify-center shadow-sm" aria-label="Step 1 completed">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="mt-2 text-xs font-semibold text-nse-green-700">Registration</span>
                </li>

                {{-- Connector --}}
                <li class="flex-1 flex items-center justify-center pb-5" aria-hidden="true">
                    <div class="h-0.5 w-full bg-nse-green-700"></div>
                </li>

                {{-- Step 2: Email Verification (active) --}}
                <li class="flex-1 flex flex-col items-center" role="listitem">
                    <div class="w-9 h-9 rounded-full bg-nse-green-700 ring-4 ring-nse-green-100 flex items-center justify-center shadow-sm" aria-label="Step 2 active: Email Verification" aria-current="step">
                        <span class="text-white text-sm font-bold">2</span>
                    </div>
                    <span class="mt-2 text-xs font-bold text-nse-green-700">Verify Email</span>
                </li>

                {{-- Connector --}}
                <li class="flex-1 flex items-center justify-center pb-5" aria-hidden="true">
                    <div class="h-0.5 w-full bg-nse-neutral-200"></div>
                </li>

                {{-- Step 3: Payment (pending) --}}
                <li class="flex-1 flex flex-col items-center" role="listitem">
                    <div class="w-9 h-9 rounded-full bg-white border-2 border-nse-neutral-200 flex items-center justify-center" aria-label="Step 3 pending: Payment">
                        <span class="text-nse-neutral-400 text-sm font-bold">3</span>
                    </div>
                    <span class="mt-2 text-xs font-medium text-nse-neutral-400">Payment</span>
                </li>

            </ol>
        </div>

        {{-- ── Page heading ── --}}
        <div class="text-center mb-6">
            {{-- Email icon --}}
            <div class="mx-auto mb-4 w-16 h-16 bg-nse-green-50 rounded-2xl flex items-center justify-center border border-nse-green-100" aria-hidden="true">
                <svg class="w-8 h-8 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-nse-gold-700 text-xs font-bold uppercase tracking-widest mb-1">Email Verification</p>
            <h1 id="verify-heading" class="text-2xl sm:text-3xl font-bold text-nse-neutral-900 leading-tight">Check your inbox</h1>
            <p class="text-nse-neutral-500 text-sm mt-2 leading-relaxed">
                We sent a 6-digit verification code to<br>
                <strong class="text-nse-neutral-900 font-semibold">{{ $registration->email }}</strong>
            </p>
        </div>

        {{-- ── Flash messages ── --}}
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold text-sm">Please fix the following</p>
                    <ul class="list-disc list-inside mt-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- ── OTP Form ── --}}
        <div
            x-data="otpForm()"
            x-init="init()"
            class="bg-white rounded-xl border border-nse-neutral-200 shadow-sm p-6 sm:p-8"
        >

            {{-- Countdown timer --}}
            <div class="text-center mb-6">
                <div
                    x-show="!expired"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-nse-neutral-50 rounded-full border border-nse-neutral-200"
                >
                    <svg class="w-4 h-4 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-nse-neutral-600">Code expires in <span class="font-mono font-semibold text-nse-neutral-900" x-text="timeLeft"></span></span>
                </div>

                <div
                    x-show="expired"
                    x-cloak
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 rounded-full border border-red-200"
                    role="alert"
                >
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-red-700 font-medium">Code has expired — request a new one below</span>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('register.verify') }}"
                @submit.prevent="submitOtp($event)"
                novalidate
            >
                @csrf
                <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                {{-- Actual OTP value bound from digit boxes --}}
                <input type="hidden" name="otp" x-bind:value="otp">

                {{-- 6-digit OTP input boxes --}}
                <div class="mb-6">
                    <label class="sr-only">6-digit verification code</label>

                    <div
                        class="flex items-center justify-center gap-2 sm:gap-3"
                        @paste.prevent="handlePaste($event)"
                        role="group"
                        aria-label="Enter verification code"
                    >
                        @for ($i = 0; $i < 6; $i++)
                            <input
                                x-ref="d{{ $i }}"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]"
                                maxlength="1"
                                autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                                x-model="digits[{{ $i }}]"
                                @input="handleInput({{ $i }}, $event)"
                                @keydown="handleKeydown({{ $i }}, $event)"
                                @focus="$event.target.select()"
                                :disabled="expired"
                                :class="expired ? 'bg-nse-neutral-50 text-nse-neutral-300 cursor-not-allowed' : 'bg-white text-nse-neutral-900 focus:border-nse-green-700 focus:ring-2 focus:ring-nse-green-700'"
                                class="w-11 h-14 sm:w-13 sm:h-16 text-center text-xl sm:text-2xl font-bold border-2 border-nse-neutral-200 rounded-lg outline-none transition-colors duration-150 caret-transparent"
                                aria-label="Digit {{ $i + 1 }}"
                            >
                        @endfor
                    </div>

                    <p class="mt-3 text-center text-xs text-nse-neutral-400">
                        Enter the 6-digit code from your email. Check spam if not received.
                    </p>
                </div>

                {{-- Submit button --}}
                <button
                    type="submit"
                    :disabled="!isFull || expired || submitting"
                    :class="{
                        'bg-nse-green-700 hover:bg-nse-green-900 cursor-pointer': isFull && !expired,
                        'bg-nse-neutral-200 text-nse-neutral-400 cursor-not-allowed': !isFull || expired,
                    }"
                    class="w-full flex items-center justify-center gap-2 px-6 py-4 text-white text-base font-semibold rounded-lg transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 min-h-[52px]"
                    aria-live="polite"
                >
                    <span x-show="!submitting" class="flex items-center gap-2">
                        Verify &amp; Continue to Payment
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </span>
                    <span x-show="submitting" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        Verifying…
                    </span>
                </button>
            </form>

            {{-- Resend OTP --}}
            <div class="mt-5 text-center">
                <p class="text-sm text-nse-neutral-500 mb-3">Didn't receive the code?</p>

                {{-- Cooldown state --}}
                <p x-show="resendCooldown > 0" x-cloak class="text-sm text-nse-neutral-400">
                    Resend available in <span class="font-mono font-semibold" x-text="resendCooldown + 's'"></span>
                </p>

                {{-- Resend form (visible when cooldown is 0) --}}
                <form
                    x-show="resendCooldown === 0"
                    method="POST"
                    action="{{ route('register.verify.resend') }}"
                    @submit="onResend()"
                >
                    @csrf
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-nse-green-700 hover:text-nse-green-900 hover:underline focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-1 rounded transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Resend verification code
                    </button>
                </form>
            </div>

            {{-- Wrong email? --}}
            <p class="mt-4 text-center text-xs text-nse-neutral-400 leading-relaxed">
                Wrong email address?
                <a href="{{ route('register') }}" class="text-nse-green-700 font-medium hover:underline">Start registration again</a>
            </p>

        </div>{{-- /card --}}

        {{-- ── Developer testing panel (local/testing only) ── --}}
        @if (session('otp') && app()->isLocal())
            <div class="mt-5 p-4 bg-amber-50 border border-amber-200 rounded-lg" role="note">
                <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1">Dev / Local — OTP Preview</p>
                <p class="text-sm text-amber-800">
                    OTP for this session:
                    <code class="bg-white border border-amber-200 px-2 py-0.5 rounded font-mono text-base font-bold tracking-widest text-amber-900">{{ session('otp') }}</code>
                </p>
                <p class="text-xs text-amber-600 mt-1">This panel only appears in local environment.</p>
            </div>
        @endif

        {{-- Trust signal --}}
        <p class="mt-6 text-xs text-nse-neutral-400 text-center leading-relaxed">
            <svg class="inline w-3.5 h-3.5 mr-0.5 -mt-0.5 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Your code is valid for 10 minutes. Do not share it with anyone.
        </p>

    </div>
</section>

@push('scripts')
<script>
function otpForm() {
    return {
        digits: ['', '', '', '', '', ''],
        seconds: 600,
        resendCooldown: 0,
        submitting: false,
        _timer: null,

        get otp() {
            return this.digits.join('');
        },
        get isFull() {
            return this.otp.replace(/\s/g, '').length === 6;
        },
        get expired() {
            return this.seconds <= 0;
        },
        get timeLeft() {
            const m = Math.floor(this.seconds / 60);
            const s = this.seconds % 60;
            return `${m}:${String(s).padStart(2, '0')}`;
        },

        init() {
            this._timer = setInterval(() => {
                if (this.seconds > 0) this.seconds--;
                if (this.resendCooldown > 0) this.resendCooldown--;
            }, 1000);

            // Auto-focus first digit on load
            this.$nextTick(() => {
                if (this.$refs.d0) this.$refs.d0.focus();
            });
        },

        handleInput(index, event) {
            // Strip non-numeric, keep only last character
            const raw = event.target.value.replace(/\D/g, '');
            this.digits[index] = raw.slice(-1);
            event.target.value = this.digits[index];

            // Advance focus
            if (this.digits[index] && index < 5) {
                this.$nextTick(() => this.$refs[`d${index + 1}`].focus());
            }
        },

        handleKeydown(index, event) {
            if (event.key === 'Backspace') {
                if (!this.digits[index] && index > 0) {
                    this.digits[index - 1] = '';
                    this.$nextTick(() => this.$refs[`d${index - 1}`].focus());
                }
            }
            if (event.key === 'ArrowLeft' && index > 0) {
                this.$refs[`d${index - 1}`].focus();
            }
            if (event.key === 'ArrowRight' && index < 5) {
                this.$refs[`d${index + 1}`].focus();
            }
        },

        handlePaste(event) {
            const paste = (event.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, 6);

            for (let i = 0; i < 6; i++) {
                this.digits[i] = paste[i] || '';
            }

            this.$nextTick(() => {
                const focusIdx = Math.min(paste.length, 5);
                this.$refs[`d${focusIdx}`].focus();
            });
        },

        onResend() {
            this.resendCooldown = 60;
            this.seconds = 600;
            // Reset digits
            this.digits = ['', '', '', '', '', ''];
            this.$nextTick(() => {
                if (this.$refs.d0) this.$refs.d0.focus();
            });
        },

        submitOtp(event) {
            if (!this.isFull || this.expired) return;
            this.submitting = true;
            event.target.submit();
        },
    }
}
</script>
@endpush

@endsection
