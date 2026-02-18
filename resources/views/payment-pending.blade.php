@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-2xl font-bold mb-4">Payment Pending</h1>
        <p class="text-gray-700 mb-2">
            We are verifying your payment. This usually completes within a minute.
        </p>
        <p class="text-sm text-gray-600">
            Reference: <strong>{{ $reference }}</strong>
        </p>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="text-blue-700 underline">Return to dashboard</a>
        </div>
    </div>
@endsection
