@extends('layouts.auth')

@section('form')
    <h1 class="text-2xl font-extrabold text-ink-900">Atur ulang password</h1>
    <p class="mt-2 text-sm text-ink-600">Masukkan password baru untuk akunmu.</p>

    <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-input label="Email" name="email" type="email" :value="$email" placeholder="kamu@email.com" required autofocus />
        <x-input label="Password Baru" name="password" type="password" placeholder="Minimal 8 karakter" required />
        <x-input label="Konfirmasi Password Baru" name="password_confirmation" type="password" placeholder="Ulangi password baru" required />

        <button type="submit" class="w-full py-3.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
            Reset Password
        </button>
    </form>
@endsection
