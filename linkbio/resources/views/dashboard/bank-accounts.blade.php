@extends('layouts.dashboard')

@section('title', 'Rekening')
@section('page-title', 'Rekening')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    @if(session('status'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
    @endif

    @if(request('from') === 'shop' && $accounts->isEmpty())
        <div class="rounded-2xl border border-[#dce8bd] bg-[#f1f8df] px-5 py-4 text-sm leading-6 text-[#536239]">
            <p class="font-extrabold text-[#1e2a5b]">Belum tersambung ke rekening</p>
            <p>Tambahkan rekening untuk menerima pembayaran dari produk yang kamu jual di Shop.</p>
        </div>
    @endif

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#879366]">Pencairan dana</p>
                <h2 class="mt-1 text-xl font-extrabold text-slate-900">Rekening pembayaran</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">Hubungkan rekening bank untuk menerima hasil penjualan dari TreeLink Shop.</p>
            </div>
            <span class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#f1f3e9] text-[#1e2a5b] sm:flex">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M3 21h18M12 3l9 5H3l9-5z"/></svg>
            </span>
        </div>

        @forelse($accounts as $account)
            <div class="mt-6 flex flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-sm font-extrabold text-[#1e2a5b] shadow-sm">{{ strtoupper(substr($account->bank_name, 0, 2)) }}</span>
                    <div>
                        <p class="font-bold text-slate-900">{{ $account->bank_name }} <span class="ml-1 rounded-full bg-emerald-100 px-2 py-1 text-[10px] text-emerald-700">Terhubung</span></p>
                        <p class="mt-1 text-sm text-slate-500">{{ $account->account_name }} · {{ $account->masked_account_number }}</p>
                    </div>
                </div>
                <form action="{{ route('dashboard.accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Hapus rekening ini?')">
                    @csrf @method('DELETE')
                    <button class="rounded-xl border border-rose-200 px-4 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                </form>
            </div>
        @empty
            <div class="mt-6 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center">
                <p class="font-bold text-slate-800">Belum ada rekening yang tersambung</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan rekening di bawah untuk mulai menjual produk.</p>
            </div>
        @endforelse
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h3 class="text-lg font-extrabold text-slate-900">{{ $accounts->isEmpty() ? 'Tambahkan rekening' : 'Tambah rekening lain' }}</h3>
        <form action="{{ route('dashboard.accounts.store') }}" method="POST" class="mt-5 grid gap-4 sm:grid-cols-2">
            @csrf
            <div>
                <label for="bank_name" class="mb-1.5 block text-xs font-bold text-slate-700">Nama bank</label>
                <input id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required placeholder="Contoh: BCA" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                @error('bank_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="account_name" class="mb-1.5 block text-xs font-bold text-slate-700">Nama pemilik rekening</label>
                <input id="account_name" name="account_name" value="{{ old('account_name') }}" required placeholder="Sesuai buku rekening" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                @error('account_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="account_number" class="mb-1.5 block text-xs font-bold text-slate-700">Nomor rekening</label>
                <input id="account_number" name="account_number" value="{{ old('account_number') }}" required inputmode="numeric" placeholder="Contoh: 1234567890" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                @error('account_number')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end justify-end">
                <button type="submit" class="w-full rounded-xl bg-[#c8ff4d] px-5 py-2.5 text-sm font-bold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91] sm:w-auto">Sambungkan Rekening</button>
            </div>
        </form>
    </section>
</div>
@endsection
