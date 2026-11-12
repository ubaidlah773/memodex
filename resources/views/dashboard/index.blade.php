<x-app-layout>
    <x-slot name="title">Home — MEMODEX</x-slot>

    <div class="space-y-10 lg:space-y-12">
        <!-- Editorial Welcome Header -->
        <section class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 pb-6 border-b border-[#D4DCF0]/[0.08]">
            <div>
                <p class="text-xs font-medium tracking-wider uppercase text-[#D67FF2]">Personal Collection</p>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F6F7FB] mt-1.5">
                    Welcome back, {{ $user->name }}
                </h1>
                <p class="text-sm text-[#BCC6E0] mt-1.5">
                    @if ($totalCards === 0)
                        Your personal 63&times;88 mm trading card archive is ready.
                    @else
                        {{ number_format($totalCards) }} {{ $totalCards === 1 ? 'card' : 'cards' }} preserved across {{ number_format($totalAlbums) }} {{ $totalAlbums === 1 ? 'binder' : 'binders' }}.
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-3 self-start sm:self-auto">
                <a href="{{ route('pokedex.index') }}" class="memodex-btn-secondary">
                    Explore Pokédex
                </a>
                <a href="{{ route('cards.create') }}" class="memodex-btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    <span>Upload Cards</span>
                </a>
            </div>
        </section>

        @if ($totalCards === 0)
            <!-- Empty Collection Onboarding -->
            <section class="space-y-10">
                <x-empty-state
                    title="Begin your personal card archive"
                    description="Upload finished Pokémon card images to store them privately in original quality and mount them in archival 63:88 outer display frames."
                    icon="card">
                    <x-slot:actions>
                        <a href="{{ route('cards.create', ['mode' => 'automatic']) }}" class="memodex-btn-primary">
                            Upload First Cards
                        </a>
                        <a href="{{ route('photos.index') }}" class="memodex-btn-secondary">
                            Open My Collection
                        </a>
                    </x-slot:actions>
                </x-empty-state>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-[#D4DCF0]/[0.08]">
                    <div class="space-y-2">
                        <span class="font-mono text-xs text-[#FFA22B]">01</span>
                        <h3 class="font-display text-base font-semibold text-[#F6F7FB]">100% Original Preservation</h3>
                        <p class="text-sm text-[#BCC6E0] leading-relaxed">Your uploaded artwork, typography, HP, and rarity symbols are never cropped, regenerated, or altered.</p>
                    </div>
                    <div class="space-y-2">
                        <span class="font-mono text-xs text-[#FFA22B]">02</span>
                        <h3 class="font-display text-base font-semibold text-[#F6F7FB]">Archival Outer Frames</h3>
                        <p class="text-sm text-[#BCC6E0] leading-relaxed">Select Classic, Silver, Gold, Electric, Holographic, Rainbow, or Museum-grade outer display mounts.</p>
                    </div>
                    <div class="space-y-2">
                        <span class="font-mono text-xs text-[#FFDB00]">03</span>
                        <h3 class="font-display text-base font-semibold text-[#F6F7FB]">Curated Binders &amp; Sharing</h3>
                        <p class="text-sm text-[#BCC6E0] leading-relaxed">Organize cards across thematic digital binders and share read-only showcase links safely.</p>
                    </div>
                </div>
            </section>
        @else
            <!-- Spotlight Showcase & Collection Progress -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Left: Featured Card Showcase -->
                @if ($featuredCard)
                @php
                    $featFrame = $featuredCard->frame;
                    $featAccent = $featFrame?->config['accent_color'] ?? '#69AFFF';
                    $featPrimary = $featFrame?->config['border_primary'] ?? '#3D4F66';
                @endphp
                <div class="lg:col-span-7 bg-[#26355D] border border-[#D4DCF0]/10 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row gap-6 sm:gap-8 items-center">
                    <a href="{{ route('pokedex.show', $featuredCard) }}"
                       class="w-44 sm:w-48 flex-shrink-0 aspect-card card-stage rounded-xl border border-[#D4DCF0]/15 p-2 overflow-hidden block group">
                        @if ($featuredCard->effectiveFramedPath())
                            <img src="{{ route('media.card.framed', $featuredCard) }}?v={{ $featuredCard->updated_at?->timestamp }}"
                                 alt="{{ $featuredCard->display_name }}"
                                 class="w-full h-full object-contain transition-transform duration-200 group-hover:scale-[1.02]">
                        @elseif ($featuredCard->photo)
                            <img src="{{ route('media.photo.thumbnail', $featuredCard->photo) }}"
                                 alt="{{ $featuredCard->display_name }}"
                                 class="w-full h-full object-contain">
                        @endif
                    </a>

                    <div class="flex-1 min-w-0 text-center sm:text-left space-y-4">
                        <div class="space-y-1.5">
                            <div class="inline-flex items-center gap-2 text-xs font-medium {{ $featuredCard->is_favorite ? 'text-[#FFDB00]' : 'text-[#FFA22B]' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $featuredCard->is_favorite ? 'bg-[#FFDB00]' : 'bg-[#FF8F00]' }}"></span>
                                <span>{{ $featuredCard->is_favorite ? 'Starred Spotlight' : 'Latest Entry' }}</span>
                            </div>
                            <h2 class="font-display text-xl sm:text-2xl font-semibold text-[#F6F7FB] truncate">
                                {{ $featuredCard->display_name }}
                            </h2>
                            <p class="font-mono text-xs text-[#BCC6E0] tabular-nums">
                                {{ $featuredCard->card_number }} &middot; {{ $featuredCard->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-[#D4DCF0]/[0.08] flex flex-wrap items-center justify-center sm:justify-start gap-x-6 gap-y-2 text-xs">
                            <div>
                                <span class="text-[#8B99BD] block">Outer Frame</span>
                                <span class="inline-flex items-center gap-1.5 text-[#F6F7FB] font-medium mt-0.5">
                                    <span class="w-2.5 h-2.5 rounded-full border" style="background-color: {{ $featPrimary }}; border-color: {{ $featAccent }};"></span>
                                    {{ $featFrame?->name ?? 'Classic Frame' }}
                                </span>
                            </div>
                            @if ($featuredCard->albums->count() > 0)
                            <div>
                                <span class="text-[#8B99BD] block">Binders</span>
                                <span class="text-[#D4DCF0] font-medium mt-0.5 block">
                                    {{ $featuredCard->albums->pluck('name')->take(2)->join(', ') }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="pt-2 flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                            <a href="{{ route('pokedex.show', $featuredCard) }}" class="memodex-btn-primary text-xs px-4 py-2">
                                Inspect Card
                            </a>
                            <a href="{{ route('media.card.download.framed', $featuredCard) }}" class="memodex-btn-secondary text-xs px-3.5 py-2">
                                Download PNG
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Right: Collection Telemetry & Progress (Clean Typographic Ledger) -->
                <div class="lg:col-span-{{ $featuredCard ? '5' : '12' }} flex flex-col justify-between gap-6 bg-[#26355D]/60 border border-[#D4DCF0]/10 rounded-2xl p-6">
                    <!-- Clean Hairline Stats Matrix (Not 4 identical SaaS cards) -->
                    <div class="grid grid-cols-2 gap-x-6 gap-y-5 pb-5 border-b border-[#D4DCF0]/[0.08]">
                        <div>
                            <span class="text-xs text-[#8B99BD] block">Archived Cards</span>
                            <div class="flex items-baseline justify-between mt-1">
                                <span class="font-display text-2xl font-semibold text-[#F6F7FB] tabular-nums">{{ number_format($totalCards) }}</span>
                                <a href="{{ route('pokedex.index') }}" class="text-xs text-[#FFA22B] hover:underline">Catalog &rarr;</a>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-[#8B99BD] block">Curated Binders</span>
                            <div class="flex items-baseline justify-between mt-1">
                                <span class="font-display text-2xl font-semibold text-[#F6F7FB] tabular-nums">{{ number_format($totalAlbums) }}</span>
                                <a href="{{ route('albums.index') }}" class="text-xs text-[#FFA22B] hover:underline">Binders &rarr;</a>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-[#8B99BD] block">Starred Favorites</span>
                            <div class="flex items-baseline justify-between mt-1">
                                <span class="font-display text-2xl font-semibold text-[#FFDB00] tabular-nums">{{ number_format($totalFavorites) }}</span>
                                <a href="{{ route('favorites.index') }}" class="text-xs text-[#BCC6E0] hover:text-[#F6F7FB]">View &rarr;</a>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-[#8B99BD] block">Original Vault</span>
                            <div class="flex items-baseline justify-between mt-1">
                                <span class="font-display text-2xl font-semibold text-[#F6F7FB] tabular-nums">{{ number_format($totalPhotos) }}</span>
                                <a href="{{ route('photos.index') }}" class="text-xs text-[#BCC6E0] hover:text-[#F6F7FB]">Assets &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Progress Bars -->
                    <div class="space-y-4">
                        @php
                            $frameRatio = $activeFramesCount > 0 ? min(100, round(($framesCollectedCount / $activeFramesCount) * 100)) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="text-[#BCC6E0] font-medium">Frame Collection Progress</span>
                                <span class="font-mono text-[#F6F7FB] tabular-nums">{{ $framesCollectedCount }} / {{ $activeFramesCount }} frames</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-[#161F3B] overflow-hidden">
                                <div class="h-full rounded-full bg-[#FFDB00] transition-all duration-300" style="width: {{ $frameRatio }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="text-[#BCC6E0] font-medium">Private Storage</span>
                                <span class="font-mono text-[#BCC6E0] tabular-nums">{{ round($storageUsed / (1024 * 1024), 1) }} / {{ round($storageQuota / (1024 * 1024), 0) }} MB ({{ $storagePercent }}%)</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-[#161F3B] overflow-hidden">
                                <div class="h-full rounded-full bg-[#AF47D2] transition-all duration-300" style="width: {{ min(100, $storagePercent) }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Upload Action Link -->
                    <div class="pt-4 border-t border-[#D4DCF0]/[0.08] flex items-center justify-between gap-3">
                        <span class="text-xs text-[#BCC6E0]">Ready to archive another card?</span>
                        <a href="{{ route('cards.create', ['mode' => 'manual']) }}" class="text-xs font-semibold text-[#FFA22B] hover:underline whitespace-nowrap">
                            Open Frame Studio &rarr;
                        </a>
                    </div>
                </div>
            </section>

            <!-- Recently Added Cards -->
            <section class="space-y-5 pt-4 border-t border-[#D4DCF0]/[0.08]">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Recently Added</h2>
                        <p class="text-sm text-[#BCC6E0] mt-0.5">Latest entries preserved in your Pokédex</p>
                    </div>
                    <a href="{{ route('pokedex.index') }}" class="text-sm font-medium text-[#FFA22B] hover:underline whitespace-nowrap">
                        View all ({{ $totalCards }}) &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-5">
                    @foreach ($recentCards as $card)
                        <x-card-tile :card="$card" />
                    @endforeach
                </div>
            </section>

            <!-- Recent Binders / Albums -->
            @if ($recentAlbums->count() > 0)
            <section class="space-y-5 pt-4 border-t border-[#D4DCF0]/[0.08]">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Recent Binders</h2>
                        <p class="text-sm text-[#BCC6E0] mt-0.5">Thematic collections and curated sets</p>
                    </div>
                    <a href="{{ route('albums.index') }}" class="text-sm font-medium text-[#FFA22B] hover:underline whitespace-nowrap">
                        All albums ({{ $totalAlbums }}) &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($recentAlbums as $album)
                        <a href="{{ route('albums.show', $album) }}"
                           class="group bg-[#223056]/55 hover:bg-[#223056] border border-[#D4DCF0]/10 hover:border-[#AF47D2]/35 rounded-xl overflow-hidden transition-all flex flex-col justify-between">
                            <!-- Binder 3-slot 63:88 card preview strip -->
                            <div class="p-3.5 bg-[#11182E]/70 border-b border-[#D4DCF0]/[0.08]">
                                <div class="grid grid-cols-3 gap-2">
                                    @for ($slot = 0; $slot < 3; $slot++)
                                        @php $previewCard = $album->cards->get($slot); @endphp
                                        <div class="aspect-card rounded-md bg-[#161F3B] border border-[#D4DCF0]/10 overflow-hidden flex items-center justify-center p-0.5">
                                            @if ($previewCard && $previewCard->effectiveFramedPath())
                                                <img src="{{ route('media.card.framed', $previewCard) }}?v={{ $previewCard->updated_at?->timestamp }}" alt="{{ $previewCard->display_name }}" class="w-full h-full object-contain" loading="lazy">
                                            @elseif ($previewCard && $previewCard->photo)
                                                <img src="{{ route('media.photo.thumbnail', $previewCard->photo) }}" alt="{{ $previewCard->display_name }}" class="w-full h-full object-contain" loading="lazy">
                                            @else
                                                <div class="w-3 h-4 rounded-sm border border-[#D4DCF0]/10"></div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-display font-semibold text-sm text-[#F6F7FB] group-hover:text-[#FFA22B] truncate transition-colors">
                                            {{ $album->name }}
                                        </h3>
                                        @if ($album->isPublic())
                                            <span class="w-2 h-2 rounded-full bg-[#5CC99B] flex-shrink-0 mt-1.5" title="Public share link enabled"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-[#BCC6E0] mt-1 line-clamp-2 leading-relaxed">
                                        {{ $album->description ?: 'Curated card binder' }}
                                    </p>
                                </div>

                                <div class="pt-2.5 border-t border-[#D4DCF0]/[0.07] flex items-center justify-between text-xs text-[#8B99BD]">
                                    <span class="font-mono tabular-nums">{{ $album->cards_count }} {{ $album->cards_count === 1 ? 'card' : 'cards' }}</span>
                                    <span class="text-[#FFA22B] font-medium">Open &rarr;</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif
        @endif
    </div>
</x-app-layout>
