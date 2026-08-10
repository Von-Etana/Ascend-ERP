<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => __('Default Language'),
    'value' => null,
    'help' => null,
    'preferred' => ['vi', 'en'],
    'placeholder' => null,
    'error' => null,
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
    'name',
    'label' => __('Default Language'),
    'value' => null,
    'help' => null,
    'preferred' => ['vi', 'en'],
    'placeholder' => null,
    'error' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (isset($component)) { $__componentOriginalfd0205fd4870412ed739533efc90a607 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd0205fd4870412ed739533efc90a607 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.language-select','data' => ['name' => $name,'label' => $label,'value' => $value,'help' => $help,'preferred' => $preferred,'placeholder' => $placeholder,'error' => $error,'attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.language-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'help' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($help),'preferred' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($preferred),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholder),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($error),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd0205fd4870412ed739533efc90a607)): ?>
<?php $attributes = $__attributesOriginalfd0205fd4870412ed739533efc90a607; ?>
<?php unset($__attributesOriginalfd0205fd4870412ed739533efc90a607); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd0205fd4870412ed739533efc90a607)): ?>
<?php $component = $__componentOriginalfd0205fd4870412ed739533efc90a607; ?>
<?php unset($__componentOriginalfd0205fd4870412ed739533efc90a607); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ai/language-field.blade.php ENDPATH**/ ?>