@extends('layouts.dashboard')

@section('title', 'Tools')
@section('page-title', 'Tools')

@section('content')
@php
    $tools = [
        ['name' => 'Link Preview', 'desc' => 'Cek tampilan halaman TreeLink di mobile dan desktop.', 'icon' => '📱'],
        ['name' => 'Short Link', 'desc' => 'Buat tautan pendek untuk kampanye dan promo.', 'icon' => '🔗'],
        ['name' => 'Brand Kit', 'desc' => 'Siapkan visual profil, bio, dan tone brand.', 'icon' => '🎨'],
    ];
@endphp

<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-500">Tools</p>
        <h2 class="mt-2 text-3xl font-extrabold text-ink-900">Alat untuk tumbuh lebih cepat</h2>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        @foreach ($tools as $tool)
            <div class="rounded-3xl border border-ink-100 bg-white p-5 shadow-card">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-2xl">{{ $tool['icon'] }}</div>
                <h3 class="text-lg font-bold text-ink-900">{{ $tool['name'] }}</h3>
                <p class="mt-2 text-sm text-ink-500">{{ $tool['desc'] }}</p>
                <button class="mt-5 rounded-xl bg-ink-900 px-3 py-2 text-sm font-semibold text-white">Buka</button>
            </div>
        @endforeach
    </div>
</div>
@endsection
