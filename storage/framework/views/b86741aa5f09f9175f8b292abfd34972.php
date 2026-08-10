<aside class="space-y-5">
    <?php if (isset($component)) { $__componentOriginalce574f703b9b7329d58617771064dcb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce574f703b9b7329d58617771064dcb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.surface-card','data' => ['class' => 'space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.surface-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div>
            <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Get started')); ?></p>
            <p class="mt-1 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Use a reusable template to define the writing job, then add the topic, offer, audience, or context in your own words.')); ?></p>
        </div>

        <div class="space-y-3">
            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent);">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Step 1')); ?></p>
                <p class="mt-2 text-sm leading-6" style="color: var(--theme-header-text-color);"><?php echo e(__('Pick a category and load a template, or start from a blank custom prompt.')); ?></p>
            </div>
            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent);">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Step 2')); ?></p>
                <p class="mt-2 text-sm leading-6" style="color: var(--theme-header-text-color);"><?php echo e(__('Add the campaign context, set tone/creativity, and choose the target platforms.')); ?></p>
            </div>
            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent);">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Step 3')); ?></p>
                <p class="mt-2 text-sm leading-6" style="color: var(--theme-header-text-color);"><?php echo e(__('Generate multiple content variants, then save the strong ones straight into Captions.')); ?></p>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $attributes = $__attributesOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__attributesOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $component = $__componentOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__componentOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalce574f703b9b7329d58617771064dcb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce574f703b9b7329d58617771064dcb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.surface-card','data' => ['class' => 'space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.surface-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Prompt history')); ?></p>
                <p class="mt-1 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Reload previous runs with one click if you want to refine or regenerate them.')); ?></p>
            </div>
            <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.badge','data' => ['variant' => 'neutral']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'neutral']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($promptHistory->count()); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($promptHistory->isNotEmpty()): ?>
            <div class="max-h-[28rem] space-y-2 overflow-y-auto pr-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $promptHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button type="button" wire:click="loadPromptHistory(<?php echo e($history->id); ?>)" class="w-full rounded-[1rem] border px-4 py-3 text-left transition" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent);">
                        <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($history->title ?: __('Content prompt')); ?></p>
                        <p class="mt-1 text-xs leading-5" style="color: var(--theme-muted-text-color);"><?php echo e(\Illuminate\Support\Str::limit($history->prompt, 88)); ?></p>
                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.empty','data' => ['icon' => 'fa-light fa-book-open','title' => __('No prompt history yet'),'description' => __('Generated caption briefs will be stored here after your first run.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-book-open','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No prompt history yet')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Generated caption briefs will be stored here after your first run.'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756)): ?>
<?php $attributes = $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756; ?>
<?php unset($__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756)): ?>
<?php $component = $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756; ?>
<?php unset($__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $attributes = $__attributesOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__attributesOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $component = $__componentOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__componentOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalce574f703b9b7329d58617771064dcb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce574f703b9b7329d58617771064dcb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.surface-card','data' => ['class' => 'space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.surface-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Current setup')); ?></p>
        <div class="space-y-3">
            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent);">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Template')); ?></p>
                <p class="mt-2 text-sm leading-6" style="color: var(--theme-header-text-color);"><?php echo e($selectedTemplate?->category?->name ?: __('Custom prompt')); ?></p>
            </div>
            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent);">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Output')); ?></p>
                <p class="mt-2 text-sm leading-6" style="color: var(--theme-header-text-color);"><?php echo e(__(':count results - :words words target', ['count' => $totalResults, 'words' => $approximateWords])); ?></p>
            </div>
            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent);">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Hashtags')); ?></p>
                <p class="mt-2 text-sm leading-6" style="color: var(--theme-header-text-color);"><?php echo e(ucfirst($hashtagMode)); ?></p>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $attributes = $__attributesOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__attributesOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $component = $__componentOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__componentOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>
</aside>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppAIContent\Providers/../Resources/views/partials/support-sidebar.blade.php ENDPATH**/ ?>