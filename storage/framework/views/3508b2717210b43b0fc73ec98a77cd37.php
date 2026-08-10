<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'trailing' => null,
    'trailingIcon' => null,
    'variant' => 'default',
    'close' => true,
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
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'trailing' => null,
    'trailingIcon' => null,
    'variant' => 'default',
    'close' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $tag = $href ? 'a' : 'button';
    $variantClasses = match ($variant) {
        'muted' => 'cursor-default hover:bg-transparent',
        'danger' => '',
        default => '',
    };
    $baseClasses = 'flex w-full items-center gap-3 whitespace-nowrap rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 transition';
    $clickToClose = $close ? 'open = false' : null;
    $baseStyle = match ($variant) {
        'muted' => 'color: var(--theme-muted-text-color);',
        'danger' => 'color: var(--theme-danger-color);',
        default => 'color: var(--theme-header-text-color);',
    };
    $hoverStyle = match ($variant) {
        'muted' => 'background-color: transparent; color: var(--theme-muted-text-color);',
        'danger' => 'background-color: rgba(244, 63, 94, 0.08); color: var(--theme-danger-color);',
        default => 'background-color: var(--theme-surface-soft); color: var(--theme-header-text-color);',
    };
    $iconStyle = match ($variant) {
        'muted' => 'color: var(--theme-muted-text-color);',
        'danger' => 'color: var(--theme-danger-color);',
        default => 'color: var(--theme-muted-text-color);',
    };
?>

<<?php echo e($tag); ?>

    <?php if($href): ?>
        href="<?php echo e($href); ?>"
    <?php else: ?>
        type="<?php echo e($type); ?>"
    <?php endif; ?>
    <?php echo e($attributes->merge(['style' => $baseStyle])->class($baseClasses.' '.$variantClasses)); ?>

    <?php if($clickToClose): ?>
        x-on:click.stop="<?php echo e($clickToClose); ?>"
    <?php endif; ?>
    x-data="{ hover: false }"
    x-on:mouseenter="hover = true"
    x-on:mouseleave="hover = false"
    x-bind:style="hover ? '<?php echo e($hoverStyle); ?>' : '<?php echo e($baseStyle); ?>'"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($icon): ?>
        <i class="<?php echo e($icon); ?> text-[14px]" style="<?php echo e($iconStyle); ?>"></i>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <span class="min-w-0 flex-1 truncate"><?php echo e($slot); ?></span>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trailing): ?>
        <span class="pl-4 text-[14px] font-medium tracking-[0.02em]" style="color: var(--theme-muted-text-color);"><?php echo e($trailing); ?></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trailingIcon): ?>
        <i class="<?php echo e($trailingIcon); ?> pl-4 text-[13px]" style="color: var(--theme-muted-text-color);"></i>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</<?php echo e($tag); ?>>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/dropdown-menu-item.blade.php ENDPATH**/ ?>