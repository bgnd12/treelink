

<?php $__env->startSection('title', __('Short Links')); ?>
<?php $__env->startSection('page-title', __('Short Links')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-6">
        <h2 class="text-xl font-bold mb-4"><?php echo e(__('Create New Short Link')); ?></h2>
        <form action="<?php echo e(route('dashboard.short-links.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1"><?php echo e(__('Destination URL')); ?></label>
                <input type="url" name="destination_url" required placeholder="https://example.com/very-long-url" class="w-full rounded-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1"><?php echo e(__('Custom Slug (Optional)')); ?></label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-ink-200 bg-ink-50 text-ink-500 text-sm">
                        <?php echo e(url('/')); ?>/
                    </span>
                    <input type="text" name="slug" placeholder="my-event" class="flex-1 rounded-r-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <p class="text-xs text-ink-400 mt-1"><?php echo e(__('Leave blank to generate randomly.')); ?></p>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition">
                <?php echo e(__('Create Link')); ?>

            </button>
        </form>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-ink-900"><?php echo e(__('Your Short Links')); ?></h3>
        <?php $__empty_1 = true; $__currentLoopData = $shortLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-5 flex items-center justify-between">
                <div class="flex-1 min-w-0 pr-4">
                    <a href="<?php echo e(url('/' . $link->slug)); ?>" target="_blank" class="font-bold text-brand-600 text-lg hover:underline block truncate">
                        <?php echo e(url('/' . $link->slug)); ?>

                    </a>
                    <p class="text-sm text-ink-500 truncate mt-1">➡ <?php echo e($link->destination_url); ?></p>
                    <div class="flex items-center gap-4 mt-3 text-xs font-semibold text-ink-400">
                        <span class="flex items-center gap-1"><span class="text-lg">📊</span> <?php echo e(number_format($link->clicks)); ?> <?php echo e(__('Clicks')); ?></span>
                        <span class="flex items-center gap-1"><span class="text-lg">📅</span> <?php echo e($link->created_at->format('M d, Y')); ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form action="<?php echo e(route('dashboard.short-links.toggle', $link)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="p-2 rounded-xl <?php echo e($link->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-ink-50 text-ink-400 hover:bg-ink-100'); ?>" title="<?php echo e(__('Toggle Status')); ?>">
                            <?php echo e($link->is_active ? '✅' : '❌'); ?>

                        </button>
                    </form>
                    <form action="<?php echo e(route('dashboard.short-links.destroy', $link)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this short link?')); ?>')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100" title="<?php echo e(__('Delete')); ?>">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12 bg-white rounded-2xl border border-ink-100 border-dashed">
                <div class="text-4xl mb-3">🔗</div>
                <h4 class="text-lg font-bold text-ink-900"><?php echo e(__('No short links yet')); ?></h4>
                <p class="text-sm text-ink-500 mt-1"><?php echo e(__('Create your first short link above.')); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/dashboard/short-links/index.blade.php ENDPATH**/ ?>