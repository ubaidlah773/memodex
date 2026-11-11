@props(['card'])

<div class="group relative flex flex-col bg-memodex-panel rounded-xl border border-memodex-border hover:border-memodex-border-hover overflow-hidden transition-all duration-200 shadow-md hover:shadow-xl">
    <!-- Card Ratio Viewport (63:88) -->
    <a href="{{ route('pokedex.show', $card) }}" class="block relative w-full aspect-card bg-[#0C1118] overflow-hidden cursor-pointer">
        @if ($card->effectiveFramedPath())
            <img src="{{ route('media.card.framed', $card) }}" alt="{{ $card->display_name }}" class="w-full h-full object-contain select-none transition-transform duration-200 group-hover:scale-[1.02]" loading="lazy">
        @elseif ($card->photo)
            <img src="{{ route('media.photo.thumbnail', $card->photo) }}" alt="{{ $card->display_name }}" class="w-full h-full object-cover select-none" loading="lazy">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center">
                <span class="text-xs text-memodex-subtle font-mono">{{ $card->card_number }}</span>
            </div>
        @endif

        <!-- Frame Badge Pill -->
        <div class="absolute top-2 left-2 z-10">
            @php
                $frameConfig = $card->frame?->config ?? [];
                $pillBg = $frameConfig['outer_bg'] ?? '#141D2B';
                $pillColor = $frameConfig['accent_color'] ?? '#83B9F2';
                $pillBorder = $frameConfig['border_primary'] ?? '#3D4F66';
            @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-mono tracking-wider font-semibold border backdrop-blur-sm"
                  style="background-color: {{ $pillBg }}ee; color: {{ $pillColor }}; border-color: {{ $pillBorder }};">
                {{ $card->frame->name ?? 'Classic' }}
            </span>
        </div>

        <!-- Quick Favorite Button -->
        <form method="POST" action="{{ route('favorites.toggle', $card) }}" class="absolute top-2 right-2 z-20" @click.stop>
            @csrf
            <button type="submit" class="w-7 h-7 rounded-full bg-memodex-panel/85 hover:bg-memodex-panel border border-memodex-border flex items-center justify-center transition-colors {{ $card->is_favorite ? 'text-amber-400 bg-amber-400/10 border-amber-400/40' : 'text-memodex-subtle hover:text-memodex-text' }}" title="{{ $card->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </button>
        </form>
    </a>

    <!-- Card Info Strip -->
    <div class="p-3 border-t border-memodex-border flex flex-col justify-between flex-1 bg-memodex-panel">
        <div class="flex items-start justify-between gap-1 mb-1">
            <a href="{{ route('pokedex.show', $card) }}" class="font-semibold text-xs text-memodex-text hover:text-memodex-primary truncate transition-colors" title="{{ $card->display_name }}">
                {{ $card->display_name }}
            </a>
        </div>
        <div class="flex items-center justify-between text-[10px] font-mono text-memodex-subtle pt-1 border-t border-memodex-border/40">
            <span class="font-bold text-memodex-text">{{ $card->card_number }}</span>
            <span>{{ $card->created_at->format('M d, Y') }}</span>
        </div>
    </div>
</div>
