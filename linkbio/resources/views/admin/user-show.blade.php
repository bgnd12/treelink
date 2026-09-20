@extends('layouts.admin')

@section('title', $targetUser->name)
@section('page-title', 'Detail User')

@section('content')
    <a href="{{ route('admin.users') }}" class="text-sm font-semibold text-brand-600 hover:underline">&larr; Kembali ke Semua User</a>

    <div class="mt-5 grid lg:grid-cols-3 gap-6">
        {{-- Profile card --}}
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card text-center">
            <img src="{{ $targetUser->profile?->avatar_url }}" class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-ink-100" alt="Avatar">
            <p class="mt-3 font-bold text-ink-900">{{ $targetUser->name }}</p>
            <p class="text-sm text-ink-400">/{{ $targetUser->username }}</p>
            <p class="text-sm text-ink-500 mt-1">{{ $targetUser->email }}</p>

            <div class="mt-4">
                @if ($targetUser->is_admin)
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold">Admin</span>
                @elseif ($targetUser->is_active)
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">Aktif</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold">Nonaktif</span>
                @endif
            </div>

            <a href="{{ $targetUser->publicUrl() }}" target="_blank" class="mt-5 block w-full py-2.5 rounded-xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition">
                Lihat Halaman Publik
            </a>

            @unless ($targetUser->is_admin)
                <div class="mt-3 flex gap-2">
                    <form method="POST" action="{{ route('admin.users.toggle', $targetUser) }}" class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full py-2.5 rounded-xl border text-sm font-semibold transition {{ $targetUser->is_active ? 'border-rose-200 text-rose-600 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                            {{ $targetUser->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $targetUser) }}" class="flex-1" onsubmit="return confirm('Hapus user ini beserta seluruh datanya?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2.5 rounded-xl border border-ink-200 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">Hapus</button>
                    </form>
                </div>
            @endunless
        </div>

        {{-- Stats + links --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-ink-100 p-5 shadow-card text-center">
                    <p class="text-2xl font-extrabold text-ink-900">{{ number_format($totalViews) }}</p>
                    <p class="text-xs text-ink-500">Kunjungan</p>
                </div>
                <div class="bg-white rounded-2xl border border-ink-100 p-5 shadow-card text-center">
                    <p class="text-2xl font-extrabold text-ink-900">{{ number_format($totalClicks) }}</p>
                    <p class="text-xs text-ink-500">Klik</p>
                </div>
                <div class="bg-white rounded-2xl border border-ink-100 p-5 shadow-card text-center">
                    <p class="text-2xl font-extrabold text-ink-900">{{ $targetUser->links->count() }}</p>
                    <p class="text-xs text-ink-500">Total Link</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <h2 class="font-bold text-ink-900 mb-4">Link yang Dibuat</h2>
                @forelse ($targetUser->links as $link)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-ink-50' : '' }}">
                        <div class="min-w-0">
                            <p class="font-semibold text-sm text-ink-900 truncate">{{ $link->title }}</p>
                            <p class="text-xs text-ink-400 truncate max-w-sm">{{ $link->url }}</p>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="text-xs font-semibold text-ink-600">{{ $link->clicks->count() }} klik</span>
                            @if ($link->is_active)
                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">Aktif</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-ink-100 text-ink-500 text-xs font-semibold">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-ink-400 text-center py-6">User ini belum menambahkan link.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
