<?php $__env->startSection('title', 'Semua User'); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-900 tracking-tight">Manajemen User</h1>
            <p class="text-ink-500 font-medium mt-1">Kelola semua pengguna terdaftar di sistem TreeLink.</p>
        </div>
        
        <form method="GET" action="<?php echo e(route('admin.users')); ?>" class="relative max-w-sm w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Cari nama, email, atau username..."
                   class="w-full bg-white border border-ink-200 rounded-full pl-11 pr-4 py-2.5 text-sm font-medium text-ink-900 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition-all shadow-sm">
        </form>
    </div>

    <div class="bg-white rounded-[20px] shadow-sm border border-ink-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-ink-50/50 text-ink-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4 text-center">Links</th>
                        <th class="px-6 py-4 text-center">Views</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-50">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-ink-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($u->name)); ?>&background=random" class="w-12 h-12 rounded-2xl" alt="<?php echo e($u->name); ?>">
                                    <div>
                                        <p class="font-bold text-ink-900 text-base"><?php echo e($u->name); ?></p>
                                        <a href="<?php echo e($u->publicUrl()); ?>" target="_blank" class="text-xs font-semibold text-brand-600 hover:underline">/<?php echo e($u->username); ?></a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-ink-600 font-medium"><?php echo e($u->email); ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 font-bold">
                                    <?php echo e($u->links_count); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-sky-50 text-sky-700 font-bold">
                                    <?php echo e(number_format($u->profile_views_count)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($u->is_admin): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-50 text-brand-700 text-xs font-bold border border-brand-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        Admin
                                    </span>
                                <?php elseif($u->is_active): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo e(route('admin.users.show', $u)); ?>" class="p-2 text-ink-500 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    
                                    <?php if (! ($u->is_admin)): ?>
                                        <div class="w-px h-5 bg-ink-200 mx-1"></div>
                                        <form method="POST" action="<?php echo e(route('admin.users.toggle', $u)); ?>" class="inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="p-2 rounded-xl transition <?php echo e($u->is_active ? 'text-rose-500 hover:bg-rose-50' : 'text-emerald-500 hover:bg-emerald-50'); ?>" title="<?php echo e($u->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                                <?php if($u->is_active): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                                <?php else: ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <?php endif; ?>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $u)); ?>" onsubmit="return confirm('Hapus user <?php echo e($u->name); ?> beserta seluruh datanya? Tindakan ini tidak bisa dibatalkan.');" class="inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 text-ink-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Hapus Permanen">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-ink-50 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-ink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                                <p class="text-base font-bold text-ink-900">Tidak ada user ditemukan</p>
                                <p class="text-sm text-ink-500 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($users->hasPages()): ?>
        <div class="px-6 py-5 border-t border-ink-100 bg-ink-50/30">
            <?php echo e($users->links()); ?>

        </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/admin/users.blade.php ENDPATH**/ ?>