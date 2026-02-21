@extends('layouts.admin')

@section('admin_content')
    <div class="py-8 space-y-6">
        <x-admin.page-header
            title="Sponsors"
            subtitle="Manage homepage sponsor display (5-7 active recommended)."
        />

        <x-admin.panel class="p-5">
            <h2 class="text-lg font-semibold text-nse-neutral-900 mb-4">Add Sponsor</h2>
            <form method="POST" action="{{ route('admin.sponsors.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @csrf
                <input name="name" placeholder="Sponsor name" class="border border-nse-neutral-300 rounded px-3 py-2" required>
                <input name="logo_url" placeholder="Logo URL (optional)" class="border border-nse-neutral-300 rounded px-3 py-2">
                <input type="file" name="logo_file" accept="image/*" class="border border-nse-neutral-300 rounded px-3 py-2">
                <input name="website_url" placeholder="Website URL (optional)" class="border border-nse-neutral-300 rounded px-3 py-2">
                <input type="number" min="0" name="sort_order" value="0" class="border border-nse-neutral-300 rounded px-3 py-2">
                <label class="inline-flex items-center gap-2 text-sm text-nse-neutral-700">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Active on homepage
                </label>
                <div>
                    <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Create Sponsor</button>
                </div>
            </form>
        </x-admin.panel>

        <x-admin.table>
                <thead class="bg-nse-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Logo</th>
                        <th class="px-4 py-3 text-left">Website</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sponsors as $sponsor)
                        <tr class="border-t border-nse-neutral-200">
                            <td class="px-4 py-3 align-top">
                                <form method="POST" action="{{ route('admin.sponsors.update', $sponsor) }}" enctype="multipart/form-data" class="space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <input name="name" value="{{ $sponsor->name }}" class="w-full border border-nse-neutral-300 rounded px-2 py-1" required>
                            </td>
                            <td class="px-4 py-3 align-top">
                                    <input type="number" min="0" name="sort_order" value="{{ $sponsor->sort_order }}" class="w-24 border border-nse-neutral-300 rounded px-2 py-1">
                            </td>
                            <td class="px-4 py-3 align-top">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" name="is_active" value="1" @checked($sponsor->is_active)>
                                        <span class="text-xs">{{ $sponsor->is_active ? 'Active' : 'Inactive' }}</span>
                                    </label>
                            </td>
                            <td class="px-4 py-3 align-top">
                                    <input name="logo_url" value="{{ $sponsor->logo_url }}" class="w-full border border-nse-neutral-300 rounded px-2 py-1" placeholder="https://...">
                                    <input type="file" name="logo_file" accept="image/*" class="w-full border border-nse-neutral-300 rounded px-2 py-1 mt-2">
                            </td>
                            <td class="px-4 py-3 align-top">
                                    <input name="website_url" value="{{ $sponsor->website_url }}" class="w-full border border-nse-neutral-300 rounded px-2 py-1" placeholder="https://...">
                            </td>
                            <td class="px-4 py-3 align-top">
                                    <div class="flex items-center gap-2">
                                        <button class="px-3 py-1 bg-nse-green-700 text-white rounded">Save</button>
                                </form>
                                        <form method="POST" action="{{ route('admin.sponsors.delete', $sponsor) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 bg-red-600 text-white rounded" onclick="return confirm('Delete sponsor?')">Delete</button>
                                        </form>
                                    </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-nse-neutral-500">No sponsors found.</td>
                        </tr>
                    @endforelse
                </tbody>
        </x-admin.table>
    </div>
@endsection
