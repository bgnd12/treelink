<nav x-data="{ open: false, scrolled: false }"
    x-init="scrolled = window.scrollY > 20; window.addEventListener('scroll', () => scrolled = window.scrollY > 20, { passive: true })"
     class="fixed top-0 inset-x-0 z-50 transition-all duration-300 ease-out"
    :style="scrolled
        ? 'background-color: rgba(255,255,255,.96); border-color: #f1f5f9; box-shadow: 0 4px 14px rgba(15,23,42,.08); backdrop-filter: blur(16px)'
        : 'background-color: transparent; border-color: transparent; box-shadow: none; backdrop-filter: blur(0px)'"
    :class="scrolled ? 'border-b' : 'border-b border-transparent'">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
       <div class="flex items-center justify-between transition-all duration-300 ease-out"
           :class="scrolled ? 'py-3' : 'py-5'">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-lg transition-colors duration-300"
                    :style="scrolled ? 'color:#0f172a' : 'color:#ffffff'">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                TreeLink
            </a>

              <div class="hidden md:flex items-center gap-8 text-sm font-medium transition-colors duration-300"
                  :style="scrolled ? 'color:#475569' : 'color:rgba(255,255,255,.8)'">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition">Home</a>
                <a href="{{ route('home') }}#features" class="hover:text-brand-600 transition">Features</a>
                <a href="{{ route('home') }}#how-it-works" class="hover:text-brand-600 transition">About</a>
                <a href="{{ route('home') }}#contact" class="hover:text-brand-600 transition">Contact</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="flex items-center gap-1 rounded-full border border-current/20 px-1 py-1 text-[11px] font-extrabold">
                    <a href="{{ route('language.switch', 'en') }}" class="rounded-full px-2 py-1 transition {{ app()->getLocale() === 'en' ? 'bg-brand-500 text-white' : 'opacity-60 hover:opacity-100' }}">EN</a>
                    <a href="{{ route('language.switch', 'id') }}" class="rounded-full px-2 py-1 transition {{ app()->getLocale() === 'id' ? 'bg-brand-500 text-white' : 'opacity-60 hover:opacity-100' }}">ID</a>
                </div>
                @auth
                    <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('dashboard.index') }}"
                       class="px-4 py-2 rounded-full text-sm font-semibold bg-ink-900 text-white hover:bg-ink-800 transition">
                        Go to Dashboard
                    </a>
                @else
                          <a href="{{ route('login') }}" class="text-sm font-semibold transition-colors duration-300 hover:text-brand-600"
                              :style="scrolled ? 'color:#334155' : 'color:#ffffff'">Log in</a>
                    <a href="{{ route('register') }}"
                              class="px-4 py-2 rounded-full text-sm font-semibold bg-brand-600 text-white shadow-soft hover:bg-brand-700 transition">
                        Get Started
                    </a>
                @endauth
            </div>

                    <button @click="open = !open" class="md:hidden p-2 transition-colors duration-300"
                        :style="scrolled ? 'color:#334155' : 'color:#ffffff'">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="md:hidden pb-4 flex flex-col gap-3 text-sm font-medium text-ink-700">
            <a href="{{ route('home') }}" class="py-1">Home</a>
            <a href="{{ route('home') }}#features" class="py-1">Features</a>
            <a href="{{ route('home') }}#how-it-works" class="py-1">About</a>
            <a href="{{ route('home') }}#contact" class="py-1">Contact</a>
            <div class="flex gap-3 pt-2">
                <div class="flex items-center gap-1 rounded-full border border-ink-200 px-1 py-1 text-[11px] font-extrabold">
                    <a href="{{ route('language.switch', 'en') }}" class="rounded-full px-2 py-1 {{ app()->getLocale() === 'en' ? 'bg-brand-500 text-white' : 'text-ink-500' }}">EN</a>
                    <a href="{{ route('language.switch', 'id') }}" class="rounded-full px-2 py-1 {{ app()->getLocale() === 'id' ? 'bg-brand-500 text-white' : 'text-ink-500' }}">ID</a>
                </div>
                @auth
                    <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('dashboard.index') }}" class="px-4 py-2 rounded-full bg-ink-900 text-white text-center flex-1">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-ink-200 text-center flex-1">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-brand-600 text-white text-center flex-1">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
