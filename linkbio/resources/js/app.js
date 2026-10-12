import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Sortable from 'sortablejs';
import editor from './editor';
import stickmanThemeToggle from './stickman-toggle';
import '../css/stickman-toggle.css';

// Keep a reference so the bundler never drops the import above.
window.Sortable = Sortable;

Alpine.plugin(collapse);
Alpine.data('editor', editor);
Alpine.data('stickmanThemeToggle', stickmanThemeToggle);

window.Alpine = Alpine;

Alpine.start();