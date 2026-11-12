<x-app-layout>
    <x-slot name="title">Albums &amp; Binders — MEMODEX</x-slot>

    <div class="space-y-8" x-data="{ showCreateModal: false }">
        <!-- Page Header -->
        <section class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-5 border-b border-[#C8D2DF]/[0.08]">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8]">Albums &amp; Binders</h1>
                <p class="text-sm text-[#9DAABD] mt-1">
                    Curate your framed cards into themed digital binders with controlled public sharing.
                </p>
            </div>
            <button type="button" @click="showCreateModal = true" class="memodex-btn-primary self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>New Binder</span>
            </button>
        </section>

        @if ($albums->count() === 0)
            <x-empty-state
                title="No card binders created yet"
                description="Group your Pokémon cards into themed binders—such as Master Sets, Vintage Favorites, or Trade Binders—and share read-only showcase links."
                icon="binder">
                <x-slot:actions>
                    <button type="button" @click="showCreateModal = true" class="memodex-btn-primary">
                        Create First Binder
                    </button>
                </x-slot:actions>
            </x-empty-state>
        @else
            <!-- Digital Card Binder Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($albums as $album)
                    <article class="group bg-[#17243A] border border-[#C8D2DF]/10 hover:border-[#69AFFF]/35 rounded-2xl overflow-hidden transition-all flex flex-col justify-between card-collectible">
                        <!-- Binder Sleeve Preview Window (4-Pocket 63:88 Spread) -->
                        <a href="{{ route('albums.show', $album) }}" class="block p-4 bg-[#0B111C]/80 border-b border-[#C8D2DF]/[0.08]">
                            <div class="grid grid-cols-4 gap-2.5">
                                @for ($i = 0; $i < 4; $i++)
                                    @php $card = $album->cards->get($i); @endphp
                                    <div class="aspect-card rounded-lg bg-[#101827] border border-[#C8D2DF]/10 overflow-hidden flex items-center justify-center p-1">
                                        @if ($card && $card->effectiveFramedPath())
                                            <img src="{{ route('media.card.framed', $card) }}" alt="{{ $card->display_name }}" class="w-full h-full object-contain" loading="lazy">
                                        @elseif ($card && $card->photo)
                                            <img src="{{ route('media.photo.thumbnail', $card->photo) }}" alt="{{ $card->display_name }}" class="w-full h-full object-contain" loading="lazy">
                                        @else
                                            <div class="w-4 h-6 rounded-sm border border-dashed border-[#C8D2DF]/10"></div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </a>

                        <!-- Binder Folio Details -->
                        <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                            <div class="space-y-1.5">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('albums.show', $album) }}" class="font-display text-lg font-semibold text-[#F3F5F8] group-hover:text-[#69AFFF] transition-colors truncate">
                                        {{ $album->name }}
                                    </a>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium flex-shrink-0 {{ $album->isPublic() ? 'text-[#5CC99B]' : 'text-[#6D7A8D]' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $album->isPublic() ? 'bg-[#5CC99B]' : 'bg-[#6D7A8D]' }}"></span>
                                        {{ $album->isPublic() ? 'Shared' : 'Private' }}
                                    </span>
                                </div>
                                <p class="text-sm text-[#9DAABD] line-clamp-2 leading-relaxed">
                                    {{ $album->description ?: 'No description added.' }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-[#C8D2DF]/[0.08] flex items-center justify-between text-xs">
                                <span class="font-mono text-[#9DAABD] tabular-nums">{{ $album->cards_count }} {{ $album->cards_count === 1 ? 'card' : 'cards' }}</span>
                                <a href="{{ route('albums.show', $album) }}" class="font-medium text-[#69AFFF] hover:underline">
                                    Open Binder &rarr;
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($albums->hasPages())
            <div class="pt-4">
                {{ $albums->links() }}
            </div>
            @endif
        @endif

        <!-- Create Binder Modal -->
        <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-[#070B14]/80 backdrop-blur-sm" @click="showCreateModal = false"></div>
            <div class="relative z-10 bg-[#1D293B] border border-[#C8D2DF]/15 rounded-2xl p-6 max-w-md w-full space-y-5 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-[#C8D2DF]/10">
                    <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Create New Binder</h2>
                    <button type="button" @click="showCreateModal = false" class="text-[#9DAABD] hover:text-[#F3F5F8] text-xl leading-none">&times;</button>
                </div>

                <form method="POST" action="{{ route('albums.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="modal_name" class="block text-xs font-medium text-[#9DAABD] mb-1.5">Binder Title</label>
                        <input type="text" id="modal_name" name="name" required maxlength="80" placeholder="e.g. Base Set Holofoil Archive" class="memodex-input">
                    </div>

                    <div>
                        <label for="modal_desc" class="block text-xs font-medium text-[#9DAABD] mb-1.5">Description (Optional)</label>
                        <textarea id="modal_desc" name="description" rows="3" maxlength="300" placeholder="Notes on this binder set or collection theme..." class="memodex-input"></textarea>
                    </div>

                    <div>
                        <label for="modal_vis" class="block text-xs font-medium text-[#9DAABD] mb-1.5">Visibility</label>
                        <select id="modal_vis" name="visibility" class="memodex-select">
                            <option value="private">Private (Only visible to you)</option>
                            <option value="public-link">Public Link (Ready for shareable token)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="showCreateModal = false" class="memodex-btn-ghost text-xs">Cancel</button>
                        <button type="submit" class="memodex-btn-primary text-xs px-5 py-2.5">Create Binder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
