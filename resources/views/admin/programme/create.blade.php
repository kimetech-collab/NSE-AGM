@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 space-y-6">
        <x-admin.page-header
            title="Add Programme Item"
            subtitle="Create a schedule item for the conference programme."
        />

        <x-admin.panel class="p-6">
            <form method="POST" action="{{ route('admin.programme.store') }}" class="space-y-6">
                @csrf

                <fieldset class="border border-nse-neutral-200 rounded-lg p-4 md:p-5">
                    <legend class="text-base font-semibold text-nse-neutral-900 px-2">Schedule Details</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>
                            @error('title')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Category</label>
                            <input type="text" name="category" value="{{ old('category') }}" placeholder="Keynote, Workshop, Break" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            @error('category')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Programme Date *</label>
                            <input type="date" name="programme_date" value="{{ old('programme_date') }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>
                            @error('programme_date')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Start Time *</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>
                            @error('start_time')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            @error('end_time')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Track</label>
                            <input type="text" name="track" value="{{ old('track') }}" placeholder="Technical Track" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            @error('track')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Location</label>
                            <input type="text" name="location" value="{{ old('location') }}" placeholder="Main Hall" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            @error('location')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Speaker / Facilitator</label>
                            <input type="text" name="speaker_name" value="{{ old('speaker_name') }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            @error('speaker_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Display Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" max="9999" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            @error('sort_order')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Description</label>
                            <textarea name="description" rows="4" class="w-full border border-nse-neutral-300 rounded px-3 py-2">{{ old('description') }}</textarea>
                            @error('description')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border border-nse-neutral-200 rounded-lg p-4 md:p-5">
                    <legend class="text-base font-semibold text-nse-neutral-900 px-2">Visibility</legend>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-nse-neutral-300">
                            <span class="text-sm text-nse-neutral-700">Mark as featured item</span>
                        </label>

                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-nse-neutral-300">
                            <span class="text-sm text-nse-neutral-700">Visible on public programme page</span>
                        </label>
                    </div>
                </fieldset>

                <div class="flex items-center gap-3 pt-4 border-t border-nse-neutral-200">
                    <button type="submit" class="px-5 py-2 bg-nse-green-700 text-white text-sm rounded font-medium hover:bg-nse-green-800">Create Item</button>
                    <a href="{{ route('admin.programme.index') }}" class="px-5 py-2 border border-nse-neutral-300 text-nse-neutral-700 text-sm rounded font-medium hover:bg-nse-neutral-50">Cancel</a>
                </div>
            </form>
        </x-admin.panel>
    </div>
@endsection
