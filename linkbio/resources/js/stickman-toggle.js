/**
 * Stickman theme toggle.
 *
 * A canvas-animated Light/Dark switch, registered as an Alpine component so it
 * can sit inside the editor's reactive scope. It owns only the animation and
 * the switch visuals; the actual profile theme is applied and persisted by the
 * editor() component, which listens for the bubbling `theme-mode-changed`
 * event and feeds back via the window `treelink-theme-mode-sync` event.
 */
export default function stickmanThemeToggle(config = {}) {
    return {
        dark: !!config.initialDark,
        busy: false,

        _st: null,
        _last: 0,
        _raf: null,
        _ctx: null,
        _dpr: 1,
        _W: 300,
        _H: 150,
        _hole: { x: 248, y: 122, rx: 20, ry: 5 },

        init() {
            const cv = this.$refs.stage;
            if (!cv) return;

            this._dpr = Math.min(window.devicePixelRatio || 1, 2);
            cv.width = this._W * this._dpr;
            cv.height = this._H * this._dpr;
            this._ctx = cv.getContext('2d');
            this._last = performance.now();
            this._raf = requestAnimationFrame((ts) => this._frame(ts));
        },

        destroy() {
            if (this._raf) cancelAnimationFrame(this._raf);
            this._raf = null;
        },

        /**
         * Reflect an external theme change without notifying back up, so the
         * editor never ends up in a toggle loop.
         */
        setDark(value) {
            const next = !!value;
            if (next === this.dark) return;
            this._applyDark(next);
        },

        go() {
            if (this.busy) return;
            this.busy = true;
            this._st = { t: 0, sw: false };
        },

        _applyDark(value) {
            this.dark = value;

            const panel = this.$refs.panel;
            if (panel) {
                panel.classList.remove('tl-flash');
                void panel.offsetWidth;
                panel.classList.add('tl-flash');
            }
        },

        _commit() {
            this._applyDark(!this.dark);
            this.$dispatch('theme-mode-changed', { dark: this.dark });
        },

        _drawHole() {
            const ctx = this._ctx;
            const { x, y, rx, ry } = this._hole;

            ctx.save();
            ctx.fillStyle = this.dark ? '#0a0b14' : 'rgba(150,160,205,.45)';
            ctx.beginPath();
            ctx.ellipse(x, y, rx, ry, 0, 0, Math.PI * 2);
            ctx.fill();
            ctx.strokeStyle = this.dark ? 'rgba(255,255,255,.08)' : 'rgba(255,255,255,.9)';
            ctx.lineWidth = 1.2;
            ctx.beginPath();
            ctx.ellipse(x, y + 0.8, rx, ry, 0, 0, Math.PI);
            ctx.stroke();
            ctx.restore();
        },

        _drawMan(x, y, s, t, pose) {
            const ctx = this._ctx;
            const c = this.dark ? '#ffffff' : '#2f3146';

            ctx.save();
            ctx.translate(x, y);
            ctx.scale(s, s);
            ctx.strokeStyle = c;
            ctx.fillStyle = c;
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            const sw = Math.sin(t) * 9;
            if (pose === 'walk') ctx.translate(0, -Math.abs(Math.cos(t)) * 1.5);

            ctx.beginPath();
            ctx.moveTo(0, -18);
            ctx.lineTo(sw, 0);
            ctx.moveTo(0, -18);
            ctx.lineTo(-sw, 0);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(0, -18);
            ctx.lineTo(0, -36);
            ctx.stroke();

            ctx.beginPath();
            if (pose === 'wave') {
                ctx.moveTo(0, -33);
                ctx.lineTo(-11, -44);
                ctx.moveTo(0, -33);
                ctx.lineTo(11, -44);
            } else if (pose === 'fall') {
                ctx.moveTo(0, -33);
                ctx.lineTo(-12, -30);
                ctx.moveTo(0, -33);
                ctx.lineTo(12, -30);
            } else {
                const a = Math.sin(t) * 7;
                ctx.moveTo(0, -33);
                ctx.lineTo(-a, -24);
                ctx.moveTo(0, -33);
                ctx.lineTo(a, -24);
            }
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(0, -42, 5.5, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        },

        _frame(ts) {
            this._raf = requestAnimationFrame((next) => this._frame(next));
            if (!this._ctx) return;

            const dt = Math.min(ts - this._last, 50);
            this._last = ts;

            // Pause the sequence while the widget is hidden (e.g. another tab).
            if (this.$el.offsetParent === null) return;

            const ctx = this._ctx;
            const HOLE = this._hole;
            const GROUND = HOLE.y;
            const W = this._W;

            ctx.setTransform(this._dpr, 0, 0, this._dpr, 0, 0);
            ctx.clearRect(0, 0, W, this._H);
            this._drawHole();

            if (!this._st) return;

            this._st.t += dt;
            const T = this._st.t;
            const A = 1700;
            const B = 500;
            const C = 650;
            const ease = (t) => (t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2);

            if (T < A) {
                const p = T / A;
                const x = 118 + (HOLE.x - 14 - 118) * p;
                const s = 0.45 + 0.55 * ease(Math.min(p * 2.2, 1));
                this._drawMan(x, GROUND, s, T / 85, 'walk');
            } else if (T < A + B) {
                this._drawMan(HOLE.x - 14, GROUND, 1, 0, 'wave');
            } else if (T < A + B + C) {
                const p = (T - A - B) / C;
                const x = HOLE.x - 14 + 14 * Math.min(p * 2, 1);
                ctx.save();
                ctx.beginPath();
                ctx.rect(0, 0, W, GROUND + 1);
                ctx.clip();
                this._drawMan(x, GROUND + p * p * 60, 1 - 0.6 * p, 0, 'fall');
                ctx.restore();

                if (!this._st.sw && p > 0.8) {
                    this._st.sw = true;
                    this._commit();
                }
            } else {
                this._st = null;
                this.busy = false;
            }
        },
    };
}
