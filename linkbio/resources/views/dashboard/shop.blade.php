@extends('layouts.dashboard')

@section('title', 'My Shop')
@section('page-title', 'My Shop')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">TreeLink Shop</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">My Shop</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola produk yang tampil di halaman TreeLink kamu.</p>
        </div>
        <a href="{{ route('dashboard.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">
            <span class="text-lg leading-none">+</span> Tambah Produk
        </a>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach([
            ['label' => 'Total Produk', 'value' => $totalProducts, 'note' => 'Semua produk', 'icon' => '▧', 'iconClass' => 'bg-[#f1f3e9] text-[#1e2a5b]'],
            ['label' => 'Produk Aktif', 'value' => $activeProducts, 'note' => 'Tampil di public profile', 'icon' => '✓', 'iconClass' => 'bg-emerald-50 text-emerald-600'],
            ['label' => 'Draft', 'value' => $draftProducts, 'note' => 'Tidak tampil ke publik', 'icon' => '◷', 'iconClass' => 'bg-amber-50 text-amber-600'],
            ['label' => 'Stok Habis', 'value' => $outOfStockProducts, 'note' => 'Produk dengan stok 0', 'icon' => '◉', 'iconClass' => 'bg-rose-50 text-rose-600'],
        ] as $stat)
            <div class="flex min-h-28 items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-extrabold {{ $stat['iconClass'] }}">{{ $stat['icon'] }}</span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-2xl font-extrabold leading-none text-slate-900">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-[11px] text-slate-400">{{ $stat['note'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col gap-4 border-b border-slate-200 pb-4 lg:flex-row lg:items-center lg:justify-between">
        <nav class="flex flex-wrap gap-2" aria-label="Filter produk">
            @foreach(['all' => 'Semua Produk', 'active' => 'Aktif', 'draft' => 'Draft', 'out-of-stock' => 'Habis Stok'] as $value => $label)
                <a href="{{ route('dashboard.shop', ['filter' => $value, 'q' => $search ?: null]) }}" class="rounded-full px-3.5 py-2 text-xs font-bold transition {{ $filter === $value ? 'bg-[#1e2a5b] text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                    <span class="ml-1 opacity-70">{{ ['all' => $totalProducts, 'active' => $activeProducts, 'draft' => $draftProducts, 'out-of-stock' => $outOfStockProducts][$value] }}</span>
                </a>
            @endforeach
        </nav>
        <form method="GET" action="{{ route('dashboard.shop') }}" class="flex w-full gap-2 lg:max-w-sm">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <label class="relative min-w-0 flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">⌕</span>
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari produk..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm outline-none transition focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
            </label>
            <button class="rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cari</button>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1f3e9] text-xl text-[#1e2a5b]">▧</div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $search || $filter !== 'all' ? 'Produk tidak ditemukan' : 'Shop kamu masih kosong' }}</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">{{ $search || $filter !== 'all' ? 'Coba ubah kata kunci atau filter.' : 'Tambahkan produk pertama. Produk aktif akan langsung muncul di public profile.' }}</p>
            @if(!$search && $filter === 'all')
                <a href="{{ route('dashboard.products.create') }}" class="mt-5 inline-flex rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] hover:bg-[#ddff91]">Tambah Produk</a>
            @endif
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4">
            @foreach($products as $product)
                <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <a href="{{ route('dashboard.products.edit', $product) }}" class="relative block aspect-[1.42] overflow-hidden bg-slate-100">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#eef2dc] via-white to-[#dfe5f4] text-3xl font-extrabold text-[#1e2a5b]">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                        @endif
                        <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[10px] font-bold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-white/90 text-slate-600' }}">{{ $product->is_active ? 'Aktif' : 'Draft' }}</span>
                    </a>
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $product->category ?: 'Produk' }}</p>
                                <h3 class="mt-1 truncate text-sm font-bold text-slate-900">{{ $product->name }}</h3>
                            </div>
                            <span class="whitespace-nowrap text-sm font-extrabold text-slate-900">{{ $product->price_label ?? 'Gratis' }}</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span>{{ $product->stock === null ? 'Stok tidak dibatasi' : 'Stok: '.$product->stock }}</span>
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-600"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>{{ $product->is_active ? 'Tayang' : 'Disembunyikan' }}</span>
                        </div>
                        <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('dashboard.products.edit', $product) }}" class="flex-1 rounded-lg border border-slate-200 py-2 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-50">Edit</a>
                            <form action="{{ route('dashboard.products.toggle', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" aria-label="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk" title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" class="flex h-8 w-9 items-center justify-center rounded-lg border border-slate-200 text-sm text-slate-500 hover:bg-slate-50">{{ $product->is_active ? '◉' : '○' }}</button>
                            </form>
                            <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Hapus produk" title="Hapus produk" class="flex h-8 w-9 items-center justify-center rounded-lg border border-slate-200 text-sm text-rose-500 hover:bg-rose-50">⌫</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
