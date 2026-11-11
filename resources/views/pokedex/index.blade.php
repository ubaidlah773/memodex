<x-app-layout>
    <x-slot name="title">Pokédex Archive — MEMODEX</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">Collection Catalog</span>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Pokédex</h1>
                <p class="text-xs text-memodex-muted mt-1">
                    Complete archive of your authentic Pokémon cards with decorative outer frames ({{ $cards->total() }} total entries).
                </p>
            </div>
            <a href="{{ route('cards.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors self-start sm:self-auto shadow">
                + Add Pokémon Cards
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('pokedex.index') }}" class="p-4 rounded-xl bg-memodex-panel border border-memodex-border space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <!-- Search -->
                <div class="lg:col-span-3">
                    <label for="search" class="block text-[11px] font-mono text-memodex-subtle uppercase mb-1">Search Card</label>
                    <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Search by name, number..."
                           class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs text-memodex-text placeholder:text-memodex-subtle focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
                </div>

                <!-- Frame Filter -->
                <div class="lg:col-span-2">
                    <label for="frame_id" class="block text-[11px] font-mono text-memodex-subtle uppercase mb-1">Outer Frame</label>
                    <select id="frame_id" name="frame_id" class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
                        <option value="">All Frames</option>
                        @foreach ($frames as $fr)
                            <option value="{{ $fr->id }}" {{ (string)$frameId === (string)$fr->id ? 'selected' : '' }}>{{ $fr->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Album / Binder Filter -->
                <div class="lg:col-span-2">
                    <label for="album_id" class="block text-[11px] font-mono text-memodex-subtle uppercase mb-1">Binder</label>
                    <select id="album_id" name="album_id" class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
                        <option value="">All Binders</option>
                        @foreach ($albums as $alb)
                            <option value="{{ $alb->id }}" {{ (string)$albumId === (string)$alb->id ? 'selected' : '' }}>{{ $alb->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="lg:col-span-2">
                    <label for="date" class="block text-[11px] font-mono text-memodex-subtle uppercase mb-1">Upload Date</label>
                    <select id="date" name="date" class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
                        <option value="">All Time</option>
                        <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this_week" {{ $dateFilter === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>

                <!-- Sort -->
                <div class="lg:col-span-2">
                    <label for="sort" class="block text-[11px] font-mono text-memodex-subtle uppercase mb-1">Sort Order</label>
                    <select id="sort" name="sort" class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="number" {{ $sort === 'number' ? 'selected' : '' }}>Card Number</option>
                        <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    </select>
                </div>

                <!-- Filter Actions & View Mode Toggle -->
                <div class="lg:col-span-1 flex items-center gap-1.5">
                    <button type="submit" class="w-full py-1.5 px-3 bg-memodex-raised hover:bg-memodex-soft border border-memodex-border text-memodex-text rounded-md text-xs font-semibold text-center transition-colors">
                        Filter
                    </button>
                </div>
            </div>

            <!-- View Mode Switcher -->
            <div class="flex items-center justify-between pt-2 border-t border-memodex-border/40 text-xs">
                <span class="text-[11px] text-memodex-muted font-mono">
                    Showing {{ $cards->firstItem() ?? 0 }} - {{ $cards->lastItem() ?? 0 }} of {{ $cards->total() }} entries
                </span>

                <div class="flex items-center gap-1">
                    <span class="text-[11px] font-mono text-memodex-subtle mr-2">View:</span>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                       class="p-1.5 rounded border {{ $viewMode === 'grid' ? 'bg-memodex-soft border-memodex-primary text-memodex-primary' : 'border-memodex-border text-memodex-muted hover:text-memodex-text' }}"
                       title="Grid View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                       class="p-1.5 rounded border {{ $viewMode === 'list' ? 'bg-memodex-soft border-memodex-primary text-memodex-primary' : 'border-memodex-border text-memodex-muted hover:text-memodex-text' }}"
                       title="List View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </a>
                </div>
            </div>
        </form>

        <!-- Card Grid / List / Empty States -->
        @if ($cards->count() === 0)
            @if ($search || $frameId || $albumId || $dateFilter)
                <div class="p-12 text-center rounded-xl border border-memodex-border bg-memodex-panel space-y-3">
                    <p class="text-sm font-semibold text-memodex-text">No matching cards found</p>
                    <p class="text-xs text-memodex-muted">Try clearing your search query or filters.</p>
                    <a href="{{ route('pokedex.index') }}" class="inline-block mt-2 text-xs text-memodex-primary hover:underline">
                        Reset all filters &rarr;
                    </a>
                </div>
            @else
                <div class="p-12 text-center rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <div class="w-12 h-12 mx-auto rounded-full bg-memodex-raised border border-memodex-border flex items-center justify-center text-memodex-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-memodex-text">Your Pokédex is currently empty</h3>
                        <p class="text-xs text-memodex-muted mt-1">Upload Pokémon card images to begin building your digital collection binder.</p>
                    </div>
                    <a href="{{ route('cards.create') }}" class="inline-flex items-center px-4 py-2 bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg rounded-lg text-xs font-semibold shadow">
                        + Add First Pokémon Card
                    </a>
                </div>
            @endif
        @else
            @if ($viewMode === 'list')
                <!-- List View -->
                <div class="rounded-xl border border-memodex-border bg-memodex-panel overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-memodex-raised text-[10px] font-mono uppercase text-memodex-subtle border-b border-memodex-border">
                                <tr>
                                    <th class="py-3 px-4">Card</th>
                                    <th class="py-3 px-4">Number</th>
                                    <th class="py-3 px-4">Frame</th>
                                    <th class="py-3 px-4">Date Added</th>
                                    <th class="py-3 px-4">Binders</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-memodex-border/60">
                                @foreach ($cards as $card)
                                    <tr class="hover:bg-memodex-soft/40 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('pokedex.show', $card) }}" class="w-10 h-14 rounded bg-[#0C1118] border border-memodex-border overflow-hidden flex-shrink-0">
                                                    <img src="{{ route('media.card.framed', $card) }}" alt="{{ $card->display_name }}" class="w-full h-full object-contain">
                                                </a>
                                                <a href="{{ route('pokedex.show', $card) }}" class="font-semibold text-memodex-text hover:text-memodex-primary transition-colors">
                                                    {{ $card->display_name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-mono font-bold text-memodex-text">
                                            {{ $card->card_number }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $pillBg = $card->frame->config['outer_bg'] ?? '#141D2B';
                                                $pillColor = $card->frame->config['accent_color'] ?? '#83B9F2';
                                                $pillBorder = $card->frame->config['border_primary'] ?? '#3D4F66';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono border"
                                                  style="background-color: {{ $pillBg }}; color: {{ $pillColor }}; border-color: {{ $pillBorder }};">
                                                {{ $card->frame->name ?? 'Classic' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-memodex-muted">
                                            {{ $card->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($card->albums->count() > 0)
                                                <span class="text-memodex-text">{{ $card->albums->pluck('name')->join(', ') }}</span>
                                            @else
                                                <span class="text-memodex-subtle">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <form method="POST" action="{{ route('favorites.toggle', $card) }}">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 rounded hover:bg-memodex-raised {{ $card->is_favorite ? 'text-amber-400' : 'text-memodex-subtle' }}" title="Toggle Favorite">
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    </button>
                                                </form>
                                                <a href="{{ route('pokedex.show', $card) }}" class="px-2.5 py-1 rounded bg-memodex-raised hover:bg-memodex-soft text-memodex-text font-medium transition-colors">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Grid View -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($cards as $card)
                        <x-card-tile :card="$card" />
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            <div class="pt-6">
                {{ $cards->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
