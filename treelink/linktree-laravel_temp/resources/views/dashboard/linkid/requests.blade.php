@extends('layouts.dashboard')

@section('title', 'Requests')
@section('page-title', 'Requests')

@section('content')
@php
    $requests = [
        ['name' => 'Ghea', 'role' => 'Fashion Creator', 'type' => 'Content Collaboration', 'message' => 'Saya ingin mengajakmu untuk kampanye content baru bulan depan.', 'status' => 'Pending'],
        ['name' => 'Kreasi House', 'role' => 'Brand', 'type' => 'Product', 'message' => 'Kami butuh creator untuk review produk editing di social.', 'status' => 'Accepted'],
        ['name' => 'Loka Event', 'role' => 'Event Organizer', 'type' => 'Event', 'message' => 'Mari kolaborasikan event musik komunitas kami.', 'status' => 'Rejected'],
    ];
@endphp

<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-500">LinkID</p>
        <h2 class="mt-2 text-3xl font-extrabold text-ink-900">Requests</h2>
    </div>

    <div class="space-y-4">
        @foreach ($requests as $request)
            <div class="rounded-3xl border border-ink-100 bg-white p-5 shadow-card">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-ink-900">{{ $request['name'] }}</h3>
                        <p class="text-sm text-ink-500">{{ $request['role'] }}</p>
                    </div>
                    <span class="inline-flex rounded-full {{ $request['status'] === 'Accepted' ? 'bg-emerald-50 text-emerald-700' : ($request['status'] === 'Rejected' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }} px-2.5 py-1 text-xs font-bold">{{ $request['status'] }}</span>
                </div>

                <p class="mt-4 text-sm text-ink-600">{{ $request['type'] }}</p>
                <p class="mt-2 text-sm text-ink-500">“{{ $request['message'] }}”</p>

                @if ($request['status'] === 'Pending')
                    <div class="mt-4 flex gap-2">
                        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Accept</button>
                        <button class="rounded-xl border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-700">Reject</button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
