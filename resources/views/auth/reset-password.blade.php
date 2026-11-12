<x-guest-layout>
    <x-slot name="title">Choose New Password — MEMODEX</x-slot>

    <div class="mb-5">
        <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Set a new password</h2>
        <p class="text-sm text-[#9DAABD] mt-1">Choose a strong password for your collection archive.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Email Address" />
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="memodex-input">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="New Password" />
            <input id="password" type="password" name="password" required autocomplete="new-password" class="memodex-input">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="memodex-input">
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="pt-2">
            <button type="submit" class="memodex-btn-primary w-full">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>
