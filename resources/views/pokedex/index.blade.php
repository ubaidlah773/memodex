<x-app-layout>
    <x-slot name="title">Pokédex — MEMODEX</x-slot>

    @php
        $activeFilterCount = collect([$frameId, $albumId, $dateFilter])->filter()->count();
    @endphp

    <div class="space-y-6" x-data="{ filtersOpen: {{ $activeFilterCount > 0 ? 'true' : 'false' }} }">
        <!-- Page Header -->
        <section class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-5 border-b border-[#D4DCF0]/[0.08]">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F6F7FB]">Pokédex</h1>
                <p class="text-sm text-[#BCC6E0] mt-1">
                    {{ number_format($cards->total()) }} archived {{ $cards->total() === 1 ? 'card' : 'cards' }} in 63&times;88 mm archival mounts.
                </p>
            </div>
            <a href="{{ route('cards.create') }}" class="memodex-btn-primary self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Add Cards</span>
            </a>
        </section>

        <!-- Compact Search, Filter & View Controls -->
        <form method="GET" action="{{ route('pokedex.index') }}" class="space-y-3">
            <input type="hidden" name="view" value="{{ $viewMode }}">

            <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
                <!-- Search Field -->
                <div class="relative flex-1 min-w-0">
                    <svg class="w-4 h-4 text-[#8B99BD] absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search by card name or MDX number..."
                           class="w-full bg-[#26355D] border border-[#3B4E82] rounded-lg pl-10 pr-4 py-2 text-sm text-[#F6F7FB] placeholder:text-[#8B99BD] focus:outline-none focus:border-[#AF47D2] focus:ring-1 focus:ring-[#AF47D2]">
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-2.5">
                    <!-- Filter Drawer Toggle -->
                    <button type="button"
                            @click="filtersOpen = !filtersOpen"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-sm font-medium border transition-colors {{ $activeFilterCount > 0 ? 'bg-[#304273] border-[#AF47D2]/50 text-[#F6F7FB]' : 'bg-[#26355D] border-[#3B4E82] text-[#BCC6E0] hover:text-[#F6F7FB]' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                        <span>Filters</span>
                        @if ($activeFilterCount > 0)
                            <span class="w-5 h-5 rounded-full bg-[#FF8F00] text-[#161F3B] text-[11px] font-semibold flex items-center justify-center">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    <!-- Quick Sort Select -->
                    <div class="flex-1 sm:flex-none sm:w-40">
                        <select name="sort" onchange="this.form.submit()" aria-label="Sort cards"
                                class="w-full bg-[#26355D] border border-[#3B4E82] rounded-lg px-3 py-2 text-sm text-[#F6F7FB] focus:outline-none focus:border-[#AF47D2]">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="number" {{ $sort === 'number' ? 'selected' : '' }}>Card Number</option>
                            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name (A–Z)</option>
                        </select>
                    </div>

                    <!-- View Switcher -->
                    <div class="flex items-center bg-[#26355D] border border-[#3B4E82] rounded-lg p-0.5 flex-shrink-0">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                           class="p-1.5 rounded-md transition-colors {{ $viewMode === 'grid' ? 'bg-[#304273] text-[#FFA22B]' : 'text-[#8B99BD] hover:text-[#F6F7FB]' }}"
                           title="Grid View" aria-label="Grid View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                           class="p-1.5 rounded-md transition-colors {{ $viewMode === 'list' ? 'bg-[#304273] text-[#FFA22B]' : 'text-[#8B99BD] hover:text-[#F6F7FB]' }}"
                           title="List View" aria-label="List View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Collapsible Filter Controls Panel -->
            <div x-show="filtersOpen" x-cloak x-transition
                 class="p-4 rounded-xl bg-[#26355D] border border-[#D4DCF0]/10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label for="frame_id" class="block text-xs text-[#BCC6E0] mb-1">Outer Frame</label>
                    <select id="frame_id" name="frame_id" class="memodex-select py-2">
                        <option value="">All Frames</option>
                        @foreach ($frames as $fr)
                            <option value="{{ $fr->id }}" {{ (string)$frameId === (string)$fr->id ? 'selected' : '' }}>{{ $fr->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="album_id" class="block text-xs text-[#BCC6E0] mb-1">Album / Binder</label>
                    <select id="album_id" name="album_id" class="memodex-select py-2">
                        <option value="">All Binders</option>
                        @foreach ($albums as $alb)
                            <option value="{{ $alb->id }}" {{ (string)$albumId === (string)$alb->id ? 'selected' : '' }}>{{ $alb->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-xs text-[#BCC6E0] mb-1">Date Added</label>
                    <select id="date" name="date" class="memodex-select py-2">
                        <option value="">All Time</option>
                        <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this_week" {{ $dateFilter === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>

                <div class="sm:hidden">
                    <label for="sort_mobile" class="block text-xs text-[#BCC6E0] mb-1">Sort Order</label>
                    <select id="sort_mobile" name="sort" class="memodex-select py-2">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="number" {{ $sort === 'number' ? 'selected' : '' }}>Card Number</option>
                        <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name (A–Z)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 lg:col-span-2 justify-end">
                    @if ($search || $frameId || $albumId || $dateFilter)
                        <a href="{{ route('pokedex.index', ['view' => $viewMode]) }}" class="memodex-btn-ghost text-xs">
                            Clear All
                        </a>
                    @endif
                    <button type="submit" class="memodex-btn-primary text-xs px-5 py-2.5">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>

        <!-- Card Gallery / List / Empty States -->
        @if ($cards->count() === 0)
            @if ($search || $frameId || $albumId || $dateFilter)
                <x-empty-state
                    title="No matching cards found"
                    description="No entries in your Pokédex matched your current search or filter criteria."
                    icon="search">
                    <x-slot:actions>
                        <a href="{{ route('pokedex.index', ['view' => $viewMode]) }}" class="memodex-btn-secondary">
                            Reset Filters
                        </a>
                    </x-slot:actions>
                </x-empty-state>
            @else
                <x-empty-state
                    title="Your Pokédex is ready"
                    description="Upload finished Pokémon card images to store them intact and mount them in custom outer frames."
                    icon="card">
                    <x-slot:actions>
                        <a href="{{ route('cards.create') }}" class="memodex-btn-primary">
                            Upload First Cards
                        </a>
                    </x-slot:actions>
                </x-empty-state>
            @endif
        @else
            @if ($viewMode === 'list')
                <!-- Editorial Catalog List View -->
                <div class="rounded-xl border border-[#D4DCF0]/10 bg-[#26355D]/70 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#223056]/80 text-xs font-medium text-[#BCC6E0] border-b border-[#D4DCF0]/10">
                                <tr>
                                    <th class="py-3.5 px-4">Card</th>
                                    <th class="py-3.5 px-4">Serial</th>
                                    <th class="py-3.5 px-4">Outer Frame</th>
                                    <th class="py-3.5 px-4 hidden md:table-cell">Binders</th>
                                    <th class="py-3.5 px-4 hidden sm:table-cell">Archived</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#D4DCF0]/[0.07]">
                                @foreach ($cards as $card)
                                    @php
                                        $fc = $card->frame?->config ?? [];
                                    @endphp
                                    <tr class="hover:bg-[#223056]/60 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3.5">
                                                <a href="{{ route('pokedex.show', $card) }}" class="w-11 aspect-card rounded-md bg-[#11182E] border border-[#D4DCF0]/15 overflow-hidden flex-shrink-0 p-0.5">
                                                    @if ($card->effectiveFramedPath())
                                                        <img src="{{ route('media.card.framed', $card) }}" alt="{{ $card->display_name }}" class="w-full h-full object-contain" loading="lazy">
                                                    @endif
                                                </a>
                                                <a href="{{ route('pokedex.show', $card) }}" class="font-medium text-[#F6F7FB] hover:text-[#FFA22B] transition-colors">
                                                    {{ $card->display_name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-mono text-xs text-[#BCC6E0] tabular-nums whitespace-nowrap">
                                            {{ $card->card_number }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center gap-2 text-xs text-[#D4DCF0]">
                                                <span class="w-2.5 h-2.5 rounded-full border flex-shrink-0" style="background-color: {{ $fc['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></span>
                                                {{ $card->frame?->name ?? 'Classic Frame' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 hidden md:table-cell text-xs text-[#BCC6E0]">
                                            {{ $card->albums->count() > 0 ? $card->albums->pluck('name')->join(', ') : '—' }}
                                        </td>
                                        <td class="py-3 px-4 hidden sm:table-cell text-xs text-[#8B99BD] whitespace-nowrap">
                                            {{ $card->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <form method="POST" action="{{ route('favorites.toggle', $card) }}">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-[#304273] transition-colors {{ $card->is_favorite ? 'text-[#FFDB00]' : 'text-[#8B99BD] hover:text-[#D4DCF0]' }}" title="Toggle Favorite">
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    </button>
                                                </form>
                                                <a href="{{ route('pokedex.show', $card) }}" class="memodex-btn-secondary text-xs px-3 py-1.5">
                                                    Inspect
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
                <!-- Balanced Responsive 63:88 Card Gallery -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-5 lg:gap-6">
                    @foreach ($cards as $card)
                        <x-card-tile :card="$card" />
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            @if ($cards->hasPages())
            <div class="pt-4">
                {{ $cards->links() }}
            </div>
            @endif
        @endif
    </div>
</x-app-layout>
