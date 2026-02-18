@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Finance</h1>

        <table class="min-w-full bg-white">
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
        </table>

        <div class="mt-4">{{ $transactions->links() }}</div>
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
