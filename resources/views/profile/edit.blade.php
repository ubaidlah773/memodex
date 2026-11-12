<x-app-layout>
    <x-slot name="title">Profile &amp; Security — MEMODEX</x-slot>

    <div class="space-y-8 max-w-3xl">
        <section class="flex items-end justify-between gap-4 pb-5 border-b border-[#C8D2DF]/[0.08]">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight text-[#F3F5F8]">Account &amp; Security</h1>
                <p class="text-sm text-[#9DAABD] mt-1">Manage your login credentials, email, and account status.</p>
            </div>
            <a href="{{ route('settings.index') }}" class="memodex-btn-secondary text-xs">
                &larr; Settings
            </a>
        </section>

        <div class="bg-[#17243A] border border-[#C8D2DF]/10 rounded-2xl p-6 sm:p-8">
            <div class="max-w-lg">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-[#17243A] border border-[#C8D2DF]/10 rounded-2xl p-6 sm:p-8">
            <div class="max-w-lg">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-[#17243A] border border-[#C8D2DF]/10 rounded-2xl p-6 sm:p-8">
            <div class="max-w-lg">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
