export default function editor(initial = {}) {
    return {
        tab: initial.tab ?? 'content',
        content_tab: initial.content_tab ?? 'links',
        profile: initial.profile ?? {
            name: '',
            username: '',
            display_name: '',
            bio: '',
            avatar_url: '',
        },
        socials: initial.socials ?? {},
        design: {
            theme: 'aurora',
            button_style: 'soft',
            font: 'sans',
            settings: {},
            ...(initial.design ?? {}),
            settings: {
                show_social: true,
                show_share: true,
                animations_enabled: false,
                featured_link_id: null,
                seo_title: '',
                seo_description: '',
                ...(initial.design?.settings ?? {}),
            },
        },
        links: initial.links ?? [],
        themes: initial.themes ?? {},
        icons: initial.icons ?? [],
        iconColors: initial.icon_colors ?? {},
        urls: initial.urls ?? {},

        loading: false,
        toast: '',
        toastType: 'ok',
        toastTimer: null,

        showAddForm: false,
        addingProduct: false,
        newLink: { title: '', url: '', icon: 'link' },

        editingId: null,
        editForm: {},

        dragIndex: null,
        avatarPreview: null,

        get settings() {
            return this.design.settings;
        },

        get activeLinks() {
            return this.links.filter((l) => l.is_active);
        },

        get productLinks() {
            return this.links.filter((l) => l.icon === 'shop');
        },

        get regularLinks() {
            return this.links.filter((l) => l.icon !== 'shop');
        },

        setTab(tab) {
            this.tab = tab;
        },

        setContentTab(tab) {
            this.content_tab = tab;
        },

        notify(message, type = 'ok') {
            this.toast = message;
            this.toastType = type;
            clearTimeout(this.toastTimer);
            this.toastTimer = setTimeout(() => {
                this.toast = '';
            }, 2600);
        },

        isLightColor(hex) {
            let value = String(hex || '#ffffff').replace('#', '');
            if (value.length === 3) {
                value = value.split('').map((c) => c + c).join('');
            }
            const ints = value
                .match(/.{2}/g)
                .slice(0, 3)
                .map((c) => parseInt(c, 16) / 255);
            if (ints.some((n) => Number.isNaN(n))) return true;
            const [r, g, b] = ints;
            const lum = 0.2126 * r + 0.7152 * g + 0.0722 * b;
            return lum > 0.55;
        },

        theme() {
            return this.themes[this.design.theme] || this.themes.aurora;
        },

        textColor() {
            const s = this.settings;
            if (s.text_color) return s.text_color;
            if (s.wallpaper_type === 'gradient') return this.theme().text_hex;
            return this.isLightColor(s.bg_color) ? '#0f172a' : '#ffffff';
        },

        accentColor() {
            const s = this.settings;
            if (s.accent_color) return s.accent_color;
            return this.theme().from_hex;
        },

        screenStyle() {
            const s = this.settings;
            const base = s.bg_color || '#ffffff';
            let background;

            if (s.wallpaper_type === 'solid') {
                background = `background-color:${base};`;
            } else if (s.wallpaper_type === 'pattern') {
                const overlay = this.isLightColor(base)
                    ? 'rgba(15,23,42,0.12)'
                    : 'rgba(255,255,255,0.16)';
                background = `background-color:${base}; background-image:${this.patternImage(s.pattern, overlay)};`;
            } else if (s.wallpaper_type === 'custom' && (s.custom_wallpaper_preview || s.custom_wallpaper_url)) {
                const wallpaper = s.custom_wallpaper_preview || s.custom_wallpaper_url;
                background = `background-color:#f8fafc; background-image:url("${wallpaper}"); background-size:cover; background-position:center;`;
            } else {
                const t = this.theme();
                background = `background-image:linear-gradient(135deg, ${t.from_hex}, ${t.to_hex});`;
            }

            return `${background} color:${this.textColor()}; font-family:${this.fontFamily()};`;
        },

        patternImage(pattern, overlay) {
            switch (pattern) {
                case 'grid':
                    return `linear-gradient(${overlay} 1px, transparent 1px), linear-gradient(90deg, ${overlay} 1px, transparent 1px); background-size:24px 24px`;
                case 'waves':
                    return `radial-gradient(ellipse at 50% -20%, ${overlay} 40%, transparent 42%); background-size:40px 26px`;
                case 'rings':
                    return `radial-gradient(circle, transparent 6px, ${overlay} 7px, ${overlay} 8px, transparent 9px); background-size:32px 32px`;
                default:
                    return `radial-gradient(circle, ${overlay} 1.5px, transparent 1.6px); background-size:16px 16px`;
            }
        },

        fontFamily() {
            const fonts = {
                sans: "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif",
                serif: "'Playfair Display', ui-serif, Georgia, serif",
                mono: "'JetBrains Mono', ui-monospace, monospace",
            };
            return fonts[this.design.font] || fonts.sans;
        },

        btnStyle() {
            const s = this.settings;
            const style = this.design.button_style;
            const radius =
                style === 'pill' ? '999px' : style === 'square' ? '0px' : `${s.button_radius || 14}px`;
            const text = this.textColor();
            const css = [];

            if (style === 'outline') {
                css.push('background-color:transparent');
                css.push('border:1.5px solid ' + text);
            } else {
                if (s.button_color) {
                    const bg = s.button_color;
                    const fg = this.isLightColor(bg) ? '#0f172a' : '#ffffff';
                    if (style === 'solid') {
                        css.push(`background-color:${bg}`);
                        css.push(`color:${fg}`);
                    } else {
                        css.push(`background-color:${bg}`);
                        css.push(`color:${fg}`);
                    }
                } else {
                    const bg =
                        text === '#ffffff'
                            ? style === 'solid'
                                ? 'rgba(255,255,255,0.92)'
                                : 'rgba(255,255,255,0.14)'
                            : style === 'solid'
                              ? 'rgba(15,23,42,0.92)'
                              : 'rgba(255,255,255,0.6)';
                    const fg = text === '#ffffff' ? (style === 'solid' ? '#0f172a' : '#ffffff') : (style === 'solid' ? '#ffffff' : '#0f172a');
                    css.push(`background-color:${bg}`);
                    css.push(`color:${fg}`);
                }

                if (s.button_border) {
                    css.push('border:1px solid ' + (text === '#ffffff' ? 'rgba(255,255,255,0.35)' : 'rgba(15,23,42,0.15)'));
                } else {
                    css.push('border:1px solid transparent');
                }
            }

            if (s.button_shadow) {
                css.push('box-shadow:0 8px 24px -6px rgba(0,0,0,0.35)');
            }

            css.push('border-radius:' + radius);
            return css.join('; ');
        },

        iconColor(icon) {
            return this.iconColors[icon] || '#6366f1';
        },

        iconGlyph(icon) {
            return {
                instagram: '◎', tiktok: '♪', youtube: '▶', whatsapp: '◉',
                facebook: 'f', threads: '@', x: '𝕏', telegram: '➤',
                discord: '☯', spotify: '●', github: '◖', linkedin: 'in',
                website: '◉', mail: '@', shop: '▣', product: '▣',
                donation: '♥', contact: '☎', location: '⌖', link: '↗',
            }[icon] || '↗';
        },

        iconSvg(icon) {
            const paths = {
                instagram: '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>',
                tiktok: '<path d="M14 4v10.5a4.5 4.5 0 1 1-3-4.24V7.1c3.1 2.1 5.4 2.3 6 2.3V6.2c-1.4-.1-2.5-.8-3-2.2Z"/>',
                youtube: '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="m10 9 5 3-5 3V9Z" fill="currentColor" stroke="none"/>',
                whatsapp: '<path d="M6.5 19.5 7.5 16A7 7 0 1 1 12 19a7 7 0 0 1-3.5-.9l-2 .4Z"/><path d="M9.5 9.5c.5 2 1.5 3 3.5 4l1.5-1c.3-.2.7-.1.9.1l.8.8c.3.3.3.8 0 1.1-3.5 2-8-2.5-6-6 .3-.3.8-.3 1.1 0l.8.8c.2.2.3.6.1.9l-.7 1.3Z" fill="currentColor" stroke="none"/>',
                facebook: '<path d="M14 8h3V4.5h-3c-2.8 0-4.5 1.7-4.5 4.7V11H7v3.5h2.5V20H13v-5.5h3l.5-3.5H13V9.5c0-1 .3-1.5 1-1.5Z" fill="currentColor" stroke="none"/>',
                x: '<path d="m5 4 5.4 6.5L5.3 20H8l3.6-7 5.8 7H21l-6.2-7.4L20.5 4h-2.7l-3.1 6.2L9.5 4H5Z" fill="currentColor" stroke="none"/>',
                telegram: '<path d="m21 4-3 16-5.2-4.1-2.8 2.7.4-4.2L18 7l-9.2 5.8-4-1.3L21 4Z" fill="currentColor" stroke="none"/>',
                linkedin: '<path d="M5 8.5H2V21h3V8.5ZM3.5 3A1.8 1.8 0 1 0 3.5 6.6 1.8 1.8 0 0 0 3.5 3ZM8 8.5h3v1.7c.6-1.1 1.8-2 3.8-2 4 0 4.7 2.6 4.7 6V21h-3v-6c0-1.4 0-3.2-2-3.2s-2.3 1.5-2.3 3.1V21H8V8.5Z" fill="currentColor" stroke="none"/>',
            };
            return `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${paths[icon] || '<circle cx="12" cy="12" r="8"/><path d="M12 8v8m-4-4h8"/>'}</svg>`;
        },

        iconLabel(icon) {
            return {
                link: 'Link', instagram: 'Instagram', tiktok: 'TikTok', youtube: 'YouTube',
                whatsapp: 'WhatsApp', facebook: 'Facebook', threads: 'Threads', x: 'X',
                telegram: 'Telegram', discord: 'Discord', spotify: 'Spotify', github: 'GitHub',
                linkedin: 'LinkedIn', website: 'Website', mail: 'Email', shop: 'Toko Online',
                product: 'Produk', donation: 'Donasi', contact: 'Kontak', location: 'Lokasi',
            }[icon] || icon;
        },

        defaultColor(key) {
            const s = this.settings;
            if (key === 'bg_color') return s.bg_color || '#f8fafc';
            if (key === 'text_color') return s.text_color || this.textColor();
            if (key === 'button_color') return s.button_color || '#ffffff';
            if (key === 'accent_color') return s.accent_color || this.accentColor();
            return '#6366f1';
        },

        onSubmit(form, endpoint, onSuccess) {
            this.loading = true;
            const body = new FormData(form);
            body.append('_method', 'PUT');
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body,
            })
                .then(async (res) => {
                    const json = await res.json().catch(() => ({}));
                    if (!res.ok) throw json;
                    if (onSuccess) onSuccess(json);
                    this.notify(json.message || 'Tersimpan.');
                })
                .catch((err) => {
                    const msg = err?.message || 'Terjadi kesalahan.';
                    const first = err?.errors ? Object.values(err.errors)[0]?.[0] : null;
                    this.notify(first || msg, 'err');
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        saveHeader(form) {
            this.onSubmit(form, this.urls.profile_update, (json) => {
                this.profile = json.profile;
                this.avatarPreview = null;
            });
        },

        saveDesign(form) {
            this.onSubmit(form, this.urls.settings_update, (json) => {
                this.design = json.design;
            });
        },

        saveEnhance(form) {
            this.onSubmit(form, this.urls.settings_update, (json) => {
                this.design = json.design;
                this.socials = json.socials || {};
            });
        },

        openAddLink(isProduct = false) {
            this.addingProduct = isProduct;
            this.newLink = { title: '', url: '', icon: isProduct ? 'shop' : 'link' };
            this.showAddForm = true;
        },

        submitCreate() {
            const payload = {
                title: this.newLink.title,
                url: this.newLink.url,
                icon: this.newLink.icon,
                content_tab: this.content_tab,
            };
            this.loading = true;
            fetch(this.urls.links_store, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify(payload),
            })
                .then((res) => res.json())
                .then((json) => {
                    if (json?.errors) {
                        this.notify(Object.values(json.errors)[0]?.[0] || 'Gagal menambah link.', 'err');
                        return;
                    }
                    this.links.push(json.link);
                    this.showAddForm = false;
                    this.newLink = { title: '', url: '', icon: this.addingProduct ? 'shop' : 'link' };
                    this.notify(json.message || 'Link ditambahkan.');
                })
                .catch(() => this.notify('Terjadi kesalahan.', 'err'))
                .finally(() => (this.loading = false));
        },

        startEdit(link) {
            this.editingId = link.id;
            this.editForm = { ...link };
        },

        cancelEdit() {
            this.editingId = null;
            this.editForm = {};
        },

        submitEdit(link) {
            const payload = {
                title: this.editForm.title,
                url: this.editForm.url,
                slug: this.editForm.slug,
                icon: this.editForm.icon,
                _method: 'PUT',
            };
            this.loading = true;
            fetch('/dashboard/links/' + link.id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify(payload),
            })
                .then(async (res) => {
                    const json = await res.json().catch(() => ({}));
                    if (json?.errors) {
                        this.notify(Object.values(json.errors)[0]?.[0] || 'Gagal menyimpan.', 'err');
                        return;
                    }
                    const idx = this.links.findIndex((l) => l.id === link.id);
                    if (idx > -1) this.links[idx] = json.link;
                    this.cancelEdit();
                    this.notify(json.message || 'Tersimpan.');
                })
                .catch(() => this.notify('Terjadi kesalahan.', 'err'))
                .finally(() => (this.loading = false));
        },

        toggleLink(link) {
            const previous = link.is_active;
            link.is_active = !previous;
            fetch('/dashboard/links/' + link.id + '/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ _method: 'PATCH' }),
            })
                .then(async (res) => {
                    const json = await res.json().catch(() => ({}));
                    if (json?.errors) {
                        link.is_active = previous;
                        this.notify('Gagal mengubah status.', 'err');
                        return;
                    }
                    this.notify(json.message || 'Status diperbarui.');
                })
                .catch(() => {
                    link.is_active = previous;
                    this.notify('Terjadi kesalahan.', 'err');
                });
        },

        removeLink(link) {
            if (!confirm('Hapus link ini?')) return;
            fetch('/dashboard/links/' + link.id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ _method: 'DELETE' }),
            })
                .then(async (res) => {
                    const json = await res.json().catch(() => ({}));
                    if (json?.errors) {
                        this.notify('Gagal menghapus link.', 'err');
                        return;
                    }
                    this.links = this.links.filter((l) => l.id !== link.id);
                    this.notify(json.message || 'Link dihapus.');
                })
                .catch(() => this.notify('Terjadi kesalahan.', 'err'));
        },

        dragStart(index) {
            this.dragIndex = index;
        },

        dropAt(index) {
            if (this.dragIndex === null || this.dragIndex === index) {
                this.dragIndex = null;
                return;
            }
            const moved = this.links.splice(this.dragIndex, 1)[0];
            this.links.splice(index, 0, moved);
            this.dragIndex = null;
            this.persistOrder();
        },

        persistOrder() {
            fetch(this.urls.links_reorder, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ order: this.links.map((l) => l.id) }),
            }).catch(() => this.notify('Gagal menyimpan urutan.', 'err'));
        },

        previewAvatar() {
            return this.avatarPreview || this.profile.avatar_url || '';
        },

        previewWallpaper(file) {
            if (!file) return;
            if (this.design.settings.custom_wallpaper_preview) {
                URL.revokeObjectURL(this.design.settings.custom_wallpaper_preview);
            }
            this.design.settings.custom_wallpaper_preview = URL.createObjectURL(file);
        },

        triggerAvatar(input) {
            if (!input.files || !input.files[0]) return;
            this.avatarPreview = URL.createObjectURL(input.files[0]);
        },
    };
}