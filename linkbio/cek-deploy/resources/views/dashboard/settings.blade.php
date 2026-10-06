@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-2xl space-y-6">

    <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-1">Akun</h2>
        <p class="text-sm text-ink-500 mb-5">Perbarui email dan password akunmu.</p>

        <form method="POST" action="{{ route('dashboard.settings.account') }}" class="space-y-5">
            @csrf
            <x-input label="Email" name="email" type="email" :value="$user->email" required />
            <x-input label="Password Baru (opsional)" name="password" type="password" placeholder="Kosongkan jika tidak diubah" />
            <x-input label="Konfirmasi Password Baru" name="password_confirmation" type="password" placeholder="Ulangi password baru" />

            <button type="submit" class="px-6 py-3 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-1">URL Profil Publik</h2>
        <p class="text-sm text-ink-500 mb-4">Bagikan link ini kepada audiensmu.</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" readonly value="{{ $user->publicUrl() }}" class="flex-1 px-4 py-3 rounded-xl border border-ink-200 bg-ink-50 text-sm text-ink-600">
            <button type="button" onclick="copyProfileUrl()" class="px-5 py-3 rounded-xl border border-ink-200 font-semibold text-sm hover:border-ink-400 transition">Salin</button>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-rose-200 p-6 shadow-card">
        <h2 class="font-bold text-rose-600 mb-1">Zona Berbahaya</h2>
        <p class="text-sm text-ink-500 mb-4">Menghapus akun akan menghapus seluruh data profil, link, dan statistik secara permanen.</p>

        <form method="POST" action="{{ route('dashboard.settings.destroy') }}" onsubmit="return confirm('Yakin ingin menghapus akun secara permanen? Tindakan ini tidak dapat dibatalkan.');" class="flex items-center gap-3">
            @csrf
            @method('DELETE')
            <label class="flex items-center gap-2 text-sm text-ink-600">
                <input type="checkbox" name="confirm_delete" required class="rounded border-ink-300 text-rose-600 focus:ring-rose-400">
                Saya mengerti risikonya
            </label>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                Hapus Akun
            </button>
        </form>
    </div>
</div>
@endsection
