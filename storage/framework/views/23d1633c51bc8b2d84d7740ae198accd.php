<div class="space-y-8 px-4 pb-10 pt-4 sm:px-5 xl:px-6">
    <section class="overflow-hidden rounded-[1.75rem] border" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background:
        radial-gradient(circle at 0% 0%, rgba(var(--theme-accent-rgb), 0.16), transparent 30%),
        linear-gradient(135deg, rgba(var(--theme-surface-base-rgb,255,255,255),0.98), rgba(var(--theme-surface-base-rgb,255,255,255),0.94));
    ">
        <div class="grid gap-6 px-6 py-7 sm:px-8 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em]" style="color: var(--theme-accent);"><?php echo e(__('AI content studio')); ?></p>
                <h1 class="mt-2 text-[1.85rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(__('AI Publishing Schedules')); ?></h1>
                <p class="mt-3 max-w-3xl text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e(__('Manage recurring AI publishing schedules separately from the create/edit workflow. Each schedule can generate content and push valid posts into Publishing.')); ?></p>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2">
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => route('portal.ai-publishing.create'),'size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.ai-publishing.create')),'size' => 'lg']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <i class="fa-light fa-plus"></i>
                    <?php echo e(__('Create AI Publishing')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            </div>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal7554ad69528436c5ab8ea29131994dda = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7554ad69528436c5ab8ea29131994dda = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.metric-strip','data' => ['columns' => 'md:grid-cols-2 xl:grid-cols-5','gap' => 'gap-5','cardStyle' => 'border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.96); min-height: 12rem;','progressTrackStyle' => 'background-color: rgba(var(--theme-border-color-rgb), 0.18);','items' => [
            ['label' => __('Total'), 'value' => number_format($summary['total']), 'description' => __('All AI publishing schedules.'), 'tone' => '#7c3aed', 'icon' => 'fa-light fa-sparkles', 'cardStyle' => 'background: linear-gradient(180deg, rgba(124, 58, 237, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(124, 58, 237, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(124, 58, 237, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(124, 58, 237, 0.18)', 'progressTrackStyle' => 'background-color: rgba(124, 58, 237, 0.12);'],
            ['label' => __('Running'), 'value' => number_format($summary['running']), 'description' => __('Schedules still active within their date range.'), 'tone' => '#059669', 'icon' => 'fa-light fa-loader', 'cardStyle' => 'background: linear-gradient(180deg, rgba(16, 185, 129, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(16, 185, 129, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(16, 185, 129, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(16, 185, 129, 0.18)', 'progressTrackStyle' => 'background-color: rgba(16, 185, 129, 0.12);'],
            ['label' => __('Completed'), 'value' => number_format($summary['completed']), 'description' => __('Schedules that reached their end date.'), 'tone' => '#0f766e', 'icon' => 'fa-light fa-circle-check', 'cardStyle' => 'background: linear-gradient(180deg, rgba(15, 118, 110, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(15, 118, 110, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(15, 118, 110, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(15, 118, 110, 0.18)', 'progressTrackStyle' => 'background-color: rgba(15, 118, 110, 0.12);'],
            ['label' => __('Paused'), 'value' => number_format($summary['paused']), 'description' => __('Schedules whose publishing posts are temporarily paused.'), 'tone' => '#ea580c', 'icon' => 'fa-light fa-circle-pause', 'cardStyle' => 'background: linear-gradient(180deg, rgba(249, 115, 22, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(249, 115, 22, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(249, 115, 22, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(249, 115, 22, 0.18)', 'progressTrackStyle' => 'background-color: rgba(249, 115, 22, 0.12);'],
            ['label' => __('Failed'), 'value' => number_format($summary['failed']), 'description' => __('Schedules with failed generation items.'), 'tone' => '#dc2626', 'icon' => 'fa-light fa-circle-exclamation', 'cardStyle' => 'background: linear-gradient(180deg, rgba(239, 68, 68, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(239, 68, 68, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(239, 68, 68, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(239, 68, 68, 0.18)', 'progressTrackStyle' => 'background-color: rgba(239, 68, 68, 0.12);'],
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['columns' => 'md:grid-cols-2 xl:grid-cols-5','gap' => 'gap-5','card-style' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.96); min-height: 12rem;'),'progress-track-style' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('background-color: rgba(var(--theme-border-color-rgb), 0.18);'),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => __('Total'), 'value' => number_format($summary['total']), 'description' => __('All AI publishing schedules.'), 'tone' => '#7c3aed', 'icon' => 'fa-light fa-sparkles', 'cardStyle' => 'background: linear-gradient(180deg, rgba(124, 58, 237, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(124, 58, 237, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(124, 58, 237, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(124, 58, 237, 0.18)', 'progressTrackStyle' => 'background-color: rgba(124, 58, 237, 0.12);'],
            ['label' => __('Running'), 'value' => number_format($summary['running']), 'description' => __('Schedules still active within their date range.'), 'tone' => '#059669', 'icon' => 'fa-light fa-loader', 'cardStyle' => 'background: linear-gradient(180deg, rgba(16, 185, 129, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(16, 185, 129, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(16, 185, 129, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(16, 185, 129, 0.18)', 'progressTrackStyle' => 'background-color: rgba(16, 185, 129, 0.12);'],
            ['label' => __('Completed'), 'value' => number_format($summary['completed']), 'description' => __('Schedules that reached their end date.'), 'tone' => '#0f766e', 'icon' => 'fa-light fa-circle-check', 'cardStyle' => 'background: linear-gradient(180deg, rgba(15, 118, 110, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(15, 118, 110, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(15, 118, 110, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(15, 118, 110, 0.18)', 'progressTrackStyle' => 'background-color: rgba(15, 118, 110, 0.12);'],
            ['label' => __('Paused'), 'value' => number_format($summary['paused']), 'description' => __('Schedules whose publishing posts are temporarily paused.'), 'tone' => '#ea580c', 'icon' => 'fa-light fa-circle-pause', 'cardStyle' => 'background: linear-gradient(180deg, rgba(249, 115, 22, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(249, 115, 22, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(249, 115, 22, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(249, 115, 22, 0.18)', 'progressTrackStyle' => 'background-color: rgba(249, 115, 22, 0.12);'],
            ['label' => __('Failed'), 'value' => number_format($summary['failed']), 'description' => __('Schedules with failed generation items.'), 'tone' => '#dc2626', 'icon' => 'fa-light fa-circle-exclamation', 'cardStyle' => 'background: linear-gradient(180deg, rgba(239, 68, 68, 0.08), rgba(var(--theme-surface-base-rgb,255,255,255),0.98) 42%); border-color: rgba(239, 68, 68, 0.16);', 'iconSurface' => 'linear-gradient(145deg, rgba(239, 68, 68, 0.14), rgba(255,255,255,0.96));', 'iconBorder' => 'rgba(239, 68, 68, 0.18)', 'progressTrackStyle' => 'background-color: rgba(239, 68, 68, 0.12);'],
        ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7554ad69528436c5ab8ea29131994dda)): ?>
