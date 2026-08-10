<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'size' => 'md',
    'tone' => 'accent',
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
    'size' => 'md',
    'tone' => 'accent',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizes = [
        'sm' => 'h-4 w-4 border-2',
        'md' => 'h-5 w-5 border-2',
        'lg' => 'h-7 w-7 border-[3px]',
    ];
    $tones = [
        'accent' => 'border-[color:rgba(var(--theme-accent-rgb),0.18)] border-t-[var(--theme-accent)]',
        'slate' => 'border-[color:var(--theme-border-color)] border-t-[color:var(--theme-muted-text-color)]',
        'white' => 'border-white/25 border-t-white',
    ];
?>

<span <?php echo e($attributes->class('inline-block animate-spin rounded-full '.($sizes[$size] ?? $sizes['md']).' '.($tones[$tone] ?? $tones['accent']))); ?>></span>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/spinner.blade.php ENDPATH**/ ?>