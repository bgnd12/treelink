<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> &mdash; <?php echo e(config('app.name')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-[#F7F7FA] text-ink-900" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 inset-y-0 left-0 z-50 w-[280px] bg-[#0A0B1A] text-white flex flex-col transition-transform duration-300 h-screen shadow-2xl lg:shadow-none">
            
            <!-- Logo Area -->
            <div class="h-[88px] flex items-center px-8">
                <a href="<?php echo e(route('admin.index')); ?>" class="flex items-center gap-3 font-extrabold text-xl tracking-tight">
                    <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-lg shadow-lg shadow-brand-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Admin Panel
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-ink-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden scrollbar-hide">
                <div class="px-4 mb-2 text-[11px] font-bold text-ink-500 uppercase tracking-wider">Menu Utama</div>
                
                <?php
                    $navItems = [
                        ['route' => 'admin.index', 'label' => 'Overview', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />'],
                        ['route' => 'admin.users', 'label' => 'Semua User', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />'],
                    ];
                ?>
                
                <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($item['route'])); ?>"
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-[14.5px] font-semibold transition-all duration-200
                       <?php echo e(request()->routeIs($item['route']) || request()->routeIs('admin.users.show') ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-ink-400 hover:bg-white/5 hover:text-white'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 <?php echo e(request()->routeIs($item['route']) || request()->routeIs('admin.users.show') ? 'text-white' : 'text-ink-400'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <?php echo $item['icon']; ?>

                        </svg>
                        <?php echo e($item['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <!-- Bottom Profile / Actions -->
            <div class="p-6">
                <div class="bg-white/5 rounded-2xl p-4 mb-4">
                    <a href="<?php echo e(route('dashboard.index')); ?>" class="flex items-center gap-3 text-sm font-semibold text-brand-300 hover:text-brand-200 transition">
                        <div class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        </div>
                        Kembali ke App
                    </a>
                </div>
                
                <div class="flex items-center gap-3 mb-4 px-2">
                    <img src="https://ui-avatars.com/api/?name=Super+Admin&background=random" class="w-10 h-10 rounded-full border border-white/20" alt="Admin">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">Super Admin</p>
                        <p class="text-xs text-ink-400 truncate">Administrator</p>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-white/10 text-sm font-bold text-ink-300 hover:bg-rose-500/10 hover:text-rose-400 hover:border-rose-500/20 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay -->
        <div @click="sidebarOpen = false" x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-[#0A0B1A]/60 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto">
            <!-- Topbar -->
            <header class="h-[88px] bg-white/80 backdrop-blur-md border-b border-ink-100 flex items-center justify-between px-6 sm:px-10 sticky top-0 z-30">
                <div class="flex items-center gap-4 flex-1">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 text-ink-500 hover:text-ink-900 rounded-lg hover:bg-ink-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Breadcrumbs -->
                    <div class="hidden sm:flex items-center gap-2 text-sm font-semibold">
                        <span class="text-ink-400">Admin</span>
                        <span class="text-ink-300">/</span>
                        <span class="text-ink-900"><?php echo $__env->yieldContent('title', 'Overview'); ?></span>
                    </div>
                </div>

                <!-- Center Search (Optional/Decorative) -->
                <div class="hidden md:flex flex-1 max-w-md mx-6">
                    <div class="relative w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" placeholder="Cari apapun..." class="w-full bg-[#F7F7FA] border-0 rounded-full pl-12 pr-4 py-2.5 text-sm font-medium text-ink-900 focus:ring-2 focus:ring-brand-500/20 placeholder-ink-400 transition-shadow">
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4 flex-1 justify-end">
                    <button class="relative p-2 text-ink-400 hover:text-ink-700 bg-[#F7F7FA] rounded-full hover:bg-ink-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span class="absolute top-1 right-1.5 w-2 h-2 rounded-full bg-rose-500 border border-white"></span>
                    </button>
                    
                    <div class="h-8 w-px bg-ink-200 hidden sm:block"></div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-ink-900 leading-tight">Super Admin</p>
                            <p class="text-[11px] font-semibold text-brand-600">Administrator</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Super+Admin&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm" alt="Admin">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 sm:p-10">
                <?php if(session('status')): ?>
                    <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 text-sm font-bold animate-fade-up">
                        <div class="w-8 h-8 rounded-full bg-emerald-200/50 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>
                
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/layouts/admin.blade.php ENDPATH**/ ?>