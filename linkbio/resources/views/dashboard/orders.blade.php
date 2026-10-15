@extends('layouts.dashboard')

@section('title', 'Pesanan')
@section('page-title', 'Pesanan')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">My Shop</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Pesanan Masuk</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola pesanan beserta pengiriman dan pembayarannya.</p>
        </div>
        <a href="{{ route('dashboard.shop') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Kelola Produk</a>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach([
            ['label' => 'Total Pesanan', 'value' => $totalOrders, 'note' => 'Semua pesanan', 'icon' => '🧾', 'iconClass' => 'bg-[#f1f3e9] text-[#1e2a5b]'],
            ['label' => 'Pesanan Baru', 'value' => $pendingOrders, 'note' => 'Perlu diproses', 'icon' => '🔔', 'iconClass' => 'bg-amber-50 text-amber-600'],
            ['label' => 'Selesai', 'value' => $completedOrders, 'note' => 'Pesanan tuntas', 'icon' => '✓', 'iconClass' => 'bg-emerald-50 text-emerald-600'],
            ['label' => 'Pendapatan', 'value' => 'Rp '.number_format($revenue, 0, ',', '.'), 'note' => 'Dari pesanan selesai', 'icon' => '💰', 'iconClass' => 'bg-indigo-50 text-indigo-600'],
        ] as $stat)
            <div class="flex min-h-28 items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-extrabold {{ $stat['iconClass'] }}">{{ $stat['icon'] }}</span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-xl font-extrabold leading-tight text-slate-900">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-[11px] text-slate-400">{{ $stat['note'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <nav class="flex flex-wrap gap-2 border-b border-slate-200 pb-4" aria-label="Filter pesanan">
        @foreach(array_merge(['all' => 'Semua'], \App\Models\Order::STATUSES) as $value => $label)
            <a href="{{ route('dashboard.orders.index', ['status' => $value]) }}" class="rounded-full px-3.5 py-2 text-xs font-bold transition {{ $status === $value ? 'bg-[#1e2a5b] text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">{{ $label }}</a>
        @endforeach
    </nav>

    @if($orders->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1f3e9] text-xl text-[#1e2a5b]">🧾</div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada pesanan</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Pesanan dari pembeli akan muncul di sini secara otomatis.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-3">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            <span class="text-sm font-extrabold text-slate-900">{{ $order->code }}</span>
                            <span class="text-xs text-slate-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ ['pending' => 'bg-amber-50 text-amber-700', 'processing' => 'bg-blue-50 text-blue-700', 'shipped' => 'bg-violet-50 text-violet-700', 'completed' => 'bg-emerald-50 text-emerald-700', 'cancelled' => 'bg-rose-50 text-rose-700'][$order->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $order->status_label }}</span>
                            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $order->is_paid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $order->payment_status_label }}</span>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900">{{ $order->total_label }}</span>
                    </div>

                    <div class="grid gap-5 p-5 lg:grid-cols-[1fr_260px]">
                        <div class="space-y-3">
                            <div class="divide-y divide-slate-100">
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

                            <div class="rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-600">
                                <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-400">Pengiriman</p>
                                <p class="mt-1"><span class="font-bold text-slate-700">{{ $order->shipping_name ?: $order->buyer_name }}</span> &middot; {{ $order->shipping_phone ?: $order->buyer_contact }}</p>
                                <p class="mt-0.5">{{ $order->shipping_summary ?: 'Alamat tidak tersedia' }}</p>
                                @if($order->note)<p class="mt-1"><span class="font-bold text-slate-700">Catatan:</span> {{ $order->note }}</p>@endif
                                @if($order->tracking_number)<p class="mt-1"><span class="font-bold text-slate-700">Resi:</span> {{ $order->tracking_number }}</p>@endif
                            </div>
                        </div>

                        <div class="space-y-4 lg:border-l lg:border-slate-100 lg:pl-4">
                            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <label for="status-{{ $order->id }}" class="text-xs font-bold text-slate-700">Status pesanan</label>
                                <select id="status-{{ $order->id }}" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                                    @foreach(\App\Models\Order::STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <input name="tracking_number" value="{{ $order->tracking_number }}" maxlength="100" placeholder="No. resi (opsional)" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                                <button type="submit" class="w-full rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">Simpan Status</button>
                            </form>

                            <form method="POST" action="{{ route('dashboard.orders.payment', $order) }}" class="space-y-2 border-t border-slate-100 pt-4">
                                @csrf
                                @method('PATCH')
                                <label for="payment-{{ $order->id }}" class="text-xs font-bold text-slate-700">Pembayaran ({{ $order->payment_method_label ?? 'Belum dipilih' }})</label>
                                <select id="payment-{{ $order->id }}" name="payment_status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                                    @foreach(\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($order->payment_status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Simpan Pembayaran</button>
                                @if($order->payment_proof_url)
                                    <a href="{{ $order->payment_proof_url }}" target="_blank" rel="noopener" class="mt-1 block overflow-hidden rounded-lg border border-slate-200">
                                        <img src="{{ $order->payment_proof_url }}" alt="Bukti pembayaran" class="max-h-28 w-full object-contain bg-slate-50">
                                    </a>
                                    <p class="text-center text-[10px] text-slate-400">Lihat bukti pembayaran</p>
                                @else
                                    <p class="text-center text-[10px] text-slate-400">Belum ada bukti pembayaran</p>
                                @endif
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div>{{ $orders->links() }}</div>
    @endif
</div>
@endsection
