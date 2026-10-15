@extends('layouts.dashboard')

@section('title', 'Jelajahi Shop')
@section('page-title', 'Jelajahi Shop')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-6">
    @include('partials.shop-navbar')

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
                @include('dashboard.partials.marketplace-card', ['product' => $product])
            @endforeach
        </div>
        <div>{{ $products->links() }}</div>
    @endif
</div>
@endsection
