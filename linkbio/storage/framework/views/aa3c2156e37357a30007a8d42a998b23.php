
<div class="mx-auto w-[315px]">
    
    <div class="rounded-[3.2rem] p-3 bg-[#0d1527] ring-1 ring-slate-800 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.35)] relative overflow-hidden">
        
        
        <div class="rounded-[2.5rem] overflow-hidden relative h-[570px] flex flex-col bg-slate-900 transition-colors duration-300"
             :class="backgroundClass + ' ' + (cardEnabled ? '' : profile.theme_text)"
             :style="backgroundStyle">

            
            <div class="h-10 pt-2 px-6 flex items-center justify-between text-white/90 z-20 select-none flex-shrink-0">
                <span class="text-xs font-semibold tracking-tight">9:41</span>
                
                <div class="w-20 h-4 bg-black/90 rounded-full -mt-1 mx-auto flex items-center justify-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-800/80 mr-4"></span>
                </div>
                <div class="flex items-center gap-1.5 text-xs">
                    
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 18.23C2.89 16.5 2 14.36 2 12 2 6.48 6.48 2 12 2s10 4.48 10 10c0 2.36-.89 4.5-2.35 6.23l-.62-.62C20.26 16.07 21 14.12 21 12c0-4.97-4.03-9-9-9z"/><circle cx="12" cy="18" r="2"/></svg>
                    
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 4C7.31 4 3.07 5.9 0 8.98L12 21 24 8.98A16.88 16.88 0 0012 4zm0 3.5c3.34 0 6.39 1.25 8.7 3.32L12 19.34 3.3 10.82A13.38 13.38 0 0112 7.5z"/></svg>
                    
                    <div class="w-5 h-2.5 rounded-xs border border-white/80 p-0.5 flex items-center">
                        <div class="w-full h-full bg-white rounded-2xs"></div>
                    </div>
                </div>
            </div>

            
            <div class="flex-1 overflow-y-auto px-4 pb-8 text-center transition-all">

                
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

                
                <template x-if="profile.header_layout === 'classic' || !profile.header_layout">
                    <div class="pt-4">
                        <img :src="avatarSrc" alt="Avatar"
                             class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-white/80 shadow-md">
                        <p class="mt-3 font-extrabold text-base">
                            <span x-show="profile.display_name" x-text="profile.display_name"></span>
                            <span x-show="!profile.display_name" x-text="profile.username"></span>
                        </p>
                        <p class="text-xs opacity-70 mt-0.5">@<span x-text="profile.username"></span></p>
                        <p x-show="profile.bio" x-text="profile.bio" class="text-xs opacity-80 mt-2 px-2 leading-relaxed"></p>
                    </div>
                </template>

                
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

                
                <div class="mt-5 space-y-2.5" x-show="activeLinks.length || activeProducts.length">
                    <template x-for="link in activeLinks" :key="link.id">
                        <div class="flex items-center gap-3 w-full py-3 px-3.5 rounded-2xl bg-white text-[#14213d] shadow-sm font-bold text-sm transition-transform duration-150"
                             :style="profile.button_style === 'pill' ? 'border-radius:9999px;' : (profile.button_style === 'square' ? 'border-radius:0.5rem;' : '')"
                             :class="link.is_featured ? 'ring-2 ring-blue-500' : ''">
                            
                            
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 shadow-2xs overflow-hidden"
                                 :style="brandBoxStyle(link)">
                                <span x-html="linkIcon(link, 'w-4 h-4')"></span>
                            </div>

                            <span x-text="link.title" class="flex-1 min-w-0 text-left truncate"></span>

                            <span x-show="link.is_featured" class="text-xs flex-shrink-0">⭐</span>
                            <span class="text-slate-400 font-bold text-base flex-shrink-0">›</span>
                        </div>
                    </template>

                    
                    <template x-if="activeProducts.length">
                        <div class="pt-3">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] opacity-75 mb-2.5">SHOP</p>
                            <div class="space-y-2.5">
                                <template x-for="product in activeProducts" :key="product.id">
                                    <div class="flex items-center gap-3 w-full p-2.5 rounded-2xl bg-white text-[#14213d] shadow-sm text-left transition-transform duration-150">
                                        <img x-show="product.image_url" :src="product.image_url"
                                             class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                                        <span x-show="!product.image_url"
                                              class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0 text-base">🛍️</span>
                                        <div class="flex-1 min-w-0">
                                            <p x-text="product.name" class="text-xs font-bold text-[#14213d] truncate"></p>
                                            <p x-show="product.price_label" x-text="product.price_label" class="text-[11px] text-slate-500 mt-0.5"></p>
                                        </div>
                                        <span class="text-slate-400 font-bold text-base flex-shrink-0">›</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <p x-show="!activeLinks.length && !activeProducts.length" class="text-xs opacity-60 mt-8">Tambahkan link pertamamu di tab Content ✨</p>
            </div>
        </div>
    </div>

    
    <div class="flex items-center justify-center gap-2 text-xs text-slate-400 font-medium mt-4">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <span>Preview langsung berubah saat kamu mengedit</span>
    </div>
</div>
<?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/components/editor-phone-preview.blade.php ENDPATH**/ ?>