@extends('layouts.shop')

@section('title', $product->name)
@section('meta')
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($product->description ?: $product->name, 150) }}">
@endsection

@section('content')
@php
    $outOfStock = $product->stock === 0;
@endphp

<a href="{{ route('public.profile', $profileUser->username) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
    <span aria-hidden="true">&larr;</span> Kembali ke {{ $profile->display_name ?: $profileUser->name }}
</a>

<div class="mt-4 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="grid gap-6 p-5 sm:grid-cols-2 sm:p-7">
        <div class="overflow-hidden rounded-2xl bg-[#f3f4f6]">
            <div class="flex aspect-square items-center justify-center">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-contain">
                @else
                    <span class="text-5xl">🛍️</span>
                @endif
            </div>
        </div>

        <div class="flex flex-col">
            @if ($product->category)
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $product->category }}</p>
            @endif
            <h1 class="mt-1 text-2xl font-extrabold leading-tight text-slate-900">{{ $product->name }}</h1>

            <p class="mt-3 text-2xl font-extrabold text-[#14213d]">{{ $product->price_label ?? 'Gratis' }}</p>

            <p class="mt-2 text-xs font-semibold text-slate-500">
                @if ($product->stock === null)
                    Stok tersedia
                @elseif ($outOfStock)
                    <span class="text-rose-600">Stok habis</span>
                @else
                    Sisa stok: {{ $product->stock }}
                @endif
            </p>

            @if ($product->description)
                <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $product->description }}</p>
            @endif

            <div class="mt-auto pt-6">
                @if ($isOwner)
                    <a href="{{ route('dashboard.products.edit', $product) }}" class="flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Edit produk ini</a>
                @elseif ($outOfStock)
                    <button type="button" disabled class="w-full cursor-not-allowed rounded-xl bg-slate-200 px-4 py-3 text-sm font-bold text-slate-500">Stok habis</button>
                @else
                    <form method="POST" action="{{ route('cart.add', $product) }}" class="space-y-3">
                        @csrf
                        <div class="flex items-center gap-3">
                            <label for="quantity" class="text-xs font-bold text-slate-700">Jumlah</label>
                            <input id="quantity" name="quantity" type="number" min="1" @if($product->stock !== null) max="{{ $product->stock }}" @endif value="1" class="w-24 rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                        </div>
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <button type="submit" name="action" value="add" class="flex-1 rounded-xl border border-[#1e2a5b] px-4 py-3 text-sm font-extrabold text-[#1e2a5b] transition hover:bg-slate-50">Tambah ke Keranjang</button>
                            <button type="submit" name="action" value="buy_now" class="flex-1 rounded-xl bg-[#c8ff4d] px-4 py-3 text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Beli Sekarang</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<p class="mt-8 text-center text-xs font-medium text-slate-400">
    Dijual di <a href="{{ route('home') }}" class="font-bold text-slate-600 underline hover:opacity-80">TreeLink</a>
</p>
@endsection
