import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Sortable from 'sortablejs';
import editor from './editor';

// Keep a reference so the bundler never drops the import above.
window.Sortable = Sortable;

Alpine.plugin(collapse);
Alpine.data('editor', editor);

window.Alpine = Alpine;

Alpine.start();