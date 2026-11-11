<x-app-layout>
    <x-slot name="title">Admin Control Panel — MEMODEX</x-slot>

    <div class="space-y-6">
        <!-- Admin Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-accent-warm">System Administration</span>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Admin Control Center</h1>
                <p class="text-xs text-memodex-muted mt-0.5">
                    Platform metrics, frame configurations, and storage allocation oversight.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('admin.frames.index') }}" class="px-3.5 py-1.5 rounded-lg border border-memodex-border bg-memodex-raised hover:bg-memodex-soft text-xs font-medium text-memodex-text transition-colors">
                    Manage Frames ({{ $activeFrames }}/{{ $totalFrames }})
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-3.5 py-1.5 rounded-lg border border-memodex-border bg-memodex-raised hover:bg-memodex-soft text-xs font-medium text-memodex-text transition-colors">
                    Manage Users ({{ $totalUsers }})
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-xl border border-memodex-border bg-memodex-panel space-y-2">
                <span class="text-[10px] font-mono uppercase text-memodex-subtle">Total Collectors</span>
                <p class="text-2xl font-bold text-memodex-text">{{ number_format($totalUsers) }}</p>
                <span class="text-[11px] text-memodex-muted block">Registered accounts</span>
            </div>

            <div class="p-5 rounded-xl border border-memodex-border bg-memodex-panel space-y-2">
                <span class="text-[10px] font-mono uppercase text-memodex-subtle">Archived Cards</span>
                <p class="text-2xl font-bold text-memodex-primary">{{ number_format($totalCards) }}</p>
                <span class="text-[11px] text-memodex-muted block">Framed collections</span>
            </div>

            <div class="p-5 rounded-xl border border-memodex-border bg-memodex-panel space-y-2">
                <span class="text-[10px] font-mono uppercase text-memodex-subtle">Total Albums</span>
                <p class="text-2xl font-bold text-memodex-accent-warm">{{ number_format($totalAlbums) }}</p>
                <span class="text-[11px] text-memodex-muted block">Collector binders</span>
            </div>

            <div class="p-5 rounded-xl border border-memodex-border bg-memodex-panel space-y-2">
                <span class="text-[10px] font-mono uppercase text-memodex-subtle">Private Storage</span>
                <p class="text-2xl font-bold text-memodex-text">{{ number_format($totalStorageBytes / (1024 * 1024), 2) }} MB</p>
                <span class="text-[11px] text-memodex-muted block">Originals + Framed PNGs</span>
            </div>
        </div>

        <!-- Two Columns: Active Frames & Recent Users -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Active Outer Frames -->
            <div class="lg:col-span-7 p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-memodex-text">Outer Frame Collection ({{ $frames->count() }})</h2>
                        <p class="text-[11px] text-memodex-muted">Archival frames available to collectors.</p>
                    </div>
                    <a href="{{ route('admin.frames.index') }}" class="text-xs text-memodex-primary hover:underline">
                        Configure All &rarr;
                    </a>
                </div>

                <div class="divide-y divide-memodex-border/60">
                    @foreach ($frames as $fr)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full border shadow-sm flex-shrink-0" style="background-color: {{ $fr->config['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fr->config['accent_color'] ?? '#83B9F2' }};"></div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs font-semibold text-memodex-text">{{ $fr->name }}</p>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-medium {{ $fr->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                            {{ $fr->is_active ? 'ACTIVE' : 'DISABLED' }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-memodex-muted">{{ $fr->description }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-right">
                                <span class="text-xs font-mono text-memodex-muted">{{ $fr->cards_count }} cards</span>
                                <a href="{{ route('admin.frames.edit', $fr) }}" class="px-2.5 py-1 rounded bg-memodex-raised hover:bg-memodex-soft border border-memodex-border text-xs text-memodex-text">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Collectors -->
            <div class="lg:col-span-5 p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-memodex-text">Recent Collectors</h2>
                        <p class="text-[11px] text-memodex-muted">Newly registered members.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-memodex-primary hover:underline">
                        View All &rarr;
                    </a>
                </div>

                <div class="divide-y divide-memodex-border/60">
                    @foreach ($recentUsers as $u)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-memodex-text">{{ $u->name }}</p>
                                <p class="text-[10px] text-memodex-muted">{{ $u->email }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono {{ $u->is_admin ? 'bg-amber-400/10 text-amber-300 border border-amber-400/30 font-bold' : 'bg-memodex-raised text-memodex-muted' }}">
                                    {{ $u->is_admin ? 'ADMIN' : 'COLLECTOR' }}
                                </span>
                                <p class="text-[9px] font-mono text-memodex-subtle mt-0.5">{{ $u->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Archived Cards Table -->
        <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
            <h2 class="text-sm font-bold text-memodex-text">Recently Archived Pokémon Cards</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-memodex-raised text-[10px] font-mono uppercase text-memodex-subtle border-b border-memodex-border">
                        <tr>
                            <th class="py-2.5 px-3">Card</th>
                            <th class="py-2.5 px-3">Card Number</th>
                            <th class="py-2.5 px-3">Collector</th>
                            <th class="py-2.5 px-3">Outer Frame</th>
                            <th class="py-2.5 px-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-memodex-border/60">
                        @foreach ($recentCards as $rc)
                            <tr class="hover:bg-memodex-soft/30 transition-colors">
                                <td class="py-2.5 px-3 font-semibold text-memodex-text">
                                    {{ $rc->display_name }}
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-memodex-primary">
                                    {{ $rc->card_number }}
                                </td>
                                <td class="py-2.5 px-3 text-memodex-muted">
                                    {{ $rc->user?->name ?? '—' }}
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono border"
                                          style="background-color: {{ $rc->frame->config['outer_bg'] ?? '#141D2B' }}; color: {{ $rc->frame->config['accent_color'] ?? '#83B9F2' }}; border-color: {{ $rc->frame->config['border_primary'] ?? '#3D4F66' }};">
                                        {{ $rc->frame->name ?? 'Classic' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-memodex-subtle font-mono text-[11px]">
                                    {{ $rc->created_at->format('M d, Y • H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