<?php $attributes = $__attributesOriginal7554ad69528436c5ab8ea29131994dda; ?>
<?php unset($__attributesOriginal7554ad69528436c5ab8ea29131994dda); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7554ad69528436c5ab8ea29131994dda)): ?>
<?php $component = $__componentOriginal7554ad69528436c5ab8ea29131994dda; ?>
<?php unset($__componentOriginal7554ad69528436c5ab8ea29131994dda); ?>
<?php endif; ?>

    <div class="rounded-[1.45rem] border px-4 py-4 sm:px-5" style="border-color: rgba(var(--theme-border-color-rgb), 0.58); background:
        radial-gradient(circle at top right, rgba(var(--theme-accent-rgb), 0.08), transparent 28%),
        linear-gradient(135deg, rgba(var(--theme-surface-base-rgb,255,255,255),0.98), rgba(var(--theme-surface-soft-rgb,248,250,252),0.8));
        box-shadow: 0 18px 50px rgba(15, 23, 42, 0.04);
    ">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-end">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-[0.95rem]" style="background-color: rgba(var(--theme-accent-rgb), 0.1); color: var(--theme-accent);">
                        <i class="fa-light fa-magnifying-glass text-sm"></i>
                    </span>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Search schedules')); ?></p>
                        <p class="text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(__('Filter runs by schedule name.')); ?></p>
                    </div>
                </div>

                <div class="mt-3">
                    <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.input','data' => ['wire:model.live.debounce.300ms' => 'search','placeholder' => __('Search AI publishing schedules...')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.300ms' => 'search','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Search AI publishing schedules...'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-[14rem_auto_auto] xl:w-auto xl:items-end">
                <div class="min-w-0">
                    <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Status')); ?></p>
                    <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'status']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($option['value']); ?>"><?php echo e($option['label']); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
                </div>

                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','size' => 'sm','class' => 'h-11 px-5','wire:click' => '$refresh']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','size' => 'sm','class' => 'h-11 px-5','wire:click' => '$refresh']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Apply')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','size' => 'sm','class' => 'h-11 px-5','wire:click' => 'resetFilters']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','size' => 'sm','class' => 'h-11 px-5','wire:click' => 'resetFilters']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Reset')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $runs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $run): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $badge = $this->statusBadge($run);
                $itemsCount = collect((array) $run->prompt_ids)->filter()->count();
                $createdPosts = (int) data_get($runMetrics, $run->id.'.posts', 0);
                $generatedItems = (int) data_get($runMetrics, $run->id.'.generated', 0);
                $failedItems = (int) data_get($runMetrics, $run->id.'.failed', 0);
                $failedPrompts = collect((array) data_get($run->stats, 'failed_prompts', []))->filter(fn ($item) => filled(data_get($item, 'message')));
                $runLogs = collect((array) data_get($run->stats, 'run_logs', []))->filter(fn ($item) => is_array($item))->values();
                $successRatio = $createdPosts > 0 ? min(100, max(0, (int) round((($createdPosts - $failedItems) / max(1, $createdPosts)) * 100))) : 0;
                $weekdayOrder = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                $weekdays = collect((array) data_get($run->schedule_config, 'weekdays', []))
                    ->map(fn ($day) => ucfirst(strtolower((string) $day)))
                    ->filter()
                    ->sortBy(fn ($day) => array_search(strtolower($day), $weekdayOrder, true))
                    ->values();
                $nextRunLabel = $nextRunLabels[$run->id] ?? __('No next run');
                $runTimezone = (string) data_get($run->schedule_config, 'timezone', config('app.timezone'));
            ?>

            <div class="h-full">
                <article class="group relative z-0 flex h-full flex-col overflow-visible rounded-[1.7rem] border" style="border-color: rgba(var(--theme-border-color-rgb), 0.64); background:
                    radial-gradient(circle at top left, rgba(var(--theme-accent-rgb), 0.08), transparent 24%),
                    linear-gradient(180deg, rgba(var(--theme-surface-base-rgb,255,255,255),0.99), rgba(var(--theme-surface-soft-rgb,248,250,252),0.84));
                    box-shadow: 0 22px 50px rgba(15, 23, 42, 0.045);
                ">
                    <div class="flex h-full flex-col gap-3.5 px-6 py-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-start gap-4">
                            <span class="inline-flex h-12 w-12 flex-none items-center justify-center rounded-[1.1rem] border" style="border-color: rgba(124, 58, 237, 0.18); background:
                                radial-gradient(circle at 30% 30%, rgba(221, 214, 254, 0.95), rgba(196, 181, 253, 0.88) 58%, rgba(167, 139, 250, 0.82) 100%);
                                color: #ffffff;
                                box-shadow: inset 0 1px 0 rgba(255,255,255,0.35), 0 10px 24px rgba(124, 58, 237, 0.18);
                            ">
                                <i class="fa-solid fa-sparkles text-[1rem]"></i>
                            </span>

                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.24em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('AI automation')); ?></p>
                                <p class="mt-1 truncate text-[1.15rem] font-semibold tracking-[-0.05em] leading-tight" style="color: var(--theme-header-text-color);"><?php echo e($run->name ?: __('AI Publishing Schedule')); ?></p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em]" style="border-color: color-mix(in srgb, <?php echo e($badge['color']); ?> 18%, rgba(var(--theme-border-color-rgb), 0.42)); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.72); color: <?php echo e($badge['color']); ?>;">
                                        <span class="h-1.5 w-1.5 rounded-full" style="background-color: <?php echo e($badge['color']); ?>;"></span>
                                        <?php echo e($badge['label']); ?>

                                    </span>
                                    <span class="inline-flex items-center rounded-full border px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em]" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.72); color: var(--theme-muted-text-color);">
                                        <?php echo e(number_format($itemsCount)); ?> <?php echo e(__('prompts')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu','data' => ['align' => 'right','width' => 'auto','class' => 'min-w-[13rem]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => 'auto','class' => 'min-w-[13rem]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                             <?php $__env->slot('trigger', null, []); ?> 
                                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-[1rem] border transition" style="border-color: rgba(var(--theme-border-color-rgb), 0.52); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.78); color: var(--theme-header-text-color);">
                                    <i class="fa-light fa-ellipsis text-sm"></i>
                                </button>
                             <?php $__env->endSlot(); ?>

                            <button type="button" class="flex w-full items-center gap-3 rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 transition" style="color: var(--theme-header-text-color);" x-data="{ hover: false }" x-on:mouseenter="hover = true" x-on:mouseleave="hover = false" x-bind:style="hover ? 'background-color: var(--theme-surface-soft); color: var(--theme-header-text-color);' : 'color: var(--theme-header-text-color);'" x-on:click.stop="open = false; document.getElementById('ai-analytics-trigger-<?php echo e($run->id); ?>')?.click()">
                                <i class="fa-light fa-chart-mixed text-[14px]" style="color: var(--theme-muted-text-color);"></i>
                                <span class="min-w-0 flex-1 truncate"><?php echo e(__('View Analytics')); ?></span>
                            </button>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canEditRun($run)): ?>
                                <a href="<?php echo e(route('portal.ai-publishing.edit', $run)); ?>" wire:navigate class="flex w-full items-center gap-3 rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 transition" style="color: var(--theme-header-text-color);" x-data="{ hover: false }" x-on:mouseenter="hover = true" x-on:mouseleave="hover = false" x-bind:style="hover ? 'background-color: var(--theme-surface-soft); color: var(--theme-header-text-color);' : 'color: var(--theme-header-text-color);'" x-on:click.stop="open = false">
                                    <i class="fa-light fa-pen-to-square text-[14px]" style="color: var(--theme-muted-text-color);"></i>
                                    <span class="min-w-0 flex-1 truncate"><?php echo e(__('Edit Setup')); ?></span>
                                </a>
                            <?php else: ?>
                                <div class="flex w-full items-center gap-3 rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 opacity-55" style="color: var(--theme-muted-text-color); cursor: not-allowed;">
                                    <i class="fa-light fa-pen-to-square text-[14px]"></i>
                                    <span class="min-w-0 flex-1 truncate"><?php echo e(__('Edit Setup')); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <a href="<?php echo e(route('portal.publishing.calendar')); ?>" class="flex w-full items-center gap-3 rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 transition" style="color: var(--theme-header-text-color);" x-data="{ hover: false }" x-on:mouseenter="hover = true" x-on:mouseleave="hover = false" x-bind:style="hover ? 'background-color: var(--theme-surface-soft); color: var(--theme-header-text-color);' : 'color: var(--theme-header-text-color);'" x-on:click.stop="open = false">
                                <i class="fa-light fa-arrow-up-right-from-square text-[14px]" style="color: var(--theme-muted-text-color);"></i>
                                <span class="min-w-0 flex-1 truncate"><?php echo e(__('Open Publishing')); ?></span>
                            </a>

                            <button type="button" class="flex w-full items-center gap-3 rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 transition" style="color: var(--theme-header-text-color);" x-data="{ hover: false }" x-on:mouseenter="hover = true" x-on:mouseleave="hover = false" x-bind:style="hover ? 'background-color: var(--theme-surface-soft); color: var(--theme-header-text-color);' : 'color: var(--theme-header-text-color);'" x-on:click.stop="open = false; document.getElementById('ai-log-trigger-<?php echo e($run->id); ?>')?.click()">
                                <i class="fa-light fa-rectangle-history text-[14px]" style="color: var(--theme-muted-text-color);"></i>
                                <span class="min-w-0 flex-1 truncate"><?php echo e(__('View Run Log')); ?></span>
                            </button>

                            <div class="my-1 border-t" style="border-color: color-mix(in srgb, var(--theme-border-color) 58%, transparent);"></div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canDeleteRun($run)): ?>
                                <button type="button" class="flex w-full items-center gap-3 rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 transition" style="color: var(--theme-danger-color);" x-data="{ hover: false }" x-on:mouseenter="hover = true" x-on:mouseleave="hover = false" x-bind:style="hover ? 'background-color: rgba(244, 63, 94, 0.08); color: var(--theme-danger-color);' : 'color: var(--theme-danger-color);'" x-on:click.stop="open = false; document.getElementById('ai-delete-trigger-<?php echo e($run->id); ?>')?.click()">
                                    <i class="fa-light fa-trash text-[14px]" style="color: var(--theme-danger-color);"></i>
                                    <span class="min-w-0 flex-1 truncate"><?php echo e(__('Delete Schedule')); ?></span>
                                </button>
                            <?php else: ?>
                                <div class="flex w-full items-center gap-3 rounded-[0.8rem] px-3 py-2 text-left text-[15px] font-medium leading-6 opacity-55" style="color: var(--theme-danger-color); cursor: not-allowed;">
                                    <i class="fa-light fa-trash text-[14px]"></i>
                                    <span class="min-w-0 flex-1 truncate"><?php echo e(__('Delete Schedule')); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $attributes = $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $component = $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
                    </div>

                    <div class="overflow-hidden rounded-[1rem] border" style="border-color: rgba(var(--theme-border-color-rgb), 0.46); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.52);">
                        <div class="grid gap-0 text-sm sm:grid-cols-2">
                            <div class="flex items-start gap-2.5 px-3.5 py-3" style="background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.32);">
                                <span class="mt-0.5 inline-flex h-7 w-7 flex-none items-center justify-center rounded-full" style="background-color: rgba(var(--theme-accent-rgb), 0.08); color: var(--theme-accent);">
                                    <i class="fa-light fa-clock text-[11px]"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Next run')); ?></span>
                                    <p class="mt-0.5 font-medium" style="color: var(--theme-header-text-color);"><?php echo e($nextRunLabel); ?></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2.5 border-t px-3.5 py-3 sm:border-l sm:border-t-0" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.32);">
                                <span class="mt-0.5 inline-flex h-7 w-7 flex-none items-center justify-center rounded-full" style="background-color: rgba(14, 165, 233, 0.08); color: #0284c7;">
                                    <i class="fa-light fa-share-nodes text-[11px]"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Channels')); ?></span>
                                    <p class="mt-0.5 font-medium truncate" style="color: var(--theme-header-text-color);"><?php echo e(number_format(count((array) $run->account_ids))); ?> <?php echo e(__('selected')); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 border-t px-3.5 py-3 text-sm" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.36);">
                            <span class="mt-0.5 inline-flex h-7 w-7 flex-none items-center justify-center rounded-full" style="background-color: rgba(16, 185, 129, 0.08); color: #059669;">
                                <i class="fa-light fa-repeat text-[11px]"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="text-[10px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Active days')); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($weekdays->isNotEmpty()): ?>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold tracking-[0.02em]" style="border-color: rgba(16, 185, 129, 0.16); background-color: rgba(16, 185, 129, 0.08); color: #047857;">
                                                <?php echo e($day); ?>

                                            </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="mt-0.5 font-medium" style="color: var(--theme-header-text-color);"><?php echo e(__('No days')); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-2.5 sm:grid-cols-3">
                        <div class="rounded-[0.9rem] border px-3 py-2.5" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.56);">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Generated')); ?></p>
                            <p class="mt-1 text-[1.4rem] font-semibold tracking-[-0.05em]" style="color: #7c3aed;"><?php echo e(number_format($generatedItems)); ?></p>
                        </div>
                        <div class="rounded-[0.9rem] border px-3 py-2.5" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.56);">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Posts')); ?></p>
                            <p class="mt-1 text-[1.4rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($createdPosts)); ?></p>
                        </div>
                        <div class="rounded-[0.9rem] border px-3 py-2.5" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.56);">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Failed')); ?></p>
                            <p class="mt-1 text-[1.4rem] font-semibold tracking-[-0.05em]" style="color: <?php echo e($failedItems > 0 ? 'var(--theme-danger-color)' : 'var(--theme-header-text-color)'); ?>;"><?php echo e(number_format($failedItems)); ?></p>
                        </div>
                    </div>

                    <div class="mt-auto flex items-center gap-2 pt-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canRunNow($run)): ?>
                            <?php if (isset($component)) { $__componentOriginal2ea7316722ba0192da1c4e243dcbd20c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dialog','data' => ['width' => 'sm','dismissible' => true,'title' => __('Run AI publishing now?'),'description' => __('This will generate content immediately and try to publish it to the selected channel right away.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => 'sm','dismissible' => true,'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Run AI publishing now?')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('This will generate content immediately and try to publish it to the selected channel right away.'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                 <?php $__env->slot('trigger', null, []); ?> 
                                    <button
                                        type="button"
                                        class="inline-flex h-9 min-w-0 flex-1 items-center justify-center gap-2 whitespace-nowrap rounded-[0.75rem] border px-3.5 text-sm font-semibold tracking-[-0.01em] text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:ring-offset-2 focus:ring-offset-[#f4f6fb] disabled:pointer-events-none disabled:opacity-50"
                                        style="border-color: var(--theme-accent); background-color: var(--theme-accent);"
                                    >
                                        <i class="fa-light fa-play"></i>
                                        <?php echo e(__('Run Now')); ?>

                                    </button>
                                 <?php $__env->endSlot(); ?>

                                 <?php $__env->slot('footer', null, []); ?> 
                                    <div class="flex justify-end gap-3">
                                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'open = false']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Cancel')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','wire:click' => 'runNow('.e($run->id).')','wire:loading.attr' => 'disabled','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'runNow('.e($run->id).')','wire:loading.attr' => 'disabled','x-on:click' => 'open = false']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                            <i class="fa-light fa-play"></i>
                                            <?php echo e(__('Run Now')); ?>

                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                    </div>
                                 <?php $__env->endSlot(); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c)): ?>
