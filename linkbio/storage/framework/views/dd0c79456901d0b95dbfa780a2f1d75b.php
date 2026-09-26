<?php $__env->startSection('title', 'Halaman Tidak Ditemukan'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-6 bg-gradient-to-b from-brand-50 to-white">
    <div class="text-center max-w-md">
        <p class="text-7xl font-extrabold text-brand-600">404</p>
        <h1 class="mt-4 text-2xl font-bold text-ink-900">Halaman atau username tidak ditemukan</h1>
        <p class="mt-2 text-ink-500">Sepertinya halaman yang kamu cari tidak ada, atau username tersebut belum digunakan siapa pun.</p>
        <a href="<?php echo e(route('home')); ?>" class="mt-8 inline-block px-6 py-3 rounded-full bg-brand-600 text-white font-semibold hover:bg-brand-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/errors/404.blade.php ENDPATH**/ ?>