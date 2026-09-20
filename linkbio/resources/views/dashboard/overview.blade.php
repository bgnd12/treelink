@extends('layouts.dashboard')

@section('title', 'Overview')
@section('page-title', 'Overview')

@section('content')
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @php
            $cards = [
                ['label' => 'Total Kunjungan', 'value' => number_format($totalViews), 'icon' => '👁️', 'color' => 'from-brand-500 to-indigo-600'],
                ['label' => 'Total Klik Link', 'value' => number_format($totalClicks), 'icon' => '🖱️', 'color' => 'from-emerald-500 to-teal-600'],
                ['label' => 'Kunjungan 7 Hari', 'value' => number_format($viewsLast7Days), 'icon' => '📈', 'color' => 'from-orange-400 to-pink-500'],
                ['label' => 'Link Aktif', 'value' => $activeLinksCount.' / '.$links->count(), 'icon' => '🔗', 'color' => 'from-fuchsia-500 to-rose-500'],
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

    <div class="mt-8 grid lg:grid-cols-3 gap-6">
        {{-- Quick actions + top links --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-ink-900">Link Terpopuler</h2>
                <a href="{{ route('dashboard.links.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Kelola Semua &rarr;</a>
            </div>

            @forelse ($topLinks as $link)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-ink-50' : '' }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-9 h-9 rounded-lg bg-ink-50 flex items-center justify-center text-sm flex-shrink-0">🔗</span>
                        <div class="min-w-0">
                            <p class="font-semibold text-sm text-ink-900 truncate">{{ $link->title }}</p>
                            <p class="text-xs text-ink-400 truncate max-w-xs">{{ $link->url }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-ink-700 flex-shrink-0">{{ number_format($link->clicks_count) }} klik</span>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-ink-400 text-sm">Kamu belum memiliki link. Yuk tambahkan link pertamamu!</p>
                    <a href="{{ route('dashboard.links.index') }}" class="mt-4 inline-block px-5 py-2.5 rounded-full bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition">
                        + Tambah Link
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Profile card / share --}}
        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 rounded-2xl p-6 text-white shadow-soft flex flex-col">
            <div class="flex items-center gap-3">
                <img src="{{ $profile->avatar_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-white/50" alt="Avatar">
                <div class="min-w-0">
                    <p class="font-bold truncate">{{ $profile->display_name ?: auth()->user()->name }}</p>
                    <p class="text-brand-100 text-sm truncate">/{{ auth()->user()->username }}</p>
                </div>
            </div>
            <p class="mt-4 text-sm text-brand-100 line-clamp-3">{{ $profile->bio ?: 'Belum ada bio. Tambahkan di menu Profile.' }}</p>

            <div class="mt-auto pt-6 space-y-2">
                <a href="{{ auth()->user()->publicUrl() }}" target="_blank" class="block w-full py-2.5 rounded-full bg-white text-brand-700 text-center text-sm font-bold hover:bg-brand-50 transition">
                    Lihat Halaman Publik
                </a>
                <button type="button" onclick="copyProfileUrl()" class="block w-full py-2.5 rounded-full border border-white/40 text-center text-sm font-semibold hover:bg-white/10 transition">
                    Salin URL
                </button>
            </div>
        </div>
    </div>
@endsection
