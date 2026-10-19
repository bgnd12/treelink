@extends('layouts.shop')

@section('title', 'Tambah Alamat Baru')

@section('content')
<div class="mx-auto max-w-xl">
    <div class="mb-5 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Tambah Alamat Baru</h1>
            <p class="mt-1 text-sm text-slate-500">Masukkan detail alamat pengiriman untuk pesanan kamu.</p>
        </div>
        <a href="{{ route('checkout.show') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('addresses.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="label" class="mb-1.5 block text-xs font-bold text-slate-700">Label Alamat <span class="font-normal text-slate-400">(opsional, mis. Rumah, Kantor)</span></label>
                <input id="label" name="label" value="{{ old('label') }}" maxlength="60" placeholder="Contoh: Rumah" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                @error('label')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="recipient_name" class="mb-1.5 block text-xs font-bold text-slate-700">Nama Penerima <span class="text-rose-500">*</span></label>
                    <input id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()?->name) }}" required maxlength="120" placeholder="Nama lengkap penerima" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('recipient_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="mb-1.5 block text-xs font-bold text-slate-700">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" required maxlength="40" placeholder="08xxxxxxxxxx" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="address" class="mb-1.5 block text-xs font-bold text-slate-700">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea id="address" name="address" rows="3" required maxlength="500" placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan" class="w-full resize-y rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">{{ old('address') }}</textarea>
                @error('address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="city" class="mb-1.5 block text-xs font-bold text-slate-700">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                    <input id="city" name="city" value="{{ old('city') }}" required maxlength="100" placeholder="Contoh: Bogor" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('city')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="province" class="mb-1.5 block text-xs font-bold text-slate-700">Provinsi <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="province" name="province" value="{{ old('province') }}" maxlength="100" placeholder="Contoh: Jawa Barat" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('province')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="postal_code" class="mb-1.5 block text-xs font-bold text-slate-700">Kode Pos <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="postal_code" name="postal_code" value="{{ old('postal_code') }}" maxlength="12" placeholder="Contoh: 16371" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                @error('postal_code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('checkout.show') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-[#c8ff4d] px-6 py-2.5 text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">
                    Simpan Alamat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
