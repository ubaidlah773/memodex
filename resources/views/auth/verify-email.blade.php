<x-guest-layout>
    <x-slot name="title">Verify Email — MEMODEX</x-slot>

    <div class="mb-5">
        <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">Verify your email</h2>
        <p class="text-sm text-[#9DAABD] mt-1 leading-relaxed">
            Thanks for signing up! Please verify your email address by clicking the link we just sent you.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 px-3.5 py-2.5 rounded-lg bg-[#5CC99B]/10 border border-[#5CC99B]/30 text-xs font-medium text-[#5CC99B]">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="pt-2 flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="memodex-btn-primary">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs text-[#9DAABD] hover:text-[#F3F5F8] underline">
                Sign Out
            </button>
        </form>
    </div>
</x-guest-layout>
