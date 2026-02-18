@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-lg mx-auto">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <h2 class="font-bold text-lg mb-2">Payment Error</h2>
            <p>{{ $message ?? 'An error occurred during payment processing.' }}</p>
        </div>

        <div class="mt-6">
            <p class="text-gray-600 mb-4">Please try again or contact support.</p>
            <a href="/" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 inline-block">
                Return Home
            </a>
        </div>
    </div>
@endsection
