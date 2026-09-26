<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-ink-900">Enhance</h1>
            <p class="text-sm text-ink-500 mt-0.5">Perkaya halaman kamu dengan sosial, featured link, animasi, dan SEO.</p>
        </div>
    </div>

    <form class="space-y-5" @submit.prevent="saveEnhance($el)">
        @csrf
        <input type="hidden" name="tab" value="enhance">
        <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
            <h2 class="font-bold text-ink-900">Ikon Sosial Media</h2>
            <p class="text-sm text-ink-500 mt-1 mb-5">Isi URL untuk menampilkannya di halaman publik.</p>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach (['instagram' => ['label' => 'Instagram', 'placeholder' => 'https://instagram.com/username'], 'tiktok' => ['label' => 'TikTok', 'placeholder' => 'https://tiktok.com/@username'], 'youtube' => ['label' => 'YouTube', 'placeholder' => 'https://youtube.com/@username'], 'facebook' => ['label' => 'Facebook', 'placeholder' => 'https://facebook.com/username'], 'x' => ['label' => 'X / Twitter', 'placeholder' => 'https://x.com/username'], 'whatsapp' => ['label' => 'WhatsApp', 'placeholder' => 'https://wa.me/628xxxxxxxxxx']] as $key => $platform)
                    <div><label for="enhance-social-{{ $key }}" class="block text-sm font-semibold text-ink-800 mb-1.5">{{ $platform['label'] }}</label><input id="enhance-social-{{ $key }}" type="url" name="social_links[{{ $key }}]" value="{{ $editor['socials'][$key] ?? '' }}" placeholder="{{ $platform['placeholder'] }}" class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm"></div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card"><h2 class="font-bold text-ink-900">Link Unggulan</h2><p class="text-sm text-ink-500 mt-1 mb-4">Pilih salah satu link aktif untuk dibuat lebih menonjol.</p><select name="featured_link_id" class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 focus:border-brand-500 outline-none text-sm"><option value="">Tidak ada link unggulan</option>@foreach ($links->where('is_active', true) as $link)<option value="{{ $link->id }}" @selected((string) ($editor['design']['settings']['featured_link_id'] ?? '') === (string) $link->id)>⭐ {{ $link->title }}</option>@endforeach</select></div>

        <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card flex items-center justify-between gap-4"><div><h2 class="font-bold text-ink-900">Animasi</h2><p class="text-sm text-ink-500 mt-1">Fade-in ringan pada elemen halaman publik.</p></div><label class="relative inline-flex items-center cursor-pointer shrink-0"><input type="hidden" name="animations_enabled" value="0"><input type="checkbox" name="animations_enabled" value="1" class="sr-only peer" @checked($editor['design']['settings']['animations_enabled'] ?? false)><span class="w-11 h-6 rounded-full bg-ink-200 peer-checked:bg-brand-600"></span><span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></span></label></div>

        <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card space-y-5" x-data="{ seoTitle: @js($editor['design']['settings']['seo_title'] ?? ''), seoDescription: @js($editor['design']['settings']['seo_description'] ?? '') }"><div><h2 class="font-bold text-ink-900">SEO</h2><p class="text-sm text-ink-500 mt-1">Informasi yang digunakan pada mesin pencari.</p></div><div><div class="flex justify-between mb-1.5"><label for="seo-title" class="text-sm font-semibold text-ink-800">Judul SEO</label><span class="text-xs text-ink-400" x-text="`${seoTitle.length}/60`"></span></div><input id="seo-title" name="seo_title" maxlength="60" x-model="seoTitle" class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 outline-none text-sm"></div><div><div class="flex justify-between mb-1.5"><label for="seo-description" class="text-sm font-semibold text-ink-800">Deskripsi SEO</label><span class="text-xs text-ink-400" x-text="`${seoDescription.length}/300`"></span></div><textarea id="seo-description" name="seo_description" maxlength="300" rows="3" x-model="seoDescription" class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 outline-none text-sm"></textarea></div></div>

        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 rounded-2xl p-5 sm:p-6 text-white shadow-soft"><h2 class="font-bold">Bagikan Halamanmu</h2><p class="text-brand-100 text-sm mt-1">{{ $user->publicUrl() }}</p><button type="button" x-data="{ copied: false }" @click="navigator.clipboard.writeText(@js($user->publicUrl())).then(() => { copied = true; setTimeout(() => copied = false, 1800); })" class="mt-4 px-4 py-2 rounded-lg bg-white text-brand-700 text-xs font-bold" x-text="copied ? 'Tersalin!' : 'Salin'"></button></div>

        <button type="submit" :disabled="loading" class="w-full py-3 rounded-2xl bg-brand-600 text-white font-bold shadow-soft hover:bg-brand-700 transition disabled:opacity-50"><span x-text="loading ? 'Menyimpan...' : 'Simpan Pengaturan'"></span></button>
    </form>

    {{-- Share card --}}
    <div class="bg-gradient-to-br from-brand-600 to-indigo-700 rounded-2xl p-6 text-white shadow-soft">
        <h2 class="font-bold">Bagikan Halamanmu</h2>
        <p class="text-brand-100 text-sm mt-1">Satu link untuk semua kontenmu.</p>
        <div class="mt-4 flex items-center gap-2 bg-black/15 rounded-xl px-3 py-2.5">
            <span class="text-sm font-mono truncate flex-1">{{ $user->publicUrl() }}</span>
            <button type="button" x-data="{ copied: false }" @click="navigator.clipboard.writeText(@js($user->publicUrl())).then(() => { copied = true; setTimeout(() => copied = false, 1800); })"
                    class="px-3 py-1.5 rounded-lg bg-white text-brand-700 text-xs font-bold shrink-0">
                <span x-text="copied ? '✓' : 'Copy'"></span>
            </button>
        </div>
        <a href="{{ $user->publicUrl() }}" target="_blank"
           class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-100 hover:text-white transition">
            Lihat halaman publik ↗
        </a>
    </div>
</div>