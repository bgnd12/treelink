<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editor &mdash; {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-ink-50 text-ink-900">

    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-ink-100">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-3">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 font-extrabold text-lg text-ink-900 shrink-0">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                    <span class="hidden sm:inline">TreeLink</span>
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" x-data="{ copied: false }" @click="navigator.clipboard.writeText(@js($user->publicUrl())).then(() => { copied = true; setTimeout(() => copied = false, 1800); })"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-ink-200 text-sm font-semibold text-ink-700 hover:border-brand-400 hover:text-brand-700 transition">
                        <span x-text="copied ? '✓' : '🔗'"></span>
                        <span class="hidden sm:inline" x-text="copied ? 'Tersalin!' : 'Salin URL'"></span>
                    </button>
                    <a href="{{ $user->publicUrl() }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition shadow-soft">
                        <span class="hidden sm:inline">Lihat Halaman</span>
                        <span class="sm:hidden">↗</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="ml-1">
                        @csrf
                        <button type="submit" title="Keluar"
                                class="flex items-center justify-center w-9 h-9 rounded-full text-ink-500 hover:bg-ink-100 hover:text-rose-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        @if (session('status'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-700 text-sm font-medium border border-emerald-100">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>