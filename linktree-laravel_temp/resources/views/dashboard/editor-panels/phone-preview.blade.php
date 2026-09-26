<div class="mx-auto w-[270px] sm:w-[300px]">

    <div class="rounded-[3rem] border-[10px] border-ink-900 bg-white shadow-2xl overflow-hidden">

        {{-- Notch --}}
        <div class="absolute h-6 w-32 rounded-b-2xl bg-ink-900 left-1/2 -translate-x-1/2 z-10 hidden"></div>

        <div class="relative h-[560px] sm:h-[600px] overflow-y-auto no-scrollbar px-6 pt-10 pb-10 text-center transition-colors"
             :style="screenStyle()">

            {{-- ============ CLASSIC ============ --}}
            <template x-if="design.settings.layout === 'classic'">
                <div>
                    <img :src="previewAvatar()" class="w-20 h-20 rounded-full object-cover mx-auto border-4"
                         :style="'border-color:' + accentColor()" alt="Avatar">
                    <h1 class="mt-3 text-lg font-extrabold tracking-tight" x-text="profile.display_name || profile.name"></h1>
                    <p class="text-xs opacity-70 font-medium" x-text="'@' + profile.username"></p>
                    <p x-show="profile.bio" class="text-xs opacity-85 mt-2.5 px-2 leading-relaxed" x-text="profile.bio"></p>
                </div>
            </template>

            {{-- ============ HERO ============ --}}
            <template x-if="design.settings.layout === 'hero'">
                <div class="pt-2">
                    <div class="relative">
                        <span class="absolute left-1/2 -top-3 -translate-x-1/2 w-36 h-36 rounded-full blur-2xl opacity-40"
                              :style="'background:' + accentColor()"></span>
                        <img :src="previewAvatar()" class="relative w-24 h-24 rounded-full object-cover mx-auto border-4"
                             :style="'border-color:' + accentColor()" alt="Avatar">
                    </div>
                    <h1 class="mt-3 text-2xl font-extrabold tracking-tight" x-text="profile.display_name || profile.name"></h1>
                    <p class="text-xs opacity-70 font-medium" x-text="'@' + profile.username"></p>
                    <p x-show="profile.bio" class="text-xs opacity-85 mt-2.5 px-2 leading-relaxed" x-text="profile.bio"></p>
                </div>
            </template>

            {{-- ============ BANNER ============ --}}
            <template x-if="design.settings.layout === 'banner'">
                <div>
                    <div class="h-24 -mx-6 -mt-10 mb-2"
                         :style="'background:linear-gradient(135deg,' + theme().from_hex + ',' + theme().to_hex + ')'"></div>
                    <img :src="previewAvatar()" class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-white/50 -mt-9 relative shadow-lg"
                         alt="Avatar">
                    <h1 class="mt-2 text-lg font-extrabold tracking-tight" x-text="profile.display_name || profile.name"></h1>
                    <p class="text-xs opacity-70 font-medium" x-text="'@' + profile.username"></p>
                    <p x-show="profile.bio" class="text-xs opacity-85 mt-2 px-2 leading-relaxed" x-text="profile.bio"></p>
                </div>
            </template>

            {{-- ============ CUTOUT ============ --}}
            <template x-if="design.settings.layout === 'cutout'">
                <div class="pt-2">
                    <div class="relative w-24 h-24 mx-auto">
                        <span class="absolute inset-0 rounded-full opacity-30"
                              :style="'background:' + accentColor()"></span>
                        <img :src="previewAvatar()" class="relative w-24 h-24 rounded-full object-cover border-4 border-white/50"
                             alt="Avatar">
                        <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full text-[10px] font-bold shadow"
                              x-text="'@' + profile.username"></span>
                    </div>
                    <h1 class="mt-5 text-lg font-extrabold tracking-tight" x-text="profile.display_name || profile.name"></h1>
                    <p x-show="profile.bio" class="text-xs opacity-85 mt-1 px-2 leading-relaxed" x-text="profile.bio"></p>
                </div>
            </template>

            {{-- ============ SHAPE ============ --}}
            <template x-if="design.settings.layout === 'shape'">
                <div class="pt-1">
                    <img :src="previewAvatar()" class="w-20 h-20 rounded-[1.75rem] object-cover mx-auto border-4 rotate-3"
                         :style="'border-color:' + accentColor()" alt="Avatar">
                    <h1 class="mt-4 text-xl font-extrabold tracking-tight" x-text="profile.display_name || profile.name"></h1>
                    <p class="text-xs opacity-70 font-medium" x-text="'@' + profile.username"></p>
                    <p x-show="profile.bio" class="text-xs opacity-85 mt-2 px-2 leading-relaxed" x-text="profile.bio"></p>
                </div>
            </template>

            {{-- Socials --}}
            <div x-show="settings.show_social && Object.keys(socials).length" x-cloak
                 class="flex justify-center flex-wrap gap-2 mt-4">
                <template x-for="(url, key) in socials" :key="key">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-bold"
                          :style="'background-color:rgba(128,90,213,0.12); border:1px solid rgba(128,90,213,0.25)'"
                          x-text="({instagram:'◎',tiktok:'♪',youtube:'▶',facebook:'f',x:'𝕏',whatsapp:'◉'})[key] || key.charAt(0).toUpperCase()"></span>
                </template>
            </div>

            {{-- My Links --}}
            <div class="mt-6">
                <div class="flex items-center gap-3 text-[9px] font-bold uppercase tracking-[0.2em] opacity-40">
                    <span class="h-px flex-1" :style="'background:' + textColor()"></span>
                    <span>My Links</span>
                    <span class="h-px flex-1" :style="'background:' + textColor()"></span>
                </div>

                <div class="space-y-2.5 mt-4">
                    <template x-for="link in activeLinks" :key="link.id">
                            <div class="w-full px-4 py-3 flex items-center gap-2.5 text-[13px] font-semibold transition-all"
                             :style="btnStyle()"
                                :class="[(design.button_style === 'pill' ? 'px-6' : ''), String(settings.featured_link_id) === String(link.id) ? 'ring-2 ring-yellow-300 scale-[1.03]' : '', settings.animations_enabled ? 'animate-fade-up' : '']">
                               <span x-show="String(settings.featured_link_id) === String(link.id)">⭐</span>
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-extrabold text-white shrink-0" :style="'background:' + iconColor(link.icon)" x-html="iconSvg(link.icon)"></span>
                            <span class="truncate flex-1 text-left" x-text="link.title"></span>
                        </div>
                    </template>
                    <p x-show="!activeLinks.length" class="text-xs opacity-60 mt-4" x-text="'Belum ada link aktif'"></p>
                </div>
            </div>

            {{-- Share button --}}
            <div x-show="settings.show_share" x-cloak class="mt-7">
                <span class="inline-flex items-center gap-1.5 px-5 py-2 rounded-full text-[11px] font-semibold border"
                      :style="'background-color:rgba(128,90,213,0.1); border-color:rgba(128,90,213,0.3)'">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                    Bagikan Halaman
                </span>
            </div>
        </div>
    </div>

    <p class="text-center text-xs text-ink-400 mt-4 font-medium">Besutan TreeLink · treelink.app</p>
</div>