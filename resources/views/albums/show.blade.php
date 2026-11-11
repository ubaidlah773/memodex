<x-app-layout>
    <x-slot name="title">{{ $album->name }} — MEMODEX Binder</x-slot>

    <div class="space-y-8" x-data="{ showShareModal: false, showAddCardModal: false }">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <a href="{{ route('albums.index') }}" class="text-xs font-mono text-memodex-subtle hover:text-memodex-primary">&larr; Back to Binders</a>
                <div class="flex items-center gap-2 mt-1">
                    <h1 class="text-2xl font-bold tracking-tight text-memodex-text">{{ $album->name }}</h1>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-memodex-raised border border-memodex-border text-memodex-subtle uppercase">
                        {{ $album->visibility }}
                    </span>
                </div>
                <p class="text-xs text-memodex-muted mt-1 max-w-xl">
                    {{ $album->description ?: 'No binder description added yet.' }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2.5">
                <button type="button" @click="showShareModal = true" class="inline-flex items-center px-3.5 py-2 rounded-md text-xs font-medium border border-memodex-border bg-memodex-raised hover:bg-memodex-soft text-memodex-text transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    Share Binder
                </button>
                <button type="button" @click="showAddCardModal = true" class="inline-flex items-center px-3.5 py-2 rounded-md text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors">
                    + Add Card
                </button>
            </div>
        </div>

        <!-- Cards in this Binder -->
        @if ($album->cards->count() === 0)
            <div class="p-12 text-center rounded-xl border border-memodex-border bg-memodex-panel space-y-4 max-w-lg mx-auto">
                <div class="w-12 h-12 mx-auto rounded-full bg-memodex-raised border border-memodex-border flex items-center justify-center text-memodex-subtle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-memodex-text">This binder has no cards</h3>
                    <p class="text-xs text-memodex-muted mt-1">Select cards from your collection or generate new ones to place in this binder.</p>
                </div>
                <button type="button" @click="showAddCardModal = true" class="inline-flex items-center px-4 py-2 rounded bg-memodex-primary text-memodex-bg text-xs font-semibold">
                    Add Cards to Binder
                </button>
            </div>
        @else
            <div class="space-y-3">
                <div class="flex items-center justify-between text-xs font-mono text-memodex-subtle">
                    <span>{{ $album->cards->count() }} Cards in Binder</span>
                    <span>Ordered layout</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($album->cards as $card)
                        <div class="relative group">
                            <x-card-tile :card="$card" />
                            <!-- Quick Detach Button -->
                            <form method="POST" action="{{ route('albums.cards.remove', [$album, $card]) }}" onsubmit="return confirm('Remove card from this binder? (The card remains in your Pokédex)');" class="absolute -top-1 -left-1 z-30">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-5 h-5 rounded-full bg-memodex-bg border border-memodex-border text-memodex-subtle hover:text-memodex-danger hover:border-memodex-danger flex items-center justify-center text-xs font-bold transition-colors" title="Remove from binder">
                                    &times;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Bottom Danger Zone -->
        <div class="pt-8 border-t border-memodex-border flex items-center justify-between">
            <span class="text-xs text-memodex-subtle">Deleting a binder will not delete any cards.</span>
            <form method="POST" action="{{ route('albums.destroy', $album) }}" onsubmit="return confirm('Are you sure you want to delete binder &quot;{{ $album->name }}&quot;?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-memodex-danger hover:underline">
                    Delete Binder
                </button>
            </form>
        </div>

        <!-- Add Card Modal -->
        <div x-show="showAddCardModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
            <div @click.away="showAddCardModal = false" class="bg-memodex-panel border border-memodex-border rounded-xl p-6 max-w-xl w-full space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-memodex-border">
                    <h3 class="text-base font-bold text-memodex-text">Add Cards from Pokédex</h3>
                    <button type="button" @click="showAddCardModal = false" class="text-memodex-subtle hover:text-memodex-text text-xl font-bold">&times;</button>
                </div>

                @if ($availableCards->count() === 0)
                    <p class="text-xs text-memodex-muted py-4">All your existing cards are already in this binder, or you have not created any cards yet.</p>
                @else
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 max-h-72 overflow-y-auto p-2 bg-memodex-bg rounded border border-memodex-border">
                        @foreach ($availableCards as $c)
                            <form method="POST" action="{{ route('albums.cards.add', $album) }}" class="flex flex-col items-center">
                                @csrf
                                <input type="hidden" name="card_id" value="{{ $c->id }}">
                                <button type="submit" class="w-full text-left p-1.5 rounded bg-memodex-panel hover:bg-memodex-raised border border-memodex-border transition-colors group">
                                    <div class="w-full aspect-card rounded bg-memodex-bg overflow-hidden relative mb-1">
                                        @if ($c->render_path)
                                            <img src="{{ route('media.card.render', $c) }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                        @elseif ($c->photo)
                                            <img src="{{ route('media.photo.thumbnail', $c->photo) }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <p class="text-[11px] font-semibold text-memodex-text truncate group-hover:text-memodex-primary">{{ $c->name }}</p>
                                    <span class="text-[9px] font-mono text-memodex-subtle">+ Add</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-end pt-2">
                    <button type="button" @click="showAddCardModal = false" class="px-4 py-2 text-xs text-memodex-muted hover:text-memodex-text">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Share Controlled Link Modal -->
        <div x-show="showShareModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
            <div @click.away="showShareModal = false" class="bg-memodex-panel border border-memodex-border rounded-xl p-6 max-w-md w-full space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-memodex-border">
                    <h3 class="text-base font-bold text-memodex-text">Controlled Public Sharing</h3>
                    <button type="button" @click="showShareModal = false" class="text-memodex-subtle hover:text-memodex-text text-xl font-bold">&times;</button>
                </div>

                <p class="text-xs text-memodex-muted leading-relaxed">
                    Sharing creates an unguessable token link to view <strong>only this binder</strong> in read-only mode. It never exposes your personal account, private files, or remaining Pokédex.
                </p>

                @if ($album->activeSharedLink)
                    <div class="p-4 rounded-lg bg-memodex-bg border border-memodex-border space-y-3">
                        <span class="text-[10px] font-mono text-memodex-success uppercase font-semibold">Active Share Link</span>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ route('shared.show', $album->activeSharedLink->token) }}" id="shareUrlInput" class="w-full bg-memodex-panel border border-memodex-border rounded px-2.5 py-1.5 text-xs text-memodex-text font-mono select-all">
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('shareUrlInput').value); alert('Link copied to clipboard!');" class="px-3 py-1.5 bg-memodex-primary text-memodex-bg rounded text-xs font-semibold whitespace-nowrap">
                                Copy
                            </button>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-memodex-subtle font-mono pt-1">
                            <span>Status: Active</span>
                            <form method="POST" action="{{ route('albums.share.revoke', [$album, $album->activeSharedLink]) }}" onsubmit="return confirm('Revoke this share link immediately?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-memodex-danger hover:underline">
                                    Revoke Access
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('albums.share', $album) }}" class="space-y-4">
                        @csrf
                        <div class="p-4 rounded-lg bg-memodex-bg border border-memodex-border">
                            <p class="text-xs text-memodex-muted">No active share link exists for this binder.</p>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg text-xs font-semibold rounded transition-colors">
                            Generate New Share Link
                        </button>
                    </form>
                @endif

                <div class="flex justify-end pt-2">
                    <button type="button" @click="showShareModal = false" class="px-4 py-2 text-xs text-memodex-muted hover:text-memodex-text">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
