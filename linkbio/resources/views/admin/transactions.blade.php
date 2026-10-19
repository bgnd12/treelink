@extends('layouts.admin')

@section('title', 'Transaksi')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-ink-900 sm:text-3xl">Transaksi Marketplace</h1>
            <p class="mt-1 font-medium text-ink-500">Periksa pembayaran pembeli dan konfirmasi transaksi secara manual bila diperlukan.</p>
        </div>
        <div class="flex gap-2">
            @foreach(['all' => 'Semua', 'unpaid' => 'Menunggu Pembayaran', 'paid' => 'Sudah Dibayar'] as $value => $label)
                <a href="{{ route('admin.transactions', ['status' => $value]) }}" class="rounded-full px-3.5 py-2 text-xs font-bold {{ $status === $value ? 'bg-brand-600 text-white' : 'border border-ink-200 bg-white text-ink-600 hover:bg-ink-50' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-[20px] border border-ink-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-ink-50/60 text-[11px] font-bold uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-6 py-4">Transaksi</th>
                        <th class="px-6 py-4">Penjual</th>
                        <th class="px-6 py-4">Pembeli</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-50">
                    @forelse($transactions as $order)
                        <tr class="transition-colors hover:bg-ink-50/30">
                            <td class="px-6 py-4">
                                <p class="font-extrabold text-ink-900">#{{ $order->code }}</p>
                                <p class="mt-1 text-xs text-ink-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 font-semibold text-ink-700">{{ $order->seller?->name ?: '—' }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $order->buyer?->name ?: $order->buyer_name }}</td>
                            <td class="px-6 py-4 font-extrabold text-ink-900">{{ $order->total_label }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $order->is_paid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $order->is_paid ? 'Sudah Dibayar' : 'Menunggu Pembayaran' }}</span>
                                <p class="mt-2 text-[11px] text-ink-400">Pesanan: {{ $order->status_label }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(!$order->is_paid)
                                    <form method="POST" action="{{ route('admin.transactions.confirm-payment', $order) }}" class="inline" onsubmit="return confirm('Konfirmasi pembayaran transaksi ini?')">
                                        @csrf @method('PATCH')
                                        <button class="rounded-xl bg-emerald-500 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-600">Konfirmasi</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.transactions.reject-payment', $order) }}" class="inline" onsubmit="return confirm('Tandai transaksi ini belum dibayar?')">
                                        @csrf @method('PATCH')
                                        <button class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">Batalkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-ink-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="border-t border-ink-100 px-6 py-4">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
