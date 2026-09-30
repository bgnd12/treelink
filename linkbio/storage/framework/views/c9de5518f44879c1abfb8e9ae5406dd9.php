<?php $__env->startSection('title', 'Links'); ?>
<?php $__env->startSection('page-title', 'Links'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid lg:grid-cols-3 gap-8">

    
    <div class="lg:col-span-2 space-y-6">

        
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <h2 class="font-bold text-ink-900 mb-4">Tambah Link Baru</h2>
            <form method="POST" action="<?php echo e(route('dashboard.links.store')); ?>" class="grid sm:grid-cols-2 gap-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Judul</label>
                    <input type="text" name="title" placeholder="Contoh: Instagram Saya" required value="<?php echo e(old('title')); ?>"
                           class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">URL</label>
                    <input type="text" name="url" placeholder="https://instagram.com/kamu" required value="<?php echo e(old('url')); ?>"
                           class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Icon</label>
                    <select name="icon" class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                        <?php $__currentLoopData = $availableIcons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($icon); ?>"><?php echo e(ucfirst(str_replace('-', ' ', $icon))); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit" class="sm:col-span-2 py-3 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
                    + Tambahkan Link
                </button>
            </form>
        </div>

        
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-ink-900">Link Kamu (<?php echo e($links->count()); ?>)</h2>
                <p class="text-xs text-ink-400">Seret <span class="font-semibold">⠿</span> untuk mengubah urutan</p>
            </div>

            <ul id="links-list" data-reorder-url="<?php echo e(route('dashboard.links.reorder')); ?>" class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li data-id="<?php echo e($link->id); ?>" class="link-item flex items-center gap-3 p-4 rounded-xl border border-ink-100 bg-ink-50/40 <?php echo e(!$link->is_active ? 'opacity-50' : ''); ?>">
                        <span class="drag-handle cursor-grab active:cursor-grabbing text-ink-300 text-lg select-none">⠿</span>
                        <span class="w-9 h-9 rounded-lg bg-white border border-ink-100 flex items-center justify-center text-sm flex-shrink-0">
                            <?php echo \App\Support\Brands::render($link->url, $link->icon, 'w-5 h-5'); ?>

                        </span>

                        <div class="flex-1 min-w-0" x-data="{ editing: false }">
                            <div x-show="!editing">
                                <p class="font-semibold text-sm text-ink-900 truncate"><?php echo e($link->title); ?></p>
                                <p class="text-xs text-ink-400 truncate max-w-xs"><?php echo e($link->url); ?></p>
                            </div>
                            <form x-show="editing" x-cloak method="POST" action="<?php echo e(route('dashboard.links.update', $link)); ?>" class="flex flex-col sm:flex-row gap-2">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="text" name="title" value="<?php echo e($link->title); ?>" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                <input type="text" name="url" value="<?php echo e($link->url); ?>" class="flex-1 px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                <select name="icon" class="px-3 py-2 rounded-lg border border-ink-200 text-sm outline-none focus:border-brand-500">
                                    <?php $__currentLoopData = $availableIcons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($icon); ?>" <?php if($link->icon === $icon): echo 'selected'; endif; ?>><?php echo e(ucfirst($icon)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button type="submit" class="px-3 py-2 rounded-lg bg-brand-600 text-white text-sm font-semibold">Simpan</button>
                            </form>

                            <div class="flex items-center gap-3 mt-2 text-xs">
                                <button type="button" @click="editing = !editing" class="font-semibold text-brand-600 hover:underline" x-text="editing ? 'Batal' : 'Edit'"></button>
                                <span class="text-ink-300">•</span>
                                <span class="text-ink-500"><?php echo e($link->clicks_count); ?> klik</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form method="POST" action="<?php echo e(route('dashboard.links.toggle', $link)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="w-11 h-6 rounded-full flex items-center px-0.5 transition <?php echo e($link->is_active ? 'bg-brand-600 justify-end' : 'bg-ink-200 justify-start'); ?>">
                                    <span class="w-5 h-5 rounded-full bg-white shadow"></span>
                                </button>
                            </form>
                            <form method="POST" action="<?php echo e(route('dashboard.links.destroy', $link)); ?>" onsubmit="return confirm('Hapus link ini?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-9 h-9 rounded-lg text-rose-500 hover:bg-rose-50 transition flex items-center justify-center">🗑️</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-center py-10 text-ink-400 text-sm">Belum ada link. Tambahkan link pertamamu di atas!</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    
    <div>
        <div class="sticky top-24">
            <p class="text-sm font-semibold text-ink-500 mb-3 text-center">Live Preview</p>
            <?php echo $__env->make('components.phone-preview', ['profile' => $profile, 'links' => $links, 'user' => auth()->user()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/dashboard/links.blade.php ENDPATH**/ ?>