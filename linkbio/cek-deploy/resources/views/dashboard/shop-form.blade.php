@extends('layouts.dashboard')

@section('title', $product ? 'Edit Produk' : 'Tambah Produk')
@section('page-title', $product ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="mx-auto max-w-4xl">
    <a href="{{ route('dashboard.shop') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900"><span aria-hidden="true">←</span> Kembali ke My Shop</a>

    <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">My Shop</p>
            <h2 class="mt-1 text-xl font-extrabold text-slate-900">{{ $product ? 'Edit produk' : 'Tambah Produk' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Informasi ini digunakan pada kartu produk di public profile.</p>
        </div>

        <form action="{{ $product ? route('dashboard.products.update', $product) : route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-5 p-5 sm:grid-cols-[180px_1fr] sm:p-7">
            @csrf
            @if($product) @method('PUT') @endif

            <label class="flex aspect-square cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border border-dashed border-[#c7d19f] bg-[#f6f8ee] text-center transition hover:bg-[#f1f3e9]">
                @if($product?->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                @else
                    <span class="text-2xl text-[#1e2a5b]">↑</span>
                    <span class="mt-2 text-xs font-bold text-slate-700">Upload foto produk</span>
                    <span class="mt-1 px-3 text-[10px] text-slate-400">PNG, JPG, maksimal 4 MB</span>
                @endif
                <input type="file" name="image" accept="image/*" class="sr-only">
            </label>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="product_name" class="mb-1.5 block text-xs font-bold text-slate-700">Nama Produk <span class="text-rose-500">*</span></label>
                    <input id="product_name" name="name" value="{{ old('name', $product?->name) }}" required maxlength="150" placeholder="Contoh: Tote Bag Syari" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="product_price" class="mb-1.5 block text-xs font-bold text-slate-700">Harga (Rp)</label>
                    <input id="product_price" name="price" type="number" min="0" step="1000" value="{{ old('price', $product?->price) }}" placeholder="75000" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="product_category" class="mb-1.5 block text-xs font-bold text-slate-700">Kategori</label>
                    <input id="product_category" name="category" value="{{ old('category', $product?->category) }}" maxlength="100" placeholder="Pilih kategori" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('category')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="product_stock" class="mb-1.5 block text-xs font-bold text-slate-700">Stok</label>
                    <input id="product_stock" name="stock" type="number" min="0" value="{{ old('stock', $product?->stock) }}" placeholder="Kosongkan untuk tanpa batas" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('stock')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="product_url" class="mb-1.5 block text-xs font-bold text-slate-700">Link Pembelian <span class="text-rose-500">*</span></label>
                    <input id="product_url" name="url" type="url" value="{{ old('url', $product?->url) }}" required maxlength="2048" placeholder="https://contoh.com/produk" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('url')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="product_description" class="mb-1.5 block text-xs font-bold text-slate-700">Deskripsi</label>
                    <textarea id="product_description" name="description" rows="4" maxlength="500" placeholder="Tulis deskripsi produk di sini..." class="w-full resize-y rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">{{ old('description', $product?->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 sm:col-span-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true)) class="h-4 w-4 rounded border-slate-300 text-[#1e2a5b] focus:ring-[#c8ff4d]">
                    Tampilkan produk di Shop
                </label>
            </div>

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4 sm:col-span-2">
                <a href="{{ route('dashboard.shop') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-[#c8ff4d] px-5 py-2.5 text-sm font-bold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">{{ $product ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
