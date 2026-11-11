<x-app-layout>
    <x-slot name="title">Starred Favorites — MEMODEX</x-slot>

    <div class="space-y-6">
        <div class="pb-4 border-b border-memodex-border">
            <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">Starred Items</span>
            <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Favorite Cards</h1>
            <p class="text-xs text-memodex-muted mt-1">
                Your pinned cards across all binders and categories ({{ $cards->total() }} total).
            </p>
        </div>

        @if ($cards->count() === 0)
            <div class="p-12 text-center rounded-xl border border-memodex-border bg-memodex-panel space-y-3 max-w-md mx-auto">
                <div class="w-12 h-12 mx-auto rounded-full bg-memodex-raised border border-memodex-border flex items-center justify-center text-amber-400">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-memodex-text">No favorites starred yet</h3>
                <p class="text-xs text-memodex-muted">Click the star button on any card in your Pokédex to pin it here.</p>
                <a href="{{ route('pokedex.index') }}" class="inline-flex items-center text-xs text-memodex-primary hover:underline">
                    Browse Pokédex &rarr;
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($cards as $card)
                    <x-card-tile :card="$card" />
                @endforeach
            </div>

            <div class="pt-4">
                {{ $cards->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
