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
        cardStyles: initial.cardStyles || {},
        buttonStyles: initial.buttonStyles || [],
        icons: initial.icons || [],
        iconSvgs: initial.iconSvgs || {},
        brandHosts: initial.brandHosts || {},
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

        // ---- card -----------------------------------------------------------
        get cardStyleName() {
            const style = this.profile.card_style;
            return this.cardStyles[style] ? style : 'glass';
        },

        get cardEnabled() {
            const value = this.profile.card_enabled;
            return value === undefined || value === null || value === '' ? true : !!value && value !== '0' && value !== 0;
        },

        get cardColor() {
            const color = this.profile.card_color || '';
            return /^#[0-9a-fA-F]{6}$/.test(color) ? color : this.themeIsLight ? '#ffffff' : '#0f172a';
        },

        get cardOpacity() {
            const value = this.profile.card_opacity;
            if (value !== undefined && value !== null && value !== '') {
                return Math.max(0, Math.min(100, Number(value)));
            }
            return { solid: 100, outline: 0 }[this.cardStyleName] ?? (this.themeIsLight ? 96 : 55);
        },

        get cardText() {
            const hex = this.cardColor.slice(1);
            const opacity = this.cardOpacity / 100;
            const cardLuma =
                0.299 * parseInt(hex.slice(0, 2), 16) +
                0.587 * parseInt(hex.slice(2, 4), 16) +
                0.114 * parseInt(hex.slice(4, 6), 16);
            const luma = opacity * cardLuma + (1 - opacity) * (this.themeIsLight ? 255 : 0);

            return luma > 150 ? '#111827' : '#ffffff';
        },

        get cardStyle() {
            const style = this.cardStyleName;
            const contrast = (this.themeIsLight ? '#000000' : '#ffffff').slice(1);
            const hasColor = /^#[0-9a-fA-F]{6}$/.test(this.profile.card_color || '');
            const borderHex = style === 'outline' && hasColor ? this.profile.card_color.slice(1) : contrast;
            const alpha = (p) => Math.round((Math.max(0, Math.min(100, p)) * 255) / 100).toString(16).padStart(2, '0');

            return {
                background: this.cardOpacity >= 100 ? this.cardColor : this.cardColor + alpha(this.cardOpacity),
                color: this.cardText,
                border: `${this.profile.card_border_width ?? 1}px solid #${borderHex}${alpha(style === 'outline' ? 45 : 18)}`,
                boxShadow: this.profile.card_shadow ? '0 20px 45px -18px rgba(0,0,0,.55)' : 'none',
                borderRadius: `${this.profile.card_radius ?? 32}px`,
                backdropFilter: style === 'glass' ? 'blur(16px)' : 'none',
            };
        },

        get buttonStyle() {
            const p = this.profile;
            let radius = p.button_radius ?? (p.button_style === 'pill' ? 999 : p.button_style === 'square' ? 6 : 14);
            const border = p.button_style === 'outline' ? Math.max(p.button_border_width || 0, 2) : (p.button_border_width || 0);
            const isOutline = p.button_style === 'outline';
            const bgColor = p.button_color || this.theme.accent || '#ffffff';
            const textColor = isOutline ? (this.isLight ? '#111827' : '#ffffff') : this.luminanceText(bgColor);
            const alpha = border >= 3 ? 0.45 : 0.35;

            return {
                borderRadius: `${radius}px`,
                borderWidth: `${border}px`,
                borderStyle: border ? 'solid' : 'none',
                borderColor: this.isLight ? `rgba(0,0,0,${alpha})` : `rgba(255,255,255,${alpha})`,
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

        get themeIsLight() {
            return this.profile.theme_text !== 'text-white';
        },

        // Inside the card the readable colour is decided by the card itself,
        // so a light card on a dark page still renders dark text.
        get isLight() {
            return this.cardEnabled ? this.cardText === '#111827' : this.themeIsLight;
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

        // ---- brand icons ----------------------------------------------------
        hostOf(url) {
            try {
                return (new URL(String(url || ''))).hostname.toLowerCase().replace(/^www\./, '');
            } catch (e) {
                return '';
            }
        },

        brandKeyOf(url) {
            const u = String(url || '').toLowerCase().trim();
            if (u.startsWith('mailto:')) return 'mail';
            if (u.startsWith('tel:') || u.startsWith('sms:')) return 'phone';
            if (u.startsWith('whatsapp:')) return 'whatsapp';
            const host = this.hostOf(u);
            if (!host) return 'link';
            for (const [domain, slug] of Object.entries(this.brandHosts)) {
                if (host === domain || host.endsWith('.' + domain)) return slug;
            }
            return 'favicon';
        },

        faviconOf(url) {
            const host = this.hostOf(url);
            return host ? `https://www.google.com/s2/favicons?domain=${encodeURIComponent(host)}&sz=64` : '';
        },

        linkIcon(link, cls = 'w-6 h-6') {
            if (!link) return '';
            const key = link.icon && !['link', 'globe'].includes(link.icon) ? link.icon : this.brandKeyOf(link.url || '');
            if (key === 'favicon') {
                const src = this.faviconOf(link.url);
                return src ? `<img src="${src}" alt="" class="${cls} flex-shrink-0" loading="lazy" referrerpolicy="no-referrer">` : '';
            }
            return (this.iconSvgs[key] || this.iconSvgs['link'] || '').replace('{class}', cls);
        },

        socialIcon(key, cls = 'w-4 h-4') {
            const mapped = key === 'website' ? 'globe' : key === 'email' ? 'mail' : key;
            return (this.iconSvgs[mapped] || '').replace('{class}', cls);
        },

        iconSvgFor(key, cls = 'w-4 h-4') {
            return (this.iconSvgs[key] || this.iconSvgs['link'] || '').replace('{class}', cls);
        },

        iconLabel(key) {
            const labels = {
                link: 'Link', instagram: 'Instagram', tiktok: 'TikTok', youtube: 'YouTube',
                whatsapp: 'WhatsApp', github: 'GitHub', twitter: 'X / Twitter', facebook: 'Facebook',
                linkedin: 'LinkedIn', globe: 'Website', mail: 'Email', spotify: 'Spotify',
                telegram: 'Telegram', discord: 'Discord', pinterest: 'Pinterest', twitch: 'Twitch',
                paypal: 'PayPal', phone: 'Telepon', shop: 'Belanja', calendar: 'Kalender',
                music: 'Musik', camera: 'Kamera', file: 'File', 'map-pin': 'Lokasi',
            };
            return labels[key] || (key ? key.charAt(0).toUpperCase() + key.slice(1).replace(/-/g, ' ') : 'Link');
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

        // Route templates ship a `__ID__` placeholder so no digit in the host
        // (e.g. the :8000 port) can ever be mistaken for the model id.
        url(template, id) {
            return String(template || '').replace('__ID__', encodeURIComponent(id));
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
            this.jsonReq(this.url(window.linkUpdateUrl, d.id), 'PUT', { title: d.title, url: d.url, icon: d.icon })
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
            this.req(this.url(window.linkToggleUrl, link.id), { method: 'PATCH' })
                .then((res) => {
                    const idx = this.links.findIndex((l) => l.id === res.link.id);
                    if (idx >= 0) this.links.splice(idx, 1, { ...res.link, _open: false });
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        deleteLink(link) {
            if (!confirm(`Hapus link "${link.title}"?`)) return;
            this.req(this.url(window.linkDestroyUrl, link.id), { method: 'DELETE' })
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
            this.persistProduct(this.url(window.productUpdateUrl, d.id), 'PUT')
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
            this.req(this.url(window.productToggleUrl, product.id), { method: 'PATCH' })
                .then((res) => {
                    const idx = this.products.findIndex((p) => p.id === res.product.id);
                    if (idx >= 0) this.products.splice(idx, 1, { ...res.product, _open: false });
                })
                .catch((e) => this.toast(e.message, 'err'));
        },

        deleteProduct(product) {
            if (!confirm(`Hapus produk "${product.name}"?`)) return;
            this.req(this.url(window.productDestroyUrl, product.id), { method: 'DELETE' })
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
            const payload = { ...this.designPayload(), remove_background_image: 1 };
            this.jsonReq(window.designUrl, 'POST', payload)
                .then((res) => {
                    this.profile = { ...this.profile, ...res.profile };
                    this.toast('Background dihapus');
                })
                .catch((e) => this.toast(e.message, 'err'))
                .finally(() => (this.saving = false));
        },

        designPayload() {
            return {
                theme: this.profile.theme,
                button_style: this.profile.button_style,
                button_color: this.profile.button_color || '',
                button_radius: this.profile.button_radius ?? '',
                button_border_width: this.profile.button_border_width ?? 0,
                button_shadow: this.profile.button_shadow ? 1 : 0,
                font: this.profile.font,
                background_type: this.profile.background_type,
                background_value: this.profile.background_value || '',
                card_enabled: this.cardEnabled ? 1 : 0,
                card_style: this.cardStyleName,
                card_color: this.profile.card_color || '',
                card_opacity: this.profile.card_opacity ?? '',
                card_radius: this.profile.card_radius ?? '',
                card_border_width: this.profile.card_border_width ?? 1,
                card_shadow: this.profile.card_shadow ? 1 : 0,
            };
        },

        saveDesign(withFile = false) {
            if (this.saving) return;
            this.saving = true;
            const payload = this.designPayload();

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