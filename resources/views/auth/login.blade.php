<x-guest-layout>
    <x-slot name="title">Sign In — MEMODEX</x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-medium text-memodex-muted mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', 'collector@memodex.test') }}" required autofocus autocomplete="username"
                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-memodex-danger text-[11px]" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-medium text-memodex-muted mb-1">Password</label>
            <input id="password" type="password" name="password" value="password" required autocomplete="current-password"
                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-memodex-danger text-[11px]" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center text-memodex-subtle cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded bg-memodex-bg border-memodex-border text-memodex-primary focus:ring-0">
                <span class="ms-2">Keep me signed in</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-memodex-subtle hover:text-memodex-primary" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <div class="pt-2 space-y-3">
            <button type="submit" class="w-full py-2.5 bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg text-xs font-semibold rounded-md transition-colors shadow">
                Sign In to Collection
            </button>

            <p class="text-center text-xs text-memodex-muted">
                New collector?
                <a href="{{ route('register') }}" class="text-memodex-primary hover:underline font-medium">Create an account</a>
            </p>
        </div>
    </form>
</x-guest-layout>
