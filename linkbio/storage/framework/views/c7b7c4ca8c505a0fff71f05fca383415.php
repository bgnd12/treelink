<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Buat halaman link-in-bio kamu sendiri dan bagikan semua tautan penting hanya lewat satu link.'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-white text-ink-900">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/layouts/guest.blade.php ENDPATH**/ ?>