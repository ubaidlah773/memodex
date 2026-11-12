<x-app-layout>
    <x-slot name="title">Configure {{ $frame->name }} — Admin MEMODEX</x-slot>

    @php
        $cfg = $frame->config ?? [];
    @endphp

    <div x-data="{
        name: '{{ addslashes($frame->name) }}',
        description: '{{ addslashes($frame->description ?? '') }}',
        borderPrimary: '{{ $cfg['border_primary'] ?? '#3D4F66' }}',
        borderInner: '{{ $cfg['border_inner'] ?? '#202E40' }}',
        accentColor: '{{ $cfg['accent_color'] ?? '#69AFFF' }}',
        outerBg: '{{ $cfg['outer_bg'] ?? '#0F1622' }}',
        borderWidth: {{ (int) ($cfg['border_width'] ?? 32) }},
        innerPadding: {{ (int) ($cfg['inner_padding'] ?? 12) }},
        label: '{{ addslashes($cfg['label'] ?? 'ARCHIVE ITEM') }}'
    }" class="space-y-8">

        <section class="pb-5 border-b border-[#D4DCF0]/[0.08]">
            <a href="{{ route('admin.frames.index') }}" class="inline-flex items-center gap-2 text-sm text-[#BCC6E0] hover:text-[#F6F7FB] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Outer Frames</span>
            </a>
            <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F6F7FB] mt-2">Configure: {{ $frame->name }}</h1>
            <p class="text-sm text-[#BCC6E0] mt-1">Slug: <code class="font-mono text-[#FFA22B]">{{ $frame->slug }}</code> &middot; Adjusts outer border rendering only.</p>
        </section>

        <form method="POST" action="{{ route('admin.frames.update', $frame) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            @csrf
            @method('PUT')

            <!-- Left Preview -->
            <div class="lg:col-span-5 flex flex-col items-center lg:sticky lg:top-8 space-y-3">
                <div class="w-full max-w-[340px] flex items-center justify-between text-xs text-[#BCC6E0]">
                    <span>Live Frame Mockup</span>
                    <span class="font-mono" x-text="borderWidth + 'px rim'"></span>
                </div>

                <div class="w-full max-w-[340px] aspect-card rounded-2xl border-2 p-3 flex flex-col justify-between shadow-2xl select-none transition-colors"
                     :style="`border-color: ${borderPrimary}; background-color: ${outerBg};`">
                    <div class="w-full h-full rounded-xl border p-2.5 flex flex-col justify-between"
                         :style="`border-color: ${borderInner};`">
                        <div class="w-full flex-1 rounded-lg bg-[#11182E] flex items-center justify-center border"
                             :style="`border-color: ${borderPrimary};`">
                            <div class="text-center p-6 space-y-2">
                                <p class="font-display text-sm font-semibold text-[#F6F7FB]">Card Artwork Window</p>
                                <p class="text-xs text-[#8B99BD] font-mono">63:88 Unaltered Area</p>
                            </div>
                        </div>

                        <div class="mt-2.5 px-3 py-1.5 rounded bg-[#0A0F16] border flex items-center justify-between text-[9px] font-mono"
                             :style="`border-color: ${borderInner};`">
                            <span class="text-[#8B99BD]">MEMODEX</span>
                            <span class="font-semibold uppercase truncate mx-2" :style="`color: ${accentColor};`" x-text="label"></span>
                            <span class="text-[#F6F7FB]">MDX-000001</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="lg:col-span-7 space-y-7">
                <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-4">
                    <h2 class="font-display text-base font-semibold text-[#F6F7FB]">Frame Details</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Display Name</label>
                            <input type="text" id="name" name="name" x-model="name" required maxlength="50" class="memodex-input">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Description</label>
                            <textarea id="description" name="description" x-model="description" rows="2" maxlength="250" class="memodex-input"></textarea>
                        </div>

                        <div>
                            <label for="label" class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Plaque Label</label>
                            <input type="text" id="label" name="label" x-model="label" required maxlength="40" class="memodex-input">
                        </div>

                        <div class="flex items-center pt-5">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $frame->is_active ? 'checked' : '' }}
                                       class="rounded bg-[#161F3B] border-[#3B4E82] text-[#FFA22B] focus:ring-[#AF47D2]">
                                <span class="text-sm text-[#F6F7FB]">Active for Collectors</span>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="pb-6 border-b border-[#D4DCF0]/[0.08] space-y-4">
                    <h2 class="font-display text-base font-semibold text-[#F6F7FB]">Color Tokens</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Primary Border Hex</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="borderPrimary" class="w-10 h-10 rounded-lg border border-[#3B4E82] bg-transparent cursor-pointer">
                                <input type="text" name="border_primary" x-model="borderPrimary" required pattern="^#[0-9A-Fa-f]{6}$" class="memodex-input font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Inner Border Hex</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="borderInner" class="w-10 h-10 rounded-lg border border-[#3B4E82] bg-transparent cursor-pointer">
                                <input type="text" name="border_inner" x-model="borderInner" required pattern="^#[0-9A-Fa-f]{6}$" class="memodex-input font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Accent Color Hex</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="accentColor" class="w-10 h-10 rounded-lg border border-[#3B4E82] bg-transparent cursor-pointer">
                                <input type="text" name="accent_color" x-model="accentColor" required pattern="^#[0-9A-Fa-f]{6}$" class="memodex-input font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#BCC6E0] mb-1.5">Outer Matting Hex</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="outerBg" class="w-10 h-10 rounded-lg border border-[#3B4E82] bg-transparent cursor-pointer">
                                <input type="text" name="outer_bg" x-model="outerBg" required pattern="^#[0-9A-Fa-f]{6}$" class="memodex-input font-mono">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="font-display text-base font-semibold text-[#F6F7FB]">Frame Geometry</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-[#BCC6E0] mb-2">Border Width: <span class="font-mono text-[#F6F7FB]" x-text="borderWidth + ' px'"></span></label>
                            <input type="range" name="border_width" min="16" max="64" step="2" x-model.number="borderWidth" class="w-full accent-[#AF47D2]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#BCC6E0] mb-2">Inner Padding: <span class="font-mono text-[#F6F7FB]" x-text="innerPadding + ' px'"></span></label>
                            <input type="range" name="inner_padding" min="4" max="32" step="2" x-model.number="innerPadding" class="w-full accent-[#AF47D2]">
                        </div>
                    </div>
                </section>

                <div class="flex items-center justify-between pt-4 border-t border-[#D4DCF0]/[0.08]">
                    <a href="{{ route('admin.frames.index') }}" class="memodex-btn-ghost">Cancel</a>
                    <button type="submit" class="memodex-btn-primary px-6 py-2.5">Save Configuration</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
