@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-2xl font-bold mb-4">Payment</h1>
        
        <div class="bg-gray-100 p-4 rounded mb-4">
            <p><strong>Name:</strong> {{ $registration->name }}</p>
            <p><strong>Email:</strong> {{ $registration->email }}</p>
            <p><strong>Amount:</strong> NGN {{ number_format($registration->price_cents / 100, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($registration->payment_status) }}</p>
        </div>

        <div class="mb-4">
            <h2 class="font-semibold mb-2">Proceed to Payment</h2>
            <p class="text-sm text-gray-600 mb-4">Click the button below to complete payment via Paystack.</p>
            <form id="payment-form" method="POST" action="{{ route('payment.initiate') }}">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $registration->id }}" />
                <button id="pay-btn" type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400">
                    <span id="btn-text">Pay Now</span>
                </button>
            </form>
        </div>

        <div class="bg-yellow-50 p-4 rounded border border-yellow-200 text-sm">
            <p><strong>Sandbox Mode:</strong> Use test card <strong>4111 1111 1111 1111</strong>, CVV <strong>123</strong>, any future date.</p>
        </div>
    </div>

    <script>
        document.getElementById('payment-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('pay-btn');
            const btnText = document.getElementById('btn-text');
            btn.disabled = true;
            btnText.textContent = 'Processing...';

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
                btnText.textContent = 'Pay Now';
                alert('Error initiating payment. Please try again.');
            }
        });
    </script>
@endsection
