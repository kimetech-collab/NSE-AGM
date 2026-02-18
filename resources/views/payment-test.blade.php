@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-lg mx-auto">
        <div class="bg-blue-50 border border-blue-200 p-4 rounded mb-6">
            <p class="text-sm text-blue-700">
                <strong>Development Mode:</strong> This is a test payment form. Click "Complete Payment" to simulate a successful payment and proceed to your ticket.
            </p>
        </div>

        <h1 class="text-2xl font-bold mb-4">Test Payment</h1>

        <div class="bg-gray-100 p-4 rounded mb-6">
            <p><strong>Name:</strong> {{ $registration->name }}</p>
            <p><strong>Email:</strong> {{ $registration->email }}</p>
            <p><strong>Amount:</strong> NGN {{ number_format($registration->price_cents / 100, 2) }}</p>
            <p><strong>Reference:</strong> <code class="bg-white px-2 py-1 rounded text-xs">{{ $reference }}</code></p>
        </div>

        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
            <h3 class="font-semibold text-yellow-900 mb-2">Simulated Payment Details</h3>
            <p class="text-sm text-yellow-800 mb-3">In development, this form simulates a payment gateway. Choose an action below:</p>
            
            <div class="space-y-3">
                <a href="{{ route('payment.test.success', ['reference' => $reference]) }}" 
                   class="block w-full px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700 text-center font-medium">
                    ✓ Complete Payment (Success)
                </a>
                
                <a href="/" 
                   class="block w-full px-6 py-3 bg-red-600 text-white rounded hover:bg-red-700 text-center font-medium">
                    ✗ Cancel Payment
                </a>
            </div>
        </div>

        <div class="text-sm text-gray-600">
            <p><strong>Note:</strong> This test form only appears when using dummy Paystack keys. Configure real Paystack sandbox keys in <code class="bg-gray-100 px-1 rounded">.env</code> to use the actual payment gateway.</p>
        </div>
    </div>
@endsection
