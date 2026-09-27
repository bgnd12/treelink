<?php $__env->startSection('title', config('app.name').' — Find Your People, Build Your Future'); ?>
<?php $__env->startSection('description', 'TreeLink helps students discover friends, mentors, and communities that help them grow.'); ?>

<?php $__env->startSection('content'); ?>
<div class="landing-page">
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <section class="hero-stage relative overflow-hidden pt-28 pb-20 sm:pt-36 sm:pb-28">
        <div class="hero-grid absolute inset-0 -z-10"></div>
        <div class="max-w-7xl min-h-[calc(100svh-7rem)] mx-auto px-5 sm:px-8 grid md:grid-cols-[.92fr_1.08fr] gap-8 xl:gap-16 items-center">
            <div class="animate-fade-up max-w-xl">
                <p class="hero-kicker"><span></span> <?php echo e(__('CONNECT · LEARN · GROW')); ?></p>
                <h1 class="hero-title mt-6"><?php echo __('Find your people,<br><em>build your future.</em>'); ?></h1>
                <p class="mt-6 text-lg text-ink-600 max-w-lg leading-relaxed">
                    <?php echo e(__('TreeLink connects students, mentors, and learning communities. Meet new people, discover new opportunities, and grow together.')); ?>

                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo e(route('register')); ?>" class="hero-primary"><?php echo e(__('Get started')); ?> <span>↗</span></a>
                    <a href="#features" class="hero-secondary"><?php echo e(__('Explore features')); ?> <span>↓</span></a>
                </div>
                <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-semibold text-ink-500">
                    <span><b class="hero-check">✓</b> <?php echo e(__('Meet new people')); ?></span>
                    <span><b class="hero-check">✓</b> <?php echo e(__('Learn with mentors')); ?></span>
                </div>
            </div>

            
            <div class="hero-art relative min-h-[530px] sm:min-h-[590px] animate-fade-up" style="animation-delay:.15s">
                <div class="hero-note hero-note-top"><span class="hero-dot"></span><div><b><?php echo e(__('LIVE PREVIEW')); ?></b><small><?php echo e(__('See every connection grow')); ?></small></div></div>
                <div class="hero-note hero-note-bottom"><span class="hero-spark">↗</span><div><b><?php echo e(__('+24% this week')); ?></b><small><?php echo e(__('New people discovered')); ?></small></div></div>
                <div class="hero-student-image"><img src="<?php echo e(asset('images/hero-portrait.jpg')); ?>" alt="TreeLink community member" width="900" height="1350"></div>
                <div class="hero-note hero-note-students"><span class="hero-spark">✦</span><div><b><?php echo e(__('12.5K+')); ?></b><small><?php echo e(__('active students')); ?></small></div></div>

                <div class="hero-profile-card">
                    <div class="hero-profile-head">
                        <div class="hero-avatar">T</div>
                        <div class="hero-profile-meta"><b>tree.link</b><span><?php echo e(__('student community')); ?></span></div>
                        <span class="hero-menu">•••</span>
                    </div>
                    <div class="hero-profile-intro"><strong><?php echo __('Better<br>together.'); ?></strong><span>↗</span></div>
                    <div class="hero-link hero-link-featured"><i class="hero-icon hero-icon-lime">✦</i><span><?php echo e(__('Join communities')); ?></span><b>↗</b></div>
                    <div class="hero-link"><i class="hero-icon hero-icon-dark">◎</i><span><?php echo e(__('Find a mentor')); ?></span><b>↗</b></div>
                    <div class="hero-link"><i class="hero-icon hero-icon-red">⌁</i><span><?php echo e(__('Explore opportunities')); ?></span><b>↗</b></div>
                    <div class="hero-profile-footer"><span>treelink.app/connect</span><span>♡</span></div>
                </div>

                <div class="hero-mini-card hero-mini-share"><span>✦</span><b><?php echo e(__('Better together')); ?></b><small><?php echo e(__('grow with your people')); ?></small></div>
                <div class="hero-mini-card hero-mini-custom"><span class="hero-mini-number">↗</span><b><?php echo e(__('Join communities')); ?></b><small><?php echo e(__('learn, share, grow')); ?></small></div>
            </div>
        </div>
    </section>

    
    <section id="features" class="landing-features py-24">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <p class="hero-kicker justify-center"><span></span> <?php echo e(__('WHY TREELINK?')); ?></p>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-ink-900 mt-4"><?php echo e(__('Why TreeLink?')); ?></h2>
                <p class="mt-4 text-ink-600"><?php echo e(__('You do not have to figure everything out alone. Connect, learn, and grow with people who get you.')); ?></p>
            </div>

            <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <?php
                        $features = [
                            ['icon' => '✦', 'title' => 'Connect with New Friends', 'desc' => 'Meet people who share your interests and goals.'],
                            ['icon' => '▣', 'title' => 'Access Mentors & Learning', 'desc' => 'Learn from people who have already walked the path.'],
                            ['icon' => '⌁', 'title' => 'Join Communities', 'desc' => 'Discover communities built around your passions.'],
                            ['icon' => '◎', 'title' => 'Achieve Your Goals Together', 'desc' => 'Turn small conversations into meaningful progress.'],
                        ];
                ?>

                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="landing-feature-card p-7 rounded-2xl border hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 flex items-center justify-center text-2xl"><?php echo e($feature['icon']); ?></div>
                        <h3 class="mt-5 font-bold text-lg text-ink-900"><?php echo e(__($feature['title'])); ?></h3>
                        <p class="mt-2 text-sm text-ink-600 leading-relaxed"><?php echo e(__($feature['desc'])); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <section id="how-it-works" class="landing-steps py-24">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <p class="hero-kicker justify-center"><span></span> <?php echo e(__('SIMPLE BY DESIGN')); ?></p>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-ink-900 mt-4"><?php echo e(__('How TreeLink Works')); ?></h2>
                <p class="mt-4 text-ink-600"><?php echo e(__('Simple, clear, and designed around your next step.')); ?></p>
            </div>

            <div class="mt-16 grid md:grid-cols-3 gap-8">
                <?php
                    $steps = [
                        ['num' => '01', 'title' => 'Create an Account', 'desc' => 'Set up your space in a few simple steps.'],
                        ['num' => '02', 'title' => 'Explore & Discover', 'desc' => 'Find friends, mentors, and communities that match your interests.'],
                        ['num' => '03', 'title' => 'Start Connecting', 'desc' => 'Join in, learn together, and keep growing.'],
                    ];
                ?>
                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="landing-step-card relative p-8 rounded-2xl text-center">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto shadow-soft">
                            <?php echo e($step['num']); ?>

                        </div>
                        <h3 class="mt-5 font-bold text-lg text-ink-900"><?php echo e(__($step['title'])); ?></h3>
                        <p class="mt-2 text-sm text-ink-600 leading-relaxed"><?php echo e(__($step['desc'])); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <section id="contact" class="landing-cta py-24">
        <div class="max-w-5xl mx-auto px-5 sm:px-8">
            <div class="relative overflow-hidden rounded-3xl px-8 py-16 sm:px-16 text-center shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white"><?php echo __('Ready to be part of <span class="text-lime-300">TreeLink?</span>'); ?></h2>
                <p class="mt-4 text-brand-100 max-w-xl mx-auto"><?php echo e(__('Start your journey today and discover communities that help you grow.')); ?></p>
                <a href="<?php echo e(route('register')); ?>" class="mt-8 inline-block px-8 py-4 rounded-full bg-lime-300 text-ink-900 font-bold shadow-lg hover:-translate-y-0.5 hover:shadow-xl transition-all">
                    <?php echo e(__('Get Started')); ?> ↗
                </a>
            </div>
        </div>
    </section>

    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/welcome.blade.php ENDPATH**/ ?>