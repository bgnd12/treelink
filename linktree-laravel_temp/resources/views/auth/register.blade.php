@extends('layouts.auth')

@section('form')
    <h1 class="text-2xl font-extrabold text-ink-900">Buat akun gratis</h1>
    <p class="mt-2 text-sm text-ink-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">Masuk di sini</a></p>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf

        <x-input label="Nama Lengkap" name="name" placeholder="Nama kamu" required autofocus />

        <div>
            <label for="username" class="block text-sm font-semibold text-ink-800 mb-1.5">Username</label>
            <div class="flex rounded-xl border border-ink-200 focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-100 overflow-hidden transition">
                <span class="px-4 flex items-center bg-ink-50 text-ink-400 text-sm border-r border-ink-200">{{ request()->getHost() }}/</span>
                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="namakamu" required
                       class="w-full px-3 py-3 outline-none text-sm placeholder:text-ink-300">
            </div>
            <p class="mt-1.5 text-xs text-ink-400">Ini akan menjadi URL halaman publikmu.</p>
            @error('username')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <x-input label="Email" name="email" type="email" placeholder="kamu@email.com" required />
        <x-input label="Password" name="password" type="password" placeholder="Minimal 8 karakter" required />
        <x-input label="Konfirmasi Password" name="password_confirmation" type="password" placeholder="Ulangi password" required />

        <button type="submit" class="w-full py-3.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
            Daftar Sekarang
        </button>

        <p class="text-xs text-center text-ink-400">Dengan mendaftar, kamu menyetujui Syarat Layanan dan Kebijakan Privasi kami.</p>
    </form>
@endsection