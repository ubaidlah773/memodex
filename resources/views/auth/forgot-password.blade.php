<x-guest-layout>
    <x-slot name="title">Reset Password — MEMODEX</x-slot>

    <div class="mb-5">
        <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Reset your password</h2>
        <p class="text-sm text-[#9DAABD] mt-1 leading-relaxed">
            Enter the email associated with your MEMODEX account and we will send you a recovery link.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email Address" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="memodex-input">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="pt-2 space-y-3">
            <button type="submit" class="memodex-btn-primary w-full">
                Send Password Reset Link
            </button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-xs text-[#9DAABD] hover:text-[#F3F5F8]">Back to sign in</a>
            </div>
        </div>
    </form>
</x-guest-layout>
