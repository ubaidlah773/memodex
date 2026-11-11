<x-app-layout>
    <x-slot name="title">Dashboard — MEMODEX</x-slot>

    <div class="space-y-8">
        <!-- 1. Header / Greeting -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">Digital Binder Studio</span>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">
                    Welcome back, {{ $user->name }}
                </h1>
                <p class="text-xs text-memodex-muted mt-1">
                    Your collection holds {{ $totalCards }} card{{ $totalCards === 1 ? '' : 's' }} across {{ $totalAlbums }} binder{{ $totalAlbums === 1 ? '' : 's' }}.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('photos.index') }}" class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-medium border border-memodex-border bg-memodex-raised hover:bg-memodex-soft text-memodex-text transition-colors">
                    Photo Library
                </a>
                <a href="{{ route('cards.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors shadow">
                    + Add Pokémon Cards
                </a>
            </div>
        </div>

        @if ($totalCards === 0)
            <!-- Onboarding Empty State for New Account -->
            <div class="rounded-xl border border-memodex-border bg-memodex-panel p-8 md:p-12 text-center max-w-2xl mx-auto space-y-6">
                <div class="w-16 h-16 mx-auto rounded-full bg-memodex-raised border border-memodex-primary/40 flex items-center justify-center text-memodex-primary shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="space-y-2">
                    <h2 class="text-lg font-bold text-memodex-text">Your Pokédex is ready for its first card</h2>
                    <p class="text-xs text-memodex-muted max-w-md mx-auto leading-relaxed">
                        MEMODEX preserves your authentic Pokémon card artwork 100% intact while adding archive-grade outer display frames.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    <a href="{{ route('cards.create', ['mode' => 'automatic']) }}" class="w-full sm:w-auto px-6 py-2.5 rounded-lg text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors shadow">
                        Upload Pokémon Cards &rarr;
                    </a>
                </div>
            </div>
        @else
            <!-- 2. Hero / Featured Card & Collection Overview -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Featured Card Display -->
                @if ($featuredCard)
                <div class="lg:col-span-5 rounded-xl border border-memodex-border bg-memodex-panel p-6 flex flex-col sm:flex-row gap-6 items-center">
                    <div class="w-44 sm:w-40 flex-shrink-0">
                        <x-card-tile :card="$featuredCard" />
                    </div>
                    <div class="flex-1 space-y-3 text-center sm:text-left">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-mono uppercase bg-memodex-raised text-memodex-primary border border-memodex-primary/30">
                            Featured Item
                        </span>
                        <h3 class="text-base font-bold text-memodex-text">{{ $featuredCard->display_name }}</h3>
                        <p class="text-xs text-memodex-muted">
                            Outer Frame: <span class="text-memodex-text font-medium">{{ $featuredCard->frame->name ?? 'Classic' }}</span>
                        </p>
                        <p class="text-[11px] font-mono text-memodex-subtle">
                            {{ $featuredCard->card_number }} &bull; {{ $featuredCard->created_at->format('M d, Y') }}
                        </p>
                        <div class="pt-1">
                            <a href="{{ route('pokedex.show', $featuredCard) }}" class="inline-flex items-center text-xs font-medium text-memodex-primary hover:text-memodex-primary-strong">
                                View in Pokédex &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Disciplined Collection Summary (4 values only) -->
                <div class="lg:col-span-{{ $featuredCard ? '7' : '12' }} grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-4 rounded-xl border border-memodex-border bg-memodex-panel flex flex-col justify-between">
                        <span class="text-[11px] font-mono text-memodex-subtle uppercase">Total Cards</span>
                        <span class="text-2xl font-bold font-mono text-memodex-text mt-2">{{ $totalCards }}</span>
                        <a href="{{ route('pokedex.index') }}" class="text-[11px] text-memodex-primary mt-2 hover:underline">Explore Pokédex &rarr;</a>
                    </div>
                    <div class="p-4 rounded-xl border border-memodex-border bg-memodex-panel flex flex-col justify-between">
                        <span class="text-[11px] font-mono text-memodex-subtle uppercase">Binders</span>
                        <span class="text-2xl font-bold font-mono text-memodex-text mt-2">{{ $totalAlbums }}</span>
                        <a href="{{ route('albums.index') }}" class="text-[11px] text-memodex-primary mt-2 hover:underline">View Binders &rarr;</a>
                    </div>
                    <div class="p-4 rounded-xl border border-memodex-border bg-memodex-panel flex flex-col justify-between">
                        <span class="text-[11px] font-mono text-memodex-subtle uppercase">Favorites</span>
                        <span class="text-2xl font-bold font-mono text-memodex-text mt-2">{{ $totalFavorites }}</span>
                        <a href="{{ route('favorites.index') }}" class="text-[11px] text-memodex-primary mt-2 hover:underline">View Stars &rarr;</a>
                    </div>
                    <div class="p-4 rounded-xl border border-memodex-border bg-memodex-panel flex flex-col justify-between">
                        <span class="text-[11px] font-mono text-memodex-subtle uppercase">Storage</span>
                        <span class="text-2xl font-bold font-mono text-memodex-text mt-2">{{ $storagePercent }}%</span>
                        <span class="text-[11px] text-memodex-subtle font-mono mt-2">{{ round($storageUsed / (1024 * 1024), 1) }} / 500 MB</span>
                    </div>
                </div>
            </div>

            <!-- 3. Recently Added Cards -->
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-memodex-text">Recent Additions</h2>
                        <p class="text-xs text-memodex-muted">Your latest archived Pokémon cards</p>
                    </div>
                    <a href="{{ route('pokedex.index') }}" class="text-xs font-medium text-memodex-primary hover:text-memodex-primary-strong">
                        View All ({{ $totalCards }}) &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($recentCards as $card)
                        <x-card-tile :card="$card" />
                    @endforeach
                </div>
            </div>

            <!-- 4. Active Binders -->
            @if ($recentAlbums->count() > 0)
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-memodex-text">Themed Binders</h2>
                        <p class="text-xs text-memodex-muted">Organized card sets</p>
                    </div>
                    <a href="{{ route('albums.index') }}" class="text-xs font-medium text-memodex-primary hover:text-memodex-primary-strong">
                        Manage Binders &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($recentAlbums as $album)
                    <a href="{{ route('albums.show', $album) }}" class="p-4 rounded-xl border border-memodex-border bg-memodex-panel hover:bg-memodex-raised transition-colors group block">
                        <div class="flex items-start justify-between">
                            <h3 class="font-semibold text-sm text-memodex-text group-hover:text-memodex-primary transition-colors truncate">
                                {{ $album->name }}
                            </h3>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-memodex-raised border border-memodex-border text-memodex-subtle">
                                {{ $album->visibility }}
                            </span>
                        </div>
                        <p class="text-xs text-memodex-muted mt-2 line-clamp-2">
                            {{ $album->description ?: 'No binder description added.' }}
                        </p>
                        <div class="mt-4 pt-2 border-t border-memodex-border flex items-center justify-between text-xs text-memodex-subtle font-mono">
                            <span>{{ $album->cards_count }} cards</span>
                            <span class="text-memodex-primary">Open &rarr;</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        @endif
    </div>
</x-app-layout>
