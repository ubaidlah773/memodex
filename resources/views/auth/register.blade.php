<x-guest-layout>
    <x-slot name="title">Create Account — MEMODEX</x-slot>

    <div class="mb-6">
        <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Create collector account</h2>
        <p class="text-sm text-[#9DAABD] mt-1">Start archiving your authentic Pokémon cards in 63:88 frames.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Full Name" />
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Ash Ketchum" required autofocus autocomplete="name" class="memodex-input">
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email Address" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="collector@example.com" required autocomplete="username" class="memodex-input">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <input id="password" type="password" name="password" placeholder="Minimum 8 characters" required autocomplete="new-password" class="memodex-input">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Re-enter password" required autocomplete="new-password" class="memodex-input">
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="pt-2 space-y-4">
            <button type="submit" class="memodex-btn-primary w-full">
                Create Collector Account
            </button>

            <p class="text-center text-xs text-[#9DAABD] pt-2 border-t border-[#C8D2DF]/10">
                Already have an account?
                <a href="{{ route('login') }}" class="text-[#69AFFF] hover:underline font-medium">Sign in</a>
            </p>
        </div>
    </form>
</x-guest-layout>
