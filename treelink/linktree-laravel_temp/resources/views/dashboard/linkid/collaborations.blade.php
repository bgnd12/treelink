@extends('layouts.dashboard')

@section('title', 'My Collaboration')
@section('page-title', 'My Collaboration')

@section('content')
@php
    $collabs = [
        ['name' => 'Ghea', 'type' => 'Content Collaboration', 'status' => 'In Discussion', 'progress' => ['Request', 'Accepted', 'In Discussion', 'Current']],
        ['name' => 'Nusa Labs', 'type' => 'Product Collaboration', 'status' => 'In Progress', 'progress' => ['Request', 'Accepted', 'In Discussion', 'In Progress']],
    ];
@endphp

<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-500">LinkID</p>
        <h2 class="mt-2 text-3xl font-extrabold text-ink-900">My Collaboration</h2>
    </div>

    <div class="space-y-4">
        @foreach ($collabs as $collab)
            <div class="rounded-3xl border border-ink-100 bg-white p-5 shadow-card">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-ink-900">{{ $collab['name'] }}</h3>
                        <p class="text-sm text-ink-500">{{ $collab['type'] }}</p>
                    </div>
                    <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-bold text-sky-700">{{ $collab['status'] }}</span>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($collab['progress'] as $step)
                        <span class="rounded-full {{ $step === 'Current' || $step === 'In Discussion' ? 'bg-violet-600 text-white' : 'bg-ink-100 text-ink-600' }} px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.18em]">{{ $step }}</span>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
