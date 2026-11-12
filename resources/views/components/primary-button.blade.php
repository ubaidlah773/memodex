<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#69AFFF] hover:bg-[#4E98F2] border border-transparent rounded-lg font-semibold text-sm text-[#101827] focus:outline-none focus:ring-2 focus:ring-[#69AFFF] focus:ring-offset-2 focus:ring-offset-[#101827] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
