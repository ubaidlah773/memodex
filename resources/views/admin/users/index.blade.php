<x-app-layout>
    <x-slot name="title">Collectors &amp; Quotas — Admin MEMODEX</x-slot>

    <div class="space-y-8">
        <section class="pb-5 border-b border-[#C8D2DF]/[0.08]">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-[#9DAABD] hover:text-[#F3F5F8] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Admin Center</span>
            </a>
            <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8] mt-2">Collector Accounts</h1>
            <p class="text-sm text-[#9DAABD] mt-1">Monitor collector quotas, archived card counts, and admin permissions.</p>
        </section>

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, or username..." class="memodex-input max-w-md">
            <button type="submit" class="memodex-btn-secondary text-xs px-4 py-2.5">Search</button>
            @if ($search)
                <a href="{{ route('admin.users.index') }}" class="memodex-btn-ghost text-xs">Clear</a>
            @endif
        </form>

        <div class="rounded-2xl border border-[#C8D2DF]/10 bg-[#17243A]/70 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#1D293B]/80 text-xs font-medium text-[#9DAABD] border-b border-[#C8D2DF]/10">
                        <tr>
                            <th class="py-3.5 px-4">Collector</th>
                            <th class="py-3.5 px-4">Handle</th>
                            <th class="py-3.5 px-4">Cards</th>
                            <th class="py-3.5 px-4">Binders</th>
                            <th class="py-3.5 px-4">Storage</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#C8D2DF]/[0.07]">
                        @foreach ($users as $user)
                            <tr class="hover:bg-[#1D293B]/50 transition-colors">
                                <td class="py-3.5 px-4">
                                    <p class="font-medium text-[#F3F5F8]">{{ $user->name }}</p>
                                    <p class="text-xs text-[#6D7A8D]">{{ $user->email }}</p>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-[#9DAABD]">{{ $user->username ? '@' . $user->username : '—' }}</td>
                                <td class="py-3.5 px-4 font-mono text-xs text-[#69AFFF] tabular-nums">{{ $user->cards_count }}</td>
                                <td class="py-3.5 px-4 font-mono text-xs text-[#9DAABD] tabular-nums">{{ $user->albums_count }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="w-36 space-y-1.5">
                                        <div class="flex items-center justify-between text-[11px] font-mono text-[#9DAABD]">
                                            <span>{{ number_format(($user->storage_used_bytes ?? 0) / (1024 * 1024), 1) }} MB</span>
                                            <span>{{ $user->storagePercent() }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 rounded-full bg-[#101827] overflow-hidden">
                                            <div class="h-full bg-[#69AFFF] rounded-full" style="width: {{ min(100, $user->storagePercent()) }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-xs font-medium {{ $user->is_admin ? 'text-[#D4B36A]' : 'text-[#9DAABD]' }}">
                                        {{ $user->is_admin ? 'Admin' : 'Collector' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="memodex-btn-secondary text-xs px-3 py-1.5">
                                                {{ $user->is_admin ? 'Demote' : 'Make Admin' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-[#6D7A8D]">You</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
        <div class="pt-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
