<x-app-layout>
    <x-slot name="title">My Collection — MEMODEX</x-slot>

    <div class="space-y-8"
         x-data="{
            dragging: false,
            uploading: false,
            uploadProgress: 0,
            uploadError: '',
            deleteTargetAction: '',
            deleteTargetName: '',
            uploadFiles(fileList) {
                if (!fileList || fileList.length === 0 || this.uploading) return;
                this.uploadError = '';
                const allowed = ['image/jpeg', 'image/png', 'image/webp'];
                const fd = new FormData();
                fd.append('_token', document.querySelector('meta[name=csrf-token]').content);

                let count = 0;
                for (const file of fileList) {
                    if (!allowed.includes(file.type)) {
                        this.uploadError = file.name + ' is not JPEG, PNG, or WebP.';
                        return;
                    }
                    if (file.size > 15 * 1024 * 1024) {
                        this.uploadError = file.name + ' exceeds 15 MB.';
                        return;
                    }
                    fd.append('photos[]', file);
                    count++;
                }
                if (count === 0) return;

                this.uploading = true;
                this.uploadProgress = 0;

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('photos.store') }}', true);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                };
                xhr.onload = () => {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        if (xhr.status >= 200 && xhr.status < 300 && res.success) {
                            window.location.reload();
                            return;
                        }
                        this.uploadError = (res.errors && res.errors.join(', ')) || res.message || 'Upload failed.';
                    } catch {
                        this.uploadError = 'Upload error (' + xhr.status + ').';
                    }
                    this.uploading = false;
                };
                xhr.onerror = () => {
                    this.uploadError = 'Network error while uploading original files.';
                    this.uploading = false;
                };
                xhr.send(fd);
            }
         }">

        <!-- Header & Status Filter -->
        <section class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-5 border-b border-[#C8D2DF]/[0.08]">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8]">My Collection</h1>
                <p class="text-sm text-[#9DAABD] mt-1">
                    Original unaltered card uploads stored privately ({{ round(Auth::user()->storage_used_bytes / (1024 * 1024), 1) }} / {{ round(Auth::user()->storage_quota_bytes / (1024 * 1024), 0) }} MB used).
                </p>
            </div>

            <div class="inline-flex items-center p-1 bg-[#17243A] border border-[#C8D2DF]/10 rounded-xl text-xs font-medium self-start md:self-auto">
                <a href="{{ route('photos.index') }}" class="px-3.5 py-1.5 rounded-lg transition-colors {{ empty($filter) ? 'bg-[#263449] text-[#F3F5F8]' : 'text-[#9DAABD] hover:text-[#F3F5F8]' }}">
                    All Assets
                </a>
                <a href="{{ route('photos.index', ['filter' => 'used']) }}" class="px-3.5 py-1.5 rounded-lg transition-colors {{ $filter === 'used' ? 'bg-[#263449] text-[#F3F5F8]' : 'text-[#9DAABD] hover:text-[#F3F5F8]' }}">
                    Framed
                </a>
                <a href="{{ route('photos.index', ['filter' => 'unused']) }}" class="px-3.5 py-1.5 rounded-lg transition-colors {{ $filter === 'unused' ? 'bg-[#263449] text-[#F3F5F8]' : 'text-[#9DAABD] hover:text-[#F3F5F8]' }}">
                    Unframed
                </a>
            </div>
        </section>

        <!-- Drag & Drop Original Upload Zone -->
        <section @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; uploadFiles($event.dataTransfer.files)"
                 :class="dragging ? 'border-[#69AFFF] bg-[#1D293B]' : 'border-[#2D3C53] bg-[#17243A]/50 hover:border-[#3E5474]'"
                 class="relative border-2 border-dashed rounded-2xl p-6 sm:p-8 text-center transition-colors">
            <form method="POST" action="{{ route('photos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="file"
                       id="photosInput"
                       name="photos[]"
                       multiple
                       accept="image/jpeg,image/png,image/webp"
                       @change="uploadFiles($event.target.files); $event.target.value = ''"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
            </form>

            <div class="max-w-md mx-auto space-y-2.5 pointer-events-none">
                <div class="w-11 h-11 mx-auto rounded-xl bg-[#101827] border border-[#C8D2DF]/15 flex items-center justify-center text-[#69AFFF]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                </div>
                <p class="font-display text-sm font-semibold text-[#F3F5F8]">Drop original card scans here or click to browse</p>
                <p class="text-xs text-[#9DAABD]">JPEG, PNG, or WebP up to 15 MB each &middot; Preserved at full resolution</p>
            </div>
        </section>

        <!-- Error / Progress Feedback -->
        <div x-show="uploadError" x-cloak class="p-3.5 rounded-xl bg-[#EB6F75]/10 border border-[#EB6F75]/35 text-sm text-[#EB6F75]" x-text="uploadError"></div>

        <div x-show="uploading" x-cloak class="p-4 rounded-xl bg-[#17243A] border border-[#C8D2DF]/15 space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="text-[#F3F5F8] font-medium">Uploading original files to private vault...</span>
                <span class="font-mono text-[#69AFFF] tabular-nums" x-text="uploadProgress + '%'"></span>
            </div>
            <div class="w-full h-1.5 rounded-full bg-[#101827] overflow-hidden">
                <div class="h-full bg-[#69AFFF] transition-all duration-150" :style="`width: ${uploadProgress}%`"></div>
            </div>
        </div>

        <!-- Original Assets Grid (63:88 Uncropped!) -->
        @if ($photos->count() === 0)
            <x-empty-state
                title="No original assets in this view"
                description="Upload Pokémon card images above to store untouched originals and reuse them across multiple outer frames."
                icon="card" />
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-5 lg:gap-6">
                @foreach ($photos as $photo)
                    <article class="bg-[#1D293B]/60 rounded-xl border border-[#C8D2DF]/10 overflow-hidden flex flex-col justify-between">
                        <!-- 63:88 Uncropped Viewport -->
                        <div class="aspect-card card-stage p-2 overflow-hidden flex items-center justify-center">
                            <img src="{{ route('media.photo.thumbnail', $photo) }}"
                                 alt="{{ $photo->original_name }}"
                                 class="w-full h-full object-contain select-none"
                                 loading="lazy">
                        </div>

                        <div class="px-3.5 py-3 border-t border-[#C8D2DF]/[0.08] space-y-2">
                            <div>
                                <p class="text-xs font-semibold text-[#F3F5F8] truncate" title="{{ $photo->original_name }}">{{ $photo->original_name }}</p>
                                <div class="flex items-center justify-between text-[11px] font-mono text-[#6D7A8D] mt-1">
                                    <span>{{ $photo->fileSizeHuman() }}</span>
                                    <span>{{ $photo->cards_count > 0 ? $photo->cards_count . ' framed' : 'Unframed' }}</span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-[#C8D2DF]/[0.06] flex items-center justify-between text-xs">
                                <a href="{{ route('cards.create', ['photo_id' => $photo->id, 'mode' => 'manual']) }}" class="font-medium text-[#69AFFF] hover:underline">
                                    + Frame Card
                                </a>

                                <button type="button"
                                        @click="deleteTargetAction = '{{ route('photos.destroy', $photo) }}'; deleteTargetName = '{{ addslashes($photo->original_name) }}'"
                                        class="text-[#6D7A8D] hover:text-[#EB6F75] transition-colors">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($photos->hasPages())
            <div class="pt-4">
                {{ $photos->links() }}
            </div>
            @endif
        @endif

        <!-- Styled Delete Original Confirmation Modal -->
        <div x-show="deleteTargetAction" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-[#070B14]/80 backdrop-blur-sm" @click="deleteTargetAction = ''"></div>
            <div class="relative z-10 bg-[#1D293B] border border-[#C8D2DF]/15 rounded-2xl p-6 max-w-md w-full space-y-4 shadow-2xl">
                <h3 class="font-display text-lg font-semibold text-[#F3F5F8]">Delete Original File?</h3>
                <p class="text-sm text-[#9DAABD] leading-relaxed">
                    Remove <strong class="text-[#F3F5F8]" x-text="deleteTargetName"></strong> from your private collection? Files linked to existing Pokédex cards cannot be deleted until those cards are removed.
                </p>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="deleteTargetAction = ''" class="memodex-btn-secondary text-xs">Cancel</button>
                    <form method="POST" :action="deleteTargetAction">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 rounded-lg bg-[#EB6F75] hover:bg-[#dc5b62] text-[#101827] text-xs font-semibold transition-colors">
                            Delete File
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
