<?php $__env->startSection('title', __('Short Links')); ?>
<?php $__env->startSection('page-title', __('Short Links')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-6">
        <h2 class="text-xl font-bold mb-4"><?php echo e(__('Create New Short Link')); ?></h2>

        
        <?php
            $failedSource = old('source');
            $failedSlugId = $errors->get('slug_id')[0] ?? null;
            $failedSlugId = $failedSlugId !== null ? (int) $failedSlugId : null;
            $failedLink = $failedSlugId !== null ? $shortLinks->firstWhere('id', $failedSlugId) : null;

            $editingFailed = $failedSource === 'top' && $failedLink !== null;
            $failedSlugId = $failedLink !== null ? $failedSlugId : null;

            $rowFailed = $failedSource === 'row';
            $rowSlugValue = $rowFailed ? old('slug') : $failedLink?->slug;
            $rowDestValue = $rowFailed ? old('destination_url') : ($failedLink?->destination_url ?? '');
        ?>

        <form action="<?php echo e($editingFailed ? route('dashboard.short-links.update', $failedLink) : route('dashboard.short-links.store')); ?>"
              method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php if($editingFailed): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1"><?php echo e(__('Destination URL')); ?></label>
                <input type="url" name="destination_url" required
                       value="<?php echo e($rowFailed ? '' : old('destination_url', $failedLink?->destination_url)); ?>"
                       placeholder="https://example.com/very-long-url"
                       class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                           'w-full rounded-xl px-3 py-2.5 outline-none text-sm transition',
                           'border-rose-300 bg-rose-50/40 focus:border-rose-500 focus:ring-4 focus:ring-rose-50' => $errors->has('destination_url'),
                           'border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100' => ! $errors->has('destination_url'),
                       ]); ?>">
                <?php $__errorArgs = ['destination_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 flex items-start gap-1.5 text-xs font-semibold text-rose-600">
                        <span aria-hidden="true">⚠</span>
                        <span><?php echo e($message); ?></span>
                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <?php if (isset($component)) { $__componentOriginal472d2f0ddc4edf94af5067cbc02b33af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal472d2f0ddc4edf94af5067cbc02b33af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.short-link-slug-input','data' => ['required' => $editingFailed,'value' => $rowFailed ? '' : old('slug', $failedLink?->slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('short-link-slug-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($editingFailed),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rowFailed ? '' : old('slug', $failedLink?->slug))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal472d2f0ddc4edf94af5067cbc02b33af)): ?>
