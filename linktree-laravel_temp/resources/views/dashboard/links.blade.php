@extends('layouts.dashboard')

@section('title', 'Links')
@section('page-title', 'Links')

@section('content')
<div class="grid lg:grid-cols-3 gap-8">

    {{-- LEFT: form + list --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Add new link --}}
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <h2 class="font-bold text-ink-900 mb-4">Tambah Link Baru</h2>
            <form method="POST" action="{{ route('dashboard.links.store') }}" class="grid sm:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Judul</label>
                    <input type="text" name="title" placeholder="Contoh: Instagram Saya" required value="{{ old('title') }}"
                           class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">URL</label>
                    <input type="text" name="url" placeholder="https://instagram.com/kamu" required value="{{ old('url') }}"
                           class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Icon</label>
                    <select name="icon" class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                        @foreach ($availableIcons as $icon)
                            <option value="{{ $icon }}">{{ ucfirst(str_replace('-', ' ', $icon)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sm:col-span-2 py-3 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
                    + Tambahkan Link
                </button>
            </form>
        </div>

        {{-- Links list --}}
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-ink-900">Link Kamu ({{ $links->count() }})</h2>
            </div>

            <ul id="links-list" class="space-y-3">
                @forelse ($links as $link)
                    <li data-id="{{ $link->id }}" class="flex items-center gap-3 p-4 rounded-xl border border-ink-100 bg-ink-50/40 {{ !$link->is_active ? 'opacity-50' : '' }}">
                        <span class="w-9 h-9 rounded-lg bg-white border border-ink-100 flex items-center justify-center text-sm flex-shrink-0">{{ $link->icon }}</span>

                        <div class="flex-1 min-w-0" x-data="{ editing: false }">
                            <div x-show="!editing">
                                <p class="font-semibold text-sm text-ink-900 truncate">{{ $link->title }}</p>
                                <p class="text-xs text-ink-400 truncate max-w-xs">{{ $link->url }}</p>
                            </div>
                            <form x-show="editing" x-cloak method="POST" action="{{ route('dashboard.links.update', $link) }}" class="flex flex-col sm:flex-row gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="title" value="{{ $link->title }}" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                <input type="text" name="url" value="{{ $link->url }}" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                <select name="icon" class="px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                    @foreach ($availableIcons as $icon)
                                        <option value="{{ $icon }}" @selected($link->icon === $icon)>{{ ucfirst($icon) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-3 py-2 rounded-lg bg-brand-600 text-white text-sm font-semibold">Simpan</button>
                            </form>

                            <div class="flex items-center gap-3 mt-2 text-xs">
                                <button type="button" @click="editing = !editing" class="font-semibold text-brand-600 hover:underline" x-text="editing ? 'Batal' : 'Edit'"></button>
                                <span class="text-ink-300">•</span>
                                <span class="text-ink-500">{{ $link->clicks_count }} klik</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form method="POST" action="{{ route('dashboard.links.toggle', $link) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-11 h-6 rounded-full flex items-center px-0.5 transition {{ $link->is_active ? 'bg-brand-600 justify-end' : 'bg-ink-200 justify-start' }}">
                                    <span class="w-5 h-5 rounded-full bg-white shadow"></span>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('dashboard.links.destroy', $link) }}" onsubmit="return confirm('Hapus link ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-lg text-rose-500 hover:bg-rose-50 transition flex items-center justify-center">🗑️</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="text-center py-10 text-ink-400 text-sm">Belum ada link. Tambahkan link pertamamu di atas!</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- RIGHT: live preview --}}
    <div>
        <div class="sticky top-24">
            <p class="text-sm font-semibold text-ink-500 mb-3 text-center">Live Preview</p>
            @include('components.phone-preview', ['profile' => $profile, 'links' => $links, 'user' => $user])
        </div>
    </div>
</div>
@endsection