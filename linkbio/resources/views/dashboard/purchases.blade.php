@extends('layouts.dashboard')

@section('title', 'Pesanan Saya')
@section('page-title', 'Pesanan Saya')

@section('content')
<div class="mx-auto max-w-[1200px] space-y-6">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">Pembelian</p>
        <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Pesanan Saya</h2>
        <p class="mt-1 text-sm text-slate-500">Lacak status pesanan dan pembayaran yang kamu lakukan.</p>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach([
            ['label' => 'Total Pesanan', 'value' => $totalOrders, 'icon' => '🧾', 'class' => 'bg-[#f1f3e9] text-[#1e2a5b]'],
            ['label' => 'Sedang Berjalan', 'value' => $activeOrders, 'icon' => '🚚', 'class' => 'bg-blue-50 text-blue-600'],
            ['label' => 'Selesai', 'value' => $completedOrders, 'icon' => '✓', 'class' => 'bg-emerald-50 text-emerald-600'],
            ['label' => 'Total Belanja', 'value' => 'Rp '.number_format($spent, 0, ',', '.'), 'icon' => '💰', 'class' => 'bg-indigo-50 text-indigo-600'],
        ] as $stat)
            <div class="flex min-h-24 items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-extrabold {{ $stat['class'] }}">{{ $stat['icon'] }}</span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-lg font-extrabold leading-tight text-slate-900">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1f3e9] text-xl text-[#1e2a5b]">🛍️</div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada pesanan</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Produk yang kamu beli akan tampil di sini.</p>
            <a href="{{ route('dashboard.marketplace') }}" class="mt-5 inline-flex rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] hover:bg-[#ddff91]">Jelajahi Shop</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($orders as $order)
                <a href="{{ route('dashboard.purchases.show', $order) }}" class="block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            <span class="text-sm font-extrabold text-slate-900">{{ $order->code }}</span>
                            <span class="text-xs text-slate-400">@ {{ $order->seller?->username }}</span>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ ['pending' => 'bg-amber-50 text-amber-700', 'processing' => 'bg-blue-50 text-blue-700', 'shipped' => 'bg-violet-50 text-violet-700', 'completed' => 'bg-emerald-50 text-emerald-700', 'cancelled' => 'bg-rose-50 text-rose-700'][$order->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $order->status_label }}</span>
                    </div>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-slate-800">{{ $order->items->first()?->product_name ?? 'Produk' }}@if($order->items->count() > 1) <span class="font-normal text-slate-400">+{{ $order->items->count() - 1 }} lainnya</span>@endif</p>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-extrabold text-slate-900">{{ $order->total_label }}</p>
                            <p class="text-[11px] font-semibold {{ $order->is_paid ? 'text-emerald-600' : 'text-amber-600' }}">{{ $order->payment_status_label }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div>{{ $orders->links() }}</div>
    @endif
</div>
@endsection