<?php $attributes = $__attributesOriginal472d2f0ddc4edf94af5067cbc02b33af; ?>
<?php unset($__attributesOriginal472d2f0ddc4edf94af5067cbc02b33af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal472d2f0ddc4edf94af5067cbc02b33af)): ?>
<?php $component = $__componentOriginal472d2f0ddc4edf94af5067cbc02b33af; ?>
<?php unset($__componentOriginal472d2f0ddc4edf94af5067cbc02b33af); ?>
<?php endif; ?>

            <input type="hidden" name="source" value="top">
            <?php if($editingFailed): ?>
                <input type="hidden" name="slug_id" value="<?php echo e($failedSlugId); ?>">
            <?php endif; ?>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition">
                    <?php echo e($editingFailed ? __('Save Changes') : __('Create Link')); ?>

                </button>
                <?php if($editingFailed): ?>
                    <a href="<?php echo e(route('dashboard.short-links.index')); ?>"
                       class="text-sm font-semibold text-ink-500 hover:text-ink-700"><?php echo e(__('Cancel')); ?></a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-ink-900"><?php echo e(__('Your Short Links')); ?></h3>
        <?php $__empty_1 = true; $__currentLoopData = $shortLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            
            <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-5"
                 x-data="{
                     editing: <?php echo \Illuminate\Support\Js::from($rowFailed && $failedSlugId === $link->id)->toHtml() ?>,
                     draft: {
                         slug: <?php echo \Illuminate\Support\Js::from($rowSlugValue)->toHtml() ?>,
                         destination_url: <?php echo \Illuminate\Support\Js::from($rowDestValue)->toHtml() ?>,
                     },
                 }">

                <div x-show="!editing" class="flex items-center justify-between">
                    <div class="flex-1 min-w-0 pr-4">
                        <a href="<?php echo e(url('/' . $link->slug)); ?>" target="_blank" rel="noopener"
                           class="font-bold text-brand-600 text-lg hover:underline block truncate">
                            <?php echo e(url('/' . $link->slug)); ?>

                        </a>
                        <p class="text-sm text-ink-500 truncate mt-1">➡ <?php echo e($link->destination_url); ?></p>
                        <div class="flex items-center gap-4 mt-3 text-xs font-semibold text-ink-400">
                            <span class="flex items-center gap-1"><span class="text-lg">📊</span> <?php echo e(number_format($link->clicks)); ?> <?php echo e(__('Clicks')); ?></span>
                            <span class="flex items-center gap-1"><span class="text-lg">📅</span> <?php echo e($link->created_at->format('M d, Y')); ?></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="editing = true"
                                class="p-2 rounded-xl bg-ink-50 text-ink-600 hover:bg-ink-100" title="<?php echo e(__('Edit')); ?>">
                            ✏️
                        </button>
                        <form action="<?php echo e(route('dashboard.short-links.toggle', $link)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="p-2 rounded-xl <?php echo e($link->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-ink-50 text-ink-400 hover:bg-ink-100'); ?>" title="<?php echo e(__('Toggle Status')); ?>">
                                <?php echo e($link->is_active ? '✅' : '❌'); ?>

                            </button>
                        </form>
                        <form action="<?php echo e(route('dashboard.short-links.destroy', $link)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this short link?')); ?>')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100" title="<?php echo e(__('Delete')); ?>">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>

                <form x-show="editing" x-cloak method="POST"
                      action="<?php echo e(route('dashboard.short-links.update', $link)); ?>" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="source" value="row">
                    <input type="hidden" name="slug_id" value="<?php echo e($link->id); ?>">

                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1"><?php echo e(__('Destination URL')); ?></label>
                        <input type="url" name="destination_url" required x-model="draft.destination_url"
                               class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                   'w-full rounded-xl px-3 py-2.5 outline-none text-sm transition',
                                   'border-rose-300 bg-rose-50/40 focus:border-rose-500 focus:ring-4 focus:ring-rose-50' => $errors->has('destination_url'),
                                   'border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100' => ! $errors->has('destination_url'),
                               ]); ?>">
                    </div>

                    <div class="flex rounded-xl border transition overflow-hidden focus-within:ring-4 focus-within:ring-brand-100
                                <?php echo e($errors->has('slug') ? 'border-rose-300 focus-within:border-rose-500 focus-within:ring-rose-50' : 'border-ink-200 focus-within:border-brand-500'); ?>">
                        <span class="inline-flex items-center px-4 border-r bg-ink-50 text-ink-500 text-sm
                                     <?php echo e($errors->has('slug') ? 'border-rose-300' : 'border-ink-200'); ?>">
                            <?php echo e(url('/')); ?>/
                        </span>
                        <input type="text" name="slug" x-model="draft.slug" maxlength="50" required
                               spellcheck="false" autocomplete="off" placeholder="my-event"
                               class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                   'flex-1 px-3 py-2.5 outline-none text-sm',
                                   'border-rose-300 bg-rose-50/40' => $errors->has('slug'),
                                   'bg-white' => ! $errors->has('slug'),
                               ]); ?>">
                    </div>

                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="flex items-start gap-1.5 text-xs font-semibold text-rose-600">
                            <span aria-hidden="true">⚠</span>
                            <span><?php echo e($message); ?></span>
                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="flex items-center gap-3 pt-1">
                        <button type="submit" class="px-5 py-2 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition">
                            <?php echo e(__('Save Changes')); ?>

                        </button>
                        <button type="button" @click="editing = false"
                                class="text-sm font-semibold text-ink-500 hover:text-ink-700"><?php echo e(__('Cancel')); ?></button>
                    </div>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12 bg-white rounded-2xl border border-ink-100 border-dashed">
                <div class="text-4xl mb-3">🔗</div>
                <h4 class="text-lg font-bold text-ink-900"><?php echo e(__('No short links yet')); ?></h4>
                <p class="text-sm text-ink-500 mt-1"><?php echo e(__('Create your first short link above.')); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/dashboard/short-links/index.blade.php ENDPATH**/ ?>