<form class="space-y-5" @submit.prevent="saveHeader($el)">
    @csrf
    <input type="hidden" name="tab" value="header">
    <input type="hidden" name="layout" x-model="design.settings.layout">
    <input type="hidden" name="name" x-model="profile.name">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-ink-900">Header</h1>
            <p class="text-sm text-ink-500 mt-0.5">Identitas halaman profil kamu.</p>
        </div>
    </div>

    {{-- Avatar --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-4">Foto Profil</h2>
        <div class="flex items-center gap-4">
            <div class="relative">
                <img :src="previewAvatar()" class="w-20 h-20 rounded-full object-cover border-4 border-ink-100" alt="Avatar">
                <span class="absolute inset-0 rounded-full ring-1 ring-inset ring-black/5"></span>
            </div>
            <div class="flex-1">
                <label class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-ink-200 text-sm font-semibold text-ink-700 cursor-pointer hover:border-brand-400 hover:text-brand-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                    Ganti Foto
                    <input type="file" name="avatar" accept="image/*" class="hidden" @change="triggerAvatar($event.target)">
                </label>
                <p class="text-xs text-ink-400 mt-2">JPG, PNG. Maksimal 2MB.</p>
            </div>
        </div>
    </div>

    {{-- Basic info --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card space-y-4">
        <h2 class="font-bold text-ink-900">Informasi Dasar</h2>

        <div>
            <label class="block text-xs font-semibold text-ink-700 mb-1.5">Nama Tampilan</label>
            <input type="text" name="display_name" x-model="profile.display_name" placeholder="Misal: Ghea Beauty"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-ink-700 mb-1.5">Username</label>
            <div class="flex rounded-xl border border-ink-200 focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-100 overflow-hidden transition">
                <span class="px-3 flex items-center bg-ink-50 text-ink-400 text-sm border-r border-ink-200">/</span>
                <input type="text" name="username" x-model="profile.username" class="w-full px-3 py-2.5 outline-none text-sm">
            </div>
            <p class="text-xs text-ink-400 mt-1.5">Hanya huruf, angka, dan underscore.</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-ink-700 mb-1.5">Bio</label>
            <textarea name="bio" x-model="profile.bio" rows="3" maxlength="280" placeholder="Ceritakan sedikit tentang dirimu…"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm resize-none"></textarea>
            <div class="flex justify-end mt-1">
                <span class="text-xs text-ink-400 tabular-nums" x-text="(profile.bio || '').length + '/280'"></span>
            </div>
        </div>
    </div>

    {{-- Layout --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-1">Layout</h2>
        <p class="text-sm text-ink-500 mb-4">Pilih cara header kamu ditampilkan.</p>

        <div class="grid grid-cols-1 gap-2.5">
            @foreach (\App\Models\Profile::HEADER_LAYOUTS as $key => $layout)
                <button type="button" @click="design.settings.layout = '{{ $key }}'"
                        class="flex items-center gap-3 p-3 rounded-xl border transition text-left"
                        :class="design.settings.layout === '{{ $key }}' ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-100' : 'border-ink-200 hover:border-ink-300'">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                          :class="design.settings.layout === '{{ $key }}' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-500'">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="3.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 19a6.5 6.5 0 0 1 13 0" />
                        </svg>
                    </span>
                    <span class="flex-1">
                        <span class="block text-sm font-semibold text-ink-900">{{ $layout['label'] }}</span>
                        <span class="block text-xs text-ink-500">{{ $layout['desc'] }}</span>
                    </span>
                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                          :class="design.settings.layout === '{{ $key }}' ? 'border-brand-600' : 'border-ink-300'">
                        <span class="w-2.5 h-2.5 rounded-full" :class="design.settings.layout === '{{ $key }}' ? 'bg-brand-600' : ''"></span>
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Social links --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-1">Social Media</h2>
        <p class="text-sm text-ink-500 mb-4">Tampil sebagai ikon di bawah header halaman publikmu.</p>

        <div class="grid sm:grid-cols-2 gap-3">
            @foreach (\App\Models\Profile::SOCIAL_PLATFORMS as $key => $platform)
                <div>
                    <label class="block text-xs font-semibold text-ink-600 mb-1">{{ $platform['label'] }}</label>
                    <input type="text" name="social_links[{{ $key }}]" x-model="socials['{{ $key }}']"
                           placeholder="{{ $platform['placeholder'] }}"
                           class="w-full px-3 py-2 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" :disabled="loading"
            class="w-full py-3.5 rounded-2xl bg-brand-600 text-white font-bold shadow-soft hover:bg-brand-700 transition disabled:opacity-50">
        <span x-text="loading ? 'Menyimpan…' : 'Simpan Header'"></span>
    </button>
</form>