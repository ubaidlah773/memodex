<x-guest-layout>
    <x-slot name="title">Sign In — MEMODEX</x-slot>

    <div class="mb-6">
        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Sign in to your archive</h2>
        <p class="text-sm text-[#BCC6E0] mt-1">Access your framed trading cards and curated binders.</p>
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
                <label for="password" class="block font-medium text-xs text-[#BCC6E0] tracking-wide">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-[#FFA22B] hover:underline" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password" class="memodex-input">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="pt-1">
            <label for="remember_me" class="inline-flex items-center text-sm text-[#BCC6E0] cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded bg-[#161F3B] border-[#3B4E82] text-[#FFA22B] focus:ring-[#AF47D2] focus:ring-offset-[#26355D]">
                <span class="ms-2.5 text-xs">Keep me signed in</span>
            </label>
        </div>

        <div class="pt-2 space-y-4">
            <button type="submit" class="memodex-btn-primary w-full">
                Sign In
            </button>

            <p class="text-center text-xs text-[#BCC6E0] pt-2 border-t border-[#D4DCF0]/10">
                New collector?
                <a href="{{ route('register') }}" class="text-[#FFA22B] hover:underline font-medium">Create an account</a>
            </p>
        </div>
    </form>
</x-guest-layout>
