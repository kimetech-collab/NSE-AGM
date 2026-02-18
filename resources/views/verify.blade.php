@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-4">Verify Email</h1>
        
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <p class="text-gray-600 mb-4">Enter the 6-digit OTP sent to <strong>{{ $registration->email }}</strong></p>
        
        <form method="POST" action="{{ route('register.verify') }}">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}" />
            <div class="mb-4">
                <label class="block font-semibold">OTP Code</label>
                <input name="otp" type="text" maxlength="6" class="border p-2 w-full" placeholder="000000" required />
            </div>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Verify
            </button>
        </form>

        @if (session('otp'))
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded text-sm">
                <p><strong>Testing:</strong> OTP for this session is <code class="bg-white px-2 py-1">{{ session('otp') }}</code></p>
            </div>
        @endif
    </div>
@endsection