<?php $attributes = $__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c; ?>
<?php unset($__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ea7316722ba0192da1c4e243dcbd20c)): ?>
<?php $component = $__componentOriginal2ea7316722ba0192da1c4e243dcbd20c; ?>
<?php unset($__componentOriginal2ea7316722ba0192da1c4e243dcbd20c); ?>
<?php endif; ?>
                        <?php else: ?>
                            <div class="inline-flex h-9 min-w-0 flex-1 items-center justify-center rounded-[0.75rem] border px-3.5 text-sm font-semibold tracking-[-0.01em]" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); color: var(--theme-muted-text-color); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.62);">
                                <?php echo e(__('Processing')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($run->id, $resumableRunIds ?? [], true)): ?>
                            <button
                                type="button"
                                wire:click="startRun(<?php echo e($run->id); ?>)"
                                wire:loading.attr="disabled"
                                class="inline-flex h-9 min-w-0 flex-1 items-center justify-center gap-2 whitespace-nowrap rounded-[0.75rem] border bg-transparent px-3.5 text-sm font-semibold tracking-[-0.01em] text-[var(--theme-header-text-color)] shadow-sm transition-all duration-200 hover:-translate-y-px hover:shadow-[0_14px_28px_-22px_rgba(15,23,42,0.3)] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:ring-offset-2 focus:ring-offset-[#f4f6fb] disabled:pointer-events-none disabled:opacity-50"
                                style="border-color: var(--theme-border-color);"
                            >
                                <i class="fa-light fa-play"></i>
                                <?php echo e(__('Resume')); ?>

                            </button>
                        <?php elseif($this->canStopRun($run)): ?>
                            <button
                                type="button"
                                wire:click="stopRun(<?php echo e($run->id); ?>)"
                                wire:loading.attr="disabled"
                                class="inline-flex h-9 min-w-0 flex-1 items-center justify-center gap-2 whitespace-nowrap rounded-[0.75rem] border bg-transparent px-3.5 text-sm font-semibold tracking-[-0.01em] text-[var(--theme-header-text-color)] shadow-sm transition-all duration-200 hover:-translate-y-px hover:shadow-[0_14px_28px_-22px_rgba(15,23,42,0.3)] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:ring-offset-2 focus:ring-offset-[#f4f6fb] disabled:pointer-events-none disabled:opacity-50"
                                style="border-color: var(--theme-border-color);"
                            >
                                <i class="fa-light fa-pause"></i>
                                <?php echo e(__('Pause')); ?>

                            </button>
                        <?php else: ?>
                            <div class="inline-flex h-9 min-w-0 flex-1 items-center justify-center rounded-[0.75rem] border px-3.5 text-sm font-semibold tracking-[-0.01em]" style="border-color: rgba(var(--theme-border-color-rgb), 0.42); color: var(--theme-muted-text-color); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.62);">
                                <?php echo e(__('Stopped')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    </div>
                </article>

                <?php if (isset($component)) { $__componentOriginal7762953202be6518eecd1cfbd075bf2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7762953202be6518eecd1cfbd075bf2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.modal','data' => ['width' => 'lg','title' => __('AI publishing analytics'),'description' => __('Quick schedule stats for this run.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => 'lg','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('AI publishing analytics')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Quick schedule stats for this run.'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                     <?php $__env->slot('trigger', null, []); ?> 
                        <button type="button" id="ai-analytics-trigger-<?php echo e($run->id); ?>" class="hidden"></button>
                     <?php $__env->endSlot(); ?>

                    <div class="space-y-5">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-[1.05rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.8);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Next run')); ?></p>
                                <p class="mt-2 text-[1.1rem] font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e($nextRunLabel); ?></p>
                            </div>

                            <div class="rounded-[1.05rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.8);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Channels')); ?></p>
                                <p class="mt-2 text-[1.6rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format(count((array) $run->account_ids))); ?></p>
                            </div>

                            <div class="rounded-[1.05rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.8);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Generated')); ?></p>
                                <p class="mt-2 text-[1.6rem] font-semibold tracking-[-0.04em]" style="color: #7c3aed;"><?php echo e(number_format($generatedItems)); ?></p>
                            </div>

                            <div class="rounded-[1.05rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.8);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Posts')); ?></p>
                                <p class="mt-2 text-[1.6rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($createdPosts)); ?></p>
                            </div>

                            <div class="rounded-[1.05rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.8);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Failed')); ?></p>
                                <p class="mt-2 text-[1.6rem] font-semibold tracking-[-0.04em]" style="color: <?php echo e($failedItems > 0 ? 'var(--theme-danger-color)' : 'var(--theme-header-text-color)'); ?>;"><?php echo e(number_format($failedItems)); ?></p>
                            </div>

                            <div class="rounded-[1.05rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background:
                                linear-gradient(180deg, rgba(var(--theme-accent-rgb), 0.08), rgba(16, 185, 129, 0.05));
                            ">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Output health')); ?></p>
                                <p class="mt-2 text-[1.6rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e($successRatio); ?>%</p>
                                <div class="mt-3 h-2 overflow-hidden rounded-full" style="background-color: rgba(var(--theme-border-color-rgb), 0.16);">
                                    <div class="h-full rounded-full" style="width: <?php echo e($successRatio); ?>%; background: linear-gradient(90deg, #7c3aed, #10b981);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7762953202be6518eecd1cfbd075bf2f)): ?>
<?php $attributes = $__attributesOriginal7762953202be6518eecd1cfbd075bf2f; ?>
<?php unset($__attributesOriginal7762953202be6518eecd1cfbd075bf2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7762953202be6518eecd1cfbd075bf2f)): ?>
<?php $component = $__componentOriginal7762953202be6518eecd1cfbd075bf2f; ?>
<?php unset($__componentOriginal7762953202be6518eecd1cfbd075bf2f); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2ea7316722ba0192da1c4e243dcbd20c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dialog','data' => ['width' => 'lg','dismissible' => true,'title' => __('AI Publishing Log'),'description' => __('Recent run activity for this schedule, including generation and publish attempts.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => 'lg','dismissible' => true,'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('AI Publishing Log')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Recent run activity for this schedule, including generation and publish attempts.'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                     <?php $__env->slot('trigger', null, []); ?> 
                        <button type="button" id="ai-log-trigger-<?php echo e($run->id); ?>" class="hidden"></button>
                     <?php $__env->endSlot(); ?>

                    <div class="max-h-[70vh] space-y-3 overflow-y-auto pr-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_2 = true; $__currentLoopData = $runLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $level = (string) data_get($log, 'level', 'info');
                                $levelColor = match ($level) {
                                    'success' => '#059669',
                                    'warning' => '#b45309',
                                    'error' => 'var(--theme-danger-color)',
                                    default => 'var(--theme-accent)',
                                };
                                $loggedAt = trim((string) data_get($log, 'logged_at', ''));
                                $loggedAtLabel = $loggedAt !== ''
                                    ? \Carbon\Carbon::parse($loggedAt)->timezone($runTimezone)->format('d/m/Y H:i:s')
                                    : __('Unknown');
                            ?>
                            <div class="rounded-[1rem] border px-4 py-3" style="border-color: color-mix(in srgb, <?php echo e($levelColor); ?> 18%, rgba(var(--theme-border-color-rgb), 0.44)); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.82);">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(data_get($log, 'message', __('Activity recorded'))); ?></p>
                                        <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs" style="color: var(--theme-muted-text-color);">
                                            <span><?php echo e($loggedAtLabel); ?></span>
                                            <span><?php echo e($runTimezone); ?></span>
                                            <span><?php echo e(str(data_get($log, 'stage', 'info'))->headline()); ?></span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled(data_get($log, 'prompt_id'))): ?>
                                                <span>#<?php echo e(data_get($log, 'prompt_id')); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled(data_get($log, 'account_label'))): ?>
                                                <span><?php echo e(data_get($log, 'account_label')); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled(data_get($log, 'post_id'))): ?>
                                                <span><?php echo e(__('Post #:id', ['id' => data_get($log, 'post_id')])); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled(data_get($log, 'prompt'))): ?>
                                            <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(data_get($log, 'prompt')); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold" style="border-color: color-mix(in srgb, <?php echo e($levelColor); ?> 18%, rgba(var(--theme-border-color-rgb), 0.4)); color: <?php echo e($levelColor); ?>; background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.82);">
                                        <?php echo e(str($level)->headline()); ?>

                                    </span>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <div class="rounded-[1rem] border border-dashed px-4 py-8 text-center text-sm" style="border-color: rgba(var(--theme-border-color-rgb), 0.44); color: var(--theme-muted-text-color);">
                                <?php echo e(__('No run log yet.')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c)): ?>
