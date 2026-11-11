<x-app-layout>
    <x-slot name="title">Edit Frame: {{ $frame->name }} — Admin MEMODEX</x-slot>

    @php
        $cfg = $frame->config ?? [];
    @endphp

    <div x-data="{
        name: '{{ addslashes($frame->name) }}',
        description: '{{ addslashes($frame->description ?? '') }}',
        borderPrimary: '{{ $cfg['border_primary'] ?? '#3D4F66' }}',
        borderInner: '{{ $cfg['border_inner'] ?? '#202E40' }}',
        accentColor: '{{ $cfg['accent_color'] ?? '#83B9F2' }}',
        outerBg: '{{ $cfg['outer_bg'] ?? '#0F1622' }}',
        borderWidth: {{ (int) ($cfg['border_width'] ?? 32) }},
        innerPadding: {{ (int) ($cfg['inner_padding'] ?? 12) }},
        label: '{{ addslashes($cfg['label'] ?? 'ARCHIVE ITEM') }}'
    }" class="space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-memodex-border">
            <div>
                <a href="{{ route('admin.frames.index') }}" class="text-xs font-mono text-memodex-subtle hover:text-memodex-primary">&larr; Back to Frames</a>
                <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Edit Frame: {{ $frame->name }}</h1>
                <p class="text-xs text-memodex-muted mt-0.5">Slug: <code class="text-memodex-primary">{{ $frame->slug }}</code> &bull; Modifies outer frame rendering parameters.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.frames.update', $frame) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf
            @method('PUT')

            <!-- Left: Frame Live Preview -->
            <div class="lg:col-span-5 flex flex-col items-center lg:sticky lg:top-24">
                <div class="w-full max-w-sm">
                    <span class="text-[11px] font-mono text-memodex-subtle uppercase mb-2 block">Live Frame Preview</span>

                    <!-- Mockup with dynamic bound styles -->
                    <div class="w-full aspect-card rounded-xl border-2 overflow-hidden relative shadow-2xl flex flex-col justify-between p-3 select-none transition-all duration-200"
                         :style="`border-color: ${borderPrimary}; background-color: ${outerBg};`">
                        <div class="w-full h-full rounded border flex flex-col justify-between p-2 relative"
                             :style="`border-color: ${borderInner};`">
                            <!-- Card artwork mockup -->
                            <div class="w-full flex-1 rounded bg-[#0A0E14] overflow-hidden flex items-center justify-center relative border"
                                 :style="`border-color: ${borderPrimary};`">
                                <div class="text-center p-6 space-y-2">
                                    <div class="w-8 h-8 mx-auto rounded-full border flex items-center justify-center font-bold text-xs" :style="`color: ${accentColor}; border-color: ${accentColor};`">
                                        P
                                    </div>
                                    <p class="text-xs font-semibold text-memodex-text">Pokémon Card Area</p>
                                    <p class="text-[10px] text-memodex-subtle font-mono">100% Intact Unaltered Artwork</p>
                                </div>
                            </div>

                            <!-- Plaque -->
                            <div class="mt-2 px-2 py-1.5 rounded bg-[#0A0F16] border flex items-center justify-between text-[9px] font-mono"
                                 :style="`border-color: ${borderInner};`">
                                <span class="text-memodex-subtle">MEMODEX ARCHIVE</span>
                                <span class="font-bold uppercase" :style="`color: ${accentColor};`" x-text="label"></span>
                                <span class="text-memodex-text">MDX-000001</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Config Controls -->
            <div class="lg:col-span-7 space-y-6">
                <!-- General Info -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">General Information</span>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-xs text-memodex-muted mb-1 font-medium">Display Name</label>
                            <input type="text" id="name" name="name" x-model="name" required maxlength="50"
                                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-xs text-memodex-muted mb-1 font-medium">Description</label>
                            <textarea id="description" name="description" x-model="description" rows="2" maxlength="250"
                                      class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary"></textarea>
                        </div>

                        <div>
                            <label for="label" class="block text-xs text-memodex-muted mb-1 font-medium">Plaque Label Text</label>
                            <input type="text" id="label" name="label" x-model="label" required maxlength="40"
                                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                        </div>

                        <div class="flex items-center pt-5">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $frame->is_active ? 'checked' : '' }}
                                       class="rounded bg-memodex-bg border-memodex-border text-memodex-primary focus:ring-memodex-primary">
                                <span class="text-xs font-medium text-memodex-text">Active for Collectors</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Palette Colors -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">Color Palette Tokens</span>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-memodex-muted mb-1 font-medium">Primary Border Color (Hex)</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="borderPrimary" class="w-8 h-8 rounded border border-memodex-border bg-transparent cursor-pointer">
                                <input type="text" name="border_primary" x-model="borderPrimary" required pattern="^#[0-9A-Fa-f]{6}$"
                                       class="flex-1 bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs font-mono text-memodex-text">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-memodex-muted mb-1 font-medium">Inner Border Color (Hex)</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="borderInner" class="w-8 h-8 rounded border border-memodex-border bg-transparent cursor-pointer">
                                <input type="text" name="border_inner" x-model="borderInner" required pattern="^#[0-9A-Fa-f]{6}$"
                                       class="flex-1 bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs font-mono text-memodex-text">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-memodex-muted mb-1 font-medium">Accent / Text Color (Hex)</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="accentColor" class="w-8 h-8 rounded border border-memodex-border bg-transparent cursor-pointer">
                                <input type="text" name="accent_color" x-model="accentColor" required pattern="^#[0-9A-Fa-f]{6}$"
                                       class="flex-1 bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs font-mono text-memodex-text">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-memodex-muted mb-1 font-medium">Outer Background Matting (Hex)</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="outerBg" class="w-8 h-8 rounded border border-memodex-border bg-transparent cursor-pointer">
                                <input type="text" name="outer_bg" x-model="outerBg" required pattern="^#[0-9A-Fa-f]{6}$"
                                       class="flex-1 bg-memodex-bg border border-memodex-border rounded-md px-3 py-1.5 text-xs font-mono text-memodex-text">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Geometric Dimensions -->
                <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
                    <span class="text-xs font-mono uppercase text-memodex-subtle tracking-wider">Frame Geometry</span>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-memodex-muted mb-1 font-medium">Border Width: <span class="font-mono" x-text="borderWidth + 'px'"></span></label>
                            <input type="range" name="border_width" min="16" max="64" step="2" x-model.number="borderWidth" class="w-full accent-memodex-primary">
                        </div>

                        <div>
                            <label class="block text-xs text-memodex-muted mb-1 font-medium">Inner Padding: <span class="font-mono" x-text="innerPadding + 'px'"></span></label>
                            <input type="range" name="inner_padding" min="4" max="32" step="2" x-model.number="innerPadding" class="w-full accent-memodex-primary">
                        </div>
                    </div>
                </div>

                <!-- Form Submit -->
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('admin.frames.index') }}" class="px-4 py-2 text-xs text-memodex-muted hover:text-memodex-text">
                        Cancel
                    </a>

                    <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-lg text-xs font-semibold bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg transition-colors shadow">
                        Save Frame Configuration &rarr;
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
