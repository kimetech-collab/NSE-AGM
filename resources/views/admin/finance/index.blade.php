@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="Finance"
            subtitle="Review payments and process refunds."
        />

        <x-admin.panel class="p-4 mb-4">
            <form method="GET" action="{{ route('admin.finance.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Reference, name, or email" class="border rounded px-3 py-2">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All statuses</option>
                    @foreach(['pending','success','failed','refunded'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-3 py-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-3 py-2">
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-nse-green-700 text-white rounded" type="submit">Filter</button>
                    <a href="{{ route('admin.finance.index') }}" class="px-4 py-2 border rounded">Reset</a>
                </div>
            </form>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('admin.finance.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="px-3 py-2 border rounded text-sm">Export CSV</a>
                <a href="{{ route('admin.finance.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="px-3 py-2 border rounded text-sm">Export PDF</a>
            </div>
        </x-admin.panel>

        <x-admin.table tableClass="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Reference</th>
                    <th class="px-4 py-2">Amount</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Created</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $t->id }}</td>
                        <td class="px-4 py-2">{{ $t->provider_reference }}</td>
                        <td class="px-4 py-2">{{ number_format($t->amount_cents / 100, 2) }} {{ $t->currency }}</td>
                        <td class="px-4 py-2">{{ $t->status }}</td>
                        <td class="px-4 py-2">{{ $t->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('admin.finance.refund', $t->id) }}" class="refund-form" data-reference="{{ $t->provider_reference }}" data-amount="{{ $t->amount_cents }}">
                                @csrf
                                <button class="text-red-600 refund-btn" type="submit">Refund</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin.table>

        <x-admin.pagination-footer :paginator="$transactions" />
    </div>

    <!-- Refund Confirmation Modal -->
    <div id="refund-modal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded p-6 max-w-lg w-full">
            <h3 class="text-lg font-semibold mb-2">Confirm Refund</h3>
            <p id="refund-info" class="text-sm text-gray-700 mb-4"></p>
            <div class="flex justify-end space-x-3">
                <button id="refund-cancel" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                <button id="refund-confirm" class="px-4 py-2 bg-red-600 text-white rounded">Confirm Refund</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentForm = null;
            document.querySelectorAll('.refund-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    currentForm = form;
                    const ref = form.dataset.reference;
                    const amount = Number(form.dataset.amount) / 100;
                    document.getElementById('refund-info').textContent = `Refund reference ${ref} for NGN ${amount.toFixed(2)}?`;
                    document.getElementById('refund-modal').classList.remove('hidden');
                    document.getElementById('refund-modal').classList.add('flex');
                });
            });

            document.getElementById('refund-cancel').addEventListener('click', function() {
                document.getElementById('refund-modal').classList.add('hidden');
                document.getElementById('refund-modal').classList.remove('flex');
                currentForm = null;
            });

            document.getElementById('refund-confirm').addEventListener('click', function() {
                if (!currentForm) return;
                currentForm.submit();
            });
        });
    </script>
@endsection
