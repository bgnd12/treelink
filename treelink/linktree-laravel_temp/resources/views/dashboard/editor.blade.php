@extends('layouts.editor')

@section('title', 'Editor')

@section('content')
    <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1fr)_360px]" x-data='editor(@json($editor))'>
        <div class="min-w-0 space-y-6">
            <div x-show="toast" x-transition.opacity.duration.200ms class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded-2xl border border-[#ece6ff] bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-[0_18px_35px_rgba(124,58,237,0.18)]" :class="toastType === 'err' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-violet-200 bg-white text-slate-700'">
                <p x-text="toast"></p>
            </div>

            <nav class="flex gap-2 overflow-x-auto rounded-[28px] border border-[#ece6ff] bg-white p-2 shadow-[0_12px_30px_rgba(124,58,237,0.04)]">
                @php
                    $nav = [
                        'profile' => ['label' => 'Profile'],
                        'linkid' => ['label' => 'LinkID'],
                        'shop' => ['label' => 'Shop'],
                        'appearance' => ['label' => 'Appearance'],
                    ];
                @endphp
                @foreach ($nav as $key => $item)
                    <button type="button" @click="setTab('{{ $key }}')" class="shrink-0 rounded-2xl px-4 py-2.5 text-sm font-semibold transition" :class="tab === '{{ $key }}' ? 'bg-violet-600 text-white shadow-lg shadow-violet-200' : 'text-slate-600 hover:bg-violet-50 hover:text-violet-700'">
                        {{ $item['label'] }}
                    </button>
                @endforeach
            </nav>

            <section x-show="tab === 'profile'" x-cloak x-transition.opacity class="space-y-5">
                <div class="rounded-[28px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.04)]">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-extrabold tracking-[-0.05em] text-slate-900">Profile</h1>
                            <p class="mt-1 text-sm text-slate-500">Manage your bio, links, and public identity.</p>
                        </div>
                    </div>

                    <div class="inline-flex gap-2 rounded-2xl bg-[#f5f2ff] p-1">
                        <button type="button" @click="setContentTab('links')" class="rounded-xl px-4 py-2 text-sm font-semibold transition" :class="content_tab === 'links' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500'">Links</button>
                        <button type="button" @click="setContentTab('shop')" class="rounded-xl px-4 py-2 text-sm font-semibold transition" :class="content_tab === 'shop' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500'">Shop</button>
                    </div>

                    <div x-show="content_tab === 'links'" class="mt-5">
                        @include('dashboard.editor-panels.content-links')
                    </div>

                    <div x-show="content_tab === 'shop'" x-cloak class="mt-5">
                        @include('dashboard.editor-panels.content-products')
                    </div>
                </div>
            </section>

            <section x-show="tab === 'linkid'" x-cloak x-transition.opacity class="space-y-6">
                <div class="rounded-[28px] border border-[#ece6ff] bg-gradient-to-br from-violet-50 to-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.04)]">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">LinkID</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-[-0.05em] text-slate-900">Open for collaboration</h3>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" class="peer sr-only" checked />
                            <span class="h-7 w-12 rounded-full bg-violet-600 transition peer-checked:bg-violet-600"></span>
                            <span class="absolute left-1 top-1 h-5 w-5 rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </label>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-[#ece6ff] bg-white p-4">
                            <p class="text-sm font-semibold text-slate-800">Collaboration types</p>
                            <div class="mt-3 space-y-2 text-sm text-slate-600">
                                <label class="flex items-center gap-2"><input type="checkbox" checked /> Content Creator</label>
                                <label class="flex items-center gap-2"><input type="checkbox" checked /> Product</label>
                                <label class="flex items-center gap-2"><input type="checkbox" /> Brand</label>
                                <label class="flex items-center gap-2"><input type="checkbox" /> Event</label>
                                <label class="flex items-center gap-2"><input type="checkbox" /> Project</label>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-[#ece6ff] bg-white p-4">
                            <p class="text-sm font-semibold text-slate-800">Collaboration description</p>
                            <textarea class="mt-3 min-h-[145px] w-full rounded-2xl border border-[#ece6ff] bg-[#f8f7ff] px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-violet-300 focus:ring-4 focus:ring-violet-100">Terbuka untuk kolaborasi kreatif, content, brand partnership, dan product campaign.</textarea>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-dashed border-violet-200 bg-violet-50/70 p-4">
                        <p class="text-sm font-semibold text-slate-800">Contact</p>
                        <input type="text" value="hello@athaya.link" class="mt-2 w-full rounded-2xl border border-violet-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-violet-300 focus:ring-4 focus:ring-violet-100" />
                    </div>
                </div>
            </section>

            <section x-show="tab === 'shop'" x-cloak x-transition.opacity>
                @include('dashboard.editor-panels.content-products')
            </section>

            <section x-show="tab === 'appearance'" x-cloak x-transition.opacity>
                @include('dashboard.editor-panels.design')
            </section>
        </div>

        <div class="lg:sticky lg:top-20">
            <div class="rounded-[30px] border border-[#ece6ff] bg-white p-3 shadow-[0_12px_30px_rgba(124,58,237,0.06)]">
                <p class="mb-3 text-center text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Live preview</p>
                @include('dashboard.editor-panels.phone-preview')
            </div>
        </div>
    </div>
@endsection