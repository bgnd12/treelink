@extends('layouts.admin')

@section('title', 'Pengajuan Dana')

@section('content')
<div class="space-y-8">
    @if(session('status'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-ink-900 sm:text-3xl">Pengajuan Dana</h1>
            <p class="mt-1 font-medium text-ink-500">Periksa pengajuan pencairan user, lalu terima atau tolak secara manual.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach(['pending' => 'Menunggu', 'paid' => 'Diterima', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $value => $label)
                <a href="{{ route('admin.withdrawals', ['status' => $value]) }}" class="rounded-full px-3.5 py-2 text-xs font-bold {{ $status === $value ? 'bg-brand-600 text-white' : 'border border-ink-200 bg-white text-ink-600 hover:bg-ink-50' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-[20px] border border-ink-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead class="bg-ink-50/60 text-[11px] font-bold uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-6 py-4">Pengaju</th>
                        <th class="px-6 py-4">Rekening tujuan</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Diajukan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-50">
                    @forelse($withdrawals as $withdrawal)
                        <tr class="align-top transition-colors hover:bg-ink-50/30">
                            <td class="px-6 py-4">
                                <p class="font-extrabold text-ink-900">{{ $withdrawal->user?->name ?: '-' }}</p>
                                <p class="mt-1 text-xs text-ink-400">{{ $withdrawal->user?->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-ink-700">{{ $withdrawal->bankAccount?->bank_name ?: '-' }}</p>
                                <p class="mt-1 text-xs text-ink-400">{{ $withdrawal->bankAccount?->account_name }} · {{ $withdrawal->bankAccount?->masked_account_number }}</p>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-ink-900">Rp {{ number_format((float) $withdrawal->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-xs text-ink-500">{{ $withdrawal->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $withdrawal->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($withdrawal->status === 'rejected' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">{{ $withdrawal->status_label }}</span>
                                @if($withdrawal->note)
                                    <p class="mt-2 max-w-[190px] text-[11px] leading-4 text-ink-400">{{ $withdrawal->note }}</p>
                                @endif
                                @if($withdrawal->proof_url)
                                    <a href="{{ $withdrawal->proof_url }}" target="_blank" rel="noopener" class="mt-2 block text-[11px] font-bold text-indigo-600 hover:underline">Lihat bukti struk</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($withdrawal->status === 'pending')
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" onsubmit="return confirm('Terima pengajuan dan buat bukti struk?')">
                                            @csrf @method('PATCH')
                                            <button class="rounded-xl bg-emerald-500 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-600">Terima</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" onsubmit="return confirm('Tolak pengajuan ini?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="note" value="Pengajuan ditolak oleh super admin.">
                                            <button class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-ink-400">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-ink-500">Belum ada pengajuan dana.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($withdrawals->hasPages())
            <div class="border-t border-ink-100 px-6 py-4">{{ $withdrawals->links() }}</div>
        @endif
    </div>
</div>
@endsection
