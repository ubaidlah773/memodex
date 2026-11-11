<x-app-layout>
    <x-slot name="title">Manage Users — Admin MEMODEX</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-mono text-memodex-subtle hover:text-memodex-primary">&larr; Back to Admin Center</a>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">User &amp; Storage Management</h1>
                <p class="text-xs text-memodex-muted mt-0.5">
                    Monitor collector storage quotas, card counts, and administrator authorizations.
                </p>
            </div>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="p-4 rounded-xl bg-memodex-panel border border-memodex-border flex items-center gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, or username..."
                   class="flex-1 bg-memodex-bg border border-memodex-border rounded-lg px-3 py-1.5 text-xs text-memodex-text placeholder:text-memodex-subtle focus:border-memodex-primary">
            <button type="submit" class="px-4 py-1.5 bg-memodex-raised hover:bg-memodex-soft border border-memodex-border rounded-lg text-xs font-medium text-memodex-text">
                Search
            </button>
            @if ($search)
                <a href="{{ route('admin.users.index') }}" class="text-xs text-memodex-subtle hover:text-memodex-text">
                    Clear
                </a>
            @endif
        </form>

        <!-- Users Table -->
        <div class="rounded-xl border border-memodex-border bg-memodex-panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-memodex-raised text-[10px] font-mono uppercase text-memodex-subtle border-b border-memodex-border">
                        <tr>
                            <th class="py-3 px-4">Collector</th>
                            <th class="py-3 px-4">Username</th>
                            <th class="py-3 px-4">Cards</th>
                            <th class="py-3 px-4">Binders</th>
                            <th class="py-3 px-4">Storage Used</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-memodex-border/60">
                        @foreach ($users as $user)
                            <tr class="hover:bg-memodex-soft/30 transition-colors">
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-memodex-text">{{ $user->name }}</p>
                                    <p class="text-[11px] text-memodex-muted">{{ $user->email }}</p>
                                </td>
                                <td class="py-3 px-4 font-mono text-memodex-text">
                                    {{ $user->username ?? '—' }}
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-memodex-primary">
                                    {{ $user->cards_count }}
                                </td>
                                <td class="py-3 px-4 font-mono text-memodex-muted">
                                    {{ $user->albums_count }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="w-36 space-y-1">
                                        <div class="flex items-center justify-between text-[10px] font-mono text-memodex-subtle">
                                            <span>{{ number_format(($user->storage_used_bytes ?? 0) / (1024 * 1024), 1) }} MB</span>
                                            <span>{{ $user->storagePercent() }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 rounded-full bg-memodex-bg overflow-hidden border border-memodex-border/50">
                                            <div class="h-full bg-memodex-primary rounded-full" style="width: {{ $user->storagePercent() }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold {{ $user->is_admin ? 'bg-amber-400/10 text-amber-300 border border-amber-400/30' : 'bg-memodex-raised text-memodex-muted border border-memodex-border' }}">
                                        {{ $user->is_admin ? 'ADMIN' : 'COLLECTOR' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded border border-memodex-border text-[11px] hover:bg-memodex-soft text-memodex-text">
                                                {{ $user->is_admin ? 'Demote' : 'Make Admin' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-memodex-subtle italic">You</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
