<x-guest-layout>
    <x-slot name="title">Register — MEMODEX</x-slot>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-medium text-memodex-muted mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-memodex-danger text-[11px]" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-medium text-memodex-muted mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-memodex-danger text-[11px]" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-medium text-memodex-muted mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-memodex-danger text-[11px]" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-memodex-muted mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary focus:ring-1 focus:ring-memodex-primary">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-memodex-danger text-[11px]" />
        </div>

        <div class="pt-2 space-y-3">
            <button type="submit" class="w-full py-2.5 bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg text-xs font-semibold rounded-md transition-colors shadow">
                Create Collector Account
            </button>

            <p class="text-center text-xs text-memodex-muted">
                Already registered?
                <a href="{{ route('login') }}" class="text-memodex-primary hover:underline font-medium">Sign in</a>
            </p>
        </div>
    </form>
</x-guest-layout>
