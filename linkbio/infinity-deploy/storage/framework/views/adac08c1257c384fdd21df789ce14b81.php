<?php $__env->startSection('title', 'Semua User'); ?>
<?php $__env->startSection('page-title', 'Semua User'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
        <form method="GET" action="<?php echo e(route('admin.users')); ?>" class="mb-6">
            <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Cari nama, username, atau email..."
                   class="w-full sm:w-96 px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-ink-400 border-b border-ink-100">
                        <th class="pb-3 font-semibold">User</th>
                        <th class="pb-3 font-semibold">Email</th>
                        <th class="pb-3 font-semibold text-center">Link</th>
                        <th class="pb-3 font-semibold text-center">Kunjungan</th>
                        <th class="pb-3 font-semibold text-center">Status</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-ink-50 last:border-0">
                            <td class="py-3.5">
                                <p class="font-semibold text-ink-900"><?php echo e($u->name); ?></p>
                                <p class="text-xs text-ink-400">/<?php echo e($u->username); ?></p>
                            </td>
                            <td class="py-3.5 text-ink-600"><?php echo e($u->email); ?></td>
                            <td class="py-3.5 text-center font-semibold text-ink-700"><?php echo e($u->links_count); ?></td>
                            <td class="py-3.5 text-center font-semibold text-ink-700"><?php echo e($u->profile_views_count); ?></td>
                            <td class="py-3.5 text-center">
                                <?php if($u->is_admin): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold">Admin</span>
                                <?php elseif($u->is_active): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">Aktif</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo e(route('admin.users.show', $u)); ?>" class="px-3 py-1.5 rounded-lg border border-ink-200 text-xs font-semibold hover:border-ink-400 transition">Detail</a>
                                    <?php if (! ($u->is_admin)): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.toggle', $u)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border text-xs font-semibold transition <?php echo e($u->is_active ? 'border-rose-200 text-rose-600 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50'); ?>">
                                                <?php echo e($u->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $u)); ?>" onsubmit="return confirm('Hapus user <?php echo e($u->name); ?> beserta seluruh datanya?');">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-ink-200 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="py-10 text-center text-ink-400">Tidak ada user ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <?php echo e($users->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/admin/users.blade.php ENDPATH**/ ?>