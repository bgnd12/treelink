@extends('layouts.shop')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-sm sm:p-9">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-3xl">✅</div>
    <h1 class="mt-4 text-2xl font-extrabold text-slate-900">Pesanan Berhasil Dibuat!</h1>
    <p class="mt-2 text-sm text-slate-500">Terima kasih! Pesananmu sudah tercatat di TreeLink. Penjual akan segera memprosesnya.</p>
</div>

<div class="mt-5 space-y-4">
    @foreach ($orders as $order)
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 bg-slate-50/60 px-5 py-3">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <span class="text-sm font-extrabold text-slate-900">{{ $order->code }}</span>
                    <span class="text-xs text-slate-400">@ {{ $order->seller->username }}</span>
                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-700">{{ $order->status_label }}</span>
                </div>
                <span class="text-sm font-extrabold text-slate-900">{{ $order->total_label }}</span>
            </div>

            <div class="space-y-3 p-5">
                <div class="divide-y divide-slate-100">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-2 text-sm">
                            <span class="min-w-0 flex-1 truncate pr-3 text-slate-700">{{ $item->quantity }} &times; {{ $item->product_name }}</span>
                            <span class="font-bold text-slate-900">{{ $item->subtotal_label }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-600">
                    <p><span class="font-bold text-slate-700">Kirim ke:</span> {{ $order->shipping_name }} &middot; {{ $order->shipping_phone }}</p>
                    <p class="mt-0.5">{{ $order->shipping_summary }}</p>
                    <p class="mt-1"><span class="font-bold text-slate-700">Pembayaran:</span> {{ $order->payment_method_label }} &middot; <span class="{{ $order->is_paid ? 'text-emerald-600' : 'text-amber-600' }} font-bold">{{ $order->payment_status_label }}</span></p>
                    <p class="mt-1 text-[11px] text-slate-500">Halaman ini hanya menampilkan status pesanan. Konfirmasi pembayaran berasal dari callback gateway yang tervalidasi.</p>
                </div>

                @unless ($order->is_paid)
                    @if ($order->isOnlinePayment())
                        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs text-blue-700">
                            <p class="font-bold">Pembayaran Online (Otomatis)</p>
                            <p class="mt-1">Selesaikan pembayaran melalui halaman pembayaran. Status akan diperbarui otomatis setelah pembayaran berhasil.</p>
                            @if ($order->payment_url)
                                <a href="{{ $order->payment_url }}" class="mt-2 inline-block rounded-lg bg-[#1e2a5b] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#303e76]">Lanjutkan Pembayaran</a>
                            @endif
                        </div>
                    @else
                        <form method="POST" action="{{ URL::signedRoute('public.order.proof', ['order' => $order->id]) }}" enctype="multipart/form-data" class="flex flex-col gap-2 rounded-xl border border-dashed border-slate-300 px-4 py-3 sm:flex-row sm:items-center">
                            @csrf
                            <div class="min-w-0 flex-1">
                                <label for="proof-{{ $order->id }}" class="block text-xs font-bold text-slate-700">Unggah bukti pembayaran</label>
                                <input id="proof-{{ $order->id }}" type="file" name="payment_proof" accept="image/*" required class="mt-1 block w-full text-xs text-slate-500 file:mr-2 file:rounded-lg file:border-0 file:bg-[#f1f3e9] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#1e2a5b]">
                            </div>
                            <button type="submit" class="rounded-xl bg-[#1e2a5b] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#303e76]">Upload</button>
                        </form>
                    @endif
                @else
                    <p class="rounded-xl bg-emerald-50 px-4 py-2.5 text-xs font-semibold text-emerald-700">Pembayaran sudah dikonfirmasi oleh sistem pembayaran.</p>
                @endunless

                <a href="{{ route('public.profile', $order->seller->username) }}" class="inline-block text-xs font-bold text-slate-500 underline underline-offset-4 hover:text-slate-800">Kunjungi toko {{ $order->seller->username }}</a>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-5 flex flex-col gap-2 sm:flex-row">
    @auth
        <a href="{{ route('dashboard.purchases.index') }}" class="flex-1 rounded-xl bg-[#c8ff4d] px-4 py-3 text-center text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Lacak Pesanan Saya</a>
    @else
        <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-[#c8ff4d] px-4 py-3 text-center text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Daftar untuk Lacak Pesanan</a>
    @endauth
    <a href="{{ route('home') }}" class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-50">Beranda</a>
</div>
@endsection
