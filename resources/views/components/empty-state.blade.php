@props(['title', 'description', 'icon' => 'card'])

<div {{ $attributes->merge(['class' => 'py-14 px-6 border border-dashed border-[#D4DCF0]/15 rounded-2xl bg-[#26355D]/40 text-center max-w-xl mx-auto']) }}>
    <div class="w-14 h-20 mx-auto mb-5 rounded-lg border border-[#D4DCF0]/20 bg-[#161F3B] flex items-center justify-center relative">
        @if ($icon === 'binder')
            <svg class="w-6 h-6 text-[#FFA22B]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"/>
            </svg>
        @elseif ($icon === 'star')
            <svg class="w-6 h-6 text-[#FFDB00]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
            </svg>
        @elseif ($icon === 'search')
            <svg class="w-6 h-6 text-[#BCC6E0]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
        @else
            <svg class="w-6 h-6 text-[#FFA22B]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
            </svg>
        @endif
        <span class="absolute -bottom-1.5 -right-1.5 w-3 h-3 rounded-full bg-[#26355D] border border-[#FFDB00]/60"></span>
    </div>

    <h3 class="font-display text-lg font-semibold text-[#F6F7FB]">{{ $title }}</h3>
    <p class="text-sm text-[#BCC6E0] mt-2 max-w-md mx-auto leading-relaxed">{{ $description }}</p>

    @if (isset($actions))
        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
