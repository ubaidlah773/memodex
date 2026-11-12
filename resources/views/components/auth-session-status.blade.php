@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'px-3.5 py-2.5 rounded-lg bg-[#5CC99B]/10 border border-[#5CC99B]/30 text-xs font-medium text-[#5CC99B]']) }}>
        {{ $status }}
    </div>
@endif
