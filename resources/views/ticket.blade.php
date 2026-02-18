@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-lg mx-auto border p-6 rounded">
        <h1 class="text-xl font-bold mb-2">Ticket</h1>
        <p><strong>Name:</strong> {{ $registration->name }}</p>
        <p><strong>Email:</strong> {{ $registration->email }}</p>
        <p><strong>Payment status:</strong> {{ $registration->payment_status }}</p>
        <div class="mt-4">
            <h2 class="font-semibold">QR Token</h2>
            <div class="p-4 bg-gray-100 rounded mt-2">{{ $registration->ticket_token ?? 'Not issued' }}</div>
        </div>
        <form method="POST" action="{{ route('ticket.download') }}" class="mt-6">
            @csrf
            <input type="hidden" name="token" value="{{ $registration->ticket_token }}" />
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Download PDF</button>
        </form>
    </div>
@endsection
