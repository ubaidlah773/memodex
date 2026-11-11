<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MEMODEX — Collectible Archive' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-memodex-bg text-memodex-text font-sans antialiased min-h-screen flex flex-col justify-center items-center p-4 selection:bg-memodex-primary selection:text-memodex-bg">

    <div class="w-full max-w-md space-y-6">
        <!-- Brand -->
        <div class="text-center space-y-2">
            <div class="w-10 h-10 mx-auto rounded border border-memodex-primary/40 bg-memodex-raised flex items-center justify-center text-memodex-primary font-bold text-base tracking-wider shadow">
                M
            </div>
            <h1 class="text-xl font-bold tracking-widest text-memodex-text">MEMODEX</h1>
            <p class="text-xs text-memodex-subtle uppercase tracking-wider font-mono">Personal Collectible Archive</p>
        </div>

        <!-- Auth Card Container -->
        <div class="bg-memodex-panel border border-memodex-border rounded-xl p-8 shadow-2xl">
            {{ $slot }}
        </div>

        <p class="text-[11px] text-center text-memodex-subtle font-mono">
            &copy; {{ date('Y') }} MEMODEX Archive &bull; Physical-Proportion Digital Cards
        </p>
    </div>

</body>
</html>
