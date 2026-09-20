@extends('layouts.dashboard')

@section('title', 'Appearance')
@section('page-title', 'Appearance')

@section('content')
<div class="grid lg:grid-cols-3 gap-8"
     x-data="{
        theme: '{{ $profile->theme }}',
        buttonStyle: '{{ $profile->button_style }}',
        font: '{{ $profile->font }}',
        themes: @js($themes),
        get t() { return this.themes[this.theme] ?? this.themes['aurora'] },
        get btnClass() {
            return { pill: 'rounded-full', square: 'rounded-md', outline: 'rounded-xl border-2 border-current bg-transparent' }[this.buttonStyle] ?? 'rounded-xl'
        }
     }">
    <div class="lg:col-span-2 space-y-6">

        <form method="POST" action="{{ route('dashboard.appearance.update') }}">
            @csrf

            {{-- Theme picker --}}
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <h2 class="font-bold text-ink-900 mb-1">Pilih Tema</h2>
                <p class="text-sm text-ink-500 mb-5">Warna latar belakang halaman publikmu.</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ($themes as $key => $t)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="theme" value="{{ $key }}" x-model="theme" class="peer sr-only">
                            <div class="h-24 rounded-2xl bg-gradient-to-br {{ $t['from'] }} {{ $t['to'] }} flex items-end p-3 ring-2 ring-transparent peer-checked:ring-brand-600 peer-checked:ring-offset-2 transition-all">
                                <span class="text-xs font-bold {{ $t['text'] }}">{{ $t['label'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Button style --}}
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card mt-6">
                <h2 class="font-bold text-ink-900 mb-1">Gaya Tombol</h2>
                <p class="text-sm text-ink-500 mb-5">Bentuk tombol link pada halaman publikmu.</p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($buttonStyles as $style)
                        <label class="cursor-pointer">
                            <input type="radio" name="button_style" value="{{ $style }}" x-model="buttonStyle" class="peer sr-only">
                            <div class="h-12 flex items-center justify-center bg-ink-100 text-ink-700 text-xs font-bold ring-2 ring-transparent peer-checked:ring-brand-600 transition-all
                                {{ match($style) { 'pill' => 'rounded-full', 'square' => 'rounded-md', 'outline' => 'rounded-xl border-2 border-ink-400 bg-transparent', default => 'rounded-xl' } }}">
                                {{ ucfirst($style) }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Font --}}
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card mt-6">
                <h2 class="font-bold text-ink-900 mb-1">Font</h2>
                <p class="text-sm text-ink-500 mb-5">Gaya tipografi untuk halaman publikmu.</p>
                <div class="grid grid-cols-3 gap-4">
                    @foreach (['sans' => 'Sans-serif', 'serif' => 'Serif', 'mono' => 'Monospace'] as $key => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="font" value="{{ $key }}" x-model="font" class="peer sr-only">
                            <div class="h-12 flex items-center justify-center rounded-xl bg-ink-100 text-ink-700 text-sm ring-2 ring-transparent peer-checked:ring-brand-600 transition-all" style="font-family: {{ $key }}">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="mt-6 w-full sm:w-auto px-8 py-3.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <div>
        <div class="sticky top-24">
            <p class="text-sm font-semibold text-ink-500 mb-3 text-center">Live Preview</p>
            <div class="mx-auto w-[280px]">
                <div class="rounded-[2.5rem] border-8 border-ink-900 bg-ink-900 shadow-2xl overflow-hidden">
                    <div class="h-[520px] overflow-y-auto px-5 pt-9 pb-8 text-center transition-colors duration-300"
                         :class="`bg-gradient-to-b ${t.from} ${t.to} ${t.text}`">
                        <img src="{{ $profile->avatar_url }}" class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-white/40" alt="Avatar">
                        <p class="mt-3 font-bold">{{ $profile->display_name ?: auth()->user()->name }}</p>
                        <p class="text-xs opacity-70">@{{ auth()->user()->username }}</p>
                        @if ($profile->bio)
                            <p class="text-xs opacity-80 mt-2 px-2">{{ $profile->bio }}</p>
                        @endif
                        <div class="mt-5 space-y-2.5">
                            @forelse (auth()->user()->links()->where('is_active', true)->get() as $link)
                                <div class="w-full py-2.5 px-4 bg-white/15 backdrop-blur text-xs font-semibold truncate" :class="btnClass">
                                    {{ $link->title }}
                                </div>
                            @empty
                                <div class="w-full py-2.5 px-4 bg-white/15 backdrop-blur text-xs font-semibold" :class="btnClass">Contoh Link</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <p class="text-center text-xs text-ink-400 mt-3">Berubah otomatis saat kamu memilih tema</p>
            </div>
        </div>
    </div>
</div>
@endsection
