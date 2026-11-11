<x-app-layout>
    <x-slot name="title">Storage &amp; Settings — MEMODEX</x-slot>

    <div class="space-y-8 max-w-4xl">
        <div class="pb-4 border-b border-memodex-border">
            <span class="text-[11px] font-mono tracking-widest uppercase text-memodex-primary">Account Management</span>
            <h1 class="text-2xl font-bold tracking-tight text-memodex-text mt-1">Archive Settings</h1>
            <p class="text-xs text-memodex-muted mt-1">
                Manage your profile identity, storage quota, and account security.
            </p>
        </div>

        <!-- 1. Private Storage Usage Bar -->
        <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-bold text-memodex-text">Encrypted Storage Quota</h3>
                    <p class="text-xs text-memodex-muted">Your original photos, thumbnails, and high-res PNG card renders.</p>
                </div>
                <div class="text-right font-mono text-xs text-memodex-subtle">
                    <span class="text-memodex-text font-bold">{{ round($user->storage_used_bytes / (1024 * 1024), 2) }} MB</span>
                    / {{ round($user->storage_quota_bytes / (1024 * 1024), 0) }} MB ({{ $user->storagePercent() }}%)
                </div>
            </div>

            <div class="w-full bg-memodex-bg rounded-full h-3 overflow-hidden border border-memodex-border">
                <div class="bg-memodex-primary h-3 rounded-full transition-all duration-300" style="width: {{ min(100, $user->storagePercent()) }}%"></div>
            </div>

            <!-- Asset Counts Breakdown -->
            <div class="grid grid-cols-3 gap-3 pt-2 text-center text-xs font-mono">
                <div class="p-3 bg-memodex-bg rounded border border-memodex-border">
                    <span class="text-memodex-subtle block text-[10px] uppercase">Photos</span>
                    <span class="text-memodex-text font-bold text-sm mt-1 block">{{ $totalPhotos }}</span>
                </div>
                <div class="p-3 bg-memodex-bg rounded border border-memodex-border">
                    <span class="text-memodex-subtle block text-[10px] uppercase">Cards</span>
                    <span class="text-memodex-text font-bold text-sm mt-1 block">{{ $totalCards }}</span>
                </div>
                <div class="p-3 bg-memodex-bg rounded border border-memodex-border">
                    <span class="text-memodex-subtle block text-[10px] uppercase">Binders</span>
                    <span class="text-memodex-text font-bold text-sm mt-1 block">{{ $totalAlbums }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Profile Information Form -->
        <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel space-y-4">
            <h3 class="text-sm font-bold text-memodex-text">Collector Identity</h3>

            <form method="POST" action="{{ route('settings.profile') }}" class="space-y-4 max-w-lg">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs text-memodex-muted mb-1 font-medium">Display Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-memodex-bg border border-memodex-border rounded-md px-3 py-2 text-xs text-memodex-text focus:border-memodex-primary">
                    @error('name')
                        <p class="text-[11px] text-memodex-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-xs text-memodex-muted mb-1 font-medium">Collector Handle (@username)</label>
                    <div class="flex items-center">
                        <span class="px-3 py-2 bg-memodex-raised border border-r-0 border-memodex-border rounded-l-md text-xs text-memodex-subtle font-mono">@</span>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" placeholder="handle" class="w-full bg-memodex-bg border border-memodex-border rounded-r-md px-3 py-2 text-xs text-memodex-text font-mono focus:border-memodex-primary">
                    </div>
                    @error('username')
                        <p class="text-[11px] text-memodex-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs text-memodex-muted mb-1 font-medium">Email Address</label>
                    <input type="email" disabled value="{{ $user->email }}" class="w-full bg-memodex-bg/60 border border-memodex-border/60 rounded-md px-3 py-2 text-xs text-memodex-subtle cursor-not-allowed">
                    <p class="text-[10px] text-memodex-subtle mt-1 font-mono">Email authentication account.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-5 py-2 bg-memodex-primary hover:bg-memodex-primary-strong text-memodex-bg text-xs font-semibold rounded transition-colors">
                        Save Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- 3. Password / Account Settings Links -->
        <div class="p-6 rounded-xl border border-memodex-border bg-memodex-panel flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-memodex-text">Security &amp; Passwords</h3>
                <p class="text-xs text-memodex-muted mt-0.5">Update credentials or manage active sessions.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 border border-memodex-border bg-memodex-raised hover:bg-memodex-soft rounded text-xs text-memodex-text">
                Manage Credentials &rarr;
            </a>
        </div>
    </div>
</x-app-layout>
