@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-[#161F3B] border-[#3B4E82] text-[#F6F7FB] placeholder:text-[#8B99BD] focus:border-[#AF47D2] focus:ring-[#AF47D2] rounded-lg shadow-sm text-sm']) }}>
