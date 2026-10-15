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
                        ['route' => 'dashboard.links.index', 'label' => __('Links'), 'icon' => 'link'],
                        ['route' => 'dashboard.shop', 'label' => __('Shop'), 'icon' => 'shop'],
                        ['route' => 'dashboard.orders.index', 'label' => __('Orders'), 'icon' => 'orders'],
                        ['route' => 'dashboard.purchases.index', 'label' => __('My Purchases'), 'icon' => 'cart'],
                        ['label' => __('LINKED'), 'section' => true],
                        ['route' => 'dashboard.linkid.messages', 'label' => __('Messages'), 'icon' => 'messages', 'badge' => $unreadMessageCount],
                        ['route' => 'dashboard.analytics.index', 'label' => __('Analytics'), 'icon' => 'analytics'],
                        ['route' => 'dashboard.settings.index', 'label' => __('Settings'), 'icon' => 'settings'],
                    ];
                @endphp

                @foreach ($navItems as $item)
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
                        <p class="font-bold text-xs text-[#14213d] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">@<span>{{ auth()->user()->username }}</span></p>
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
            <header class="h-20 bg-transparent flex items-center justify-between px-6 sm:px-10 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 text-slate-600 hover:text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="font-black text-2xl text-[#14213d]">@yield('page-title', 'Edit Profil')</h1>
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
                                <span class="block text-xs font-bold text-[#14213d] leading-tight">{{ auth()->user()->name }}</span>
                                <span class="block text-[10px] text-slate-400 leading-tight">@<span>{{ auth()->user()->username }}</span></span>
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="openAccount" @click.outside="openAccount = false" x-cloak class="absolute right-0 mt-3 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl z-50">
                            <a href="{{ route('dashboard.profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Profile</a>
                            <a href="{{ route('dashboard.settings.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Account Settings</a>
                            <a href="{{ route('dashboard.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">My TreeLink</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-2">
                                @csrf
                                <button type="submit" class="block w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Logout</button>
                            </form>
                        </div>
                    </div>

                    {{-- Copy Profile URL --}}
                    <button type="button" onclick="copyProfileUrl()"
                            class="hidden sm:flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-200/90 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span id="copy-label">{{ __('Copy Profile URL') }}</span>
                    </button>

                    {{-- View Page button --}}
                    <a href="{{ auth()->user()->publicUrl() }}" target="_blank"
                       class="px-5 py-2.5 rounded-full bg-[#c8ff4d] hover:bg-[#bcf040] text-[#14213d] text-sm font-extrabold transition flex items-center gap-2 shadow-2xs">
                        <span>{{ __('View Page') }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
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
