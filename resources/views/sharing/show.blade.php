<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $album->name }} — MEMODEX Shared Binder</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-memodex-bg text-memodex-text font-sans antialiased min-h-screen flex flex-col selection:bg-memodex-primary selection:text-memodex-bg">

    <!-- Public Brand Header -->
    <header class="h-16 border-b border-memodex-border bg-memodex-panel px-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded border border-memodex-primary/40 bg-memodex-raised flex items-center justify-center text-memodex-primary font-bold text-sm tracking-wider">
                M
            </div>
            <div>
                <span class="font-bold tracking-widest text-sm text-memodex-text">MEMODEX</span>
                <span class="block text-[10px] text-memodex-subtle uppercase tracking-wider font-mono">Shared Portfolio</span>
            </div>
        </div>

        @if (auth()->check())
            <a href="{{ route('dashboard') }}" class="text-xs text-memodex-primary hover:underline">
                Open My Studio &rarr;
            </a>
        @else
            <a href="{{ route('login') }}" class="text-xs text-memodex-primary hover:underline">
                Collector Sign In &rarr;
            </a>
        @endif
    </header>

    <!-- Shared Album Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
        <!-- Binder Hero Banner -->
        <div class="p-8 rounded-xl border border-memodex-border bg-memodex-panel space-y-3">
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-memodex-raised border border-memodex-border text-memodex-primary uppercase font-semibold">
                    Public Trading Card Binder
                </span>
                <span class="text-[11px] font-mono text-memodex-subtle">
                    {{ $album->cards->count() }} Cards
                </span>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-memodex-text">{{ $album->name }}</h1>

            @if ($album->description)
                <p class="text-sm text-memodex-muted max-w-2xl leading-relaxed">
                    {{ $album->description }}
                </p>
            @endif
        </div>

        <!-- Cards Collection -->
        @if ($album->cards->count() === 0)
            <div class="p-12 text-center rounded-xl border border-memodex-border bg-memodex-panel">
                <p class="text-sm font-semibold text-memodex-text">This shared binder is currently empty</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach ($album->cards as $card)
                    <div class="rounded-xl border border-memodex-border bg-memodex-panel overflow-hidden card-collectible flex flex-col justify-between">
                        <!-- Card Viewport with token authorized render URL -->
                        <div class="w-full aspect-card bg-memodex-bg overflow-hidden relative">
                            @if ($card->render_path)
                                <img src="{{ route('media.card.render', $card) }}?token={{ $sharedLink->token }}" alt="{{ $card->name }}" class="w-full h-full object-cover select-none" loading="lazy">
                            @endif
                        </div>

                        <div class="p-3 border-t border-memodex-border bg-memodex-panel">
                            <div class="flex items-start justify-between gap-1 mb-1">
                                <span class="font-semibold text-xs text-memodex-text truncate">{{ $card->name }}</span>
                                @if ($card->hp)
                                    <span class="text-[10px] font-mono text-memodex-accent-warm font-semibold flex-shrink-0">{{ $card->hp }} HP</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between text-[10px] font-mono text-memodex-subtle pt-1 border-t border-memodex-border/40">
                                <span>{{ $card->card_number }}</span>
                                <span>{{ $card->rarity }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="py-6 border-t border-memodex-border text-center text-xs text-memodex-subtle font-mono">
        Curated with MEMODEX &bull; Physical Trading Card Archive
    </footer>
</body>
</html>
