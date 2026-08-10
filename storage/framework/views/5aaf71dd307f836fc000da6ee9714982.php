<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'info' => null,
    'footerText' => null,
    'headerClass' => '',
    'eyebrowClass' => '',
    'titleClass' => '',
    'descriptionClass' => '',
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
    'title' => null,
    'info' => null,
    'footerText' => null,
    'headerClass' => '',
    'eyebrowClass' => '',
    'titleClass' => '',
    'descriptionClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (isset($component)) { $__componentOriginal9126f4b0a5e35a5093cbc90f6af9d394 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9126f4b0a5e35a5093cbc90f6af9d394 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.section-card','data' => ['padding' => 'none','class' => 'shadow-[0_24px_60px_-42px_rgba(15,23,42,0.16)]','eyebrow' => $title ? __('Directory') : null,'title' => $title,'description' => $info,'headerClass' => $headerClass,'eyebrowClass' => $eyebrowClass,'titleClass' => $titleClass,'descriptionClass' => $descriptionClass]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'none','class' => 'shadow-[0_24px_60px_-42px_rgba(15,23,42,0.16)]','eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title ? __('Directory') : null),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($info),'header-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($headerClass),'eyebrow-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($eyebrowClass),'title-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($titleClass),'description-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($descriptionClass)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

     <?php $__env->slot('actions', null, []); ?> 
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($toolbar)): ?>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <?php echo e($toolbar); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
     <?php $__env->endSlot(); ?>

    <div class="[&>div]:rounded-none [&>div]:border-0 [&>div]:shadow-none">
        <?php echo e($slot); ?>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($footer) || $footerText): ?>
         <?php $__env->slot('footer', null, []); ?> 
            <div class="flex flex-col gap-4 text-sm sm:flex-row sm:items-center sm:justify-between" style="color: var(--theme-muted-text-color);">
                <div><?php echo e($footerText); ?></div>
                <div><?php echo e($footer ?? ''); ?></div>
            </div>
         <?php $__env->endSlot(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9126f4b0a5e35a5093cbc90f6af9d394)): ?>
<?php $attributes = $__attributesOriginal9126f4b0a5e35a5093cbc90f6af9d394; ?>
<?php unset($__attributesOriginal9126f4b0a5e35a5093cbc90f6af9d394); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9126f4b0a5e35a5093cbc90f6af9d394)): ?>
<?php $component = $__componentOriginal9126f4b0a5e35a5093cbc90f6af9d394; ?>
<?php unset($__componentOriginal9126f4b0a5e35a5093cbc90f6af9d394); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/datatable-shell.blade.php ENDPATH**/ ?>