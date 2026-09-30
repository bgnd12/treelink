<?php $__env->startSection('title', 'Overview'); ?>
<?php $__env->startSection('page-title', 'Platform Overview'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <?php
            $cards = [
                ['label' => 'Total User', 'value' => number_format($stats['total_users']), 'icon' => '👥', 'color' => 'from-brand-500 to-indigo-600'],
                ['label' => 'User Aktif', 'value' => number_format($stats['active_users']), 'icon' => '✅', 'color' => 'from-emerald-500 to-teal-600'],
                ['label' => 'User Nonaktif', 'value' => number_format($stats['inactive_users']), 'icon' => '⛔', 'color' => 'from-rose-500 to-red-600'],
                ['label' => 'User Baru (7 Hari)', 'value' => number_format($stats['new_users_7d']), 'icon' => '🆕', 'color' => 'from-orange-400 to-pink-500'],
                ['label' => 'Total Link Dibuat', 'value' => number_format($stats['total_links']), 'icon' => '🔗', 'color' => 'from-fuchsia-500 to-rose-500'],
                ['label' => 'Total Kunjungan Profil', 'value' => number_format($stats['total_views']), 'icon' => '👁️', 'color' => 'from-sky-500 to-blue-600'],
                ['label' => 'Total Klik Link', 'value' => number_format($stats['total_clicks']), 'icon' => '🖱️', 'color' => 'from-violet-500 to-purple-600'],
            ];
        ?>
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?php echo e($card['color']); ?> text-white flex items-center justify-center text-lg"><?php echo e($card['icon']); ?></div>
                <p class="mt-4 text-2xl font-extrabold text-ink-900"><?php echo e($card['value']); ?></p>
                <p class="text-sm text-ink-500"><?php echo e($card['label']); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-8 grid lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-ink-900">User Terbaru</h2>
                <a href="<?php echo e(route('admin.users')); ?>" class="text-sm font-semibold text-brand-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
            <?php $__currentLoopData = $latestUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between py-3 <?php echo e(!$loop->last ? 'border-b border-ink-50' : ''); ?>">
                    <div class="min-w-0">
                        <p class="font-semibold text-sm text-ink-900 truncate"><?php echo e($u->name); ?></p>
                        <p class="text-xs text-ink-400 truncate">/<?php echo e($u->username); ?> &middot; <?php echo e($u->links_count); ?> link</p>
                    </div>
                    <a href="<?php echo e(route('admin.users.show', $u)); ?>" class="text-xs font-semibold text-brand-600 hover:underline flex-shrink-0">Detail</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
            <h2 class="font-bold text-ink-900 mb-5">Profil Paling Banyak Dikunjungi</h2>
            <?php $__empty_1 = true; $__currentLoopData = $mostViewedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between py-3 <?php echo e(!$loop->last ? 'border-b border-ink-50' : ''); ?>">
                    <div class="min-w-0">
                        <p class="font-semibold text-sm text-ink-900 truncate"><?php echo e($u->name); ?></p>
                        <p class="text-xs text-ink-400 truncate">/<?php echo e($u->username); ?></p>
                    </div>
                    <span class="text-sm font-bold text-ink-700 flex-shrink-0"><?php echo e(number_format($u->profile_views_count)); ?> views</span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-ink-400 text-center py-6">Belum ada data kunjungan.</p>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>