@extends('layouts.dashboard')

@section('title', 'Appearance')
@section('page-title', 'Appearance')

@section('content')
<div class="grid lg:grid-cols-3 gap-8"
     x-data="{
        theme: @js(old('theme', $profile->theme)),
        buttonStyle: @js(old('button_style', $profile->button_style)),
        font: @js(old('font', $profile->font)),
        socials: @js(old('social_links', $profile->social_links ?? [])),
        featuredLink: @js((string) old('featured_link_id', $profile->featured_link_id ?? '')),
        animationsEnabled: @js((bool) old('animations_enabled', $profile->animations_enabled)),
        seoTitle: @js(old('seo_title', $profile->seo_title)),
        seoDescription: @js(old('seo_description', $profile->seo_description)),
        saving: false,
        copied: false,
        themes: @js($themes),
        links: @js($links->map(fn ($link) => ['id' => (string) $link->id, 'title' => $link->title, 'url' => $link->url])->values()),
        platforms: @js(array_intersect_key(\App\Models\Profile::SOCIAL_PLATFORMS, array_flip(['instagram', 'tiktok', 'youtube', 'facebook', 'twitter', 'whatsapp']))),
        get t() { return this.themes[this.theme] ?? this.themes['aurora'] },
        get selectedLink() { return this.links.find(link => link.id === this.featuredLink) },
        get btnClass() {
            return { pill: 'rounded-full', square: 'rounded-md', outline: 'rounded-xl border-2 border-current bg-transparent' }[this.buttonStyle] ?? 'rounded-xl'
        },
        copyUrl() {
            navigator.clipboard.writeText(@js(auth()->user()->publicUrl())).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        }
     }">
    <div class="lg:col-span-2 space-y-6">

        <form method="POST" action="{{ route('dashboard.appearance.update') }}" @submit="saving = true">
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

            {{-- Enhance --}}
            <div class="mt-6 space-y-6">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold text-ink-900">Enhance</h2>
                        <p class="text-sm text-ink-500 mt-1">Buat halaman publikmu lebih mudah ditemukan dan dibagikan.</p>
                    </div>
                    <span x-show="saving" x-cloak class="text-xs text-brand-600 font-semibold">Menyimpan...</span>
                </div>

                <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                    <h3 class="font-bold text-ink-900 mb-1">Ikon Sosial Media</h3>
                    <p class="text-sm text-ink-500 mb-5">Isi URL yang ingin ditampilkan di halaman publik.</p>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <template x-for="(platform, key) in platforms" :key="key">
                            <div>
                                <label class="block text-sm font-semibold text-ink-800 mb-1.5" :for="`social-${key}`">
                                    <span class="inline-flex w-6 text-center text-brand-600" x-text="{instagram:'◎', tiktok:'♪', youtube:'▶', facebook:'f', twitter:'𝕏', whatsapp:'◉'}[key]"></span>
                                    <span x-text="platform.label"></span>
                                </label>
                                <input :id="`social-${key}`" type="url" :name="`social_links[${key}]`" x-model="socials[key]" placeholder="https://..."
                                       class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                    <h3 class="font-bold text-ink-900 mb-1">Link Unggulan</h3>
                    <p class="text-sm text-ink-500 mb-4">Pilih link aktif yang ingin dibuat paling menonjol.</p>
                    <select name="featured_link_id" x-model="featuredLink" class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                        <option value="">Tidak ada link unggulan</option>
                        <template x-for="link in links" :key="link.id">
                            <option :value="link.id" x-text="`⭐ ${link.title}`"></option>
                        </template>
                    </select>
                    <p x-show="links.length === 0" class="text-xs text-ink-400 mt-3">Buat link aktif terlebih dahulu di halaman Links.</p>
                </div>

                <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card flex items-center justify-between gap-4">
                    <div><h3 class="font-bold text-ink-900">Animasi</h3><p class="text-sm text-ink-500 mt-1">Gunakan transisi halus pada elemen halaman publik.</p></div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="animations_enabled" value="0">
                        <input type="checkbox" name="animations_enabled" value="1" x-model="animationsEnabled" class="sr-only peer">
                        <span class="w-11 h-6 bg-ink-200 rounded-full peer-checked:bg-brand-600 transition-colors"></span>
                        <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></span>
                    </label>
                </div>

                <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card space-y-5">
                    <div><h3 class="font-bold text-ink-900">SEO</h3><p class="text-sm text-ink-500 mt-1">Atur informasi yang muncul di mesin pencari.</p></div>
                    <div>
                        <div class="flex justify-between items-center mb-1.5"><label for="seo-title" class="text-sm font-semibold text-ink-800">Judul SEO</label><span class="text-xs text-ink-400" x-text="`${seoTitle.length}/60`"></span></div>
                        <input id="seo-title" name="seo_title" type="text" maxlength="60" x-model="seoTitle" placeholder="Nama atau judul halaman" class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1.5"><label for="seo-description" class="text-sm font-semibold text-ink-800">Deskripsi SEO</label><span class="text-xs text-ink-400" x-text="`${seoDescription.length}/300`"></span></div>
                        <textarea id="seo-description" name="seo_description" maxlength="300" rows="3" x-model="seoDescription" placeholder="Deskripsi singkat halamanmu" class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm"></textarea>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                    <h3 class="font-bold text-ink-900 mb-1">Bagikan Halaman</h3>
                    <p class="text-sm text-ink-500 mb-4">URL publik akunmu.</p>
                    <div class="flex gap-2"><input readonly value="{{ auth()->user()->publicUrl() }}" class="min-w-0 flex-1 px-4 py-3 rounded-xl border border-ink-200 bg-ink-50 text-sm text-ink-600"><button type="button" @click="copyUrl()" class="px-4 py-3 rounded-xl bg-ink-900 text-white text-sm font-semibold hover:bg-ink-700 transition" x-text="copied ? 'Tersalin!' : 'Salin'"></button></div>
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
                        <div class="flex justify-center flex-wrap gap-2 mt-4" x-show="Object.values(socials).some(Boolean)">
                            <template x-for="(url, key) in socials" :key="key"><span x-show="url" class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold" x-text="{instagram:'◎', tiktok:'♪', youtube:'▶', facebook:'f', twitter:'𝕏', whatsapp:'◉'}[key] || '•'"></span></template>
                        </div>
                        <div class="mt-5 space-y-2.5">
                            <template x-for="link in links" :key="link.id">
                                <div class="w-full py-2.5 px-4 bg-white/15 backdrop-blur text-xs font-semibold truncate transition-all" :class="[btnClass, link.id === featuredLink ? 'ring-2 ring-yellow-300 scale-[1.03]' : '', animationsEnabled ? 'animate-fade-up' : '']"><span x-show="link.id === featuredLink">⭐ </span><span x-text="link.title"></span></div>
                            </template>
                            <div x-show="links.length === 0" class="w-full py-2.5 px-4 bg-white/15 backdrop-blur text-xs font-semibold" :class="btnClass">Belum ada link aktif</div>
                        </div>
                    </div>
                </div>
                <p class="text-center text-xs text-ink-400 mt-3">Berubah otomatis saat kamu memilih tema</p>
            </div>
        </div>
    </div>
</div>
@endsection
