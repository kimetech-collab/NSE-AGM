@extends('layouts.public')

@section('title', 'Terms & Privacy — NSE 59th AGM Portal')

@section('content')
<section class="bg-white py-16 sm:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-10">
        <h1 class="text-3xl font-bold text-nse-neutral-900 mb-4">Terms & Privacy Policy</h1>
        <p class="text-nse-neutral-600 mb-8">By using this portal, you agree to registration, payment, and certificate verification terms for the NSE 59th AGM.</p>

        <div class="space-y-6 text-sm text-nse-neutral-700 leading-relaxed">
            <div>
                <h2 class="text-base font-semibold text-nse-neutral-900 mb-2">1. Registration Data</h2>
                <p>You confirm that personal and membership data submitted during registration is accurate and may be used for participation validation, accreditation, and certificate issuance.</p>
            </div>
            <div>
                <h2 class="text-base font-semibold text-nse-neutral-900 mb-2">2. Payment & Refunds</h2>
                <p>Payments are processed through Paystack. Refunds are subject to NSE policy and may require admin approval and verification logs.</p>
            </div>
            <div>
                <h2 class="text-base font-semibold text-nse-neutral-900 mb-2">3. Certificates</h2>
                <p>Certificate issuance is based on attendance eligibility rules. Public verification may display participant name, event name, issue date, and certificate ID.</p>
            </div>
            <div>
                <h2 class="text-base font-semibold text-nse-neutral-900 mb-2">4. Security & Audit</h2>
                <p>Administrative actions are audit logged for compliance and governance. Access abuse, fraud attempts, or impersonation may result in access revocation.</p>
            </div>
        </div>
    </div>
</section>
@endsection
