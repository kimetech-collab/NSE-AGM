@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Certificates</h1>
            <p class="text-sm text-nse-neutral-600">Eligible: {{ $eligibleCount }} · Release at {{ $eventEndAt->format('Y-m-d H:i') }}</p>
        </div>

        <div class="bg-white p-4 rounded border border-nse-neutral-200 mb-6">
            <form method="POST" action="{{ route('admin.certificates.generate-batch') }}" class="flex items-center gap-3">
                @csrf
                <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Generate Batch</button>
            </form>
        </div>

        <div class="bg-white p-4 rounded border border-nse-neutral-200 mb-6">
            <h2 class="text-sm font-semibold mb-3">Issue Override</h2>
            <form method="POST" action="{{ route('admin.certificates.issue') }}" class="flex gap-3">
                @csrf
                <input type="number" name="registration_id" class="border p-2 flex-1" placeholder="Registration ID" required>
                <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Issue</button>
            </form>
        </div>

        <div class="bg-white rounded border border-nse-neutral-200 overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-xs text-nse-neutral-600">
                        <th class="px-4 py-2">Certificate ID</th>
                        <th class="px-4 py-2">Registrant</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Issued</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $c)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $c->certificate_id }}</td>
                            <td class="px-4 py-2">
                                {{ $c->registration->name ?? 'Unknown' }}<br>
                                <span class="text-xs text-nse-neutral-500">{{ $c->registration->email ?? '' }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $c->status }}</td>
                            <td class="px-4 py-2">{{ optional($c->issued_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">
                                @if($c->status !== 'revoked')
                                    <form method="POST" action="{{ route('admin.certificates.revoke', $c->id) }}" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="reason" class="border px-2 py-1 text-xs" placeholder="Reason">
                                        <button class="text-xs text-red-600">Revoke</button>
                                    </form>
                                @else
                                    <span class="text-xs text-nse-neutral-500">Revoked</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-sm text-nse-neutral-500">No certificates yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $certificates->links() }}</div>
    </div>
@endsection
