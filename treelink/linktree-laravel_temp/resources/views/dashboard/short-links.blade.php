@extends('layouts.dashboard')

@section('title', 'Short Links')
@section('page-title', 'Short Links')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">Short Links</p>
            <h2 class="mt-2 text-3xl font-extrabold tracking-[-0.06em] text-slate-900">Create and manage your shortened TreeLink URLs.</h2>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-[30px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
                <h3 class="text-xl font-extrabold tracking-[-0.05em] text-slate-900">Create Short Link</h3>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Destination URL</label>
                        <input type="url" value="https://instagram.com/athaya" class="w-full rounded-2xl border border-[#ece6ff] bg-[#f8f7ff] px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-violet-300 focus:ring-4 focus:ring-violet-100" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Custom Address</label>
                        <input type="text" value="tree.link/athaya" class="w-full rounded-2xl border border-[#ece6ff] bg-[#f8f7ff] px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-violet-300 focus:ring-4 focus:ring-violet-100" />
                    </div>
                    <button class="w-full rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-200">Create Short Link</button>
                </div>
            </div>

            <div class="rounded-[30px] border border-[#ece6ff] bg-white p-5 shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold tracking-[-0.05em] text-slate-900">Your short links</h3>
                    <span class="rounded-full bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700">12 total</span>
                </div>

                <div class="mt-5 space-y-3">
                    @php $links = [
                        ['name' => 'tree.link/athaya', 'destination' => 'https://instagram.com/athaya', 'clicks' => '2.1K'],
                        ['name' => 'tree.link/shop', 'destination' => 'https://shop.example.com', 'clicks' => '1.3K'],
                        ['name' => 'tree.link/portfolio', 'destination' => 'https://behance.net/athaya', 'clicks' => '860'],
                    ]; @endphp
                    @foreach ($links as $link)
                        <div class="flex flex-col gap-3 rounded-2xl border border-[#f0ebff] bg-[#faf8ff] p-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $link['name'] }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $link['destination'] }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">{{ $link['clicks'] }} clicks</span>
                                <button class="rounded-xl border border-[#ece6ff] bg-white px-3 py-2 text-xs font-semibold text-slate-700">Copy</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection