@extends('layouts.guest')

@section('title', ($title ?? 'Autentikasi').' — '.config('app.name'))

@section('content')
<div class="auth-page min-h-screen flex">
    {{-- Left brand panel --}}
    <div class="auth-brand hidden lg:flex lg:w-[48%] relative items-center p-16 overflow-hidden">
        <div class="auth-brand-grid absolute inset-0"></div>
        <div class="relative z-10 text-white max-w-lg">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-2xl">
                <span class="auth-logo">↗</span>
                TreeLink
            </a>
            <p class="auth-eyebrow mt-20">YOUR SPACE ON THE INTERNET</p>
            <h2 class="mt-5 text-5xl font-extrabold leading-[.98] tracking-[-.06em]">Semua yang kamu buat,<br><span>di satu tempat.</span></h2>
            <p class="mt-6 max-w-sm text-slate-300 leading-relaxed">Buat halaman yang terasa seperti milikmu. Bagikan karya, toko, dan cerita dengan satu link.</p>
            <div class="auth-preview mt-12">
                <div class="auth-preview-top"><span class="auth-preview-avatar">T</span><span><b>tree.link</b><small>creative space</small></span><i>•••</i></div>
                <div class="auth-preview-title">Make room<br>for what matters.</div>
                <div class="auth-preview-link auth-preview-link-main"><span>✦</span>Latest work <b>↗</b></div>
                <div class="auth-preview-link"><span>◎</span>Instagram <b>↗</b></div>
                <div class="auth-preview-link"><span>▶</span>Watch the story <b>↗</b></div>
            </div>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="auth-form-side flex-1 flex items-center justify-center px-6 py-12 sm:px-10 lg:px-16">
        <div class="w-full max-w-[430px] animate-fade-up">
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-xl text-ink-900">
                    <span class="auth-logo auth-logo-small">↗</span>
                    TreeLink
                </a>
            </div>

            @yield('form')
        </div>
    </div>
</div>
@endsection