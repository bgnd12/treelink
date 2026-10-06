<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'slug',
    'value' => null,
    'label' => null,
    'required' => false,
    'placeholder' => 'my-event',
    'hint' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => 'slug',
    'value' => null,
    'label' => null,
    'required' => false,
    'placeholder' => 'my-event',
    'hint' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $label ??= $required
        ? __('Custom Address')
        : __('Custom Address (Optional)');

    $hint ??= $required
        ? __('The address is unique across all of TreeLink.')
        : __('Leave blank to generate randomly.');
?>

<div>
    <label class="block text-sm font-semibold text-ink-700 mb-1"><?php echo e($label); ?></label>
    <div class="flex rounded-xl border transition overflow-hidden focus-within:ring-4 focus-within:ring-brand-100
                <?php echo e($errors->has($name) ? 'border-rose-300 focus-within:border-rose-500 focus-within:ring-rose-50' : 'border-ink-200 focus-within:border-brand-500'); ?>">
        <span class="inline-flex items-center px-4 border-r bg-ink-50 text-ink-500 text-sm
                     <?php echo e($errors->has($name) ? 'border-rose-300' : 'border-ink-200'); ?>">
            <?php echo e(url('/')); ?>/
        </span>
        <input type="text" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>"
               placeholder="<?php echo e($placeholder); ?>" maxlength="50" spellcheck="false" autocomplete="off"
               <?php if($required): ?> required <?php endif; ?>
               class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                   'flex-1 px-3 py-2.5 outline-none text-sm',
                   'border-rose-300 bg-rose-50/40' => $errors->has($name),
                   'bg-white' => ! $errors->has($name),
               ]); ?>">
    </div>

    <?php $__errorArgs = [$name];
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

    <p class="text-xs text-ink-400 mt-1"><?php echo e($hint); ?></p>
</div>
<?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/components/short-link-slug-input.blade.php ENDPATH**/ ?>