<x-app-layout>
    <x-slot name="title">Binders &amp; Sets — MEMODEX</x-slot>

    <div class="space-y-6" x-data="{ showCreateModal: false }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">Themed Portfolios</span>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Binders &amp; Albums</h1>
                <p class="text-xs text-memodex-muted mt-1">
                    Group your trading cards into thematic collections and controlled shareable binders.
                </p>
            </div>
            <button type="button" @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors self-start sm:self-auto">
                + New Binder
            </button>
        </div>

        <!-- Albums Grid -->
        @if ($albums->count() === 0)
            <div class="p-12 text-center rounded-xl border border-memodex-border bg-memodex-panel space-y-4 max-w-lg mx-auto">
                <div class="w-12 h-12 mx-auto rounded-full bg-memodex-raised border border-memodex-border flex items-center justify-center text-memodex-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-memodex-text">No binders created yet</h3>
                    <p class="text-xs text-memodex-muted mt-1">Create thematic binders to organize cards from special travels, milestones, or family archives.</p>
                </div>
                <button type="button" @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 rounded bg-memodex-primary text-memodex-bg text-xs font-semibold">
                    Create Your First Binder
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($albums as $album)
                    <div class="rounded-xl border border-memodex-border bg-memodex-panel hover:border-memodex-primary/50 transition-colors flex flex-col justify-between overflow-hidden group">
                        <!-- Cover / Sample Miniatures Preview -->
                        <a href="{{ route('albums.show', $album) }}" class="block p-4 bg-memodex-bg/60 border-b border-memodex-border">
                            <div class="grid grid-cols-4 gap-2 h-24">
                                @forelse ($album->cards->take(4) as $c)
                                    <div class="aspect-card rounded border border-memodex-border overflow-hidden bg-memodex-panel">
                                        @if ($c->render_path)
                                            <img src="{{ route('media.card.render', $c) }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                        @elseif ($c->photo)
                                            <img src="{{ route('media.photo.thumbnail', $c->photo) }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                @empty
                                    <div class="col-span-4 flex items-center justify-center text-[11px] font-mono text-memodex-subtle">
                                        Empty Binder
                                    </div>
                                @endforelse
                            </div>
                        </a>

                        <!-- Binder Details -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-start justify-between gap-2">
                                    <a href="{{ route('albums.show', $album) }}" class="font-bold text-base text-memodex-text group-hover:text-memodex-primary transition-colors">
                                        {{ $album->name }}
                                    </a>
                                    <span class="text-[9px] font-mono px-2 py-0.5 rounded bg-memodex-raised border border-memodex-border text-memodex-subtle uppercase flex-shrink-0">
                                        {{ $album->visibility }}
                                    </span>
                                </div>
                                <p class="text-xs text-memodex-muted mt-2 line-clamp-2 leading-relaxed">
                                    {{ $album->description ?: 'No binder description provided.' }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-memodex-border flex items-center justify-between text-xs text-memodex-subtle font-mono">
                                <span>{{ $album->cards_count }} cards</span>
                                <a href="{{ route('albums.show', $album) }}" class="text-memodex-primary hover:underline font-sans font-medium">
                                    Open Binder &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $albums->links() }}
            </div>
        @endif

        <!-- Create Binder Modal -->
        <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
            <div @click.away="showCreateModal = false" class="bg-memodex-panel border border-memodex-border rounded-xl p-6 max-w-md w-full space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-memodex-border">
                    <h3 class="text-base font-bold text-memodex-text">New Collector Binder</h3>
                    <button type="button" @click="showCreateModal = false" class="text-memodex-subtle hover:text-memodex-text text-xl font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('albums.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="modal_name" class="block text-xs text-memodex-muted mb-1 font-medium">Binder Title</label>
                        <input type="text" id="modal_name" name="name" required maxlength="80" placeholder="e.g. Kyoto Summer 2024" class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                    </div>

                    <div>
                        <label for="modal_desc" class="block text-xs text-memodex-muted mb-1 font-medium">Description (Optional)</label>
                        <textarea id="modal_desc" name="description" rows="3" maxlength="300" placeholder="Brief summary of what this collection represents..." class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary"></textarea>
                    </div>

                    <div>
                        <label for="modal_vis" class="block text-xs text-memodex-muted mb-1 font-medium">Initial Visibility</label>
                        <select id="modal_vis" name="visibility" class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                            <option value="private">Private (Only you can view)</option>
                            <option value="public-link">Public Link (Accessible via unguessable share link)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs text-memodex-muted hover:text-memodex-text">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-memodex-primary text-memodex-bg text-xs font-semibold rounded hover:bg-memodex-primary-strong">
                            Create Binder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
