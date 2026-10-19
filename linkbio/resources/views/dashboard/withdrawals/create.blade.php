@extends('layouts.dashboard')

@section('title', 'Tarik Dana')
@section('page-title', 'Tarik Dana')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    @if(session('status'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#879366]">Saldo penjualan</p>
                <h2 class="mt-1 text-xl font-extrabold text-slate-900">Tarik dana langsung</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">Pilih rekening tujuan dan nominal yang ingin langsung dikirim dari hasil penjualan.</p>
            </div>
            <div class="rounded-2xl bg-[#f1f8df] px-4 py-3 text-right">
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#879366]">Bisa dicairkan</p>
                <p class="mt-1 text-lg font-black text-[#1e2a5b]">Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($availableBalance <= 0)
            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-5 text-sm leading-6 text-amber-800">
                <p class="font-extrabold">Saldo Anda masih nol</p>
                <p>Belum ada dana penjualan yang bisa dicairkan. Saldo akan tersedia setelah pembayaran pembeli terverifikasi atau dikonfirmasi admin.</p>
            </div>
        @elseif($accounts->isEmpty())
            <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
                <p class="font-bold text-slate-800">Belum ada rekening tujuan</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan rekening terlebih dahulu sebelum mengajukan pencairan.</p>
                <a href="{{ route('dashboard.accounts.index', ['from' => 'withdrawal']) }}" class="mt-4 inline-flex rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b]">Kelola Rekening</a>
            </div>
        @else
            <form action="{{ route('dashboard.withdrawals.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="bank_account_id" class="mb-1.5 block text-xs font-bold text-slate-700">Kirim ke rekening</label>
                    <select id="bank_account_id" name="bank_account_id" required class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                        <option value="">Pilih rekening tujuan</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @selected(old('bank_account_id') == $account->id)>{{ $account->bank_name }} — {{ $account->account_name }} ({{ $account->masked_account_number }})</option>
                        @endforeach
                    </select>
                    @error('bank_account_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="amount" class="mb-1.5 block text-xs font-bold text-slate-700">Nominal pencairan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input id="amount" name="amount" type="number" min="1" max="{{ $availableBalance }}" step="1" value="{{ old('amount') }}" required placeholder="Masukkan nominal pencairan" class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">Kamu bisa menarik nominal berapa saja selama tidak melebihi saldo yang tersedia.</p>
                    @error('amount')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-xs leading-5 text-blue-800">Pengajuan akan diperiksa super admin terlebih dahulu. Bukti struk gambar akan tersedia setelah pengajuan diterima.</div>
                <button type="submit" class="w-full rounded-xl bg-[#c8ff4d] px-5 py-3 text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Ajukan Pencairan</button>
            </form>
        @endif
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h3 class="text-lg font-extrabold text-slate-900">Riwayat pencairan</h3>
        @forelse($withdrawals as $withdrawal)
            <div class="mt-4 flex flex-col gap-2 border-b border-slate-100 pb-4 last:border-0 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-800">Rp {{ number_format((float) $withdrawal->amount, 0, ',', '.') }}</p>
                    @if($withdrawal->proof_url)
                        <a href="{{ $withdrawal->proof_url }}" target="_blank" rel="noopener" class="mt-1 inline-block text-[11px] font-semibold text-indigo-600 hover:underline">Lihat bukti pencairan</a>
                    @elseif($withdrawal->status === 'rejected' && $withdrawal->note)
                        <p class="mt-1 text-[11px] font-semibold text-rose-600">{{ $withdrawal->note }}</p>
                    @endif
                    <p class="mt-1 text-xs text-slate-400">{{ $withdrawal->bankAccount?->bank_name }} · {{ $withdrawal->bankAccount?->masked_account_number }} · {{ $withdrawal->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <span class="w-fit rounded-full px-2.5 py-1 text-[10px] font-bold {{ $withdrawal->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($withdrawal->status === 'rejected' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">{{ $withdrawal->status_label }}</span>
            </div>
        @empty
            <p class="mt-3 text-sm text-slate-500">Belum ada riwayat penarikan.</p>
        @endforelse
    </div>
</div>
@endsection
