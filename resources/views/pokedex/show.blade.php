<x-app-layout>
    <x-slot name="title">{{ $card->display_name }} ({{ $card->card_number }}) — MEMODEX</x-slot>

    @php
        $currentFrame = $card->frame;
        $currentCfg = $currentFrame?->config ?? [];
    @endphp

    <div x-data="{
        viewTab: 'framed',
        savedFrameId: '{{ $card->frame_id }}',
        selectedFrameId: '{{ $card->frame_id }}',
        selectedFrameName: '{{ addslashes($currentFrame?->name ?? 'Classic Frame') }}',
        selectedOuterBg: '{{ $currentCfg['outer_bg'] ?? '#0F1622' }}',
        selectedPrimary: '{{ $currentCfg['border_primary'] ?? '#3D4F66' }}',
        selectedInner: '{{ $currentCfg['border_inner'] ?? '#202E40' }}',
        selectedAccent: '{{ $currentCfg['accent_color'] ?? '#69AFFF' }}',
        selectedLabel: '{{ addslashes($currentCfg['label'] ?? 'CLASSIC ARCHIVE') }}',
        selectedAlbumUrl: '',
        showDeleteModal: false,
        selectFrame(id, name, outerBg, primary, inner, accent, label) {
            this.selectedFrameId = String(id);
            this.selectedFrameName = name;
            this.selectedOuterBg = outerBg;
            this.selectedPrimary = primary;
            this.selectedInner = inner;
            this.selectedAccent = accent;
            this.selectedLabel = label;
            this.viewTab = 'framed';
        }
    }" class="space-y-8">

        <!-- Breadcrumb Row -->
        <div class="flex items-center justify-between gap-4 pb-4 border-b border-[#C8D2DF]/[0.08]">
            <a href="{{ route('pokedex.index') }}" class="inline-flex items-center gap-2 text-sm text-[#9DAABD] hover:text-[#F3F5F8] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Pokédex</span>
            </a>
            <span class="font-mono text-xs text-[#9DAABD] tabular-nums px-2.5 py-1 rounded-md bg-[#17243A] border border-[#C8D2DF]/10">
                {{ $card->card_number }}
            </span>
        </div>

        <!-- Primary Split Showcase -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            <!-- Left Column: Unobstructed Large Card Pedestal -->
            <div class="lg:col-span-6 flex flex-col items-center lg:sticky lg:top-8 space-y-5">
                <!-- View Mode Toggle -->
                <div class="inline-flex items-center p-1 bg-[#17243A] border border-[#C8D2DF]/10 rounded-xl text-xs font-medium">
                    <button type="button"
                            @click="viewTab = 'framed'"
                            :class="viewTab === 'framed' ? 'bg-[#263449] text-[#F3F5F8] shadow-sm' : 'text-[#9DAABD] hover:text-[#F3F5F8]'"
                            class="px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <span>Framed Mount</span>
                        <span x-show="selectedFrameId !== savedFrameId" x-cloak class="w-1.5 h-1.5 rounded-full bg-[#69AFFF]" title="Unsaved preview"></span>
                    </button>
                    <button type="button"
                            @click="viewTab = 'original'"
                            :class="viewTab === 'original' ? 'bg-[#263449] text-[#F3F5F8] shadow-sm' : 'text-[#9DAABD] hover:text-[#F3F5F8]'"
                            class="px-4 py-2 rounded-lg transition-colors">
                        Original Unaltered
                    </button>
                </div>

                <!-- 63:88 Museum Pedestal -->
                <div class="w-full max-w-[390px] aspect-card card-stage rounded-2xl border border-[#C8D2DF]/15 shadow-2xl overflow-hidden flex items-center justify-center p-3 sm:p-4">
                    <!-- Saved Framed PNG (shown when current frame matches saved frame) -->
                    <div x-show="viewTab === 'framed' && selectedFrameId === savedFrameId" class="w-full h-full flex items-center justify-center">
                        <img src="{{ route('media.card.framed', $card) }}?v={{ $card->updated_at->timestamp }}"
                             alt="{{ $card->display_name }} (Framed)"
                             class="w-full h-full object-contain select-none">
                    </div>

                    <!-- Live Frame Comparison Preview (when user previews a different frame before saving) -->
                    <div x-show="viewTab === 'framed' && selectedFrameId !== savedFrameId" x-cloak
                         class="w-full h-full rounded-xl border-2 p-2.5 flex flex-col justify-between transition-all duration-200"
                         :style="`background-color: ${selectedOuterBg}; border-color: ${selectedPrimary};`">
                        <div class="w-full h-full rounded-lg border p-2 flex flex-col justify-between"
                             :style="`border-color: ${selectedInner};`">
                            <div class="flex-1 rounded bg-[#0B111C] overflow-hidden flex items-center justify-center border"
                                 :style="`border-color: ${selectedAccent}44;`">
                                <img src="{{ route('media.card.original', $card) }}"
                                     alt="{{ $card->display_name }} Preview"
                                     class="w-full h-full object-contain select-none">
                            </div>
                            <div class="mt-2 px-2.5 py-1.5 rounded bg-[#0A0F16] border flex items-center justify-between text-[9px] font-mono"
                                 :style="`border-color: ${selectedInner};`">
                                <span class="text-[#6D7A8D]">MEMODEX</span>
                                <span class="font-semibold uppercase truncate mx-2" :style="`color: ${selectedAccent};`" x-text="selectedLabel"></span>
                                <span class="text-[#F3F5F8]">{{ $card->card_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 100% Untouched Original Artwork -->
                    <div x-show="viewTab === 'original'" x-cloak class="w-full h-full flex items-center justify-center">
                        <img src="{{ route('media.card.original', $card) }}"
                             alt="{{ $card->display_name }} (Original)"
                             class="w-full h-full object-contain select-none">
                    </div>
                </div>

                <!-- Live preview hint -->
                <p x-show="selectedFrameId !== savedFrameId && viewTab === 'framed'" x-cloak class="text-xs text-[#69AFFF]">
                    Previewing <span class="font-semibold" x-text="selectedFrameName"></span> — click "Apply Outer Frame" to render &amp; save.
                </p>

                <!-- Export / Download Controls -->
                <div class="w-full max-w-[390px] grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('media.card.download.framed', $card) }}" class="memodex-btn-primary text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        <span>Download Framed</span>
                    </a>
                    <a href="{{ route('media.card.download.original', $card) }}" class="memodex-btn-secondary text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        <span>Download Original</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Editorial Metadata & Actions -->
            <div class="lg:col-span-6 space-y-8">
                <!-- Title & Primary Actions -->
                <section class="pb-6 border-b border-[#C8D2DF]/[0.08] space-y-4">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center gap-2 text-xs text-[#9DAABD]">
                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $currentCfg['accent_color'] ?? '#69AFFF' }};"></span>
                                <span>{{ $currentFrame?->name ?? 'Classic Frame' }}</span>
                                <span class="text-[#6D7A8D]">&middot;</span>
                                <span class="font-mono tabular-nums text-[#6D7A8D]">{{ $card->card_number }}</span>
                            </div>
                            <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8] break-words">
                                {{ $card->display_name }}
                            </h1>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form method="POST" action="{{ route('favorites.toggle', $card) }}">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium border transition-colors {{ $card->is_favorite ? 'bg-[#D4B36A]/15 border-[#D4B36A]/45 text-[#D4B36A]' : 'bg-[#17243A] border-[#2D3C53] text-[#9DAABD] hover:text-[#F3F5F8]' }}">
                                    <svg class="w-4 h-4 {{ $card->is_favorite ? 'fill-current' : '' }}" fill="{{ $card->is_favorite ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                    <span>{{ $card->is_favorite ? 'Favorited' : 'Favorite' }}</span>
                                </button>
                            </form>

                            <a href="{{ route('cards.edit', $card) }}" class="memodex-btn-secondary text-xs px-3.5 py-2">
                                Edit Label
                            </a>
                        </div>
                    </div>

                    <p class="text-xs text-[#9DAABD] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#5CC99B] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        <span>Original artwork, text, HP, and card design are preserved 100% intact.</span>
                    </p>
                </section>

                <!-- Frame Selection Section -->
                <section class="pb-6 border-b border-[#C8D2DF]/[0.08] space-y-4">
                    <div>
                        <h2 class="font-display text-base font-semibold text-[#F3F5F8]">Outer Display Frame</h2>
                        <p class="text-xs text-[#9DAABD] mt-0.5">Select a frame to preview live on the left, then apply to re-render the framed PNG.</p>
                    </div>

                    @if ($frames->isEmpty())
                        <p class="text-sm text-[#9DAABD] py-4 px-4 rounded-xl bg-[#17243A] border border-[#C8D2DF]/10">
                            No active outer frames are currently available in the database.
                        </p>
                    @else
                        <form method="POST" action="{{ route('cards.switch-frame', $card) }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                @foreach ($frames as $fr)
                                    @php $fc = $fr->config ?? []; @endphp
                                    <label
                                        @click="selectFrame('{{ $fr->id }}', '{{ addslashes($fr->name) }}', '{{ $fc['outer_bg'] ?? '#0F1622' }}', '{{ $fc['border_primary'] ?? '#3D4F66' }}', '{{ $fc['border_inner'] ?? '#202E40' }}', '{{ $fc['accent_color'] ?? '#69AFFF' }}', '{{ addslashes($fc['label'] ?? $fr->name) }}')"
                                        :class="selectedFrameId === '{{ $fr->id }}' ? 'bg-[#263449] border-[#69AFFF] ring-1 ring-[#69AFFF]/40' : 'bg-[#17243A]/70 border-[#C8D2DF]/10 hover:border-[#C8D2DF]/30'"
                                        class="flex items-center justify-between gap-3 p-3 rounded-xl border cursor-pointer transition-all">
                                        <input type="radio" name="frame_id" value="{{ $fr->id }}" :checked="selectedFrameId === '{{ $fr->id }}'" class="sr-only">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <!-- Mini 63:88 Frame Silhouette Swatch (replaces double radio circles) -->
                                            <div class="w-7 h-9 rounded border-2 flex-shrink-0 flex items-center justify-center p-1"
                                                 style="background-color: {{ $fc['outer_bg'] ?? '#0F1622' }}; border-color: {{ $fc['border_primary'] ?? '#3D4F66' }};">
                                                <div class="w-full h-full rounded-[2px] border" style="border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></div>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-[#F3F5F8] truncate">{{ $fr->name }}</p>
                                                <p class="text-[11px] text-[#6D7A8D] truncate">{{ $fc['label'] ?? $fr->slug }}</p>
                                            </div>
                                        </div>
                                        <svg x-show="selectedFrameId === '{{ $fr->id }}'" x-cloak class="w-4 h-4 text-[#69AFFF] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="memodex-btn-secondary text-xs px-5 py-2.5"
                                        :class="selectedFrameId !== savedFrameId ? '!bg-[#69AFFF] !text-[#101827] !border-[#69AFFF] font-semibold' : ''">
                                    Apply Outer Frame
                                </button>
                            </div>
                        </form>
                    @endif
                </section>

                <!-- Binder / Album Assignments -->
                <section class="pb-6 border-b border-[#C8D2DF]/[0.08] space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-base font-semibold text-[#F3F5F8]">Binders &amp; Albums</h2>
                        <a href="{{ route('albums.index') }}" class="text-xs text-[#69AFFF] hover:underline">Manage Albums</a>
                    </div>

                    @if ($card->albums->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach ($card->albums as $album)
                                <div class="inline-flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-lg bg-[#17243A] border border-[#C8D2DF]/15 text-xs text-[#F3F5F8]">
                                    <a href="{{ route('albums.show', $album) }}" class="hover:text-[#69AFFF] font-medium transition-colors">{{ $album->name }}</a>
                                    <form method="POST" action="{{ route('albums.cards.remove', [$album, $card]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-0.5 rounded text-[#6D7A8D] hover:text-[#EB6F75] transition-colors" title="Remove from binder">&times;</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-[#6D7A8D]">Not assigned to any binder yet.</p>
                    @endif

                    @php
                        $unassignedAlbums = $userAlbums->filter(fn($alb) => !$card->albums->contains($alb->id));
                    @endphp
                    @if ($unassignedAlbums->count() > 0)
                        <form method="POST" :action="selectedAlbumUrl" class="flex items-center gap-2.5"
                              @submit.prevent="if (selectedAlbumUrl) $el.submit()">
                            @csrf
                            <input type="hidden" name="card_id" value="{{ $card->id }}">
                            <select x-model="selectedAlbumUrl" class="memodex-select py-2 flex-1">
                                <option value="">Assign to another binder...</option>
                                @foreach ($unassignedAlbums as $alb)
                                    <option value="{{ route('albums.cards.add', $alb) }}">{{ $alb->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" :disabled="!selectedAlbumUrl" class="memodex-btn-secondary text-xs px-4 py-2 whitespace-nowrap">
                                Add
                            </button>
                        </form>
                    @endif
                </section>

                <!-- Archival File Metadata -->
                <section class="pb-6 border-b border-[#C8D2DF]/[0.08] space-y-4">
                    <h2 class="font-display text-base font-semibold text-[#F3F5F8]">Archival Specifications</h2>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-xs text-[#6D7A8D]">Original Filename</dt>
                            <dd class="font-mono text-xs text-[#F3F5F8] truncate mt-1" title="{{ $card->photo?->original_name }}">{{ $card->photo?->original_name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-[#6D7A8D]">Archived Date</dt>
                            <dd class="text-xs text-[#F3F5F8] mt-1">{{ $card->created_at->format('M d, Y · H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-[#6D7A8D]">Original Dimensions</dt>
                            <dd class="font-mono text-xs text-[#F3F5F8] tabular-nums mt-1">{{ $card->photo?->width ?? '—' }} &times; {{ $card->photo?->height ?? '—' }} px</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-[#6D7A8D]">Original File Size</dt>
                            <dd class="font-mono text-xs text-[#F3F5F8] tabular-nums mt-1">{{ number_format(($card->photo?->file_size ?? 0) / 1024, 1) }} KB</dd>
                        </div>
                    </dl>
                </section>

                <!-- Danger Action -->
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#6D7A8D]">Deleting removes this framed card entry; your original file stays in My Collection.</span>
                    <button type="button" @click="showDeleteModal = true" class="text-xs font-medium text-[#EB6F75] hover:underline whitespace-nowrap ml-4">
                        Delete Card
                    </button>
                </div>
            </div>
        </div>

        <!-- Styled Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-[#070B14]/80 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div class="relative z-10 max-w-md w-full bg-[#1D293B] border border-[#C8D2DF]/15 rounded-2xl p-6 space-y-4 shadow-2xl">
                <h3 class="font-display text-lg font-semibold text-[#F3F5F8]">Delete {{ $card->card_number }}?</h3>
                <p class="text-sm text-[#9DAABD] leading-relaxed">
                    Are you sure you want to permanently remove <strong class="text-[#F3F5F8]">{{ $card->display_name }}</strong> from your Pokédex?
                </p>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="showDeleteModal = false" class="memodex-btn-secondary text-xs">Cancel</button>
                    <form method="POST" action="{{ route('cards.destroy', $card) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 rounded-lg bg-[#EB6F75] hover:bg-[#dc5b62] text-[#101827] text-xs font-semibold transition-colors">
                            Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
