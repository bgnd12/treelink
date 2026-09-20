<nav x-data="{ open: false }" class="fixed top-0 inset-x-0 z-50 backdrop-blur-lg bg-white/70 border-b border-ink-100">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-lg text-ink-900">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                Tree<span class="text-brand-600">Link</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-ink-600">
                <a href="{{ route('home') }}#features" class="hover:text-ink-900 transition">Fitur</a>
                <a href="{{ route('home') }}#how-it-works" class="hover:text-ink-900 transition">Cara Kerja</a>
                <a href="{{ route('home') }}#faq" class="hover:text-ink-900 transition">FAQ</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('dashboard.index') }}"
                       class="px-4 py-2 rounded-full text-sm font-semibold bg-ink-900 text-white hover:bg-ink-800 transition">
                        Ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-ink-700 hover:text-ink-900 transition">Masuk</a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-full text-sm font-semibold bg-brand-600 text-white shadow-soft hover:bg-brand-700 transition">
                        Daftar Gratis
                    </a>
                @endauth
            </div>

            <button @click="open = !open" class="md:hidden p-2 text-ink-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="md:hidden pb-4 flex flex-col gap-3 text-sm font-medium text-ink-700">
            <a href="{{ route('home') }}#features" class="py-1">Fitur</a>
            <a href="{{ route('home') }}#how-it-works" class="py-1">Cara Kerja</a>
            <a href="{{ route('home') }}#faq" class="py-1">FAQ</a>
            <div class="flex gap-3 pt-2">
                @auth
                    <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('dashboard.index') }}" class="px-4 py-2 rounded-full bg-ink-900 text-white text-center flex-1">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-ink-200 text-center flex-1">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-brand-600 text-white text-center flex-1">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>