<?php $attributes = $__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c; ?>
<?php unset($__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ea7316722ba0192da1c4e243dcbd20c)): ?>
<?php $component = $__componentOriginal2ea7316722ba0192da1c4e243dcbd20c; ?>
<?php unset($__componentOriginal2ea7316722ba0192da1c4e243dcbd20c); ?>
<?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canDeleteRun($run)): ?>
                    <?php if (isset($component)) { $__componentOriginal2ea7316722ba0192da1c4e243dcbd20c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dialog','data' => ['width' => 'sm','dismissible' => true,'title' => __('Delete this AI publishing schedule?'),'description' => __('This removes the schedule and keeps published history.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => 'sm','dismissible' => true,'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Delete this AI publishing schedule?')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('This removes the schedule and keeps published history.'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                         <?php $__env->slot('trigger', null, []); ?> 
                            <button type="button" id="ai-delete-trigger-<?php echo e($run->id); ?>" class="hidden"></button>
                         <?php $__env->endSlot(); ?>

                         <?php $__env->slot('footer', null, []); ?> 
                            <div class="flex justify-end gap-3">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'open = false']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Cancel')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'danger','wire:click' => 'deleteRun('.e($run->id).')','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'danger','wire:click' => 'deleteRun('.e($run->id).')','x-on:click' => 'open = false']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <?php echo e(__('Delete')); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                            </div>
                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c)): ?>
