@extends('layouts.public')

@section('title', 'Register — NSE 59th AGM & International Conference 2026')

@section('content')

@php
    $pricingItems = $pricingItems ?? collect();
    $earlyBirdActive = $earlyBirdActive ?? now()->lt(\Carbon\Carbon::parse('2026-04-28')->endOfDay());
    $noPackages = $pricingItems->isEmpty();
@endphp

<section class="bg-nse-neutral-50 py-8 sm:py-12" aria-labelledby="register-heading">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        <div class="mb-6">
            <p class="text-nse-gold-700 text-xs font-bold uppercase tracking-widest mb-1">Registration</p>
            <h1 id="register-heading" class="text-2xl sm:text-3xl font-bold text-nse-neutral-900 leading-tight">Register for NSE 59th AGM &amp; Conference</h1>
            <p class="text-nse-neutral-500 text-sm mt-2">Complete registration now — email verification is required before payment. Your price is locked at registration.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded" role="alert">
                <h3 class="font-semibold">Please fix the following errors</h3>
                <ul class="list-disc list-inside mt-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" x-data="registrationForm()" x-cloak novalidate>
            @csrf

            {{-- Stepper visual (mobile-first) --}}
            <div class="mb-6" aria-hidden="true">
                <ol class="flex items-center justify-between" role="list">
                    <li class="flex-1 text-center">
                        <div :class="step > 1 ? 'bg-nse-green-700 text-white' : 'bg-white text-nse-neutral-700'" class="mx-auto w-8 h-8 rounded-full border flex items-center justify-center">1</div>
                        <div class="text-xs mt-2">Your details</div>
                    </li>
                    <li class="flex-1 text-center">
                        <div :class="step > 2 ? 'bg-nse-green-700 text-white' : (step === 2 ? 'bg-nse-green-50 text-nse-green-700' : 'bg-white text-nse-neutral-700')" class="mx-auto w-8 h-8 rounded-full border flex items-center justify-center">2</div>
                        <div class="text-xs mt-2">Selection</div>
                    </li>
                    <li class="flex-1 text-center">
                        <div :class="step === 3 ? 'bg-nse-green-700 text-white' : 'bg-white text-nse-neutral-700'" class="mx-auto w-8 h-8 rounded-full border flex items-center justify-center">3</div>
                        <div class="text-xs mt-2">Review</div>
                    </li>
                </ol>
            </div>

            {{-- Step 1: Personal details --}}
            <section x-show="step === 1" x-transition aria-labelledby="step1-heading" class="space-y-4">
                <h2 id="step1-heading" class="sr-only">Step 1 — Your details</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-nse-neutral-900">First name <span class="text-nse-error">*</span></label>
                        <input id="first_name" name="first_name" x-model="form.first_name" type="text" required autocomplete="given-name" value="{{ old('first_name') }}" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700" />
                    </div>
                    <div>
                        <label for="surname" class="block text-sm font-medium text-nse-neutral-900">Surname <span class="text-nse-error">*</span></label>
                        <input id="surname" name="surname" x-model="form.surname" type="text" required autocomplete="family-name" value="{{ old('surname') }}" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="gender" class="block text-sm font-medium text-nse-neutral-900">Gender</label>
                        <select id="gender" name="gender" x-model="form.gender" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700">
                            <option value="">Prefer not to say</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="organization" class="block text-sm font-medium text-nse-neutral-900">Organisation / Company</label>
                        <input id="organization" name="organization" x-model="form.organization" type="text" value="{{ old('organization') }}" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700" />
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-nse-neutral-900">Email address <span class="text-nse-error">*</span></label>
                    <input id="email" name="email" x-model="form.email" type="email" required autocomplete="email" value="{{ old('email') }}" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700" />
                    <p class="text-xs text-nse-neutral-500 mt-1">We will send a 6-digit OTP to this email; verification is required before payment.</p>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-nse-neutral-900">Phone number <span class="text-nse-error">*</span></label>
                    <input id="phone" name="phone" x-model="form.phone" type="tel" inputmode="tel" required autocomplete="tel" value="{{ old('phone') }}" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700" />
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex items-center h-5">
                        <input id="self_attest" name="self_attest" x-model="form.self_attest" type="checkbox" required class="h-4 w-4 text-nse-green-700 border-gray-300 rounded focus:ring-nse-green-700" />
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="self_attest" class="font-medium text-nse-neutral-900">I confirm that the information provided is accurate</label>
                        <p class="text-xs text-nse-neutral-500">This self-attestation is required to proceed with registration.</p>
                    </div>
                </div>

                {{-- Membership toggle --}}
                <div class="pt-4 border-t border-nse-neutral-200">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center h-5">
                            <input id="is_member" name="is_member" x-model="form.is_member" type="checkbox" class="h-4 w-4 text-nse-green-700 border-gray-300 rounded focus:ring-nse-green-700" />
                        </div>
                        <div class="ml-2 text-sm">
                            <label for="is_member" class="font-medium text-nse-neutral-900">I am an NSE member</label>
                            <p class="text-xs text-nse-neutral-500">Select if you have an NSE membership number. Members receive member pricing.</p>
                        </div>
                    </div>

                    <div x-show="form.is_member" x-cloak class="mt-4 space-y-3">
                        <div>
                            <label for="membership_category" class="block text-sm font-medium text-nse-neutral-900">Membership category</label>
                            <select id="membership_category" name="membership_category" x-model="form.membership_category" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700">
                                <option value="">Select category</option>
                                <option value="student" {{ old('membership_category') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="graduate" {{ old('membership_category') === 'graduate' ? 'selected' : '' }}>Graduate</option>
                                <option value="corporate" {{ old('membership_category') === 'corporate' ? 'selected' : '' }}>Corporate</option>
                                <option value="fellow" {{ old('membership_category') === 'fellow' ? 'selected' : '' }}>Fellow</option>
                            </select>
                        </div>

                        <div>
                            <label for="membership_number" class="block text-sm font-medium text-nse-neutral-900">NSE membership number</label>
                            <input id="membership_number" name="membership_number" x-model="form.membership_number" type="text" placeholder="e.g. NSE-12345" value="{{ old('membership_number') }}" class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nse-green-700" />
                            <p class="text-xs text-nse-neutral-500 mt-1">Format: NSE-12345 · Self-declared.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <div></div>
                    <button type="button" @click="nextStep()" class="inline-flex items-center px-5 py-2 bg-nse-green-700 text-white rounded-md hover:bg-nse-green-900 focus:outline-none focus:ring-2 focus:ring-nse-green-700">Continue</button>
                </div>
            </section>

            {{-- Step 2: Category & Attendance --}}
            <section x-show="step === 2" x-transition aria-labelledby="step2-heading" class="space-y-4">
                <h2 id="step2-heading" class="sr-only">Step 2 — Category & Attendance</h2>

                <div>
                    <label class="block text-sm font-medium text-nse-neutral-900">Select category <span class="text-nse-error">*</span></label>
                    <fieldset class="mt-3 grid grid-cols-1 gap-3" role="radiogroup" aria-label="Registration category">
                        @php
                            $categories = [
                                ['id' => 'student', 'label' => 'Student'],
                                ['id' => 'graduate', 'label' => 'Graduate'],
                                ['id' => 'corporate', 'label' => 'Corporate'],
                                ['id' => 'fellow', 'label' => 'Fellow'],
                                ['id' => 'non_member', 'label' => 'Non-member'],
                            ];
                        @endphp
                        @foreach($categories as $c)
                            <label class="relative flex items-center gap-3 p-3 border rounded-md hover:border-nse-green-700 cursor-pointer">
                                <input type="radio" name="category" x-model="form.category" value="{{ $c['id'] }}" class="h-4 w-4 text-nse-green-700" />
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-nse-neutral-900">{{ $c['label'] }}</span>
                                        <span class="text-xs text-nse-neutral-400">Member pricing where applicable</span>
                                    </div>
                                    <p class="text-xs text-nse-neutral-500 mt-1">Choose the category that best describes you.</p>
                                </div>
                            </label>
                        @endforeach
                    </fieldset>

                </div>

                <div>
                    <label class="block text-sm font-medium text-nse-neutral-900">Attendance type <span class="text-nse-error">*</span></label>
                    <div class="mt-2 flex gap-3">
                        <button type="button" @click="form.attendance = 'physical'" :class="form.attendance === 'physical' ? 'bg-nse-green-700 text-white' : 'bg-white text-nse-neutral-700'" class="px-4 py-2 border rounded-md">Physical</button>
                        <button type="button" @click="form.attendance = 'virtual'" :class="form.attendance === 'virtual' ? 'bg-nse-gold-500 text-white' : 'bg-white text-nse-neutral-700'" class="px-4 py-2 border rounded-md">Virtual</button>
                    </div>
                </div>

                {{-- SECTION D — Registration Package (uses $pricingItems) --}}
                <div class="p-6 border-y border-nse-neutral-200 bg-white rounded-md">
                    <div class="flex items-baseline justify-between mb-1">
                        <h3 class="text-sm font-semibold text-nse-neutral-900">Registration Package</h3>
                        <a href="{{ route('pricing') }}" target="_blank" rel="noopener" class="text-xs text-nse-green-700 hover:underline">Full pricing table →</a>
                    </div>
                    <p class="text-xs text-nse-neutral-500 mb-4">Select the package matching your membership category and chosen attendance type above.</p>

                    @if($noPackages)
                        <div class="flex items-start gap-3 border border-nse-neutral-200 bg-nse-neutral-50 rounded-lg p-4">
                            <svg class="w-5 h-5 text-nse-neutral-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-nse-neutral-600">Registration prices are being finalised</p>
                                <p class="text-xs text-nse-neutral-400 mt-0.5 leading-relaxed">The NSE Secretariat is configuring registration packages. The portal opens officially on <strong>February 28, 2026</strong>. Please check back then.</p>
                            </div>
                        </div>
                    @else
                        <div>
                            <label for="pricing_item_id" class="block text-sm font-semibold text-nse-neutral-900 mb-1.5">Select Package <span class="text-nse-error text-xs ml-0.5">*</span></label>
                            <div class="relative">
                                <select id="pricing_item_id" name="pricing_item_id" required class="w-full pl-4 pr-10 py-3.5 text-base border rounded-lg appearance-none bg-white transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:border-nse-green-700">
                                    <option value="" disabled {{ !old('pricing_item_id') ? 'selected' : '' }}>— Select your package —</option>
                                    @foreach($pricingItems as $item)
                                        <option value="{{ $item->id }}" {{ old('pricing_item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }} — ₦{{ number_format($item->price_cents / 100) }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-nse-neutral-400" aria-hidden="true">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            @if($earlyBirdActive)
                                <p class="mt-2 text-xs text-nse-neutral-400 flex items-center gap-1.5"><span class="inline-flex px-1.5 py-0.5 bg-nse-gold-50 text-nse-gold-700 text-[9px] font-bold border border-nse-gold-500 rounded uppercase">Early Bird</span> Early bird rates active. Your registration date locks your price tier.</p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- CAPTCHA + Attestation + Submit --}}
                <div class="mt-6 space-y-5">
                    @if(config('services.turnstile.site_key'))
                        <div>
                            <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="light" data-language="en"></div>
                        </div>
                    @endif

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="attestation" required class="mt-0.5 w-4 h-4 rounded border-nse-neutral-200 text-nse-green-700 cursor-pointer focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-1 flex-shrink-0">
                        <span class="text-sm text-nse-neutral-600 leading-relaxed select-none">I confirm that all information provided is accurate. I understand that misrepresentation of NSE membership status is a disciplinary matter under NSE rules. I agree to the <a href="/terms" target="_blank" rel="noopener" class="text-nse-green-700 underline">Terms &amp; Privacy Policy</a>. <span class="text-nse-error text-xs ml-0.5">*</span></span>
                    </label>

                    <div>
                        <button type="submit" @click="if (canSubmit) submitting = true" :disabled="!canSubmit" :class="canSubmit ? 'bg-nse-green-700 hover:bg-nse-green-900 cursor-pointer' : 'bg-nse-neutral-200 text-nse-neutral-400 cursor-not-allowed'" class="w-full flex items-center justify-center gap-2 px-6 py-4 text-white text-base font-semibold rounded-lg transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:ring-offset-2 min-h-[52px]" aria-live="polite">
                            <span x-show="!submitting" class="flex items-center gap-2">Continue to Email Verification <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></span>
                            <span x-show="submitting" x-cloak class="flex items-center gap-2"><svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg> Processing your registration…</span>
                        </button>

                        <p x-show="!attendType && !submitting" x-cloak class="mt-2 text-xs text-nse-neutral-400 text-center" role="status" aria-live="polite">Select an attendance type above to enable registration.</p>

                        <p class="mt-4 text-sm text-nse-neutral-400 text-center">Already registered? <a href="{{ route('login') }}" class="text-nse-green-700 font-medium hover:underline">Sign in to your account</a></p>
                    </div>
                </div>

                {{-- Hidden canonical inputs to ensure server receives fields --}}
                <input type="hidden" name="_js_enabled" value="1">
                <input type="hidden" name="first_name" x-bind:value="form.first_name">
                <input type="hidden" name="surname" x-bind:value="form.surname">
                <input type="hidden" name="gender" x-bind:value="form.gender">
                <input type="hidden" name="organization" x-bind:value="form.organization">
                <input type="hidden" name="email" x-bind:value="form.email">
                <input type="hidden" name="phone" x-bind:value="form.phone">
                <input type="hidden" name="is_member" x-bind:value="form.is_member ? 1 : 0">
                <input type="hidden" name="membership_category" x-bind:value="form.membership_category">
                <input type="hidden" name="membership_number" x-bind:value="form.membership_number">
                <input type="hidden" name="category" x-bind:value="form.category">
                <input type="hidden" name="attendance" x-bind:value="form.attendance">
                <input type="hidden" name="self_attest" x-bind:value="form.self_attest ? 1 : 0">

        </form>

        <p class="mt-6 text-xs text-nse-neutral-400 text-center leading-relaxed"><svg class="inline w-3.5 h-3.5 mr-0.5 -mt-0.5 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Your data is protected. Payments are processed securely by Paystack, Nigeria's regulated payment gateway.</p>

    </div>
</section>

<script>
    function registrationForm(){
        return {
            step: 1,
            submitting: false,
            form: {
                first_name: `{{ old('first_name', '') }}`,
                surname: `{{ old('surname', '') }}`,
                gender: `{{ old('gender', '') }}`,
                organization: `{{ old('organization', '') }}`,
                email: `{{ old('email', '') }}`,
                phone: `{{ old('phone', '') }}`,
                self_attest: {{ old('self_attest') ? 'true' : 'false' }},
                is_member: {{ old('is_member') ? 'true' : 'false' }},
                membership_category: `{{ old('membership_category', '') }}`,
                membership_number: `{{ old('membership_number', '') }}`,
                category: `{{ old('category', '') }}`,
                attendance: `{{ old('attendance', 'physical') }}`,
            },
            get canSubmit(){
                return this.form.first_name && this.form.surname && this.form.email && this.form.phone && this.form.self_attest && this.form.attendance;
            },
            nextStep(){
                if(this.step === 1){
                    if(!this.form.first_name || !this.form.surname || !this.form.email || !this.form.phone || !this.form.self_attest){
                        alert('Please complete the required fields on this step before continuing.');
                        return;
                    }
                }
                if(this.step === 2){
                    if(!this.form.category || !this.form.attendance){
                        alert('Please choose a category and attendance type.');
                        return;
                    }
                }
                if(this.step < 3) this.step++;
                window.scrollTo({top:0,behavior:'smooth'});
            },
            prevStep(){ if(this.step > 1) this.step--; window.scrollTo({top:0,behavior:'smooth'}); },
        }
    }
</script>

@if (config('services.turnstile.site_key'))
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif

@endsection
