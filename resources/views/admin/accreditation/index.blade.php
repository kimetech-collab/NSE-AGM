@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Accreditation Scanner</h1>

        @if (session('scan_result'))
            @php $result = session('scan_result'); @endphp
            <div class="mb-4 p-4 rounded {{ $result['ok'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <p class="font-semibold">{{ $result['message'] ?? ($result['ok'] ? 'Valid ticket.' : 'Scan failed.') }}</p>
                @if (!empty($result['registration']))
                    <p class="text-sm">{{ $result['registration']->name }} — {{ $result['registration']->email }}</p>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('admin.accreditation.scan') }}" class="flex gap-3">
            @csrf
            <input type="text" name="token" class="border p-2 flex-1" placeholder="Scan or paste QR token" required />
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Scan</button>
        </form>

        <div class="mt-6 text-sm">
            <a class="text-blue-700 underline" href="{{ route('admin.accreditation.offline') }}">Download offline cache (JSON)</a>
        </div>
    </div>
@endsection
