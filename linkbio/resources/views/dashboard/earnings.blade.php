@extends('layouts.dashboard')

@section('title', __('Penghasilan'))
@section('page-title', __('Penghasilan'))

@php
    $money = fn ($value) => 'Rp '.number_format((float) $value, 0, ',', '.');
@endphp

@section('content')
<div class="mx-auto max-w-[1500px] space-y-6">
    <div>
        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#879366]">My Shop</p>
        <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">{{ __('Penghasilan') }}</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">{{ __('Pantau transaksi yang pembayarannya sudah terverifikasi. Angka di halaman ini adalah laporan penjualan, bukan saldo yang sudah dapat dicairkan.') }}</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500">{{ __('Penghasilan tercatat') }}</p>
            <p class="mt-3 text-2xl font-black text-[#14213d]">{{ $money($recordedRevenue) }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ $paidOrderCount }} {{ __('pesanan terbayar') }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5 shadow-sm">
            <p class="text-xs font-bold text-emerald-700">{{ __('Pesanan selesai') }}</p>
            <p class="mt-3 text-2xl font-black text-emerald-800">{{ $money($completedRevenue) }}</p>
            <p class="mt-1 text-xs text-emerald-700/70">{{ __('Pembayaran terverifikasi dan status selesai') }}</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-5 shadow-sm">
            <p class="text-xs font-bold text-amber-700">{{ __('Masih diproses') }}</p>
            <p class="mt-3 text-2xl font-black text-amber-800">{{ $money($pendingRevenue) }}</p>
            <p class="mt-1 text-xs text-amber-700/70">{{ __('Belum menjadi saldo pencairan') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500">{{ __('Komisi & pencairan') }}</p>
            <p class="mt-3 text-lg font-black text-slate-700">{{ __('Belum tersedia') }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ __('Menunggu konfigurasi ledger dan payout resmi') }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4 text-sm leading-6 text-blue-900">
        <span class="font-extrabold">{{ __('Catatan keamanan:') }}</span>
        {{ __('Pembayaran hanya dihitung setelah status dikonfirmasi oleh gateway atau proses verifikasi resmi. TreeLink belum mengaktifkan wallet, komisi, atau pencairan otomatis.') }}
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="font-extrabold text-slate-900">{{ __('Riwayat transaksi terbayar') }}</h3>
                <p class="mt-1 text-xs text-slate-500">{{ __('Hanya order milik akun ini yang ditampilkan.') }}</p>
            </div>
            <span class="rounded-full bg-[#edf8dd] px-3 py-1.5 text-xs font-bold text-[#4d7c0f]">{{ $paidOrderCount }} {{ __('transaksi') }}</span>
        </div>

        @if($orders->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1f3e9] text-[#1e2a5b]"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6m14 0a8 8 0 11-16 0 8 8 0 0116 0Z"/></svg></div>
                <h4 class="mt-4 font-bold text-slate-900">{{ __('Belum ada penghasilan tercatat') }}</h4>
                <p class="mt-1 text-sm text-slate-500">{{ __('Pesanan yang pembayarannya sudah terverifikasi akan muncul di sini.') }}</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($orders as $order)
                    @php
                        $statusTone = match ($order->status_code) {
                            'completed' => 'bg-emerald-50 text-emerald-700',
                            'cancelled' => 'bg-rose-50 text-rose-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-extrabold text-slate-900">#{{ $order->code }}</span>
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $statusTone }}">{{ __($order->status_label) }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }} &middot; {{ $order->buyer_name }}</p>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900">{{ $order->total_label }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-slate-100 px-5 py-4">{{ $orders->links() }}</div>
        @endif
    </section>
</div>
@endsection
