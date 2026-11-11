<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MEMODEX — Collectible Archive' }}</title>

    <!-- Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-memodex-bg text-memodex-text font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-memodex-primary selection:text-memodex-bg">

    <!-- DESKTOP / TABLET SIDEBAR -->
    <aside class="hidden md:flex md:w-60 lg:w-64 flex-col bg-memodex-panel border-r border-memodex-border flex-shrink-0 min-h-screen sticky top-0 z-30">
        <!-- Brand / Identity -->
        <div class="h-16 flex items-center px-6 border-b border-memodex-border justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-8 h-8 rounded border border-memodex-primary/40 bg-memodex-raised flex items-center justify-center text-memodex-primary font-bold text-sm tracking-wider">
                    M
                </div>
                <div>
                    <span class="font-bold tracking-widest text-sm text-memodex-text group-hover:text-memodex-primary transition-colors">MEMODEX</span>
                    <span class="block text-[10px] text-memodex-subtle uppercase tracking-wider font-mono">Archive Studio</span>
                </div>
            </a>
        </div>

        <!-- Main Navigation Links -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <div class="px-3 pb-2 text-[10px] font-mono tracking-widest uppercase text-memodex-subtle">Collection</div>

            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-memodex-soft text-memodex-primary border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Overview
            </a>

            <a href="{{ route('pokedex.index') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('pokedex.*') ? 'bg-memodex-soft text-memodex-primary border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Pokédex
            </a>

            <a href="{{ route('cards.create') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('cards.create') ? 'bg-memodex-soft text-memodex-primary border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Card
            </a>

            <a href="{{ route('albums.index') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('albums.*') ? 'bg-memodex-soft text-memodex-primary border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Binders / Albums
            </a>

            <a href="{{ route('favorites.index') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('favorites.*') ? 'bg-memodex-soft text-memodex-primary border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Favorites
            </a>

            <div class="pt-6 px-3 pb-2 text-[10px] font-mono tracking-widest uppercase text-memodex-subtle">Assets</div>

            <a href="{{ route('photos.index') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('photos.*') ? 'bg-memodex-soft text-memodex-primary border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Photo Library
            </a>

            <a href="{{ route('settings.index') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('settings.*') ? 'bg-memodex-soft text-memodex-primary border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Storage & Settings
            </a>

            @if (auth()->user()?->is_admin)
                <div class="pt-6 px-3 pb-2 text-[10px] font-mono tracking-widest uppercase text-memodex-accent-warm">Administration</div>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.*') ? 'bg-memodex-soft text-memodex-accent-warm border border-memodex-border' : 'text-memodex-muted hover:bg-memodex-raised hover:text-memodex-text' }}">
                    <svg class="w-4 h-4 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Admin Center
                </a>
            @endif
        </nav>

        <!-- Storage Quota Widget -->
        @auth
        <div class="p-4 mx-3 mb-4 rounded-lg bg-memodex-raised border border-memodex-border text-xs">
            <div class="flex items-center justify-between text-memodex-subtle mb-1.5 font-mono text-[11px]">
                <span>PRIVATE STORAGE</span>
                <span class="text-memodex-muted">{{ Auth::user()->storagePercent() }}%</span>
            </div>
            <div class="w-full bg-memodex-bg rounded-full h-1.5 overflow-hidden">
                <div class="bg-memodex-primary h-1.5 rounded-full transition-all duration-300" style="width: {{ min(100, Auth::user()->storagePercent()) }}%"></div>
            </div>
            <div class="mt-2 text-[10px] text-memodex-subtle flex justify-between font-mono">
                <span>{{ round(Auth::user()->storage_used_bytes / (1024 * 1024), 1) }} MB</span>
                <span>500 MB QUOTA</span>
            </div>
        </div>

        <!-- User Profile Bar -->
        <div class="p-3 border-t border-memodex-border flex items-center justify-between">
            <div class="flex items-center space-x-2.5 min-w-0">
                <div class="w-8 h-8 rounded-full bg-memodex-soft border border-memodex-border flex items-center justify-center text-xs font-semibold text-memodex-text">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-memodex-text truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-memodex-subtle truncate font-mono">@<span>{{ Auth::user()->username ?: 'collector' }}</span></p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 text-memodex-subtle hover:text-memodex-danger rounded transition-colors" title="Sign out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <!-- MOBILE TOPBAR -->
    <header class="md:hidden h-14 bg-memodex-panel border-b border-memodex-border flex items-center justify-between px-4 sticky top-0 z-30">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <div class="w-6 h-6 rounded border border-memodex-primary/40 bg-memodex-raised flex items-center justify-center text-memodex-primary font-bold text-xs">M</div>
            <span class="font-bold tracking-widest text-xs text-memodex-text">MEMODEX</span>
        </a>
        <div class="flex items-center space-x-2">
            <a href="{{ route('cards.create') }}" class="px-2.5 py-1 text-xs font-medium bg-memodex-primary text-memodex-bg rounded font-mono">+ Card</a>
        </div>
    </header>

    <!-- MAIN VIEW AREA -->
    <div class="flex-1 flex flex-col min-w-0 pb-16 md:pb-0">
        <!-- Toast / Alerts -->
        @if (session('success'))
            <div class="bg-memodex-success/15 border-b border-memodex-success/30 px-6 py-3 text-xs text-memodex-success flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-memodex-danger/15 border-b border-memodex-danger/30 px-6 py-3 text-xs text-memodex-danger flex items-center justify-between">
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="bg-memodex-warning/15 border-b border-memodex-warning/30 px-6 py-3 text-xs text-memodex-warning flex items-center justify-between">
                <span>{!! session('warning') !!}</span>
            </div>
        @endif

        <!-- Dynamic Content Slot -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            {{ $slot }}
        </main>
    </div>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-14 bg-memodex-panel border-t border-memodex-border z-30 flex items-center justify-around px-2">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('dashboard') ? 'text-memodex-primary' : 'text-memodex-subtle hover:text-memodex-text' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[9px] mt-0.5 font-medium">Home</span>
        </a>
        <a href="{{ route('pokedex.index') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('pokedex.*') ? 'text-memodex-primary' : 'text-memodex-subtle hover:text-memodex-text' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="text-[9px] mt-0.5 font-medium">Pokédex</span>
        </a>
        <a href="{{ route('cards.create') }}" class="flex flex-col items-center justify-center flex-1 h-full text-memodex-primary">
            <div class="w-8 h-8 rounded-full bg-memodex-primary/20 border border-memodex-primary flex items-center justify-center">
                <svg class="w-4 h-4 text-memodex-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
        </a>
        <a href="{{ route('albums.index') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('albums.*') ? 'text-memodex-primary' : 'text-memodex-subtle hover:text-memodex-text' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span class="text-[9px] mt-0.5 font-medium">Binder</span>
        </a>
        <a href="{{ route('settings.index') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('settings.*') ? 'text-memodex-primary' : 'text-memodex-subtle hover:text-memodex-text' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="text-[9px] mt-0.5 font-medium">More</span>
        </a>
    </nav>
</body>
</html>
