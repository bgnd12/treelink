@extends('layouts.dashboard')

@section('title', 'Editor')
@section('page-title', 'Edit Profil')

@section('content')
<div class="max-w-[1500px] mx-auto" x-data="editor(@js($editorData))">

    {{-- Save indicator --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-2 text-sm text-ink-500" x-cloak x-show="savedAt">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Tersimpan otomatis · <span x-text="savedAt"></span>
        </div>
        <a href="{{ $user->publicUrl() }}" target="_blank"
           class="ml-auto px-4 py-2 rounded-full bg-ink-900 text-white text-xs font-semibold hover:bg-ink-700 transition">
            ↗ Buka halaman publik
        </a>
    </div>

    {{-- Toast --}}
    <div class="fixed top-6 left-1/2 -translate-x-1/2 z-50" x-cloak x-show="toastMsg" x-transition>
        <div class="px-5 py-3 rounded-2xl shadow-soft font-semibold text-sm"
             :class="toastType === 'err' ? 'bg-rose-600 text-white' : 'bg-ink-900 text-white'">
            <span x-text="toastMsg"></span>
        </div>
    </div>

    <div class="grid lg:grid-cols-[minmax(0,1fr)_360px] xl:grid-cols-[minmax(0,1fr)_400px] gap-8 items-start">

        {{-- ================= LEFT: EDITOR PANEL ================= --}}
        <div class="bg-white rounded-3xl border border-ink-100 shadow-card overflow-hidden">

            {{-- Tabs --}}
            <div class="grid grid-cols-4 border-b border-ink-100 bg-ink-50/50">
                @foreach ([
                    'content' => ['label' => 'Content', 'icon' => '✦'],
                    'header' => ['label' => 'Header', 'icon' => '◉'],
                    'design' => ['label' => 'Design', 'icon' => '◐'],
                    'enhance' => ['label' => 'Enhance', 'icon' => '⬡'],
                ] as $key => $tab)
                    <button type="button" @click="setTab('{{ $key }}')"
                            class="flex items-center justify-center gap-2 px-3 py-4 text-sm font-bold transition border-b-2 -mb-px"
                            :class="activeTab === '{{ $key }}' ? 'border-brand-600 text-brand-700 bg-white' : 'border-transparent text-ink-400 hover:text-ink-700'">
                        <span class="text-base">{{ $tab['icon'] }}</span>
                        <span class="hidden sm:inline">{{ $tab['label'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="p-5 sm:p-7">

                {{-- ============ TAB: CONTENT ============ --}}
                <section x-show="activeTab === 'content'" x-cloak class="space-y-8">

                    {{-- LINKS --}}
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="font-extrabold text-ink-900 text-lg">Links</h2>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-brand-50 text-brand-700" x-text="links.length"></span>
                        </div>

                        {{-- Add link --}}
                        <div class="rounded-2xl border border-ink-100 bg-ink-50/40 p-4">
                            <form @submit.prevent="addLink()" class="grid sm:grid-cols-[2fr_2fr_1fr_auto] gap-3">
                                <input type="text" x-model="linkForm.title" placeholder="Judul (cth: Instagram Saya)"
                                       class="px-4 py-3 rounded-xl border border-ink-200 bg-white text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition">
                                <input type="text" x-model="linkForm.url" placeholder="https://instagram.com/kamu"
                                       class="px-4 py-3 rounded-xl border border-ink-200 bg-white text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition">
                                <select x-model="linkForm.icon"
                                        class="px-4 py-3 rounded-xl border border-ink-200 bg-white text-sm outline-none focus:border-brand-500">
                                    <template x-for="icon in icons" :key="icon">
                                        <option :value="icon" x-text="icon"></option>
                                    </template>
                                </select>
                                <button type="submit" class="px-5 py-3 rounded-xl bg-brand-600 text-white text-sm font-bold hover:bg-brand-700 transition shadow-soft"
                                        :disabled="saving">
                                    + Tambah
                                </button>
                            </form>
                            <p x-show="linkForm.error" x-text="linkForm.error" class="mt-2 text-xs text-rose-600 font-semibold"></p>
                        </div>

                        {{-- Links list (sortable) --}}
                        <ul x-ref="linksList" class="mt-4 space-y-2.5">
                            <template x-for="link in links" :key="link.id">
                                <li class="group flex items-center gap-3 p-3.5 rounded-2xl border border-ink-100 bg-white transition"
                                    :class="!link.is_active ? 'opacity-55' : ''">

                                    {{-- Drag handle --}}
                                    <span class="drag-handle cursor-grab active:cursor-grabbing text-ink-300 text-lg select-none">⠿</span>

                                    {{-- Icon letter --}}
                                    <span class="w-9 h-9 rounded-xl bg-ink-50 flex items-center justify-center text-xs uppercase font-bold text-ink-600 flex-shrink-0">
                                        <span x-text="(link.icon || 'l').slice(0,1)"></span>
                                    </span>

                                    {{-- View mode --}}
                                    <div class="flex-1 min-w-0" x-show="editingLinkId !== link.id">
                                        <p class="font-semibold text-sm text-ink-900 truncate" x-text="link.title"></p>
                                        <p class="text-xs text-ink-400 truncate" x-text="link.url"></p>
                                    </div>

                                    {{-- Edit mode --}}
                                    <div class="flex-1 min-w-0" x-show="editingLinkId === link.id" x-cloak>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="text" x-model="editDraft.title" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                            <input type="text" x-model="editDraft.url" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                            <select x-model="editDraft.icon" class="px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none">
                                                <template x-for="icon in icons" :key="icon">
                                                    <option :value="icon" x-text="icon"></option>
                                                </template>
                                            </select>
                                            <button @click="saveEditLink()" class="px-4 py-2 rounded-lg bg-brand-600 text-white text-xs font-bold">Simpan</button>
                                            <button @click="cancelEdit()" class="px-4 py-2 rounded-lg bg-ink-100 text-ink-600 text-xs font-bold">Batal</button>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button @click="startEditLink(link)" class="w-9 h-9 rounded-xl text-ink-400 hover:text-brand-600 hover:bg-brand-50 transition flex items-center justify-center" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button @click="toggleLink(link)" class="w-9 h-9 rounded-xl flex items-center justify-center transition" :class="link.is_active ? 'text-emerald-600 hover:bg-emerald-50' : 'text-ink-300 hover:bg-ink-50'" title="Aktif/nonaktif">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"></path></svg>
                                        </button>
                                        <button @click="deleteLink(link)" class="w-9 h-9 rounded-xl text-ink-300 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </li>
                            </template>
                            <li x-show="!links.length" class="text-center py-10 text-ink-400 text-sm">Belum ada link. Tambahkan link pertamamu di atas!</li>
                        </ul>
                        <p x-show="links.length" class="mt-2 text-xs text-ink-400">Seret <span class="font-semibold">⠿</span> untuk mengatur urutan.</p>
                    </div>

                    {{-- SHOP --}}
                    <div class="pt-6 border-t border-ink-100">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="font-extrabold text-ink-900 text-lg">Shop</h2>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700" x-text="products.length"></span>
                        </div>

                        {{-- Add product --}}
                        <div class="rounded-2xl border border-ink-100 bg-ink-50/40 p-4">
                            <form @submit.prevent="addProduct()" class="grid sm:grid-cols-2 xl:grid-cols-4 gap-3">
                                <input type="text" x-model="productForm.name" placeholder="Nama produk"
                                       class="px-4 py-3 rounded-xl border border-ink-200 bg-white text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition">
                                <input type="text" x-model="productForm.url" placeholder="https://... (URL produk)"
                                       class="px-4 py-3 rounded-xl border border-ink-200 bg-white text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition">
                                <input type="number" step="0.01" min="0" x-model="productForm.price" placeholder="Harga (opsional)"
                                       class="px-4 py-3 rounded-xl border border-ink-200 bg-white text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition">
                                <label class="flex items-center gap-2 px-4 py-3 rounded-xl border border-dashed border-ink-300 bg-white cursor-pointer hover:border-emerald-400 transition text-sm text-ink-500">
                                    <span x-show="!productForm.imagePreview">📷 Gambar</span>
                                    <span x-show="productForm.imagePreview" class="flex items-center gap-2"><img :src="productForm.imagePreview" class="w-6 h-6 rounded object-cover"> Dipilih</span>
                                    <input type="file" accept="image/*" class="hidden" @change="productImageSelected($event)">
                                </label>
                                <button type="submit" class="sm:col-span-2 xl:col-span-4 py-3 rounded-xl bg-ink-900 text-white text-sm font-bold hover:bg-ink-800 transition" :disabled="saving">
                                    + Tambahkan Produk
                                </button>
                            </form>
                            <p x-show="productForm.error" x-text="productForm.error" class="mt-2 text-xs text-rose-600 font-semibold"></p>
                        </div>

                        {{-- Products list (sortable) --}}
                        <ul x-ref="productsList" class="mt-4 space-y-2.5">
                            <template x-for="product in products" :key="product.id">
                                <li class="flex items-center gap-3 p-3.5 rounded-2xl border border-ink-100 bg-white transition"
                                    :class="!product.is_active ? 'opacity-55' : ''">
                                    <span class="drag-handle cursor-grab active:cursor-grabbing text-ink-300 text-lg select-none">⠿</span>

                                    <div class="w-11 h-11 rounded-xl overflow-hidden bg-ink-50 flex items-center justify-center flex-shrink-0">
                                        <img x-show="product.image_url" :src="product.image_url" class="w-full h-full object-cover">
                                        <span x-show="!product.image_url" class="text-lg">🛍️</span>
                                    </div>

                                    <div class="flex-1 min-w-0" x-show="editingProductId !== product.id">
                                        <p class="font-semibold text-sm text-ink-900 truncate" x-text="product.name"></p>
                                        <p class="text-xs text-ink-400 truncate">
                                            <span x-text="product.price_label || 'Tanpa harga'"></span> · <span x-text="product.url"></span>
                                        </p>
                                    </div>

                                    <div class="flex-1 min-w-0" x-show="editingProductId === product.id" x-cloak>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="text" x-model="editDraft.name" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-emerald-500">
                                            <input type="text" x-model="editDraft.url" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-emerald-500">
                                            <input type="number" step="0.01" x-model="editDraft.price" class="w-24 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none" placeholder="Harga">
                                            <label class="flex items-center gap-1.5 px-3 py-2 rounded-lg border border-dashed border-ink-300 cursor-pointer text-xs text-ink-500">
                                                📷 <input type="file" accept="image/*" class="hidden" @change="editProductImageSelected($event)">
                                            </label>
                                            <button @click="saveEditProduct()" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-xs font-bold">Simpan</button>
                                            <button @click="cancelEdit()" class="px-4 py-2 rounded-lg bg-ink-100 text-ink-600 text-xs font-bold">Batal</button>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button @click="startEditProduct(product)" class="w-9 h-9 rounded-xl text-ink-400 hover:text-emerald-600 hover:bg-emerald-50 transition flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button @click="toggleProduct(product)" class="w-9 h-9 rounded-xl flex items-center justify-center transition" :class="product.is_active ? 'text-emerald-600 hover:bg-emerald-50' : 'text-ink-300 hover:bg-ink-50'">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"></path></svg>
                                        </button>
                                        <button @click="deleteProduct(product)" class="w-9 h-9 rounded-xl text-ink-300 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </li>
                            </template>
                            <li x-show="!products.length" class="text-center py-8 text-ink-400 text-sm">Belum ada produk. Tambahkan produk pertamamu untuk fitur Shop.</li>
                        </ul>
                        <p x-show="products.length" class="mt-2 text-xs text-ink-400">Produk aktif tampil di halaman publik sebagai section <span class="font-semibold">Shop</span>.</p>
                    </div>
                </section>

                {{-- ============ TAB: HEADER ============ --}}
                <section x-show="activeTab === 'header'" x-cloak class="space-y-6">

                    {{-- Avatar --}}
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

                    {{-- Identity --}}
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

                    {{-- Header layout --}}
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

                {{-- ============ TAB: DESIGN ============ --}}
                <section x-show="activeTab === 'design'" x-cloak class="space-y-6">

                    {{-- Theme --}}
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

                    {{-- Background --}}
                    <div class="rounded-2xl border border-ink-100 p-5">
                        <h3 class="font-bold text-ink-900 mb-1">Background</h3>
                        <p class="text-sm text-ink-500 mb-5">Jenis latar belakang halaman kamu.</p>

                        {{-- type selector --}}
                        <div class="flex flex-wrap gap-2 mb-5">
                            <template x-for="(label, type) in backgroundTypes" :key="type">
                                <button type="button" @click="profile.background_type = type; onBackgroundTypeChange()"
                                        class="px-4 py-2 rounded-full text-xs font-bold transition"
                                        :class="profile.background_type === type ? 'bg-ink-900 text-white' : 'bg-ink-100 text-ink-600 hover:bg-ink-200'">
                                    <span x-text="label"></span>
                                </button>
                            </template>
                        </div>

                        {{-- gradient presets --}}
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

                        {{-- solid color --}}
                        <div x-show="profile.background_type === 'solid'" x-cloak class="flex items-center gap-3">
                            <input type="color" x-model="profile.background_value" @change="designChanged()"
                                   class="w-14 h-14 rounded-xl border border-ink-200 cursor-pointer">
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Warna solid</p>
                                <p class="text-xs text-ink-400">Pilih satu warna latar belakang.</p>
                            </div>
                        </div>

                        {{-- pattern --}}
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

                        {{-- image --}}
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
                                <p class="text-xs text-ink-400 mt-3">JPG/PNG, maks 4MB. Gambar akan menjadi latar halamanmu.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Button --}}
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

                    {{-- Font --}}
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

                {{-- ============ TAB: ENHANCE ============ --}}
                <section x-show="activeTab === 'enhance'" x-cloak class="space-y-6">

                    {{-- Social icons --}}
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
                                    <input type="text" x-model="s.url" @input="enhanceChanged()" placeholder="https://..."
                                           class="w-full px-4 py-3 rounded-xl border border-ink-200 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition">
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Featured link --}}
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

                    {{-- Animation --}}
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

                    {{-- SEO --}}
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

                    {{-- Share --}}
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

        {{-- ================= RIGHT: PHONE PREVIEW ================= --}}
        <div class="lg:sticky lg:top-20">
            <div class="rounded-3xl bg-white border border-ink-100 shadow-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-bold text-ink-700">Preview</p>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">Live</span>
                </div>
                @include('components.editor-phone-preview')
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.linkStoreUrl = @json(route('dashboard.links.store'));
    window.linkUpdateUrl = @json(route('dashboard.links.update', 0));
    window.linkToggleUrl = @json(route('dashboard.links.toggle', 0));
    window.linkDestroyUrl = @json(route('dashboard.links.destroy', 0));
    window.linkReorderUrl = @json(route('dashboard.links.reorder'));
    window.productStoreUrl = @json(route('dashboard.products.store'));
    window.productUpdateUrl = @json(route('dashboard.products.update', 0));
    window.productToggleUrl = @json(route('dashboard.products.toggle', 0));
    window.productDestroyUrl = @json(route('dashboard.products.destroy', 0));
    window.productReorderUrl = @json(route('dashboard.products.reorder'));
    window.headerUrl = @json(route('dashboard.header'));
    window.designUrl = @json(route('dashboard.design'));
    window.enhanceUrl = @json(route('dashboard.enhance'));
</script>
@endsection