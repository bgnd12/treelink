@extends('layouts.shop')

@section('title', 'Keranjang')

@section('content')
<div class="flex items-end justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Keranjang</h1>
        <p class="mt-1 text-sm text-slate-500">Periksa produk lalu lanjut ke pembayaran.</p>
    </div>
    @if (! $groups->isEmpty())
        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan keranjang?')">
            @csrf
            <button type="submit" class="text-xs font-bold text-slate-500 underline underline-offset-4 hover:text-rose-600">Kosongkan</button>
        </form>
    @endif
</div>

@if ($groups->isEmpty())
    <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f1f3e9] text-2xl">🛒</div>
        <h2 class="mt-4 text-lg font-bold text-slate-900">Keranjang masih kosong</h2>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Jelajahi toko dan tambahkan produk untuk mulai berbelanja.</p>
        <a href="{{ route('home') }}" class="mt-5 inline-flex rounded-xl bg-[#c8ff4d] px-5 py-2.5 text-sm font-bold text-[#1e2a5b] hover:bg-[#ddff91]">Kembali ke Beranda</a>
    </div>
@else
    <div class="mt-5 space-y-4">
        @foreach ($groups as $group)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50/60 px-5 py-3">
                    <span class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">Penjual</span>
                    <a href="{{ route('public.profile', $group['seller']->username) }}" class="text-sm font-extrabold text-[#14213d] hover:underline">@ {{ $group['seller']->username }}</a>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($group['items'] as $row)
                        @php $product = $row['product']; @endphp
                        <div class="flex gap-3 p-4 sm:gap-4 sm:p-5">
                            <a href="{{ route('public.product.show', ['username' => $group['seller']->username, 'product' => $product->id]) }}" class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-[#f3f4f6]">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                @else
                                    <span class="flex h-full w-full items-center justify-center text-2xl">🛍️</span>
                                @endif
                            </a>

                            <div class="flex min-w-0 flex-1 flex-col">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                         <p class="truncate text-sm font-bold text-slate-900">{{ $product->name }}</p>
                                         <p class="mt-0.5 text-xs text-slate-500">{{ ($row['variant']?->price_label) ?: ($product->price_label ?? 'Gratis') }}</p>
                                         @if ($row['variant'])
                                             <p class="mt-0.5 text-[11px] text-slate-400">Varian: {{ $row['variant']->label }}</p>
                                         @endif
                                    </div>
                                    <span class="shrink-0 text-sm font-extrabold text-slate-900">Rp {{ number_format($row['subtotal'], 0, ',', '.') }}</span>
                                </div>

                                <div class="mt-3 flex items-center justify-between gap-2">
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-1.5">
                                        @csrf
                                 <input type="hidden" name="key" value="{{ $row['key'] }}">
                                         <input type="number" name="quantity" min="0" max="{{ $row['variant'] ? ($row['variant']->stock ?? 100000) : ($product->stock ?? 100000) }}" value="{{ $row['quantity'] }}" class="w-16 rounded-lg border border-slate-200 px-2 py-1.5 text-sm outline-none focus:border-[#879366] focus:ring-2 focus:ring-[#edf0e7]">
                                        <button type="submit" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-50">Ubah</button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                         <input type="hidden" name="key" value="{{ $row['key'] }}">
                                        <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-slate-500">Total ({{ app(\App\Support\Cart::class)->count() }} barang)</span>
            <span class="text-xl font-extrabold text-[#14213d]">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        <a href="{{ route('checkout.show') }}" class="mt-4 flex w-full items-center justify-center rounded-xl bg-[#c8ff4d] px-5 py-3 text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Lanjut ke Checkout</a>
    </div>
@endif
@endsection
