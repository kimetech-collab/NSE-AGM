@extends('layouts.admin')

@section('admin_content')
    <div class="py-8 space-y-6">
        <x-admin.page-header
            title="Add Speaker"
            subtitle="Create a new speaker profile for the conference."
        />

        <x-admin.panel class="p-6">
            <form method="POST" action="{{ route('admin.speakers.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Basic Information -->
                <fieldset>
                    <legend class="text-lg font-semibold text-nse-neutral-900 mb-4">Basic Information</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">First Name *</label>
                            <input 
                                type="text" 
                                name="first_name" 
                                value="{{ old('first_name') }}"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                                required
                            >
                            @error('first_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Last Name *</label>
                            <input 
                                type="text" 
                                name="last_name" 
                                value="{{ old('last_name') }}"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                                required
                            >
                            @error('last_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Title</label>
                            <input 
                                type="text" 
                                name="title" 
                                value="{{ old('title') }}"
                                placeholder="e.g., Chief Executive Officer"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('title')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Organization</label>
                            <input 
                                type="text" 
                                name="organization" 
                                value="{{ old('organization') }}"
                                placeholder="e.g., ABC Corporation"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('organization')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Biography</label>
                            <textarea 
                                name="bio" 
                                rows="4"
                                placeholder="Brief biography of the speaker..."
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >{{ old('bio') }}</textarea>
                            <p class="text-xs text-nse-neutral-500 mt-1">Maximum 5000 characters</p>
                            @error('bio')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Contact Information -->
                <fieldset>
                    <legend class="text-lg font-semibold text-nse-neutral-900 mb-4">Contact Information</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Phone</label>
                            <input 
                                type="tel" 
                                name="phone" 
                                value="{{ old('phone') }}"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('phone')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Photo & Media -->
                <fieldset>
                    <legend class="text-lg font-semibold text-nse-neutral-900 mb-4">Photo & Media</legend>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Photo URL</label>
                            <input 
                                type="text" 
                                name="photo_url" 
                                value="{{ old('photo_url') }}"
                                placeholder="https://example.com/photo.jpg"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('photo_url')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Upload Photo (JPG, PNG - Max 5MB)</label>
                            <input 
                                type="file" 
                                name="photo_file" 
                                accept="image/jpeg,image/png"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            <p class="text-xs text-nse-neutral-500 mt-1">Leave blank if using Photo URL</p>
                            @error('photo_file')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Social Links -->
                <fieldset>
                    <legend class="text-lg font-semibold text-nse-neutral-900 mb-4">Social Links</legend>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Website</label>
                            <input 
                                type="url" 
                                name="website_url" 
                                value="{{ old('website_url') }}"
                                placeholder="https://..."
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('website_url')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Twitter</label>
                            <input 
                                type="url" 
                                name="twitter_url" 
                                value="{{ old('twitter_url') }}"
                                placeholder="https://twitter.com/..."
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('twitter_url')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">LinkedIn</label>
                            <input 
                                type="url" 
                                name="linkedin_url" 
                                value="{{ old('linkedin_url') }}"
                                placeholder="https://linkedin.com/in/..."
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('linkedin_url')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Expertise & Session -->
                <fieldset>
                    <legend class="text-lg font-semibold text-nse-neutral-900 mb-4">Expertise & Session</legend>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Expertise Topics</label>
                            <input 
                                type="text" 
                                name="expertise_topics" 
                                value="{{ old('expertise_topics') }}"
                                placeholder="e.g., Finance, Technology, Innovation (comma-separated)"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            <p class="text-xs text-nse-neutral-500 mt-1">Separate multiple topics with commas</p>
                            @error('expertise_topics')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Session Title</label>
                            <input 
                                type="text" 
                                name="session_title" 
                                value="{{ old('session_title') }}"
                                placeholder="e.g., The Future of African Markets"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('session_title')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Session Description</label>
                            <textarea 
                                name="session_description" 
                                rows="3"
                                placeholder="Detailed description of the session..."
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >{{ old('session_description') }}</textarea>
                            <p class="text-xs text-nse-neutral-500 mt-1">Maximum 5000 characters</p>
                            @error('session_description')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Session Date & Time</label>
                            <input 
                                type="datetime-local" 
                                name="session_time" 
                                value="{{ old('session_time') }}"
                                class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                            >
                            @error('session_time')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Settings -->
                <fieldset>
                    <legend class="text-lg font-semibold text-nse-neutral-900 mb-4">Settings</legend>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3">
                            <input 
                                type="checkbox" 
                                name="is_keynote" 
                                value="1"
                                {{ old('is_keynote') ? 'checked' : '' }}
                                class="rounded border-nse-neutral-300"
                            >
                            <span class="text-sm text-nse-neutral-700">Mark as Keynote Speaker</span>
                        </label>

                        <label class="flex items-center gap-3">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-nse-neutral-300"
                            >
                            <span class="text-sm text-nse-neutral-700">Active (visible on public page)</span>
                        </label>
                    </div>
                </fieldset>

                <!-- Sort Order -->
                <fieldset>
                    <legend class="text-lg font-semibold text-nse-neutral-900 mb-4">Display Order</legend>
                    <div>
                        <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Sort Order</label>
                        <input 
                            type="number" 
                            name="sort_order" 
                            value="{{ old('sort_order', 0) }}"
                            min="0"
                            max="9999"
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2 focus:ring-2 focus:ring-nse-green-500 focus:border-transparent"
                        >
                        <p class="text-xs text-nse-neutral-500 mt-1">Lower numbers appear first (keynotes first, then sorted by this value)</p>
                        @error('sort_order')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </fieldset>

                <!-- Form Actions -->
                <div class="flex items-center gap-3 pt-6 border-t border-nse-neutral-200">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-nse-green-700 text-white rounded font-medium hover:bg-nse-green-800"
                    >
                        Create Speaker
                    </button>
                    <a 
                        href="{{ route('admin.speakers.index') }}" 
                        class="px-6 py-2 border border-nse-neutral-300 text-nse-neutral-700 rounded font-medium hover:bg-nse-neutral-50"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.panel>
    </div>
@endsection
