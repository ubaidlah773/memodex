<x-app-layout>
    <x-slot name="title">Outer Frame Management — Admin MEMODEX</x-slot>

    <div class="space-y-8">
        <section class="pb-5 border-b border-[#C8D2DF]/[0.08]">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-[#9DAABD] hover:text-[#F3F5F8] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Admin Center</span>
            </a>
            <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8] mt-2">Outer Frame Management</h1>
            <p class="text-sm text-[#9DAABD] mt-1">Configure border widths, color tokens, and availability for outer card frames.</p>
        </section>

        @if ($frames->isEmpty())
            <x-empty-state title="No outer frames configured" description="Run the CardFrameSeeder to populate the 7 archival frame styles." icon="card" />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($frames as $fr)
                    @php $fc = $fr->config ?? []; @endphp
                    <article class="p-6 rounded-2xl border border-[#C8D2DF]/10 bg-[#17243A] flex flex-col justify-between gap-5">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-9 h-12 rounded-md border-2 p-1 flex-shrink-0"
                                         style="background-color: {{ $fc['outer_bg'] ?? '#0F1622' }}; border-color: {{ $fc['border_primary'] ?? '#3D4F66' }};">
                                        <div class="w-full h-full rounded-[2px] border" style="border-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></div>
                                    </div>
                                    <div>
                                        <h2 class="font-display text-base font-semibold text-[#F3F5F8]">{{ $fr->name }}</h2>
                                        <p class="text-xs font-mono text-[#6D7A8D]">{{ $fr->slug }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium {{ $fr->is_active ? 'text-[#5CC99B]' : 'text-[#EB6F75]' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $fr->is_active ? 'bg-[#5CC99B]' : 'bg-[#EB6F75]' }}"></span>
                                    {{ $fr->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </div>

                            <p class="text-sm text-[#9DAABD] leading-relaxed">{{ $fr->description }}</p>

                            <dl class="pt-3 border-t border-[#C8D2DF]/[0.08] grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <dt class="text-[#6D7A8D]">Primary Border</dt>
                                    <dd class="font-mono text-[#F3F5F8] flex items-center gap-1.5 mt-1">
                                        <span class="w-3 h-3 rounded border border-[#C8D2DF]/20" style="background-color: {{ $fc['border_primary'] ?? '#3D4F66' }};"></span>
                                        {{ $fc['border_primary'] ?? '#3D4F66' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[#6D7A8D]">Accent Token</dt>
                                    <dd class="font-mono text-[#F3F5F8] flex items-center gap-1.5 mt-1">
                                        <span class="w-3 h-3 rounded border border-[#C8D2DF]/20" style="background-color: {{ $fc['accent_color'] ?? '#69AFFF' }};"></span>
                                        {{ $fc['accent_color'] ?? '#69AFFF' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[#6D7A8D]">Border Width</dt>
                                    <dd class="font-mono text-[#9DAABD] mt-1">{{ $fc['border_width'] ?? 32 }} px</dd>
                                </div>
                                <div>
                                    <dt class="text-[#6D7A8D]">Usage</dt>
                                    <dd class="font-mono text-[#9DAABD] mt-1">{{ $fr->cards_count }} cards</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="pt-4 border-t border-[#C8D2DF]/[0.08] flex items-center justify-between">
                            <form method="POST" action="{{ route('admin.frames.toggle', $fr) }}">
                                @csrf
                                <button type="submit" class="text-xs font-medium hover:underline {{ $fr->is_active ? 'text-[#EB6F75]' : 'text-[#5CC99B]' }}">
                                    {{ $fr->is_active ? 'Disable Frame' : 'Enable Frame' }}
                                </button>
                            </form>

                            <a href="{{ route('admin.frames.edit', $fr) }}" class="memodex-btn-secondary text-xs px-3.5 py-2">
                                Configure &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
