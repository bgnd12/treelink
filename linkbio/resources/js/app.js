import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Sortable from 'sortablejs';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/**
 * Drag & drop reordering for the dashboard "Links" page.
 * Uses SortableJS on #links-list and posts the new order to the backend.
 */
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('links-list');

    if (!list) {
        return;
    }

    const reorderUrl = list.dataset.reorderUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 200,
        ghostClass: 'opacity-40',
        onEnd: function () {
            const order = Array.from(list.querySelectorAll('[data-id]')).map((el) => el.dataset.id);

            fetch(reorderUrl || '/dashboard/links/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify({ order }),
            }).catch((err) => console.error('Gagal menyimpan urutan link:', err));
        },
    });
});
