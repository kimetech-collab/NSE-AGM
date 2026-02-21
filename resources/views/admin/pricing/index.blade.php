@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="Pricing Management"
            subtitle="Create and manage pricing versions and items."
        />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-admin.panel class="p-4 shadow-sm">
                <h2 class="text-lg font-semibold mb-3">Create Pricing Version</h2>
                <form method="POST" action="{{ route('admin.pricing.versions.store') }}" class="grid grid-cols-1 gap-3">
                    @csrf
                    <div>
                        <label class="text-sm text-gray-600">Version Name</label>
                        <input type="text" name="version_name" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Starts At</label>
                        <input type="datetime-local" name="starts_at" data-picker="datetime" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Ends At</label>
                        <input type="datetime-local" name="ends_at" data-picker="datetime" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Create Version</button>
                    </div>
                </form>
            </x-admin.panel>

            <x-admin.panel class="p-4 shadow-sm">
                <h2 class="text-lg font-semibold mb-3">Create Pricing Item</h2>
                <form method="POST" action="{{ route('admin.pricing.items.store') }}" class="grid grid-cols-1 gap-3">
                    @csrf
                    <div>
                        <label class="text-sm text-gray-600">Version</label>
                        <select name="pricing_version_id" class="w-full border rounded px-3 py-2" required>
                            @foreach($versions as $version)
                                <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Name</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Description</label>
                        <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Price (kobo)</label>
                        <input type="number" min="0" name="price_cents" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Currency</label>
                        <input type="text" name="currency" value="NGN" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Create Item</button>
                    </div>
                </form>
            </x-admin.panel>
        </div>

        <div class="mt-8 space-y-6">
            @forelse($versions as $version)
                <x-admin.panel class="p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $version->version_name }}</h3>
                            <p class="text-xs text-nse-neutral-600">
                                {{ optional($version->starts_at)->format('Y-m-d H:i') ?? 'No start' }} →
                                {{ optional($version->ends_at)->format('Y-m-d H:i') ?? 'No end' }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('admin.pricing.versions.delete', $version->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm text-red-600">Delete Version</button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('admin.pricing.versions.update', $version->id) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                        @csrf
                        @method('PUT')
                        <input type="text" name="version_name" value="{{ $version->version_name }}" class="border rounded px-3 py-2">
                        <input type="datetime-local" name="starts_at" value="{{ optional($version->starts_at)->format('Y-m-d\\TH:i') }}" data-picker="datetime" class="border rounded px-3 py-2">
                        <input type="datetime-local" name="ends_at" value="{{ optional($version->ends_at)->format('Y-m-d\\TH:i') }}" data-picker="datetime" class="border rounded px-3 py-2">
                        <button class="px-3 py-2 bg-nse-neutral-50 border rounded">Update</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs text-nse-neutral-600 px-2 py-2">Name</th>
                                    <th class="text-left text-xs text-nse-neutral-600 px-2 py-2">Price</th>
                                    <th class="text-left text-xs text-nse-neutral-600 px-2 py-2">Currency</th>
                                    <th class="text-left text-xs text-nse-neutral-600 px-2 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($version->items as $item)
                                    <tr class="border-t">
                                        <td class="px-2 py-2">
                                            <div class="text-sm font-medium">{{ $item->name }}</div>
                                            <div class="text-xs text-nse-neutral-600">{{ $item->description }}</div>
                                        </td>
                                        <td class="px-2 py-2">{{ number_format($item->price_cents / 100, 2) }}</td>
                                        <td class="px-2 py-2">{{ $item->currency }}</td>
                                        <td class="px-2 py-2">
                                            <form method="POST" action="{{ route('admin.pricing.items.update', $item->id) }}" class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="name" value="{{ $item->name }}" class="border rounded px-2 py-1 text-sm">
                                                <input type="text" name="description" value="{{ $item->description }}" class="border rounded px-2 py-1 text-sm">
                                                <input type="number" min="0" name="price_cents" value="{{ $item->price_cents }}" class="border rounded px-2 py-1 text-sm">
                                                <input type="text" name="currency" value="{{ $item->currency }}" class="border rounded px-2 py-1 text-sm">
                                                <button class="px-2 py-1 text-xs border rounded md:col-span-4">Update Item</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.pricing.items.delete', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-xs text-red-600">Delete Item</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-2 py-3 text-sm text-nse-neutral-500">No items yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-admin.panel>
            @empty
                <div class="text-sm text-nse-neutral-600">No pricing versions yet.</div>
            @endforelse
        </div>
    </div>
@endsection
