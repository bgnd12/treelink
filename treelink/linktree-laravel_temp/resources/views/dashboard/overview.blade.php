@extends('layouts.dashboard')

@section('title', 'Overview')
@section('page-title', 'Good morning, Athaya 👋')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-500">Overview</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-[-0.06em] text-slate-900">Here's what's happening with your TreeLink.</h2>
            </div>
            <a href="{{ route('dashboard.editor') }}" class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:shadow-violet-300">Edit Profile</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @php
                $cards = [
                    ['label' => 'Total Clicks', 'value' => '8,421', 'delta' => '+12.4%', 'tone' => 'from-violet-500 to-purple-600'],
                    ['label' => 'Unique Visitors', 'value' => '3,204', 'delta' => '+8.1%', 'tone' => 'from-violet-100 to-violet-200'],
                    ['label' => 'Sales', 'value' => 'Rp1.250.000', 'delta' => '+15.7%', 'tone' => 'from-emerald-100 to-emerald-200'],
                    ['label' => 'Collaboration', 'value' => '5', 'delta' => '+2 this month', 'tone' => 'from-pink-100 to-rose-200'],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="rounded-3xl border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.06)]">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">{{ $card['label'] }}</span>
                        <span class="rounded-full bg-violet-50 px-2 py-1 text-[10px] font-bold text-violet-700">{{ $card['delta'] }}</span>
                    </div>
                    <div class="mt-5 flex items-end justify-between">
                        <div class="text-3xl font-extrabold tracking-[-0.06em] text-slate-900">{{ $card['value'] }}</div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br {{ $card['tone'] }} text-slate-700">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 18V7m6 11V4m6 14v-8m6 8V9"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
            <div class="rounded-[28px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.06)]">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">LinkID Activity</p>
                        <h3 class="mt-2 text-2xl font-extrabold tracking-[-0.05em] text-slate-900">Collaboration insights</h3>
                    </div>
                    <span class="rounded-full bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700">This week</span>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-violet-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Requests</p>
                        <p class="mt-3 text-3xl font-extrabold text-slate-900">3</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Active</p>
                        <p class="mt-3 text-3xl font-extrabold text-slate-900">5</p>
                    </div>
                    <div class="rounded-2xl bg-sky-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-600">Product Clicks</p>
                        <p class="mt-3 text-3xl font-extrabold text-slate-900">12</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] border border-[#ece6ff] bg-gradient-to-br from-violet-600 to-indigo-600 p-5 text-white shadow-[0_18px_35px_rgba(109,40,217,0.28)]">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-100">LinkID</p>
                <h3 class="mt-3 text-2xl font-extrabold tracking-[-0.05em]">Temukan lebih banyak peluang kolaborasi di LinkID</h3>
                <p class="mt-3 text-sm text-violet-100">Tingkatkan brand value dan jangkau creator dan brand yang relevan.</p>
                <a href="{{ route('dashboard.linkid.discover') }}" class="mt-6 inline-flex rounded-2xl bg-white px-4 py-2.5 text-sm font-semibold text-violet-700 transition hover:bg-violet-50">Jelajahi LinkID</a>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-[28px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.06)]">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold tracking-[-0.05em] text-slate-900">Your Top Links</h3>
                    <a href="{{ route('dashboard.links.index') }}" class="text-sm font-semibold text-violet-700">Manage all</a>
                </div>

                <div class="mt-5 space-y-3">
                    @php $topLinks = [['name' => 'Instagram', 'value' => '2.4K clicks', 'tone' => 'bg-pink-100 text-pink-700'], ['name' => 'TikTok', 'value' => '1.9K clicks', 'tone' => 'bg-[#f2f2ff] text-violet-700'], ['name' => 'YouTube', 'value' => '1.2K clicks', 'tone' => 'bg-red-100 text-red-700'], ['name' => 'LinkID', 'value' => '840 clicks', 'tone' => 'bg-emerald-100 text-emerald-700'], ['name' => 'Shop', 'value' => '620 clicks', 'tone' => 'bg-sky-100 text-sky-700']]; @endphp
                    @foreach ($topLinks as $link)
                        <div class="flex items-center justify-between rounded-2xl border border-[#f0ebff] bg-[#faf8ff] px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $link['tone'] }} text-sm font-bold">{{ strtoupper(substr($link['name'], 0, 1)) }}</span>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $link['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $link['value'] }}</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-[#ece6ff]">Active</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[28px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.06)]">
                <h3 class="text-xl font-extrabold tracking-[-0.05em] text-slate-900">Quick wins</h3>
                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-[#f8f5ff] p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Product</p>
                        <p class="mt-2 text-lg font-bold text-slate-900">Add a featured item</p>
                    </div>
                    <div class="rounded-2xl bg-[#f3fff8] p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Collab</p>
                        <p class="mt-2 text-lg font-bold text-slate-900">Respond to 3 requests</p>
                    </div>
                    <div class="rounded-2xl bg-[#fff7f8] p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-500">Profile</p>
                        <p class="mt-2 text-lg font-bold text-slate-900">Update your bio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection