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
<body class="min-h-screen bg-[#f7f5ff] text-slate-900 antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen lg:flex">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 flex h-screen w-[276px] -translate-x-full flex-col border-r border-[#eee7ff] bg-white/90 backdrop-blur-xl transition-transform duration-300 lg:sticky lg:translate-x-0">
            <div class="flex h-20 items-center justify-between border-b border-[#f0ebff] px-5">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 via-purple-500 to-indigo-600 text-base text-white shadow-lg shadow-violet-200">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 7c0-2.2 1.8-4 4-4s4 1.8 4 4c0 1.6-.9 3-2.2 3.7L13 14.5V18h3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 12.8 4 17l3.2-1.2 2.7 2.9M17 7.5c.2 0 .4 0 .6.1M12 12.5a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-xl font-extrabold tracking-[-0.04em] text-slate-900">TreeLink</div>
                </a>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
                @php
                    $navItems = [
                        ['route' => 'dashboard.index', 'label' => 'My TreeLink', 'icon' => 'home'],
                        ['route' => 'dashboard.links.index', 'label' => 'Links', 'icon' => 'links'],
                        ['route' => 'dashboard.linkid.discover', 'label' => 'LinkID', 'icon' => 'users', 'children' => [
                            ['route' => 'dashboard.linkid.discover', 'label' => 'Discover'],
                            ['route' => 'dashboard.linkid.collaborations', 'label' => 'My Collaboration'],
                            ['route' => 'dashboard.linkid.requests', 'label' => 'Requests'],
                            ['route' => 'dashboard.messages', 'label' => 'Messages', 'badge' => '3'],
                        ]],
                        ['route' => 'dashboard.shop.index', 'label' => 'Shop', 'icon' => 'shop'],
                        ['route' => 'dashboard.analytics.index', 'label' => 'Analytics', 'icon' => 'analytics'],
                        ['route' => 'dashboard.tools.index', 'label' => 'Tools', 'icon' => 'tools'],
                        ['route' => 'dashboard.settings.index', 'label' => 'Settings', 'icon' => 'settings'],
                    ];
                    $icons = [
                        'home' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-9.5Z"/></svg>',
                        'links' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 13.5 8 16a3 3 0 1 1-4.2-4.2l3.6-3.6a3 3 0 0 1 4.2 0M13.5 10.5 16 8a3 3 0 1 1 4.2 4.2l-3.6 3.6a3 3 0 0 1-4.2 0M8.5 15.5l7-7"/></svg>',
                        'users' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 19a6.5 6.5 0 0 0-9 0M12 12a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm8 7a6 6 0 0 0-3.5-5.4M16.5 7.5a3 3 0 1 1 0 6M4 19a6 6 0 0 1 3.5-5.4"/></svg>',
                        'shop' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8h18l-1.5 10.5A2 2 0 0 1 17.5 20h-11A2 2 0 0 1 4.5 18.5L3 8Zm4-2.5A5 5 0 0 1 17 5.5"/></svg>',
                        'analytics' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 18V6M10 18V10M16 18v-6M22 18V4"/></svg>',
                        'tools' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7v7h-2V6.4l-4.3 4.3-1.4-1.4L17.6 5H15V3Zm-9 9 4.3-4.3 1.4 1.4L6.4 13H9v2H3v-6h2v2.6Zm8 9h7v-7h-2v2.6l-4.3-4.3-1.4 1.4L17.6 19H15v2Zm-9-9 4.3 4.3 1.4-1.4L6.4 11H9V9H3v6h2v-2.6Z"/></svg>',
                        'settings' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 3.5a.8.8 0 0 1 .7.5l.5 1.3a7.2 7.2 0 0 1 1.8.9l1.3-.5a.8.8 0 0 1 1 .4l1 1.8a.8.8 0 0 1-.2.9l-1 .9a7.1 7.1 0 0 1 0 1.8l1 .9a.8.8 0 0 1 .2.9l-1 1.8a.8.8 0 0 1-1 .4l-1.3-.5a7.2 7.2 0 0 1-1.8.9l-.5 1.3a.8.8 0 0 1-.7.5h-2a.8.8 0 0 1-.7-.5l-.5-1.3a7.2 7.2 0 0 1-1.8-.9l-1.3.5a.8.8 0 0 1-1-.4l-1-1.8a.8.8 0 0 1 .2-.9l1-.9a7.1 7.1 0 0 1 0-1.8l-1-.9a.8.8 0 0 1-.2-.9l1-1.8a.8.8 0 0 1 1-.4l1.3.5a7.2 7.2 0 0 1 1.8-.9l.5-1.3a.8.8 0 0 1 .7-.5h2Zm0 6.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z"/></svg>',
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php
                        $hasChildren = !empty($item['children'] ?? []);
                        $isActive = $item['route'] && request()->routeIs($item['route']);
                    @endphp

                    @if ($hasChildren)
                        <div class="space-y-1">
                            <a href="{{ route($item['route']) }}" class="flex items-center justify-between gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold transition {{ $isActive ? 'bg-violet-50 text-violet-700 shadow-sm ring-1 ring-violet-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="flex items-center gap-3">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-xl {{ $isActive ? 'bg-white text-violet-600' : 'bg-[#f5f3ff] text-slate-500' }}">
                                        {!! $icons[$item['icon']] !!}
                                    </span>
                                    {{ $item['label'] }}
                                </span>
                            </a>
                            <div class="ml-8 space-y-1">
                                @foreach ($item['children'] as $child)
                                    @php $childActive = request()->routeIs($child['route']); @endphp
                                    <a href="{{ route($child['route']) }}" class="flex items-center justify-between gap-2 rounded-xl px-2.5 py-2 text-xs font-medium transition {{ $childActive ? 'bg-violet-50 text-violet-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                        <span>{{ $child['label'] }}</span>
                                        @if (!empty($child['badge']))
                                            <span class="inline-flex min-w-5 items-center justify-center rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $child['badge'] }}</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold transition {{ $isActive ? 'bg-violet-50 text-violet-700 shadow-sm ring-1 ring-violet-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl {{ $isActive ? 'bg-white text-violet-600' : 'bg-[#f5f3ff] text-slate-500' }}">
                                {!! $icons[$item['icon']] !!}
                            </span>
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="border-t border-[#f0ebff] p-4">
                <div class="flex items-center gap-3 rounded-2xl bg-[#f7f4ff] p-3 ring-1 ring-[#efe9ff]">
                    <img src="{{ auth()->user()->getOrCreateProfile()->avatar_url ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80' }}" alt="Profile" class="h-10 w-10 rounded-full object-cover ring-2 ring-white" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="truncate text-[11px] text-slate-500">@ {{ auth()->user()->username }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl border border-[#f0e4f7] bg-white px-3 py-2.5 text-sm font-semibold text-rose-500 transition hover:bg-rose-50">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M18 15l3-3m0 0-3-3m3 3H9"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div @click="sidebarOpen = false" x-show="sidebarOpen" x-cloak class="fixed inset-0 z-30 bg-slate-900/35 lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 border-b border-[#ede7ff] bg-white/80 backdrop-blur-xl">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#eee7ff] bg-white text-slate-600 lg:hidden">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
                            </svg>
                        </button>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">Dashboard</p>
                            <h1 class="truncate text-xl font-extrabold tracking-[-0.05em] text-slate-900">@yield('page-title', 'Good morning, Athaya 👋')</h1>
                        </div>
                    </div>

                    <div class="hidden items-center gap-3 md:flex">
                        <div class="flex items-center gap-2 rounded-2xl border border-[#ece6ff] bg-[#f9f7ff] px-3 py-2 text-sm text-slate-500">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="6"/>
                                <path stroke-linecap="round" d="m16 16 4 4"/>
                            </svg>
                            Cari sesuatu...
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <button class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#ece6ff] bg-white text-slate-600 transition hover:border-violet-200 hover:text-violet-600">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 18.5h-5m9.5-6.5V10a7 7 0 1 0-14 0v2.5l-1.8 4.5h17.6l-1.8-4.5Zm-8.5 8.5a2.5 2.5 0 0 0 5 0"/>
                            </svg>
                        </button>
                        <button class="rounded-2xl border border-[#ece6ff] bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:border-violet-200 hover:text-violet-600">Preview</button>
                        <button class="rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:shadow-violet-300">Save Changes</button>
                        <div class="flex items-center gap-2 rounded-2xl border border-[#ece6ff] bg-white px-2 py-1.5">
                            <img src="{{ auth()->user()->getOrCreateProfile()->avatar_url ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80' }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover" />
                            <div class="hidden text-left sm:block">
                                <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-slate-500">Free Plan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                        <ul class="list-disc space-y-1 pl-5">
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
            navigator.clipboard.writeText(url).catch(() => {});
        }
    </script>
    @yield('scripts')
</body>
</html>