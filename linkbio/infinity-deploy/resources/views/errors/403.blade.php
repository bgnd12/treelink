@extends('layouts.guest')

@section('title', 'Akses Ditolak')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 bg-gradient-to-b from-rose-50 to-white">
    <div class="text-center max-w-md">
        <p class="text-7xl font-extrabold text-rose-500">403</p>
        <h1 class="mt-4 text-2xl font-bold text-ink-900">Akses Ditolak</h1>
        <p class="mt-2 text-ink-500">{{ $exception->getMessage() ?: 'Kamu tidak memiliki izin untuk mengakses halaman ini.' }}</p>
        <a href="{{ route('home') }}" class="mt-8 inline-block px-6 py-3 rounded-full bg-ink-900 text-white font-semibold hover:bg-ink-800 transition">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
