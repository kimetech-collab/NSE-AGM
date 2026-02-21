@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="Registrations"
            subtitle="Manage participant records, filters, and exports."
        />

        <x-admin.filter-bar>
        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs text-nse-neutral-600 mb-1">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="w-full border rounded px-3 py-2 text-sm" placeholder="Name or email">
                </div>
                <div>
                    <label class="block text-xs text-nse-neutral-600 mb-1">Payment Status</label>
                    <select name="payment_status" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach(['pending','paid','failed','refunded'] as $s)
                            <option value="{{ $s }}" @selected(request('payment_status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-nse-neutral-600 mb-1">Member</label>
                    <select name="is_member" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">All</option>
                        <option value="1" @selected(request('is_member') === '1')>Yes</option>
                        <option value="0" @selected(request('is_member') === '0')>No</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-nse-neutral-600 mb-1">Per page</label>
                    <select name="per_page" class="w-full border rounded px-3 py-2 text-sm">
                        @foreach([25,50,100] as $pp)
                            <option value="{{ $pp }}" @selected((int) request('per_page', 25) === $pp)>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-nse-neutral-600 mb-1">Date from</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" data-picker="date" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-nse-neutral-600 mb-1">Date to</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" data-picker="date" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div class="flex items-end gap-2">
                    <button class="px-4 py-2 bg-nse-green-700 text-white text-sm rounded">Filter</button>
                    <a href="{{ route('admin.registrations.index') }}" class="px-4 py-2 bg-nse-neutral-50 text-nse-neutral-700 text-sm rounded border">Reset</a>
                </div>
                <div class="flex items-end gap-2">
                    <a href="{{ route('admin.registrations.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="px-4 py-2 bg-white text-nse-neutral-700 text-sm rounded border">Export CSV</a>
                    <a href="{{ route('admin.registrations.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="px-4 py-2 bg-white text-nse-neutral-700 text-sm rounded border">Export PDF</a>
                </div>
            </div>
        </form>
        </x-admin.filter-bar>

        <x-admin.table tableClass="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Member</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Registered</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $r->id }}</td>
                        <td class="px-4 py-2">{{ $r->name }}</td>
                        <td class="px-4 py-2">{{ $r->email }}</td>
                        <td class="px-4 py-2">{{ $r->is_member ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-2">{{ $r->payment_status }}</td>
                        <td class="px-4 py-2">{{ optional($r->registration_timestamp)->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.registrations.show', $r->id) }}" class="text-blue-600">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin.table>

        <x-admin.pagination-footer :paginator="$registrations" />
    </div>
@endsection
