@extends('layouts.shop')

@section('title', 'Pesanan '.$order->code)
@section('meta')
    <meta name="robots" content="noindex">
@endsection

@section('content')
<div class="rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-sm sm:p-9">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-3xl">✅</div>
    <h1 class="mt-4 text-2xl font-extrabold text-slate-900">Pesanan Diterima!</h1>
    <p class="mt-2 text-sm text-slate-500">Pesananmu sudah tercatat di TreeLink. Penjual akan segera memprosesnya.</p>

    <div class="mt-6 rounded-2xl bg-slate-50 px-5 py-4 text-left">
        <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-500">Kode Pesanan</span>
            <span class="font-extrabold text-slate-900">{{ $order->code }}</span>
        </div>
        <div class="mt-2 flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-500">Status</span>
            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">{{ $order->status_label }}</span>
        </div>
        <div class="mt-2 flex items-center justify-between text-sm">
            <span class="font-semibold text-slate-500">Tanggal</span>
            <span class="font-bold text-slate-900">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
        </div>
    </div>
</div>

<div class="mt-4 grid gap-4 lg:grid-cols-2">
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">Detail Produk</h2>
        <div class="mt-3 divide-y divide-slate-100">
            @foreach ($order->items as $item)
                <div class="flex items-center gap-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-900">{{ $item->product_name }}</p>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $item->quantity }} &times; {{ $item->price_label }}</p>
                    </div>
                    <span class="text-sm font-extrabold text-slate-900">{{ $item->subtotal_label }}</span>
                </div>
            @endforeach
        </div>
        <div class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3">
            <span class="text-sm font-bold text-slate-500">Total</span>
            <span class="text-lg font-extrabold text-[#14213d]">{{ $order->total_label }}</span>
        </div>
    </div>

    <div class="space-y-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">Pengiriman</h2>
            <p class="mt-2 text-sm font-bold text-slate-800">{{ $order->shipping_name }} &middot; {{ $order->shipping_phone }}</p>
            <p class="mt-1 text-sm text-slate-600">{{ $order->shipping_summary }}</p>
            @if ($order->note)
                <p class="mt-2 text-xs text-slate-500">Catatan: {{ $order->note }}</p>
            @endif
            @if ($order->tracking_number)
                <p class="mt-2 text-xs text-slate-500">No. Resi: <span class="font-bold text-slate-700">{{ $order->tracking_number }}</span></p>
            @endif
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">Pembayaran</h2>
            <div class="mt-2 flex items-center justify-between text-sm">
                <span class="text-slate-600">{{ $order->payment_method_label }}</span>
                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $order->is_paid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $order->payment_status_label }}</span>
            </div>

            @if ($order->payment_proof_url)
                <a href="{{ $order->payment_proof_url }}" target="_blank" rel="noopener" class="mt-3 block overflow-hidden rounded-xl border border-slate-200">
                    <img src="{{ $order->payment_proof_url }}" alt="Bukti pembayaran" class="max-h-40 w-full object-contain bg-slate-50">
                </a>
            @endif

            @unless ($order->is_paid)
                <form method="POST" action="{{ URL::signedRoute('public.order.proof', ['order' => $order->id]) }}" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <label for="payment_proof" class="block text-xs font-bold text-slate-700">Unggah bukti pembayaran</label>
                    <input id="payment_proof" type="file" name="payment_proof" accept="image/*" required class="mt-1 block w-full text-xs text-slate-500 file:mr-2 file:rounded-lg file:border-0 file:bg-[#f1f3e9] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#1e2a5b]">
                    <button type="submit" class="mt-2 w-full rounded-xl bg-[#1e2a5b] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#303e76]">Upload Bukti</button>
                </form>
                <p class="mt-2 text-[11px] leading-relaxed text-slate-400">Setelah bukti diunggah, penjual akan mengonfirmasi pembayaranmu.</p>
            @endunless
        </div>
    </div>
</div>

<div class="mt-5 flex flex-col gap-2 sm:flex-row">
    <a href="{{ route('public.profile', $profileUser->username) }}" class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-50">Kembali ke Toko</a>
    @auth
        <a href="{{ route('dashboard.purchases.index') }}" class="flex-1 rounded-xl bg-[#c8ff4d] px-4 py-3 text-center text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Lacak Pesanan Saya</a>
    @else
        <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-[#c8ff4d] px-4 py-3 text-center text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Daftar untuk Lacak Pesanan</a>
    @endauth
</div>
@endsection
