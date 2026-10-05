@extends('layouts.dashboard')
@section('title', 'Messages')
@section('page-title', 'Messages')

@section('content')
<div class="bg-white rounded-3xl border border-ink-100 shadow-card overflow-hidden">
    <div class="grid lg:grid-cols-[340px_minmax(0,1fr)] min-h-[720px]">
        <aside class="border-r border-ink-100 bg-ink-50/40">
            <div class="p-5 border-b border-ink-100">
                <h2 class="text-xl font-extrabold text-ink-900">Messages</h2>
            </div>

            <div class="p-3 space-y-3">
                <div class="rounded-2xl border border-dashed border-ink-200 bg-white/60 p-6 text-center">
                    <p class="text-sm font-semibold text-ink-600">Belum ada percakapan</p>
                    <p class="mt-1 text-xs text-ink-400">Chat baru akan muncul di sini saat Anda mulai kolaborasi.</p>
                </div>
            </div>
        </aside>

        <section class="flex flex-col bg-white">
            <div class="flex-1 flex items-center justify-center p-8 bg-gradient-to-b from-ink-50/40 to-white">
                <div class="text-center max-w-md">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-50 text-3xl">💬</div>
                    <h3 class="mt-5 text-xl font-extrabold text-ink-900">Belum ada pesan</h3>
                    <p class="mt-2 text-sm text-ink-500">Saat Anda dan teman mulai kolaborasi, chat akan muncul di sini untuk komunikasi yang lebih cepat.</p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
