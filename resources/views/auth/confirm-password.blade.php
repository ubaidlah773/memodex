<x-guest-layout>
    <x-slot name="title">Confirm Password — MEMODEX</x-slot>

    <div class="mb-5">
        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Confirm your password</h2>
        <p class="text-sm text-[#BCC6E0] mt-1 leading-relaxed">
            This is a protected action. Please confirm your password before continuing.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" value="Password" />
            <input id="password" type="password" name="password" required autocomplete="current-password" class="memodex-input">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="pt-2">
            <button type="submit" class="memodex-btn-primary w-full">
                Confirm
            </button>
        </div>
    </form>
</x-guest-layout>
