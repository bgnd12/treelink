@extends('layouts.dashboard')
@section('title', 'Discover LinkID')
@section('page-title', 'LinkID')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-6">
    <div class="flex flex-col gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">LinkID</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Temukan partner kolaborasi</h2>
            <p class="mt-1 text-sm text-slate-500">Jelajahi pengguna TreeLink yang membuka kesempatan kolaborasi.</p>
        </div>
        <form method="GET" action="{{ route('dashboard.linkid.discover') }}" class="flex w-full gap-2 xl:max-w-md">
            @if($type)<input type="hidden" name="type" value="{{ $type }}">@endif
            <label class="relative min-w-0 flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">⌕</span>
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama atau username..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-9 pr-3 text-sm outline-none transition focus:border-violet-400 focus:bg-white focus:ring-4 focus:ring-violet-100">
            </label>
            <button class="rounded-xl bg-violet-600 px-4 text-sm font-bold text-white transition hover:bg-violet-700">Cari</button>
        </form>
    </div>

    <nav class="flex flex-wrap gap-2" aria-label="Filter jenis kolaborasi">
        <a href="{{ route('dashboard.linkid.discover', ['q' => $search ?: null]) }}" class="rounded-full px-4 py-2 text-xs font-bold transition {{ $type === '' ? 'bg-violet-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">Semua</a>
        @foreach($types as $option)
            <a href="{{ route('dashboard.linkid.discover', ['q' => $search ?: null, 'type' => $option]) }}" class="rounded-full px-4 py-2 text-xs font-bold transition {{ $type === $option ? 'bg-violet-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">{{ $option }}</a>
        @endforeach
    </nav>

    @if($profiles->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-xl text-violet-600">↗</div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada profil LinkID</h3>
            <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Saat pengguna mengaktifkan LinkID, profil mereka akan muncul di sini. Kamu juga bisa mengaktifkannya dari editor My TreeLink.</p>
            @if(!$search && !$type)
                <a href="{{ route('dashboard.index') }}" class="mt-5 inline-flex rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-violet-700">Buka My TreeLink</a>
            @endif
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
            @foreach($profiles as $directoryProfile)
                @php
                    $directoryUser = $directoryProfile->user;
                    $directoryTypes = collect($directoryProfile->linkid_types ?? [])->filter(fn ($value) => is_string($value) && trim($value) !== '');
                @endphp
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-violet-200 hover:shadow-md">
                    <div class="flex items-start gap-3">
                        <img src="{{ $directoryProfile->avatar_url }}" alt="{{ $directoryProfile->display_name ?: $directoryUser->name }}" class="h-14 w-14 shrink-0 rounded-full border border-slate-100 object-cover">
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-base font-extrabold text-slate-900">{{ $directoryProfile->display_name ?: $directoryUser->name }}</h3>
                            <a href="{{ $directoryUser->publicUrl() }}" target="_blank" rel="noopener" class="text-xs font-medium text-slate-500 hover:text-violet-600">{{ '@'.$directoryUser->username }}</a>
                            @if($directoryProfile->linkid_description)
                                <p class="mt-2 line-clamp-2 text-sm leading-5 text-slate-600">{{ $directoryProfile->linkid_description }}</p>
                            @elseif($directoryProfile->bio)
                                <p class="mt-2 line-clamp-2 text-sm leading-5 text-slate-600">{{ $directoryProfile->bio }}</p>
                            @endif
                        </div>
                    </div>

                    @if($directoryTypes->isNotEmpty())
                        <div class="mt-4 flex min-h-7 flex-wrap gap-1.5">
                            @foreach($directoryTypes as $directoryType)
                                <span class="rounded-full bg-violet-50 px-2.5 py-1 text-[10px] font-bold text-violet-700">{{ $directoryType }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-5 flex gap-2 border-t border-slate-100 pt-4">
                        <a href="{{ $directoryUser->publicUrl() }}" target="_blank" rel="noopener" class="flex-1 rounded-xl border border-slate-200 py-2.5 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-50">Lihat Profil</a>
                        <a href="{{ $directoryUser->publicUrl() }}#linkid" target="_blank" rel="noopener" class="flex-1 rounded-xl bg-violet-600 py-2.5 text-center text-xs font-bold text-white transition hover:bg-violet-700">Ajak Kolaborasi</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div>{{ $profiles->links() }}</div>
    @endif
</div>
@endsection
