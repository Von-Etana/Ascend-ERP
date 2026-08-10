<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'eyebrow' => null,
    'title' => null,
    'description' => null,
    'count' => null,
    'icon' => 'fa-light fa-layer-group',
    'iconStyle' => null,
    'panelStyle' => null,
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
    'eyebrow' => null,
    'title' => null,
    'description' => null,
    'count' => null,
    'icon' => 'fa-light fa-layer-group',
    'iconStyle' => null,
    'panelStyle' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $resolvedPanelStyle = $panelStyle ?: 'background:
        radial-gradient(circle at top left, rgba(var(--theme-accent-rgb), 0.22), transparent 38%),
        linear-gradient(135deg,
            color-mix(in srgb, var(--theme-accent) 10%, var(--theme-surface-base)) 0%,
            var(--theme-surface-base) 58%,
            color-mix(in srgb, var(--theme-header-text-color) 6%, var(--theme-surface-base)) 100%
        );';

    $resolvedIconStyle = $iconStyle ?: 'background-color: rgba(var(--theme-accent-rgb), 0.14); color: var(--theme-accent);';
?>

<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'overflow-hidden border-none shadow-[0_30px_80px_-48px_rgba(15,23,42,0.42)]','style' => ''.e($resolvedPanelStyle).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'overflow-hidden border-none shadow-[0_30px_80px_-48px_rgba(15,23,42,0.42)]','style' => ''.e($resolvedPanelStyle).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
        <div class="flex items-start gap-4">
            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl text-xl shadow-[0_18px_38px_-24px_rgba(15,23,42,0.34)]" style="<?php echo e($resolvedIconStyle); ?>">
                <i class="<?php echo e($icon); ?>"></i>
            </span>
            <div class="min-w-0">
                <?php if (isset($component)) { $__componentOriginal7923649007586c0c5a045f54785e05d3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7923649007586c0c5a045f54785e05d3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.sub-header','data' => ['eyebrow' => $eyebrow,'title' => $title,'description' => $description,'count' => $count]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.sub-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($eyebrow),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($description),'count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($count)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7923649007586c0c5a045f54785e05d3)): ?>
<?php $attributes = $__attributesOriginal7923649007586c0c5a045f54785e05d3; ?>
<?php unset($__attributesOriginal7923649007586c0c5a045f54785e05d3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7923649007586c0c5a045f54785e05d3)): ?>
<?php $component = $__componentOriginal7923649007586c0c5a045f54785e05d3; ?>
<?php unset($__componentOriginal7923649007586c0c5a045f54785e05d3); ?>
<?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($actions)): ?>
            <div class="flex min-w-0 flex-wrap items-center justify-start gap-2 lg:justify-end">
                <?php echo e($actions); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/page-hero.blade.php ENDPATH**/ ?>