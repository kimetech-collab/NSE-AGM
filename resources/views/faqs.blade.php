@extends('layouts.public')

@section('title', 'FAQs — NSE 59th AGM & Conference')

@section('content')
<section class="bg-white py-16 sm:py-20" aria-labelledby="faq-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="text-center mb-10">
            <p class="text-nse-neutral-500 text-xs font-semibold uppercase tracking-widest mb-2">Help & Support</p>
            <h1 id="faq-heading" class="text-3xl font-bold text-nse-neutral-900">Frequently Asked Questions</h1>
            <p class="text-sm text-nse-neutral-600 mt-3 max-w-2xl mx-auto">Search and browse answers to common conference questions.</p>
        </div>

        @if($faqItems->count() > 0)
            <div class="bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-4 mb-8">
                <input
                    type="text"
                    placeholder="Search FAQs..."
                    x-data
                    @input="document.querySelectorAll('[data-faq-item]').forEach(item => {
                        const query = $event.target.value.toLowerCase();
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(query) ? '' : 'none';
                    });"
                    class="w-full border border-nse-neutral-300 rounded px-3 py-2 text-sm"
                />
            </div>

            <div class="space-y-8">
                @foreach($groupedFaqs as $category => $items)
                    <div>
                        <h2 class="text-xl font-semibold text-nse-neutral-900 mb-4">{{ $category }}</h2>
                        <div class="space-y-3">
                            @foreach($items as $item)
                                <div data-faq-item class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden" x-data="{ open: false }">
                                    <button @click="open = !open" :aria-expanded="open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-nse-neutral-50 transition-colors">
                                        <span class="text-left text-sm font-semibold text-nse-neutral-900">{{ $item->question }}</span>
                                        <svg class="w-4 h-4 text-nse-neutral-600 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="open" x-transition class="px-5 pb-4 bg-nse-neutral-50 border-t border-nse-neutral-200">
                                        <p class="text-sm text-nse-neutral-700 leading-relaxed">{{ $item->answer }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg">
                <h3 class="text-lg font-semibold text-nse-neutral-900 mb-2">No FAQs available yet</h3>
                <p class="text-sm text-nse-neutral-600">Frequently asked questions will appear here once configured.</p>
            </div>
        @endif
    </div>
</section>
@endsection
