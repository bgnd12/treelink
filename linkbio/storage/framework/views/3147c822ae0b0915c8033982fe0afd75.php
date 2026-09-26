<nav x-data="{ open: false }" class="fixed top-0 inset-x-0 z-50 backdrop-blur-lg bg-white/70 border-b border-ink-100">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 font-extrabold text-lg text-ink-900">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                Tree<span class="text-brand-600">Link</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-ink-600">
                <a href="<?php echo e(route('home')); ?>#features" class="hover:text-ink-900 transition"><?php echo e(__('Fitur')); ?></a>
                <a href="<?php echo e(route('home')); ?>#how-it-works" class="hover:text-ink-900 transition"><?php echo e(__('Cara Kerja')); ?></a>
                <a href="<?php echo e(route('home')); ?>#faq" class="hover:text-ink-900 transition"><?php echo e(__('FAQ')); ?></a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="flex items-center gap-2 mr-4 border-r border-ink-200 pr-4">
                    <a href="<?php echo e(route('locale.set', 'id')); ?>" class="text-xs font-bold <?php echo e(session('locale', 'id') == 'id' ? 'text-brand-600' : 'text-ink-400 hover:text-ink-600'); ?>">ID</a>
                    <span class="text-ink-300">/</span>
                    <a href="<?php echo e(route('locale.set', 'en')); ?>" class="text-xs font-bold <?php echo e(session('locale', 'id') == 'en' ? 'text-brand-600' : 'text-ink-400 hover:text-ink-600'); ?>">EN</a>
                </div>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(auth()->user()->is_admin ? route('admin.index') : route('dashboard.index')); ?>"
                       class="px-4 py-2 rounded-full text-sm font-semibold bg-ink-900 text-white hover:bg-ink-800 transition">
                        <?php echo e(__('Ke Dashboard')); ?>

                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-sm font-semibold text-ink-700 hover:text-ink-900 transition"><?php echo e(__('Masuk')); ?></a>
                    <a href="<?php echo e(route('register')); ?>"
                       class="px-4 py-2 rounded-full text-sm font-semibold bg-brand-600 text-white shadow-soft hover:bg-brand-700 transition">
                        <?php echo e(__('Daftar Gratis')); ?>

                    </a>
                <?php endif; ?>
            </div>

            <button @click="open = !open" class="md:hidden p-2 text-ink-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="md:hidden pb-4 flex flex-col gap-3 text-sm font-medium text-ink-700">
            <a href="<?php echo e(route('home')); ?>#features" class="py-1">Fitur</a>
            <a href="<?php echo e(route('home')); ?>#how-it-works" class="py-1">Cara Kerja</a>
            <a href="<?php echo e(route('home')); ?>#faq" class="py-1">FAQ</a>
            <div class="flex gap-3 pt-2">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(auth()->user()->is_admin ? route('admin.index') : route('dashboard.index')); ?>" class="px-4 py-2 rounded-full bg-ink-900 text-white text-center flex-1">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 rounded-full border border-ink-200 text-center flex-1">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="px-4 py-2 rounded-full bg-brand-600 text-white text-center flex-1">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/components/navbar.blade.php ENDPATH**/ ?>