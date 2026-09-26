{{-- Reactive phone preview — driven entirely by the editor() Alpine component state. --}}
<div class="mx-auto w-[300px]">
    <div class="rounded-[2.5rem] border-8 border-ink-900 bg-ink-900 shadow-2xl overflow-hidden">
        <div class="h-[560px] overflow-y-auto px-5 pt-10 pb-8 text-center transition-colors duration-300"
             :class="backgroundClass + ' ' + profile.theme_text"
             :style="backgroundStyle">

            {{-- BANNER header layout --}}
            <template x-if="profile.header_layout === 'banner'">
                <div class="pt-4">
                    <div class="h-20 w-[130%] -ml-[15%] rounded-b-3xl"
                         :class="isLight ? 'bg-black/10' : 'bg-white/15 backdrop-blur'"></div>
                    <img :src="avatarSrc" alt="Avatar"
                         class="w-24 h-24 rounded-full object-cover mx-auto -mt-10 border-4"
                         :class="isLight ? 'border-gray-200 shadow' : 'border-white/70 shadow-xl'">
                    <p class="mt-3 font-bold text-base">
                        <span x-show="profile.display_name" x-text="profile.display_name"></span>
                        <span x-show="!profile.display_name" x-text="profile.username"></span>
                    </p>
                    <p class="text-xs opacity-70">@<span x-text="profile.username"></span></p>
                    <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                </div>
            </template>

            {{-- Cutout header layout --}}
            <template x-if="profile.header_layout === 'cutout'">
                <div class="pt-6">
                    <div class="relative w-24 h-24 mx-auto">
                        <div class="absolute inset-2 rotate-45 rounded-3xl" :class="isLight ? 'bg-white/80' : 'bg-white/20'"></div>
                        <img :src="avatarSrc" alt="Avatar"
                             class="absolute inset-0 w-full h-full rounded-full object-cover border-4"
                             :class="isLight ? 'border-white' : 'border-white/80'">
                    </div>
                    <p class="mt-4 font-bold text-base">
                        <span x-show="profile.display_name" x-text="profile.display_name"></span>
                        <span x-show="!profile.display_name" x-text="profile.username"></span>
                    </p>
                    <p class="text-xs opacity-70">@<span x-text="profile.username"></span></p>
                    <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                </div>
            </template>

            {{-- Shape header layout --}}
            <template x-if="profile.header_layout === 'shape'">
                <div class="pt-6">
                    <div class="mx-auto w-24 h-24 rounded-[36%] p-1.5" :class="isLight ? 'bg-white shadow' : 'bg-white/25'">
                        <img :src="avatarSrc" alt="Avatar" class="w-full h-full rounded-[34%] object-cover">
                    </div>
                    <p class="mt-4 font-bold text-base">
                        <span x-show="profile.display_name" x-text="profile.display_name"></span>
                        <span x-show="!profile.display_name" x-text="profile.username"></span>
                    </p>
                    <p class="text-xs opacity-70">@<span x-text="profile.username"></span></p>
                    <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                </div>
            </template>

            {{-- Hero header layout --}}
            <template x-if="profile.header_layout === 'hero'">
                <div class="pt-4">
                    <div class="mx-auto w-28 h-28 rounded-[1.75rem] p-1" :class="isLight ? 'bg-white' : 'bg-white/90'">
                        <img :src="avatarSrc" alt="Avatar" class="w-full h-full rounded-[1.5rem] object-cover">
                    </div>
                    <p class="mt-4 font-extrabold text-lg">
                        <span x-show="profile.display_name" x-text="profile.display_name"></span>
                        <span x-show="!profile.display_name" x-text="profile.username"></span>
                    </p>
                    <p class="text-xs opacity-70 mt-0.5">@<span x-text="profile.username"></span></p>
                    <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                </div>
            </template>

            {{-- Classic header layout (default) --}}
            <template x-if="profile.header_layout === 'classic'">
                <div class="pt-2">
                    <img :src="avatarSrc" alt="Avatar"
                         class="w-20 h-20 rounded-full object-cover mx-auto border-4"
                         :class="isLight ? 'border-black/10' : 'border-white/40'">
                    <p class="mt-3 font-bold text-base">
                        <span x-show="profile.display_name" x-text="profile.display_name"></span>
                        <span x-show="!profile.display_name" x-text="profile.username"></span>
                    </p>
                    <p class="text-xs opacity-70">@<span x-text="profile.username"></span></p>
                    <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                </div>
            </template>

            {{-- Social icons --}}
            <template x-if="socials.length && socials.some(s => s.url)">
                <div class="flex justify-center flex-wrap gap-2 mt-4">
                    <template x-for="s in socials.filter(s => s.url && s.url.trim())" :key="s.key">
                        <span class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] uppercase font-bold"
                              :style="isLight ? 'background:rgba(0,0,0,.12);color:#111827' : 'background:rgba(255,255,255,.22);color:#fff'">
                            <span x-text="s.label.slice(0,1)"></span>
                        </span>
                    </template>
                </div>
            </template>

            {{-- Links --}}
            <div class="mt-5 space-y-2.5" x-show="activeLinks.length || activeProducts.length">
                <template x-for="link in activeLinks" :key="link.id">
                    <div class="flex items-center gap-2.5 w-full py-3 px-4 font-semibold text-sm truncate transition-transform duration-150"
                         :style="buttonStyle"
                         :class="link.is_featured ? 'ring-2 ring-offset-2 ' + (isLight ? 'ring-black/30 ring-offset-black/20' : 'ring-white/60 ring-offset-white/10') : ''">
                        <span class="w-6 h-6 flex-shrink-0 rounded-full flex items-center justify-center text-[10px] uppercase font-bold"
                              :style="isLight ? 'background:rgba(0,0,0,.12)' : 'background:rgba(0,0,0,.22)'">
                            <span x-text="(link.icon || 'link').slice(0,1)"></span>
                        </span>
                        <span x-text="link.title" class="flex-1 min-w-0 truncate"></span>
                        <span x-show="link.is_featured" class="text-xs flex-shrink-0">⭐</span>
                    </div>
                </template>

                {{-- Products / Shop --}}
                <template x-if="activeProducts.length">
                    <div class="pt-3">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] opacity-70 mb-3">Shop</p>
                        <div class="space-y-2.5">
                            <template x-for="product in activeProducts" :key="product.id">
                                <div class="flex items-center gap-3 w-full p-2.5 text-left transition-transform duration-150" :style="buttonStyle">
                                    <img x-show="product.image_url" :src="product.image_url"
                                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                    <span x-show="!product.image_url"
                                          class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 text-lg"
                                          :style="isLight ? 'background:rgba(0,0,0,.10)' : 'background:rgba(0,0,0,.22)'">🛍️</span>
                                    <div class="flex-1 min-w-0">
                                        <p x-text="product.name" class="text-sm font-semibold truncate"></p>
                                        <p x-show="product.price_label" x-text="product.price_label" class="text-xs opacity-75"></p>
                                    </div>
                                    <span class="flex-shrink-0 opacity-70 text-xs">→</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <p x-show="!activeLinks.length && !activeProducts.length" class="text-xs opacity-60 mt-8">Tambahkan link pertamamu di tab Content ✨</p>
        </div>
    </div>
    <p class="text-center text-xs text-ink-400 mt-3">Preview langsung berubah tanpa reload</p>
</div>