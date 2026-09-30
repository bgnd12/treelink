import Sortable from 'sortablejs';

/**
 * The TreeLink editor component.
 *
 * Wired to the page via `x-data="editor(@js($editorData))"`.
 * Owns the full editor state and mirrors every change straight into the
 * reactive phone preview next to it. Every mutation is persisted through the
 * JSON API endpoints registered under /dashboard.
 */
export default function editor(initial = {}) {
    return {
        // ---- static config -------------------------------------------------
        activeTab: 'content',
        themes: initial.themes || {},
        headerLayouts: initial.headerLayouts || {},
        fonts: initial.fonts || {},
        patterns: initial.patterns || {},
        backgroundTypes: initial.backgroundTypes || {},
        buttonStyles: initial.buttonStyles || [],
        icons: initial.icons || [],
        socials: initial.socials || [],
        urlPrefix: (initial.publicUrl || '').replace((initial.profile || {}).username || '', ''),

        // ---- live state ----------------------------------------------------
        profile: initial.profile || {},
        links: (initial.links || []).map((l) => ({ ...l, _open: false })),
        products: (initial.products || []).map((p) => ({ ...p, _open: false })),

        // ---- form / ui state ------------------------------------------------
        linkForm: { title: '', url: '', icon: 'link', error: '' },
        productForm: { name: '', url: '', price: '', image: null, imagePreview: '', error: '' },
        editingLinkId: null,
        editingProductId: null,
        editDraft: null,
        avatarFile: null,
        avatarPreview: '',
        backgroundFile: null,
        backgroundPreview: '',
        featuredLinkId: (initial.links || []).find((l) => l.is_featured)?.id ?? null,
        saving: false,
        savedAt: '',
        toastMsg: '',
        toastType: 'ok',

        _sortables: {},
        _timers: {},

        // ---- getters ---------------------------------------------------------
        get theme() {
            return this.themes[this.profile.theme] || this.themes['aurora'] || {};
        },

        get activeLinks() {
            return this.links.filter((l) => l.is_active);
        },

        get activeProducts() {
            return this.products.filter((p) => p.is_active);
        },

        get username() {
            return this.profile.username || '';
        },

        get publicUrlLabel() {
            return this.urlPrefix + this.username;
        },

        get backgroundClass() {
            const t = this.profile.background_type;
            if (t === 'gradient') return `bg-gradient-to-b ${this.profile.theme_from} ${this.profile.theme_to}`;
            if (t === 'pattern') return `pattern-${this.profile.background_value || 'dots'}`;
            return '';
        },

        get backgroundStyle() {
            const t = this.profile.background_type;
            if (t === 'solid') return { backgroundColor: this.profile.background_value || '#7c4dff' };
            if (t === 'pattern') return { backgroundColor: this.theme.pattern_bg || this.profile.theme_from || '#3f1c99' };
            if (t === 'image' && this.profile.background_image_url) {
                return {
                    backgroundImage: `url('${this.profile.background_image_url}')`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                };
            }
            return {};
        },

        get buttonStyle() {
            const p = this.profile;
            let radius = p.button_radius ?? (p.button_style === 'pill' ? 999 : p.button_style === 'square' ? 6 : 14);
            const border = p.button_style === 'outline' ? Math.max(p.button_border_width || 0, 2) : (p.button_border_width || 0);
            const isOutline = p.button_style === 'outline';
            const bgColor = p.button_color || this.theme.accent || '#ffffff';
            const textColor = isOutline ? (p.theme_text === 'text-white' ? '#ffffff' : '#111827') : this.luminanceText(bgColor);
            const alpha = border >= 3 ? 0.45 : 0.35;

            return {
                borderRadius: `${radius}px`,
                borderWidth: `${border}px`,
                borderStyle: border ? 'solid' : 'none',
                borderColor: p.theme_text === 'text-white' ? `rgba(255,255,255,${alpha})` : `rgba(0,0,0,${alpha})`,
                backgroundColor: isOutline ? 'transparent' : bgColor,
                color: textColor,
                boxShadow: p.button_shadow ? '0 10px 28px -10px rgba(0,0,0,0.45)' : 'none',
            };
        },

        get fontFamily() {
            return this.profile.font_family || "sans-serif";
        },

        get fontClass() {
            return this.profile.font || 'sans';
        },

        get isLight() {
            return this.profile.theme_text !== 'text-white';
        },

        get avatarSrc() {
            return this.avatarPreview || this.profile.avatar_url || '';
        },

        luminanceText(hex) {
            const clean = (hex || '').replace('#', '');
            if (clean.length !== 6 || !/^[0-9a-fA-F]{6}$/.test(clean)) return '#ffffff';
            const r = parseInt(clean.slice(0, 2), 16);
            const g = parseInt(clean.slice(2, 4), 16);
            const b = parseInt(clean.slice(4, 6), 16);
            return (0.299 * r + 0.587 * g + 0.114 * b) / 255 > 0.6 ? '#111827' : '#ffffff';
        },

        // ---- lifecycle -------------------------------------------------------
        init() {
            this.$nextTick(() => {
                this.makeSortable('linksList', 'links');
                this.makeSortable('productsList', 'products');
            });
        },

        setTab(tab) {
            this.activeTab = tab;
            this.$nextTick(() => {
                this.makeSortable('linksList', 'links');
                this.makeSortable('productsList', 'products');
            });
        },

        token() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        },

        toast(msg, type = 'ok') {
            this.toastMsg = msg;
            this.toastType = type;
            clearTimeout(this._timers.toast);
            this._timers.toast = setTimeout(() => (this.toastMsg = ''), 2600);
        },

        markSaved() {
            this.savedAt = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        },

        queue(kind) {
            const fn = { header: this.saveHeader, design: this.saveDesign, enhance: this.saveEnhance }[kind];
            clearTimeout(this._timers[kind]);
            this._timers[kind] = setTimeout(() => fn.call(this), 700);
        },

        // ---- fetch helpers --------------------------------------------------
        async req(url, options = {}) {
            const headers = {
                Accept: 'application/json',
                'X-CSRF-TOKEN': this.token(),
                ...(options.headers || {}),
            };
            const res = await fetch(url, { ...options, headers });
            let data = null;
            try {
                data = await res.json();
            } catch (e) {
                data = null;
            }
            if (!res.ok) {
                let msg = `${res.status} ${res.statusText}`;
                if (data && data.errors) {
                    const keys = Object.keys(data.errors);
                    if (keys.length) msg = data.errors[keys[0]][0];
                } else if (data && data.message) {
                    msg = data.message;
                }
                throw new Error(msg);
            }
            return data;
        },

        async jsonReq(url, method, body) {
            return this.req(url, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body),
            });
        },

        // ---- links ----------------------------------------------------------
        addLink() {
            const f = this.linkForm;
            if (!f.title.trim() || !f.url.trim()) {
                f.error = 'Judul dan URL wajib diisi.';
                return;
            }
            f.error = '';
            this.saving = true;
            this.jsonReq(window.linkStoreUrl, 'POST', { title: f.title, url: f.url, icon: f.icon })
                .then((res) => {
                    this.links.push({ ...res.link, _open: false });
                    f.title = '';
                    f.url = '';
                    f.icon = 'link';
                    this.toast('Link ditambahkan');
                })
                .catch((e) => this.toast(e.message, 'err'))
                .finally(() => (this.saving = false));
        },

        startEditLink(link) {
            this.editingLinkId = link.id;
            this.editingProductId = null;
            this.editDraft = { ...link };
        },

        cancelEdit() {
            this.editingLinkId = null;
            this.editingProductId = null;
            this.editDraft = null;
        },

        saveEditLink() {
            const d = this.editDraft;
            if (!d || !d.title.trim() || !d.url.trim()) return;
            this.jsonReq(window.linkUpdateUrl.replace('0', d.id), 'PUT', { title: d.title, url: d.url, icon: d.icon })
                .then((res) => {
                    const idx = this.links.findIndex((l) => l.id === res.link.id);
                    if (idx >= 0) this.links.splice(idx, 1, { ...res.link, _open: false });
                    this.editingLinkId = null;
                    this.editDraft = null;
                    this.toast('Link diperbarui');
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        toggleLink(link) {
            this.req(window.linkToggleUrl.replace('0', link.id), { method: 'PATCH' })
                .then((res) => {
                    const idx = this.links.findIndex((l) => l.id === res.link.id);
                    if (idx >= 0) this.links.splice(idx, 1, { ...res.link, _open: false });
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        deleteLink(link) {
            if (!confirm(`Hapus link "${link.title}"?`)) return;
            this.req(window.linkDestroyUrl.replace('0', link.id), { method: 'DELETE' })
                .then(() => {
                    this.links = this.links.filter((l) => l.id !== link.id);
                    if (this.featuredLinkId === link.id) this.featuredLinkId = null;
                    this.toast('Link dihapus');
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        // ---- products -------------------------------------------------------
        addProduct() {
            const f = this.productForm;
            if (!f.name.trim() || !f.url.trim()) {
                f.error = 'Nama dan URL wajib diisi.';
                return;
            }
            f.error = '';
            this.saving = true;
            this.persistProduct(window.productStoreUrl, 'POST')
                .then((res) => {
                    this.products.push({ ...res.product, _open: false });
                    f.name = '';
                    f.url = '';
                    f.price = '';
                    f.image = null;
                    f.imagePreview = '';
                    this.toast('Produk ditambahkan');
                })
                .catch((e) => this.toast(e.message, 'err'))
                .finally(() => (this.saving = false));
        },

        startEditProduct(product) {
            this.editingProductId = product.id;
            this.editingLinkId = null;
            this.editDraft = { ...product };
        },

        saveEditProduct() {
            const d = this.editDraft;
            if (!d || !d.name.trim() || !d.url.trim()) return;
            this.persistProduct(window.productUpdateUrl.replace('0', d.id), 'PUT')
                .then((res) => {
                    const idx = this.products.findIndex((p) => p.id === res.product.id);
                    if (idx >= 0) this.products.splice(idx, 1, { ...res.product, _open: false });
                    this.editingProductId = null;
                    this.editDraft = null;
                    this.toast('Produk diperbarui');
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        toggleProduct(product) {
            this.req(window.productToggleUrl.replace('0', product.id), { method: 'PATCH' })
                .then((res) => {
                    const idx = this.products.findIndex((p) => p.id === res.product.id);
                    if (idx >= 0) this.products.splice(idx, 1, { ...res.product, _open: false });
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        deleteProduct(product) {
            if (!confirm(`Hapus produk "${product.name}"?`)) return;
            this.req(window.productDestroyUrl.replace('0', product.id), { method: 'DELETE' })
                .then(() => {
                    this.products = this.products.filter((p) => p.id !== product.id);
                    this.toast('Produk dihapus');
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        productImageSelected(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.productForm.image = file;
            this.productForm.imagePreview = URL.createObjectURL(file);
        },

        editProductImageSelected(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.editDraft._image = file;
            this.editDraft.image_url = URL.createObjectURL(file);
        },

        persistProduct(url, method) {
            const isEdit = method === 'PUT' && this.editDraft;
            const image = isEdit ? this.editDraft._image : this.productForm.image;
            const payload = isEdit
                ? { name: this.editDraft.name, url: this.editDraft.url, price: this.editDraft.price ?? '' }
                : { name: this.productForm.name, url: this.productForm.url, price: this.productForm.price };

            if (image instanceof File) {
                const fd = new FormData();
                fd.append('image', image);
                for (const [k, v] of Object.entries(payload)) fd.append(k, v ?? '');
                fd.append('_method', isEdit ? 'PUT' : 'POST');
                return this.req(url, { method: 'POST', body: fd });
            }

            return this.jsonReq(url, method, payload);
        },

        // ---- reorder --------------------------------------------------------
        makeSortable(refName, type) {
            const el = this.$refs[refName];
            if (!el || this._sortables[type]) return;
            this._sortables[type] = Sortable.create(el, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'opacity-40',
                onEnd: () => {
                    this.$nextTick(() => this.reorderItems(type));
                },
            });
        },

        reorderItems(type) {
            const refName = type === 'links' ? 'linksList' : 'productsList';
            const list = this.$refs[refName];
            if (!list) return;
            const order = Array.from(list.querySelectorAll('[data-id]')).map((el) => Number(el.dataset.id));
            const arrName = type === 'links' ? 'links' : 'products';
            const ordered = order
                .map((id) => this[arrName].find((i) => i.id === id))
                .filter(Boolean);
            this[arrName] = ordered;

            const url = type === 'links' ? window.linkReorderUrl : window.productReorderUrl;
            this.jsonReq(url, 'POST', { order })
                .then(() => this.toast(type === 'links' ? 'Urutan link disimpan' : 'Urutan produk disimpan'))
                .catch(() => {});
        },

        // ---- header ---------------------------------------------------------
        headerChanged() {
            this.queue('header');
        },

        avatarSelected(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.avatarFile = file;
            this.avatarPreview = URL.createObjectURL(file);
            this.saveHeader();
        },

        saveHeader() {
            if (this.saving) return;
            this.saving = true;
            const fd = new FormData();
            fd.append('display_name', this.profile.display_name || '');
            fd.append('username', this.profile.username || '');
            fd.append('bio', this.profile.bio || '');
            fd.append('header_layout', this.profile.header_layout || 'classic');
            if (this.avatarFile instanceof File) fd.append('avatar', this.avatarFile);

            this.req(window.headerUrl, { method: 'POST', body: fd })
                .then((res) => {
                    this.profile = { ...this.profile, ...res.profile };
                    this.avatarFile = null;
                    this.avatarPreview = '';
                    this.markSaved();
                })
                .catch((e) => this.toast(e.message, 'err'))
                .finally(() => (this.saving = false));
        },

        // ---- design ---------------------------------------------------------
        designChanged() {
            this.queue('design');
        },

        onThemeChange() {
            if (this.profile.background_type === 'gradient') {
                this.profile.background_value = `${this.theme.from} ${this.theme.to}`;
            }
            this.queue('design');
        },

        onBackgroundTypeChange() {
            if (this.profile.background_type === 'gradient') {
                this.profile.background_value = `${this.theme.from} ${this.theme.to}`;
            }
            if (this.profile.background_type === 'solid' && !this.profile.background_value) {
                this.profile.background_value = this.theme.pattern_bg || '#7c4dff';
            }
            if (this.profile.background_type === 'pattern' && !this.profile.background_value) {
                this.profile.background_value = 'dots';
            }
            this.queue('design');
        },

        backgroundSelected(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.backgroundFile = file;
            this.backgroundPreview = URL.createObjectURL(file);
            this.saveDesign(true);
        },

        removeBackground() {
            if (!confirm('Hapus gambar background?')) return;
            this.backgroundFile = null;
            this.backgroundPreview = '';
            this.profile.background_image_url = null;
            this.saving = true;
            const payload = {
                theme: this.profile.theme,
                button_style: this.profile.button_style,
                button_color: this.profile.button_color || '',
                button_radius: this.profile.button_radius ?? '',
                button_border_width: this.profile.button_border_width ?? 0,
                button_shadow: this.profile.button_shadow ? 1 : 0,
                font: this.profile.font,
                background_type: this.profile.background_type,
                background_value: this.profile.background_value || '',
                remove_background_image: 1,
            };
            this.jsonReq(window.designUrl, 'POST', payload)
                .then((res) => {
                    this.profile = { ...this.profile, ...res.profile };
                    this.toast('Background dihapus');
                })
                .catch((e) => this.toast(e.message, 'err'))
                .finally(() => (this.saving = false));
        },

        saveDesign(withFile = false) {
            if (this.saving) return;
            this.saving = true;
            const payload = {
                theme: this.profile.theme,
                button_style: this.profile.button_style,
                button_color: this.profile.button_color || '',
                button_radius: this.profile.button_radius ?? '',
                button_border_width: this.profile.button_border_width ?? 0,
                button_shadow: this.profile.button_shadow ? 1 : 0,
                font: this.profile.font,
                background_type: this.profile.background_type,
                background_value: this.profile.background_value || '',
            };

            const send = (url, options) =>
                this.req(url, options)
                    .then((res) => {
                        this.profile = { ...this.profile, ...res.profile };
                        this.backgroundFile = null;
                        this.backgroundPreview = '';
                        this.markSaved();
                    })
                    .catch((e) => this.toast(e.message, 'err'))
                    .finally(() => (this.saving = false));

            if (withFile && this.backgroundFile instanceof File) {
                const fd = new FormData();
                fd.append('background_image', this.backgroundFile);
                for (const [k, v] of Object.entries(payload)) fd.append(k, v ?? '');
                return send(window.designUrl, { method: 'POST', body: fd });
            }

            return this.jsonReq(window.designUrl, 'POST', payload).then((res) => {
                this.profile = { ...this.profile, ...res.profile };
                this.backgroundFile = null;
                this.backgroundPreview = '';
                this.markSaved();
            }).catch((e) => this.toast(e.message, 'err')).finally(() => (this.saving = false));
        },

        // ---- enhance --------------------------------------------------------
        enhanceChanged() {
            this.queue('enhance');
        },

        saveEnhance() {
            if (this.saving) return;
            this.saving = true;
            const social_links = {};
            this.socials.forEach((s) => {
                if (s.url && s.url.trim()) social_links[s.key] = s.url.trim();
            });

            return this.jsonReq(window.enhanceUrl, 'POST', {
                social_links,
                featured_link_id: this.featuredLinkId || null,
                seo_title: this.profile.seo_title || '',
                seo_description: this.profile.seo_description || '',
                animation_enabled: this.profile.animation_enabled ? 1 : 0,
            })
                .then((res) => {
                    this.profile = { ...this.profile, ...res.profile };
                    this.links = (res.links || []).map((l) => ({ ...l, _open: false }));
                    this.featuredLinkId = (res.links || []).find((l) => l.is_featured)?.id ?? null;
                    this.markSaved();
                })
                .catch((e) => this.toast(e.message, 'err'))
                .finally(() => (this.saving = false));
        },
    };
}