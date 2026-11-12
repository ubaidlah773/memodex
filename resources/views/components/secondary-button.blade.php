<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#223056] hover:bg-[#304273] border border-[#3B4E82] rounded-lg font-medium text-sm text-[#F6F7FB] focus:outline-none focus:ring-2 focus:ring-[#AF47D2] focus:ring-offset-2 focus:ring-offset-[#161F3B] disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
