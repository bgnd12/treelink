<?php $__env->startSection('title', 'Editor'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid lg:grid-cols-[minmax(0,1fr)_340px] xl:grid-cols-[minmax(0,1fr)_380px] gap-8 items-start"
    x-data='editor(<?php echo json_encode($editor, 15, 512) ?>)'>

    
    <div class="min-w-0 space-y-6">

        
        <div x-show="toast" x-transition.opacity.duration.200ms
             class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-2xl text-sm font-semibold shadow-2xl border"
             :class="toastType === 'err' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-white text-ink-800 border-ink-200'">
            <p x-text="toast"></p>
        </div>

        
        <nav class="flex lg:sticky lg:top-20 gap-1.5 p-1.5 rounded-2xl bg-white border border-ink-100 shadow-card overflow-x-auto no-scrollbar">
            <?php
                $nav = [
                    'content' => ['label' => 'Content', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
                    'header' => ['label' => 'Header', 'icon' => 'M5.121 17.804A13.937 13.937 0 0 1 12 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                    'design' => ['label' => 'Design', 'icon' => 'M4.098 19.902a3.75 3.75 0 0 0 5.304 0l6.401-6.402 1.238-3.429a.75.75 0 0 0-.93-.93l-3.428 1.238-6.402 6.402a3.75 3.75 0 0 0 0 5.121Z M13.5 4.353A4.5 4.5 0 1 1 19.647 10.5L18 12'],
                    'enhance' => ['label' => 'Enhance', 'icon' => 'M12 3v2M12 19v2M5.64 5.64l1.42 1.42M16.94 16.94l1.41 1.41M3 12h2M19 12h2M5.64 18.36l1.42-1.42M16.94 7.06l1.41-1.41M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm0 0c.026-.74.1-1.308.294-1.935.216-.7.573-1.277 1.03-1.682M12 8c0 0 0 0 0 0'],
                ];
            ?>
            <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" @click="setTab('<?php echo e($key); ?>')"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition shrink-0"
                        :class="tab === '<?php echo e($key); ?>' ? 'bg-brand-600 text-white shadow-soft' : 'text-ink-600 hover:bg-ink-50'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($item['icon']); ?>" />
                    </svg>
                    <?php echo e($item['label']); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        
        <section x-show="tab === 'content'" x-cloak x-transition.opacity class="space-y-5">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-extrabold text-ink-900">Content</h1>
                    <p class="text-sm text-ink-500 mt-0.5">Kelola tautan dan produk kamu.</p>
                </div>
            </div>

            
            <div class="inline-flex p-1 rounded-xl bg-ink-100/70 gap-1">
                <button type="button" @click="setContentTab('links')"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition"
                        :class="content_tab === 'links' ? 'bg-white text-ink-900 shadow-card' : 'text-ink-500 hover:text-ink-700'">
                    Links
                </button>
                <button type="button" @click="setContentTab('shop')"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition"
                        :class="content_tab === 'shop' ? 'bg-white text-ink-900 shadow-card' : 'text-ink-500 hover:text-ink-700'">
                    Shop
                </button>
            </div>

            <div x-show="content_tab === 'links'">
                <?php echo $__env->make('dashboard.editor-panels.content-links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <div x-show="content_tab === 'shop'" x-cloak>
                <?php echo $__env->make('dashboard.editor-panels.content-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </section>

        
        <section x-show="tab === 'header'" x-cloak x-transition.opacity>
            <?php echo $__env->make('dashboard.editor-panels.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>

        
        <section x-show="tab === 'design'" x-cloak x-transition.opacity>
            <?php echo $__env->make('dashboard.editor-panels.design', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>

        
        <section x-show="tab === 'enhance'" x-cloak x-transition.opacity>
            <?php echo $__env->make('dashboard.editor-panels.enhance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>
    </div>

    
    <div>
        <div class="lg:sticky lg:top-20">
            <p class="text-xs font-semibold uppercase tracking-wider text-ink-400 text-center mb-3 lg:hidden">Pratinjau — geser ke bawah</p>
            <?php echo $__env->make('dashboard.editor-panels.phone-preview', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.editor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\linktree-laravel\resources\views/dashboard/editor.blade.php ENDPATH**/ ?>