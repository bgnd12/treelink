<?php
    $theme = \App\Models\Profile::AVAILABLE_THEMES[$profile->theme] ?? \App\Models\Profile::AVAILABLE_THEMES['aurora'];
    $btnClasses = match($profile->button_style) {
        'pill' => 'rounded-full',
        'square' => 'rounded-md',
        'outline' => 'rounded-xl border-2 border-white bg-transparent',
        default => 'rounded-xl',
    };
    $btnBg = $profile->button_style === 'outline' ? 'bg-transparent' : ($theme['text'] === 'text-white' ? 'bg-white/15 backdrop-blur' : 'bg-white');
    $featuredLink = $links->firstWhere('id', $profile->featured_link_id);
?>

<div class="mx-auto w-[280px]">
    <div class="rounded-[2.5rem] border-8 border-ink-900 bg-ink-900 shadow-2xl overflow-hidden">
        <div class="h-[520px] overflow-y-auto bg-gradient-to-b <?php echo e($theme['from']); ?> <?php echo e($theme['to']); ?> <?php echo e($theme['text']); ?> px-5 pt-9 pb-8 text-center">
            <img src="<?php echo e($profile->avatar_url); ?>" class="w-20 h-20 rounded-full object-cover mx-auto border-4 <?php echo e($theme['text'] === 'text-white' ? 'border-white/40' : 'border-black/10'); ?>" alt="Avatar">
            <p class="mt-3 font-bold"><?php echo e($profile->display_name ?: $user->name); ?></p>
            <p class="text-xs opacity-70">{{ $user->username }}</p>
            <?php if($profile->bio): ?>
                <p class="text-xs opacity-80 mt-2 px-2"><?php echo e($profile->bio); ?></p>
            <?php endif; ?>

            <?php if(!empty(array_filter($profile->social_links ?? []))): ?>
                <div class="flex justify-center flex-wrap gap-2 mt-4">
                    <?php $__currentLoopData = array_filter($profile->social_links ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="w-7 h-7 rounded-full <?php echo e($btnBg); ?> flex items-center justify-center text-xs">
                            <?php echo \App\Support\Brands::markup($platform === 'website' ? 'globe' : ($platform === 'email' ? 'mail' : $platform), 'w-3.5 h-3.5'); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <div class="mt-5 space-y-2.5">
                <?php $__empty_1 = true; $__currentLoopData = $links->where('is_active', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="w-full py-2.5 px-4 <?php echo e($btnClasses); ?> <?php echo e($btnBg); ?> text-xs font-semibold truncate <?php echo e($profile->animations_enabled ? 'animate-fade-up' : ''); ?> <?php echo e($featuredLink?->id === $link->id ? 'ring-2 ring-yellow-300 scale-[1.03]' : ''); ?>">
                        <?php if($featuredLink?->id === $link->id): ?>⭐ <?php endif; ?>
                        <?php echo \App\Support\Brands::render($link->url, $link->icon, 'w-4 h-4 inline-block align-[-2px]'); ?>

                        <?php echo e($link->title); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-xs opacity-60 mt-6">Belum ada link aktif</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <p class="text-center text-xs text-ink-400 mt-3">Tampilan ini diperbarui otomatis</p>
</div>
<?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/components/phone-preview.blade.php ENDPATH**/ ?>