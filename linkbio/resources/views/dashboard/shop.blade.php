@extends('layouts.dashboard')

@section('title', 'Shop')
@section('page-title', 'Shop')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-8">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Shop Saya</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola produkmu dan temukan produk kreator lain di satu tempat.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('cart.index') }}"
               class="relative inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-[#1e2a5b] shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:shadow-md">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Keranjang
                <?php $cartCount = app(\App\Support\Cart::class)->count(); ?>
                <?php if ($cartCount > 0): ?>
                    <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-[#c8ff4d] px-1.5 text-[10px] font-extrabold text-[#14213d]">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                <?php endif; ?>
            </a>
            <a href="{{ $hasBankAccount ? route('dashboard.products.create') : route('dashboard.accounts.index', ['from' => 'shop']) }}"
               class="inline-flex items-center gap-2 rounded-2xl bg-[#c8ff4d] px-5 py-3 text-sm font-bold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91] hover:shadow-md">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Produk
            </a>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
        <?php foreach ([
            [
                'label' => 'Total Produk',
                'value' => $totalProducts,
                'note'  => 'Semua produk yang kamu jual',
                'color' => 'bg-[#f1f3ff] text-[#4f46e5]',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
            ],
            [
                'label' => 'Produk Aktif',
                'value' => $activeProducts,
                'note'  => 'Produk yang sedang ditampilkan',
                'color' => 'bg-emerald-50 text-emerald-600',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ],
            [
                'label' => 'Draft',
                'value' => $draftProducts,
                'note'  => 'Produk yang belum dipublikasikan',
                'color' => 'bg-amber-50 text-amber-500',
                'icon'  => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            ],
            [
                'label' => 'Stok Habis',
                'value' => $outOfStockProducts,
                'note'  => 'Produk dengan stok habis',
                'color' => 'bg-rose-50 text-rose-500',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
            ],
        ] as $stat): ?>
            <div class="flex min-h-[7rem] items-start gap-3 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $stat['color'] }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $stat['icon'] !!}</svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-3xl font-extrabold leading-none text-slate-900">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-[11px] leading-4 text-slate-400">{{ $stat['note'] }}</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    {{-- ===== PRODUK SAYA ===== --}}
    <div>
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-extrabold text-slate-900">Produk Saya</h3>
            <?php if ($totalProducts > 4): ?>
                <a href="{{ route('dashboard.shop', ['filter' => 'all']) }}" class="text-sm font-semibold text-[#536239] hover:underline flex items-center gap-1">
                    Lihat semua
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            <?php endif; ?>
        </div>

        <?php if ($products->isEmpty()): ?>
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-14 text-center">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f1f3e9]">
                    <svg class="h-7 w-7 text-[#1e2a5b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">Shop kamu masih kosong</h4>
                <p class="mt-1 text-sm text-slate-500">Tambahkan produk pertama kamu sekarang.</p>
                <a href="{{ $hasBankAccount ? route('dashboard.products.create') : route('dashboard.accounts.index', ['from' => 'shop']) }}" class="mt-4 inline-flex rounded-xl bg-[#c8ff4d] px-5 py-2.5 text-sm font-bold text-[#1e2a5b] hover:bg-[#ddff91]">+ Tambah Produk</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-4 min-[420px]:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($products->take(8) as $product): ?>
                    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        {{-- Product image --}}
                        <a href="{{ route('dashboard.products.edit', $product) }}" class="relative block aspect-[4/3] w-full shrink-0 overflow-hidden border-b border-slate-100 bg-[#f8fafc]">
                            <?php if ($product->image_url): ?>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                     class="absolute inset-0 h-full w-full object-contain object-center p-2 transition duration-300 group-hover:scale-[1.04]">
                            <?php else: ?>
                                <div class="flex h-full w-full items-center justify-center text-4xl font-extrabold text-[#1e2a5b]/30">
                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                </div>
                            <?php endif; ?>
                            {{-- Status badge --}}
                            <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[10px] font-bold shadow-sm
                                {{ $product->is_active
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700' }}">
                                {{ $product->is_active ? '● Aktif' : '● Draft' }}
                            </span>
                        </a>

                        {{-- Product info --}}
                        <div class="flex flex-1 flex-col p-4">
                            <h4 class="line-clamp-2 min-h-[2.5rem] font-bold text-slate-900 text-sm leading-snug">{{ $product->name }}</h4>
                            <p class="mt-1 text-sm font-extrabold text-slate-800">{{ $product->price_label ?? '—' }}</p>
                            <p class="mt-1 flex items-center gap-1 text-[11px] text-slate-400">
                                <?php if ($product->stock === null): ?>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Stok tidak dibatasi
                                <?php elseif ($product->stock > 0): ?>
                                    <svg class="h-3 w-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Stok {{ $product->stock }}
                                <?php else: ?>
                                    <svg class="h-3 w-3 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Stok 0
                                <?php endif; ?>
                            </p>

                            {{-- Actions row --}}
                            <div class="mt-auto flex items-center gap-2 border-t border-slate-100 pt-3">
                                <a href="{{ route('dashboard.products.edit', $product) }}"
                                   class="flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-slate-200 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('dashboard.products.toggle', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            class="flex h-8 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 text-xs">
                                        {{ $product->is_active ? '◉' : '○' }}
                                    </button>
                                </form>
                                <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Hapus"
                                            class="flex h-8 w-9 items-center justify-center rounded-xl border border-slate-200 text-rose-400 transition hover:bg-rose-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                {{-- Three-dot menu placeholder --}}
                                <button type="button" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition hover:bg-slate-50 text-base leading-none font-extrabold tracking-wider">
                                    ···
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    {{-- ===== PRODUK LAIN UNTUK KAMU ===== --}}
    <?php if (isset($marketplace) && $marketplace->isNotEmpty()): ?>
    <div>
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">Produk Lain untuk Kamu</h3>
                <p class="mt-0.5 text-sm text-slate-500">Jelajahi produk menarik dari kreator lain dan temukan yang sesuai dengan gayamu.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 min-[420px]:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($marketplace as $product): ?>
                @php
                    $seller        = $product->user;
                    $sellerProfile = $seller?->profile;
                @endphp
                <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    {{-- Product image --}}
                    <a href="{{ route('public.profile', $seller->username) }}" target="_blank" class="relative block aspect-[4/3] w-full shrink-0 overflow-hidden border-b border-slate-100 bg-[#f8fafc]">
                        <?php if ($product->image_url): ?>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="absolute inset-0 h-full w-full object-contain object-center p-2 transition duration-300 group-hover:scale-[1.04]">
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center text-4xl font-extrabold text-[#1e2a5b]/30">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                        <?php endif; ?>

                        @php
                            $badge = match(true) {
                                $product->created_at->gt(now()->subDays(3)) => ['label' => 'Baru', 'class' => 'bg-[#1e2a5b] text-white'],
                                ($product->original_price ?? 0) > 0 && $product->price < ($product->original_price ?? $product->price) => ['label' => 'Diskon', 'class' => 'bg-amber-400 text-white'],
                                default => null,
                            };
                        @endphp
                        <?php if ($badge): ?>
                            <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[10px] font-bold shadow-sm {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        <?php endif; ?>
                    </a>

                    {{-- Product info --}}
                    <div class="flex flex-1 flex-col p-4">
                        <h4 class="line-clamp-2 min-h-[2.5rem] font-bold text-slate-900 text-sm leading-snug">{{ $product->name }}</h4>
                        <p class="mt-1 text-sm font-extrabold text-slate-800">{{ $product->price_label ?? 'Gratis' }}</p>

                        {{-- Seller --}}
                        <div class="mt-1 flex items-center gap-1.5 text-[11px] text-slate-400">
                            <?php if ($sellerProfile?->avatar_path): ?>
                                <img src="{{ $sellerProfile->avatar_url }}" alt="" class="h-4 w-4 shrink-0 rounded-full object-cover">
                            <?php else: ?>
                                <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[#f1f3e9] text-[8px] font-bold text-[#1e2a5b]">
                                    {{ strtoupper(substr($seller->name, 0, 1)) }}
                                </span>
                            <?php endif; ?>
                            <span class="min-w-0 flex-1 truncate">{{ $sellerProfile?->display_name ?: $seller->name }}</span>
                        </div>

                        {{-- Actions row --}}
                        <div class="mt-auto flex items-center gap-2 border-t border-slate-100 pt-3">
                            <form method="POST" action="{{ route('dashboard.marketplace.products.chat', $product) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="flex h-full w-full items-center justify-center gap-1.5 rounded-xl border border-slate-200 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Chat
                                </button>
                            </form>
                            <a href="{{ route('public.profile', $seller->username) }}"
                               target="_blank"
                               class="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-[#c8ff4d] py-2 text-xs font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Lihat Produk
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
@endsection
