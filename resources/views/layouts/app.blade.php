<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name', 'NSE AGM Portal') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="/build/assets/app.css">
        <script src="/build/assets/app.js" defer></script>
    @endif
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-900">
    <div class="min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
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
            @yield('content')
        </div>
    </div>
</body>
</html>
