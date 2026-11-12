@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs text-[#9DAABD] tracking-wide mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
