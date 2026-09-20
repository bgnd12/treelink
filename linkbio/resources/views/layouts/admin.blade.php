<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') &mdash; {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-ink-50 text-ink-900" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 inset-y-0 left-0 z-40 w-72 bg-ink-950 text-white flex flex-col transition-transform duration-300 h-screen">
            <div class="h-16 flex items-center px-6 border-b border-white/10">
                <a href="{{ route('admin.index') }}" class="flex items-center gap-2 font-extrabold text-lg">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-sm">🛡️</span>
                    Admin Panel
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1">
                @php
                    $navItems = [
                        ['route' => 'admin.index', 'label' => 'Overview', 'icon' => '📊'],
                        ['route' => 'admin.users', 'label' => 'Semua User', 'icon' => '👥'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition
                       {{ request()->routeIs($item['route']) || request()->routeIs('admin.users.show') ? 'bg-white/10 text-white' : 'text-ink-300 hover:bg-white/5 hover:text-white' }}">
                        <span>{{ $item['icon'] }}</span> {{ $item['label'] }}
                    </a>
                @endforeach

                <div class="pt-4 mt-4 border-t border-white/10">
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-brand-300 hover:bg-white/5 transition">
                        <span>🔙</span> Kembali ke Dashboard
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-rose-300 hover:bg-white/5 transition">
                        🚪 Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div @click="sidebarOpen = false" x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-black/30 z-30 lg:hidden"></div>

        <div class="flex-1 min-w-0 flex flex-col">
            <header class="h-16 bg-white border-b border-ink-100 flex items-center justify-between px-5 sm:px-8 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 text-ink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="font-bold text-lg text-ink-900">@yield('page-title', 'Admin')</h1>
                </div>
                <span class="px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">Super Admin</span>
            </header>

            <main class="flex-1 p-5 sm:p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
