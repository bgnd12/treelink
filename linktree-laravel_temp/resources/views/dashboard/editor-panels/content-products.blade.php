<div class="bg-white rounded-2xl border border-ink-100 p-5 sm:p-6 shadow-card">

    <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-ink-900">Produk <span class="text-ink-400 font-semibold text-sm" x-text="'(' + productLinks.length + ')'"></span></h2>
        <button type="button" @click="openAddLink(true)"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-ink-900 text-white text-sm font-semibold hover:bg-ink-800 transition shadow-soft">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
            Add Product
        </button>
    </div>

    {{-- Add product form --}}
    <div x-show="showAddForm && addingProduct" x-cloak x-collapse class="mb-5 rounded-2xl border border-ink-200 bg-ink-50/60 p-4">
        <h3 class="text-sm font-bold text-ink-800 mb-3">Tambahkan Produk Baru</h3>
        <div class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1">Nama Produk</label>
                <input type="text" x-model="newLink.title" placeholder="Contoh: Kaos Premium"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 focus:border-ink-900 focus:ring-4 focus:ring-ink-100 outline-none transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1">URL Produk / Toko</label>
                <input type="text" x-model="newLink.url" placeholder="https://shopee.co.id/kamu"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-ink-200 focus:border-ink-900 focus:ring-4 focus:ring-ink-100 outline-none transition text-sm">
            </div>
            <div class="flex gap-2 pt-1">
                <button type="button" @click="submitCreate()" :disabled="loading"
                        class="flex-1 py-2.5 rounded-xl bg-ink-900 text-white text-sm font-semibold hover:bg-ink-800 transition disabled:opacity-50">
                    <span x-text="loading ? 'Menyimpan…' : '+ Tambah Produk'"></span>
                </button>
                <button type="button" @click="showAddForm = false"
                        class="px-5 py-2.5 rounded-xl border border-ink-200 text-sm font-semibold text-ink-600 hover:bg-ink-50 transition">Batal</button>
            </div>
        </div>
    </div>

    {{-- Product cards --}}
    <div class="space-y-3">
        <template x-for="(link, index) in productLinks" :key="link.id">
            <div class="flex items-center gap-3 p-3.5 rounded-2xl border border-ink-100 bg-white hover:border-ink-200 hover:shadow-card transition group"
                 :class="{ 'opacity-50': !link.is_active }">

                <span class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-extrabold shrink-0"
                      :style="'background:' + iconColor('shop')">🛍️</span>

                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-ink-900 truncate" x-text="link.title"></p>
                    <p class="text-xs text-ink-400 truncate" x-text="link.url"></p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" @click="toggleLink(link)"
                            class="relative w-11 h-6 rounded-full transition shrink-0"
                            :class="link.is_active ? 'bg-ink-900' : 'bg-ink-200'">
                        <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow transition-all"
                              :class="link.is_active ? 'left-[1.375rem]' : 'left-0.5'"></span>
                    </button>
                    <button type="button" @click="removeLink(link)"
                            class="w-9 h-9 rounded-xl text-ink-400 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center"
                            title="Hapus">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    </button>
                </div>
            </div>
        </template>

        <div x-show="!productLinks.length" x-cloak class="py-12 text-center">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-ink-100 flex items-center justify-center text-2xl mb-3">🛍️</div>
            <p class="text-ink-500 text-sm font-medium">Belum ada produk.</p>
            <p class="text-ink-400 text-xs mt-1">Klik "Add Product" untuk menautkan produk atau tokomu.</p>
        </div>
    </div>
</div>