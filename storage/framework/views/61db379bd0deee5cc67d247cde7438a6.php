<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'padding' => 'md',
    'tone' => 'default',
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
    'padding' => 'md',
    'tone' => 'default',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-5',
        'md' => 'p-6',
        'lg' => 'p-7',
    ];

    $toneClasses = [
        'default' => 'shadow-[0_20px_52px_-40px_rgba(15,23,42,0.18)]',
        'soft' => 'shadow-[0_14px_36px_-30px_rgba(15,23,42,0.12)]',
        'contrast' => 'shadow-[0_24px_54px_-36px_rgba(15,23,42,0.5)]',
    ];

    $toneStyles = [
        'default' => 'border-color: var(--theme-border-color); background-color: var(--theme-surface-base);',
        'soft' => 'border-color: var(--theme-border-color); background-color: var(--theme-surface-soft);',
        'contrast' => 'border-color: var(--theme-card-contrast-bg); background-color: var(--theme-card-contrast-bg); color: var(--theme-card-contrast-text);',
    ];
?>

<div <?php echo e($attributes->merge(['style' => 'border-radius: var(--theme-card-radius, 1.15rem); '.($toneStyles[$tone] ?? $toneStyles['default'])])->class('min-w-0 max-w-full border backdrop-blur-[1px] '.($toneClasses[$tone] ?? $toneClasses['default']).' '.($paddingClasses[$padding] ?? 'p-6'))); ?>>
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/card.blade.php ENDPATH**/ ?>