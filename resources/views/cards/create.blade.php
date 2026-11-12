<x-app-layout>
    <x-slot name="title">Upload &amp; Frame Cards — MEMODEX</x-slot>

    @php
        $defaultFrame = $frames->firstWhere('slug', 'classic') ?? $frames->first();
        $defaultCfg = $defaultFrame?->config ?? [];
    @endphp

    <div x-data="{
        mode: '{{ $mode ?? 'automatic' }}',
        selectedFrameId: '{{ $defaultFrame?->id ?? '' }}',
        selectedFrameSlug: '{{ $defaultFrame?->slug ?? 'classic' }}',
        selectedFrameName: '{{ addslashes($defaultFrame?->name ?? 'Classic Frame') }}',
        selectedOuterBg: '{{ $defaultCfg['outer_bg'] ?? '#0F1622' }}',
        selectedPrimary: '{{ $defaultCfg['border_primary'] ?? '#3D4F66' }}',
        selectedInner: '{{ $defaultCfg['border_inner'] ?? '#202E40' }}',
        selectedAccent: '{{ $defaultCfg['accent_color'] ?? '#69AFFF' }}',
        selectedLabel: '{{ addslashes($defaultCfg['label'] ?? 'CLASSIC ARCHIVE') }}',
        selectedPhotoId: '{{ $selectedPhoto ? $selectedPhoto->id : ($photos->first() ? $photos->first()->id : '') }}',
        photoThumbUrl: '{{ $selectedPhoto ? route('media.photo.thumbnail', $selectedPhoto) : ($photos->first() ? route('media.photo.thumbnail', $photos->first()) : '') }}',
        name: '{{ $selectedPhoto ? addslashes(pathinfo($selectedPhoto->original_name, PATHINFO_FILENAME)) : '' }}',
        albumId: '',

        // Batch upload state
        batchFiles: [],
        dragging: false,
        batchError: '',
        uploading: false,
        uploadProgress: 0,
        uploadSuccess: false,
        manualDragging: false,
        manualSubmitting: false,

        updateFrame(id, slug, name, outerBg, primary, inner, accent, label) {
            this.selectedFrameId = String(id);
            this.selectedFrameSlug = slug;
            this.selectedFrameName = name;
            this.selectedOuterBg = outerBg;
            this.selectedPrimary = primary;
            this.selectedInner = inner;
            this.selectedAccent = accent;
            this.selectedLabel = label;
        },
        selectPhoto(id, url, originalName) {
            this.selectedPhotoId = String(id);
            this.photoThumbUrl = url;
            if (!this.name) {
                this.name = originalName.replace(/\.[^/.]+$/, '');
            }
        },
        addBatchFiles(fileList) {
            this.batchError = '';
            const allowed = ['image/jpeg', 'image/png', 'image/webp'];
            const maxBytes = 15 * 1024 * 1024;
            for (const file of fileList) {
                if (!allowed.includes(file.type)) {
                    this.batchError = file.name + ' is not a supported format (JPEG, PNG, WebP only).';
                    continue;
                }
                if (file.size > maxBytes) {
                    this.batchError = file.name + ' exceeds the 15 MB per-file limit.';
                    continue;
                }
                this.batchFiles.push({
                    file,
                    name: file.name,
                    sizeLabel: (file.size / 1024).toFixed(0) + ' KB',
                    previewUrl: URL.createObjectURL(file)
                });
            }
        },
        removeBatchFile(idx) {
            const item = this.batchFiles[idx];
            if (item?.previewUrl) URL.revokeObjectURL(item.previewUrl);
            this.batchFiles.splice(idx, 1);
        },
        handleBatchDrop(e) {
            this.dragging = false;
            if (e.dataTransfer?.files?.length) {
                this.addBatchFiles(e.dataTransfer.files);
            }
        },
        handleManualFile(file) {
            if (!file) return;
            this.selectedPhotoId = '';
            this.photoThumbUrl = URL.createObjectURL(file);
            if (!this.name) {
                this.name = file.name.replace(/\.[^/.]+$/, '');
            }
        },
        submitBatchUpload() {
            if (this.batchFiles.length === 0 || this.uploading) return;
            this.batchError = '';
            this.uploading = true;
            this.uploadProgress = 0;

            const fd = new FormData();
            fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
            if (this.selectedFrameId) fd.append('frame_id', this.selectedFrameId);
            if (this.albumId) fd.append('album_id', this.albumId);
            this.batchFiles.forEach(item => fd.append('photos[]', item.file));

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('cards.store') }}', true);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = (evt) => {
                if (evt.lengthComputable) {
                    this.uploadProgress = Math.round((evt.loaded / evt.total) * 100);
                }
            };
            xhr.onload = () => {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (xhr.status >= 200 && xhr.status < 300 && res.success) {
                        this.uploadSuccess = true;
                        this.uploadProgress = 100;
                        window.location.href = res.redirect || '{{ route('pokedex.index') }}';
                        return;
                    }
                    this.batchError = res.error || res.message || 'Upload failed. Please verify your files.';
                } catch {
                    this.batchError = 'Unexpected server response (' + xhr.status + ').';
                }
                this.uploading = false;
            };
            xhr.onerror = () => {
                this.batchError = 'Network error while uploading cards.';
                this.uploading = false;
            };
            xhr.send(fd);
        }
    }" class="space-y-8">

        <!-- Page Header & Mode Switcher -->
        <section class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-5 border-b border-[#D4DCF0]/[0.08]">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F6F7FB]">Upload &amp; Frame Cards</h1>
                <p class="text-sm text-[#BCC6E0] mt-1">
                    Original card artwork, text, and proportions are never cropped or modified. Only an outer display frame is added.
                </p>
            </div>

            <div class="inline-flex items-center p-1 bg-[#26355D] border border-[#D4DCF0]/10 rounded-xl text-xs font-medium self-start md:self-auto">
                <button type="button"
                        @click="mode = 'automatic'"
                        :class="mode === 'automatic' ? 'bg-[#304273] text-[#F6F7FB] shadow-sm' : 'text-[#BCC6E0] hover:text-[#F6F7FB]'"
                        class="px-4 py-2 rounded-lg transition-colors">
                    Batch Upload
                </button>
                <button type="button"
                        @click="mode = 'manual'"
                        :class="mode === 'manual' ? 'bg-[#304273] text-[#F6F7FB] shadow-sm' : 'text-[#BCC6E0] hover:text-[#F6F7FB]'"
                        class="px-4 py-2 rounded-lg transition-colors">
                    Single Card Studio
                </button>
            </div>
        </section>

        @if ($frames->isEmpty())
            <div class="p-4 rounded-xl bg-[#E5B567]/10 border border-[#E5B567]/30 text-sm text-[#E5B567]">
                No outer display frames are currently seeded in the database. Please add or seed at least one frame in <code class="font-mono">card_frames</code> before saving cards.
            </div>
        @endif

        <!-- ============================================== -->
        <!-- MODE 1: BATCH UPLOAD (AUTOMATIC MODE)         -->
        <!-- ============================================== -->
        <section x-show="mode === 'automatic'" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Dropzone + Live Thumbnail Queue -->
            <div class="lg:col-span-7 space-y-5">
                <div @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="handleBatchDrop($event)"
                     :class="dragging ? 'border-[#AF47D2] bg-[#223056]' : 'border-[#3B4E82] bg-[#26355D]/60 hover:border-[#5268A6]'"
                     class="relative border-2 border-dashed rounded-2xl p-8 sm:p-10 text-center transition-colors">
                    <input type="file"
                           multiple
                           accept="image/png,image/jpeg,image/webp"
                           @change="addBatchFiles($event.target.files); $event.target.value = ''"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <div class="space-y-3 pointer-events-none">
                        <div class="w-12 h-12 mx-auto rounded-xl bg-[#161F3B] border border-[#D4DCF0]/15 flex items-center justify-center text-[#FFA22B]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        </div>
                        <div>
                            <p class="font-display text-base font-semibold text-[#F6F7FB]">Drop Pokémon card images here</p>
                            <p class="text-sm text-[#BCC6E0] mt-1">Or click to select multiple PNG, JPEG, or WebP files (up to 15 MB each)</p>
                        </div>
                    </div>
                </div>

                <!-- Error Feedback -->
                <div x-show="batchError" x-cloak class="p-3.5 rounded-xl bg-[#EB6F75]/10 border border-[#EB6F75]/35 text-sm text-[#EB6F75]" x-text="batchError"></div>

                <!-- Upload Progress Feedback -->
                <div x-show="uploading" x-cloak class="p-4 rounded-xl bg-[#26355D] border border-[#D4DCF0]/15 space-y-2.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-medium text-[#F6F7FB]" x-text="uploadSuccess ? 'Archived! Redirecting to Pokédex...' : 'Uploading & rendering outer frames...'"></span>
                        <span class="font-mono text-[#FFA22B] tabular-nums" x-text="uploadProgress + '%'"></span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-[#161F3B] overflow-hidden">
                        <div class="h-full bg-[#FF8F00] transition-all duration-150" :style="`width: ${uploadProgress}%`"></div>
                    </div>
                </div>

                <!-- Queued Files 63:88 Thumbnail Preview Strip -->
                <div x-show="batchFiles.length > 0" x-cloak class="space-y-3">
                    <div class="flex items-center justify-between text-xs text-[#BCC6E0]">
                        <span class="font-medium" x-text="batchFiles.length + ' card image(s) queued'"></span>
                        <button type="button" @click="batchFiles = []" class="text-[#EB6F75] hover:underline">Clear queue</button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                        <template x-for="(item, index) in batchFiles" :key="index">
                            <div class="relative bg-[#26355D] border border-[#D4DCF0]/10 rounded-xl overflow-hidden flex flex-col">
                                <div class="aspect-card card-stage p-1.5 flex items-center justify-center relative">
                                    <img :src="item.previewUrl" :alt="item.name" class="w-full h-full object-contain">
                                    <button type="button"
                                            @click="removeBatchFile(index)"
                                            class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-[#161F3B]/90 border border-[#D4DCF0]/20 text-[#BCC6E0] hover:text-[#EB6F75] flex items-center justify-center text-xs font-bold"
                                            title="Remove">
                                        &times;
                                    </button>
                                </div>
                                <div class="px-2.5 py-2 border-t border-[#D4DCF0]/[0.08]">
                                    <p class="text-xs font-medium text-[#F6F7FB] truncate" x-text="item.name"></p>
                                    <p class="text-[11px] font-mono text-[#8B99BD]" x-text="item.sizeLabel"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right: Outer Frame & Binder Assignment -->
            <div class="lg:col-span-5 bg-[#26355D] border border-[#D4DCF0]/10 rounded-2xl p-6 space-y-6">
                <div>
                    <h2 class="font-display text-base font-semibold text-[#F6F7FB]">Select Batch Outer Frame</h2>
                    <p class="text-xs text-[#BCC6E0] mt-1">Applied around every card in this batch without touching original artwork.</p>
                </div>

                <div class="space-y-2">
                    @foreach ($frames as $fr)
                        @php $fc = $fr->config ?? []; @endphp
                        <button type="button"
                                @click="updateFrame('{{ $fr->id }}', '{{ $fr->slug }}', '{{ addslashes($fr->name) }}', '{{ $fc['outer_bg'] ?? '#0F1622' }}', '{{ $fc['border_primary'] ?? '#3D4F66' }}', '{{ $fc['border_inner'] ?? '#202E40' }}', '{{ $fc['accent_color'] ?? '#69AFFF' }}', '{{ addslashes($fc['label'] ?? $fr->name) }}')"
                                :class="selectedFrameId === '{{ $fr->id }}' ? 'bg-[#304273] border-[#AF47D2] ring-1 ring-[#AF47D2]/40' : 'bg-[#161F3B]/70 border-[#D4DCF0]/10 hover:border-[#D4DCF0]/30'"
                                class="w-full text-left p-3 rounded-xl border transition-all flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-7 h-9 rounded border-2 flex-shrink-0 p-1 flex items-center justify-center"
                                     style="background-color: {{ $fc['outer_bg'] ?? '#0F1622' }}; border-color: {{ $fc['border_primary'] ?? '#3D4F66' }};">
                                    <div class="w-full h-full rounded-[2px] border" style="border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#F6F7FB] truncate">{{ $fr->name }}</p>
                                    <p class="text-xs text-[#8B99BD] truncate">{{ $fr->description }}</p>
                                </div>
                            </div>
                            <svg x-show="selectedFrameId === '{{ $fr->id }}'" x-cloak class="w-4 h-4 text-[#FFA22B] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </button>
                    @endforeach
                </div>

                @if ($albums->count() > 0)
                <div class="pt-4 border-t border-[#D4DCF0]/[0.08]">
                    <label for="album_id_auto" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Assign to Binder (Optional)</label>
                    <select id="album_id_auto" x-model="albumId" class="memodex-select py-2">
                        <option value="">No Binder (Pokédex only)</option>
                        @foreach ($albums as $alb)
                            <option value="{{ $alb->id }}">{{ $alb->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <button type="button"
                        @click="submitBatchUpload()"
                        :disabled="batchFiles.length === 0 || uploading || !selectedFrameId"
                        class="memodex-btn-primary w-full py-3">
                    <span x-show="!uploading" x-text="batchFiles.length > 0 ? 'Frame & Archive ' + batchFiles.length + ' Card(s)' : 'Select Card Images to Begin'"></span>
                    <span x-show="uploading" x-cloak>Processing Archive...</span>
                </button>
            </div>
        </section>

        <!-- ============================================== -->
        <!-- MODE 2: SINGLE CARD STUDIO (MANUAL MODE)       -->
        <!-- ============================================== -->
        <section x-show="mode === 'manual'" x-cloak>
            <form method="POST"
                  action="{{ route('cards.store') }}"
                  enctype="multipart/form-data"
                  @submit="manualSubmitting = true"
                  class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                @csrf
                <input type="hidden" name="frame_id" :value="selectedFrameId">
                <input type="hidden" name="photo_id" :value="selectedPhotoId">

                <!-- Left: Sticky Live Frame Comparison Stage (63:88) -->
                <div class="lg:col-span-5 flex flex-col items-center lg:sticky lg:top-8 space-y-3">
                    <div class="w-full max-w-[360px] flex items-center justify-between text-xs">
                        <span class="text-[#BCC6E0] font-medium">Live Outer Frame Preview</span>
                        <span class="font-medium" :style="`color: ${selectedAccent};`" x-text="selectedFrameName"></span>
                    </div>

                    <div class="w-full max-w-[360px] aspect-card rounded-2xl border-2 p-3 flex flex-col justify-between shadow-2xl transition-colors duration-200 select-none"
                         :style="`border-color: ${selectedPrimary}; background-color: ${selectedOuterBg};`">
                        <div class="w-full h-full rounded-xl border p-2.5 flex flex-col justify-between"
                             :style="`border-color: ${selectedInner};`">
                            <!-- Card Artwork Window (100% object-contain) -->
                            <div class="w-full flex-1 rounded-lg bg-[#11182E] overflow-hidden flex items-center justify-center border border-[#D4DCF0]/10">
                                <template x-if="photoThumbUrl">
                                    <img :src="photoThumbUrl" alt="Selected Pokémon Card" class="w-full h-full object-contain">
                                </template>
                                <template x-if="!photoThumbUrl">
                                    <div class="text-center p-6 space-y-2">
                                        <svg class="w-8 h-8 mx-auto text-[#8B99BD]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                                        <p class="text-xs text-[#BCC6E0]">Choose or drop a card image</p>
                                    </div>
                                </template>
                            </div>

                            <!-- Collector Bottom Plaque -->
                            <div class="mt-2.5 px-3 py-1.5 rounded bg-[#0A0F16] border flex items-center justify-between text-[9px] font-mono"
                                 :style="`border-color: ${selectedInner};`">
                                <span class="text-[#8B99BD]">MEMODEX</span>
                                <span class="font-semibold uppercase truncate mx-2" :style="`color: ${selectedAccent};`" x-text="selectedLabel"></span>
                                <span class="text-[#F6F7FB]">MDX-NEXT</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-[#8B99BD] font-mono">Export resolution: 744 &times; 1039 px PNG (63:88)</p>
                </div>

                <!-- Right: Studio Controls -->
                <div class="lg:col-span-7 space-y-7">
                    <!-- Step 1: Source Card -->
                    <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="font-display text-base font-semibold text-[#F6F7FB]">1. Source Card Image</h2>
                            <span class="text-xs text-[#5CC99B]">Preserved Unaltered</span>
                        </div>

                        <label @dragover.prevent="manualDragging = true"
                               @dragleave.prevent="manualDragging = false"
                               @drop.prevent="manualDragging = false; if ($event.dataTransfer.files[0]) { $refs.manualFileInput.files = $event.dataTransfer.files; handleManualFile($event.dataTransfer.files[0]); }"
                               :class="manualDragging ? 'border-[#AF47D2] bg-[#223056]' : 'border-[#3B4E82] bg-[#26355D]/60 hover:border-[#5268A6]'"
                               class="flex items-center justify-between gap-4 p-4 rounded-xl border border-dashed cursor-pointer transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-[#161F3B] border border-[#D4DCF0]/10 flex items-center justify-center text-[#FFA22B] flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#F6F7FB] truncate">Choose or drop a Pokémon card file</p>
                                    <p class="text-xs text-[#8B99BD]">PNG, JPEG, or WebP up to 15 MB</p>
                                </div>
                            </div>
                            <span class="memodex-btn-secondary text-xs px-3 py-1.5 flex-shrink-0">Browse</span>
                            <input x-ref="manualFileInput" type="file" name="photo_file" accept="image/png,image/jpeg,image/webp" class="sr-only"
                                   @change="handleManualFile($event.target.files[0])">
                        </label>

                        @if ($photos->count() > 0)
                        <div class="space-y-2">
                            <span class="text-xs text-[#BCC6E0] block">Or reuse from My Collection (Original Assets):</span>
                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2.5 max-h-48 overflow-y-auto p-2 bg-[#26355D]/50 rounded-xl border border-[#D4DCF0]/10">
                                @foreach ($photos as $p)
                                    <button type="button"
                                            @click="selectPhoto('{{ $p->id }}', '{{ route('media.photo.thumbnail', $p) }}', '{{ addslashes($p->original_name) }}')"
                                            :class="selectedPhotoId === '{{ $p->id }}' ? 'ring-2 ring-[#AF47D2] border-transparent opacity-100' : 'border-[#D4DCF0]/10 opacity-75 hover:opacity-100'"
                                            class="aspect-card card-stage rounded-lg border overflow-hidden p-1 transition-all">
                                        <img src="{{ route('media.photo.thumbnail', $p) }}" alt="{{ $p->original_name }}" class="w-full h-full object-contain">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </section>

                    <!-- Step 2: Frame Selection -->
                    <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-4">
                        <h2 class="font-display text-base font-semibold text-[#F6F7FB]">2. Choose Outer Display Frame</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach ($frames as $fr)
                                @php $fc = $fr->config ?? []; @endphp
                                <button type="button"
                                        @click="updateFrame('{{ $fr->id }}', '{{ $fr->slug }}', '{{ addslashes($fr->name) }}', '{{ $fc['outer_bg'] ?? '#0F1622' }}', '{{ $fc['border_primary'] ?? '#3D4F66' }}', '{{ $fc['border_inner'] ?? '#202E40' }}', '{{ $fc['accent_color'] ?? '#69AFFF' }}', '{{ addslashes($fc['label'] ?? $fr->name) }}')"
                                        :class="selectedFrameId === '{{ $fr->id }}' ? 'bg-[#304273] border-[#AF47D2] ring-1 ring-[#AF47D2]/40' : 'bg-[#26355D]/70 border-[#D4DCF0]/10 hover:border-[#D4DCF0]/30'"
                                        class="text-left p-3 rounded-xl border transition-all flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-7 h-9 rounded border-2 flex-shrink-0 p-1 flex items-center justify-center"
                                             style="background-color: {{ $fc['outer_bg'] ?? '#0F1622' }}; border-color: {{ $fc['border_primary'] ?? '#3D4F66' }};">
                                            <div class="w-full h-full rounded-[2px] border" style="border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-[#F6F7FB] truncate">{{ $fr->name }}</p>
                                            <p class="text-xs text-[#8B99BD] truncate">{{ $fc['label'] ?? $fr->slug }}</p>
                                        </div>
                                    </div>
                                    <svg x-show="selectedFrameId === '{{ $fr->id }}'" x-cloak class="w-4 h-4 text-[#FFA22B] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <!-- Step 3: Optional Metadata -->
                    <section class="space-y-4">
                        <h2 class="font-display text-base font-semibold text-[#F6F7FB]">3. Catalog Details (Optional)</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name_manual" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Card Name / Label</label>
                                <input type="text" id="name_manual" name="name" x-model="name" placeholder="e.g. Charizard Base Set" maxlength="80" class="memodex-input">
                                <span class="text-[11px] text-[#8B99BD] mt-1 block">Used only for indexing; never printed on card artwork.</span>
                            </div>

                            @if ($albums->count() > 0)
                            <div>
                                <label for="album_id_manual" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Add to Binder</label>
                                <select id="album_id_manual" name="album_id" x-model="albumId" class="memodex-select">
                                    <option value="">None (Pokédex only)</option>
                                    @foreach ($albums as $alb)
                                        <option value="{{ $alb->id }}">{{ $alb->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                    </section>

                    <!-- Submit Row -->
                    <div class="pt-4 border-t border-[#D4DCF0]/[0.08] flex items-center justify-between">
                        <a href="{{ route('pokedex.index') }}" class="memodex-btn-ghost">Cancel</a>
                        <button type="submit" :disabled="manualSubmitting || !selectedFrameId" class="memodex-btn-primary px-6 py-2.5">
                            <span x-show="!manualSubmitting">Save &amp; Frame Card</span>
                            <span x-show="manualSubmitting" x-cloak>Rendering Frame...</span>
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
