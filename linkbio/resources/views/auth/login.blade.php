@extends('layouts.auth')

@section('title', 'Masuk Akun — '.config('app.name'))
@section('auth_heading', __('Selamat Datang Kembali'))
@section('auth_subheading', __('Masuk untuk mengelola TreeLink dan terhubung dengan komunitasmu.'))

@section('form')
<form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPass: false }" autocomplete="on">
    @csrf

    {{-- Email or Username --}}
    <div>
        <label for="login" class="block text-xs font-bold text-slate-700 mb-1">{{ __('Email atau Username') }}</label>
        <div class="relative flex items-center">
            <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
            <input type="text" name="login" id="login" value="{{ old('login') }}" placeholder="Username atau email" required autofocus
                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200/80 bg-slate-50/70 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-[#879366] focus:bg-white focus:ring-4 focus:ring-[#edf0e7]">
        </div>
        @error('login')
            <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div>
        <div class="flex items-center justify-between mb-1">
            <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
            <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-[#1e2a5b] hover:text-[#4d7c0f] hover:underline">{{ __('Lupa password?') }}</a>
        </div>
        <div class="relative flex items-center">
            <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
            <input :type="showPass ? 'text' : 'password'" name="password" id="password" placeholder="Enter your password" required
                   class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200/80 bg-slate-50/70 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-[#879366] focus:bg-white focus:ring-4 focus:ring-[#edf0e7]">
            <button type="button" @click="showPass = !showPass" class="absolute right-3 text-slate-400 hover:text-slate-600 transition" tabindex="-1">
                <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg x-show="showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                </svg>
            </button>
        </div>
        @error('password')
            <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="flex items-center gap-2 pt-1">
        <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300 text-[#879366] focus:ring-[#c8ff4d]">
        <label for="remember" class="text-xs text-slate-600 font-medium select-none cursor-pointer">
            {{ __('Ingat saya') }}
        </label>
    </div>

    {{-- Submit Button --}}
    <button type="submit" class="w-full mt-2 py-3 px-4 rounded-xl bg-[#c8ff4d] hover:bg-[#ddff91] text-[#1e2a5b] font-extrabold text-sm shadow-sm transition hover:shadow-md flex items-center justify-center gap-2">
        <span>{{ __('Masuk') }}</span>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
        </svg>
    </button>

    {{-- Social Logins Divider --}}
    <div class="relative my-4 flex items-center justify-center">
        <div class="w-full border-t border-slate-200/80"></div>
        <span class="absolute bg-white px-3 text-[11px] text-slate-400 font-medium">{{ __('atau lanjutkan dengan') }}</span>
    </div>

    {{-- Social Buttons (Google & Apple) --}}
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-100 text-xs font-bold text-slate-700 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.49 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Google</span>
        </a>
        <button type="button" class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-100 text-xs font-bold text-slate-700 transition">
            <svg class="w-4 h-4 fill-current text-slate-900" viewBox="0 0 24 24">
                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.37c.62-.77 1.05-1.84.93-2.92-.93.04-2.09.63-2.73 1.4-.56.65-.99 1.74-.86 2.79 1.04.08 2.08-.54 2.66-1.27z"/>
            </svg>
            <span>Apple</span>
        </button>
    </div>

    {{-- Switch to Register Link --}}
    <p class="pt-2 text-center text-xs text-slate-500 font-medium">
        {{ __('Belum punya akun?') }} <a href="{{ route('register') }}" class="font-extrabold text-[#1e2a5b] hover:text-[#4d7c0f] hover:underline">{{ __('Daftar gratis') }}</a>
    </p>
</form>
@endsection
