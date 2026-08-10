<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => null,
    'type' => null,
    'variant' => 'primary',
    'size' => 'md',
    'block' => false,
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
    'type' => null,
    'variant' => 'primary',
    'size' => 'md',
    'block' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $buttonStyle = theme_setting('button_style', 'app', 'solid');
    $buttonShadow = theme_setting('button_shadow', 'app', 'soft');
    $primaryVariant = match ($buttonStyle) {
        'soft' => 'border border-[color:rgba(var(--theme-accent-rgb),0.16)] bg-[color:rgba(var(--theme-accent-rgb),0.12)] text-[var(--theme-accent)] hover:bg-[color:rgba(var(--theme-accent-rgb),0.18)]',
        'outline' => 'border border-[var(--theme-accent)] bg-transparent text-[var(--theme-accent)] hover:bg-[color:rgba(var(--theme-accent-rgb),0.06)]',
        default => 'border border-[var(--theme-accent)] bg-[var(--theme-accent)] text-white hover:opacity-95',
    };
    $primaryShadow = match ($buttonShadow) {
        'none' => 'shadow-none',
        'strong' => 'shadow-[0_18px_36px_-18px_rgba(var(--theme-accent-rgb),0.55)]',
        default => 'shadow-[0_14px_28px_-18px_rgba(var(--theme-accent-rgb),0.35)]',
    };

    $base = 'inline-flex cursor-pointer items-center justify-center gap-2 whitespace-nowrap font-semibold tracking-[-0.01em] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:ring-offset-2 focus:ring-offset-[#f4f6fb] disabled:pointer-events-none disabled:opacity-50 dark:focus:ring-offset-slate-950';

    $variants = [
        'primary' => $primaryVariant.' '.$primaryShadow,
        'secondary' => 'border text-[var(--theme-button-soft-text)] shadow-sm hover:-translate-y-px hover:shadow-[0_14px_28px_-22px_rgba(15,23,42,0.38)]',
        'outline' => 'border bg-transparent shadow-sm hover:-translate-y-px hover:shadow-[0_14px_28px_-22px_rgba(15,23,42,0.3)]',
        'ghost' => 'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white',
        'success' => 'border border-emerald-600 bg-emerald-600 text-white shadow-[0_14px_28px_-18px_rgba(16,185,129,0.42)] hover:border-emerald-500 hover:bg-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400',
        'warning' => 'border border-amber-500 bg-amber-500 text-slate-950 shadow-[0_14px_28px_-18px_rgba(245,158,11,0.4)] hover:border-amber-400 hover:bg-amber-400 dark:border-amber-400 dark:bg-amber-400 dark:hover:bg-amber-300',
        'info' => 'border border-sky-600 bg-sky-600 text-white shadow-[0_14px_28px_-18px_rgba(14,165,233,0.42)] hover:border-sky-500 hover:bg-sky-500 dark:border-sky-500 dark:bg-sky-500 dark:hover:bg-sky-400',
        'danger' => 'border border-rose-600 bg-rose-600 text-white shadow-[0_14px_28px_-18px_rgba(225,29,72,0.42)] hover:border-rose-500 hover:bg-rose-500 dark:border-rose-500 dark:bg-rose-500 dark:hover:bg-rose-400',
    ];

    $sizes = [
        'sm' => 'h-9 px-3.5 text-sm',
        'md' => 'h-10 px-4.5 text-sm',
        'lg' => 'h-11 px-5 text-[15px]',
    ];

    $classes = implode(' ', [
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $block ? 'w-full' : '',
    ]);

    $styles = match ($variant) {
        'secondary' => 'border-radius: var(--theme-button-radius, 0.75rem); border-color: var(--theme-border-color); background-color: var(--theme-button-soft-bg); color: var(--theme-button-soft-text);',
        'outline' => '--button-outline-text: var(--theme-header-text-color); --button-outline-bg: transparent; --button-outline-hover-bg: var(--theme-button-outline-hover); --button-outline-border: var(--theme-border-color); --button-outline-hover-border: rgba(var(--theme-accent-rgb), 0.35); border-radius: var(--theme-button-radius, 0.75rem); border-color: var(--theme-border-color); color: var(--theme-header-text-color); background-color: transparent;',
        default => 'border-radius: var(--theme-button-radius, 0.75rem);',
    };
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($href): ?>
    <a href="<?php echo e($href); ?>"
       <?php echo e($attributes->merge(['style' => $styles])->class($classes)); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button
        <?php if($type !== null): ?> type="<?php echo e($type); ?>" <?php endif; ?>
        <?php echo e($attributes->merge(['style' => $styles])->class($classes)); ?>>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/button.blade.php ENDPATH**/ ?>