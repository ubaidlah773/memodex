<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $album->name }} — MEMODEX Shared Binder</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#101827] text-[#F3F5F8] font-sans antialiased min-h-screen flex flex-col selection:bg-[#69AFFF] selection:text-[#101827]"
      x-data="{ activeCard: null }">

    <!-- Public Exhibition Header -->
    <header class="h-16 border-b border-[#C8D2DF]/[0.08] bg-[#17243A]/90 backdrop-blur-md px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <x-brand-mark size="sm" />
            <div>
                <span class="font-display font-bold tracking-[0.16em] text-sm text-[#F3F5F8]">MEMODEX</span>
                <span class="block text-[11px] text-[#6D7A8D]">Shared Collection</span>
            </div>
        </div>

        @if (auth()->check())
            <a href="{{ route('dashboard') }}" class="memodex-btn-secondary text-xs px-3.5 py-2">
                Open My Archive &rarr;
            </a>
        @else
            <a href="{{ route('login') }}" class="memodex-btn-secondary text-xs px-3.5 py-2">
                Collector Sign In &rarr;
            </a>
        @endif
    </header>

    <!-- Exhibition Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 space-y-10">
        <!-- Editorial Binder Intro -->
        <section class="pb-8 border-b border-[#C8D2DF]/[0.08] space-y-3">
            <div class="flex items-center gap-2.5 text-xs">
                <span class="inline-flex items-center gap-1.5 text-[#5CC99B] font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#5CC99B]"></span>
                    Public Showcase
                </span>
                <span class="text-[#6D7A8D]">&middot;</span>
                <span class="font-mono text-[#9DAABD] tabular-nums">{{ $album->cards->count() }} {{ $album->cards->count() === 1 ? 'card' : 'cards' }}</span>
            </div>

            <h1 class="font-display text-3xl sm:text-4xl font-semibold tracking-tight text-[#F3F5F8]">{{ $album->name }}</h1>

            @if ($album->description)
                <p class="text-base text-[#9DAABD] max-w-2xl leading-relaxed">{{ $album->description }}</p>
            @endif
        </section>

        <!-- Cards Showcase Grid -->
        @if ($album->cards->count() === 0)
            <x-empty-state
                title="This shared binder is empty"
                description="The collector has not added any cards to this binder yet."
                icon="binder" />
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($album->cards as $card)
                    @php
                        $imgUrl = $card->effectiveFramedPath()
                            ? route('media.card.framed', $card) . '?token=' . $sharedLink->token
                            : ($card->photo ? route('media.photo.thumbnail', $card->photo) : '');
                        $frameName = $card->frame?->name ?? 'Classic Frame';
                        $fc = $card->frame?->config ?? [];
                    @endphp
                    <button type="button"
                            @click="activeCard = { name: '{{ addslashes($card->display_name) }}', number: '{{ $card->card_number }}', frame: '{{ addslashes($frameName) }}', img: '{{ $imgUrl }}' }"
                            class="text-left group flex flex-col bg-[#1D293B]/60 hover:bg-[#1D293B] rounded-xl border border-[#C8D2DF]/10 hover:border-[#69AFFF]/35 overflow-hidden card-collectible focus:outline-none">
                        <div class="w-full aspect-card card-stage p-2 overflow-hidden">
                            @if ($imgUrl)
                                <img src="{{ $imgUrl }}" alt="{{ $card->display_name }}" class="w-full h-full object-contain select-none transition-transform duration-200 group-hover:scale-[1.015]" loading="lazy">
                            @endif
                        </div>

                        <div class="px-3.5 py-3 border-t border-[#C8D2DF]/[0.08] w-full space-y-1">
                            <p class="text-sm font-semibold text-[#F3F5F8] group-hover:text-[#69AFFF] truncate transition-colors">{{ $card->display_name }}</p>
                            <div class="flex items-center justify-between text-xs text-[#6D7A8D]">
                                <span class="font-mono text-[#9DAABD] tabular-nums">{{ $card->card_number }}</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full border" style="background-color: {{ $fc['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></span>
                                    <span class="truncate">{{ str_replace(' Frame', '', $frameName) }}</span>
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Lightbox Inspection Modal -->
    <div x-show="activeCard" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="activeCard = null">
        <div class="fixed inset-0 bg-[#070B14]/85 backdrop-blur-sm" @click="activeCard = null"></div>
        <div class="relative z-10 max-w-md w-full bg-[#17243A] border border-[#C8D2DF]/15 rounded-2xl p-5 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-display text-base font-semibold text-[#F3F5F8]" x-text="activeCard?.name"></p>
                    <p class="text-xs font-mono text-[#9DAABD]" x-text="activeCard ? activeCard.number + ' · ' + activeCard.frame : ''"></p>
                </div>
                <button type="button" @click="activeCard = null" class="p-1.5 text-[#9DAABD] hover:text-[#F3F5F8] text-xl leading-none">&times;</button>
            </div>
            <div class="aspect-card card-stage rounded-xl border border-[#C8D2DF]/10 p-3 flex items-center justify-center">
                <template x-if="activeCard?.img">
                    <img :src="activeCard.img" :alt="activeCard?.name" class="w-full h-full object-contain">
                </template>
            </div>
        </div>
    </div>

    <footer class="py-6 border-t border-[#C8D2DF]/[0.08] text-center text-xs text-[#6D7A8D]">
        Curated with MEMODEX &middot; 63&times;88 mm Archival Card Preservation
    </footer>
</body>
</html>
