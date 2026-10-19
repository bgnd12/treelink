@extends('layouts.dashboard')

@section('title', __('Pesanan & Penghasilan'))
@section('page-title', __('Pesanan & Penghasilan'))

@section('content')
<div class="mx-auto max-w-[1500px] space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#879366]">My Shop</p>
        <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">{{ __('Pesanan & Penghasilan') }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __('Kelola pesanan dan pantau penghasilan dari pembayaran yang sudah terverifikasi.') }}</p>
        </div>
        <a href="{{ route('dashboard.withdrawals.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">
            {{ __('Tarik Dana') }}
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500">{{ __('Penghasilan tercatat') }}</p>
            <p class="mt-3 text-2xl font-black text-[#14213d]">Rp {{ number_format((float) $recordedRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ $paidOrderCount }} {{ __('pesanan terbayar') }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5 shadow-sm">
            <p class="text-xs font-bold text-emerald-700">{{ __('Pesanan selesai') }}</p>
            <p class="mt-3 text-2xl font-black text-emerald-800">Rp {{ number_format((float) $completedRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-emerald-700/70">{{ __('Pembayaran terverifikasi dan status selesai') }}</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-5 shadow-sm">
            <p class="text-xs font-bold text-amber-700">{{ __('Masih diproses') }}</p>
            <p class="mt-3 text-2xl font-black text-amber-800">Rp {{ number_format((float) $pendingRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-amber-700/70">{{ __('Belum menjadi saldo pencairan') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500">{{ __('Komisi & pencairan') }}</p>
            <p class="mt-3 text-lg font-black text-slate-700">{{ __('Belum tersedia') }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ __('Menunggu ledger dan payout resmi') }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4 text-sm leading-6 text-blue-900">
        <span class="font-extrabold">{{ __('Catatan keamanan:') }}</span>
        {{ __('Penghasilan hanya dihitung dari pembayaran yang sudah diverifikasi. TreeLink belum mengaktifkan wallet, komisi, atau pencairan otomatis.') }}
    </div>

    <nav class="flex flex-wrap gap-2 border-b border-slate-200 pb-4" aria-label="{{ __('Filter pesanan') }}">
        @foreach(array_merge(['all' => __('Semua')], \App\Models\Order::STATUSES) as $value => $label)
            <a href="{{ route('dashboard.orders.index', ['status' => $value]) }}" class="rounded-full px-3.5 py-2 text-xs font-bold transition {{ $status === $value ? 'bg-[#1e2a5b] text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">{{ __($label) }}</a>
        @endforeach
    </nav>

    @if($orders->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1f3e9] text-[#1e2a5b]"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5h6m-8 3h10m-9 4h8m-8 4h5M6 3h9l3 3v15H6V3Z"/></svg></div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">{{ __('Belum ada pesanan') }}</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">{{ __('Pesanan dari pembeli akan muncul di sini secara otomatis.') }}</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($orders as $order)
                @php
                    $statusTone = [
                        'awaiting_payment' => 'bg-amber-50 text-amber-700',
                        'awaiting_confirmation' => 'bg-amber-50 text-amber-700',
                        'processing' => 'bg-blue-50 text-blue-700',
                        'shipped' => 'bg-violet-50 text-violet-700',
                        'completed' => 'bg-emerald-50 text-emerald-700',
                        'cancelled' => 'bg-rose-50 text-rose-700',
                    ][$order->status_code] ?? 'bg-slate-100 text-slate-600';
                @endphp
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-3.5">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                            <span class="text-sm font-extrabold text-slate-900">#{{ $order->code }}</span>
                            <span class="text-xs text-slate-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $statusTone }}">{{ __($order->status_label) }}</span>
                            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $order->is_paid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ __($order->payment_status_label) }}</span>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900">{{ $order->total_label }}</span>
                    </div>

                    <div class="grid gap-5 px-5 py-4 lg:grid-cols-[minmax(0,1.35fr)_minmax(260px,1fr)_240px] lg:items-center">
                        <div class="min-w-0">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-3 {{ !$loop->last ? 'mb-3 border-b border-slate-100 pb-3' : '' }}">
                                    @if($item->product?->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="h-14 w-14 shrink-0 rounded-xl border border-slate-100 object-cover">
                                    @else
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="m4 16 4.5-4.5a2 2 0 0 1 2.8 0L16 16m-2-2 1.5-1.5a2 2 0 0 1 2.8 0L20 16M5 20h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1Z"/></svg></div>
                                    @endif
                                    <div class="min-w-0 flex-1"><p class="truncate text-sm font-extrabold text-slate-800">{{ $item->name_with_variant }}</p><p class="mt-1 text-xs text-slate-400">{{ $item->quantity }} x {{ $item->price_label }}</p></div>
                                    <span class="shrink-0 text-sm font-extrabold text-slate-900">{{ $item->subtotal_label }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-slate-100 pt-4 text-xs text-slate-500 lg:border-l lg:border-t-0 lg:pl-5 lg:pt-0">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-slate-400">{{ __('Pembeli') }}</p>
                            <p class="mt-2 font-bold text-slate-700">{{ $order->shipping_name ?: $order->buyer_name }}</p>
                            <p class="mt-0.5">{{ $order->shipping_phone ?: $order->buyer_contact }}</p>
                            <p class="mt-2 leading-5">{{ $order->shipping_summary ?: __('Alamat tidak tersedia') }}</p>
                            @if($order->note)<p class="mt-2"><span class="font-bold text-slate-700">{{ __('Catatan') }}:</span> {{ $order->note }}</p>@endif
                            @if($order->tracking_number)<p class="mt-2"><span class="font-bold text-slate-700">{{ __('Resi') }}:</span> {{ $order->tracking_number }}</p>@endif
                        </div>

                        <div class="border-t border-slate-100 pt-4 lg:border-l lg:border-t-0 lg:pl-5 lg:pt-0">
                            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <label for="status-{{ $order->id }}" class="text-xs font-bold text-slate-700">{{ __('Status pesanan') }}</label>
                                <select id="status-{{ $order->id }}" name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs text-slate-700 outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                                    @foreach(\App\Models\Order::STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($order->status_code === $value)>{{ __($label) }}</option>
                                    @endforeach
                                </select>
                                <input name="tracking_number" value="{{ $order->tracking_number }}" maxlength="100" placeholder="{{ __('No. resi (opsional)') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                                <button type="submit" class="w-full rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-xs font-extrabold text-[#1e2a5b] transition hover:bg-[#ddff91]">{{ __('Simpan Status') }}</button>
                            </form>

                            <div class="mt-3 border-t border-slate-100 pt-3 text-center text-[11px] text-slate-500">
                                {{ __('Status pembayaran ditetapkan oleh gateway atau proses verifikasi resmi, bukan oleh penjual.') }}
                                @if($order->payment_proof_url)<a href="{{ $order->payment_proof_url }}" target="_blank" rel="noopener" class="mt-1 block font-semibold text-indigo-600 hover:underline">{{ __('Lihat bukti pembayaran') }}</a>@endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div>{{ $orders->links() }}</div>
    @endif
</div>
@endsection
