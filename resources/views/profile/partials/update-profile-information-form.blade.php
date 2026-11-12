<section>
    <header>
        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-[#BCC6E0]">
            Update your account's display name and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-xs text-[#BCC6E0]">
                        Your email address is unverified.
                        <button form="send-verification" class="underline text-[#FFA22B] hover:text-[#F6F7FB] ml-1">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-[#5CC99B]">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>Save Profile</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-xs font-medium text-[#5CC99B]">
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>
