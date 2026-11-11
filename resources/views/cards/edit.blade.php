<x-app-layout>
    <x-slot name="title">Edit {{ $card->card_number }} — MEMODEX</x-slot>

    <div x-data="{
        name: '{{ addslashes($card->name ?? '') }}',
        selectedFrameId: '{{ $card->frame_id }}',
        selectedFrameName: '{{ addslashes($card->frame->name ?? 'Classic Frame') }}',
        selectedFrameColor: '{{ $card->frame->config['border_primary'] ?? '#3D4F66' }}',
        selectedFrameAccent: '{{ $card->frame->config['accent_color'] ?? '#83B9F2' }}',
        albumId: '{{ $card->albums->first()?->id ?? '' }}',
        isFavorite: {{ $card->is_favorite ? 'true' : 'false' }},
        updateFrame(id, name, primary, accent) {
            this.selectedFrameId = id;
            this.selectedFrameName = name;
            this.selectedFrameColor = primary;
            this.selectedFrameAccent = accent;
        }
    }" class="space-y-6">

        <!-- Top Header -->
        <div class="flex items-center justify-between pb-4 border-b border-memodex-border">
            <div>
                <a href="{{ route('pokedex.show', $card) }}" class="text-xs font-mono text-memodex-subtle hover:text-memodex-primary">&larr; Back to Card Detail</a>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Edit Card: {{ $card->card_number }}</h1>
                <p class="text-xs text-memodex-muted mt-0.5">Original card artwork is 100% preserved. Update catalog labels, frame, and album assignments.</p>
            </div>
            <span class="px-2.5 py-1 rounded bg-memodex-raised border border-memodex-border text-xs font-mono font-bold text-memodex-text">
                {{ $card->card_number }}
            </span>
        </div>

        <form method="POST" action="{{ route('cards.update', $card) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf
            @method('PUT')
            <input type="hidden" name="frame_id" :value="selectedFrameId">

            <!-- Left Preview: Card in Frame -->
            <div class="lg:col-span-5 flex flex-col items-center lg:sticky lg:top-24">
                <div class="w-full max-w-sm">
                    <div class="text-[11px] font-mono text-memodex-subtle uppercase mb-2 flex items-center justify-between">
                        <span>Card Preview</span>
                        <span class="text-memodex-primary" x-text="selectedFrameName"></span>
                    </div>

                    <div class="w-full aspect-card rounded-xl border-2 overflow-hidden relative shadow-2xl flex flex-col justify-between p-3 select-none transition-all duration-200"
                         :style="`border-color: ${selectedFrameColor}; background-color: #0F1622;`">
                        <div class="w-full h-full rounded border flex flex-col justify-between p-2 relative"
                             :style="`border-color: ${selectedFrameAccent};`">
                            <div class="w-full flex-1 rounded bg-[#0A0E14] overflow-hidden flex items-center justify-center relative border border-memodex-border">
                                @if ($card->photo)
                                    <img src="{{ route('media.photo.thumbnail', $card->photo) }}" alt="{{ $card->name }}" class="max-w-full max-h-full object-contain">
                                @endif
                            </div>

                            <div class="mt-2 px-2 py-1.5 rounded bg-[#0A0F16] border border-memodex-border flex items-center justify-between text-[9px] font-mono">
                                <span class="text-memodex-subtle">MEMODEX ARCHIVE</span>
                                <span class="font-bold" :style="`color: ${selectedFrameAccent};`" x-text="selectedFrameName.toUpperCase()"></span>
                                <span class="text-memodex-text">{{ $card->card_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Label & Catalog Info -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">
                        1. Catalog Details
                    </span>

                    <div>
                        <label for="name" class="block text-xs text-memodex-muted mb-1 font-medium">Card Name / Catalog Label</label>
                        <input type="text" id="name" name="name" x-model="name" required maxlength="80"
                               class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                        <span class="text-[10px] text-memodex-subtle mt-1 block">Custom name in your collection library.</span>
                    </div>

                    <!-- Favorite Checkbox -->
                    <div class="pt-2 flex items-center gap-2">
                        <input type="checkbox" id="is_favorite" name="is_favorite" value="1" x-model="isFavorite"
                               class="rounded bg-memodex-bg border-memodex-border text-memodex-primary focus:ring-memodex-primary">
                        <label for="is_favorite" class="text-xs text-memodex-text font-medium cursor-pointer">Mark as Favorite Card</label>
                    </div>
                </div>

                <!-- Frame Selection -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">
                        2. Outer Decorative Frame
                    </span>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @foreach ($frames as $fr)
                            <button type="button"
                                    @click="updateFrame('{{ $fr->id }}', '{{ $fr->name }}', '{{ $fr->config['border_primary'] ?? '#3D4F66' }}', '{{ $fr->config['accent_color'] ?? '#83B9F2' }}')"
                                    :class="selectedFrameId == '{{ $fr->id }}' ? 'border-memodex-primary bg-memodex-soft/80 ring-1 ring-memodex-primary' : 'border-memodex-border bg-memodex-bg hover:border-memodex-border-hover'"
                                    class="text-left p-3 rounded-lg border transition-all flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full border shadow-sm flex-shrink-0" style="background-color: {{ $fr->config['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fr->config['accent_color'] ?? '#83B9F2' }};"></div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-semibold text-memodex-text truncate">{{ $fr->name }}</p>
                                    <p class="text-[10px] text-memodex-muted truncate">{{ $fr->description }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Binder Assignment -->
                @if ($albums->count() > 0)
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">
                        3. Binder Assignment
                    </span>

                    <div>
                        <label for="album_id" class="block text-xs text-memodex-muted mb-1 font-medium">Add to Binder</label>
                        <select id="album_id" name="album_id" x-model="albumId"
                                class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                            <option value="">None / Keep existing binders</option>
                            @foreach ($albums as $alb)
                                <option value="{{ $alb->id }}">{{ $alb->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('pokedex.show', $card) }}" class="px-4 py-2 text-xs text-memodex-muted hover:text-memodex-text">
                        Cancel
                    </a>

                    <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-md text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors shadow">
                        Save Changes &rarr;
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
