<style>
    @keyframes treelink-tree-sway {
        0%, 100% { transform: rotate(-4deg) translateY(0); }
        50% { transform: rotate(4deg) translateY(-4px); }
    }

    @keyframes treelink-leaf-spin {
        from { transform: rotate(0deg) translateX(31px) rotate(0deg); }
        to { transform: rotate(360deg) translateX(31px) rotate(-360deg); }
    }

    @keyframes treelink-loader-pulse {
        0%, 100% { opacity: .55; transform: scale(.88); }
        50% { opacity: 1; transform: scale(1.12); }
    }

    .treelink-tree-sway { transform-origin: 50% 90%; animation: treelink-tree-sway 1.4s ease-in-out infinite; }
    .treelink-leaf-orbit { transform-origin: 50% 50%; animation: treelink-leaf-spin 2.2s linear infinite; }
    .treelink-loader-dot { transform-origin: 50% 50%; animation: treelink-loader-pulse 1s ease-in-out infinite; }
    @media (prefers-reduced-motion: reduce) {
        .treelink-tree-sway, .treelink-leaf-orbit, .treelink-loader-dot { animation: none; }
    }
</style>

<div id="page-loading-overlay"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/45 px-6 backdrop-blur-xl"
     role="status"
     aria-live="polite"
     aria-label="Memuat halaman">
    <div class="flex w-64 flex-col items-center justify-center rounded-3xl bg-white/95 px-8 py-8 text-center shadow-2xl ring-1 ring-white/70">
        <div class="relative h-24 w-24" aria-hidden="true">
            <svg class="treelink-tree-sway absolute inset-0 h-24 w-24" viewBox="0 0 96 96" fill="none">
                <path d="M47 87c1-15 1-29 0-43" stroke="#5b3a29" stroke-width="7" stroke-linecap="round"/>
                <path d="M48 63 35 51M48 54l14-15" stroke="#5b3a29" stroke-width="4" stroke-linecap="round"/>
                <path d="M48 43c-8-15-25-14-30-3-4 9 4 17 14 16 7 0 12-4 16-13Z" fill="#8bd52f"/>
                <path d="M49 39c4-17 22-22 31-12 7 8 1 19-9 21-8 2-15-1-22-9Z" fill="#c8ff4d"/>
                <path d="M49 51c-3-12-14-19-23-14-8 5-5 16 4 20 7 3 14 1 19-6Z" fill="#4d9f36"/>
                <path d="M47 32c-1-9 7-17 16-16 8 1 10 10 4 16-5 5-12 5-20 0Z" fill="#75c83c"/>
            </svg>
            <span class="treelink-leaf-orbit absolute left-1/2 top-1/2 h-3 w-3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#c8ff4d] shadow-[0_0_12px_#c8ff4d]"></span>
            <span class="treelink-loader-dot absolute bottom-1 left-1/2 h-2 w-2 -translate-x-1/2 rounded-full bg-[#879366]"></span>
        </div>
        <p class="mt-5 text-base font-extrabold text-[#14213d]">Memuat...</p>
        <p class="mt-1 text-xs text-slate-500">TreeLink sedang menyiapkan halaman</p>
    </div>
</div>

<script>
    (() => {
        const overlay = document.getElementById('page-loading-overlay');
        let isLoading = false;

        const showLoading = () => {
            if (!overlay || isLoading) return;
            isLoading = true;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            document.documentElement.setAttribute('aria-busy', 'true');
        };

        const hideLoading = () => {
            if (!overlay) return;
            isLoading = false;
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            document.documentElement.removeAttribute('aria-busy');
        };

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || form.dataset.noLoading !== undefined) return;
            if (isLoading) {
                event.preventDefault();
                return;
            }
            showLoading();
        });

        document.addEventListener('click', (event) => {
            const link = event.target.closest('a');
            if (!link || link.dataset.noLoading !== undefined || link.target === '_blank' || link.hasAttribute('download')) return;
            if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

            let url;
            try {
                url = new URL(href, window.location.href);
            } catch {
                return;
            }

            if (url.origin !== window.location.origin) return;
            if (isLoading) {
                event.preventDefault();
                return;
            }
            showLoading();
        });

        window.addEventListener('pageshow', hideLoading);
    })();
</script>
