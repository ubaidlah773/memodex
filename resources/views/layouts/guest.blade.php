<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MEMODEX — Personal Trading Card Collection' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#101827] text-[#F3F5F8] font-sans antialiased min-h-screen flex flex-col justify-center items-center px-4 py-10 selection:bg-[#69AFFF] selection:text-[#101827]">

    <div class="w-full max-w-[440px] space-y-8">
        <!-- Refined Editorial Brand Header -->
        <div class="flex flex-col items-center text-center space-y-3">
            <x-brand-mark size="lg" />
            <div>
                <h1 class="font-display text-2xl font-bold tracking-[0.18em] text-[#F3F5F8]">MEMODEX</h1>
                <p class="text-xs text-[#9DAABD] mt-1 tracking-wide">Personal Trading Card Archive</p>
            </div>
        </div>

        <!-- Form Surface -->
        <div class="bg-[#17243A] border border-[#C8D2DF]/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
            {{ $slot }}
        </div>

        <p class="text-xs text-center text-[#6D7A8D]">
            &copy; {{ date('Y') }} MEMODEX &middot; 63&times;88 mm Archival Preservation
        </p>
    </div>

</body>
</html>
