<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#EB6F75]/15 hover:bg-[#EB6F75]/25 border border-[#EB6F75]/40 rounded-lg font-semibold text-sm text-[#EB6F75] focus:outline-none focus:ring-2 focus:ring-[#EB6F75] focus:ring-offset-2 focus:ring-offset-[#101827] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
