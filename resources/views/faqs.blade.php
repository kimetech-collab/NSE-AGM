@extends('layouts.public')

@section('title', 'FAQs — NSE 59th AGM & Conference')

@section('content')
@php
    $earlyBirdDeadlineAt = \App\Support\EventDates::get('early_bird_deadline_at');
    $registrationCloseAt = \App\Support\EventDates::get('registration_close_at');
@endphp

{{-- ═══════════════════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-gradient-to-b from-nse-green-700 to-nse-green-900 py-16 sm:py-20 text-white" aria-labelledby="faq-hero">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="max-w-3xl">
            <p class="text-nse-gold-400 text-sm font-bold uppercase tracking-widest mb-2">Help & Support</p>
            <h1 id="faq-hero" class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">
                Frequently Asked Questions
            </h1>
            <p class="text-lg text-white/80 leading-relaxed">
                Find answers to common questions about registration, attendance, payment, travel, and the conference itself.
            </p>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     SEARCH BOX
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-b border-nse-neutral-200 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="relative max-w-2xl mx-auto">
            <input
                type="text"
                placeholder="Search FAQs..."
                x-data
                @input="document.querySelectorAll('[data-category]').forEach(cat => {
                    const query = $event.target.value.toLowerCase();
                    const items = cat.querySelectorAll('[data-faq-item]');
                    let visible = false;
                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if(text.includes(query)) {
                            item.style.display = '';
                            visible = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    cat.style.display = visible || query === '' ? '' : 'none';
                })"
                class="w-full px-4 py-3 pl-12 border border-nse-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-nse-green-700 focus:border-transparent"
            />
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     FAQ CATEGORIES
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-neutral-50 py-12 sm:py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">

        {{-- Registration & Eligibility --}}
        <div data-category="registration" class="mb-12">
            <h2 class="text-2xl font-bold text-nse-neutral-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-nse-green-700 font-bold">📋</span>
                </span>
                Registration & Eligibility
            </h2>
            <div class="space-y-4">

                {{-- Q1 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Do I need to be an NSE member to attend?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">No. The conference is open to all engineering professionals, academics, students, and industry professionals. Both NSE members and non-members are welcome. Members receive member pricing discounts.</p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Can students attend?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Yes, students currently enrolled in accredited engineering programs are welcome. Student pricing is available. A valid student ID will be required at check-in for verification.</p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">What if I'm from outside Nigeria?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">International participants are welcome. You may require a Nigerian visa depending on your citizenship. The NSE can provide invitation letters to facilitate visa applications. Contact conference@nse.org.ng for support.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Payment & Pricing --}}
        <div data-category="payment" class="mb-12">
            <h2 class="text-2xl font-bold text-nse-neutral-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-nse-gold-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-nse-gold-700 font-bold">💳</span>
                </span>
                Payment & Pricing
            </h2>
            <div class="space-y-4">

                {{-- Q1 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">When does early bird pricing end?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed"><strong>Early bird pricing is available until {{ $earlyBirdDeadlineAt->format('F j, Y') }}.</strong> Your price is locked at your registration date, not payment date. Register before {{ $earlyBirdDeadlineAt->format('F j') }} to secure the early bird rate, then complete payment anytime before {{ $registrationCloseAt->format('F j, Y') }}.</p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Can I register now and pay later?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Yes. Complete the registration form and verify your email. Your registration is recorded and price locked. You can return to complete payment anytime before the registration close date of {{ $registrationCloseAt->format('F j, Y') }}.</p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">What payment methods are accepted?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Payments are processed via <strong>Paystack</strong>, which accepts debit/credit cards (Visa, Mastercard), bank transfers, USSD, and mobile money.</p>
                    </div>
                </div>

                {{-- Q4 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">What is the refund policy?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Refund requests may be submitted within 7 days of payment. Refunds are processed to the original payment method. After 7 days, no refunds are provided unless otherwise determined by the NSE. Approved refunds will revoke your accreditation ticket.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Attendance & Access --}}
        <div data-category="attendance" class="mb-12">
            <h2 class="text-2xl font-bold text-nse-neutral-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-nse-green-700 font-bold">🎫</span>
                </span>
                Attendance & Access
            </h2>
            <div class="space-y-4">

                {{-- Q1 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">What if I cannot travel to Maiduguri?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Virtual attendance is fully supported. You will receive a secure link to access all plenary sessions and keynotes via live stream. Full access to parallel sessions and recordings available post-event.</p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Can I attend specific sessions only?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Conference passes include all sessions for your registered day(s). You can attend as many sessions and technical tracks as you wish. Single-day and multi-day passes are available.</p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Will I receive a CPD certificate?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Yes. All participants (physical and virtual) who complete the conference receive a CPD certificate recognized by COREN and professional engineering bodies. Digital credentials also available.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Travel & Accommodation --}}
        <div data-category="travel" class="mb-12">
            <h2 class="text-2xl font-bold text-nse-neutral-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-nse-gold-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-nse-gold-700 font-bold">✈️</span>
                </span>
                Travel & Accommodation
            </h2>
            <div class="space-y-4">

                {{-- Q1 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Is accommodation included?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Accommodation is not included in registration. However, negotiated rates are available at partner hotels in Maiduguri. Details will be sent to registered participants 4 weeks before the event.</p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">How do I get to Maiduguri?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed"><strong>By air:</strong> Maiduguri International Airport (MJI) with connections from Lagos, Abuja, and other cities (1.5–3 hrs flight time). <strong>By road:</strong> Accessible via major highways (Abuja 8–10 hrs, Lagos 12+ hrs). Flying is recommended for long distances.</p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Do I need a visa?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Visa requirements depend on your citizenship. Check <strong>nigeriaimmigration.gov.ng</strong> for eligibility. The NSE can provide invitation letters to support visa applications. Contact conference@nse.org.ng for assistance.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Technical & Support --}}
        <div data-category="support" class="mb-12">
            <h2 class="text-2xl font-bold text-nse-neutral-900 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-nse-green-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-nse-green-700 font-bold">🆘</span>
                </span>
                Technical & Support
            </h2>
            <div class="space-y-4">

                {{-- Q1 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">I've lost my ticket/accreditation QR code. What do I do?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Contact the registration desk at the venue with your email address and we can reissue your ticket. Virtual participants can access to their ticket from the participant portal anytime.</p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">Isn't working with my login. How can I reset my password?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">Use the "Forgot Password" link on the login page. A reset link will be sent to your registered email. Follow the instructions to create a new password.</p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-nse-neutral-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-nse-green-700">
                        <span class="text-left text-sm font-semibold text-nse-neutral-900">I still have questions. Who can I contact?</span>
                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                        <p class="text-sm text-nse-neutral-700 leading-relaxed">
                            Email: <a href="mailto:conference@nse.org.ng" class="text-nse-green-700 underline">conference@nse.org.ng</a><br/>
                            Phone: +234 (0) 1 XXX XXXX (during business hours)<br/>
                            We aim to respond to all inquiries within 24 hours.
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════
     BOTTOM CTA
═══════════════════════════════════════════════════════════════════ --}}
<section class="bg-nse-green-700 py-12 sm:py-14 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Couldn't Find Your Answer?</h2>
        <p class="text-white/80 text-base mb-8">Contact the NSE conference team directly for support.</p>
        <a
            href="mailto:conference@nse.org.ng"
            class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-nse-green-700 text-base font-semibold rounded-md hover:bg-nse-green-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-nse-green-700 transition-colors duration-150"
        >
            Email Us
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </a>
    </div>
</section>

@endsection
