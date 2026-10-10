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
        // The whole profile can live inside one card that floats over the page
        // background. When the card is on, the readable text colour is derived
        // from the card itself so a light card on a dark page still reads well.
        $themeIsLight = $theme['text'] !== 'text-white';
        $cardOn = (bool) $profile->card_enabled;
        $isLight = $cardOn ? $profile->cardTextColor($themeIsLight) === '#111827' : $themeIsLight;

        $cardStyle = 'background:'.$profile->cardBackgroundCss($themeIsLight).';'
            .'color:'.$profile->cardTextColor($themeIsLight).';'
            .'border:'.$profile->cardBorderCss($themeIsLight).';'
            .'box-shadow:'.$profile->cardShadowCss().';'
            .'border-radius:'.$profile->cardRadiusPx().'px;';

        if ($profile->cardStyle() === 'glass') {
            $cardStyle .= 'backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);';
        }
    ?>
</head>
<body class="min-h-screen <?php echo e($theme['text']); ?> <?php echo e($bgClass); ?>" style="<?php echo e($bgStyle); ?> font-family:<?php echo e($fontFamily); ?>">

    <?php if($bgOverlay): ?>
        <div class="fixed inset-0 bg-black/35"></div>
    <?php endif; ?>

    <div class="relative min-h-screen flex flex-col items-center px-4 py-8 sm:py-14 <?php echo e($animate ? 'animate-fade-up' : ''); ?>">
        <div class="w-full max-w-5xl mx-auto text-center">
            <?php if($cardOn): ?>
                <div class="overflow-hidden px-6 pt-9 pb-8" style="<?php echo e($cardStyle); ?>">
            <?php else: ?>
                <div class="-mx-4 -my-8 sm:-my-14 px-4 py-8 sm:py-14">
            <?php endif; ?>

            <?php if(session('status')): ?>
                <p class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-left text-sm font-semibold text-emerald-800"><?php echo e(session('status')); ?></p>
            <?php endif; ?>

            
            <?php if($layout === 'banner'): ?>
                <div class="<?php echo e($cardOn ? '-mx-6 -mt-9' : '-mt-8'); ?>">
                    <div class="h-28 <?php echo e($cardOn ? '' : 'rounded-b-3xl'); ?> <?php echo e($isLight ? 'bg-black/10' : 'bg-white/15 backdrop-blur'); ?>"></div>
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

            
            <div class="mt-5 flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                <a href="<?php echo e(route('public.connections', ['username' => $profileUser->username, 'connection' => 'followers'])); ?>" class="text-sm opacity-80 transition hover:opacity-100">
                    <strong class="font-extrabold"><?php echo e(number_format($profileUser->followers_count)); ?></strong> Pengikut
                </a>
                <a href="<?php echo e(route('public.connections', ['username' => $profileUser->username, 'connection' => 'following'])); ?>" class="text-sm opacity-80 transition hover:opacity-100">
                    <strong class="font-extrabold"><?php echo e(number_format($profileUser->following_count)); ?></strong> Mengikuti
                </a>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->id() !== $profileUser->id): ?>
                        <?php
                            $isFollowingProfile = auth()->user()->following()->whereKey($profileUser->id)->exists();
                        ?>
                        <form method="POST" action="<?php echo e(route('dashboard.accounts.follow.toggle', $profileUser)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-4 py-2 text-xs font-bold transition <?php echo e($hoverClass); ?>" style="<?php echo e($btnStyle); ?>"><?php echo e($isFollowingProfile ? 'Mengikuti' : 'Ikuti'); ?></button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 text-xs font-bold transition <?php echo e($hoverClass); ?>" style="<?php echo e($btnStyle); ?>">Masuk untuk mengikuti</a>
                <?php endif; ?>
            </div>

            
            <?php if(count($socials)): ?>
                <div class="flex justify-center flex-wrap gap-3 mt-5">
                    <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(str_starts_with($platform, 'email') ? 'mailto:'.$url : $url); ?>" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-full <?php echo e($hoverClass); ?> flex items-center justify-center text-sm font-bold <?php echo e($hoverClass); ?>"
                           style="<?php echo e($isLight ? 'background:rgba(0,0,0,.12);color:#111827' : 'background:rgba(255,255,255,.22);color:#fff'); ?>"
                           title="<?php echo e(\App\Models\Profile::SOCIAL_PLATFORMS[$platform]['label'] ?? ucfirst($platform)); ?>">
                            <?php echo \App\Support\Brands::markup($platform === 'website' ? 'globe' : ($platform === 'email' ? 'mail' : $platform), 'w-4 h-4'); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <div class="mt-8 grid gap-8 md:grid-cols-2 md:items-start md:text-left">
            
            <section class="space-y-3.5">
                <h2 class="text-xs font-bold uppercase tracking-[0.2em] opacity-70">Links</h2>
                <?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('public.link.redirect', ['username' => $profileUser->username, 'link' => $link->id])); ?>"
                       target="_blank" rel="noopener"
                       class="group flex items-center justify-center gap-2.5 w-full py-3.5 px-5 font-semibold text-sm <?php echo e($hoverClass); ?><?php echo e($shadowClass); ?>"
                       style="<?php echo e($btnStyle); ?>">
                        <?php if($link->is_featured): ?><span>⭐</span><?php endif; ?>
                        <?php echo \App\Support\Brands::render($link->url, $link->icon, 'w-5 h-5 flex-shrink-0'); ?>

                        <?php echo e($link->title); ?>

                        <span class="opacity-0 group-hover:opacity-60 transition-opacity">&rarr;</span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="opacity-60 text-sm py-8">Belum ada link yang tersedia.</p>
                <?php endif; ?>
            </section>

            
            <?php if($products->count()): ?>
                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-[0.2em] opacity-70">Shop</h2>
                        <span class="text-xs opacity-60"><?php echo e($products->count()); ?> produk</span>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($product->url); ?>" target="_blank" rel="noopener"
                               class="flex min-w-0 flex-col overflow-hidden text-left font-semibold <?php echo e($hoverClass); ?><?php echo e($shadowClass); ?>"
                               style="<?php echo e($btnStyle); ?>">
                                <?php if($product->image_url): ?>
                                    <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>"
                                         class="aspect-[1.6] w-full object-cover">
                                <?php else: ?>
                                    <span class="flex aspect-[1.6] w-full items-center justify-center text-xl"
                                          style="<?php echo e($isLight ? 'background:rgba(0,0,0,.10)' : 'background:rgba(0,0,0,.22)'); ?>">🛍️</span>
                                <?php endif; ?>
                                <span class="block w-full p-3">
                                    <?php if($product->category): ?><span class="block text-[10px] font-bold uppercase tracking-[0.15em] opacity-60"><?php echo e($product->category); ?></span><?php endif; ?>
                                    <span class="mt-1 block truncate text-sm"><?php echo e($product->name); ?></span>
                                    <?php if($product->price_label): ?>
                                        <span class="block text-xs opacity-75"><?php echo e($product->price_label); ?></span>
                                    <?php endif; ?>
                                    <?php if($product->stock !== null): ?>
                                        <span class="mt-1 block text-[11px] opacity-60"><?php echo e($product->stock > 0 ? 'Stok '.$product->stock : 'Stok habis'); ?></span>
                                    <?php endif; ?>
                                </span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>
            </div>

            <?php if($profile->is_linkid_active): ?>
                <?php
                    $linkIdTypes = collect($profile->linkid_types ?? [])->filter(fn ($value) => is_string($value) && trim($value) !== '')->values()->all();
                ?>
                <div id="linkid" class="mt-8 w-full" x-data="{ openCollabModal: false }">
                    <?php if($errors->any()): ?>
                        <div class="mb-3 rounded-xl bg-rose-50 px-4 py-3 text-left text-sm text-rose-700">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p><?php echo e($error); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <div class="rounded-2xl border border-white/30 bg-white/10 backdrop-blur-sm p-4 text-left">
                        <p class="text-[11px] font-bold uppercase tracking-[0.24em] opacity-80">LinkID</p>
                        <h2 class="mt-2 text-lg font-extrabold">Collaborate with me</h2>
                        <?php if($profile->linkid_description): ?>
                            <p class="mt-2 text-sm opacity-80"><?php echo e($profile->linkid_description); ?></p>
                        <?php endif; ?>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <?php $__currentLoopData = $linkIdTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="px-2.5 py-1 rounded-full bg-white/15 text-xs font-semibold"><?php echo e($type); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button type="button" @click="openCollabModal = true" class="flex-1 py-2.5 rounded-xl bg-white text-ink-900 font-bold text-sm shadow-sm">Ajak Kolaborasi</button>
                        </div>
                    </div>

                    <div x-show="openCollabModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                        <div @click.outside="openCollabModal = false" class="bg-white text-ink-900 rounded-2xl w-full max-w-md p-6 text-left shadow-2xl relative">
                            <button @click="openCollabModal = false" class="absolute top-4 right-4 text-ink-400 hover:text-ink-600 text-xl font-bold">&times;</button>
                            <h3 class="font-bold text-xl mb-4">Ajak Kolaborasi</h3>
                            <form method="POST" action="<?php echo e(route('public.linkid.request', $profileUser->username)); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="mb-4">
                                    <label class="mb-2 block text-sm font-semibold">Nama</label>
                                    <input name="requester_name" required maxlength="120" value="<?php echo e(old('requester_name', auth()->user()?->name)); ?>" class="mb-4 w-full rounded-xl border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Nama kamu">
                                    <label class="mb-2 block text-sm font-semibold">Email</label>
                                    <input name="requester_email" type="email" required maxlength="255" value="<?php echo e(old('requester_email', auth()->user()?->email)); ?>" class="w-full rounded-xl border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500" placeholder="nama@email.com">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold mb-2">Jenis Kolaborasi</label>
                                    <select name="type" required class="w-full rounded-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                                        <option value="general">Umum</option>
                                        <?php $__currentLoopData = $linkIdTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e(strtolower(str_replace(' ', '-', $type))); ?>"><?php echo e($type); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold mb-2">Pesan</label>
                                    <textarea name="message" required rows="4" class="w-full rounded-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="Ceritakan detail kolaborasi..."></textarea>
                                </div>
                                <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl transition">
                                    Kirim Request
                                </button>
                            </form>
                        </div>
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