@extends('layouts.dashboard')

@section('title', 'Pesanan '.$order->code)
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="mx-auto max-w-[1100px] space-y-5">
    <a href="{{ route('dashboard.purchases.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-slate-900"><span aria-hidden="true">&larr;</span> Kembali ke Pesanan Saya</a>

    @php
        $steps = ['pending' => 'Baru', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai'];
        $currentIndex = array_search($order->status, array_keys($steps), true);
        $cancelled = $order->status === 'cancelled';
    @endphp

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-4">
            <div>
                <p class="text-sm font-extrabold text-slate-900">{{ $order->code }}</p>
                <p class="text-xs text-slate-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }} &middot; @ {{ $order->seller?->username }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $cancelled ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700' }}">{{ $order->status_label }}</span>
                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $order->is_paid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $order->payment_status_label }}</span>
            </div>
        </div>

        {{-- Timeline --}}
        @unless($cancelled)
            <div class="border-b border-slate-100 px-5 py-5">
                <div class="flex items-center">
                    @foreach($steps as $key => $label)
                        @php $done = $currentIndex !== false && array_search($key, array_keys($steps), true) <= $currentIndex; @endphp
                        <div class="flex flex-1 flex-col items-center text-center">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-extrabold {{ $done ? 'bg-[#c8ff4d] text-[#1e2a5b]' : 'bg-slate-100 text-slate-400' }}">
                                @if($done) ✓ @else {{ $loop->iteration }} @endif
                            </span>
                            <span class="mt-1.5 text-[11px] font-bold {{ $done ? 'text-slate-800' : 'text-slate-400' }}">{{ $label }}</span>
                        </div>
                        @if(! $loop->last)
                            <div class="h-0.5 -mt-4 flex-1 {{ ($currentIndex !== false && array_search($key, array_keys($steps), true) < $currentIndex) ? 'bg-[#c8ff4d]' : 'bg-slate-200' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endunless

        <div class="grid gap-5 p-5 lg:grid-cols-[1fr_320px]">
            <div class="space-y-4">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">Produk</h2>
                    <div class="mt-2 divide-y divide-slate-100">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between py-2.5 text-sm">
                                <span class="min-w-0 flex-1 pr-3">
                                    <span class="block truncate font-bold text-slate-800">{{ $item->product_name }}</span>
                                    <span class="text-xs text-slate-400">{{ $item->quantity }} &times; {{ $item->price_label }}</span>
                                </span>
                                <span class="font-extrabold text-slate-900">{{ $item->subtotal_label }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-2 flex items-center justify-between border-t border-slate-200 pt-3">
                        <span class="text-sm font-bold text-slate-500">Total</span>
                        <span class="text-lg font-extrabold text-[#14213d]">{{ $order->total_label }}</span>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-600">
                    <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-400">Alamat Pengiriman</p>
                    <p class="mt-1"><span class="font-bold text-slate-700">{{ $order->shipping_name ?: $order->buyer_name }}</span> &middot; {{ $order->shipping_phone ?: $order->buyer_contact }}</p>
                    <p class="mt-0.5">{{ $order->shipping_summary ?: 'Alamat tidak tersedia' }}</p>
                    @if($order->note)<p class="mt-1"><span class="font-bold text-slate-700">Catatan:</span> {{ $order->note }}</p>@endif
                    @if($order->tracking_number)<p class="mt-1"><span class="font-bold text-slate-700">No. Resi:</span> {{ $order->tracking_number }}</p>@endif
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 p-4">
                <h2 class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">Pembayaran</h2>
                <div class="mt-2 flex items-center justify-between text-sm">
                    <span class="text-slate-600">{{ $order->payment_method_label }}</span>
                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $order->is_paid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $order->payment_status_label }}</span>
                </div>

                @if($order->payment_proof_url)
                    <a href="{{ $order->payment_proof_url }}" target="_blank" rel="noopener" class="mt-3 block overflow-hidden rounded-lg border border-slate-200">
                        <img src="{{ $order->payment_proof_url }}" alt="Bukti pembayaran" class="max-h-32 w-full object-contain bg-slate-50">
                    </a>
                @endif

                @unless($order->is_paid)
                    <form method="POST" action="{{ URL::signedRoute('public.order.proof', ['order' => $order->id]) }}" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <label for="payment_proof" class="block text-xs font-bold text-slate-700">Unggah bukti pembayaran</label>
                        <input id="payment_proof" type="file" name="payment_proof" accept="image/*" required class="mt-1 block w-full text-xs text-slate-500 file:mr-2 file:rounded-lg file:border-0 file:bg-[#f1f3e9] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#1e2a5b]">
                        <button type="submit" class="mt-2 w-full rounded-xl bg-[#1e2a5b] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#303e76]">Upload Bukti</button>
                    </form>
                @else
                    <p class="mt-3 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">Pembayaran telah dikonfirmasi penjual.</p>
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection
