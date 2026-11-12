<section class="space-y-4">
    <header>
        <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">
            Delete Account
        </h2>

        <p class="mt-1 text-sm text-[#9DAABD] leading-relaxed">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any original cards you wish to retain.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Delete Account</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <h2 class="font-display text-lg font-semibold text-[#F3F5F8]">
                Are you sure you want to delete your account?
            </h2>

            <p class="text-sm text-[#9DAABD] leading-relaxed">
                Once your account is deleted, all of its cards, photos, and binders will be permanently deleted. Enter your password to confirm.
            </p>

            <div>
                <x-input-label for="password" value="Password" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="block w-full" placeholder="Password" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-danger-button>Delete Account</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
