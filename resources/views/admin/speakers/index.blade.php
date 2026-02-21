@extends('layouts.admin')

@section('admin_content')
    <div class="py-8 space-y-6">
        <div class="flex items-center justify-between">
            <x-admin.page-header
                title="Speakers"
                subtitle="Manage keynote and invited speakers for the conference."
            />
            <a href="{{ route('admin.speakers.create') }}" class="px-4 py-2 bg-nse-green-700 text-white rounded font-medium hover:bg-nse-green-800">
                Add Speaker
            </a>
        </div>

        @if ($speakers->count() > 0)
            <x-admin.panel class="p-5">
                <div class="flex items-center gap-4 mb-4">
                    <input 
                        type="text" 
                        id="speaker-search" 
                        placeholder="Search speakers..."
                        class="flex-1 border border-nse-neutral-300 rounded px-3 py-2"
                    >
                    <select id="speaker-status" class="border border-nse-neutral-300 rounded px-3 py-2">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <select id="speaker-type" class="border border-nse-neutral-300 rounded px-3 py-2">
                        <option value="">All Types</option>
                        <option value="keynote">Keynote</option>
                        <option value="invited">Invited</option>
                    </select>
                </div>

                <div id="bulk-actions" class="hidden mb-4 p-3 bg-nse-blue-50 border border-nse-blue-200 rounded flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium" id="selected-count">0 selected</span>
                        <select id="bulk-action" class="border border-nse-neutral-300 rounded px-3 py-1 text-sm">
                            <option value="">-- Action --</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button id="bulk-apply" class="px-3 py-1 bg-nse-blue-600 text-white rounded text-sm hover:bg-nse-blue-700">Apply</button>
                    </div>
                    <button id="bulk-cancel" class="text-sm text-nse-neutral-600 hover:text-nse-neutral-900">Clear</button>
                </div>
            </x-admin.panel>

            <x-admin.table>
                <thead class="bg-nse-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded">
                        </th>
                        <th class="px-4 py-3 text-left">Photo</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Title/Organization</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Session</th>
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($speakers as $speaker)
                        <tr class="border-t border-nse-neutral-200 speaker-row" data-name="{{ $speaker->full_name }}" data-status="{{ $speaker->is_active ? 'active' : 'inactive' }}" data-type="{{ $speaker->is_keynote ? 'keynote' : 'invited' }}">
                            <td class="px-4 py-3">
                                <input type="checkbox" class="speaker-checkbox rounded" value="{{ $speaker->id }}">
                            </td>
                            <td class="px-4 py-3">
                                @if($speaker->photo_url)
                                    <img src="{{ $speaker->photo_url }}" alt="{{ $speaker->full_name }}" class="w-12 h-12 rounded object-cover bg-nse-neutral-100">
                                @else
                                    <div class="w-12 h-12 rounded bg-nse-neutral-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-nse-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-nse-neutral-900">{{ $speaker->full_name }}</div>
                                @if($speaker->email)
                                    <div class="text-xs text-nse-neutral-500">{{ $speaker->email }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-nse-neutral-900">{{ $speaker->title }}</div>
                                <div class="text-xs text-nse-neutral-600">{{ $speaker->organization }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $speaker->is_keynote ? 'bg-nse-green-100 text-nse-green-800' : 'bg-nse-neutral-100 text-nse-neutral-800' }}">
                                    {{ $speaker->is_keynote ? 'Keynote' : 'Invited' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $speaker->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $speaker->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($speaker->session_title)
                                    <div class="text-sm text-nse-neutral-900 line-clamp-1">{{ $speaker->session_title }}</div>
                                    @if($speaker->session_time)
                                        <div class="text-xs text-nse-neutral-500">{{ $speaker->session_time->format('M d, Y H:i') }}</div>
                                    @endif
                                @else
                                    <span class="text-xs text-nse-neutral-500">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 bg-nse-neutral-100 text-nse-neutral-700 text-xs rounded font-medium">{{ $speaker->sort_order }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.speakers.edit', $speaker) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.speakers.destroy', $speaker) }}" class="inline" onsubmit="return confirm('Delete this speaker?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200">
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
                <a href="{{ route('admin.speakers.create') }}" class="inline-block px-4 py-2 bg-nse-green-700 text-white rounded font-medium hover:bg-nse-green-800">
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

            function updateDisplay() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const typeValue = typeFilter.value;

                rows.forEach(row => {
                    const name = row.dataset.name.toLowerCase();
                    const status = row.dataset.status;
                    const type = row.dataset.type;

                    const matchesSearch = name.includes(searchTerm);
                    const matchesStatus = !statusValue || status === statusValue;
                    const matchesType = !typeValue || type === typeValue;

                    row.style.display = matchesSearch && matchesStatus && matchesType ? '' : 'none';
                });
            }

            function updateBulkActions() {
                const checkedCount = document.querySelectorAll('.speaker-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkActionsDiv.classList.remove('hidden');
                    selectedCountSpan.textContent = checkedCount + ' selected';
                } else {
                    bulkActionsDiv.classList.add('hidden');
                    bulkActionSelect.value = '';
                }
            }

            searchInput.addEventListener('input', updateDisplay);
            statusFilter.addEventListener('change', updateDisplay);
            typeFilter.addEventListener('change', updateDisplay);

            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                visibleRows.forEach(row => {
                    const checkbox = row.querySelector('.speaker-checkbox');
                    if (checkbox) checkbox.checked = isChecked;
                });
                updateBulkActions();
            });

            speakerCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });

            bulkApplyBtn.addEventListener('click', function() {
                const action = bulkActionSelect.value;
                if (!action) return;

                const checkedIds = Array.from(document.querySelectorAll('.speaker-checkbox:checked')).map(cb => cb.value);
                if (checkedIds.length === 0) return;

                if (action === 'delete' && !confirm('Delete ' + checkedIds.length + ' speaker(s)?')) return;

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

            bulkCancelBtn.addEventListener('click', function() {
                speakerCheckboxes.forEach(cb => cb.checked = false);
                selectAllCheckbox.checked = false;
                updateBulkActions();
            });
        });
    </script>
    @endpush
@endsection
