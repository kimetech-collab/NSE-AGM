<header class="bg-white border-b border-nse-neutral-200 sticky top-0 z-20">
    <div class="max-w-6xl mx-auto px-4 py-3">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-nse-green-700"></span>
                <span class="text-lg font-semibold text-nse-green-700">NSE AGM Portal</span>
            </a>

            <button id="site-mobile-menu-toggle" type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded border border-nse-neutral-200 text-nse-neutral-700 hover:bg-nse-neutral-50" aria-label="Toggle navigation" aria-expanded="false" aria-controls="site-mobile-menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <nav class="hidden md:flex items-center gap-2 text-sm">
                <a href="{{ route('home') }}" class="px-3 py-1.5 rounded {{ request()->routeIs('home') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Home</a>

                @if(Route::has('programme'))
                    <a href="{{ route('programme') }}" class="px-3 py-1.5 rounded {{ request()->routeIs('programme') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Programme</a>
                @endif

                @if(Route::has('pricing'))
                    <a href="{{ route('pricing') }}" class="px-3 py-1.5 rounded {{ request()->routeIs('pricing') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Pricing</a>
                @endif

                @if(Route::has('sponsors'))
                    <a href="{{ route('sponsors') }}" class="px-3 py-1.5 rounded {{ request()->routeIs('sponsors') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Sponsors</a>
                @endif

                @if(Route::has('speakers'))
                    <a href="{{ route('speakers') }}" class="px-3 py-1.5 rounded {{ request()->routeIs('speakers') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Speakers</a>
                @endif

                @auth
                    @if(Route::has('dashboard'))
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded border border-nse-neutral-200 text-nse-neutral-700 hover:text-nse-green-700 hover:border-nse-green-700">Dashboard</a>
                    @endif
                @else
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="px-3 py-1.5 rounded border border-nse-green-700 text-nse-green-700 hover:bg-nse-green-700 hover:text-white">Register</a>
                    @endif
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="px-3 py-1.5 rounded bg-nse-green-700 text-white hover:bg-nse-green-800">Login</a>
                    @endif
                @endauth
            </nav>
        </div>

        <nav id="site-mobile-menu" class="hidden md:hidden mt-3 border-t border-nse-neutral-200 pt-3">
            <div class="grid grid-cols-1 gap-2 text-sm">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded {{ request()->routeIs('home') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Home</a>

                @if(Route::has('programme'))
                    <a href="{{ route('programme') }}" class="px-3 py-2 rounded {{ request()->routeIs('programme') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Programme</a>
                @endif

                @if(Route::has('pricing'))
                    <a href="{{ route('pricing') }}" class="px-3 py-2 rounded {{ request()->routeIs('pricing') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Pricing</a>
                @endif

                @if(Route::has('sponsors'))
                    <a href="{{ route('sponsors') }}" class="px-3 py-2 rounded {{ request()->routeIs('sponsors') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Sponsors</a>
                @endif

                @if(Route::has('speakers'))
                    <a href="{{ route('speakers') }}" class="px-3 py-2 rounded {{ request()->routeIs('speakers') ? 'bg-nse-green-700 text-white' : 'text-nse-neutral-700 hover:text-nse-green-700 hover:bg-nse-neutral-50' }}">Speakers</a>
                @endif

                @auth
                    @if(Route::has('dashboard'))
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded border border-nse-neutral-200 text-nse-neutral-700 hover:text-nse-green-700 hover:border-nse-green-700">Dashboard</a>
                    @endif
                @else
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="px-3 py-2 rounded border border-nse-green-700 text-nse-green-700 hover:bg-nse-green-700 hover:text-white">Register</a>
                    @endif
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded bg-nse-green-700 text-white hover:bg-nse-green-800">Login</a>
                    @endif
                @endauth
            </div>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('site-mobile-menu-toggle');
        const menu = document.getElementById('site-mobile-menu');

        if (!toggle || !menu) {
            return;
        }

        const closeMenu = () => {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
        };

        toggle.addEventListener('click', function () {
            menu.classList.toggle('hidden');
            toggle.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');
        });

        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                closeMenu();
            }
        });
    });
</script>
