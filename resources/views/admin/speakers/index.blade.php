@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-3">
            <x-admin.page-header
                title="Speakers"
                subtitle="Manage keynote and invited speakers for the conference."
            />
            <a href="{{ route('admin.speakers.create') }}" class="inline-flex items-center px-4 py-2 bg-nse-green-700 text-white text-sm rounded hover:bg-nse-green-800">
                Add Speaker
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($speakers->count() > 0)
            <x-admin.filter-bar>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2">
                        <label for="speaker-search" class="block text-xs text-nse-neutral-600 mb-1">Search</label>
                        <input
                            type="text"
                            id="speaker-search"
                            placeholder="Search by name or organization"
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2 text-sm"
                        >
                    </div>
                    <div>
                        <label for="speaker-status" class="block text-xs text-nse-neutral-600 mb-1">Status</label>
                        <select id="speaker-status" class="w-full border border-nse-neutral-300 rounded px-3 py-2 text-sm">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="speaker-type" class="block text-xs text-nse-neutral-600 mb-1">Type</label>
                        <select id="speaker-type" class="w-full border border-nse-neutral-300 rounded px-3 py-2 text-sm">
                            <option value="">All</option>
                            <option value="keynote">Keynote</option>
                            <option value="invited">Invited</option>
                        </select>
                    </div>
                </div>

                <div id="bulk-actions" class="hidden mt-3 p-3 bg-nse-neutral-50 border border-nse-neutral-200 rounded flex-wrap items-center gap-3 justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-nse-neutral-700" id="selected-count">0 selected</span>
                        <select id="bulk-action" class="border border-nse-neutral-300 rounded px-3 py-1.5 text-sm">
                            <option value="">Choose action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button id="bulk-apply" class="px-3 py-1.5 bg-nse-green-700 text-white rounded text-sm hover:bg-nse-green-800">Apply</button>
                    </div>
                    <button id="bulk-cancel" class="text-sm text-nse-neutral-600 hover:text-nse-neutral-900">Clear selection</button>
                </div>
            </x-admin.filter-bar>

            <x-admin.table tableClass="min-w-full bg-white text-sm">
                <thead class="bg-nse-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-nse-neutral-300">
                        </th>
                        <th class="px-4 py-3 text-left">Speaker</th>
                        <th class="px-4 py-3 text-left">Organization</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Session</th>
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($speakers as $speaker)
                        <tr class="border-t border-nse-neutral-200 speaker-row" data-name="{{ strtolower($speaker->full_name) }}" data-org="{{ strtolower((string) $speaker->organization) }}" data-status="{{ $speaker->is_active ? 'active' : 'inactive' }}" data-type="{{ $speaker->is_keynote ? 'keynote' : 'invited' }}">
                            <td class="px-4 py-3 align-top">
                                <input type="checkbox" class="speaker-checkbox rounded border-nse-neutral-300" value="{{ $speaker->id }}">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($speaker->photo_url)
                                        <img src="{{ $speaker->photo_url }}" alt="{{ $speaker->full_name }}" class="w-10 h-10 rounded-full object-cover border border-nse-neutral-200 bg-nse-neutral-100">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-nse-neutral-200 flex items-center justify-center text-xs text-nse-neutral-600 font-semibold">
                                            {{ strtoupper(substr($speaker->first_name, 0, 1)) }}{{ strtoupper(substr($speaker->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-nse-neutral-900">{{ $speaker->full_name }}</div>
                                        @if($speaker->email)
                                            <div class="text-xs text-nse-neutral-500">{{ $speaker->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-nse-neutral-900">{{ $speaker->organization ?: '—' }}</div>
                                @if($speaker->title)
                                    <div class="text-xs text-nse-neutral-600">{{ $speaker->title }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $speaker->is_keynote ? 'bg-nse-green-100 text-nse-green-800' : 'bg-nse-neutral-100 text-nse-neutral-700' }}">
                                    {{ $speaker->is_keynote ? 'Keynote' : 'Invited' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $speaker->is_active ? 'bg-green-100 text-green-800' : 'bg-nse-neutral-100 text-nse-neutral-700' }}">
                                    {{ $speaker->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($speaker->session_title)
                                    <div class="text-sm text-nse-neutral-900 max-w-xs truncate">{{ $speaker->session_title }}</div>
                                    @if($speaker->session_time)
                                        <div class="text-xs text-nse-neutral-500">{{ $speaker->session_time->format('M d, Y H:i') }}</div>
                                    @endif
                                @else
                                    <span class="text-xs text-nse-neutral-500">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-nse-neutral-100 text-xs text-nse-neutral-700">{{ $speaker->sort_order }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.speakers.edit', $speaker) }}" class="px-3 py-1 text-xs bg-nse-neutral-100 text-nse-neutral-700 rounded hover:bg-nse-neutral-200">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.speakers.delete', $speaker) }}" class="inline" onsubmit="return confirm('Delete this speaker?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 text-xs bg-red-50 text-red-700 rounded hover:bg-red-100">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin.table>
        @else
            <x-admin.panel class="p-12 text-center">
                <svg class="w-12 h-12 text-nse-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h5v-2a9 9 0 00-9-9H6a9 9 0 00-9 9v2h5v-2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-nse-neutral-900 mb-2">No Speakers</h3>
                <p class="text-nse-neutral-600 mb-4">Get started by adding your first speaker.</p>
                <a href="{{ route('admin.speakers.create') }}" class="inline-block px-4 py-2 bg-nse-green-700 text-white rounded text-sm font-medium hover:bg-nse-green-800">
                    Add First Speaker
                </a>
            </x-admin.panel>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('speaker-search');
            const statusFilter = document.getElementById('speaker-status');
            const typeFilter = document.getElementById('speaker-type');
            const rows = document.querySelectorAll('.speaker-row');
            const selectAllCheckbox = document.getElementById('select-all');
            const speakerCheckboxes = document.querySelectorAll('.speaker-checkbox');
            const bulkActionsDiv = document.getElementById('bulk-actions');
            const selectedCountSpan = document.getElementById('selected-count');
            const bulkActionSelect = document.getElementById('bulk-action');
            const bulkApplyBtn = document.getElementById('bulk-apply');
            const bulkCancelBtn = document.getElementById('bulk-cancel');

            if (!searchInput || !statusFilter || !typeFilter || rows.length === 0) {
                return;
            }

            function updateDisplay() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const typeValue = typeFilter.value;

                rows.forEach(function(row) {
                    const name = row.dataset.name || '';
                    const org = row.dataset.org || '';
                    const status = row.dataset.status;
                    const type = row.dataset.type;

                    const matchesSearch = name.includes(searchTerm) || org.includes(searchTerm);
                    const matchesStatus = !statusValue || status === statusValue;
                    const matchesType = !typeValue || type === typeValue;

                    row.style.display = matchesSearch && matchesStatus && matchesType ? '' : 'none';
                });

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
                speakerCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                updateBulkActions();
            }

            function updateBulkActions() {
                const checkedCount = document.querySelectorAll('.speaker-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkActionsDiv.classList.remove('hidden');
                    bulkActionsDiv.style.display = 'flex';
                    selectedCountSpan.textContent = checkedCount + ' selected';
                } else {
                    bulkActionsDiv.classList.add('hidden');
                    bulkActionsDiv.style.display = 'none';
                    bulkActionSelect.value = '';
                }
            }

            searchInput.addEventListener('input', updateDisplay);
            statusFilter.addEventListener('change', updateDisplay);
            typeFilter.addEventListener('change', updateDisplay);

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    const visibleRows = Array.from(rows).filter(function(row) {
                        return row.style.display !== 'none';
                    });

                    visibleRows.forEach(function(row) {
                        const checkbox = row.querySelector('.speaker-checkbox');
                        if (checkbox) {
                            checkbox.checked = isChecked;
                        }
                    });

                    updateBulkActions();
                });
            }

            speakerCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateBulkActions);
            });

            if (bulkApplyBtn) {
                bulkApplyBtn.addEventListener('click', function() {
                    const action = bulkActionSelect.value;
                    if (!action) {
                        return;
                    }

                    const checkedIds = Array.from(document.querySelectorAll('.speaker-checkbox:checked')).map(function(cb) {
                        return cb.value;
                    });
                    if (checkedIds.length === 0) {
                        return;
                    }

                    if (action === 'delete' && !confirm('Delete ' + checkedIds.length + ' speaker(s)?')) {
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.speakers.bulk") }}';

                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="action" value="${action}">
                        ${checkedIds.map(id => `<input type="hidden" name="speakers[]" value="${id}">`).join('')}
                    `;

                    document.body.appendChild(form);
                    form.submit();
                });
            }

            if (bulkCancelBtn) {
                bulkCancelBtn.addEventListener('click', function() {
                    speakerCheckboxes.forEach(function(cb) {
                        cb.checked = false;
                    });
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = false;
                    }
                    updateBulkActions();
                });
            }
        });
    </script>
    @endpush
@endsection
