@props(['size' => 'md'])

@php
$dim = match($size) {
    'sm' => 'w-7 h-7',
    'lg' => 'w-11 h-11',
    default => 'w-8 h-8',
};
@endphp

<div {{ $attributes->merge(['class' => "$dim rounded-lg bg-[#101827] border border-[#C8D2DF]/20 flex items-center justify-center flex-shrink-0 shadow-sm"]) }}>
    <svg viewBox="0 0 28 36" class="w-4/6 h-4/6" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect x="3" y="2" width="22" height="32" rx="3" stroke="#C8D2DF" stroke-opacity="0.45" stroke-width="1.6"/>
        <rect x="7" y="6" width="14" height="24" rx="1.5" stroke="#69AFFF" stroke-width="1.6"/>
        <circle cx="14" cy="18" r="3.2" fill="#69AFFF"/>
        <path d="M18.5 5.5H21.5V8.5" stroke="#D4B36A" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
</div>
