@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')

@section('content')
    @php
        $conversations = [
            ['name' => 'Ghea', 'status' => 'Fashion Creator', 'last' => 'Jadi untuk briefnya...', 'time' => '10:42', 'unread' => 2, 'active' => true],
            ['name' => 'Rama Studio', 'status' => 'Brand Agency', 'last' => 'Kami tertarik untuk...', 'time' => '09:21', 'unread' => 0, 'active' => false],
            ['name' => 'Nusa Labs', 'status' => 'Product Brand', 'last' => 'Bisa kirimkan mockup...', 'time' => 'Kemarin', 'unread' => 0, 'active' => false],
        ];
    @endphp

    <div class="overflow-hidden rounded-[30px] border border-[#ece6ff] bg-white shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
        <div class="grid min-h-[680px] lg:grid-cols-[340px_minmax(0,1fr)]">
            <aside class="border-b border-[#f0ebff] bg-[#faf8ff] lg:border-b-0 lg:border-r">
                <div class="border-b border-[#f0ebff] p-4">
                    <h3 class="text-xl font-extrabold tracking-[-0.05em] text-slate-900">Messages</h3>
                </div>
                <div class="divide-y divide-[#f0ebff]">
                    @foreach ($conversations as $conversation)
                        <button class="flex w-full items-center gap-3 p-4 text-left {{ $conversation['active'] ? 'bg-white' : 'bg-transparent' }}">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80" alt="{{ $conversation['name'] }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-violet-100" />
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="truncate text-sm font-bold text-slate-900">{{ $conversation['name'] }}</p>
                                    @if ($conversation['unread'])
                                        <span class="inline-flex min-w-5 items-center justify-center rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $conversation['unread'] }}</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-[11px] font-medium text-violet-600">{{ $conversation['status'] }}</p>
                                <p class="mt-1 truncate text-[12px] text-slate-500">{{ $conversation['last'] }}</p>
                            </div>
                            <span class="text-[10px] text-slate-400">{{ $conversation['time'] }}</span>
                        </button>
                    @endforeach
                </div>
            </aside>

            <section class="flex flex-col">
                <header class="flex items-center justify-between border-b border-[#f0ebff] p-4">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80" alt="Ghea" class="h-11 w-11 rounded-full object-cover ring-2 ring-violet-100" />
                        <div>
                            <p class="text-sm font-bold text-slate-900">Ghea</p>
                            <p class="text-[11px] font-medium text-violet-600">Fashion Creator</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-semibold">
                        <span class="rounded-full bg-violet-50 px-2 py-1 text-violet-700">Collaboration</span>
                        <span class="rounded-full bg-sky-50 px-2 py-1 text-sky-700">In Discussion</span>
                    </div>
                </header>

                <div class="flex-1 space-y-4 bg-[#faf8ff] p-4">
                    <div class="max-w-[80%] rounded-2xl rounded-bl-md bg-white px-4 py-3 text-sm text-slate-700 shadow-sm ring-1 ring-[#f0ebff]">Halo, saya tertarik untuk kolaborasi content untuk launch brand baru.</div>
                    <div class="ml-auto max-w-[80%] rounded-2xl rounded-br-md bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-3 text-sm text-white shadow-lg shadow-violet-200">Tentu, saya siap. Bisa kirimkan brief dan timeline detailnya?</div>
                    <div class="max-w-[80%] rounded-2xl rounded-bl-md bg-white px-4 py-3 text-sm text-slate-700 shadow-sm ring-1 ring-[#f0ebff]">Nanti saya kirimkan moodboard dan briefnya besok pagi.</div>
                </div>

                <footer class="border-t border-[#f0ebff] p-4">
                    <div class="flex items-center gap-3 rounded-2xl border border-[#ece6ff] bg-white px-3 py-2.5">
                        <button class="text-lg text-slate-400">＋</button>
                        <input type="text" placeholder="Tulis pesan..." class="flex-1 border-0 bg-transparent text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none" />
                        <button class="rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-violet-200">Send</button>
                    </div>
                </footer>
            </section>
        </div>
    </div>
@endsection
