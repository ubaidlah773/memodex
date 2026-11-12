<x-app-layout>
    <x-slot name="title">Settings — MEMODEX</x-slot>

    @php
        $initialTab = request()->query('tab', 'profile');
        if ($errors->updatePassword->any() || session('status') === 'password-updated') {
            $initialTab = 'security';
        } elseif ($errors->userDeletion->any()) {
            $initialTab = 'account';
        }
    @endphp

    <div class="space-y-8 max-w-4xl" x-data="{ tab: '{{ $initialTab }}' }">
        <!-- Page Header -->
        <section class="pb-5 border-b border-[#D4DCF0]/[0.08]">
            <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F6F7FB]">Settings</h1>
            <p class="text-sm text-[#BCC6E0] mt-1">Manage collector identity, account credentials, and private vault allocation.</p>
        </section>

        <!-- Responsive Settings Shell (Sidebar Tabs on Desktop, Horizontal Tabs on Mobile) -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <!-- Tab Navigation -->
            <nav class="md:col-span-3 grid grid-cols-2 sm:grid-cols-4 md:flex md:flex-col gap-1.5" aria-label="Settings Sections">
                <button type="button" @click="tab = 'profile'"
                        :class="tab === 'profile' ? 'bg-[#223056] text-[#F6F7FB] border-[#AF47D2]/40' : 'text-[#BCC6E0] hover:text-[#F6F7FB] border-transparent hover:bg-[#26355D]'"
                        class="px-3.5 py-2.5 rounded-xl border text-sm font-medium text-center md:text-left whitespace-nowrap transition-colors">
                    Profile
                </button>
                <button type="button" @click="tab = 'security'"
                        :class="tab === 'security' ? 'bg-[#223056] text-[#F6F7FB] border-[#AF47D2]/40' : 'text-[#BCC6E0] hover:text-[#F6F7FB] border-transparent hover:bg-[#26355D]'"
                        class="px-3.5 py-2.5 rounded-xl border text-sm font-medium text-center md:text-left whitespace-nowrap transition-colors">
                    Security
                </button>
                <button type="button" @click="tab = 'storage'"
                        :class="tab === 'storage' ? 'bg-[#223056] text-[#F6F7FB] border-[#AF47D2]/40' : 'text-[#BCC6E0] hover:text-[#F6F7FB] border-transparent hover:bg-[#26355D]'"
                        class="px-3.5 py-2.5 rounded-xl border text-sm font-medium text-center md:text-left whitespace-nowrap transition-colors">
                    Storage
                </button>
                <button type="button" @click="tab = 'account'"
                        :class="tab === 'account' ? 'bg-[#223056] text-[#EB6F75] border-[#EB6F75]/35' : 'text-[#BCC6E0] hover:text-[#EB6F75] border-transparent hover:bg-[#26355D]'"
                        class="px-3.5 py-2.5 rounded-xl border text-sm font-medium text-center md:text-left whitespace-nowrap transition-colors">
                    Account
                </button>
            </nav>

            <!-- Tab Content Area -->
            <div class="md:col-span-9">
                <!-- TAB 1: PROFILE -->
                <section x-show="tab === 'profile'" class="bg-[#26355D] border border-[#D4DCF0]/10 rounded-2xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Collector Identity</h2>
                        <p class="text-sm text-[#BCC6E0] mt-1">Customize your display name and collector handle.</p>
                    </div>

                    <form method="POST" action="{{ route('settings.profile') }}" class="space-y-4 max-w-lg">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" value="Display Name" />
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="memodex-input">
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="username" value="Collector Handle" />
                            <div class="flex items-center">
                                <span class="px-3.5 py-2.5 bg-[#223056] border border-r-0 border-[#3B4E82] rounded-l-lg text-sm text-[#8B99BD] font-mono">@</span>
                                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" placeholder="collector" class="memodex-input rounded-l-none font-mono">
                            </div>
                            <x-input-error :messages="$errors->get('username')" />
                        </div>

                        <div>
                            <x-input-label for="email_ro" value="Registered Email" />
                            <input type="email" id="email_ro" disabled value="{{ $user->email }}" class="memodex-input opacity-60 cursor-not-allowed">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="memodex-btn-primary text-xs px-5 py-2.5">Save Profile</button>
                        </div>
                    </form>
                </section>

                <!-- TAB 2: PASSWORD & SECURITY -->
                <section x-show="tab === 'security'" x-cloak class="bg-[#26355D] border border-[#D4DCF0]/10 rounded-2xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Update Password</h2>
                        <p class="text-sm text-[#BCC6E0] mt-1">Protect your private card collection with a strong, unique password.</p>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-lg">
                        @csrf
                        @method('put')

                        <div>
                            <x-input-label for="update_password_current_password" value="Current Password" />
                            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="memodex-input">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password" value="New Password" />
                            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="memodex-input">
                            <x-input-error :messages="$errors->updatePassword->get('password')" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password_confirmation" value="Confirm Password" />
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="memodex-input">
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="memodex-btn-primary text-xs px-5 py-2.5">Update Password</button>
                            @if (session('status') === 'password-updated')
                                <span class="text-xs font-medium text-[#5CC99B]">Password updated.</span>
                            @endif
                        </div>
                    </form>
                </section>

                <!-- TAB 3: STORAGE & QUOTA -->
                <section x-show="tab === 'storage'" x-cloak class="bg-[#26355D] border border-[#D4DCF0]/10 rounded-2xl p-6 sm:p-8 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Private Storage Allocation</h2>
                            <p class="text-sm text-[#BCC6E0] mt-1">Originals, proportional thumbnails, and high-res framed PNG exports.</p>
                        </div>
                        <span class="font-mono text-sm text-[#F6F7FB] tabular-nums">
                            {{ round($user->storage_used_bytes / (1024 * 1024), 2) }} / {{ round($user->storage_quota_bytes / (1024 * 1024), 0) }} MB ({{ $user->storagePercent() }}%)
                        </span>
                    </div>

                    <div class="w-full bg-[#161F3B] rounded-full h-2.5 overflow-hidden border border-[#D4DCF0]/10">
                        <div class="bg-[#FF8F00] h-full rounded-full transition-all duration-300" style="width: {{ min(100, $user->storagePercent()) }}%"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-2 border-t border-[#D4DCF0]/[0.08]">
                        <div>
                            <span class="text-xs text-[#8B99BD] block">Original Uploads</span>
                            <span class="font-display text-xl font-semibold text-[#F6F7FB] tabular-nums mt-1 block">{{ $totalPhotos }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-[#8B99BD] block">Archived Cards</span>
                            <span class="font-display text-xl font-semibold text-[#F6F7FB] tabular-nums mt-1 block">{{ $totalCards }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-[#8B99BD] block">Curated Binders</span>
                            <span class="font-display text-xl font-semibold text-[#F6F7FB] tabular-nums mt-1 block">{{ $totalAlbums }}</span>
                        </div>
                    </div>
                </section>

                <!-- TAB 4: ACCOUNT / DANGER ZONE -->
                <section x-show="tab === 'account'" x-cloak class="bg-[#26355D] border border-[#D4DCF0]/10 rounded-2xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h2 class="font-display text-lg font-semibold text-[#F6F7FB]">Account Session &amp; Deletion</h2>
                        <p class="text-sm text-[#BCC6E0] mt-1 leading-relaxed">
                            Permanently delete your MEMODEX account, uploaded original card scans, framed renders, and binders. This action cannot be undone.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-[#D4DCF0]/[0.08]">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="memodex-btn-secondary text-xs">Sign Out of Session</button>
                        </form>

                        <button type="button"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                class="px-4 py-2.5 rounded-lg bg-[#EB6F75]/15 hover:bg-[#EB6F75]/25 border border-[#EB6F75]/40 text-xs font-semibold text-[#EB6F75] transition-colors">
                            Delete Account
                        </button>
                    </div>

                    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable maxWidth="md">
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
                            @csrf
                            @method('delete')

                            <h3 class="font-display text-lg font-semibold text-[#F6F7FB]">Delete your account permanently?</h3>
                            <p class="text-sm text-[#BCC6E0] leading-relaxed">
                                Enter your current password to confirm permanent deletion of your MEMODEX archive.
                            </p>

                            <div>
                                <x-input-label for="delete_account_password" value="Password" class="sr-only" />
                                <input id="delete_account_password" name="password" type="password" placeholder="Enter password to confirm" class="memodex-input">
                                <x-input-error :messages="$errors->userDeletion->get('password')" />
                            </div>

                            <div class="flex justify-end gap-3 pt-2">
                                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                <x-danger-button>Delete Account</x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
