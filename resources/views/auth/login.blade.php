<x-guest-layout>
    <x-slot name="title">Sign In — MEMODEX</x-slot>

    <div class="mb-6">
        <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Sign in to your archive</h2>
        <p class="text-sm text-[#9DAABD] mt-1">Access your framed trading cards and curated binders.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email Address" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="collector@example.com" required autofocus autocomplete="username" class="memodex-input">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block font-medium text-xs text-[#9DAABD] tracking-wide">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-[#69AFFF] hover:underline" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password" class="memodex-input">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="pt-1">
            <label for="remember_me" class="inline-flex items-center text-sm text-[#9DAABD] cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded bg-[#101827] border-[#2D3C53] text-[#69AFFF] focus:ring-[#69AFFF] focus:ring-offset-[#17243A]">
                <span class="ms-2.5 text-xs">Keep me signed in</span>
            </label>
        </div>

        <div class="pt-2 space-y-4">
            <button type="submit" class="memodex-btn-primary w-full">
                Sign In
            </button>

            <p class="text-center text-xs text-[#9DAABD] pt-2 border-t border-[#C8D2DF]/10">
                New collector?
                <a href="{{ route('register') }}" class="text-[#69AFFF] hover:underline font-medium">Create an account</a>
            </p>
        </div>
    </form>
</x-guest-layout>
