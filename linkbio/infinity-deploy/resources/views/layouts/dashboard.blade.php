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
<body class="dashboard-shell font-sans antialiased bg-[#f8f9f7] text-[#14213d]" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 inset-y-0 left-0 z-40 w-64 bg-white border-r border-ink-100 flex flex-col transition-transform duration-300 h-screen">
            <div class="h-16 flex items-center px-6 border-b border-ink-100">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2.5 font-extrabold text-lg text-ink-900">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#c8ff4d] text-sm font-black text-[#1e2a5b]">T</span>
                    TreeLink
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'dashboard.index', 'label' => __('My TreeLink'), 'icon' => '🧭'],
                        ['route' => 'dashboard.links.index', 'label' => __('Links'), 'icon' => '🔗'],
                        ['route' => 'dashboard.shop', 'label' => __('Shop'), 'icon' => '🛍️'],
                        ['route' => 'dashboard.marketplace', 'label' => __('Jelajahi Shop'), 'icon' => '⌕', 'nested' => true],
                        ['label' => __('LinkID'), 'icon' => '🤝', 'section' => true],
                        ['route' => 'dashboard.linkid.discover', 'label' => __('Discover'), 'icon' => '↗', 'nested' => true],
                        ['route' => 'dashboard.linkid.my-collaboration', 'label' => __('My Collaboration'), 'icon' => '✓', 'nested' => true],
                        ['route' => 'dashboard.linkid.requests', 'label' => __('Requests'), 'icon' => '✉', 'nested' => true],
                        ['route' => 'dashboard.linkid.messages', 'label' => __('Messages'), 'icon' => '💬', 'nested' => true],
                        ['route' => 'dashboard.analytics.index', 'label' => __('Analytics'), 'icon' => '📈'],
                        ['route' => 'dashboard.settings.index', 'label' => __('Settings'), 'icon' => '⚙️'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @if (!empty($item['section']))
                        <div class="px-3 pt-4 pb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-ink-400">
                            {{ $item['label'] }}
                        </div>
                        @continue
                    @endif

                    <a href="{{ !empty($item['route']) ? route($item['route']) : '#' }}"
                       aria-current="{{ !empty($item['route']) && request()->routeIs($item['route']) ? 'page' : 'false' }}"
                       class="flex items-center gap-3 border-l-2 px-4 py-2.5 rounded-r-xl text-sm font-semibold transition {{ !empty($item['route']) && request()->routeIs($item['route']) ? 'border-[#c8ff4d] bg-[#f1f3e9] text-[#1e2a5b]' : 'border-transparent text-ink-600 hover:bg-ink-50 hover:text-ink-900' }} {{ !empty($item['nested']) ? 'ml-3' : '' }}">
                        @if($item['icon'])<span class="text-base {{ !empty($item['nested']) ? 'text-ink-500' : '' }}">{{ $item['icon'] }}</span>@endif
                        <span class="flex-1">{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="min-w-5 h-5 px-1.5 rounded-full bg-brand-600 text-[10px] font-bold text-white flex items-center justify-center">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach

                @if (auth()->user()->is_admin)
                    <div class="pt-4 mt-4 border-t border-ink-100">
                        <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition">
                            <span class="text-base">🛡️</span> Admin Panel
                        </a>
                    </div>
                @endif
            </nav>

            <div class="p-4 border-t border-ink-100">
                <a href="{{ auth()->user()->publicUrl() }}" target="_blank" class="flex items-center gap-3 px-3 py-3 rounded-xl bg-[#f6f7f3] hover:bg-[#edf0e7] transition text-sm font-semibold text-ink-700">
                    <img src="{{ auth()->user()->getOrCreateProfile()->avatar_url }}" class="w-8 h-8 rounded-full object-cover" alt="Avatar">
                    <div class="flex-1 min-w-0">
                        <p class="truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-ink-400 truncate">/{{ auth()->user()->username }}</p>
                    </div>
                    <span>↗</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                        🚪 Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div @click="sidebarOpen = false" x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-black/30 z-30 lg:hidden"></div>

        {{-- Main content --}}
        <div class="flex-1 min-w-0 flex flex-col">
            <header class="h-16 bg-white border-b border-ink-100 flex items-center justify-between px-5 sm:px-8 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 text-ink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="font-bold text-lg text-ink-900">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 border border-ink-200 rounded-full px-2 py-1 mr-2 text-xs font-semibold">
                        <a href="{{ route('locale.set', 'id') }}" class="px-2 py-1 rounded-full {{ App::getLocale() === 'id' ? 'bg-ink-100 text-ink-900' : 'text-ink-500 hover:text-ink-900' }}">ID</a>
                        <a href="{{ route('locale.set', 'en') }}" class="px-2 py-1 rounded-full {{ App::getLocale() === 'en' ? 'bg-ink-100 text-ink-900' : 'text-ink-500 hover:text-ink-900' }}">EN</a>
                    </div>
                    <div class="relative" x-data="{ openAccount: false }">
                        <button type="button" @click="openAccount = !openAccount" class="flex items-center gap-3 rounded-full border border-ink-200 bg-ink-50 px-2.5 py-1.5 text-left transition hover:border-ink-300">
                            <img src="{{ auth()->user()->getOrCreateProfile()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-9 w-9 rounded-full object-cover">
                            <span class="hidden sm:block">
                                <span class="block text-sm font-semibold text-ink-900">{{ auth()->user()->name }}</span>
                                <span class="block text-[11px] text-ink-500">{{ auth()->user()->username }}</span>
                            </span>
                            <span class="text-ink-500">▾</span>
                        </button>

                        <div x-show="openAccount" @click.outside="openAccount = false" x-cloak class="absolute right-0 mt-3 w-56 rounded-2xl border border-ink-200 bg-white p-2 shadow-xl">
                            <a href="{{ route('dashboard.profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">Profile</a>
                            <a href="{{ route('dashboard.settings.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">Account Settings</a>
                            <a href="{{ route('dashboard.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">My TreeLink</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-ink-100 pt-2">
                                @csrf
                                <button type="submit" class="block w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Logout</button>
                            </form>
                        </div>
                    </div>
                    <button type="button" onclick="copyProfileUrl()"
                            class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-full border border-ink-200 text-sm font-semibold text-ink-700 hover:border-ink-400 transition">
                        <span id="copy-icon">🔗</span>
                        <span id="copy-label">{{ __('Copy Profile URL') }}</span>
                    </button>
                          <a href="{{ auth()->user()->publicUrl() }}" target="_blank"
                              class="px-4 py-2 rounded-full bg-[#c8ff4d] text-[#1e2a5b] text-sm font-bold hover:bg-[#ddff91] transition">
                        {{ __('View Page') }}
                    </a>
                </div>
            </header>

            <main class="flex-1 p-5 sm:p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium animate-fade-up">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
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

    <script>
        function copyProfileUrl() {
            const url = @json(auth()->user()->publicUrl());
            navigator.clipboard.writeText(url).then(() => {
                document.getElementById('copy-icon').textContent = '✅';
                document.getElementById('copy-label').textContent = 'Tersalin!';
                setTimeout(() => {
                    document.getElementById('copy-icon').textContent = '🔗';
                    document.getElementById('copy-label').textContent = 'Salin URL Profil';
                }, 2000);
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
