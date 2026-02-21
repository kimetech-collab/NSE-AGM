<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-900">
    @php
        $hideGlobalChrome = trim($__env->yieldContent('hideGlobalChrome')) === '1';
        $contentContainerClass = $hideGlobalChrome
            ? 'w-full px-0'
            : 'max-w-4xl mx-auto px-4';
    @endphp

    @unless($hideGlobalChrome)
        @include('partials.header')
    @endunless

    <div class="min-h-screen">
        <div class="{{ $contentContainerClass }}">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @isset($slot)
                {{ $slot }}
            @endisset
            @yield('content')
        </div>
    </div>

    @unless($hideGlobalChrome)
        @include('partials.footer')
    @endunless

    @fluxScripts
</body>
</html>
