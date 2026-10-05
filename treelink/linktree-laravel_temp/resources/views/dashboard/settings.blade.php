@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">Settings</p>
            <h2 class="mt-2 text-3xl font-extrabold tracking-[-0.06em] text-slate-900">Manage your account and preferences.</h2>
        </div>

        <div class="space-y-5 rounded-[30px] border border-[#ece6ff] bg-white p-6 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Full name</label>
                <input type="text" value="{{ auth()->user()->name }}" class="w-full rounded-2xl border border-[#ece6ff] bg-[#f8f7ff] px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-violet-300 focus:ring-4 focus:ring-violet-100" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" value="{{ auth()->user()->email }}" class="w-full rounded-2xl border border-[#ece6ff] bg-[#f8f7ff] px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-violet-300 focus:ring-4 focus:ring-violet-100" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
                <input type="text" value="{{ auth()->user()->username }}" class="w-full rounded-2xl border border-[#ece6ff] bg-[#f8f7ff] px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-violet-300 focus:ring-4 focus:ring-violet-100" />
            </div>
            <button class="rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-200">Save settings</button>
        </div>
    </div>
@endsection
