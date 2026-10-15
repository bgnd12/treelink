@php($shopCartCount = app(\App\Support\Cart::class)->count())

<nav class="-mx-5 mb-6 border-b border-slate-200 bg-white/70 px-5 backdrop-blur sm:-mx-8 sm:px-8" aria-label="Navigasi Shop">
    <div class="mx-auto flex h-14 max-w-[1440px] items-center justify-between gap-3">
        <div class="flex min-w-0 items-center gap-1 overflow-x-auto">
            <span class="mr-2 hidden shrink-0 items-center gap-2 text-sm font-extrabold text-[#14213d] sm:flex">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#c8ff4d]">
                    <svg class="h-4 w-4 text-[#4d7c0f]" viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
                </span>
                TreeLink Shop
            </span>
            <a href="{{ route('dashboard.shop') }}"
               class="shrink-0 rounded-full px-3.5 py-1.5 text-sm font-bold transition {{ request()->routeIs('dashboard.shop', 'dashboard.products.*') ? 'bg-[#1e2a5b] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Produk Saya
            </a>
            <a href="{{ route('dashboard.marketplace') }}"
               class="shrink-0 rounded-full px-3.5 py-1.5 text-sm font-bold transition {{ request()->routeIs('dashboard.marketplace') ? 'bg-[#1e2a5b] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Jelajahi Shop
            </a>
            <a href="{{ route('dashboard.orders.index') }}"
               class="shrink-0 rounded-full px-3.5 py-1.5 text-sm font-bold transition {{ request()->routeIs('dashboard.orders.*') ? 'bg-[#1e2a5b] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Pesanan
            </a>
            <a href="{{ route('dashboard.purchases.index') }}"
               class="shrink-0 rounded-full px-3.5 py-1.5 text-sm font-bold transition {{ request()->routeIs('dashboard.purchases.*') ? 'bg-[#1e2a5b] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Pembelian
            </a>
        </div>

        <a href="{{ route('cart.index') }}"
           class="relative inline-flex shrink-0 items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="hidden sm:inline">Keranjang</span>
            @if ($shopCartCount > 0)
                <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-[#c8ff4d] px-1.5 text-[10px] font-extrabold text-[#14213d]">{{ $shopCartCount > 99 ? '99+' : $shopCartCount }}</span>
            @endif
        </a>
    </div>
</nav>
