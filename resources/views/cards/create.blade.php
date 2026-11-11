<x-app-layout>
    <x-slot name="title">Archive Card — MEMODEX</x-slot>

    <div x-data="{
        mode: '{{ $mode ?? 'automatic' }}',
        selectedFrameId: '{{ $frames->firstWhere('slug', 'classic')?->id ?? ($frames->first()?->id ?? 1) }}',
        selectedFrameSlug: '{{ $frames->firstWhere('slug', 'classic')?->slug ?? ($frames->first()?->slug ?? 'classic') }}',
        selectedFrameName: '{{ $frames->firstWhere('slug', 'classic')?->name ?? 'Classic Frame' }}',
        selectedFrameColor: '{{ $frames->firstWhere('slug', 'classic')?->config['border_primary'] ?? '#3D4F66' }}',
        selectedFrameAccent: '{{ $frames->firstWhere('slug', 'classic')?->config['accent_color'] ?? '#83B9F2' }}',
        selectedPhotoId: '{{ $selectedPhoto ? $selectedPhoto->id : ($photos->first() ? $photos->first()->id : '') }}',
        photoThumbUrl: '{{ $selectedPhoto ? route('media.photo.thumbnail', $selectedPhoto) : ($photos->first() ? route('media.photo.thumbnail', $photos->first()) : '') }}',
        name: '{{ $selectedPhoto ? pathinfo($selectedPhoto->original_name, PATHINFO_FILENAME) : '' }}',
        albumId: '',
        filesCount: 0,
        updateFrame(id, slug, name, primary, accent) {
            this.selectedFrameId = id;
            this.selectedFrameSlug = slug;
            this.selectedFrameName = name;
            this.selectedFrameColor = primary;
            this.selectedFrameAccent = accent;
        },
        selectPhoto(id, url, originalName) {
            this.selectedPhotoId = id;
            this.photoThumbUrl = url;
            if (!this.name) {
                this.name = originalName.replace(/\.[^/.]+$/, '');
            }
        },
        handleBatchFiles(e) {
            this.filesCount = e.target.files.length;
        }
    }" class="space-y-6">

        <!-- Top Header & Mode Toggle -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">Collection Archiver</span>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Add Pokémon Cards</h1>
                <p class="text-xs text-memodex-muted mt-0.5">
                    Original Pokémon card artwork and text remain 100% unaltered. Choose an archival outer frame to complete your display.
                </p>
            </div>

            <!-- Mode Selector Switch -->
            <div class="flex items-center gap-1 p-1 bg-memodex-panel border border-memodex-border rounded-lg self-start sm:self-auto text-xs font-medium">
                <button type="button" @click="mode = 'automatic'" :class="mode === 'automatic' ? 'bg-memodex-soft text-memodex-primary font-semibold shadow-sm' : 'text-memodex-muted hover:text-memodex-text'" class="px-3.5 py-1.5 rounded transition-all">
                    Automatic Mode (Batch)
                </button>
                <button type="button" @click="mode = 'manual'" :class="mode === 'manual' ? 'bg-memodex-soft text-memodex-primary font-semibold shadow-sm' : 'text-memodex-muted hover:text-memodex-text'" class="px-3.5 py-1.5 rounded transition-all">
                    Manual Mode (Single)
                </button>
            </div>
        </div>

        <!-- Authenticity Notice Banner -->
        <div class="p-3.5 rounded-lg border border-memodex-primary/30 bg-memodex-soft/60 flex items-start gap-3">
            <svg class="w-5 h-5 text-memodex-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <div class="text-xs text-memodex-text space-y-0.5">
                <p class="font-semibold text-memodex-primary">100% Authentic Card Integrity Guarantee</p>
                <p class="text-memodex-muted leading-relaxed">
                    MEMODEX preserves your uploaded Pokémon cards exactly as they are. No cropping, no AI manipulation, and no alteration of artwork or game text. The system only adds an outer decorative display frame.
                </p>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODE 1: AUTOMATIC MODE (Batch Upload)     -->
        <!-- ========================================== -->
        <div x-show="mode === 'automatic'" x-transition class="space-y-6">
            <form method="POST" action="{{ route('cards.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="frame_id" :value="selectedFrameId">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <!-- Dropzone -->
                    <div class="lg:col-span-7 space-y-4">
                        <div class="p-6 rounded-xl border-2 border-dashed border-memodex-border hover:border-memodex-primary/60 bg-memodex-panel transition-colors text-center relative group">
                            <input type="file" name="photos[]" multiple accept="image/png,image/jpeg,image/webp" @change="handleBatchFiles($event)" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <div class="space-y-3 pointer-events-none">
                                <div class="w-12 h-12 mx-auto rounded-full bg-memodex-raised border border-memodex-border flex items-center justify-center text-memodex-primary group-hover:scale-105 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-memodex-text">Drag &amp; drop Pokémon card images here</h3>
                                    <p class="text-xs text-memodex-muted mt-1">Upload one or multiple card photos (PNG, JPEG, WebP up to 15MB each)</p>
                                </div>
                                <div class="pt-2 inline-flex items-center gap-2 px-4 py-2 rounded bg-memodex-soft text-memodex-primary text-xs font-medium">
                                    <span x-show="filesCount === 0">Browse files from computer</span>
                                    <span x-show="filesCount > 0" x-text="filesCount + ' card image(s) selected'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Optional Binder Selector -->
                        @if ($albums->count() > 0)
                        <div class="p-4 rounded-xl border border-memodex-border bg-memodex-panel flex items-center justify-between">
                            <div>
                                <label for="album_id_auto" class="block text-xs font-semibold text-memodex-text">Archive directly into Binder (Optional)</label>
                                <p class="text-[11px] text-memodex-muted">Organize this batch into a specific collection binder.</p>
                            </div>
                            <select id="album_id_auto" name="album_id" class="bg-memodex-bg border border-memodex-border rounded-lg px-3 py-1.5 text-xs text-memodex-text focus:border-memodex-primary">
                                <option value="">No Binder (Pokédex only)</option>
                                @foreach ($albums as $alb)
                                    <option value="{{ $alb->id }}">{{ $alb->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <!-- Frame Selection for Batch -->
                    <div class="lg:col-span-5 p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                        <div>
                            <span class="text-[11px] font-mono uppercase text-memodex-subtle tracking-wider">Outer Display Frame</span>
                            <h3 class="text-sm font-semibold text-memodex-text mt-0.5">Select Batch Outer Frame</h3>
                            <p class="text-[11px] text-memodex-muted mt-0.5">The selected frame will be applied to all cards in this batch. You can switch frames individually later.</p>
                        </div>

                        <div class="space-y-2">
                            @foreach ($frames as $fr)
                                <button type="button"
                                        @click="updateFrame('{{ $fr->id }}', '{{ $fr->slug }}', '{{ $fr->name }}', '{{ $fr->config['border_primary'] ?? '#3D4F66' }}', '{{ $fr->config['accent_color'] ?? '#83B9F2' }}')"
                                        :class="selectedFrameId == '{{ $fr->id }}' ? 'border-memodex-primary bg-memodex-soft/80 ring-1 ring-memodex-primary' : 'border-memodex-border bg-memodex-bg hover:border-memodex-border-hover'"
                                        class="w-full text-left p-3 rounded-lg border transition-all flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full border shadow-sm" style="background-color: {{ $fr->config['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fr->config['accent_color'] ?? '#83B9F2' }};"></div>
                                        <div>
                                            <p class="text-xs font-semibold text-memodex-text">{{ $fr->name }}</p>
                                            <p class="text-[10px] text-memodex-muted">{{ $fr->description }}</p>
                                        </div>
                                    </div>
                                    <span x-show="selectedFrameId == '{{ $fr->id }}'" class="text-memodex-primary font-bold text-xs">&#10003;</span>
                                </button>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full mt-4 py-2.5 px-4 rounded-lg bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg font-semibold text-xs tracking-wide transition-colors shadow">
                            Archive All to Pokédex &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- MODE 2: MANUAL MODE (Single Card Studio)  -->
        <!-- ========================================== -->
        <div x-show="mode === 'manual'" x-transition class="space-y-6">
            <form method="POST" action="{{ route('cards.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                @csrf
                <input type="hidden" name="frame_id" :value="selectedFrameId">
                <input type="hidden" name="photo_id" :value="selectedPhotoId">

                <!-- Left Column: Live Frame & Card Preview (63:88 aspect ratio) -->
                <div class="lg:col-span-5 flex flex-col items-center lg:sticky lg:top-24">
                    <div class="w-full max-w-sm">
                        <div class="text-[11px] font-mono text-memodex-subtle uppercase mb-2 flex items-center justify-between">
                            <span>Interactive Outer Frame Preview</span>
                            <span class="text-memodex-primary" x-text="selectedFrameName"></span>
                        </div>

                        <!-- Outer Frame Container -->
                        <div class="w-full aspect-card rounded-xl border-2 overflow-hidden relative shadow-2xl flex flex-col justify-between p-3 select-none transition-all duration-200"
                             :style="`border-color: ${selectedFrameColor}; background-color: #0F1622;`">

                            <!-- Subtle Outer Matting Bevel -->
                            <div class="w-full h-full rounded border flex flex-col justify-between p-2 relative"
                                 :style="`border-color: ${selectedFrameAccent};`">

                                <!-- Pokémon Card Artwork Window (100% Unaltered Aspect Ratio) -->
                                <div class="w-full flex-1 rounded bg-[#0A0E14] overflow-hidden flex items-center justify-center relative border border-memodex-border">
                                    <template x-if="photoThumbUrl">
                                        <img :src="photoThumbUrl" alt="Pokémon Card" class="max-w-full max-h-full object-contain">
                                    </template>
                                    <template x-if="!photoThumbUrl">
                                        <div class="text-center p-6 space-y-2">
                                            <svg class="w-8 h-8 mx-auto text-memodex-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <p class="text-xs text-memodex-muted font-mono">Select or upload a Pokémon card</p>
                                        </div>
                                    </template>
                                </div>

                                <!-- Collector Bottom Plaque (On Outer Frame) -->
                                <div class="mt-2 px-2 py-1.5 rounded bg-[#0A0F16] border border-memodex-border flex items-center justify-between text-[9px] font-mono">
                                    <span class="text-memodex-subtle">MEMODEX ARCHIVE</span>
                                    <span class="font-bold" :style="`color: ${selectedFrameAccent};`" x-text="selectedFrameName.toUpperCase()"></span>
                                    <span class="text-memodex-text">MDX-NEXT</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-[10px] text-center text-memodex-subtle mt-2 font-mono">
                            Archival composite output: 744 &times; 1039 px PNG
                        </p>
                    </div>
                </div>

                <!-- Right Column: Card & Frame Controls -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Step 1: Select or Upload Source Card -->
                    <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">
                                1. Source Pokémon Card Image
                            </span>
                            <span class="text-[11px] text-memodex-muted">Unmodified Original</span>
                        </div>

                        <!-- Direct File Upload Input -->
                        <div class="p-4 rounded-lg border border-dashed border-memodex-border bg-memodex-bg flex items-center gap-4">
                            <input type="file" name="photo_file" accept="image/png,image/jpeg,image/webp"
                                   @change="if ($event.target.files[0]) { photoThumbUrl = URL.createObjectURL($event.target.files[0]); if (!name) name = $event.target.files[0].name.replace(/\.[^/.]+$/, ''); }"
                                   class="text-xs text-memodex-muted file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-memodex-soft file:text-memodex-primary hover:file:bg-memodex-raised cursor-pointer">
                        </div>

                        @if ($photos->count() > 0)
                        <div>
                            <span class="text-[11px] text-memodex-muted block mb-2">Or pick from your previously uploaded photos:</span>
                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 max-h-36 overflow-y-auto p-1 bg-memodex-bg rounded-lg border border-memodex-border">
                                @foreach ($photos as $p)
                                    <button type="button"
                                            @click="selectPhoto('{{ $p->id }}', '{{ route('media.photo.thumbnail', $p) }}', '{{ addslashes($p->original_name) }}')"
                                            :class="selectedPhotoId == '{{ $p->id }}' ? 'ring-2 ring-memodex-primary border-transparent' : 'opacity-70 hover:opacity-100'"
                                            class="aspect-square rounded border border-memodex-border overflow-hidden relative transition-all">
                                        <img src="{{ route('media.photo.thumbnail', $p) }}" alt="{{ $p->original_name }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Step 2: Choose Frame -->
                    <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                        <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">
                            2. Choose Outer Decorative Frame
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($frames as $fr)
                                <button type="button"
                                        @click="updateFrame('{{ $fr->id }}', '{{ $fr->slug }}', '{{ $fr->name }}', '{{ $fr->config['border_primary'] ?? '#3D4F66' }}', '{{ $fr->config['accent_color'] ?? '#83B9F2' }}')"
                                        :class="selectedFrameId == '{{ $fr->id }}' ? 'border-memodex-primary bg-memodex-soft/80 ring-1 ring-memodex-primary' : 'border-memodex-border bg-memodex-bg hover:border-memodex-border-hover'"
                                        class="text-left p-3 rounded-lg border transition-all flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border shadow-sm flex-shrink-0" style="background-color: {{ $fr->config['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fr->config['accent_color'] ?? '#83B9F2' }};"></div>
                                    <div class="overflow-hidden">
                                        <p class="text-xs font-semibold text-memodex-text truncate">{{ $fr->name }}</p>
                                        <p class="text-[10px] text-memodex-muted truncate">{{ $fr->description }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 3: Optional Collection Label & Album -->
                    <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                        <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">
                            3. Catalog Label &amp; Binder (Optional)
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name_manual" class="block text-xs text-memodex-muted mb-1 font-medium">Card Label / Name</label>
                                <input type="text" id="name_manual" name="name" x-model="name" placeholder="e.g. Charizard Holo 1st Edition" maxlength="80"
                                       class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                                <span class="text-[10px] text-memodex-subtle mt-1 block">Catalog label for indexing; does not alter card image.</span>
                            </div>

                            @if ($albums->count() > 0)
                            <div>
                                <label for="album_id_manual" class="block text-xs text-memodex-muted mb-1 font-medium">Add to Binder</label>
                                <select id="album_id_manual" name="album_id" x-model="albumId"
                                        class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                                    <option value="">None (Pokédex only)</option>
                                    @foreach ($albums as $alb)
                                        <option value="{{ $alb->id }}">{{ $alb->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('pokedex.index') }}" class="px-4 py-2 text-xs text-memodex-muted hover:text-memodex-text">
                            Cancel
                        </a>

                        <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-md text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors shadow">
                            Save &amp; Frame Card &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
