@extends('layouts.dashboard')

@section('title', 'Discover')
@section('page-title', 'LinkID')

@section('content')
    @php
        $profiles = [
            ['name' => 'Ghea', 'role' => 'Fashion Creator', 'handle' => '@ghea', 'followers' => '12.4K followers', 'location' => 'Bandung', 'tags' => ['Content', 'Product', 'Brand'], 'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80'],
            ['name' => 'Rama Studio', 'role' => 'Brand Agency', 'handle' => '@ramastudio', 'followers' => '8.9K followers', 'location' => 'Jakarta', 'tags' => ['Brand', 'Event', 'Project'], 'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80'],
            ['name' => 'Kota Kreatif', 'role' => 'Community', 'handle' => '@kotakreatif', 'followers' => '9.6K followers', 'location' => 'Surabaya', 'tags' => ['Event', 'Community', 'Project'], 'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80'],
            ['name' => 'Nusa Labs', 'role' => 'Product Brand', 'handle' => '@nusalabs', 'followers' => '14.1K followers', 'location' => 'Yogyakarta', 'tags' => ['Product', 'Brand', 'Event'], 'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80'],
            ['name' => 'Sora Studio', 'role' => 'Photographer', 'handle' => '@sorastudio', 'followers' => '6.8K followers', 'location' => 'Bali', 'tags' => ['Content', 'Brand'], 'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80'],
            ['name' => 'Aster Club', 'role' => 'Community Hub', 'handle' => '@asterclub', 'followers' => '7.2K followers', 'location' => 'Bandung', 'tags' => ['Project', 'Event', 'Community'], 'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80'],
        ];
    @endphp

    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">LinkID</p>
            <h2 class="mt-2 text-3xl font-extrabold tracking-[-0.06em] text-slate-900">Temukan orang dan peluang untuk berkolaborasi.</h2>
            <p class="mt-2 text-sm text-slate-500">Jelajahi creator, brand, dan komunitas yang relevan dengan tujuan bisnis dan kreativitasmu.</p>
        </div>

        <div class="rounded-[28px] border border-[#ece6ff] bg-white p-4 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 rounded-2xl border border-[#ece6ff] bg-[#f8f7ff] px-4 py-3 text-slate-500">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="6"/>
                            <path stroke-linecap="round" d="m16 16 4 4"/>
                        </svg>
                        <input type="text" placeholder="Cari creator, brand, komunitas..." class="w-full border-0 bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none">
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach (['Semua', 'Creator', 'Brand', 'Komunitas', 'Project'] as $filter)
                        <button class="rounded-full px-3 py-2 text-xs font-semibold {{ $filter === 'Semua' ? 'bg-violet-600 text-white shadow-md shadow-violet-200' : 'bg-[#f3f0ff] text-slate-600' }}">{{ $filter }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-5 xl:grid-cols-3">
            @foreach ($profiles as $profile)
                <article class="rounded-[28px] border border-[#ece6ff] bg-white p-4 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $profile['image'] }}" alt="{{ $profile['name'] }}" class="h-14 w-14 rounded-full object-cover ring-2 ring-violet-100" />
                            <div>
                                <h3 class="text-lg font-extrabold tracking-[-0.04em] text-slate-900">{{ $profile['name'] }}</h3>
                                <p class="text-sm text-slate-500">{{ $profile['handle'] }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-violet-50 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-violet-700">{{ $profile['role'] }}</span>
                    </div>

                    <div class="mt-4 rounded-2xl bg-[#f8f7ff] p-3 text-sm text-slate-600">
                        <div class="flex items-center justify-between">
                            <span>{{ $profile['followers'] }}</span>
                            <span>{{ $profile['location'] }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($profile['tags'] as $tag)
                            <span class="rounded-full bg-violet-50 px-2.5 py-1 text-[10px] font-semibold text-violet-700">{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="mt-5 flex gap-2">
                        <button class="flex-1 rounded-2xl border border-[#ece6ff] bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-violet-200 hover:text-violet-700">Lihat Profil</button>
                        <button class="flex-1 rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-3 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-200">Ajak Kolaborasi</button>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endsection
