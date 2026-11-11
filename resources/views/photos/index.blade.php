<x-app-layout>
    <x-slot name="title">Photo Library — MEMODEX</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">Private Assets</span>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Photo Library</h1>
                <p class="text-xs text-memodex-muted mt-1">
                    Upload and manage high-resolution original photos for your cards. Storage: {{ round(Auth::user()->storage_used_bytes / (1024 * 1024), 1) }} / 500 MB.
                </p>
            </div>
            <!-- Filter by used / unused -->
            <div class="flex items-center gap-1.5 bg-memodex-panel p-1 rounded-md border border-memodex-border text-xs">
                <a href="{{ route('photos.index') }}" class="px-2.5 py-1 rounded {{ empty($filter) ? 'bg-memodex-soft text-memodex-primary font-medium' : 'text-memodex-muted hover:text-memodex-text' }}">
                    All
                </a>
                <a href="{{ route('photos.index', ['filter' => 'used']) }}" class="px-2.5 py-1 rounded {{ $filter === 'used' ? 'bg-memodex-soft text-memodex-primary font-medium' : 'text-memodex-muted hover:text-memodex-text' }}">
                    Used in Cards
                </a>
                <a href="{{ route('photos.index', ['filter' => 'unused']) }}" class="px-2.5 py-1 rounded {{ $filter === 'unused' ? 'bg-memodex-soft text-memodex-primary font-medium' : 'text-memodex-muted hover:text-memodex-text' }}">
                    Unused
                </a>
            </div>
        </div>

        <!-- Multi-file Upload Dropzone Panel -->
        <div class="p-6 rounded-xl border border-dashed border-memodex-border bg-memodex-panel/50 text-center" x-data="{ isDragging: false, uploading: false }">
            <form method="POST" action="{{ route('photos.store') }}" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="max-w-md mx-auto space-y-3">
                    <div class="w-12 h-12 mx-auto rounded-full bg-memodex-raised border border-memodex-border flex items-center justify-center text-memodex-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <div>
                        <label for="photosInput" class="cursor-pointer text-xs font-semibold text-memodex-primary hover:text-memodex-primary-strong">
                            Click to browse photos
                            <input type="file" id="photosInput" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" class="sr-only" onchange="document.getElementById('uploadForm').submit()">
                        </label>
                        <span class="text-xs text-memodex-muted"> or select files to upload</span>
                        <p class="text-[11px] text-memodex-subtle mt-1 font-mono">
                            JPEG, PNG, WebP up to 15 MB each &bull; Private encrypted storage
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Photo Grid -->
        @if ($photos->count() === 0)
            <div class="p-12 text-center rounded-xl border border-memodex-border bg-memodex-panel">
                <p class="text-sm font-semibold text-memodex-text">No photos in this view</p>
                <p class="text-xs text-memodex-muted mt-1">Upload personal photos to start turning them into trading cards.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($photos as $photo)
                    <div class="group relative rounded-lg border border-memodex-border bg-memodex-panel overflow-hidden flex flex-col justify-between">
                        <!-- Thumbnail Viewport -->
                        <div class="aspect-square bg-memodex-bg overflow-hidden relative">
                            <img src="{{ route('media.photo.thumbnail', $photo) }}" alt="{{ $photo->original_name }}" class="w-full h-full object-cover" loading="lazy">

                            <!-- Status Badge -->
                            <div class="absolute top-1.5 left-1.5">
                                @if ($photo->cards_count > 0)
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-mono bg-memodex-panel/90 border border-memodex-border text-memodex-primary font-semibold">
                                        {{ $photo->cards_count }} card{{ $photo->cards_count === 1 ? '' : 's' }}
                                    </span>
                                @else
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-mono bg-memodex-panel/90 border border-memodex-border text-memodex-subtle">
                                        Unused
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Info & Action Strip -->
                        <div class="p-2.5 space-y-1.5 bg-memodex-panel border-t border-memodex-border">
                            <p class="text-xs text-memodex-text font-medium truncate" title="{{ $photo->original_name }}">
                                {{ $photo->original_name }}
                            </p>
                            <div class="flex items-center justify-between text-[10px] font-mono text-memodex-subtle">
                                <span>{{ $photo->fileSizeHuman() }}</span>
                                @if ($photo->width && $photo->height)
                                    <span>{{ $photo->width }}&times;{{ $photo->height }}</span>
                                @endif
                            </div>

                            <div class="pt-2 flex items-center justify-between border-t border-memodex-border/50">
                                <a href="{{ route('cards.create', ['photo_id' => $photo->id]) }}" class="text-[11px] font-medium text-memodex-primary hover:underline">
                                    + Make Card
                                </a>

                                <form method="POST" action="{{ route('photos.destroy', $photo) }}" onsubmit="return confirm('Delete this original photo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-memodex-subtle hover:text-memodex-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $photos->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
