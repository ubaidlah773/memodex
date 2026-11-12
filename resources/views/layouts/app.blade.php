<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MEMODEX — Personal Trading Card Collection' }}</title>

    <!-- Fonts: Plus Jakarta Sans (Display), Inter (UI), JetBrains Mono (Serials) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#101827] text-[#F3F5F8] font-sans antialiased min-h-screen flex flex-col lg:flex-row selection:bg-[#69AFFF] selection:text-[#101827]"
      x-data="{
          mobileSheetOpen: false,
          toast: null,
          showToast(message, type = 'success') {
              this.toast = { message, type };
              setTimeout(() => { if (this.toast?.message === message) this.toast = null; }, 3800);
          }
      }"
      @memodex-toast.window="showToast($event.detail.message, $event.detail.type || 'success')">

    <!-- DESKTOP REFINED SIDEBAR (1024px+) -->
    <aside class="hidden lg:flex lg:w-60 xl:w-64 flex-col bg-[#17243A] border-r border-[#C8D2DF]/[0.08] flex-shrink-0 h-screen sticky top-0 z-30">
        <!-- Brand Identity -->
        <div class="h-16 flex items-center px-5 border-b border-[#C8D2DF]/[0.08]">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <x-brand-mark />
                <div>
                    <span class="font-display font-bold tracking-[0.16em] text-sm text-[#F3F5F8] group-hover:text-[#69AFFF] transition-colors block leading-none">MEMODEX</span>
                    <span class="block text-[11px] text-[#6D7A8D] mt-1 tracking-wide">Card Archive</span>
                </div>
            </a>
        </div>

        <!-- Primary Navigation -->
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto" aria-label="Main Navigation">
            @php
                $navItem = function(bool $active) {
                    return $active
                        ? 'relative flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-[#F3F5F8] bg-[#263449]/80 before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:rounded-r before:bg-[#69AFFF]'
                        : 'flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-[#9DAABD] hover:text-[#F3F5F8] hover:bg-[#1D293B]/70 transition-colors';
                };
            @endphp

            <a href="{{ route('dashboard') }}" class="{{ $navItem(request()->routeIs('dashboard')) }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-[#69AFFF]' : 'text-[#6D7A8D]' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Home</span>
            </a>

            <a href="{{ route('pokedex.index') }}" class="{{ $navItem(request()->routeIs('pokedex.*')) }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('pokedex.*') ? 'text-[#69AFFF]' : 'text-[#6D7A8D]' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                <span>Pokédex</span>
            </a>

            <a href="{{ route('photos.index') }}" class="{{ $navItem(request()->routeIs('photos.*')) }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('photos.*') ? 'text-[#69AFFF]' : 'text-[#6D7A8D]' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                <span>My Collection</span>
            </a>

            <a href="{{ route('albums.index') }}" class="{{ $navItem(request()->routeIs('albums.*')) }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('albums.*') ? 'text-[#69AFFF]' : 'text-[#6D7A8D]' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/></svg>
                <span>Albums</span>
            </a>

            <a href="{{ route('favorites.index') }}" class="{{ $navItem(request()->routeIs('favorites.*')) }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('favorites.*') ? 'text-[#D4B36A]' : 'text-[#6D7A8D]' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                <span>Favorites</span>
            </a>

            <div class="pt-4 mt-4 border-t border-[#C8D2DF]/[0.07] space-y-1">
                <a href="{{ route('cards.create') }}" class="{{ $navItem(request()->routeIs('cards.create')) }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('cards.create') ? 'text-[#69AFFF]' : 'text-[#6D7A8D]' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    <span>Upload</span>
                </a>

                <a href="{{ route('settings.index') }}" class="{{ $navItem(request()->routeIs('settings.*') || request()->routeIs('profile.*')) }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('settings.*') || request()->routeIs('profile.*') ? 'text-[#69AFFF]' : 'text-[#6D7A8D]' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Settings</span>
                </a>

                @if (auth()->user()?->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="{{ $navItem(request()->routeIs('admin.*')) }}">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.*') ? 'text-[#D4B36A]' : 'text-[#D4B36A]/75' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        <span>Admin Center</span>
                    </a>
                @endif
            </div>
        </nav>

        <!-- Storage & Collector Profile Footer -->
        @auth
        <div class="px-4 py-3.5 border-t border-[#C8D2DF]/[0.08] space-y-3 bg-[#131E31]/60">
            <!-- Minimal Inline Storage Telemetry -->
            <div>
                <div class="flex items-center justify-between text-[11px] text-[#9DAABD] mb-1.5">
                    <span>Storage</span>
                    <span class="font-mono tabular-nums text-[#6D7A8D]">{{ round(Auth::user()->storage_used_bytes / (1024 * 1024), 1) }} / {{ round(Auth::user()->storage_quota_bytes / (1024 * 1024), 0) }} MB</span>
                </div>
                <div class="w-full bg-[#101827] rounded-full h-1 overflow-hidden">
                    <div class="bg-[#69AFFF] h-1 rounded-full transition-all duration-300" style="width: {{ min(100, Auth::user()->storagePercent()) }}%"></div>
                </div>
            </div>

            <!-- Profile Row -->
            <div class="flex items-center justify-between gap-2 pt-1">
                <a href="{{ route('settings.index') }}" class="flex items-center gap-2.5 min-w-0 group">
                    <div class="w-8 h-8 rounded-full bg-[#263449] border border-[#C8D2DF]/20 flex items-center justify-center text-xs font-semibold text-[#F3F5F8] flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-[#F3F5F8] group-hover:text-[#69AFFF] truncate transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-[#6D7A8D] truncate font-mono">@<span>{{ Auth::user()->username ?: 'collector' }}</span></p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-[#6D7A8D] hover:text-[#EB6F75] rounded-lg hover:bg-[#1D293B] transition-colors" title="Sign out" aria-label="Sign out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    <!-- TABLET & MOBILE HEADER (< 1024px) -->
    <header class="lg:hidden h-14 bg-[#17243A]/95 backdrop-blur-md border-b border-[#C8D2DF]/[0.08] flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <x-brand-mark size="sm" />
            <span class="font-display font-bold tracking-[0.16em] text-sm text-[#F3F5F8]">MEMODEX</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('favorites.index') }}" class="p-2 rounded-lg text-[#9DAABD] hover:text-[#D4B36A] hover:bg-[#1D293B] transition-colors" aria-label="Favorites">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
            </a>
            <button type="button" @click="mobileSheetOpen = true" class="p-2 rounded-lg text-[#9DAABD] hover:text-[#F3F5F8] hover:bg-[#1D293B] transition-colors" aria-label="Open menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div>
    </header>

    <!-- MAIN CONTENT WORKSPACE -->
    <div class="flex-1 flex flex-col min-w-0 pb-20 lg:pb-0">
        <!-- Server Flash Notifications -->
        @if (session('success'))
            <div class="bg-[#5CC99B]/10 border-b border-[#5CC99B]/25 px-4 sm:px-8 py-3 text-sm text-[#5CC99B] flex items-center justify-between gap-3" x-data="{ open: true }" x-show="open">
                <span>{{ session('success') }}</span>
                <button type="button" @click="open = false" class="text-[#5CC99B]/80 hover:text-[#5CC99B] text-lg leading-none">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-[#EB6F75]/10 border-b border-[#EB6F75]/25 px-4 sm:px-8 py-3 text-sm text-[#EB6F75] flex items-center justify-between gap-3" x-data="{ open: true }" x-show="open">
                <span>{{ session('error') }}</span>
                <button type="button" @click="open = false" class="text-[#EB6F75]/80 hover:text-[#EB6F75] text-lg leading-none">&times;</button>
            </div>
        @endif
        @if (session('warning'))
            <div class="bg-[#E5B567]/10 border-b border-[#E5B567]/25 px-4 sm:px-8 py-3 text-sm text-[#E5B567] flex items-center justify-between gap-3" x-data="{ open: true }" x-show="open">
                <span>{!! session('warning') !!}</span>
                <button type="button" @click="open = false" class="text-[#E5B567]/80 hover:text-[#E5B567] text-lg leading-none">&times;</button>
            </div>
        @endif

        <!-- Dynamic Client Toast -->
        <div x-show="toast" x-cloak x-transition
             class="fixed top-4 right-4 z-50 max-w-sm px-4 py-3 rounded-xl border shadow-2xl text-sm font-medium flex items-center gap-3"
             :class="toast?.type === 'error' ? 'bg-[#1D293B] border-[#EB6F75]/40 text-[#EB6F75]' : 'bg-[#1D293B] border-[#5CC99B]/40 text-[#5CC99B]'">
            <span x-text="toast?.message"></span>
            <button type="button" @click="toast = null" class="opacity-70 hover:opacity-100 text-base leading-none">&times;</button>
        </div>

        <!-- Dynamic View Canvas -->
        <main class="flex-1 w-full max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-10 py-6 sm:py-8 lg:py-10">
            {{ $slot }}
        </main>
    </div>

    <!-- TABLET & MOBILE BOTTOM NAVIGATION (< 1024px) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 h-16 bg-[#17243A]/95 backdrop-blur-md border-t border-[#C8D2DF]/[0.08] z-30 flex items-center justify-around px-2" aria-label="Mobile Navigation">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center flex-1 h-full min-h-[44px] {{ request()->routeIs('dashboard') ? 'text-[#69AFFF]' : 'text-[#6D7A8D] hover:text-[#F3F5F8]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            <span class="text-[11px] mt-0.5 font-medium">Home</span>
        </a>
        <a href="{{ route('pokedex.index') }}" class="flex flex-col items-center justify-center flex-1 h-full min-h-[44px] {{ request()->routeIs('pokedex.*') ? 'text-[#69AFFF]' : 'text-[#6D7A8D] hover:text-[#F3F5F8]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            <span class="text-[11px] mt-0.5 font-medium">Pokédex</span>
        </a>
        <a href="{{ route('cards.create') }}" class="flex flex-col items-center justify-center flex-1 h-full min-h-[44px]" aria-label="Upload Card">
            <div class="w-10 h-10 rounded-full bg-[#69AFFF] text-[#101827] flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            </div>
        </a>
        <a href="{{ route('albums.index') }}" class="flex flex-col items-center justify-center flex-1 h-full min-h-[44px] {{ request()->routeIs('albums.*') ? 'text-[#69AFFF]' : 'text-[#6D7A8D] hover:text-[#F3F5F8]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/></svg>
            <span class="text-[11px] mt-0.5 font-medium">Albums</span>
        </a>
        <button type="button" @click="mobileSheetOpen = true" class="flex flex-col items-center justify-center flex-1 h-full min-h-[44px] {{ request()->routeIs('photos.*') || request()->routeIs('favorites.*') || request()->routeIs('settings.*') || request()->routeIs('admin.*') ? 'text-[#69AFFF]' : 'text-[#6D7A8D] hover:text-[#F3F5F8]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
            <span class="text-[11px] mt-0.5 font-medium">More</span>
        </button>
    </nav>

    <!-- MOBILE / TABLET DRAWER SHEET -->
    <div x-show="mobileSheetOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center">
        <div class="fixed inset-0 bg-[#070B14]/80 backdrop-blur-sm" @click="mobileSheetOpen = false"></div>
        <div class="relative z-10 w-full sm:max-w-md bg-[#17243A] border-t sm:border border-[#C8D2DF]/15 rounded-t-2xl sm:rounded-2xl p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#C8D2DF]/10">
                @auth
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#263449] border border-[#C8D2DF]/20 flex items-center justify-center text-sm font-semibold text-[#F3F5F8]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#F3F5F8]">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-[#6D7A8D] font-mono">@<span>{{ Auth::user()->username ?: 'collector' }}</span></p>
                    </div>
                </div>
                @endauth
                <button type="button" @click="mobileSheetOpen = false" class="p-2 text-[#9DAABD] hover:text-[#F3F5F8]">&times;</button>
            </div>

            <div class="grid grid-cols-2 gap-2.5 text-sm">
                <a href="{{ route('photos.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-[#1D293B] border border-[#C8D2DF]/10 text-[#F3F5F8]">
                    <svg class="w-4 h-4 text-[#69AFFF]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                    <span>My Collection</span>
                </a>
                <a href="{{ route('favorites.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-[#1D293B] border border-[#C8D2DF]/10 text-[#F3F5F8]">
                    <svg class="w-4 h-4 text-[#D4B36A]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    <span>Favorites</span>
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-[#1D293B] border border-[#C8D2DF]/10 text-[#F3F5F8]">
                    <svg class="w-4 h-4 text-[#9DAABD]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                    <span>Settings</span>
                </a>
                @if (auth()->user()?->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 p-3 rounded-xl bg-[#1D293B] border border-[#D4B36A]/30 text-[#D4B36A]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    <span>Admin Center</span>
                </a>
                @endif
            </div>

            @auth
            <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-[#C8D2DF]/10">
                @csrf
                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#EB6F75]/10 border border-[#EB6F75]/25 text-sm font-medium text-[#EB6F75]">
                    Sign Out
                </button>
            </form>
            @endauth
        </div>
    </div>
</body>
</html>
