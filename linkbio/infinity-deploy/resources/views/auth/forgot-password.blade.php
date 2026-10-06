@extends('layouts.auth')

@section('form')
    <h1 class="text-2xl font-extrabold text-ink-900">Lupa password?</h1>
    <p class="mt-2 text-sm text-ink-600">Masukkan email kamu dan kami akan mengirimkan link untuk mengatur ulang password.</p>

    @if (session('status'))
        <div class="mt-5 p-3.5 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf
        <x-input label="Email" name="email" type="email" placeholder="kamu@email.com" required autofocus />

        <button type="submit" class="w-full py-3.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
            Kirim Link Reset Password
        </button>

        <p class="text-sm text-center text-ink-500">
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">&larr; Kembali ke halaman masuk</a>
        </p>
    </form>
@endsection
