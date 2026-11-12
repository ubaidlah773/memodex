<x-app-layout>
    <x-slot name="title">Admin Center — MEMODEX</x-slot>

    <div class="space-y-10">
        <!-- Header -->
        <section class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-5 border-b border-[#C8D2DF]/[0.08]">
            <div>
                <p class="text-xs font-medium tracking-wider uppercase text-[#D4B36A]">System Administration</p>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8] mt-1">Admin Control Center</h1>
                <p class="text-sm text-[#9DAABD] mt-1">Platform telemetry, outer frame mounts, and collector storage management.</p>
            </div>
            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <a href="{{ route('admin.frames.index') }}" class="memodex-btn-secondary text-xs">
                    Frames ({{ $activeFrames }}/{{ $totalFrames }})
                </a>
                <a href="{{ route('admin.users.index') }}" class="memodex-btn-secondary text-xs">
                    Collectors ({{ $totalUsers }})
                </a>
            </div>
        </section>

        <!-- Clean Inline Telemetry Ledger (replaces 4 SaaS cards) -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-6 py-5 px-6 rounded-2xl bg-[#17243A] border border-[#C8D2DF]/10">
            <div>
                <span class="text-xs text-[#6D7A8D] block">Collectors</span>
                <span class="font-display text-2xl font-semibold text-[#F3F5F8] tabular-nums mt-1 block">{{ number_format($totalUsers) }}</span>
            </div>
            <div>
                <span class="text-xs text-[#6D7A8D] block">Archived Cards</span>
                <span class="font-display text-2xl font-semibold text-[#69AFFF] tabular-nums mt-1 block">{{ number_format($totalCards) }}</span>
            </div>
            <div>
                <span class="text-xs text-[#6D7A8D] block">Active Binders</span>
                <span class="font-display text-2xl font-semibold text-[#D4B36A] tabular-nums mt-1 block">{{ number_format($totalAlbums) }}</span>
            </div>
            <div>
                <span class="text-xs text-[#6D7A8D] block">Vault Storage</span>
                <span class="font-display text-2xl font-semibold text-[#F3F5F8] tabular-nums mt-1 block">{{ number_format($totalStorageBytes / (1024 * 1024), 2) }} MB</span>
            </div>
        </section>

        <!-- Two Columns: Frames & Recent Collectors -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Outer Frame Registry ({{ $frames->count() }})</h2>
                    <a href="{{ route('admin.frames.index') }}" class="text-xs font-medium text-[#69AFFF] hover:underline">Manage All &rarr;</a>
                </div>

                <div class="rounded-2xl bg-[#17243A]/70 border border-[#C8D2DF]/10 divide-y divide-[#C8D2DF]/[0.07]">
                    @forelse ($frames as $fr)
                        @php $fc = $fr->config ?? []; @endphp
                        <div class="p-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-7 h-9 rounded border-2 flex-shrink-0 p-1"
                                     style="background-color: {{ $fc['outer_bg'] ?? '#0F1622' }}; border-color: {{ $fc['border_primary'] ?? '#3D4F66' }};">
                                    <div class="w-full h-full rounded-[2px] border" style="border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></div>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-[#F3F5F8] truncate">{{ $fr->name }}</p>
                                        <span class="w-1.5 h-1.5 rounded-full {{ $fr->is_active ? 'bg-[#5CC99B]' : 'bg-[#EB6F75]' }}" title="{{ $fr->is_active ? 'Active' : 'Disabled' }}"></span>
                                    </div>
                                    <p class="text-xs text-[#9DAABD] truncate">{{ $fr->description }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="text-xs font-mono text-[#6D7A8D] tabular-nums">{{ $fr->cards_count }} cards</span>
                                <a href="{{ route('admin.frames.edit', $fr) }}" class="memodex-btn-secondary text-xs px-3 py-1.5">Edit</a>
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-sm text-[#9DAABD]">No outer frames configured yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Recent Collectors</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-[#69AFFF] hover:underline">View All &rarr;</a>
                </div>

                <div class="rounded-2xl bg-[#17243A]/70 border border-[#C8D2DF]/10 divide-y divide-[#C8D2DF]/[0.07]">
                    @forelse ($recentUsers as $u)
                        <div class="p-4 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-[#F3F5F8] truncate">{{ $u->name }}</p>
                                <p class="text-xs text-[#6D7A8D] truncate">{{ $u->email }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-xs font-medium {{ $u->is_admin ? 'text-[#D4B36A]' : 'text-[#9DAABD]' }}">
                                    {{ $u->is_admin ? 'Admin' : 'Collector' }}
                                </span>
                                <p class="text-[11px] font-mono text-[#6D7A8D] mt-0.5">{{ $u->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-sm text-[#9DAABD]">No collectors registered.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Recently Archived Cards Table -->
        <section class="space-y-4 pt-4 border-t border-[#C8D2DF]/[0.08]">
            <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Recent Platform Activity</h2>
            <div class="rounded-2xl border border-[#C8D2DF]/10 bg-[#17243A]/70 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#1D293B]/80 text-xs font-medium text-[#9DAABD] border-b border-[#C8D2DF]/10">
                            <tr>
                                <th class="py-3 px-4">Card</th>
                                <th class="py-3 px-4">Serial</th>
                                <th class="py-3 px-4">Collector</th>
                                <th class="py-3 px-4">Frame</th>
                                <th class="py-3 px-4">Archived</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#C8D2DF]/[0.07]">
                            @forelse ($recentCards as $rc)
                                @php $fc = $rc->frame?->config ?? []; @endphp
                                <tr class="hover:bg-[#1D293B]/50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-[#F3F5F8]">{{ $rc->display_name }}</td>
                                    <td class="py-3 px-4 font-mono text-xs text-[#69AFFF] tabular-nums">{{ $rc->card_number }}</td>
                                    <td class="py-3 px-4 text-[#9DAABD]">{{ $rc->user?->name ?? '—' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-2 text-xs text-[#C8D2DF]">
                                            <span class="w-2 h-2 rounded-full border" style="background-color: {{ $fc['border_primary'] ?? '#3D4F66' }}; border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></span>
                                            {{ $rc->frame?->name ?? 'Classic Frame' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-[#6D7A8D] font-mono">{{ $rc->created_at->format('M d, Y · H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 px-4 text-center text-sm text-[#6D7A8D]">No cards archived yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
