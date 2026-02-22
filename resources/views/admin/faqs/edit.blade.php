@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 space-y-6">
        <x-admin.page-header title="Edit FAQ" subtitle="Update this frequently asked question." />

        <x-admin.panel class="p-6">
            <form method="POST" action="{{ route('admin.faqs.update', $faqItem) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Category *</label>
                        <input type="text" name="category" value="{{ old('category', $faqItem->category) }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>
                        @error('category')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $faqItem->sort_order) }}" min="0" max="9999" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                        @error('sort_order')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Question *</label>
                    <input type="text" name="question" value="{{ old('question', $faqItem->question) }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>
                    @error('question')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-nse-neutral-700 mb-1">Answer *</label>
                    <textarea name="answer" rows="6" class="w-full border border-nse-neutral-300 rounded px-3 py-2" required>{{ old('answer', $faqItem->answer) }}</textarea>
                    @error('answer')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faqItem->is_active) ? 'checked' : '' }} class="rounded border-nse-neutral-300">
                    <span class="text-sm text-nse-neutral-700">Visible on public FAQs page</span>
                </label>

                <div class="flex items-center gap-3 pt-4 border-t border-nse-neutral-200">
                    <button type="submit" class="px-5 py-2 bg-nse-green-700 text-white text-sm rounded font-medium hover:bg-nse-green-800">Save Changes</button>
                    <a href="{{ route('admin.faqs.index') }}" class="px-5 py-2 border border-nse-neutral-300 text-nse-neutral-700 text-sm rounded font-medium hover:bg-nse-neutral-50">Cancel</a>
                </div>
            </form>
        </x-admin.panel>
    </div>
@endsection
