<footer class="bg-white border-t border-nse-neutral-200 mt-10">
    <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 text-xs text-nse-neutral-600">
        <div>
            <p class="font-medium text-nse-neutral-700">© {{ now()->year }} NSE AGM Portal</p>
            <p>Secure registration, payments, attendance and certification platform.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(Route::has('terms'))
                <a href="{{ route('terms') }}" class="hover:text-nse-green-700">Terms</a>
            @endif
            @if(Route::has('contact'))
                <a href="{{ route('contact') }}" class="hover:text-nse-green-700">Contact</a>
            @endif
            @if(Route::has('faqs'))
                <a href="{{ route('faqs') }}" class="hover:text-nse-green-700">FAQs</a>
            @endif
        </div>
    </div>
</footer>
