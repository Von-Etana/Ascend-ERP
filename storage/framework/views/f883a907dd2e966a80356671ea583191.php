<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'align' => 'left',
    'width' => '56',
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
    'align' => 'left',
    'width' => '56',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $alignClass = $align === 'right' ? 'right-0 origin-top-right' : 'left-0 origin-top-left';
    $widthClass = match ($width) {
        'auto' => 'w-max min-w-[9.25rem]',
        'full' => 'w-full',
        default => 'w-'.$width,
    };
?>

<div x-data="{ open: false }" x-bind:class="open ? 'z-[60]' : 'z-0'" class="relative inline-block text-left" x-on:click.stop <?php echo e($attributes); ?>>
    <div x-on:click.stop="open = !open">
        <?php echo e($trigger ?? ''); ?>

    </div>

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top.right
        x-on:click.stop
        x-on:click.outside="open = false"
        x-on:keydown.escape.window="open = false"
        class="absolute <?php echo e($alignClass); ?> z-50 mt-2 <?php echo e($widthClass); ?> overflow-hidden rounded-[1.1rem] border p-1.5 shadow-[0_18px_38px_-18px_rgba(15,23,42,0.22)]"
        style="border-color: color-mix(in srgb, var(--theme-border-color) 78%, transparent 22%); background-color: var(--theme-surface-base);"
    >
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/dropdown-menu.blade.php ENDPATH**/ ?>