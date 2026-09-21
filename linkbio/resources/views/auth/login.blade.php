@extends('layouts.auth')

@section('form')
    <h1 class="text-2xl font-extrabold text-ink-900">Selamat datang kembali</h1>
    <p class="mt-2 text-sm text-ink-600">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline">Daftar gratis</a></p>

    @if (session('status'))
        <div class="mt-5 p-3.5 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <x-input label="Email atau Username" name="login" placeholder="kamu@email.com" required autofocus />
        <x-input label="Password" name="password" type="password" placeholder="Password kamu" required />

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-ink-600">
                <input type="checkbox" name="remember" class="rounded border-ink-300 text-brand-600 focus:ring-brand-400">
                Ingat saya
            </label>
            <a href="{{ route('password.request') }}" class="font-semibold text-brand-600 hover:underline">Lupa password?</a>
        </div>

        <button type="submit" class="w-full py-3.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
            Masuk
        </button>
    </form>

    <div class="mt-6 p-4 rounded-xl bg-ink-50 text-xs text-ink-500">
        <p class="font-semibold text-ink-700 mb-1">Akun demo untuk uji coba:</p>
        <p>Admin: admin@TreeLink.test / password</p>
        <p>User: demo@TreeLink.test / password</p>
    </div>
@endsection
