<x-app-layout>
    <x-slot name="title">{{ $album->name }} — MEMODEX Binder</x-slot>

    <div class="space-y-8"
         x-data="{
            showShareModal: false,
            showAddCardModal: false,
            showEditModal: false,
            showDeleteModal: false,
            reorderMode: false,
            savingOrder: false,
            copied: false,
            cardsOrder: @js($album->cards->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->display_name,
                'number' => $c->card_number,
                'img' => $c->effectiveFramedPath() ? route('media.card.framed', $c) : ($c->photo ? route('media.photo.thumbnail', $c->photo) : ''),
            ])->values()),
            moveCard(index, delta) {
                const target = index + delta;
                if (target < 0 || target >= this.cardsOrder.length) return;
                const copy = [...this.cardsOrder];
                const [item] = copy.splice(index, 1);
                copy.splice(target, 0, item);
                this.cardsOrder = copy;
            },
            async saveOrder() {
                this.savingOrder = true;
                try {
                    const res = await fetch('{{ route('albums.reorder', $album) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({ card_ids: this.cardsOrder.map(c => c.id) })
                    });
                    if (res.ok) {
                        window.location.reload();
                    } else {
                        window.dispatchEvent(new CustomEvent('memodex-toast', { detail: { message: 'Could not save binder order.', type: 'error' } }));
                    }
                } catch {
                    window.dispatchEvent(new CustomEvent('memodex-toast', { detail: { message: 'Network error saving order.', type: 'error' } }));
                } finally {
                    this.savingOrder = false;
                }
            },
            copyShareLink(url) {
                navigator.clipboard.writeText(url).then(() => {
                    this.copied = true;
                    window.dispatchEvent(new CustomEvent('memodex-toast', { detail: { message: 'Share link copied to clipboard.' } }));
                    setTimeout(() => this.copied = false, 2000);
                });
            }
         }">

        <!-- Breadcrumb & Folio Header -->
        <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-4">
            <a href="{{ route('albums.index') }}" class="inline-flex items-center gap-2 text-sm text-[#BCC6E0] hover:text-[#F6F7FB] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Albums</span>
            </a>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-5">
                <div class="space-y-1.5 max-w-2xl">
                    <div class="flex items-center gap-2.5 text-xs">
                        <span class="inline-flex items-center gap-1.5 font-medium {{ $album->isPublic() ? 'text-[#5CC99B]' : 'text-[#BCC6E0]' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $album->isPublic() ? 'bg-[#5CC99B]' : 'bg-[#8B99BD]' }}"></span>
                            {{ $album->isPublic() ? 'Public Share Enabled' : 'Private Binder' }}
                        </span>
                        <span class="text-[#8B99BD]">&middot;</span>
                        <span class="font-mono text-[#BCC6E0] tabular-nums">{{ $album->cards->count() }} {{ $album->cards->count() === 1 ? 'card' : 'cards' }}</span>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F6F7FB]">{{ $album->name }}</h1>
                    @if ($album->description)
                        <p class="text-sm text-[#BCC6E0] leading-relaxed">{{ $album->description }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
                    @if ($album->cards->count() > 1)
                        <button type="button" @click="reorderMode = !reorderMode" class="memodex-btn-secondary text-xs px-3.5 py-2">
                            <span x-text="reorderMode ? 'Cancel Reorder' : 'Reorder Cards'"></span>
                        </button>
                    @endif
                    <button type="button" @click="showEditModal = true" class="memodex-btn-secondary text-xs px-3.5 py-2">
                        Edit Details
                    </button>
                    <button type="button" @click="showShareModal = true" class="memodex-btn-secondary text-xs px-3.5 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                        <span>Share</span>
                    </button>
                    <button type="button" @click="showAddCardModal = true" class="memodex-btn-primary text-xs px-4 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span>Add Cards</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Binder Card Layout -->
        @if ($album->cards->count() === 0)
            <x-empty-state
                title="This binder is empty"
                description="Add framed cards from your Pokédex to assemble your binder spread."
                icon="binder">
                <x-slot:actions>
                    <button type="button" @click="showAddCardModal = true" class="memodex-btn-primary">
                        Add Cards from Pokédex
                    </button>
                </x-slot:actions>
            </x-empty-state>
        @else
            <!-- Reorder Mode Spread -->
            <div x-show="reorderMode" x-cloak class="space-y-4">
                <div class="p-4 rounded-xl bg-[#26355D] border border-[#AF47D2]/30 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-[#F6F7FB]">Arrange binder pocket sequence using the move controls, then save order.</p>
                    <button type="button" @click="saveOrder()" :disabled="savingOrder" class="memodex-btn-primary text-xs px-4 py-2">
                        <span x-show="!savingOrder">Save Binder Order</span>
                        <span x-show="savingOrder" x-cloak>Saving...</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
                    <template x-for="(c, idx) in cardsOrder" :key="c.id">
                        <div class="bg-[#223056] border border-[#D4DCF0]/15 rounded-xl overflow-hidden flex flex-col">
                            <div class="aspect-card card-stage p-2 flex items-center justify-center relative">
                                <img :src="c.img" :alt="c.name" class="w-full h-full object-contain">
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-[#161F3B]/90 border border-[#D4DCF0]/20 font-mono text-[11px] text-[#F6F7FB]" x-text="'#' + (idx + 1)"></span>
                            </div>
                            <div class="p-3 border-t border-[#D4DCF0]/10 space-y-2">
                                <p class="text-xs font-semibold text-[#F6F7FB] truncate" x-text="c.name"></p>
                                <div class="flex items-center justify-between gap-2">
                                    <button type="button" @click="moveCard(idx, -1)" :disabled="idx === 0" class="memodex-btn-secondary text-xs px-2.5 py-1 flex-1 disabled:opacity-30">&larr;</button>
                                    <button type="button" @click="moveCard(idx, 1)" :disabled="idx === cardsOrder.length - 1" class="memodex-btn-secondary text-xs px-2.5 py-1 flex-1 disabled:opacity-30">&rarr;</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Standard Binder Gallery -->
            <div x-show="!reorderMode" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-5">
                @foreach ($album->cards as $card)
                    <div class="space-y-2">
                        <x-card-tile :card="$card" />
                        <form method="POST" action="{{ route('albums.cards.remove', [$album, $card]) }}" class="flex justify-end">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[11px] text-[#8B99BD] hover:text-[#EB6F75] transition-colors">
                                Remove from binder
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Danger Footer -->
        <div class="pt-6 border-t border-[#D4DCF0]/[0.08] flex flex-wrap items-center justify-between gap-4">
            <span class="text-xs text-[#8B99BD]">Deleting a binder never removes cards from your Pokédex.</span>
            <button type="button" @click="showDeleteModal = true" class="text-xs font-medium text-[#EB6F75] hover:underline">
                Delete Binder
            </button>
        </div>

        <!-- Add Cards Modal -->
        <div x-show="showAddCardModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-[#0B1021]/80 backdrop-blur-sm" @click="showAddCardModal = false"></div>
            <div class="relative z-10 bg-[#223056] border border-[#D4DCF0]/15 rounded-2xl p-6 max-w-2xl w-full space-y-5 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-[#D4DCF0]/10">
                    <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Add Cards from Pokédex</h2>
                    <button type="button" @click="showAddCardModal = false" class="text-[#BCC6E0] hover:text-[#F6F7FB] text-xl leading-none">&times;</button>
                </div>

                @if ($availableCards->count() === 0)
                    <p class="text-sm text-[#BCC6E0] py-6 text-center">All your archived cards are already placed in this binder, or your Pokédex is empty.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-h-[60vh] overflow-y-auto p-2 bg-[#161F3B] rounded-xl border border-[#D4DCF0]/10">
                        @foreach ($availableCards as $c)
                            <form method="POST" action="{{ route('albums.cards.add', $album) }}">
                                @csrf
                                <input type="hidden" name="card_id" value="{{ $c->id }}">
                                <button type="submit" class="w-full text-left p-2 rounded-xl bg-[#26355D] hover:bg-[#304273] border border-[#D4DCF0]/10 hover:border-[#AF47D2]/50 transition-all group">
                                    <div class="w-full aspect-card card-stage rounded-lg overflow-hidden p-1 mb-2">
                                        @if ($c->effectiveFramedPath())
                                            <img src="{{ route('media.card.framed', $c) }}" alt="{{ $c->display_name }}" class="w-full h-full object-contain" loading="lazy">
                                        @elseif ($c->photo)
                                            <img src="{{ route('media.photo.thumbnail', $c->photo) }}" alt="{{ $c->display_name }}" class="w-full h-full object-contain" loading="lazy">
                                        @endif
                                    </div>
                                    <p class="text-xs font-medium text-[#F6F7FB] truncate group-hover:text-[#FFA22B]">{{ $c->display_name }}</p>
                                    <p class="text-[11px] font-mono text-[#8B99BD] mt-0.5">+ Add</p>
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-end">
                    <button type="button" @click="showAddCardModal = false" class="memodex-btn-secondary text-xs">Close</button>
                </div>
            </div>
        </div>

        <!-- Edit Binder Modal -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-[#0B1021]/80 backdrop-blur-sm" @click="showEditModal = false"></div>
            <div class="relative z-10 bg-[#223056] border border-[#D4DCF0]/15 rounded-2xl p-6 max-w-md w-full space-y-5 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-[#D4DCF0]/10">
                    <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Edit Binder Details</h2>
                    <button type="button" @click="showEditModal = false" class="text-[#BCC6E0] hover:text-[#F6F7FB] text-xl leading-none">&times;</button>
                </div>

                <form method="POST" action="{{ route('albums.update', $album) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="edit_name" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Binder Title</label>
                        <input type="text" id="edit_name" name="name" value="{{ $album->name }}" required maxlength="80" class="memodex-input">
                    </div>

                    <div>
                        <label for="edit_desc" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Description</label>
                        <textarea id="edit_desc" name="description" rows="3" maxlength="300" class="memodex-input">{{ $album->description }}</textarea>
                    </div>

                    <div>
                        <label for="edit_vis" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Visibility</label>
                        <select id="edit_vis" name="visibility" class="memodex-select">
                            <option value="private" {{ $album->visibility === 'private' ? 'selected' : '' }}>Private</option>
                            <option value="public-link" {{ $album->visibility === 'public-link' ? 'selected' : '' }}>Public Link</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="showEditModal = false" class="memodex-btn-ghost text-xs">Cancel</button>
                        <button type="submit" class="memodex-btn-primary text-xs px-5 py-2.5">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Controlled Share Modal -->
        <div x-show="showShareModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-[#0B1021]/80 backdrop-blur-sm" @click="showShareModal = false"></div>
            <div class="relative z-10 bg-[#223056] border border-[#D4DCF0]/15 rounded-2xl p-6 max-w-md w-full space-y-5 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-[#D4DCF0]/10">
                    <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Share Binder</h2>
                    <button type="button" @click="showShareModal = false" class="text-[#BCC6E0] hover:text-[#F6F7FB] text-xl leading-none">&times;</button>
                </div>

                <p class="text-sm text-[#BCC6E0] leading-relaxed">
                    Creates a read-only link for <strong class="text-[#F6F7FB]">{{ $album->name }}</strong> only. Private files and other collections are never exposed.
                </p>

                @if ($album->activeSharedLink)
                    @php $shareUrl = route('shared.show', $album->activeSharedLink->token); @endphp
                    <div class="p-4 rounded-xl bg-[#161F3B] border border-[#D4DCF0]/15 space-y-3">
                        <span class="text-xs font-medium text-[#5CC99B] block">Active Share Link</span>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $shareUrl }}" class="memodex-input py-2 font-mono text-xs select-all">
                            <button type="button" @click="copyShareLink('{{ $shareUrl }}')" class="memodex-btn-primary text-xs px-3.5 py-2 whitespace-nowrap">
                                <span x-text="copied ? 'Copied' : 'Copy'"></span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between text-xs text-[#8B99BD] pt-1">
                            <span>
                                @if ($album->activeSharedLink->expires_at)
                                    Expires {{ $album->activeSharedLink->expires_at->format('M d, Y') }}
                                @else
                                    No expiration
                                @endif
                            </span>
                            <form method="POST" action="{{ route('albums.share.revoke', [$album, $album->activeSharedLink]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#EB6F75] hover:underline font-medium">Revoke Link</button>
                            </form>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('albums.share', $album) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="expires_at" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Expiration Date (Optional)</label>
                            <input type="date" id="expires_at" name="expires_at" min="{{ now()->addDay()->format('Y-m-d') }}" class="memodex-input">
                        </div>
                        <button type="submit" class="memodex-btn-primary w-full py-2.5 text-xs">
                            Generate Public Share Link
                        </button>
                    </form>
                @endif

                <div class="flex justify-end">
                    <button type="button" @click="showShareModal = false" class="memodex-btn-ghost text-xs">Done</button>
                </div>
            </div>
        </div>

        <!-- Delete Binder Confirmation Modal -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-[#0B1021]/80 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div class="relative z-10 bg-[#223056] border border-[#D4DCF0]/15 rounded-2xl p-6 max-w-md w-full space-y-4 shadow-2xl">
                <h3 class="font-display text-lg font-semibold text-[#F6F7FB]">Delete "{{ $album->name }}"?</h3>
                <p class="text-sm text-[#BCC6E0] leading-relaxed">
                    This deletes the binder and revokes any shared links. All cards inside remain safe in your Pokédex.
                </p>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="showDeleteModal = false" class="memodex-btn-secondary text-xs">Cancel</button>
                    <form method="POST" action="{{ route('albums.destroy', $album) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 rounded-lg bg-[#EB6F75] hover:bg-[#dc5b62] text-[#161F3B] text-xs font-semibold transition-colors">
                            Delete Binder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
