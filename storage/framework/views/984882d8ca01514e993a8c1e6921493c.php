<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'head' => false,
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
    'head' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($head): ?>
    <th <?php echo e($attributes->merge(['style' => 'border-color: var(--theme-border-color); color: var(--theme-muted-text-color);'])->class('border-b px-5 py-4 align-middle text-[11px] font-semibold uppercase tracking-[0.18em] first:pl-6 last:pr-6')); ?>>
        <?php echo e($slot); ?>

    </th>
<?php else: ?>
    <td <?php echo e($attributes->merge(['style' => 'border-color: var(--theme-border-color);'])->class('border-b px-5 py-4 align-middle first:pl-6 last:pr-6')); ?>>
        <?php echo e($slot); ?>

    </td>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/table-cell.blade.php ENDPATH**/ ?>