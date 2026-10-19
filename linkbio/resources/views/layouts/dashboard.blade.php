<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &mdash; {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $activePage = auth()->user()->currentPage();
@endphp
<body class="dashboard-shell font-sans antialiased bg-[#f8f9f7] text-[#14213d] transition-colors duration-300"
      x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('treelink-dashboard-theme') === 'dark' }"
      :class="{ 'dashboard-dark': darkMode }">

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-100 flex flex-col transition-transform duration-300 h-screen">
            <div class="h-20 flex items-center px-6">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 font-extrabold text-xl text-[#14213d] tracking-tight">
                    <div class="w-8 h-8 rounded-full bg-[#c8ff4d] flex items-center justify-center text-[#14213d] shadow-xs">
                        <svg class="w-5 h-5 text-[#4d7c0f]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/>
                        </svg>
                    </div>
                    <span>TreeLink</span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
                @php
                    $unreadMessageCount = app(\App\Support\UnreadMessages::class)->for(auth()->user())['count'];
                    $navItems = [
                        ['route' => 'dashboard.index', 'label' => __('My TreeLink'), 'icon' => 'home'],
                        ['route' => 'dashboard.shop', 'label' => __('Toko'), 'icon' => 'shop'],
                        ['route' => 'dashboard.orders.index', 'label' => __('Pesanan & Penghasilan'), 'icon' => 'orders'],
                        ['route' => 'dashboard.accounts.index', 'label' => __('Rekening'), 'icon' => 'bank'],
                        ['route' => 'dashboard.purchases.index', 'label' => __('Pembelian Saya'), 'icon' => 'cart'],
                        ['label' => __('PESAN TERHUBUNG'), 'section' => true],
                        ['route' => 'dashboard.linkid.messages', 'label' => __('Pesan'), 'icon' => 'messages', 'badge' => $unreadMessageCount],
                        ['route' => 'dashboard.analytics.index', 'label' => __('Analitik'), 'icon' => 'analytics'],
                    ];
                @endphp

                @foreach (($navItems ?? []) as $item)
                    @if (!empty($item['section']))
                        <div class="px-4 pt-5 pb-2 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">
                            {{ $item['label'] }}
                        </div>
                        @continue
                    @endif

                    @php
                        $isActive = !empty($item['route']) && request()->routeIs($item['route']);
                    @endphp

                    <a href="{{ !empty($item['route']) ? route($item['route']) : '#' }}"
                       aria-current="{{ $isActive ? 'page' : 'false' }}"
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition {{ $isActive ? 'bg-[#edf8dd] text-[#14213d] font-bold shadow-2xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        
                        <span class="w-5 h-5 flex items-center justify-center flex-shrink-0 {{ $isActive ? 'text-[#14213d]' : 'text-slate-500' }}">
                            @if($item['icon'] === 'home')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @elseif($item['icon'] === 'link')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            @elseif($item['icon'] === 'shop')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @elseif($item['icon'] === 'orders')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            @elseif($item['icon'] === 'bank')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M3 21h18M12 3l9 5H3l9-5z"/></svg>
                            @elseif($item['icon'] === 'cart')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @elseif($item['icon'] === 'messages')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            @elseif($item['icon'] === 'analytics')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            @elseif($item['icon'] === 'settings')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @endif
                        </span>

                        <span class="flex-1">{{ $item['label'] }}</span>

                        @if(array_key_exists('badge', $item))
                            <span id="messages-unread-badge" class="min-w-5 h-5 px-1.5 rounded-full bg-[#c8ff4d] text-[10px] font-extrabold text-[#14213d] flex items-center justify-center shadow-2xs" @if($item['badge'] < 1) hidden @endif>{{ $item['badge'] > 99 ? '99+' : $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach

                @if (auth()->user()->is_admin)
                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition">
                            <span class="text-base">🛡️</span> Admin Panel
                        </a>
                    </div>
                @endif
            </nav>

            {{-- Sidebar Footer Cards --}}
            <div class="p-4 border-t border-slate-100 space-y-2.5 bg-white">
                <a href="{{ route('dashboard.profile.edit') }}" class="group flex items-center gap-3 p-2.5 rounded-2xl bg-[#f8fafc] border border-slate-100 hover:border-slate-200 transition">
                    <img src="{{ auth()->user()->getOrCreateProfile()->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="Avatar">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-xs text-[#14213d] truncate">{{ $activePage->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">@<span>{{ $activePage->username }}</span></p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-2xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-bold text-[#14213d]">Premium Plan</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">Semua fitur tersedia</p>
                </div>
            </div>
        </aside>

        <div @click="sidebarOpen = false" x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-black/30 z-30 lg:hidden"></div>

        {{-- Main content --}}
        <div class="flex-1 min-w-0 flex flex-col bg-[#f8f9f7]">
            <header class="min-h-20 h-auto bg-[#f8f9f7]/95 backdrop-blur-md border-b border-slate-200/70 flex items-center justify-between gap-4 px-6 py-4 sm:px-10 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 text-slate-600 hover:text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="min-w-0 max-w-[55vw] truncate font-black text-xl sm:text-2xl text-[#14213d] whitespace-nowrap">@yield('page-title', 'Edit Profil')</h1>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Language pill --}}
                    <div class="hidden md:flex items-center border border-slate-200/90 rounded-full p-1 bg-white shadow-2xs text-xs font-bold mr-1">
                        <a href="{{ route('locale.set', 'id') }}" class="px-3 py-1 rounded-full {{ App::getLocale() === 'id' ? 'bg-slate-100 text-slate-900 font-extrabold' : 'text-slate-400 hover:text-slate-700' }}">ID</a>
                        <a href="{{ route('locale.set', 'en') }}" class="px-3 py-1 rounded-full {{ App::getLocale() === 'en' ? 'bg-slate-100 text-slate-900 font-extrabold' : 'text-slate-400 hover:text-slate-700' }}">EN</a>
                    </div>

                    {{-- User dropdown pill --}}
                    <div class="relative" x-data="{ openAccount: false }">
                        <button type="button" @click="openAccount = !openAccount" class="flex items-center gap-2.5 rounded-full border border-slate-200/90 bg-white px-2.5 py-1.5 text-left transition hover:border-slate-300 shadow-2xs">
                            <img src="{{ auth()->user()->getOrCreateProfile()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-8 w-8 rounded-full object-cover">
                            <span class="hidden sm:block">
                                <span class="block text-xs font-bold text-[#14213d] leading-tight">{{ $activePage->name }}</span>
                                <span class="block text-[10px] text-slate-400 leading-tight">@<span>{{ $activePage->username }}</span></span>
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="openAccount" @click.outside="openAccount = false" x-cloak class="absolute right-0 mt-3 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl z-50">
                            <a href="{{ route('dashboard.profile-pages.create') }}" class="flex items-center gap-2 rounded-xl px-3 py-3 text-sm font-extrabold text-[#4d7c0f] hover:bg-[#f4fae8]">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#edf8dd] text-lg font-light">+</span>
                                {{ __('Buat TreeLink Baru') }}
                            </a>
                            <div class="mt-2 border-t border-slate-100 pt-2">
                                <a href="{{ route('dashboard.settings.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Pengaturan Akun') }}</a>
                                <a href="{{ route('dashboard.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('My TreeLink') }}</a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-2">
                                    @csrf
                                    <button type="submit" class="block w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">{{ __('Logout') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Global dashboard light/dark mode --}}
                    <button type="button"
                            @click="darkMode = !darkMode; localStorage.setItem('treelink-dashboard-theme', darkMode ? 'dark' : 'light')"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200/90 bg-white px-3.5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs dark-mode-control"
                            :aria-label="darkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'">
                        <svg x-show="!darkMode" class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3l1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3l1.42-1.42"/></svg>
                        <svg x-show="darkMode" x-cloak class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        <span class="hidden md:inline" x-text="darkMode ? @js(__('Terang')) : @js(__('Gelap'))"></span>
                    </button>

                </div>
            </header>

            <main class="flex-1 p-5 pt-8 sm:p-8 sm:pt-10">
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium animate-fade-up">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any() && (! request()->routeIs('dashboard.profile-pages.create') || ! $errors->has('username')))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 text-rose-700 text-sm font-medium animate-fade-up">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div id="incoming-message-toast" class="fixed right-4 top-20 z-50 hidden w-[min(24rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl" role="status" aria-live="polite">
        <div class="flex items-start gap-3 p-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#f1f3e9] text-lg text-[#1e2a5b]">💬</span>
            <a id="incoming-message-link" href="{{ route('dashboard.linkid.messages') }}" class="min-w-0 flex-1">
                <span id="incoming-message-sender" class="block truncate text-sm font-bold text-slate-900">Pesan baru</span>
                <span id="incoming-message-preview" class="mt-1 block line-clamp-2 text-xs leading-5 text-slate-500"></span>
            </a>
            <button type="button" id="dismiss-message-toast" aria-label="Tutup notifikasi pesan" class="text-lg leading-none text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="h-1 bg-[#c8ff4d]"></div>
    </div>

    <style>
        @keyframes treelink-tree-sway {
            0%, 100% { transform: rotate(-4deg) translateY(0); }
            50% { transform: rotate(4deg) translateY(-4px); }
        }

        @keyframes treelink-leaf-spin {
            from { transform: rotate(0deg) translateX(31px) rotate(0deg); }
            to { transform: rotate(360deg) translateX(31px) rotate(-360deg); }
        }

        @keyframes treelink-loader-pulse {
            0%, 100% { opacity: .55; transform: scale(.88); }
            50% { opacity: 1; transform: scale(1.12); }
        }

        .treelink-tree-sway { transform-origin: 50% 90%; animation: treelink-tree-sway 1.4s ease-in-out infinite; }
        .treelink-leaf-orbit { transform-origin: 50% 50%; animation: treelink-leaf-spin 2.2s linear infinite; }
        .treelink-loader-dot { transform-origin: 50% 50%; animation: treelink-loader-pulse 1s ease-in-out infinite; }
        @media (prefers-reduced-motion: reduce) {
            .treelink-tree-sway, .treelink-leaf-orbit, .treelink-loader-dot { animation: none; }
        }
    </style>

    <div id="page-loading-overlay"
         class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/45 px-6 backdrop-blur-xl"
         role="status"
         aria-live="polite"
         aria-label="Memuat halaman">
        <div class="flex w-64 flex-col items-center justify-center rounded-3xl bg-white/95 px-8 py-8 text-center shadow-2xl ring-1 ring-white/70">
            <div class="relative h-24 w-24" aria-hidden="true">
                <svg class="treelink-tree-sway absolute inset-0 h-24 w-24" viewBox="0 0 96 96" fill="none">
                    <path d="M47 87c1-15 1-29 0-43" stroke="#5b3a29" stroke-width="7" stroke-linecap="round"/>
                    <path d="M48 63 35 51M48 54l14-15" stroke="#5b3a29" stroke-width="4" stroke-linecap="round"/>
                    <path d="M48 43c-8-15-25-14-30-3-4 9 4 17 14 16 7 0 12-4 16-13Z" fill="#8bd52f"/>
                    <path d="M49 39c4-17 22-22 31-12 7 8 1 19-9 21-8 2-15-1-22-9Z" fill="#c8ff4d"/>
                    <path d="M49 51c-3-12-14-19-23-14-8 5-5 16 4 20 7 3 14 1 19-6Z" fill="#4d9f36"/>
                    <path d="M47 32c-1-9 7-17 16-16 8 1 10 10 4 16-5 5-12 5-20 0Z" fill="#75c83c"/>
                </svg>
                <span class="treelink-leaf-orbit absolute left-1/2 top-1/2 h-3 w-3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#c8ff4d] shadow-[0_0_12px_#c8ff4d]"></span>
                <span class="treelink-loader-dot absolute bottom-1 left-1/2 h-2 w-2 -translate-x-1/2 rounded-full bg-[#879366]"></span>
            </div>
            <p class="mt-5 text-base font-extrabold text-[#14213d]">Memuat...</p>
            <p class="mt-1 text-xs text-slate-500">TreeLink sedang menyiapkan halaman</p>
        </div>
    </div>

    <script>
        (() => {
            const overlay = document.getElementById('page-loading-overlay');
            let isLoading = false;

            const showLoading = () => {
                if (!overlay || isLoading) return;
                isLoading = true;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                document.documentElement.setAttribute('aria-busy', 'true');
            };

            const hideLoading = () => {
                if (!overlay) return;
                isLoading = false;
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
                document.documentElement.removeAttribute('aria-busy');
            };

            document.addEventListener('submit', (event) => {
                const form = event.target;
                if (!(form instanceof HTMLFormElement) || form.dataset.noLoading !== undefined) return;

                if (isLoading) {
                    event.preventDefault();
                    return;
                }

                showLoading();
            });

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a');
                if (!link || link.dataset.noLoading !== undefined || link.target === '_blank' || link.hasAttribute('download')) return;
                if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

                let url;
                try {
                    url = new URL(href, window.location.href);
                } catch {
                    return;
                }

                if (url.origin !== window.location.origin) return;

                if (isLoading) {
                    event.preventDefault();
                    return;
                }

                showLoading();
            });

            // Restore the page when navigating back/forward from the browser cache.
            window.addEventListener('pageshow', hideLoading);
        })();

        function copyProfileUrl() {
            const url = @json($activePage->publicUrl());
            navigator.clipboard.writeText(url).then(() => {
                document.getElementById('copy-icon').textContent = '✅';
                document.getElementById('copy-label').textContent = 'Tersalin!';
                setTimeout(() => {
                    document.getElementById('copy-icon').textContent = '🔗';
                    document.getElementById('copy-label').textContent = 'Salin URL Profil';
                }, 2000);
            });
        }

        (() => {
            const badge = document.getElementById('messages-unread-badge');
            const toast = document.getElementById('incoming-message-toast');
            const senderLabel = document.getElementById('incoming-message-sender');
            const previewLabel = document.getElementById('incoming-message-preview');
            const messageLink = document.getElementById('incoming-message-link');
            const unreadUrl = @json(route('dashboard.linkid.messages.unread'));
            const threadUrl = @json(route('dashboard.linkid.messages.show', '__CONVERSATION__'));
            let unreadCount = @json($unreadMessageCount);
            let latestMessageId = null;
            let toastTimer;

            document.getElementById('dismiss-message-toast')?.addEventListener('click', () => toast?.classList.add('hidden'));

            async function refreshUnreadMessages() {
                if (document.visibilityState === 'hidden') return;

                try {
                    const response = await fetch(unreadUrl, {
                        headers: { Accept: 'application/json' },
                        credentials: 'same-origin',
                        cache: 'no-store',
                    });
                    if (!response.ok) return;

                    const data = await response.json();
                    const nextCount = Number(data.count || 0);

                    if (badge) {
                        badge.textContent = nextCount > 99 ? '99+' : String(nextCount);
                        badge.hidden = nextCount < 1;
                    }

                    if (data.latest && nextCount > unreadCount && String(data.latest.id) !== String(latestMessageId)) {
                        senderLabel.textContent = `Pesan baru dari ${data.latest.sender_name}`;
                        previewLabel.textContent = data.latest.preview;
                        messageLink.href = threadUrl.replace('__CONVERSATION__', encodeURIComponent(data.latest.conversation_id));
                        toast.classList.remove('hidden');
                        clearTimeout(toastTimer);
                        toastTimer = setTimeout(() => toast.classList.add('hidden'), 8000);
                    }

                    unreadCount = nextCount;
                    latestMessageId = data.latest?.id ?? latestMessageId;
                } catch (error) {
                    console.error('Gagal memeriksa pesan baru:', error);
                }
            }

            window.setInterval(refreshUnreadMessages, 10000);
            document.addEventListener('visibilitychange', refreshUnreadMessages);
            window.addEventListener('focus', refreshUnreadMessages);
        })();
    </script>
    @yield('scripts')
</body>
</html>
