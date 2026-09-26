<form class="space-y-5" @submit.prevent="saveDesign($el)">
    @csrf
    <input type="hidden" name="tab" value="design">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-ink-900">Design</h1>
            <p class="text-sm text-ink-500 mt-0.5">Sesuaikan tampilan halaman kamu secara real-time.</p>
        </div>
    </div>

    {{-- Wallpaper --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-4">Wallpaper</h2>

        <div class="inline-flex p-1 rounded-xl bg-ink-100/70 gap-1 mb-5">
            <input type="hidden" name="wallpaper_type" x-model="design.settings.wallpaper_type">
            @foreach (['gradient' => 'Gradient', 'solid' => 'Solid', 'pattern' => 'Pattern', 'custom' => 'Custom'] as $value => $label)
                <button type="button" @click="design.settings.wallpaper_type = '{{ $value }}'"
                        class="px-4 py-1.5 rounded-lg text-sm font-semibold transition"
                        :class="design.settings.wallpaper_type === '{{ $value }}' ? 'bg-white text-ink-900 shadow-card' : 'text-ink-500 hover:text-ink-700'">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div x-show="design.settings.wallpaper_type === 'custom'" x-cloak class="rounded-xl border border-brand-100 bg-brand-50/50 p-4 space-y-3">
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5">Upload wallpaper</label>
                <input type="file" name="custom_wallpaper" accept="image/jpeg,image/png,image/webp" class="w-full text-sm" @change="previewWallpaper($event.target.files[0])">
                <p class="text-xs text-ink-400 mt-1">JPG, PNG, atau WebP, maksimal 5MB.</p>
            </div>
            <div class="text-center text-xs text-ink-400">atau gunakan URL gambar</div>
            <input type="url" name="custom_wallpaper_url" x-model="design.settings.custom_wallpaper_url" placeholder="https://contoh.com/wallpaper.jpg" class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 outline-none focus:border-brand-500 text-sm">
        </div>

        {{-- Gradient presets --}}
        <div x-show="design.settings.wallpaper_type === 'gradient'" x-cloak>
            <label class="block text-xs font-semibold text-ink-700 mb-2">Pilih Gradient</label>
            <input type="hidden" name="theme" x-model="design.theme">
            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2.5">
                <template x-for="(swatch, key) in themes" :key="key">
                    <button type="button" @click="design.theme = key"
                            class="h-12 rounded-xl transition ring-offset-2"
                            :class="design.theme === key ? 'ring-2 ring-brand-500' : 'hover:scale-105'"
                            :style="'background:linear-gradient(135deg, ' + swatch.from_hex + ', ' + swatch.to_hex + ')'"
                            :title="swatch.label">
                        <span class="sr-only" x-text="swatch.label"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Solid / pattern color --}}
        <div x-show="design.settings.wallpaper_type !== 'gradient'" x-cloak>
            <label class="block text-xs font-semibold text-ink-700 mb-2">Warna Dasar</label>
            <div class="flex items-center gap-3">
                <input type="color" :value="design.settings.bg_color || '#ffffff'"
                       @input="design.settings.bg_color = $event.target.value"
                       class="w-11 h-11 rounded-xl border border-ink-200">
                <input type="hidden" name="bg_color" x-model="design.settings.bg_color">
                <span class="text-sm text-ink-500 font-mono" x-text="design.settings.bg_color || '#ffffff'"></span>
                <button type="button" @click="design.settings.bg_color = '#f8fafc'"
                        class="ml-auto text-xs font-semibold text-brand-600 hover:underline">Reset</button>
            </div>

            <div x-show="design.settings.wallpaper_type === 'pattern'" x-cloak class="mt-4">
                <label class="block text-xs font-semibold text-ink-700 mb-2">Pola</label>
                <input type="hidden" name="pattern" x-model="design.settings.pattern">
                <div class="grid grid-cols-4 gap-2.5">
                    @foreach (\App\Models\Profile::PATTERNS as $key => $pattern)
                        <button type="button" @click="design.settings.pattern = '{{ $key }}'"
                                class="py-2.5 rounded-xl border text-xs font-semibold transition"
                                :class="design.settings.pattern === '{{ $key }}' ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-ink-200 text-ink-600 hover:border-ink-300'">
                            {{ $pattern['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-4">Buttons</h2>

        <div class="mb-5">
            <label class="block text-xs font-semibold text-ink-700 mb-2">Gaya Tombol</label>
            <input type="hidden" name="button_style" x-model="design.button_style">
            <div class="grid grid-cols-2 gap-2.5">
                @foreach (\App\Models\Profile::BUTTON_STYLES as $key => $style)
                    <button type="button" @click="design.button_style = '{{ $key }}'"
                            class="p-3 rounded-xl border transition text-left"
                            :class="design.button_style === '{{ $key }}' ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-100' : 'border-ink-200 hover:border-ink-300'">
                        <span class="block text-sm font-semibold text-ink-900">{{ $style['label'] }}</span>
                        <span class="block text-xs text-ink-500">{{ $style['desc'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-semibold text-ink-700">Radius Sudut</label>
                    <span class="text-xs font-mono text-ink-500 tabular-nums" x-text="design.settings.button_radius + 'px'"></span>
                </div>
                <input type="range" name="button_radius" min="0" max="32" x-model.number="design.settings.button_radius"
                       class="w-full accent-brand-600">
            </div>

            <div class="flex items-center justify-between py-3 border-t border-ink-100">
                <div>
                    <p class="text-sm font-semibold text-ink-900">Bayangan</p>
                    <p class="text-xs text-ink-500">Tambahkan kedalaman pada tombol.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="button_shadow" value="0">
                    <input type="checkbox" name="button_shadow" value="1" class="sr-only peer"
                           x-model="design.settings.button_shadow">
                    <span class="w-11 h-6 rounded-full bg-ink-200 peer-checked:bg-brand-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></span>
                </label>
            </div>

            <div class="flex items-center justify-between py-3 border-t border-ink-100">
                <div>
                    <p class="text-sm font-semibold text-ink-900">Border</p>
                    <p class="text-xs text-ink-500">Garis tepi tipis pada tombol.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="button_border" value="0">
                    <input type="checkbox" name="button_border" value="1" class="sr-only peer"
                           x-model="design.settings.button_border">
                    <span class="w-11 h-6 rounded-full bg-ink-200 peer-checked:bg-brand-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></span>
                </label>
            </div>
        </div>
    </div>

    {{-- Colors --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-4">Colors</h2>

        @php
            $colorRows = [
                ['key' => 'bg_color', 'label' => 'Background', 'hint' => 'Dasar wallpaper saat mode Solid/Pattern'],
                ['key' => 'text_color', 'label' => 'Text', 'hint' => 'Kosongkan untuk otomatis kontras'],
                ['key' => 'button_color', 'label' => 'Button', 'hint' => 'Warna isi tombol solid'],
                ['key' => 'accent_color', 'label' => 'Accent', 'hint' => 'Aksen aksen kecil di header'],
            ];
        @endphp

        <div class="space-y-4">
            @foreach ($colorRows as $row)
                <div class="flex items-center gap-3">
                    <input type="color"
                           :value="design.settings['{{ $row['key'] }}'] || defaultColor('{{ $row['key'] }}')"
                           @input="design.settings['{{ $row['key'] }}'] = $event.target.value"
                           class="w-11 h-11 rounded-xl border border-ink-200 shrink-0">
                    <input type="hidden" name="{{ $row['key'] }}" x-model="design.settings['{{ $row['key'] }}']">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-ink-900">{{ $row['label'] }}</p>
                        <p class="text-xs text-ink-500 truncate">{{ $row['hint'] }}</p>
                    </div>
                    @if ($row['key'] !== 'bg_color')
                        <button type="button" @click="design.settings['{{ $row['key'] }}'] = ''"
                                class="text-xs font-semibold text-brand-600 hover:underline shrink-0">Auto</button>
                    @else
                        <button type="button" @click="design.settings.bg_color = '#f8fafc'"
                                class="text-xs font-semibold text-brand-600 hover:underline shrink-0">Reset</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Font --}}
    <div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-4">Font</h2>
        <input type="hidden" name="font" x-model="design.font">
        <div class="grid grid-cols-3 gap-2.5">
            @foreach (\App\Models\Profile::FONTS as $key => $font)
                <button type="button" @click="design.font = '{{ $key }}'"
                        class="py-3 rounded-xl border transition"
                        :class="design.font === '{{ $key }}' ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-100' : 'border-ink-200 hover:border-ink-300'">
                    <span class="block text-base font-semibold text-ink-900" style="font-family: {{ $font['family'] }}">Aa</span>
                    <span class="block text-xs text-ink-500 mt-1">{{ $font['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <button type="submit" :disabled="loading"
            class="w-full py-3.5 rounded-2xl bg-brand-600 text-white font-bold shadow-soft hover:bg-brand-700 transition disabled:opacity-50">
        <span x-text="loading ? 'Menyimpan…' : 'Simpan Design'"></span>
    </button>
</form>