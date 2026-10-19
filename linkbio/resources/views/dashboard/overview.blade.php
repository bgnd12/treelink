@extends('layouts.dashboard')

@section('title', __('My TreeLink'))
@section('page-title', __('Selamat datang, :name', ['name' => auth()->user()->name]))

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">
        <section>
            <div class="mb-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">{{ __('Kelola halaman publikmu') }}</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-[#14213d]">{{ __('My TreeLink') }}</h2>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($pages as $page)
                    @php
                        $pageProfile = $page->profile ?: $profile;
                    @endphp
                    <div class="group relative rounded-[2rem] bg-gradient-to-br from-[#1e2a5b] via-[#273b78] to-[#4f46e5] p-5 text-white shadow-[0_18px_40px_-18px_rgba(30,42,91,.7)] transition hover:-translate-y-1 hover:shadow-xl {{ $page->id === $activePageId ? 'ring-4 ring-[#c8ff4d]/40' : '' }}">
                        <form method="POST" action="{{ route('dashboard.profile-pages.switch', $page) }}" class="absolute inset-0 z-0">
                            @csrf
                            <button type="submit" aria-label="{{ __('Pilih :name', ['name' => $page->name]) }}" class="h-full w-full cursor-pointer"></button>
                        </form>
                        <div class="relative z-10 pointer-events-none">
                            <div class="flex items-center justify-between">
                                <span class="rounded-full bg-[#c8ff4d] px-3 py-1 text-[11px] font-extrabold text-[#14213d]">{{ __('Draft tersimpan') }}</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15"><svg class="h-4 w-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></span>
                            </div>
                            <div class="mt-7 flex items-center gap-3">
                                <img src="{{ $pageProfile->avatar_url }}" class="h-16 w-16 rounded-full border-4 border-white/30 object-cover" alt="{{ $pageProfile->display_name ?: $page->name }}">
                                <div class="min-w-0">
                                    <p class="truncate text-xl font-extrabold">{{ $pageProfile->display_name ?: $page->name }}</p>
                                    <p class="truncate text-sm text-blue-100">{{ '@'.$page->username }}</p>
                                </div>
                            </div>
                            <div class="mt-7 flex items-end justify-between">
                                <div>
                                    <p class="text-3xl font-black">{{ $page->active_links_count }}</p>
                                    <p class="text-xs text-blue-100">{{ __('tautan aktif di profil') }}</p>
                                </div>
                                <span class="rounded-full bg-white px-4 py-2 text-xs font-extrabold text-[#1e2a5b]">{{ __('Ubah profil') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </section>

        <section>
            <h2 class="text-xl font-extrabold text-[#14213d]">{{ __('Dalam seminggu terakhir') }}</h2>
            <div class="grid lg:grid-cols-2 gap-5">
                <a href="{{ route('dashboard.analytics.index') }}" class="group min-h-[250px] rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-extrabold text-[#14213d]">{{ __('Pengunjung TreeLink') }}</h3>
                            <p class="mt-1 text-xs font-medium text-slate-400">{{ __('7 hari terakhir') }}</p>
                        </div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#edf8dd] text-[#4d7c0f] transition group-hover:translate-x-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                    <div class="mt-8 rounded-2xl bg-[#f8fafc] p-5">
                        <p class="text-4xl font-black text-[#14213d]">{{ number_format($totalViews) }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-500">{{ __('total pengunjung profil') }}</p>
                        <div class="mt-5 flex h-10 items-end gap-1.5">
                            @foreach ([18, 30, 24, 42, 35, 56, 68, 48, 76, 62, 84, 72] as $bar)<span class="flex-1 rounded-t-md bg-[#c8ff4d] transition group-hover:bg-[#a3e635]" style="height: {{ $bar }}%"></span>@endforeach
                        </div>
                    </div>
                    <p class="mt-4 text-xs font-bold text-[#4f46e5]">{{ __('Buka analitik pengunjung →') }}</p>
                </a>

                <div class="group min-h-[250px] rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-extrabold text-[#14213d]">AI Profile Builder</h3>
                            <span class="mt-1 inline-flex rounded-full bg-[#f1edff] px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[0.12em] text-[#7c3aed]">Fitur coming soon</span>
                        </div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f1edff] text-[#7c3aed] transition group-hover:rotate-12"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l1.4 4.6L18 9l-4.6 1.4L12 15l-1.4-4.6L6 9l4.6-1.4L12 3zM19 14l.7 2.3L22 17l-2.3.7L19 20l-.7-2.3L16 17l2.3-.7L19 14z"/></svg></span>
                    </div>
                    <div class="relative mt-8 grid min-h-[190px] overflow-hidden rounded-2xl bg-gradient-to-br from-[#f8f7ff] via-[#eef2ff] to-[#ede9fe] p-5 sm:grid-cols-[minmax(0,1fr)_190px] sm:items-center sm:gap-4">
                        <div class="relative z-10">
                            <p class="text-2xl font-black tracking-tight text-[#1e2a5b] sm:text-3xl">Your Profile. Built Smarter.</p>
                            <p class="mt-3 max-w-lg text-sm font-medium leading-6 text-slate-500">Fitur masa depan yang membantu pengguna membuat desain halaman bio otomatis sesuai kebutuhan dan gaya mereka.</p>
                        </div>
                        <div class="relative mx-auto mt-5 h-28 w-44 sm:mt-0" aria-hidden="true">
                            <div class="absolute left-2 top-3 h-24 w-36 -rotate-6 rounded-2xl border border-white/80 bg-white/70 p-2 shadow-lg">
                                <div class="h-2 w-16 rounded-full bg-violet-200"></div>
                                <div class="mt-3 h-2 w-24 rounded-full bg-slate-200"></div>
                                <div class="mt-2 h-2 w-20 rounded-full bg-slate-200"></div>
                                <div class="mt-4 h-7 rounded-lg bg-lime-300/80"></div>
                            </div>
                            <div class="absolute right-0 top-0 h-28 w-36 rotate-6 rounded-2xl border border-white bg-[#1e2a5b] p-3 shadow-xl">
                                <div class="flex items-center gap-2">
                                    <span class="h-7 w-7 rounded-full bg-gradient-to-br from-lime-300 to-violet-400"></span>
                                    <span class="h-2 w-16 rounded-full bg-white/80"></span>
                                </div>
                                <div class="mt-3 h-3 rounded-full bg-white/20"></div>
                                <div class="mt-2 h-3 w-4/5 rounded-full bg-white/20"></div>
                                <div class="mt-4 h-7 rounded-lg bg-lime-300"></div>
                            </div>
                            <span class="absolute -bottom-1 left-0 flex h-8 w-8 items-center justify-center rounded-full bg-white text-violet-600 shadow-md">✦</span>
                        </div>
                    </div>
                    <p class="mt-4 text-xs font-bold text-[#7c3aed]">AI Profile Builder · Coming soon</p>
                </div>
            </div>
        </section>

    </div>
@endsection
