

<?php $__env->startSection('title', 'Editor'); ?>
<?php $__env->startSection('page-title', 'Edit Profil'); ?>

<?php $__env->startSection('content'); ?>
<?php ($__isDarkTheme = ($editorData['profile']['theme_text'] ?? 'text-white') === 'text-white'); ?>
<div class="max-w-[1500px] mx-auto" x-data="editor(<?php echo \Illuminate\Support\Js::from($editorData)->toHtml() ?>)"
     @theme-mode-changed="themeModeChanged($event.detail.dark)">

    
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-2 text-sm text-ink-500" x-cloak x-show="savedAt">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Tersimpan otomatis · <span x-text="savedAt"></span>
        </div>
    </div>

    
    <div class="fixed top-6 left-1/2 -translate-x-1/2 z-50" x-cloak x-show="toastMsg" x-transition>
        <div class="px-5 py-3 rounded-2xl shadow-soft font-semibold text-sm"
             :class="toastType === 'err' ? 'bg-rose-600 text-white' : 'bg-ink-900 text-white'">
            <span x-text="toastMsg"></span>
        </div>
    </div>

    <div class="grid lg:grid-cols-[minmax(0,1fr)_380px] xl:grid-cols-[minmax(0,1fr)_410px] gap-8 items-start">

        
        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] overflow-hidden">

            
            <div class="grid grid-cols-4 border-b border-slate-100 bg-white">
                <?php $__currentLoopData = [
                    'content' => ['label' => 'Content', 'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>'],
                    'header' => ['label' => 'Header', 'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>'],
                    'design' => ['label' => 'Design', 'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>'],
                    'enhance' => ['label' => 'Enhance', 'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" @click="setTab('<?php echo e($key); ?>')"
                            class="flex items-center justify-center gap-2.5 px-4 py-4 text-sm font-bold transition border-b-2 -mb-px"
                            :class="activeTab === '<?php echo e($key); ?>' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-slate-400 hover:text-slate-700'">
                        <?php echo $tab['icon']; ?>

                        <span><?php echo e($tab['label']); ?></span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="p-6 sm:p-8">

                
                <section x-show="activeTab === 'content'" x-cloak class="space-y-8">

                    
                    <div>
                        <div class="flex items-center gap-2.5 mb-5">
                            <h2 class="font-extrabold text-[#14213d] text-lg">Links</h2>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-[#edf2fe] text-[#3b82f6]" x-text="links.length"></span>
                        </div>

                        
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs mb-5">
                            <form @submit.prevent="addLink()" class="grid sm:grid-cols-[1.5fr_1.5fr_1.2fr_auto] gap-3.5 items-end">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Link</label>
                                    <input type="text" x-model="linkForm.title" placeholder="Contoh: YouTube"
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">URL</label>
                                    <input type="text" x-model="linkForm.url" placeholder="https://..."
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe</label>
                                    
                                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                        <button type="button" @click="open = !open"
                                                class="w-full flex items-center justify-between gap-2 px-3.5 py-3 rounded-xl border border-slate-200 bg-white text-sm outline-none hover:border-slate-300 focus:border-blue-500 transition">
                                            <div class="flex items-center gap-2 truncate">
                                                <span class="flex-shrink-0 text-slate-600" x-html="iconSvgFor(linkForm.icon, 'w-4 h-4')"></span>
                                                <span class="text-slate-700 font-medium truncate" x-text="iconLabel(linkForm.icon)"></span>
                                            </div>
                                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <div x-show="open" x-transition class="absolute z-50 mt-1 w-56 max-h-64 overflow-y-auto bg-white rounded-2xl border border-slate-100 shadow-xl py-1" style="min-width: 13rem;">
                                            <template x-for="icon in icons" :key="icon">
                                                <button type="button"
                                                        @click="linkForm.icon = icon; open = false"
                                                        class="w-full flex items-center gap-3 px-3.5 py-2 text-sm hover:bg-slate-50 transition"
                                                        :class="linkForm.icon === icon ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-700'">
                                                    <span class="flex-shrink-0" x-html="iconSvgFor(icon, 'w-4 h-4')"></span>
                                                    <span x-text="iconLabel(icon)"></span>
                                                    <span x-show="linkForm.icon === icon" class="ml-auto">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    </span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="px-6 py-3 rounded-xl bg-[#c8ff4d] hover:bg-[#bcf040] text-[#14213d] text-sm font-extrabold transition shadow-xs whitespace-nowrap"
                                        :disabled="saving">
                                    + Tambah
                                </button>
                            </form>
                            <p x-show="linkForm.error" x-text="linkForm.error" class="mt-2 text-xs text-rose-600 font-semibold"></p>
                        </div>

                        
                        <ul x-ref="linksList" class="mt-4 space-y-3">
                            <template x-for="link in links" :key="link.id">
                                <li class="group flex items-center gap-4 p-4 rounded-2xl border border-slate-200/80 bg-white transition hover:border-slate-300 shadow-xs"
                                    :class="!link.is_active ? 'opacity-55' : ''">

                                    
                                    <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-500 text-lg select-none px-1">⠿</span>

                                    
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-2xs overflow-hidden"
                                         :style="brandBoxStyle(link)">
                                        <span x-html="linkIcon(link, 'w-5 h-5')"></span>
                                    </div>

                                    
                                    <div class="flex-1 min-w-0" x-show="editingLinkId !== link.id">
                                        <p class="font-bold text-sm text-[#14213d] truncate" x-text="link.title"></p>
                                        <p class="text-xs text-slate-400 truncate mt-0.5" x-text="link.url"></p>
                                    </div>

                                    
                                    <div class="flex-1 min-w-0" x-show="editingLinkId === link.id" x-cloak>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="text" x-model="editDraft.title" class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                                            <input type="text" x-model="editDraft.url" class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                                            <button @click="saveEditLink()" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold">Simpan</button>
                                            <button @click="cancelEdit()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</button>
                                        </div>
                                    </div>

                                    
                                    <div class="flex items-center gap-3.5 flex-shrink-0">
                                        <button @click="startEditLink(link)" class="text-slate-400 hover:text-blue-600 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        
                                        <button type="button" @click="toggleLink(link)"
                                                class="w-12 h-6.5 rounded-full transition-colors relative cursor-pointer focus:outline-none p-0.5 flex items-center"
                                                :class="link.is_active ? 'bg-[#22c55e]' : 'bg-slate-200'" title="Aktif/nonaktif">
                                            <span class="block w-5.5 h-5.5 rounded-full bg-white shadow-sm transition-transform duration-200 transform"
                                                  :class="link.is_active ? 'translate-x-5.5' : 'translate-x-0'"></span>
                                        </button>

                                        <button @click="deleteLink(link)" class="text-rose-400 hover:text-rose-600 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </li>
                            </template>
                            <li x-show="!links.length" class="text-center py-10 text-slate-400 text-sm">Belum ada link. Tambahkan link pertamamu di atas!</li>
                        </ul>
                        <p x-show="links.length" class="mt-2 text-xs text-slate-400">Seret <span class="font-semibold">⠿</span> untuk mengatur urutan.</p>
                    </div>

                    
                    <div class="pt-8 border-t border-slate-100">
                        <div class="flex items-center gap-2.5 mb-5">
                            <h2 class="font-extrabold text-[#14213d] text-lg">Shop</h2>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-[#edf2fe] text-[#3b82f6]" x-text="products.length"></span>
                        </div>

                        
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs mb-5">
                            <form @submit.prevent="addProduct()" class="space-y-4">
                                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama produk</label>
                                        <input type="text" x-model="productForm.name" placeholder="Contoh: Casing imut"
                                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">URL produk</label>
                                        <input type="text" x-model="productForm.url" placeholder="https://..."
                                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga (opsional)</label>
                                        <input type="number" step="0.01" min="0" x-model="productForm.price" placeholder="Rp 0"
                                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition placeholder:text-slate-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Gambar</label>
                                        <label class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl border border-dashed border-slate-300 bg-white cursor-pointer hover:border-blue-400 transition text-xs text-slate-500 h-[46px]">
                                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <div class="truncate flex-1">
                                                <p class="font-bold text-slate-700 truncate" x-show="!productForm.imagePreview">Pilih gambar</p>
                                                <p class="text-[10px] text-slate-400 truncate" x-show="!productForm.imagePreview">JPG, PNG (maks. 2MB)</p>
                                                <p class="font-bold text-emerald-600 truncate" x-show="productForm.imagePreview">Dipilih</p>
                                            </div>
                                            <input type="file" accept="image/*" class="hidden" @change="productImageSelected($event)">
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 rounded-xl bg-[#18263f] hover:bg-[#121e33] text-white text-sm font-bold transition shadow-xs flex items-center justify-center gap-2"
                                        :disabled="saving">
                                    + Tambahkan Produk
                                </button>
                            </form>
                            <p x-show="productForm.error" x-text="productForm.error" class="mt-2 text-xs text-rose-600 font-semibold"></p>
                        </div>

                        
                        <ul x-ref="productsList" class="mt-4 space-y-3">
                            <template x-for="product in products" :key="product.id">
                                <li class="flex items-center gap-4 p-4 rounded-2xl border border-slate-200/80 bg-white transition hover:border-slate-300 shadow-xs"
                                    :class="!product.is_active ? 'opacity-55' : ''">
                                    <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-500 text-lg select-none px-1">⠿</span>

                                    <div class="w-11 h-11 rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0 border border-slate-100">
                                        <img x-show="product.image_url" :src="product.image_url" class="w-full h-full object-cover">
                                        <span x-show="!product.image_url" class="text-lg">🛍️</span>
                                    </div>

                                    <div class="flex-1 min-w-0" x-show="editingProductId !== product.id">
                                        <p class="font-bold text-sm text-[#14213d] truncate" x-text="product.name"></p>
                                        <p class="text-xs text-slate-400 truncate mt-0.5">
                                            <span x-text="product.price_label || 'Rp 0'"></span>
                                        </p>
                                    </div>

                                    <div class="flex-1 min-w-0" x-show="editingProductId === product.id" x-cloak>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="text" x-model="editDraft.name" class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                                            <input type="text" x-model="editDraft.url" class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                                            <input type="number" step="0.01" x-model="editDraft.price" class="w-24 px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none" placeholder="Harga">
                                            <button @click="saveEditProduct()" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold">Simpan</button>
                                            <button @click="cancelEdit()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</button>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3.5 flex-shrink-0">
                                        <button @click="startEditProduct(product)" class="text-slate-400 hover:text-blue-600 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        
                                        <button type="button" @click="toggleProduct(product)"
                                                class="w-12 h-6.5 rounded-full transition-colors relative cursor-pointer focus:outline-none p-0.5 flex items-center"
                                                :class="product.is_active ? 'bg-[#22c55e]' : 'bg-slate-200'" title="Aktif/nonaktif">
                                            <span class="block w-5.5 h-5.5 rounded-full bg-white shadow-sm transition-transform duration-200 transform"
                                                  :class="product.is_active ? 'translate-x-5.5' : 'translate-x-0'"></span>
                                        </button>

                                        <button @click="deleteProduct(product)" class="text-rose-400 hover:text-rose-600 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </li>
                            </template>
                            <li x-show="!products.length" class="text-center py-8 text-slate-400 text-sm">Belum ada produk. Tambahkan produk pertamamu untuk fitur Shop.</li>
                        </ul>
                        <p x-show="products.length" class="mt-2 text-xs text-slate-400">Produk aktif tampil di halaman publik sebagai section <span class="font-semibold">Shop</span>.</p>
                    </div>
                </section>

                
                <section x-show="activeTab === 'header'" x-cloak class="space-y-6">

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Foto Profil</h3>
                        <p class="text-sm text-ink-500 mb-5">Foto ini ditampilkan di halaman publikmu.</p>
                        <div class="flex items-center gap-5">
                            <div class="w-20 h-20 rounded-full overflow-hidden bg-ink-50 border-4 border-ink-100 flex items-center justify-center">
                                <template x-if="avatarSrc">
                                    <img :src="avatarSrc" class="w-full h-full object-cover">
                                </template>
                                <span x-show="!avatarSrc" class="text-2xl">👤</span>
                            </div>
                            <div>
                                <label class="inline-block px-4 py-2.5 rounded-xl border border-ink-200 text-sm font-semibold cursor-pointer hover:border-brand-500 transition">
                                    Ganti Foto
                                    <input type="file" accept="image/*" class="hidden" @change="avatarSelected($event)">
                                </label>
                                <p class="text-xs text-ink-400 mt-2">JPG/PNG, maks 2MB. Langsung tersimpan.</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5 space-y-5">
                        <h3 class="font-bold text-ink-900">Identitas</h3>

                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5">Nama tampilan</label>
                            <input type="text" x-model="profile.display_name" @input="headerChanged()" placeholder="Nama yang tampil di halaman"
                                   class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5">Username</label>
                            <div class="flex rounded-xl border border-ink-200 overflow-hidden focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-100 transition">
                                <span class="px-4 flex items-center bg-ink-50 text-ink-400 text-sm border-r border-ink-200">/</span>
                                <input type="text" x-model="profile.username" @change="headerChanged()" class="w-full px-3 py-3 outline-none text-sm">
                            </div>
                            <p class="text-xs text-ink-400 mt-2" x-text="publicUrlLabel"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5">Bio</label>
                            <textarea x-model="profile.bio" @input="headerChanged()" rows="3" maxlength="280" placeholder="Ceritakan sedikit tentang dirimu..."
                                      class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition"></textarea>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Layout Header</h3>
                        <p class="text-sm text-ink-500 mb-5">Cara avatar dan nama ditampilkan.</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3">
                            <template x-for="(meta, key) in headerLayouts" :key="key">
                                <label class="cursor-pointer">
                                    <input type="radio" name="header_layout" :value="key" x-model="profile.header_layout" @change="headerChanged()" class="peer sr-only">
                                    <div class="p-3 rounded-2xl border-2 transition text-center"
                                         :class="profile.header_layout === key ? 'border-brand-600 bg-brand-50' : 'border-ink-100 hover:border-ink-300'">
                                        <div class="h-14 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center mb-2">
                                            <span class="w-7 h-7 rounded-full bg-white"></span>
                                        </div>
                                        <p class="text-xs font-bold text-ink-900" x-text="meta.label"></p>
                                        <p class="text-[10px] text-ink-400 mt-0.5" x-text="meta.desc"></p>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </section>

                
                <section x-show="activeTab === 'design'" x-cloak class="space-y-6">

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Mode Terang / Gelap</h3>
                        <p class="text-sm text-ink-500 mb-5">Ubah tema halaman publikmu dengan sekali klik. Pilih preset warna lengkap di bawah untuk kustomisasi.</p>
                        <div class="flex justify-center">
                            <?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => ['initialDark' => $__isDarkTheme]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['initial-dark' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($__isDarkTheme)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Tema</h3>
                        <p class="text-sm text-ink-500 mb-5">Preset warna latar halaman publikmu.</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                            <template x-for="(t, key) in themes" :key="key">
                                <label class="cursor-pointer">
                                    <input type="radio" name="theme" :value="key" x-model="profile.theme" @change="onThemeChange()" class="peer sr-only">
                                    <div class="h-20 rounded-2xl bg-gradient-to-br flex items-end p-3 ring-2 ring-transparent peer-checked:ring-brand-600 transition"
                                         :class="`${t.from} ${t.to} ` + (profile.theme === key ? 'ring-brand-600 ring-offset-2' : '')">
                                        <span class="text-xs font-bold" :class="t.text" x-text="t.label"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Background</h3>
                        <p class="text-sm text-ink-500 mb-5">Jenis latar belakang halaman kamu.</p>

                        
                        <div class="flex flex-wrap gap-2 mb-5">
                            <template x-for="(label, type) in backgroundTypes" :key="type">
                                <button type="button" @click="profile.background_type = type; onBackgroundTypeChange()"
                                        class="px-4 py-2 rounded-full text-xs font-bold transition"
                                        :class="profile.background_type === type ? 'bg-ink-900 text-white' : 'bg-ink-100 text-ink-600 hover:bg-ink-200'">
                                    <span x-text="label"></span>
                                </button>
                            </template>
                        </div>

                        
                        <div x-show="profile.background_type === 'gradient'" x-cloak>
                            <p class="text-xs font-semibold text-ink-500 mb-3">Pilih gradasi:</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(t, key) in themes" :key="key">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="bg_value" class="peer sr-only" :value="`${t.from} ${t.to}`"
                                               x-model="profile.background_value" @change="designChanged()">
                                        <div class="h-14 rounded-xl bg-gradient-to-br ring-2 ring-transparent peer-checked:ring-brand-600 transition"
                                             :class="`${t.from} ${t.to}`"></div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        
                        <div x-show="profile.background_type === 'solid'" x-cloak class="flex items-center gap-3">
                            <input type="color" x-model="profile.background_value" @change="designChanged()"
                                   class="w-14 h-14 rounded-xl border border-ink-200 cursor-pointer">
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Warna solid</p>
                                <p class="text-xs text-ink-400">Pilih satu warna latar belakang.</p>
                            </div>
                        </div>

                        
                        <div x-show="profile.background_type === 'pattern'" x-cloak>
                            <p class="text-xs font-semibold text-ink-500 mb-3">Pilih pola:</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(p, key) in patterns" :key="key">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="pattern" :value="key" class="peer sr-only"
                                               x-model="profile.background_value" @change="designChanged()">
                                        <div class="h-16 rounded-xl ring-2 ring-transparent peer-checked:ring-brand-600 transition"
                                             :class="`${p.swatch} ` + (profile.background_value === key ? 'ring-brand-600' : '')"
                                             :style="`background-color:${theme.pattern_bg || '#3f1c99'}`"></div>
                                        <p class="text-center text-[11px] text-ink-500 mt-1.5 font-semibold" x-text="p.label"></p>
                                    </label>
                                </template>
                            </div>
                        </div>

                        
                        <div x-show="profile.background_type === 'image'" x-cloak>
                            <div class="rounded-2xl border-2 border-dashed border-ink-200 p-6 text-center">
                                <template x-if="profile.background_image_url || backgroundPreview">
                                    <img :src="backgroundPreview || profile.background_image_url"
                                         class="mx-auto h-28 w-full max-w-xs object-cover rounded-xl mb-4">
                                </template>
                                <label class="inline-block px-5 py-3 rounded-xl bg-ink-900 text-white text-sm font-semibold cursor-pointer hover:bg-ink-700 transition">
                                    Upload Gambar Background
                                    <input type="file" accept="image/*" class="hidden" @change="backgroundSelected($event)">
                                </label>
                                <button type="button" x-show="profile.background_image_url" x-cloak @click="removeBackground()"
                                        class="ml-2 px-5 py-3 rounded-xl bg-rose-50 text-rose-600 text-sm font-semibold hover:bg-rose-100 transition">
                                    Hapus
                                </button>
                                <p class="text-xs text-ink-400 mt-3">JPG/PNG, maks GB. Gambar akan menjadi latar halamanmu.</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-ink-900 mb-1">Card</h3>
                                <p class="text-sm text-ink-500">Kartu yang membungkus isi halaman publikmu.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" x-model="profile.card_enabled" @change="designChanged()" class="sr-only peer">
                                <span class="w-11 h-6 bg-ink-200 rounded-full peer-checked:bg-brand-600 transition-colors"></span>
                                <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></span>
                            </label>
                        </div>

                        <div class="mt-5 space-y-5" x-show="cardEnabled" x-cloak :class="cardEnabled ? '' : 'opacity-50 pointer-events-none'">
                            <div>
                                <label class="block text-sm font-semibold text-ink-800 mb-2">Gaya</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <template x-for="(meta, key) in cardStyles" :key="key">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="card_style" :value="key" class="peer sr-only"
                                                   x-model="profile.card_style" @change="designChanged()">
                                            <div class="px-3 py-2.5 rounded-xl bg-ink-100 text-ink-700 text-xs font-bold text-center ring-2 ring-transparent peer-checked:ring-brand-600 transition"
                                                 x-text="meta.label"></div>
                                            <p class="text-center text-[10px] text-ink-400 mt-1.5 leading-tight" x-text="meta.desc"></p>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-ink-800 mb-2">Warna card</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" x-model="profile.card_color" @change="designChanged()"
                                           class="w-12 h-12 rounded-xl border border-ink-200 cursor-pointer">
                                    <span class="text-xs text-ink-400">Kosongkan = warna otomatis sesuai tema.</span>
                                    <button type="button" @click="profile.card_color = null; designChanged()"
                                            class="ml-auto px-3 py-2 rounded-lg bg-ink-100 text-ink-600 text-xs font-bold hover:bg-ink-200 transition">Reset</button>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-3 gap-5">
                                <div x-show="cardStyleName !== 'outline'">
                                    <label class="block text-sm font-semibold text-ink-800 mb-2">Transparansi · <span x-text="cardOpacity"></span>%</label>
                                    <input type="range" min="0" max="100" x-model.number="profile.card_opacity" @input="designChanged()" class="w-full accent-brand-600">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-ink-800 mb-2">Radius · <span x-text="profile.card_radius ?? 32"></span>px</label>
                                    <input type="range" min="0" max="64" x-model.number="profile.card_radius" @input="designChanged()" class="w-full accent-brand-600">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-ink-800 mb-2">Border · <span x-text="profile.card_border_width ?? 0"></span>px</label>
                                    <input type="range" min="0" max="8" x-model.number="profile.card_border_width" @input="designChanged()" class="w-full accent-brand-600">
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="profile.card_shadow" @change="designChanged()"
                                           class="w-5 h-5 rounded accent-brand-600">
                                    <span class="text-sm font-semibold text-ink-800">Bayangan card</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5 space-y-5">
                        <div>
                            <h3 class="font-bold text-ink-900 mb-1">Tombol Link</h3>
                            <p class="text-sm text-ink-500 mb-4">Gaya &amp; warna tombol link kamu.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-2">Gaya</label>
                            <div class="grid grid-cols-4 gap-3">
                                <template x-for="style in buttonStyles" :key="style">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="button_style" :value="style" class="peer sr-only"
                                               x-model="profile.button_style" @change="designChanged()">
                                        <div class="h-12 flex items-center justify-center bg-ink-100 text-ink-700 text-xs font-bold ring-2 ring-transparent peer-checked:ring-brand-600 transition"
                                             :class="style === 'pill' ? 'rounded-full' : style === 'square' ? 'rounded-md border-2 border-ink-400' : 'rounded-xl'">
                                            <span x-text="style[0].toUpperCase() + style.slice(1)"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-2">Warna tombol</label>
                            <div class="flex items-center gap-3">
                                <input type="color" x-model="profile.button_color" @change="designChanged()"
                                       class="w-12 h-12 rounded-xl border border-ink-200 cursor-pointer">
                                <span class="text-xs text-ink-400">Kosongkan = warna otomatis sesuai tema.</span>
                                <button type="button" @click="profile.button_color = null; designChanged()"
                                        class="ml-auto px-3 py-2 rounded-lg bg-ink-100 text-ink-600 text-xs font-bold hover:bg-ink-200 transition">Reset</button>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-ink-800 mb-2">Radius · <span x-text="profile.button_radius ?? (profile.button_style === 'pill' ? 999 : profile.button_style === 'square' ? 6 : 14)"></span>px</label>
                                <input type="range" min="0" max="32" x-model.number="profile.button_radius" @input="designChanged()" class="w-full accent-brand-600">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-ink-800 mb-2">Border · <span x-text="profile.button_border_width || 0"></span>px</label>
                                <input type="range" min="0" max="8" x-model.number="profile.button_border_width" @input="designChanged()" class="w-full accent-brand-600">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="profile.button_shadow" @change="designChanged()"
                                           class="w-5 h-5 rounded accent-brand-600">
                                    <span class="text-sm font-semibold text-ink-800">Bayangan tombol</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Font</h3>
                        <p class="text-sm text-ink-500 mb-5">Tipografi halaman publikmu.</p>
                        <div class="grid grid-cols-3 gap-3">
                            <template x-for="(label, key) in fonts" :key="key">
                                <label class="cursor-pointer">
                                    <input type="radio" name="font" :value="key" class="peer sr-only"
                                           x-model="profile.font" @change="designChanged()">
                                    <div class="h-14 flex items-center justify-center rounded-xl bg-ink-100 text-ink-700 text-sm ring-2 ring-transparent peer-checked:ring-brand-600 transition"
                                         :style="`font-family:${key === 'serif' ? \"'Playfair Display',serif\" : key === 'mono' ? \"'JetBrains Mono',monospace\" : \"'Plus Jakarta Sans',sans-serif\"}`">
                                        <span x-text="label"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </section>

                
                <section x-show="activeTab === 'enhance'" x-cloak class="space-y-6">

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Ikon Sosial Media</h3>
                        <p class="text-sm text-ink-500 mb-5">Ikon akan tampil di bawah header halaman publikmu.</p>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <template x-for="s in socials" :key="s.key">
                                <div>
                                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">
                                        <span class="uppercase text-[10px] font-bold text-ink-400 mr-1.5" x-text="s.label.slice(0,1)"></span>
                                        <span x-text="s.label"></span>
                                    </label>
                                    <input type="text" x-model="s.url" @input="enhanceChanged()" placeholder="https"
                                           class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition">
                                </div>
                            </template>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Link Unggulan</h3>
                        <p class="text-sm text-ink-500 mb-4">Sorot satu link agar lebih menonjol di halamanmu.</p>
                        <select x-model="featuredLinkId" @change="enhanceChanged()"
                                class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500">
                            <option :value="null">Tidak ada link unggulan</option>
                            <template x-for="link in links" :key="link.id">
                                <option :value="link.id" x-text="link.title"></option>
                            </template>
                        </select>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Animasi</h3>
                        <p class="text-sm text-ink-500 mb-4">Efek gerakan halus pada halaman publik.</p>
                        <label class="flex items-center justify-between rounded-2xl bg-ink-50 p-4 cursor-pointer">
                            <div>
                                <p class="text-sm font-bold text-ink-800">Aktifkan animasi</p>
                                <p class="text-xs text-ink-400">Fade-in dan transisi halus.</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" x-model="profile.animation_enabled" @change="enhanceChanged()" class="peer sr-only">
                                <div class="w-12 h-7 rounded-full transition peer-checked:bg-brand-600 bg-ink-200 relative">
                                    <span class="absolute top-0.5 left-0.5 w-6 h-6 rounded-full bg-white shadow transition peer-checked:left-[22px]"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5 space-y-5">
                        <div>
                            <h3 class="font-bold text-ink-900 mb-1">LinkID</h3>
                            <p class="text-sm text-ink-500 mb-4">Aktifkan kolaborasi agar LinkID muncul di public profile.</p>
                        </div>

                        <label class="flex items-center justify-between rounded-2xl bg-ink-50 p-4 cursor-pointer">
                            <div>
                                <p class="text-sm font-bold text-ink-800">Tampilkan LinkID di public profile</p>
                                <p class="text-xs text-ink-400">Pengunjung bisa mengirim request kolaborasi.</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" x-model="profile.is_linkid_active" @change="enhanceChanged()" class="peer sr-only">
                                <div class="w-12 h-7 rounded-full transition peer-checked:bg-brand-600 bg-ink-200 relative">
                                    <span class="absolute top-0.5 left-0.5 w-6 h-6 rounded-full bg-white shadow transition peer-checked:left-[22px]"></span>
                                </div>
                            </div>
                        </label>

                        <div x-show="profile.is_linkid_active" x-cloak>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5">Jenis kolaborasi</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="option in ['Content Creator','Product','Brand','Event','Creator']" :key="option">
                                    <label class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-3 py-2 text-xs font-semibold text-ink-700">
                                        <input type="checkbox" :value="option" x-model="profile.linkid_types" @change="enhanceChanged()" class="rounded border-ink-300 text-brand-600">
                                        <span x-text="option"></span>
                                    </label>
                                </template>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-ink-800 mb-1.5">Deskripsi LinkID</label>
                                <textarea x-model="profile.linkid_description" @input="enhanceChanged()" rows="3" maxlength="300" placeholder="Contoh: Saya terbuka untuk campaign, produk, dan sesi kolaborasi kreatif."
                                          class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition"></textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5 space-y-5">
                        <div>
                            <h3 class="font-bold text-ink-900 mb-1">SEO</h3>
                            <p class="text-sm text-ink-500 mb-4">Kontrol cara halamanmu muncul saat dibagikan.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5">Judul SEO</label>
                            <input type="text" x-model="profile.seo_title" @input="enhanceChanged()" placeholder="Contoh: Akbar Maulana — Semua Link Saya"
                                   class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition">
                            <p class="text-xs text-ink-400 mt-1.5" x-text="profile.seo_title ? profile.seo_title.slice(0, 60) : 'Judul berisi maksimal 60 karakter.'"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5">Deskripsi SEO</label>
                            <textarea x-model="profile.seo_description" @input="enhanceChanged()" rows="3" maxlength="300" placeholder="Deskripsi singkat halamanmu"
                                      class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition"></textarea>
                        </div>
                    </div>

                    
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Bagikan Halaman</h3>
                        <p class="text-sm text-ink-500 mb-4">Salin URL publik profil kamu.</p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="text" readonly :value="publicUrlLabel" class="flex-1 px-4 py-3 rounded-xl border border-ink-200 bg-ink-50 text-sm text-ink-600">
                            <button type="button" @click="navigator.clipboard.writeText(publicUrlLabel).then(() => toast('URL disalin!'))"
                                    class="px-5 py-3 rounded-xl bg-ink-900 text-white text-sm font-semibold hover:bg-ink-700 transition">Salin</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        
        <div class="lg:sticky lg:top-24">
            <div class="rounded-3xl bg-white border border-slate-100 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6">
                <div class="flex items-center justify-between mb-5">
                    <p class="text-base font-extrabold text-[#14213d]">Preview</p>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-[#edf8dd] text-[#22c55e] flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span> Live
                    </span>
                </div>
                <?php echo $__env->make('components.editor-phone-preview', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    window.linkStoreUrl = <?php echo json_encode(route('dashboard.links.store'), 15, 512) ?>;
    window.linkUpdateUrl = <?php echo json_encode(route('dashboard.links.update', '__ID__'), 512) ?>;
    window.linkToggleUrl = <?php echo json_encode(route('dashboard.links.toggle', '__ID__'), 512) ?>;
    window.linkDestroyUrl = <?php echo json_encode(route('dashboard.links.destroy', '__ID__'), 512) ?>;
    window.linkReorderUrl = <?php echo json_encode(route('dashboard.links.reorder'), 15, 512) ?>;
    window.productStoreUrl = <?php echo json_encode(route('dashboard.products.store'), 15, 512) ?>;
    window.productUpdateUrl = <?php echo json_encode(route('dashboard.products.update', '__ID__'), 512) ?>;
    window.productToggleUrl = <?php echo json_encode(route('dashboard.products.toggle', '__ID__'), 512) ?>;
    window.productDestroyUrl = <?php echo json_encode(route('dashboard.products.destroy', '__ID__'), 512) ?>;
    window.productReorderUrl = <?php echo json_encode(route('dashboard.products.reorder'), 15, 512) ?>;
    window.headerUrl = <?php echo json_encode(route('dashboard.header'), 15, 512) ?>;
    window.designUrl = <?php echo json_encode(route('dashboard.design'), 15, 512) ?>;
    window.enhanceUrl = <?php echo json_encode(route('dashboard.enhance'), 15, 512) ?>;
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/dashboard/editor.blade.php ENDPATH**/ ?>