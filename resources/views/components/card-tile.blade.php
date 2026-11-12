@props(['card', 'showFavorite' => true])

@php
    $frameConfig = $card->frame?->config ?? [];
    $frameAccent = $frameConfig['accent_color'] ?? '#69AFFF';
    $framePrimary = $frameConfig['border_primary'] ?? '#3D4F66';
    $frameName = $card->frame?->name ?? 'Classic Frame';
@endphp

<article class="group relative flex flex-col bg-[#223056]/60 hover:bg-[#223056] rounded-xl border border-[#D4DCF0]/10 hover:border-[#AF47D2]/35 overflow-hidden card-collectible">
    <!-- Pure, Unobstructed 63:88 Card Display Viewport -->
    <a href="{{ route('pokedex.show', $card) }}"
       class="block relative w-full aspect-card card-stage overflow-hidden p-1.5 sm:p-2 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#AF47D2]"
       aria-label="Inspect {{ $card->display_name }} ({{ $card->card_number }})">
        @if ($card->effectiveFramedPath())
            <img src="{{ route('media.card.framed', $card) }}?v={{ $card->updated_at?->timestamp }}"
                 alt="{{ $card->display_name }}"
                 class="w-full h-full object-contain select-none transition-transform duration-200 group-hover:scale-[1.015]"
                 loading="lazy">
        @elseif ($card->photo)
            <img src="{{ route('media.photo.thumbnail', $card->photo) }}"
                 alt="{{ $card->display_name }}"
                 class="w-full h-full object-contain select-none transition-transform duration-200 group-hover:scale-[1.015]"
                 loading="lazy">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center border border-dashed border-[#D4DCF0]/15 rounded-lg">
                <span class="text-xs font-mono text-[#8B99BD] tabular-nums">{{ $card->card_number }}</span>
            </div>
        @endif
    </a>

    <!-- Clean Editorial Metadata Strip (Zero Collision with Card Artwork) -->
    <div class="px-3.5 py-3 border-t border-[#D4DCF0]/[0.08] flex flex-col gap-1.5">
        <div class="flex items-start justify-between gap-2 min-w-0">
            <a href="{{ route('pokedex.show', $card) }}"
               class="text-sm font-semibold text-[#F6F7FB] hover:text-[#FFA22B] truncate transition-colors leading-snug"
               title="{{ $card->display_name }}">
                {{ $card->display_name }}
            </a>

            @if ($showFavorite)
            <form method="POST" action="{{ route('favorites.toggle', $card) }}" class="flex-shrink-0 -mt-0.5">
                @csrf
                <button type="submit"
                        class="p-1 rounded-md transition-colors {{ $card->is_favorite ? 'text-[#FFDB00] hover:text-[#FFE84D]' : 'text-[#8B99BD] hover:text-[#D4DCF0]' }}"
                        title="{{ $card->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}"
                        aria-label="{{ $card->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}">
                    @if ($card->is_favorite)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    @endif
                </button>
            </form>
            @endif
        </div>

        <div class="flex items-center justify-between gap-2 text-xs text-[#8B99BD] min-w-0">
            <span class="font-mono tabular-nums text-[11px] text-[#BCC6E0] whitespace-nowrap">{{ $card->card_number }}</span>
            <span class="inline-flex items-center gap-1.5 min-w-0">
                <span class="w-2 h-2 rounded-full flex-shrink-0 border" style="background-color: {{ $framePrimary }}; border-color: {{ $frameAccent }};"></span>
                <span class="truncate text-[11px] text-[#BCC6E0]">{{ str_replace(' Frame', '', $frameName) }}</span>
            </span>
        </div>
    </div>
</article>
