<?php if (isset($component)) { $__componentOriginal52550bbca375f4f134f03258e238f0c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52550bbca375f4f134f03258e238f0c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.shell','data' => ['title' => $title ?? null,'shellArea' => $shellArea ?? null,'fullWorkspace' => $fullWorkspace ?? false,'fullWorkspacePaddingBottom' => $fullWorkspacePaddingBottom ?? true,'showLoadingBackdrop' => $showLoadingBackdrop ?? true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title ?? null),'shell-area' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($shellArea ?? null),'full-workspace' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fullWorkspace ?? false),'full-workspace-padding-bottom' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fullWorkspacePaddingBottom ?? true),'show-loading-backdrop' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showLoadingBackdrop ?? true)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php echo e($slot); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52550bbca375f4f134f03258e238f0c3)): ?>
<?php $attributes = $__attributesOriginal52550bbca375f4f134f03258e238f0c3; ?>
<?php unset($__attributesOriginal52550bbca375f4f134f03258e238f0c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52550bbca375f4f134f03258e238f0c3)): ?>
<?php $component = $__componentOriginal52550bbca375f4f134f03258e238f0c3; ?>
<?php unset($__componentOriginal52550bbca375f4f134f03258e238f0c3); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes\app\default/resources/views/layouts/app.blade.php ENDPATH**/ ?>