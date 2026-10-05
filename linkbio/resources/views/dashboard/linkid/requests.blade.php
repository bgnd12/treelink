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
                        </div>
                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-slate-600">{{ $request->message }}</p>

                    <div class="mt-4 text-xs text-slate-400">
                        {{ $request->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
