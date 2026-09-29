<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($profile->seo_title ?: ($profile->display_name ?: $profileUser->name).' (@'.$profileUser->username.')'); ?></title>
    <meta name="description" content="<?php echo e($profile->seo_description ?: ($profile->bio ?: 'Lihat semua link '.$profileUser->name.' di satu halaman.')); ?>">

    
    <meta property="og:title" content="<?php echo e($profile->seo_title ?: ($profile->display_name ?: $profileUser->name)); ?>">
    <meta property="og:description" content="<?php echo e($profile->seo_description ?: $profile->bio); ?>">
    <meta property="og:image" content="<?php echo e($profile->avatar_url); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php
        $theme = $profile->themeConfig();
        $isLight = $theme['text'] !== 'text-white';
        $fontFamily = \App\Models\Profile::FONT_FAMILIES[$profile->font] ?? \App\Models\Profile::FONT_FAMILIES['sans'];

        // Background ------------------------------------------------------------------
        $bgType = $profile->background_type;
        $bgClass = '';
        $bgStyle = '';
        $bgOverlay = false;
        if ($bgType === 'gradient') {
            $bgClass = 'bg-gradient-to-b '.$theme['from'].' '.$theme['to'];
        } elseif ($bgType === 'solid') {
            $bgStyle = 'background-color:'.($profile->background_value ?: $theme['pattern_bg'] ?: '#7c4dff').';';
        } elseif ($bgType === 'pattern') {
            $bgClass = 'pattern-'.($profile->background_value ?: 'dots');
            $bgStyle = 'background-color:'.($theme['pattern_bg'] ?: '#3f1c99').';';
        } elseif ($bgType === 'image' && $profile->background_image_url) {
            $bgStyle = "background-image:url('".$profile->background_image_url."');background-size:cover;background-position:center;";
            $bgOverlay = true;
        }

        // Buttons ----------------------------------------------------------------------
        $btnRadius = $profile->buttonRadiusPx();
        $btnBorder = $profile->button_style === 'outline' ? max((int) $profile->button_border_width, 2) : (int) $profile->button_border_width;
        $btnColor = $profile->button_color ?: $theme['accent'];
        $btnText = $profile->buttonTextColor();
        $btnStyle = $profile->button_style === 'outline'
            ? 'background:transparent;color:'.($isLight ? '#111827' : $theme['accent']).';'
            : "background:{$btnColor};color:{$btnText};";
        $btnStyle .= "border-radius:{$btnRadius}px;border:{$btnBorder}px solid ".($isLight ? 'rgba(0,0,0,.35)' : 'rgba(255,255,255,.45)').';';
        $btnStyle .= $profile->button_shadow ? 'box-shadow:0 10px 28px -10px rgba(0,0,0,.45);' : '';

        $socials = array_filter($profile->social_links ?? []);
        $layout = $profile->header_layout ?? 'classic';
        $animate = (bool) $profile->animation_enabled;
        $hoverClass = 'transition-all duration-200 hover:-translate-y-0.5';
        $shadowClass = $profile->button_shadow ? ' hover:shadow-lg' : '';

        // Card ----------------------------------------------------------------------
        // The whole profile lives inside one card that floats over the page
        // background, so it reads as a card instead of a full-bleed screen.
        $cardStyle = $isLight
            ? 'background:#ffffff;color:#111827;box-shadow:0 30px 70px -24px rgba(15,23,42,.45),0 2px 6px rgba(15,23,42,.06);'
            : 'background:rgba(15,23,42,.55);color:#fff;border:1px solid rgba(255,255,255,.18);'
              .'backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);box-shadow:0 30px 70px -24px rgba(0,0,0,.65);';
    ?>
