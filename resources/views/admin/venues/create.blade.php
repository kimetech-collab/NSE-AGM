@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 space-y-6">
        <x-admin.page-header title="Add Venue Item" subtitle="Create a new content block for the venue page." />

        <x-admin.panel class="p-6">
            <form method="POST" action="{{ route('admin.venues.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Section *</label>
                        <input type="text" name="section" value="{{ old('section') }}" placeholder="Overview, Travel, Security" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>
                        @error('section')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" max="9999" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                        @error('sort_order')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>
                    @error('title')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Content</label>
                    <textarea name="content" rows="4" class="w-full border border-nse-neutral-300 rounded px-3 py-2">{{ old('content') }}</textarea>
                    @error('content')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Meta list (one line per item)</label>
                    <textarea name="meta" rows="5" class="w-full border border-nse-neutral-300 rounded px-3 py-2" placeholder="Line 1&#10;Line 2">{{ old('meta') }}</textarea>
                    @error('meta')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-nse-neutral-300">
                        <span class="text-sm text-nse-neutral-700">Mark as featured</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-nse-neutral-300">
                        <span class="text-sm text-nse-neutral-700">Visible on public venue page</span>
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-nse-neutral-200">
                    <button type="submit" class="px-5 py-2 bg-nse-green-700 text-white text-sm rounded font-medium hover:bg-nse-green-800">Create Item</button>
                    <a href="{{ route('admin.venues.index') }}" class="px-5 py-2 border border-nse-neutral-300 text-nse-neutral-700 text-sm rounded font-medium hover:bg-nse-neutral-50">Cancel</a>
                </div>
            </form>
        </x-admin.panel>
    </div>
@endsection
