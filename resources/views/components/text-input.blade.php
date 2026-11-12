@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-[#101827] border-[#2D3C53] text-[#F3F5F8] placeholder:text-[#6D7A8D] focus:border-[#69AFFF] focus:ring-[#69AFFF] rounded-lg shadow-sm text-sm']) }}>
