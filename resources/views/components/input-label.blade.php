@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs text-[#BCC6E0] tracking-wide mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
