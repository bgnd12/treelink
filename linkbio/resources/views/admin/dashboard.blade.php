@extends('layouts.admin')

@section('title', 'Overview')
@section('page-title', 'Platform Overview')

@section('content')
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @php
            $cards = [
                ['label' => 'Total User', 'value' => number_format($stats['total_users']), 'icon' => '👥', 'color' => 'from-brand-500 to-indigo-600'],
                ['label' => 'User Aktif', 'value' => number_format($stats['active_users']), 'icon' => '✅', 'color' => 'from-emerald-500 to-teal-600'],
                ['label' => 'User Nonaktif', 'value' => number_format($stats['inactive_users']), 'icon' => '⛔', 'color' => 'from-rose-500 to-red-600'],
                ['label' => 'User Baru (7 Hari)', 'value' => number_format($stats['new_users_7d']), 'icon' => '🆕', 'color' => 'from-orange-400 to-pink-500'],
                ['label' => 'Total Link Dibuat', 'value' => number_format($stats['total_links']), 'icon' => '🔗', 'color' => 'from-fuchsia-500 to-rose-500'],
                ['label' => 'Total Kunjungan Profil', 'value' => number_format($stats['total_views']), 'icon' => '👁️', 'color' => 'from-sky-500 to-blue-600'],
                ['label' => 'Total Klik Link', 'value' => number_format($stats['total_clicks']), 'icon' => '🖱️', 'color' => 'from-violet-500 to-purple-600'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $card['color'] }} text-white flex items-center justify-center text-lg">{{ $card['icon'] }}</div>
                <p class="mt-4 text-2xl font-extrabold text-ink-900">{{ $card['value'] }}</p>
                <p class="text-sm text-ink-500">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid lg:grid-cols-2 gap-6">
        {{-- Latest users --}}
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-ink-900">User Terbaru</h2>
                <a href="{{ route('admin.users') }}" class="text-sm font-semibold text-brand-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
            @foreach ($latestUsers as $u)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-ink-50' : '' }}">
                    <div class="min-w-0">
                        <p class="font-semibold text-sm text-ink-900 truncate">{{ $u->name }}</p>
                        <p class="text-xs text-ink-400 truncate">/{{ $u->username }} &middot; {{ $u->links_count }} link</p>
                    </div>
                    <a href="{{ route('admin.users.show', $u) }}" class="text-xs font-semibold text-brand-600 hover:underline flex-shrink-0">Detail</a>
                </div>
            @endforeach
        </div>

        {{-- Most viewed --}}
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <h2 class="font-bold text-ink-900 mb-5">Profil Paling Banyak Dikunjungi</h2>
            @forelse ($mostViewedUsers as $u)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-ink-50' : '' }}">
                    <div class="min-w-0">
                        <p class="font-semibold text-sm text-ink-900 truncate">{{ $u->name }}</p>
                        <p class="text-xs text-ink-400 truncate">/{{ $u->username }}</p>
                    </div>
                    <span class="text-sm font-bold text-ink-700 flex-shrink-0">{{ number_format($u->profile_views_count) }} views</span>
                </div>
            @empty
                <p class="text-sm text-ink-400 text-center py-6">Belum ada data kunjungan.</p>
            @endforelse
        </div>
    </div>
@endsection
