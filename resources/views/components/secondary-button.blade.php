<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#1D293B] hover:bg-[#263449] border border-[#2D3C53] rounded-lg font-medium text-sm text-[#F3F5F8] focus:outline-none focus:ring-2 focus:ring-[#69AFFF] focus:ring-offset-2 focus:ring-offset-[#101827] disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
