@extends('layouts.dashboard')

@section('title', 'Jelajahi Shop')
@section('page-title', 'Jelajahi Shop')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-6">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">TreeLink Marketplace</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Jelajahi Shop</h2>
            <p class="mt-1 text-sm text-slate-500">Temukan produk dari semua penjual TreeLink dan chat langsung dengan pemiliknya.</p>
        </div>
        <a href="{{ route('dashboard.shop') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Kelola produk saya</a>
    </div>

    <form method="GET" action="{{ route('dashboard.marketplace') }}" class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-[minmax(0,1fr)_200px_180px_auto]">
        <label class="relative block min-w-0">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">⌕</span>
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari produk, kategori, atau penjual..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-9 pr-3 text-sm outline-none transition focus:border-[#879366] focus:bg-white focus:ring-4 focus:ring-[#edf0e7]">
        </label>
        <label>
            <span class="sr-only">Kategori</span>
            <select name="category" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                <option value="">Semua kategori</option>
                @foreach($categories as $option)
                    <option value="{{ $option }}" @selected($category === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <span class="sr-only">Urutkan produk</span>
            <select name="sort" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                <option value="newest" @selected($sort === 'newest')>Terbaru</option>
                <option value="price-asc" @selected($sort === 'price-asc')>Harga terendah</option>
                <option value="price-desc" @selected($sort === 'price-desc')>Harga tertinggi</option>
            </select>
        </label>
        <button type="submit" class="rounded-xl bg-[#c8ff4d] px-5 py-3 text-sm font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">Cari</button>
    </form>

    <div class="flex items-center justify-between gap-3">
        <p class="text-sm font-semibold text-slate-700">{{ $products->total() }} produk ditemukan</p>
        @if($search || $category || $sort !== 'newest')
            <a href="{{ route('dashboard.marketplace') }}" class="text-xs font-semibold text-slate-500 underline underline-offset-4 hover:text-slate-900">Reset filter</a>
        @endif
    </div>

    @if($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1f3e9] text-xl text-[#1e2a5b]">⌕</div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada produk yang cocok</h3>
            <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Produk aktif dari semua akun akan muncul di sini. Coba kata kunci lain atau reset filter.</p>
            <a href="{{ route('dashboard.marketplace') }}" class="mt-5 inline-flex rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] hover:bg-[#ddff91]">Lihat semua produk</a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @foreach($products as $product)
                @php
                    $seller = $product->user;
                    $sellerProfile = $seller?->profile;
                @endphp
                <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative aspect-[1.35] overflow-hidden bg-gradient-to-br from-[#eef2dc] via-white to-[#dfe5f4]">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                        @else
                            <div class="flex h-full items-center justify-center text-4xl font-extrabold text-[#1e2a5b]">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                        @endif
                        @if($product->stock === 0)
                            <span class="absolute left-3 top-3 rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-700">Stok habis</span>
                        @endif
                    </div>

                    <div class="space-y-3 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                @if($product->category)<p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $product->category }}</p>@endif
                                <h3 class="mt-1 line-clamp-2 min-h-10 text-sm font-bold leading-5 text-slate-900">{{ $product->name }}</h3>
                            </div>
                            <span class="shrink-0 text-sm font-extrabold text-slate-900">{{ $product->price_label ?? 'Gratis' }}</span>
                        </div>

                        @if($product->description)
                            <p class="line-clamp-2 min-h-9 text-xs leading-5 text-slate-500">{{ $product->description }}</p>
                        @else
                            <p class="min-h-9 text-xs leading-5 text-slate-400">Tidak ada deskripsi produk.</p>
                        @endif

                        <div class="flex items-center gap-2 border-t border-slate-100 pt-3 text-xs text-slate-500">
                            <a href="{{ $seller->publicUrl() }}" target="_blank" rel="noopener" class="flex min-w-0 flex-1 items-center gap-2 hover:text-[#1e2a5b]">
                                @if($sellerProfile?->avatar_path)
                                    <img src="{{ $sellerProfile->avatar_url }}" alt="" class="h-7 w-7 rounded-full object-cover">
                                @else
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#f1f3e9] text-[10px] font-bold text-[#1e2a5b]">{{ strtoupper(substr($seller->name, 0, 1)) }}</span>
                                @endif
                                <span class="min-w-0 flex-1 truncate">{{ $sellerProfile?->display_name ?: $seller->name }} <span class="text-slate-400">· {{ '@'.$seller->username }}</span></span>
                            </a>
                            @if($product->stock !== null && $product->stock > 0)<span class="shrink-0 text-[10px]">Stok {{ $product->stock }}</span>@endif
                        </div>
                        @if($seller->id !== auth()->id())
                            <form method="POST" action="{{ route('dashboard.accounts.follow.toggle', $seller) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-slate-500 underline underline-offset-2 hover:text-[#1e2a5b]">{{ $seller->is_followed_by_me ? 'Mengikuti penjual' : 'Ikuti penjual' }}</button>
                            </form>
                        @endif

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            @if($seller->id === auth()->id())
                                <a href="{{ route('dashboard.products.edit', $product) }}" class="col-span-2 rounded-xl border border-slate-200 py-2.5 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-50">Kelola produk saya</a>
                            @else
                                <form method="POST" action="{{ route('dashboard.marketplace.products.chat', $product) }}">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl bg-[#1e2a5b] px-3 py-2.5 text-xs font-bold text-white transition hover:bg-[#303e76]">Chat Penjual</button>
                                </form>
                                <a href="{{ $product->url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-[#c8ff4d] px-3 py-2.5 text-center text-xs font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">Beli Produk</a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div>{{ $products->links() }}</div>
    @endif
</div>
@endsection
