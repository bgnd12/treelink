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
<body class="font-sans antialiased bg-ink-50 text-ink-900" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 inset-y-0 left-0 z-40 w-72 bg-white border-r border-ink-100 flex flex-col transition-transform duration-300 h-screen">
            <div class="h-16 flex items-center px-6 border-b border-ink-100">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 font-extrabold text-lg text-ink-900">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                    TreeLink
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'dashboard.index', 'label' => 'Editor', 'icon' => '✏️'],
                        ['route' => 'dashboard.analytics.index', 'label' => 'Analytics', 'icon' => '📈'],
                        ['route' => 'dashboard.settings.index', 'label' => 'Settings', 'icon' => '⚙️'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition
                       {{ request()->routeIs($item['route']) ? 'bg-brand-50 text-brand-700' : 'text-ink-600 hover:bg-ink-50 hover:text-ink-900' }}">
                        <span class="text-base">{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
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
                <a href="{{ auth()->user()->publicUrl() }}" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-ink-50 hover:bg-ink-100 transition text-sm font-semibold text-ink-700">
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
                    <button type="button" onclick="copyProfileUrl()"
                            class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-full border border-ink-200 text-sm font-semibold text-ink-700 hover:border-ink-400 transition">
                        <span id="copy-icon">🔗</span>
                        <span id="copy-label">Salin URL Profil</span>
                    </button>
                    <a href="{{ auth()->user()->publicUrl() }}" target="_blank"
                       class="px-4 py-2 rounded-full bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition">
                        Lihat Halaman
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
