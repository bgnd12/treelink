@extends('layouts.guest')

@section('title', ($title ?? 'Autentikasi').' — '.config('app.name'))

@section('content')
<style>
    html,
    body {
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }

    @media (min-width: 1024px) {
        html,
        body {
            overflow: hidden !important;
        }
    }
</style>
<div class="relative h-auto min-h-[100dvh] lg:h-[100dvh] w-full bg-[#031525] text-white flex items-center justify-center p-3 sm:p-6 md:p-8 lg:p-10 overflow-x-hidden overflow-y-visible lg:overflow-hidden selection:bg-[#c8ff4d] selection:text-[#031525]">
    {{-- Background lush leaves image with soft dark gradient overlay --}}
    <div class="absolute inset-0 z-0 pointer-events-none opacity-40 mix-blend-luminosity bg-cover bg-center"
         style="background-image: url('{{ asset('images/hero-portrait.jpg') }}'); filter: blur(28px) scale(1.15);"></div>
    <div class="absolute inset-0 z-0 bg-radial-gradient from-transparent via-[#031525]/85 to-[#020d18] pointer-events-none"></div>

    {{-- Scattered Decorative Leaves SVG icons floating in background --}}
    <div class="absolute top-10 left-12 w-8 h-8 text-[#a3e635]/60 rotate-[-25deg] pointer-events-none select-none blur-[0.5px]">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
    </div>
    <div class="absolute top-1/3 left-6 w-10 h-10 text-[#c8ff4d]/40 rotate-[35deg] pointer-events-none select-none blur-[1px]">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
    </div>
    <div class="absolute bottom-20 left-1/4 w-7 h-7 text-[#a3e635]/40 rotate-[-45deg] pointer-events-none select-none">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
    </div>
    <div class="absolute top-16 right-1/3 w-6 h-6 text-[#c8ff4d]/30 rotate-[60deg] pointer-events-none select-none">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
    </div>
    <div class="absolute bottom-12 right-12 w-9 h-9 text-[#c8ff4d]/50 rotate-[15deg] pointer-events-none select-none">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
    </div>

    {{-- Main Container: Left Story / Graphic + Right Glass Card --}}
    <div class="relative z-10 w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
        
        {{-- ================= LEFT HERO SHOWCASE ================= --}}
        <div class="lg:col-span-6 xl:col-span-7 flex flex-col justify-center relative">
            
            {{-- Circular Portrait with Orbiting Feature Badges --}}
            <div class="relative w-full max-w-[390px] lg:max-w-[400px] mx-auto lg:mx-0 aspect-square flex items-center justify-center my-2 sm:my-6 lg:my-0">
                
                {{-- Thin orbital rings --}}
                <div class="absolute inset-2 sm:inset-4 rounded-full border border-sky-400/20 pointer-events-none"></div>
                <div class="absolute -inset-4 sm:-inset-6 rounded-full border border-emerald-400/25 pointer-events-none"></div>

                {{-- Center Portrait Photo --}}
                <div class="relative w-56 h-56 sm:w-72 sm:h-72 rounded-full overflow-hidden border-2 border-emerald-300/30 shadow-[0_0_50px_rgba(34,197,94,0.18)]">
                    <img src="{{ asset('images/hero-portrait.jpg') }}" alt="TreeLink student community"
                         class="w-full h-full object-cover object-top scale-105">
                </div>

                {{-- Orbit Dot Accent --}}
                <span class="absolute top-[18%] left-[22%] w-2 h-2 rounded-full bg-[#c8ff4d] shadow-[0_0_8px_#c8ff4d]"></span>
                <span class="absolute bottom-[20%] right-[16%] w-2.5 h-2.5 rounded-full bg-cyan-400 shadow-[0_0_10px_#38bdf8]"></span>

                {{-- Badge 1: Connect with new people (Top-Left) --}}
                <div class="absolute top-4 sm:top-8 left-0 sm:left-2 flex items-center gap-2.5 px-3.5 py-2 rounded-full bg-[#062438]/85 backdrop-blur-md border border-cyan-400/30 shadow-lg transition-transform hover:scale-105">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#c8ff4d] text-[#031525]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                    <div class="leading-tight pr-1">
                        <p class="text-xs font-extrabold text-white">Connect</p>
                        <p class="text-[10px] text-cyan-200/80 font-medium">with new people</p>
                    </div>
                </div>

                {{-- Badge 2: Learn new skills (Right) --}}
                <div class="absolute top-1/2 -translate-y-1/2 -right-2 sm:-right-4 flex items-center gap-2.5 px-3.5 py-2 rounded-full bg-[#062438]/85 backdrop-blur-md border border-emerald-400/30 shadow-lg transition-transform hover:scale-105">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#a3e635] text-[#031525]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </span>
                    <div class="leading-tight pr-1">
                        <p class="text-xs font-extrabold text-white">Learn</p>
                        <p class="text-[10px] text-emerald-200/80 font-medium">new skills</p>
                    </div>
                </div>

                {{-- Badge 3: Grow your future (Bottom-Left) --}}
                <div class="absolute bottom-4 sm:bottom-8 left-2 sm:left-4 flex items-center gap-2.5 px-3.5 py-2 rounded-full bg-[#062438]/85 backdrop-blur-md border border-lime-400/30 shadow-lg transition-transform hover:scale-105">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#c8ff4d] text-[#031525]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </span>
                    <div class="leading-tight pr-1">
                        <p class="text-xs font-extrabold text-white">Grow</p>
                        <p class="text-[10px] text-lime-200/80 font-medium">your future</p>
                    </div>
                </div>

                {{-- Handwriting Annotation 1: Langkah kecil untuk masa depan besar. --}}
                <div class="absolute -top-6 right-0 sm:right-6 text-right font-['Caveat',cursive] text-lime-300 text-lg sm:text-xl font-bold tracking-wide rotate-[-4deg] pointer-events-none drop-shadow">
                    <span>Langkah kecil</span><br>
                    <span class="text-lime-200 text-sm sm:text-base">untuk masa depan besar.</span>
                    <div class="w-16 h-1 ml-auto mt-0.5 border-b-2 border-lime-400/80 rounded-full"></div>
                </div>
            </div>

            {{-- Big Headline & Description --}}
            <div class="mt-4 sm:mt-6 lg:mt-3 max-w-xl lg:max-w-[430px]">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-[1.15]">
                    More than just a link &mdash; it’s your <span class="text-[#c8ff4d] block sm:inline">digital journey.</span>
                </h1>
                
                {{-- Green underline flourish --}}
                <div class="w-36 sm:w-48 h-1.5 bg-[#c8ff4d] rounded-full mt-2 sm:mt-2.5 opacity-90 shadow-[0_0_12px_rgba(200,255,77,0.5)]"></div>

                <p class="mt-4 sm:mt-5 text-sm sm:text-base text-slate-300/90 leading-relaxed max-w-lg">
                    TreeLink connects <strong class="text-white font-bold">students, mentors, and learning communities.</strong> Meet new people, discover new opportunities, and grow together.
                </p>

                {{-- Handwriting Annotation 2: Small steps, big connections --}}
                <div class="mt-4 sm:mt-6 flex items-center gap-3 font-['Caveat',cursive] text-lime-300 text-base sm:text-lg font-bold rotate-[-2deg]">
                    <span>Small steps, big connections</span>
                    <svg class="w-5 h-5 text-lime-400/80 -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </div>
        </div>

        {{-- ================= RIGHT AUTH CARD (Glassmorphic White Card) ================= --}}
        <div class="lg:col-span-6 xl:col-span-5 flex justify-center lg:justify-end">
            <div class="relative w-full max-w-[460px] rounded-[2.2rem] bg-white/95 backdrop-blur-xl p-5 sm:p-8 lg:p-6 lg:-translate-y-3 text-slate-900 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5),0_0_35px_rgba(200,255,77,0.12)] border border-white/60">
                
                {{-- Decorative leaves on card corners --}}
                <div class="absolute -top-3.5 -right-3.5 w-10 h-10 text-[#a3e635] rotate-[45deg] pointer-events-none drop-shadow-md">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
                </div>
                <div class="absolute -bottom-3 -left-3 w-9 h-9 text-[#84cc16] rotate-[-70deg] pointer-events-none drop-shadow-md">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
                </div>

                {{-- Brand Header with Leaf Logo --}}
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('home') }}" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm border border-emerald-100/60 hover:scale-105 transition-transform" title="TreeLink Home">
                        <svg class="h-6 w-6 text-[#4d7c0f]" viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 leading-tight">
                            @yield('auth_heading', 'Create Account')
                        </h2>
                        <p class="text-xs text-slate-500 font-medium">
                            @yield('auth_subheading', 'Join TreeLink and be part of a growing community.')
                        </p>
                    </div>
                </div>

                {{-- Status Messages / Validation --}}
                @if (session('status'))
                    <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-200/60 p-3 text-xs font-semibold text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div role="alert" class="mt-4 rounded-xl bg-rose-50 border border-rose-200/60 p-3 text-xs font-medium text-rose-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>&bull; {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- The Form Slot --}}
                <div class="mt-5">
                    @yield('form')
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
