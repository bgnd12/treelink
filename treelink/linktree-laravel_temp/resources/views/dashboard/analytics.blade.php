@extends('layouts.dashboard')

@section('title', 'Analytics')
@section('page-title', 'Analytics')

@section('content')
    @php
        $stats = [
            ['label' => 'Clicks', 'value' => '8,421', 'trend' => '+12.4%'],
            ['label' => 'Visitors', 'value' => '3,204', 'trend' => '+8.1%'],
            ['label' => 'Sales', 'value' => 'Rp1.250.000', 'trend' => '+15.7%'],
            ['label' => 'LinkID', 'value' => '5', 'trend' => '+2'],
        ];
    @endphp

    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">Analytics</p>
            <h2 class="mt-2 text-3xl font-extrabold tracking-[-0.06em] text-slate-900">Your TreeLink growth at a glance.</h2>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-[28px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                        <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-700">{{ $stat['trend'] }}</span>
                    </div>
                    <p class="mt-5 text-3xl font-extrabold tracking-[-0.06em] text-slate-900">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
            <div class="rounded-[28px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-xl font-extrabold tracking-[-0.05em] text-slate-900">Link Performance</h3>
                    <span class="text-sm text-slate-500">7 days</span>
                </div>
                <div class="flex h-52 items-end gap-3">
                    @foreach ([42, 58, 48, 74, 64, 88, 96] as $bar)
                        <div class="flex-1 rounded-t-2xl bg-gradient-to-t from-violet-600 to-violet-300" style="height: {{ $bar }}%"></div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4 rounded-[28px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
                <h3 class="text-xl font-extrabold tracking-[-0.05em] text-slate-900">Summary</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-2xl bg-violet-50 px-4 py-3">
                        <span class="text-sm text-violet-700">Requests</span>
                        <strong class="text-violet-900">3</strong>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-emerald-50 px-4 py-3">
                        <span class="text-sm text-emerald-700">Collaborations</span>
                        <strong class="text-emerald-900">5</strong>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-sky-50 px-4 py-3">
                        <span class="text-sm text-sky-700">Shop clicks</span>
                        <strong class="text-sky-900">12</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
