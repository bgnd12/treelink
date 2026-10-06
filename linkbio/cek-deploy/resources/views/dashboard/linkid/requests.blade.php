@extends('layouts.dashboard')
@section('title', 'Requests')
@section('page-title', 'Requests')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-500">LinkID</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Requests</h1>
        </div>
    </div>

    @if($requests->isEmpty())
        <div class="bg-white rounded-3xl border border-ink-100 shadow-card p-8">
            <div class="max-w-xl mx-auto text-center py-12">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-50 text-3xl">📩</div>
                <h3 class="mt-5 text-xl font-extrabold text-ink-900">Belum ada request</h3>
                <p class="mt-2 text-sm text-ink-500">Saat ada orang mengajukan kolaborasi, request akan muncul di sini dengan status yang jelas.</p>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($requests as $request)
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-500">{{ ucfirst($request->type) }}</p>
                            <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $request->requester_name }}</h3>
                            <p class="text-sm text-slate-500">{{ $request->requester_email }}</p>
                            @if($request->requester)
                                <p class="mt-1 text-xs font-medium text-emerald-700">Akun TreeLink · {{ '@'.$request->requester->username }}</p>
                            @else
                                <p class="mt-1 text-xs font-medium text-amber-700">Pengirim belum terhubung ke akun TreeLink</p>
                            @endif
                        </div>
                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-slate-600">{{ $request->message }}</p>

                    <div class="mt-4 text-xs text-slate-400">
                        {{ $request->created_at->diffForHumans() }}
                    </div>

                    @if($request->status === 'pending')
                        <div class="mt-4 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                            @if($request->requester_user_id)
                                <form method="POST" action="{{ route('dashboard.linkid.requests.accept', $request) }}">
                                    @csrf
                                    <button type="submit" class="rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-xs font-bold text-[#1e2a5b] hover:bg-[#ddff91]">Terima & mulai chat</button>
                                </form>
                            @else
                                <button type="button" disabled title="Chat tersedia untuk request dari akun TreeLink" class="cursor-not-allowed rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-bold text-slate-400">Pengirim perlu login untuk chat</button>
                            @endif
                            <form method="POST" action="{{ route('dashboard.linkid.requests.decline', $request) }}">
                                @csrf
                                <button type="submit" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Tolak</button>
                            </form>
                        </div>
                    @elseif($request->status === 'accepted' && $request->conversation)
                        <a href="{{ route('dashboard.linkid.messages.show', $request->conversation) }}" class="mt-4 inline-flex rounded-xl bg-[#1e2a5b] px-4 py-2.5 text-xs font-bold text-white hover:bg-[#303e76]">Buka chat</a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
