<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#FF8F00] hover:bg-[#FFA22B] border border-transparent rounded-lg font-semibold text-sm text-[#161F3B] focus:outline-none focus:ring-2 focus:ring-[#AF47D2] focus:ring-offset-2 focus:ring-offset-[#161F3B] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
