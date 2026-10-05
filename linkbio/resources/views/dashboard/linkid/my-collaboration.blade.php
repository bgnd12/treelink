@extends('layouts.dashboard')
@section('title', 'My Collaboration')
@section('page-title', 'My Collaboration')

@section('content')
<div class="space-y-5">
    @foreach([
        ['name' => 'Ghea', 'type' => 'Content Collaboration', 'status' => 'In Discussion', 'active' => 'Current', 'progress' => ['Request', 'Accepted', 'In Discussion'], 'current' => 2],
        ['name' => 'Natura Studio', 'type' => 'Product Collaboration', 'status' => 'In Progress', 'active' => 'Progress', 'progress' => ['Request', 'Accepted', 'In Discussion', 'In Progress'], 'current' => 3],
        ['name' => 'Potret Nusantara', 'type' => 'Event Collaboration', 'status' => 'Completed', 'active' => 'Completed', 'progress' => ['Request', 'Accepted', 'In Discussion', 'In Progress', 'Completed'], 'current' => 4],
    ] as $collab)
        <div class="bg-white rounded-3xl border border-ink-100 shadow-card p-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-600">{{ $collab['name'] }}</p>
                    <h3 class="mt-1 text-xl font-extrabold text-ink-900">{{ $collab['type'] }}</h3>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">{{ $collab['status'] }}</span>
            </div>

            <div class="mt-5">
                <div class="flex items-center justify-between mb-3 text-sm font-semibold text-ink-500">
                    <span>Progress</span>
                    <span class="text-ink-700">{{ $collab['active'] }}</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($collab['progress'] as $index => $step)
                        <div class="rounded-2xl border p-3 text-center {{ $index <= $collab['current'] ? 'border-brand-200 bg-brand-50 text-brand-700' : 'border-ink-100 bg-ink-50 text-ink-400' }}">
                            <div class="text-xs font-bold">{{ $step }}</div>
                            <div class="mt-2 text-lg">{{ $index <= $collab['current'] ? '✓' : '•' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