</head>
<body class="min-h-screen <?php echo e($theme['text']); ?> <?php echo e($bgClass); ?>" style="<?php echo e($bgStyle); ?> font-family:<?php echo e($fontFamily); ?>">

    <?php if($bgOverlay): ?>
        <div class="fixed inset-0 bg-black/35"></div>
    <?php endif; ?>

    <div class="relative min-h-screen flex flex-col items-center px-4 py-8 sm:py-14 <?php echo e($animate ? 'animate-fade-up' : ''); ?>">
        <div class="w-full max-w-md mx-auto text-center">
            <div class="overflow-hidden rounded-[2rem] px-6 pt-9 pb-8 profile-card" style="<?php echo e($cardStyle); ?>">

            
            <?php if($layout === 'banner'): ?>
                <div class="-mx-6 -mt-9">
                    <div class="h-28 <?php echo e($isLight ? 'bg-black/10' : 'bg-white/15 backdrop-blur'); ?>"></div>
                    <img src="<?php echo e($profile->avatar_url); ?>" alt="<?php echo e($profileUser->name); ?>"
                         class="w-24 h-24 rounded-full object-cover mx-auto -mt-12 border-4 <?php echo e($isLight ? 'border-gray-200 shadow-xl' : 'border-white/80 shadow-xl'); ?>">
                    <h1 class="mt-3 text-xl font-bold"><?php echo e($profile->display_name ?: $profileUser->name); ?></h1>
                    <p class="text-sm opacity-70"><?php echo e($profileUser->username); ?></p>
                    <?php if($profile->bio): ?><p class="mt-2 text-sm opacity-90 leading-relaxed px-2"><?php echo e($profile->bio); ?></p><?php endif; ?>
                </div>
            <?php elseif($layout === 'cutout'): ?>
                <div class="relative mx-auto mt-2 w-24 h-24">
                    <div class="absolute inset-2 rotate-45 rounded-3xl <?php echo e($isLight ? 'bg-white/80' : 'bg-white/20'); ?>"></div>
                    <img src="<?php echo e($profile->avatar_url); ?>" alt="<?php echo e($profileUser->name); ?>"
                         class="absolute inset-0 w-full h-full rounded-full object-cover border-4 <?php echo e($isLight ? 'border-white' : 'border-white/80'); ?>">
                </div>
                <h1 class="mt-5 text-xl font-bold"><?php echo e($profile->display_name ?: $profileUser->name); ?></h1>
                <p class="text-sm opacity-70"><?php echo e($profileUser->username); ?></p>
                <?php if($profile->bio): ?><p class="mt-2 text-sm opacity-90 leading-relaxed px-2"><?php echo e($profile->bio); ?></p><?php endif; ?>
            <?php elseif($layout === 'shape'): ?>
                <div class="mx-auto mt-2 w-24 h-24 rounded-[36%] p-1.5 <?php echo e($isLight ? 'bg-white shadow-xl' : 'bg-white/25'); ?>">
                    <img src="<?php echo e($profile->avatar_url); ?>" alt="<?php echo e($profileUser->name); ?>" class="w-full h-full rounded-[34%] object-cover">
                </div>
                <h1 class="mt-5 text-xl font-bold"><?php echo e($profile->display_name ?: $profileUser->name); ?></h1>
                <p class="text-sm opacity-70"><?php echo e($profileUser->username); ?></p>
                <?php if($profile->bio): ?><p class="mt-2 text-sm opacity-90 leading-relaxed px-2"><?php echo e($profile->bio); ?></p><?php endif; ?>
            <?php elseif($layout === 'hero'): ?>
                <div class="mx-auto mt-2 w-28 h-28 rounded-[1.75rem] p-1 <?php echo e($isLight ? 'bg-white shadow-xl' : 'bg-white/90'); ?>">
                    <img src="<?php echo e($profile->avatar_url); ?>" alt="<?php echo e($profileUser->name); ?>" class="w-full h-full rounded-[1.5rem] object-cover">
                </div>
                <h1 class="mt-5 text-2xl font-extrabold"><?php echo e($profile->display_name ?: $profileUser->name); ?></h1>
                <p class="text-sm opacity-70 mt-0.5"><?php echo e($profileUser->username); ?></p>
                <?php if($profile->bio): ?><p class="mt-2 text-sm opacity-90 leading-relaxed px-2"><?php echo e($profile->bio); ?></p><?php endif; ?>
            <?php else: ?>
                
                <img src="<?php echo e($profile->avatar_url); ?>" alt="<?php echo e($profileUser->name); ?>"
                     class="w-24 h-24 rounded-full object-cover mx-auto border-4 <?php echo e($isLight ? 'border-black/10' : 'border-white/40'); ?> shadow-lg">
                <h1 class="mt-4 text-xl font-bold"><?php echo e($profile->display_name ?: $profileUser->name); ?></h1>
                <p class="text-sm opacity-70"><?php echo e($profileUser->username); ?></p>
                <?php if($profile->bio): ?><p class="mt-3 text-sm opacity-90 leading-relaxed px-2"><?php echo e($profile->bio); ?></p><?php endif; ?>
            <?php endif; ?>

            
            <?php if(count($socials)): ?>
                <div class="flex justify-center flex-wrap gap-3 mt-5">
                    <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(str_starts_with($platform, 'email') ? 'mailto:'.$url : $url); ?>" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-full <?php echo e($hoverClass); ?> flex items-center justify-center text-sm font-bold <?php echo e($hoverClass); ?>"
                           style="<?php echo e($isLight ? 'background:rgba(0,0,0,.12);color:#111827' : 'background:rgba(255,255,255,.22);color:#fff'); ?>"
                           title="<?php echo e(\App\Models\Profile::SOCIAL_PLATFORMS[$platform]['label'] ?? ucfirst($platform)); ?>">
                            <?php echo e(['instagram' => '◎', 'tiktok' => '♪', 'youtube' => '▶', 'facebook' => 'f', 'twitter' => '𝕏', 'whatsapp' => '◉'][$platform] ?? strtoupper(substr($platform, 0, 1))); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <div class="mt-8 space-y-3.5">
                <?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('public.link.redirect', ['username' => $profileUser->username, 'link' => $link->id])); ?>"
                       target="_blank" rel="noopener"
                       class="group flex items-center justify-center gap-2 w-full py-3.5 px-5 font-semibold text-sm <?php echo e($hoverClass); ?><?php echo e($shadowClass); ?>"
                       style="<?php echo e($btnStyle); ?>">
                        <?php if($link->is_featured): ?><span>⭐</span><?php endif; ?>
                        <?php echo e($link->title); ?>

                        <span class="opacity-0 group-hover:opacity-60 transition-opacity">&rarr;</span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="opacity-60 text-sm py-8">Belum ada link yang tersedia.</p>
                <?php endif; ?>
            </div>

            
            <?php if($products->count()): ?>
                <div class="mt-10">
                    <p class="text-[11px] font-bold uppercase tracking-[0.25em] opacity-70 mb-4">Shop</p>
                    <div class="space-y-3.5">
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($product->url); ?>" target="_blank" rel="noopener"
                               class="flex items-center gap-4 w-full p-3 font-semibold <?php echo e($hoverClass); ?><?php echo e($shadowClass); ?>"
                               style="<?php echo e($btnStyle); ?>">
                                <?php if($product->image_url): ?>
                                    <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>"
                                         class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                                <?php else: ?>
                                    <span class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                                          style="<?php echo e($isLight ? 'background:rgba(0,0,0,.10)' : 'background:rgba(0,0,0,.22)'); ?>">🛍️</span>
                                <?php endif; ?>
                                <span class="flex-1 min-w-0 text-left">
                                    <span class="block text-sm truncate"><?php echo e($product->name); ?></span>
                                    <?php if($product->price_label): ?>
                                        <span class="block text-xs opacity-75"><?php echo e($product->price_label); ?></span>
                                    <?php endif; ?>
                                </span>
                                <span class="opacity-70">&rarr;</span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="mt-10 flex items-center justify-center gap-3">
                <button type="button" onclick="shareProfile()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full <?php echo e($hoverClass); ?>"
                        style="<?php echo e($btnStyle); ?>">
                    <span>🔗</span> Bagikan Halaman Ini
                </button>
            </div>

            <p class="mt-10 text-xs opacity-50">
                Dibuat dengan <a href="<?php echo e(route('home')); ?>" class="underline hover:opacity-80">TreeLink</a>
            </p>
            </div>
        </div>
    </div>

    <script>
        function shareProfile() {
            const url = window.location.href;
            const title = document.title;
            if (navigator.share) {
                navigator.share({ title, url }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    alert('URL profil disalin ke clipboard!');
                });
            }
        }
    </script>
</body>
</html><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/profile/show.blade.php ENDPATH**/ ?>