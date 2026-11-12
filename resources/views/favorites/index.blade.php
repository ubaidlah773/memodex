<x-app-layout>
    <x-slot name="title">Favorites — MEMODEX</x-slot>

    <div class="space-y-6">
        <section class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-5 border-b border-[#C8D2DF]/[0.08]">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8]">Favorites</h1>
                <p class="text-sm text-[#9DAABD] mt-1">
                    {{ number_format($cards->total()) }} starred {{ $cards->total() === 1 ? 'card' : 'cards' }} pinned across your collection.
                </p>
            </div>
            <a href="{{ route('pokedex.index') }}" class="memodex-btn-secondary self-start sm:self-auto">
                Browse Pokédex
            </a>
        </section>

        @if ($cards->count() === 0)
            <x-empty-state
                title="No favorites starred yet"
                description="Star any card in your Pokédex or Card Detail view to pin your prized pulls here."
                icon="star">
                <x-slot:actions>
                    <a href="{{ route('pokedex.index') }}" class="memodex-btn-primary">
                        Open Pokédex
                    </a>
                </x-slot:actions>
            </x-empty-state>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-5 lg:gap-6">
                @foreach ($cards as $card)
                    <x-card-tile :card="$card" />
                @endforeach
            </div>

            @if ($cards->hasPages())
            <div class="pt-4">
                {{ $cards->links() }}
            </div>
            @endif
        @endif
    </div>
</x-app-layout>
