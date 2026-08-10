<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'padding' => 'md',
    'tone' => 'default',
    'accent' => 'none',
    'featured' => false,
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
    'accent' => 'none',
    'featured' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $accentBlobs = [
        'none' => null,
        'primary' => 'background: radial-gradient(circle at top left, rgba(var(--theme-accent-rgb,37 99 235),0.18), rgba(var(--theme-accent-rgb,37 99 235),0.02) 58%, transparent 72%);',
        'success' => 'background: radial-gradient(circle at top left, rgba(var(--theme-success-color-rgb,16 185 129),0.16), rgba(var(--theme-success-color-rgb,16 185 129),0.02) 58%, transparent 72%);',
        'warning' => 'background: radial-gradient(circle at top left, rgba(var(--theme-warning-color-rgb,245 158 11),0.16), rgba(var(--theme-warning-color-rgb,245 158 11),0.02) 58%, transparent 72%);',
        'danger' => 'background: radial-gradient(circle at top left, rgba(var(--theme-danger-color-rgb,244 63 94),0.16), rgba(var(--theme-danger-color-rgb,244 63 94),0.02) 58%, transparent 72%);',
    ];
?>

<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => $padding,'tone' => $tone,'attributes' => $attributes->class('relative overflow-hidden')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($padding),'tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes->class('relative overflow-hidden'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured && ($accentBlobs[$accent] ?? null)): ?>
        <div class="pointer-events-none absolute left-0 top-0 h-28 w-28" style="<?php echo e($accentBlobs[$accent]); ?>"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="relative">
        <?php echo e($slot); ?>

    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/surface-card.blade.php ENDPATH**/ ?>