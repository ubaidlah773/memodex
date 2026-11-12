<x-app-layout>
    <x-slot name="title">Edit {{ $card->card_number }} — MEMODEX</x-slot>

    @php
        $currCfg = $card->frame?->config ?? [];
    @endphp

    <div x-data="{
        name: '{{ addslashes($card->name ?? '') }}',
        selectedFrameId: '{{ $card->frame_id }}',
        selectedFrameName: '{{ addslashes($card->frame?->name ?? 'Classic Frame') }}',
        selectedOuterBg: '{{ $currCfg['outer_bg'] ?? '#0F1622' }}',
        selectedPrimary: '{{ $currCfg['border_primary'] ?? '#3D4F66' }}',
        selectedInner: '{{ $currCfg['border_inner'] ?? '#202E40' }}',
        selectedAccent: '{{ $currCfg['accent_color'] ?? '#69AFFF' }}',
        selectedLabel: '{{ addslashes($currCfg['label'] ?? 'CLASSIC ARCHIVE') }}',
        albumId: '{{ $card->albums->first()?->id ?? '' }}',
        isFavorite: {{ $card->is_favorite ? 'true' : 'false' }},
        updateFrame(id, name, outerBg, primary, inner, accent, label) {
            this.selectedFrameId = String(id);
            this.selectedFrameName = name;
            this.selectedOuterBg = outerBg;
            this.selectedPrimary = primary;
            this.selectedInner = inner;
            this.selectedAccent = accent;
            this.selectedLabel = label;
        }
    }" class="space-y-8">

        <!-- Breadcrumb & Title -->
        <section class="pb-5 border-b border-[#D4DCF0]/[0.08] flex flex-wrap items-end justify-between gap-4">
            <div>
                <a href="{{ route('pokedex.show', $card) }}" class="inline-flex items-center gap-2 text-sm text-[#BCC6E0] hover:text-[#F6F7FB] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    <span>Back to Card Detail</span>
                </a>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F6F7FB] mt-2">
                    Edit {{ $card->display_name }}
                </h1>
                <p class="text-sm text-[#BCC6E0] mt-1">Update catalog label, outer frame mount, favorite pin, and binder assignment.</p>
            </div>
            <span class="font-mono text-xs text-[#BCC6E0] px-3 py-1 rounded-md bg-[#26355D] border border-[#D4DCF0]/10">
                {{ $card->card_number }}
            </span>
        </section>

        <form method="POST" action="{{ route('cards.update', $card) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            @csrf
            @method('PUT')
            <input type="hidden" name="frame_id" :value="selectedFrameId">

            <!-- Left Preview -->
            <div class="lg:col-span-5 flex flex-col items-center lg:sticky lg:top-8 space-y-3">
                <div class="w-full max-w-[360px] flex items-center justify-between text-xs">
                    <span class="text-[#BCC6E0] font-medium">Frame Preview</span>
                    <span class="font-medium" :style="`color: ${selectedAccent};`" x-text="selectedFrameName"></span>
                </div>

                <div class="w-full max-w-[360px] aspect-card rounded-2xl border-2 p-3 flex flex-col justify-between shadow-2xl transition-colors duration-200 select-none"
                     :style="`border-color: ${selectedPrimary}; background-color: ${selectedOuterBg};`">
                    <div class="w-full h-full rounded-xl border p-2.5 flex flex-col justify-between"
                         :style="`border-color: ${selectedInner};`">
                        <div class="w-full flex-1 rounded-lg bg-[#11182E] overflow-hidden flex items-center justify-center border border-[#D4DCF0]/10">
                            @if ($card->photo)
                                <img src="{{ route('media.card.original', $card) }}" alt="{{ $card->display_name }}" class="w-full h-full object-contain">
                            @endif
                        </div>
                        <div class="mt-2.5 px-3 py-1.5 rounded bg-[#0A0F16] border flex items-center justify-between text-[9px] font-mono"
                             :style="`border-color: ${selectedInner};`">
                            <span class="text-[#8B99BD]">MEMODEX</span>
                            <span class="font-semibold uppercase truncate mx-2" :style="`color: ${selectedAccent};`" x-text="selectedLabel"></span>
                            <span class="text-[#F6F7FB]">{{ $card->card_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="lg:col-span-7 space-y-7">
                <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-4">
                    <h2 class="font-display text-base font-semibold text-[#F6F7FB]">Catalog Information</h2>

                    <div>
                        <label for="name" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Card Name / Catalog Label</label>
                        <input type="text" id="name" name="name" x-model="name" required maxlength="80" class="memodex-input">
                    </div>

                    <label for="is_favorite" class="inline-flex items-center gap-2.5 cursor-pointer pt-1">
                        <input type="checkbox" id="is_favorite" name="is_favorite" value="1" x-model="isFavorite"
                               class="rounded bg-[#161F3B] border-[#3B4E82] text-[#FFDB00] focus:ring-[#FFDB00]">
                        <span class="text-sm text-[#F6F7FB]">Pin to Starred Favorites</span>
                    </label>
                </section>

                <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-4">
                    <h2 class="font-display text-base font-semibold text-[#F6F7FB]">Outer Decorative Frame</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @foreach ($frames as $fr)
                            @php $fc = $fr->config ?? []; @endphp
                            <button type="button"
                                    @click="updateFrame('{{ $fr->id }}', '{{ addslashes($fr->name) }}', '{{ $fc['outer_bg'] ?? '#0F1622' }}', '{{ $fc['border_primary'] ?? '#3D4F66' }}', '{{ $fc['border_inner'] ?? '#202E40' }}', '{{ $fc['accent_color'] ?? '#69AFFF' }}', '{{ addslashes($fc['label'] ?? $fr->name) }}')"
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

                @if ($albums->count() > 0)
                <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-3">
                    <h2 class="font-display text-base font-semibold text-[#F6F7FB]">Assign to Binder</h2>
                    <select id="album_id" name="album_id" x-model="albumId" class="memodex-select">
                        <option value="">Keep current binder assignments</option>
                        @foreach ($albums as $alb)
                            <option value="{{ $alb->id }}">{{ $alb->name }}</option>
                        @endforeach
                    </select>
                </section>
                @endif

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('pokedex.show', $card) }}" class="memodex-btn-ghost">Cancel</a>
                    <button type="submit" class="memodex-btn-primary px-6 py-2.5">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
