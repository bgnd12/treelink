@extends('layouts.guest')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 bg-gradient-to-b from-brand-50 to-white">
    <div class="text-center max-w-md">
        <p class="text-7xl font-extrabold text-brand-600">404</p>
        <h1 class="mt-4 text-2xl font-bold text-ink-900">Halaman atau username tidak ditemukan</h1>
        <p class="mt-2 text-ink-500">Sepertinya halaman yang kamu cari tidak ada, atau username tersebut belum digunakan siapa pun.</p>
        <a href="{{ route('home') }}" class="mt-8 inline-block px-6 py-3 rounded-full bg-brand-600 text-white font-semibold hover:bg-brand-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