<?php $attributes = $__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c; ?>
<?php unset($__attributesOriginal2ea7316722ba0192da1c4e243dcbd20c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ea7316722ba0192da1c4e243dcbd20c)): ?>
<?php $component = $__componentOriginal2ea7316722ba0192da1c4e243dcbd20c; ?>
<?php unset($__componentOriginal2ea7316722ba0192da1c4e243dcbd20c); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="md:col-span-2 xl:col-span-3">
                <div class="rounded-[1.7rem] border border-dashed px-6 py-10 text-center" style="border-color: rgba(var(--theme-border-color-rgb), 0.52); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.45);">
                    <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.empty','data' => ['icon' => 'fa-light fa-sparkles','title' => __('No AI publishing schedules found.'),'description' => __('Create your first AI publishing schedule to start generating posts automatically.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-sparkles','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No AI publishing schedules found.')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Create your first AI publishing schedule to start generating posts automatically.'))]); ?>
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

                    <div class="mt-5">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => route('portal.ai-publishing.create'),'size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.ai-publishing.create')),'size' => 'lg']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <i class="fa-light fa-plus"></i>
                            <?php echo e(__('Create AI Publishing')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($runs->hasPages()): ?>
        <div><?php echo e($runs->links()); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppAiPublishing\Providers/../Resources/views/livewire/index.blade.php ENDPATH**/ ?>