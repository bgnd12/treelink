@extends('layouts.dashboard')
@section('title', 'My Collaboration')
@section('page-title', 'My Collaboration')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">LinkID</p>
        <h2 class="mt-1 text-2xl font-extrabold text-slate-900">My Collaboration</h2>
        <p class="mt-1 text-sm text-slate-500">Kolaborasi yang sudah diterima dan percakapannya.</p>
    </div>

    @forelse($collaborations as $collaboration)
        @php
            $partner = $collaboration->requester_user_id === auth()->id()
                ? $collaboration->recipient
                : $collaboration->requester;
        @endphp
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">{{ $collaboration->type }}</p>
                    <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $partner?->name ?? 'Akun tidak tersedia' }}</h3>
                    @if($partner)<p class="text-sm text-slate-500">{{ '@'.$partner->username }}</p>@endif
                </div>
                <span class="inline-flex self-start rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Diterima</span>
            </div>

            @if($collaboration->message)
                <p class="mt-4 whitespace-pre-wrap text-sm leading-6 text-slate-600">{{ $collaboration->message }}</p>
            @endif

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                <time class="text-xs text-slate-400">Diterima {{ $collaboration->updated_at->diffForHumans() }}</time>
                @if($collaboration->conversation)
                    <a href="{{ route('dashboard.linkid.messages.show', $collaboration->conversation) }}" class="rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-xs font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">Buka chat</a>
                @endif
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1f3e9] text-xl text-[#1e2a5b]">↗</div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada kolaborasi aktif</h3>
            <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Request yang diterima akan muncul di sini, lengkap dengan akses ke chat.</p>
            <a href="{{ route('dashboard.linkid.discover') }}" class="mt-5 inline-flex rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] hover:bg-[#ddff91]">Cari kolaborator</a>
        </div>
    @endforelse
</div>
@endsection
