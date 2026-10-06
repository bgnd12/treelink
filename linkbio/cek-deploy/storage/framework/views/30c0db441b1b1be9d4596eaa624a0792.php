<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('page-title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <form method="POST" action="<?php echo e(route('dashboard.profile.update')); ?>" enctype="multipart/form-data" class="space-y-6" x-data="{ avatarPreview: null }">
            <?php echo csrf_field(); ?>

            
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <h2 class="font-bold text-ink-900 mb-5">Foto Profil</h2>
                <div class="flex items-center gap-5">
                    <img :src="avatarPreview || '<?php echo e($profile->avatar_url); ?>'" class="w-20 h-20 rounded-full object-cover border-4 border-ink-100" alt="Avatar">
                    <div>
                        <label class="inline-block px-4 py-2.5 rounded-xl border border-ink-200 text-sm font-semibold cursor-pointer hover:border-ink-400 transition">
                            Ganti Foto
                            <input type="file" name="avatar" accept="image/*" class="hidden"
                                   @change="avatarPreview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="text-xs text-ink-400 mt-2">JPG, PNG. Maksimal 1MB.</p>
                        <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card space-y-5">
                <h2 class="font-bold text-ink-900">Informasi Dasar</h2>

                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['label' => 'Nama Lengkap','name' => 'name','value' => $user->name,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nama Lengkap','name' => 'name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->name),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>

                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Username</label>
                    <div class="flex rounded-xl border border-ink-200 focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-100 overflow-hidden transition">
                        <span class="px-4 flex items-center bg-ink-50 text-ink-400 text-sm border-r border-ink-200"><?php echo e(request()->getHost()); ?>/</span>
                        <input type="text" name="username" value="<?php echo e(old('username', $user->username)); ?>" required class="w-full px-3 py-3 outline-none text-sm">
                    </div>
                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['label' => 'Nama Tampilan (opsional)','name' => 'display_name','value' => $profile->display_name,'placeholder' => 'Ditampilkan di halaman publik']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nama Tampilan (opsional)','name' => 'display_name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profile->display_name),'placeholder' => 'Ditampilkan di halaman publik']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>

                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Bio</label>
                    <textarea name="bio" rows="3" maxlength="280" placeholder="Ceritakan sedikit tentang dirimu..."
                              class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm"><?php echo e(old('bio', $profile->bio)); ?></textarea>
                    <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <h2 class="font-bold text-ink-900 mb-1">Social Media</h2>
                <p class="text-sm text-ink-500 mb-5">Tautan ini akan tampil sebagai ikon di halaman publikmu.</p>

                <div class="grid sm:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $socialPlatforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5"><?php echo e($platform['label']); ?></label>
                            <input type="text" name="social_links[<?php echo e($key); ?>]" value="<?php echo e(old('social_links.'.$key, $profile->social_links[$key] ?? '')); ?>"
                                   placeholder="https://..." class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <button type="submit" class="px-8 py-3.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
                Simpan Profil
            </button>
        </form>
    </div>

    <div>
        <div class="sticky top-24">
            <p class="text-sm font-semibold text-ink-500 mb-3 text-center">Live Preview</p>
            <?php echo $__env->make('components.phone-preview', ['profile' => $profile, 'links' => $user->links, 'user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/dashboard/profile.blade.php ENDPATH**/ ?>