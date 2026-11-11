<x-app-layout>
    <x-slot name="title">Manage Frames — Admin MEMODEX</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-memodex-border">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-mono text-memodex-subtle hover:text-memodex-primary">&larr; Back to Admin Center</a>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Outer Frame Management</h1>
                <p class="text-xs text-memodex-muted mt-0.5">
                    Configure active states, border widths, and aesthetic palettes for each of the 7 archival frames.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($frames as $fr)
                <div class="p-5 rounded-xl border border-memodex-border bg-memodex-panel flex flex-col justify-between space-y-4">
                    <div>
                        <!-- Header with Color Swatches -->
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-memodex-text">{{ $fr->name }}</h3>
                                <p class="text-[10px] font-mono text-memodex-subtle">{{ $fr->slug }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[9px] font-mono font-medium {{ $fr->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                {{ $fr->is_active ? 'ACTIVE' : 'DISABLED' }}
                            </span>
                        </div>

                        <p class="text-xs text-memodex-muted mt-2 leading-relaxed">
                            {{ $fr->description }}
                        </p>

                        <!-- Color Config Overview -->
                        <div class="mt-4 p-3 rounded-lg bg-memodex-bg border border-memodex-border/60 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] text-memodex-subtle">Primary Border</span>
                                <div class="flex items-center gap-1.5 font-mono text-[11px] text-memodex-text">
                                    <div class="w-3.5 h-3.5 rounded border" style="background-color: {{ $fr->config['border_primary'] ?? '#3D4F66' }};"></div>
                                    <span>{{ $fr->config['border_primary'] ?? '#3D4F66' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] text-memodex-subtle">Inner Border</span>
                                <div class="flex items-center gap-1.5 font-mono text-[11px] text-memodex-text">
                                    <div class="w-3.5 h-3.5 rounded border" style="background-color: {{ $fr->config['border_inner'] ?? '#202E40' }};"></div>
                                    <span>{{ $fr->config['border_inner'] ?? '#202E40' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] text-memodex-subtle">Accent Color</span>
                                <div class="flex items-center gap-1.5 font-mono text-[11px] text-memodex-text">
                                    <div class="w-3.5 h-3.5 rounded border" style="background-color: {{ $fr->config['accent_color'] ?? '#83B9F2' }};"></div>
                                    <span>{{ $fr->config['accent_color'] ?? '#83B9F2' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-1 border-t border-memodex-border/40 text-[10px] font-mono text-memodex-subtle">
                                <span>Border Width: {{ $fr->config['border_width'] ?? 32 }}px</span>
                                <span>Cards: {{ $fr->cards_count }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 flex items-center justify-between border-t border-memodex-border/60">
                        <form method="POST" action="{{ route('admin.frames.toggle', $fr) }}">
                            @csrf
                            <button type="submit" class="text-xs hover:underline {{ $fr->is_active ? 'text-rose-400' : 'text-emerald-400' }}">
                                {{ $fr->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.frames.edit', $fr) }}" class="px-3 py-1.5 rounded bg-memodex-raised hover:bg-memodex-soft border border-memodex-border text-xs font-medium text-memodex-text transition-colors">
                            Edit Config &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
