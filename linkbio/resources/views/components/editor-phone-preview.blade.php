{{-- Reactive phone preview — driven entirely by the editor() Alpine component state. --}}
<div class="mx-auto w-[320px]">
    {{-- Sleek Smartphone Mockup --}}
    <div class="rounded-[3.2rem] p-3 bg-[#0d1527] ring-1 ring-slate-800 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.35)] relative overflow-hidden">
        
        {{-- Inner Screen --}}
        <div class="rounded-[2.5rem] overflow-hidden relative h-[590px] flex flex-col bg-slate-900 transition-colors duration-300"
             :class="backgroundClass + ' ' + (cardEnabled ? '' : profile.theme_text)"
             :style="profile.background_image_url ? backgroundStyle : 'background: linear-gradient(180deg, rgba(15,23,42,0.7) 0%, rgba(15,23,42,0.9) 100%), url(https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&auto=format&fit=crop&q=80) center/cover no-repeat;'">

            {{-- iOS Status Bar with Traditional iPhone Notch --}}
            <div class="h-10 pt-2 px-6 flex items-center justify-between text-white/90 z-20 select-none flex-shrink-0 relative">
                <span class="text-xs font-semibold tracking-tight">9:41</span>
                
                {{-- iPhone Notch --}}
                <div class="w-28 h-5 bg-black rounded-b-2xl absolute left-1/2 -translate-x-1/2 top-0 flex items-center justify-center">
                    <div class="w-10 h-1 bg-neutral-800 rounded-full mr-2"></div>
                    <div class="w-2.5 h-2.5 bg-neutral-900 rounded-full border border-neutral-800"></div>
                </div>

                <div class="flex items-center gap-1.5 text-xs">
                    {{-- Signal bars --}}
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 18.23C2.89 16.5 2 14.36 2 12 2 6.48 6.48 2 12 2s10 4.48 10 10c0 2.36-.89 4.5-2.35 6.23l-.62-.62C20.26 16.07 21 14.12 21 12c0-4.97-4.03-9-9-9z"/><circle cx="12" cy="18" r="2"/></svg>
                    {{-- Wifi --}}
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 4C7.31 4 3.07 5.9 0 8.98L12 21 24 8.98A16.88 16.88 0 0012 4zm0 3.5c3.34 0 6.39 1.25 8.7 3.32L12 19.34 3.3 10.82A13.38 13.38 0 0112 7.5z"/></svg>
                    {{-- Battery --}}
                    <div class="w-5 h-2.5 rounded-xs border border-white/80 p-0.5 flex items-center">
                        <div class="w-full h-full bg-white rounded-2xs"></div>
                    </div>
                </div>
            </div>

            {{-- Scrollable Profile Content --}}
            <div class="flex-1 overflow-y-auto px-4 pb-20 text-center transition-all">

                {{-- BANNER header layout --}}
                <template x-if="profile.header_layout === 'banner'">
                    <div>
                        <div class="h-16 w-[calc(100%+2rem)] -ml-4 rounded-b-3xl"
                             :class="isLight ? 'bg-black/10' : 'bg-white/15 backdrop-blur'"></div>
                        <img :src="avatarSrc" alt="Avatar"
                             class="w-22 h-22 rounded-full object-cover mx-auto -mt-10 border-4"
                             :class="isLight ? 'border-gray-200 shadow' : 'border-white/80 shadow-xl'">
                        <p class="mt-3 font-extrabold text-base">
                            <span x-show="profile.display_name" x-text="profile.display_name"></span>
                            <span x-show="!profile.display_name" x-text="profile.username"></span>
                        </p>
                        <p class="text-xs opacity-70">@<span x-text="profile.username"></span></p>
                        <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                    </div>
                </template>

                {{-- Cutout header layout --}}
                <template x-if="profile.header_layout === 'cutout'">
                    <div class="pt-4">
                        <div class="relative w-22 h-22 mx-auto">
                            <div class="absolute inset-2 rotate-45 rounded-3xl" :class="isLight ? 'bg-white/80' : 'bg-white/20'"></div>
                            <img :src="avatarSrc" alt="Avatar"
                                 class="absolute inset-0 w-full h-full rounded-full object-cover border-4"
                                 :class="isLight ? 'border-white' : 'border-white/80'">
                        </div>
                        <p class="mt-3 font-extrabold text-base">
                            <span x-show="profile.display_name" x-text="profile.display_name"></span>
                            <span x-show="!profile.display_name" x-text="profile.username"></span>
                        </p>
                        <p class="text-xs opacity-70">@<span x-text="profile.username"></span></p>
                        <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                    </div>
                </template>

                {{-- Shape header layout --}}
                <template x-if="profile.header_layout === 'shape'">
                    <div class="pt-4">
                        <div class="mx-auto w-22 h-22 rounded-[36%] p-1.5" :class="isLight ? 'bg-white shadow' : 'bg-white/25'">
                            <img :src="avatarSrc" alt="Avatar" class="w-full h-full rounded-[34%] object-cover">
                        </div>
                        <p class="mt-3 font-extrabold text-base">
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
                        <div class="mx-auto w-24 h-24 rounded-[1.75rem] p-1" :class="isLight ? 'bg-white' : 'bg-white/90'">
                            <img :src="avatarSrc" alt="Avatar" class="w-full h-full rounded-[1.5rem] object-cover">
                        </div>
                        <p class="mt-3 font-extrabold text-base">
                            <span x-show="profile.display_name" x-text="profile.display_name"></span>
                            <span x-show="!profile.display_name" x-text="profile.username"></span>
                        </p>
                        <p class="text-xs opacity-70 mt-0.5">@<span x-text="profile.username"></span></p>
                        <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                    </div>
                </template>

                {{-- Classic header layout (default) --}}
                <template x-if="profile.header_layout === 'classic' || !profile.header_layout">
                    <div class="pt-4">
                        <img :src="avatarSrc" alt="Avatar"
                             class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-white/90 shadow-lg">
                        <p class="mt-3 font-extrabold text-base text-white">
                            <span x-show="profile.display_name" x-text="profile.display_name"></span>
                            <span x-show="!profile.display_name" x-text="profile.username"></span>
                        </p>
                        <p class="text-xs text-white/70 mt-0.5">@<span x-text="profile.username"></span></p>
                        <p x-show="profile.bio" x-text="profile.bio" class="text-xs text-white/80 mt-2 px-2 leading-relaxed"></p>
                    </div>
                </template>

                {{-- Social icons --}}
                <template x-if="socials.length && socials.some(s => s.url)">
                    <div class="flex justify-center flex-wrap gap-2 mt-3">
                        <template x-for="s in socials.filter(s => s.url && s.url.trim())" :key="s.key">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center"
                                  :style="isLight ? 'background:rgba(0,0,0,.12);color:#111827' : 'background:rgba(255,255,255,.22);color:#fff'">
                                <span x-html="socialIcon(s.key, 'w-3.5 h-3.5')"></span>
                            </span>
                        </template>
                    </div>
                </template>

                {{-- Links --}}
                <div class="mt-5 space-y-2.5">
                    <template x-for="link in activeLinks" :key="link.id">
                        <div class="flex items-center gap-3 w-full py-3 px-3.5 rounded-2xl bg-white text-[#14213d] shadow-sm font-bold text-sm transition-transform duration-150"
                             :style="profile.button_style === 'pill' ? 'border-radius:9999px;' : (profile.button_style === 'square' ? 'border-radius:0.5rem;' : '')"
                             :class="link.is_featured ? 'ring-2 ring-blue-500' : ''">
                            
                            {{-- Brand logo on left --}}
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 shadow-2xs overflow-hidden"
                                 :style="brandBoxStyle(link)">
                                <span x-html="linkIcon(link, 'w-4 h-4')"></span>
                            </div>

                            <span x-text="link.title" class="flex-1 min-w-0 text-left truncate"></span>

                            <span x-show="link.is_featured" class="text-xs flex-shrink-0">⭐</span>
                            <span class="text-slate-400 font-bold text-base flex-shrink-0">›</span>
                        </div>
                    </template>

                    {{-- Fallback default links if none yet --}}
                    <template x-if="!activeLinks.length">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3 w-full py-3 px-3.5 rounded-2xl bg-white text-[#14213d] shadow-sm font-bold text-sm">
                                <div class="w-7 h-7 rounded-lg bg-[#ff0000] text-white flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </div>
                                <span class="flex-1 text-left truncate">YouTube</span>
                                <span class="text-slate-400 font-bold text-base flex-shrink-0">›</span>
                            </div>
                            <div class="flex items-center gap-3 w-full py-3 px-3.5 rounded-2xl bg-white text-[#14213d] shadow-sm font-bold text-sm">
                                <div class="w-7 h-7 rounded-lg bg-black text-white flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                </div>
                                <span class="flex-1 text-left truncate">Tiktok</span>
                                <span class="text-slate-400 font-bold text-base flex-shrink-0">›</span>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Products / Shop Card Section (White Card Container with 2-Column Grid) --}}
                <div class="mt-4 rounded-3xl bg-white p-3.5 text-left shadow-sm">
                    {{-- Shop Card Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-1.5 font-bold text-xs text-[#14213d]">
                            <svg class="w-4 h-4 text-[#14213d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Shop</span>
                        </div>
                        <a href="#" class="text-[11px] font-semibold text-slate-400 hover:text-slate-600 flex items-center gap-0.5">
                            Lihat Semua <span class="text-xs">&rsaquo;</span>
                        </a>
                    </div>

                    {{-- Real Active Products Grid (if any) --}}
                    <template x-if="activeProducts.length">
                        <div class="grid grid-cols-2 gap-2.5">
                            <template x-for="product in activeProducts" :key="product.id">
                                <div class="group flex flex-col cursor-pointer">
                                    <div class="aspect-square w-full rounded-2xl bg-[#f3f4f6] overflow-hidden flex items-center justify-center relative">
                                        <img x-show="product.image_url" :src="product.image_url" class="w-full h-full object-cover">
                                        <span x-show="!product.image_url" class="text-2xl">🛍️</span>
                                    </div>
                                    <p x-text="product.name" class="font-bold text-xs text-[#14213d] truncate mt-1.5"></p>
                                    <p x-show="product.price_label" x-text="product.price_label" class="text-[10px] text-slate-500 font-medium"></p>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Showcase Sample Products (matching screenshot when activeProducts is empty) --}}
                    <template x-if="!activeProducts.length">
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="flex flex-col cursor-pointer">
                                <div class="aspect-square w-full rounded-2xl bg-[#f3f4f6] overflow-hidden flex items-center justify-center p-2">
                                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=300&auto=format&fit=crop&q=80" alt="Hoodie Oversize" class="w-full h-full object-contain">
                                </div>
                                <p class="font-bold text-xs text-[#14213d] truncate mt-1.5">Hoodie Oversize</p>
                                <p class="text-[10px] text-slate-500 font-medium">Rp 199.000</p>
                            </div>

                            <div class="flex flex-col cursor-pointer">
                                <div class="aspect-square w-full rounded-2xl bg-[#f3f4f6] overflow-hidden flex items-center justify-center p-2">
                                    <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=300&auto=format&fit=crop&q=80" alt="Sneakers Urban" class="w-full h-full object-contain">
                                </div>
                                <p class="font-bold text-xs text-[#14213d] truncate mt-1.5">Sneakers Urban</p>
                                <p class="text-[10px] text-slate-500 font-medium">Rp 279.000</p>
                            </div>

                            <div class="flex flex-col cursor-pointer">
                                <div class="aspect-square w-full rounded-2xl bg-[#f3f4f6] overflow-hidden flex items-center justify-center p-2">
                                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?w=300&auto=format&fit=crop&q=80" alt="Tote Bag" class="w-full h-full object-contain">
                                </div>
                                <p class="font-bold text-xs text-[#14213d] truncate mt-1.5">Tote Bag</p>
                                <p class="text-[10px] text-slate-500 font-medium">Rp 89.000</p>
                            </div>

                            <div class="flex flex-col cursor-pointer">
                                <div class="aspect-square w-full rounded-2xl bg-[#f3f4f6] overflow-hidden flex items-center justify-center p-2">
                                    <img src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=300&auto=format&fit=crop&q=80" alt="Topi Baseball" class="w-full h-full object-contain">
                                </div>
                                <p class="font-bold text-xs text-[#14213d] truncate mt-1.5">Topi Baseball</p>
                                <p class="text-[10px] text-slate-500 font-medium">Rp 79.000</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Bottom Tab Bar (Home, Link, Shop) --}}
            <div class="h-14 bg-white/95 backdrop-blur-md border-t border-slate-100 flex items-center justify-around px-4 z-20 flex-shrink-0">
                <button type="button" class="flex flex-col items-center justify-center gap-0.5 text-blue-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span class="text-[10px] font-bold">Home</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span class="text-[10px] font-medium">Link</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="text-[10px] font-medium">Shop</span>
                </button>
            </div>

            {{-- Floating Share Button --}}
            <button type="button" @click="navigator.clipboard.writeText(publicUrlLabel).then(() => toast('URL disalin!'))"
                    class="absolute right-4 bottom-5 w-14 h-14 rounded-full bg-slate-500/80 backdrop-blur-md text-white flex items-center justify-center shadow-2xl border border-white/30 hover:bg-slate-600/90 transition z-30"
                    title="Bagikan Profil">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Footer note --}}
    <div class="flex items-center justify-center gap-2 text-xs text-slate-400 font-medium mt-4">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <span>Preview langsung berubah saat kamu mengedit</span>
    </div>
</div>
