@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="Certificates"
            subtitle="Eligible: {{ $eligibleCount }} · Release at {{ $eventEndAt->format('Y-m-d H:i') }}"
        />

        <x-admin.panel class="p-4 mb-4">
            <form method="GET" action="{{ route('admin.certificates.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Certificate ID, name, email" class="border rounded px-3 py-2">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All statuses</option>
                    @foreach(['issued','revoked'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-nse-green-700 text-white rounded" type="submit">Filter</button>
                    <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2 border rounded">Reset</a>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.certificates.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="px-3 py-2 border rounded text-sm">Export CSV</a>
                    <a href="{{ route('admin.certificates.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="px-3 py-2 border rounded text-sm">Export PDF</a>
                </div>
            </form>
        </x-admin.panel>

        <x-admin.panel class="p-4 mb-6">
            <form method="POST" action="{{ route('admin.certificates.generate-batch') }}" class="flex items-center gap-3">
                @csrf
                <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Generate Batch</button>
            </form>
        </x-admin.panel>

        <x-admin.panel class="p-4 mb-6">
            <h2 class="text-sm font-semibold mb-3">Issue Override</h2>
            <form method="POST" action="{{ route('admin.certificates.issue') }}" class="flex gap-3">
                @csrf
                <input type="number" name="registration_id" class="border p-2 flex-1" placeholder="Registration ID" required>
                <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Issue</button>
            </form>
        </x-admin.panel>

        <x-admin.table tableClass="min-w-full">
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
                                @if($c->status === 'issued' && !empty($c->registration?->ticket_token))
                                    <a href="{{ route('certificate.show', ['token' => $c->registration->ticket_token]) }}" target="_blank" class="text-xs text-nse-green-700 underline mr-3">Preview</a>
                                @endif
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
        </x-admin.table>

        <x-admin.pagination-footer :paginator="$certificates" />
    </div>
@endsection
