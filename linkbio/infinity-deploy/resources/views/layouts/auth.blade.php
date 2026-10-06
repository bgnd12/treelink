@extends('layouts.guest')

@section('title', ($title ?? 'Autentikasi').' — '.config('app.name'))

@section('content')
<div class="min-h-screen flex">
    {{-- Left brand panel --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-brand-600 to-indigo-700 items-center justify-center p-16 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 text-white max-w-md">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-2xl">
                <span class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-lg">🔗</span>
                TreeLink
            </a>
            <h2 class="mt-10 text-3xl font-extrabold leading-tight">Satu link untuk semua kontenmu.</h2>
            <p class="mt-4 text-brand-100">Bergabung dengan ribuan kreator yang sudah membangun halaman link-in-bio profesional mereka.</p>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex items-center justify-center px-6 py-16 sm:px-10">
        <div class="w-full max-w-md animate-fade-up">
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-xl text-ink-900">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                    TreeLink
                </a>
            </div>

            @yield('form')
        </div>
    </div>
</div>
@endsection
