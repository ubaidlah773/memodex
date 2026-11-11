<x-app-layout>
    <x-slot name="title">{{ $card->display_name }} ({{ $card->card_number }}) — MEMODEX</x-slot>

    <div x-data="{
        viewTab: 'framed', // 'framed' or 'original'
        currentFrameId: '{{ $card->frame_id }}'
    }" class="space-y-6">

        <!-- Breadcrumb & Top Bar -->
        <div class="flex items-center justify-between text-xs font-mono text-memodex-subtle">
            <a href="{{ route('pokedex.index') }}" class="hover:text-memodex-primary flex items-center gap-1.5 transition-colors">
                &larr; Back to Pokédex
            </a>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-memodex-raised border border-memodex-border text-memodex-text font-bold">
                    {{ $card->card_number }}
                </span>
            </div>
        </div>

        <!-- Main Card Viewer Split Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Card Display Container with Tabs (Framed vs Original) -->
            <div class="lg:col-span-6 flex flex-col items-center">
                <!-- Viewer Tab Switcher -->
                <div class="flex items-center gap-1 p-1 bg-memodex-panel border border-memodex-border rounded-lg mb-4 text-xs font-medium">
                    <button type="button" @click="viewTab = 'framed'"
                            :class="viewTab === 'framed' ? 'bg-memodex-soft text-memodex-primary font-semibold shadow-sm' : 'text-memodex-muted hover:text-memodex-text'"
                            class="px-4 py-1.5 rounded transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6v6H9z"/></svg>
                        Framed Card
                    </button>
                    <button type="button" @click="viewTab = 'original'"
                            :class="viewTab === 'original' ? 'bg-memodex-soft text-memodex-primary font-semibold shadow-sm' : 'text-memodex-muted hover:text-memodex-text'"
                            class="px-4 py-1.5 rounded transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Original Card (Unaltered)
                    </button>
                </div>

                <!-- Main Card Frame Container (63:88 aspect ratio) -->
                <div class="w-full max-w-sm rounded-xl overflow-hidden border border-memodex-border bg-[#0C1118] shadow-2xl relative aspect-card flex items-center justify-center p-2">
                    <!-- Tab 1: Framed View -->
                    <div x-show="viewTab === 'framed'" class="w-full h-full flex items-center justify-center">
                        <img src="{{ route('media.card.framed', $card) }}?v={{ $card->updated_at->timestamp }}"
                             alt="{{ $card->display_name }} (Framed)"
                             class="max-w-full max-h-full object-contain select-none shadow-inner">
                    </div>

                    <!-- Tab 2: Original Untouched Card -->
                    <div x-show="viewTab === 'original'" class="w-full h-full flex items-center justify-center p-3">
                        <img src="{{ route('media.card.original', $card) }}"
                             alt="{{ $card->display_name }} (Original)"
                             class="max-w-full max-h-full object-contain select-none">
                    </div>
                </div>

                <!-- Download Buttons -->
                <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('media.card.download.framed', $card) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors shadow">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Framed PNG
                    </a>

                    <a href="{{ route('media.card.download.original', $card) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium border border-memodex-border bg-memodex-raised hover:bg-memodex-soft text-memodex-text transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Original
                    </a>
                </div>
            </div>

            <!-- Right: Details, Frame Switcher & Card Management -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Card Header Box -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">POKÉDEX ENTRY</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold"
                                      style="background-color: {{ ($card->frame->config['outer_bg'] ?? '#141D2B') }}; color: {{ ($card->frame->config['accent_color'] ?? '#83B9F2') }}; border: 1px solid {{ ($card->frame->config['border_primary'] ?? '#3D4F66') }};">
                                    {{ $card->frame->name ?? 'Classic Frame' }}
                                </span>
                            </div>
                            <h1 class="text-2xl font-bold text-memodex-text mt-1.5">{{ $card->display_name }}</h1>
                            <p class="text-xs font-mono text-memodex-subtle mt-0.5">{{ $card->card_number }}</p>
                        </div>

                        <!-- Favorite Button -->
                        <form method="POST" action="{{ route('favorites.toggle', $card) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-memodex-border bg-memodex-raised hover:bg-memodex-soft text-xs flex items-center gap-1.5 transition-colors {{ $card->is_favorite ? 'text-amber-400 border-amber-400/40 bg-amber-400/10' : 'text-memodex-muted' }}">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span>{{ $card->is_favorite ? 'Favorited' : 'Favorite' }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Integrity Guarantee -->
                    <div class="p-3 rounded-lg bg-memodex-bg border border-memodex-border text-[11px] text-memodex-muted flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-memodex-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Card artwork, character, text, and gameplay stats are 100% authentic and unaltered.</span>
                    </div>

                    <!-- File Details Table -->
                    <div class="pt-2 border-t border-memodex-border/60 grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-[10px] font-mono uppercase text-memodex-subtle block">Source File</span>
                            <span class="text-memodex-text font-mono truncate block" title="{{ $card->photo?->original_name }}">{{ $card->photo?->original_name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono uppercase text-memodex-subtle block">Archived Date</span>
                            <span class="text-memodex-text">{{ $card->created_at->format('M d, Y • H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono uppercase text-memodex-subtle block">Dimensions</span>
                            <span class="text-memodex-text font-mono">{{ $card->photo?->width ?? '—' }} &times; {{ $card->photo?->height ?? '—' }} px</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono uppercase text-memodex-subtle block">Original File Size</span>
                            <span class="text-memodex-text font-mono">{{ number_format(($card->photo?->file_size ?? 0) / 1024, 1) }} KB</span>
                        </div>
                    </div>
                </div>

                <!-- Frame Switcher Box -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <div>
                        <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">Switch Display Frame</span>
                        <h3 class="text-sm font-semibold text-memodex-text mt-0.5">Change Outer Decorative Frame</h3>
                        <p class="text-[11px] text-memodex-muted">Re-renders the outer archival frame instantly without modifying the original card file.</p>
                    </div>

                    <form method="POST" action="{{ route('cards.switch-frame', $card) }}" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto p-1 bg-memodex-bg rounded-lg border border-memodex-border">
                            @foreach ($frames as $fr)
                                <label class="flex items-center gap-2.5 p-2.5 rounded-lg border cursor-pointer transition-all {{ $card->frame_id == $fr->id ? 'border-memodex-primary bg-memodex-soft/80 ring-1 ring-memodex-primary' : 'border-memodex-border bg-memodex-panel hover:border-memodex-border-hover' }}">
                                    <input type="radio" name="frame_id" value="{{ $fr->id }}" {{ $card->frame_id == $fr->id ? 'checked' : '' }} class="text-memodex-primary focus:ring-memodex-primary">
                                    <div class="w-3.5 h-3.5 rounded-full border flex-shrink-0" style="background-color: {{ $fr->config['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fr->config['accent_color'] ?? '#83B9F2' }};"></div>
                                    <div class="overflow-hidden">
                                        <p class="text-xs font-medium text-memodex-text truncate">{{ $fr->name }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full py-2 px-4 rounded-lg bg-memodex-raised hover:bg-memodex-soft border border-memodex-border text-xs font-semibold text-memodex-text transition-colors">
                            Apply &amp; Re-Render Frame
                        </button>
                    </form>
                </div>

                <!-- Binder / Album Membership Box -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <h3 class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">Binder Memberships</h3>

                    @if ($card->albums->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach ($card->albums as $album)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs bg-memodex-raised border border-memodex-border text-memodex-text">
                                    <a href="{{ route('albums.show', $album) }}" class="hover:text-memodex-primary mr-2">{{ $album->name }}</a>
                                    <form method="POST" action="{{ route('albums.cards.remove', [$album, $card]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-memodex-subtle hover:text-memodex-danger text-sm font-bold leading-none" title="Remove from binder">&times;</button>
                                    </form>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-memodex-muted">This card is not yet in any binder.</p>
                    @endif

                    <!-- Add to Binder Form -->
                    @if ($userAlbums->count() > 0)
                    <form method="POST" action="" class="flex items-center gap-2 pt-2 border-t border-memodex-border/60" id="addAlbumForm">
                        @csrf
                        <input type="hidden" name="card_id" value="{{ $card->id }}">
                        <select id="albumSelect" class="flex-1 bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs text-memodex-text focus:border-memodex-primary">
                            <option value="">Select a binder to add...</option>
                            @foreach ($userAlbums as $alb)
                                @if (!$card->albums->contains($alb->id))
                                    <option value="{{ route('albums.cards.add', $alb) }}">{{ $alb->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="button" onclick="submitAddToAlbum()" class="px-3 py-1.5 bg-memodex-raised hover:bg-memodex-soft border border-memodex-border text-xs rounded text-memodex-text font-medium">
                            Add to Binder
                        </button>
                    </form>
                    <script>
                        function submitAddToAlbum() {
                            const sel = document.getElementById('albumSelect');
                            if (sel.value) {
                                const form = document.getElementById('addAlbumForm');
                                form.action = sel.value;
                                form.submit();
                            }
                        }
                    </script>
                    @endif
                </div>

                <!-- Management Actions -->
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('cards.edit', $card) }}" class="inline-flex items-center px-4 py-2 rounded-md text-xs font-medium border border-memodex-border bg-memodex-raised hover:bg-memodex-soft text-memodex-text transition-colors">
                        Edit Label
                    </a>

                    <form method="POST" action="{{ route('cards.destroy', $card) }}" onsubmit="return confirm('Are you sure you want to permanently delete card {{ $card->card_number }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 text-xs text-memodex-danger hover:underline">
                            Delete Card
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
