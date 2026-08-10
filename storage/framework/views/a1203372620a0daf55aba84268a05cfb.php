<?php
    $limit = (int) ($metrics['limit'] ?? 0);
    $usagePercent = $metrics['usage_percent'] ?? null;
    $limitLabel = $limit > 0 ? number_format($limit) : __('Unlimited');
?>

<?php if (isset($component)) { $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dashboard-module','data' => ['eyebrow' => __('Publishing'),'title' => null,'description' => null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dashboard-module'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Publishing')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="space-y-4">
        <div class="max-w-full overflow-hidden rounded-[1.55rem] border" style="border-color: rgba(var(--theme-accent-rgb,37,99,235),0.12); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.03))); box-shadow: 0 26px 60px -52px rgba(15,23,42,0.22);">
            <div class="flex min-w-0 flex-col gap-5 p-4 sm:p-5 xl:p-6">
                <div class="min-w-0 rounded-[1.2rem] border px-4 py-4 sm:px-6" style="border-color: rgba(var(--theme-border-color-rgb),0.5); background:
                    radial-gradient(circle at top left, rgba(var(--theme-accent-rgb),0.1), transparent 34%),
                    linear-gradient(135deg, color-mix(in srgb, var(--theme-surface-overlay) 95%, transparent), color-mix(in srgb, var(--theme-surface-base) 93%, rgba(var(--theme-accent-rgb),0.03)));">
                    <div class="flex min-w-0 flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="border-color: rgba(var(--theme-accent-rgb),0.16); background: rgba(var(--theme-accent-rgb),0.08); color: var(--theme-accent);">
                                    <i class="fa-light fa-calendar-days mr-1.5 text-[10px]"></i><?php echo e(__('Publishing performance')); ?>

                                </span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="background: rgba(var(--theme-success-color-rgb),0.1); color: var(--theme-success-color);">
                                    <?php echo e(__('Queue in motion')); ?>

                                </span>
                            </div>
                            <h3 class="mt-3 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Measure output, trends, and usage before the month runs away')); ?></h3>
                            <p class="mt-2 max-w-3xl text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Track monthly post volume, watch the last 7 days of activity, and compare current usage against your publishing plan limit.')); ?></p>
                        </div>

                        <div class="flex w-full min-w-0 flex-wrap items-center gap-3 lg:w-auto lg:shrink-0">
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $item['route'] ?? route('portal.publishing.calendar'),'size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['route'] ?? route('portal.publishing.calendar')),'size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Open calendar')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $campaignRoute,'variant' => 'outline','size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaignRoute),'variant' => 'outline','size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Campaigns')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $labelRoute,'variant' => 'outline','size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($labelRoute),'variant' => 'outline','size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Labels')); ?> <?php echo $__env->renderComponent(); ?>
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

                <div class="grid min-w-0 gap-5 xl:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
                <div class="min-w-0 space-y-4">
                    <div class="grid min-w-0 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(91,124,250,0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(91,124,250,0.32);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(91,124,250,0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(91,124,250,0.32);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(91,124,250,0.95), rgba(91,124,250,0.28));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['month_total'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('This month')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Posts created this month')); ?></p>
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

                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(243,181,98,0.2); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(243,181,98,0.28);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(243,181,98,0.2); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(243,181,98,0.28);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(243,181,98,0.92), rgba(243,181,98,0.24));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['scheduled'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Scheduled')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Ready to publish')); ?></p>
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

                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(111,207,151,0.2); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(111,207,151,0.28);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(111,207,151,0.2); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(111,207,151,0.28);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(111,207,151,0.94), rgba(111,207,151,0.24));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['published'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Published')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Sent successfully this month')); ?></p>
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

                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(177,138,232,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(177,138,232,0.28);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(177,138,232,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(177,138,232,0.28);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(177,138,232,0.92), rgba(177,138,232,0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['drafts'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Drafts')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Still waiting for scheduling')); ?></p>
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
                    </div>

                    <div class="grid min-w-0 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(242,139,130,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(242,139,130,0.28);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(242,139,130,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(242,139,130,0.28);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(242,139,130,0.94), rgba(242,139,130,0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['failed'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Failed')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Posts needing attention')); ?></p>
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

                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(103,183,220,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(103,183,220,0.28);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(103,183,220,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(103,183,220,0.28);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(103,183,220,0.94), rgba(103,183,220,0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['processing'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Processing')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Currently running')); ?></p>
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

                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(94,199,194,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(94,199,194,0.28);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(94,199,194,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(94,199,194,0.28);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(94,199,194,0.94), rgba(94,199,194,0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['upcoming'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Next 7 days')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Upcoming scheduled queue')); ?></p>
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

                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(243,181,98,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(243,181,98,0.28);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(243,181,98,0.18); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(243,181,98,0.28);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, rgba(243,181,98,0.94), rgba(243,181,98,0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e($metrics['publish_success_rate'] !== null ? $metrics['publish_success_rate'].'%' : '—'); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Success rate')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Published vs failed this month')); ?></p>
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
                    </div>

                    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-4','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: color-mix(in srgb, var(--theme-surface-overlay) 88%, transparent); box-shadow: 0 22px 44px -40px rgba(15,23,42,0.16);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: color-mix(in srgb, var(--theme-surface-overlay) 88%, transparent); box-shadow: 0 22px 44px -40px rgba(15,23,42,0.16);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Monthly limit')); ?></p>
                                <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);">
                                    <?php echo e($limit > 0
                                        ? __(':used of :limit posts used this month', ['used' => number_format((int) ($metrics['month_total'] ?? 0)), 'limit' => $limitLabel])
                                        : __('This plan does not enforce a monthly publishing cap.')); ?>

                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($limitLabel); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($usagePercent !== null): ?>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e($usagePercent); ?>%</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <div class="h-3 overflow-hidden rounded-full" style="background: rgba(var(--theme-border-color-rgb),0.24);">
                            <div
                                class="h-full rounded-full transition-all"
                                style="width: <?php echo e($usagePercent ?? 12); ?>%; background: linear-gradient(90deg, #5b7cfa, #6fcf97, #b18ae8);"
                            ></div>
                        </div>

                        <div class="grid min-w-0 gap-4 sm:grid-cols-3">
                            <div class="rounded-[1rem] border p-4" style="border-color: rgba(91,124,250,0.12); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, rgba(91,124,250,0.03));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Campaigns')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['campaigns'] ?? 0))); ?></p>
                            </div>

                            <div class="rounded-[1rem] border p-4" style="border-color: rgba(94,199,194,0.14); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, rgba(94,199,194,0.03));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Labels')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['labels'] ?? 0))); ?></p>
                            </div>

                            <div class="rounded-[1rem] border p-4" style="border-color: rgba(243,181,98,0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, rgba(243,181,98,0.03));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Active accounts')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['accounts'] ?? 0))); ?></p>
                            </div>
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
                </div>

                <?php if (isset($component)) { $__componentOriginalc751055b5fd59125696b302ff2a0d8e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc751055b5fd59125696b302ff2a0d8e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.chart','data' => ['class' => 'min-w-0','title' => __('Posting trend'),'description' => __('Daily publishing volume over the last 7 days.'),'type' => 'column','categories' => $chart['categories'],'series' => $chart['series'],'height' => 520]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'min-w-0','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Posting trend')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Daily publishing volume over the last 7 days.')),'type' => 'column','categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chart['categories']),'series' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chart['series']),'height' => 520]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc751055b5fd59125696b302ff2a0d8e5)): ?>
<?php $attributes = $__attributesOriginalc751055b5fd59125696b302ff2a0d8e5; ?>
<?php unset($__attributesOriginalc751055b5fd59125696b302ff2a0d8e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc751055b5fd59125696b302ff2a0d8e5)): ?>
<?php $component = $__componentOriginalc751055b5fd59125696b302ff2a0d8e5; ?>
<?php unset($__componentOriginalc751055b5fd59125696b302ff2a0d8e5); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid min-w-0 gap-4 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
            <?php if (isset($component)) { $__componentOriginalc751055b5fd59125696b302ff2a0d8e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc751055b5fd59125696b302ff2a0d8e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.chart','data' => ['class' => 'min-w-0','title' => __('Status mix'),'description' => __('How the publishing workload for this month is distributed by status.'),'type' => 'donut','series' => $statusChart,'height' => 360]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'min-w-0','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Status mix')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('How the publishing workload for this month is distributed by status.')),'type' => 'donut','series' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusChart),'height' => 360]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc751055b5fd59125696b302ff2a0d8e5)): ?>
<?php $attributes = $__attributesOriginalc751055b5fd59125696b302ff2a0d8e5; ?>
<?php unset($__attributesOriginalc751055b5fd59125696b302ff2a0d8e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc751055b5fd59125696b302ff2a0d8e5)): ?>
<?php $component = $__componentOriginalc751055b5fd59125696b302ff2a0d8e5; ?>
<?php unset($__componentOriginalc751055b5fd59125696b302ff2a0d8e5); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginalc751055b5fd59125696b302ff2a0d8e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc751055b5fd59125696b302ff2a0d8e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.chart','data' => ['class' => 'min-w-0','title' => __('Network mix'),'description' => __('Where publishing activity is concentrated this month.'),'type' => 'bar','series' => $providerChart,'height' => 360]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'min-w-0','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Network mix')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Where publishing activity is concentrated this month.')),'type' => 'bar','series' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($providerChart),'height' => 360]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc751055b5fd59125696b302ff2a0d8e5)): ?>
<?php $attributes = $__attributesOriginalc751055b5fd59125696b302ff2a0d8e5; ?>
<?php unset($__attributesOriginalc751055b5fd59125696b302ff2a0d8e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc751055b5fd59125696b302ff2a0d8e5)): ?>
<?php $component = $__componentOriginalc751055b5fd59125696b302ff2a0d8e5; ?>
<?php unset($__componentOriginalc751055b5fd59125696b302ff2a0d8e5); ?>
<?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rssSummary || $aiPublishingSummary): ?>
            <div class="grid min-w-0 gap-4 xl:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rssSummary): ?>
                    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-4','style' => 'border-color: rgba(91,124,250,0.16); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(91,124,250,0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(91,124,250,0.02))); box-shadow: 0 20px 42px -36px rgba(91,124,250,0.22);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4','style' => 'border-color: rgba(91,124,250,0.16); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(91,124,250,0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(91,124,250,0.02))); box-shadow: 0 20px 42px -36px rgba(91,124,250,0.22);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Automation lane')); ?></p>
                                <p class="mt-2 text-lg font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e(__('RSS Schedules')); ?></p>
                                <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e($rssSummary['description']); ?></p>
                            </div>
                            <a href="<?php echo e($rssSummary['route']); ?>" wire:navigate class="inline-flex h-10 w-10 items-center justify-center rounded-full transition hover:-translate-y-[1px]" style="background: linear-gradient(145deg, rgba(91,124,250,0.14), color-mix(in srgb, var(--theme-surface-overlay) 94%, transparent)); color: #5b7cfa; box-shadow: inset 0 1px 0 color-mix(in srgb, var(--theme-surface-overlay) 72%, transparent);">
                                <i class="fa-light fa-rss text-base"></i>
                            </a>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3">
                            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(91,124,250,0.14); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, rgba(91,124,250,0.03));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Active feeds')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($rssSummary['active'] ?? 0))); ?></p>
                            </div>
                            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb),0.44); background: color-mix(in srgb, var(--theme-surface-overlay) 86%, transparent);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Queued posts')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($rssSummary['queued'] ?? 0))); ?></p>
                            </div>
                            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb),0.44); background: color-mix(in srgb, var(--theme-surface-overlay) 86%, transparent);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Paused')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($rssSummary['paused'] ?? 0))); ?></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 border-t pt-3 text-sm" style="border-color: rgba(var(--theme-border-color-rgb),0.36); color: var(--theme-muted-text-color);">
                            <span><?php echo e(__('Total schedules: :count', ['count' => number_format((int) ($rssSummary['total'] ?? 0))])); ?></span>
                            <span><?php echo e(__('Next run: :time', ['time' => $rssSummary['next_run_label'] ?? __('No next run')])); ?></span>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($aiPublishingSummary): ?>
                    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-4','style' => 'border-color: rgba(94,199,194,0.18); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(94,199,194,0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(94,199,194,0.02))); box-shadow: 0 20px 42px -36px rgba(94,199,194,0.22);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4','style' => 'border-color: rgba(94,199,194,0.18); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(94,199,194,0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(94,199,194,0.02))); box-shadow: 0 20px 42px -36px rgba(94,199,194,0.22);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Automation lane')); ?></p>
                                <p class="mt-2 text-lg font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e(__('AI Publishing')); ?></p>
                                <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e($aiPublishingSummary['description']); ?></p>
                            </div>
                            <a href="<?php echo e($aiPublishingSummary['route']); ?>" wire:navigate class="inline-flex h-10 w-10 items-center justify-center rounded-full transition hover:-translate-y-[1px]" style="background: linear-gradient(145deg, rgba(94,199,194,0.16), color-mix(in srgb, var(--theme-surface-overlay) 94%, transparent)); color: #1e9d93; box-shadow: inset 0 1px 0 color-mix(in srgb, var(--theme-surface-overlay) 72%, transparent);">
                                <i class="fa-light fa-sparkles text-base"></i>
                            </a>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3">
                            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(94,199,194,0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, rgba(94,199,194,0.03));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Runs this month')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($aiPublishingSummary['month_total'] ?? 0))); ?></p>
                            </div>
                            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb),0.44); background: color-mix(in srgb, var(--theme-surface-overlay) 86%, transparent);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Queued now')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($aiPublishingSummary['queued'] ?? 0))); ?></p>
                            </div>
                            <div class="rounded-[0.95rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb),0.44); background: color-mix(in srgb, var(--theme-surface-overlay) 86%, transparent);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Completed')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($aiPublishingSummary['completed'] ?? 0))); ?></p>
                            </div>
                        </div>

                        <div class="space-y-2 border-t pt-3" style="border-color: rgba(var(--theme-border-color-rgb),0.36);">
                            <div class="flex items-center justify-between gap-3 text-xs" style="color: var(--theme-muted-text-color);">
                                <span>
                                    <?php echo e(($aiPublishingSummary['limit'] ?? 0) > 0
                                        ? __(':used of :limit AI runs used', ['used' => number_format((int) ($aiPublishingSummary['month_total'] ?? 0)), 'limit' => number_format((int) ($aiPublishingSummary['limit'] ?? 0))])
                                        : __('Unlimited AI publishing usage on this plan.')); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($aiPublishingSummary['usage_percent'] ?? null) !== null): ?>
                                    <span><?php echo e($aiPublishingSummary['usage_percent']); ?>%</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full" style="background: rgba(var(--theme-border-color-rgb),0.24);">
                                <div
                                    class="h-full rounded-full"
                                    style="width: <?php echo e($aiPublishingSummary['usage_percent'] ?? 14); ?>%; background: linear-gradient(90deg, rgba(94,199,194,0.98), rgba(var(--theme-accent-rgb),0.9));"
                                ></div>
                            </div>
                            <div class="flex items-center justify-between gap-3 text-xs" style="color: var(--theme-muted-text-color);">
                                <span><?php echo e(__('Failed runs: :count', ['count' => number_format((int) ($aiPublishingSummary['failed'] ?? 0))])); ?></span>
                                <span><?php echo e(__('Total runs: :count', ['count' => number_format((int) ($aiPublishingSummary['total'] ?? 0))])); ?></span>
                            </div>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid min-w-0 gap-4 xl:grid-cols-[minmax(0,0.78fr)_minmax(0,1.22fr)]">
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'min-w-0 space-y-6','style' => 'background: color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'min-w-0 space-y-6','style' => 'background: color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Publishing insights')); ?></p>
                        <p class="mt-2 text-lg font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Top campaigns and labels')); ?></p>
                    </div>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full" style="background: rgba(var(--theme-accent-rgb),0.10); color: var(--theme-accent);">
                        <i class="fa-light fa-layer-group text-base"></i>
                    </span>
                </div>

                <div class="space-y-5">
                    <section class="space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Top campaigns')); ?></p>
                                <p class="mt-1 text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(__('Which campaign shells are driving the most publishing volume.')); ?></p>
                            </div>
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background: rgba(91,124,250,0.10); color: #5b7cfa;">
                                <i class="fa-light fa-bullhorn"></i>
                            </span>
                        </div>

                        <div class="space-y-3 max-h-[14rem] overflow-y-auto pr-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex items-center justify-between gap-4 rounded-[1rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb),0.56); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent);">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold" style="background: rgba(91,124,250,0.12); color: #5b7cfa;">
                                            <i class="fa-light fa-star"></i>
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($campaign['name']); ?></p>
                                            <p class="mt-1 text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(__('Campaign rank #:rank', ['rank' => $index + 1])); ?></p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) $campaign['count'])); ?></span>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <div class="rounded-[1rem] border px-5 py-6 text-center" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-overlay) 70%, transparent);">
                                    <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('No campaign data yet')); ?></p>
                                    <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Campaign performance will appear here once posts start using campaign assignments.')); ?></p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </section>

                    <section class="space-y-3 border-t pt-5" style="border-color: rgba(var(--theme-border-color-rgb),0.42);">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Top labels')); ?></p>
                                <p class="mt-1 text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(__('The labels that are showing up most across your recent queue.')); ?></p>
                            </div>
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background: rgba(94,199,194,0.10); color: #5ec7c2;">
                                <i class="fa-light fa-tags"></i>
                            </span>
                        </div>

                        <div class="max-h-[17rem] overflow-y-auto pr-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="<?php echo e($index === 0 ? '' : 'mt-3'); ?>">
                                    <div class="flex items-center justify-between gap-3 rounded-[1rem] border px-4 py-3" style="border-color: rgba(94,199,194,0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent);">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold" style="background: rgba(94,199,194,0.12); color: #5ec7c2;">
                                                <i class="fa-light fa-tags"></i>
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($label['name']); ?></p>
                                                <p class="mt-1 text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(__('Label rank #:rank', ['rank' => $index + 1])); ?></p>
                                            </div>
                                        </div>
                                        <span class="shrink-0 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) $label['count'])); ?></span>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.empty','data' => ['icon' => 'fa-light fa-tags','title' => __('No label data yet'),'description' => __('Label usage will appear here once publishing posts start carrying labels.'),'class' => 'rounded-[1rem] border px-5 py-6','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-overlay) 70%, transparent);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-tags','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No label data yet')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Label usage will appear here once publishing posts start carrying labels.')),'class' => 'rounded-[1rem] border px-5 py-6','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-overlay) 70%, transparent);']); ?>
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
                        </div>
                    </section>
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

            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'min-w-0 space-y-4','style' => 'background: color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'min-w-0 space-y-4','style' => 'background: color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Recent queue')); ?></p>
                        <p class="mt-2 text-lg font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Recent editable posts')); ?></p>
                    </div>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full" style="background: rgba(243,181,98,0.12); color: #d88f38;">
                        <i class="fa-light fa-clock-rotate-left text-base"></i>
                    </span>
                </div>

                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a
                            href="<?php echo e($post['post_url'] && $post['status'] === __('Published') ? $post['post_url'] : $post['route']); ?>"
                            <?php if($post['post_url'] && $post['status'] === __('Published')): ?>
                                target="_blank" rel="noreferrer"
                            <?php else: ?>
                                wire:navigate
                            <?php endif; ?>
                            class="block rounded-[1rem] border p-3 transition hover:-translate-y-[1px]"
                            style="border-color: rgba(var(--theme-border-color-rgb),0.58); background: color-mix(in srgb, var(--theme-surface-overlay) 94%, transparent); box-shadow: 0 18px 34px -34px rgba(15,23,42,0.18);"
                        >
                            <div class="flex items-start gap-3">
                                <div class="relative h-[4.75rem] w-[4.75rem] shrink-0 overflow-hidden rounded-[0.95rem] border" style="border-color: rgba(var(--theme-border-color-rgb),0.58); background: linear-gradient(135deg, rgba(91,124,250,0.12), rgba(111,207,151,0.1));">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post['media_preview']): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post['is_video']): ?>
                                            <video class="h-full w-full object-cover" muted playsinline preload="metadata">
                                                <source src="<?php echo e($post['media_preview']); ?>" type="<?php echo e($post['media_mime'] ?: 'video/mp4'); ?>">
                                            </video>
                                            <span class="absolute inset-0 flex items-center justify-center">
                                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-950/65 text-white">
                                                    <i class="fa-solid fa-play text-[11px]"></i>
                                                </span>
                                            </span>
                                        <?php else: ?>
                                            <img src="<?php echo e($post['media_preview']); ?>" alt="<?php echo e($post['title']); ?>" class="h-full w-full object-cover" loading="lazy" decoding="async">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <div class="flex h-full w-full items-center justify-center text-xl" style="color: var(--theme-muted-text-color);">
                                            <i class="fa-light <?php echo e($post['media_type'] === 'VIDEO' || $post['media_type'] === 'REEL' ? 'fa-video' : ($post['media_type'] === 'IMAGE' || $post['media_type'] === 'CAROUSEL' ? 'fa-image' : 'fa-align-left')); ?>"></i>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($post['title']); ?></p>
                                            <p class="mt-1 text-xs" style="color: var(--theme-muted-text-color);">
                                                <?php echo e($post['network']); ?><?php echo e($post['handle'] ? ' • '.$post['handle'] : ''); ?>

                                            </p>
                                        </div>
                                        <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em]" style="background: <?php echo e($post['status_tone']['surface']); ?>; color: <?php echo e($post['status_tone']['text']); ?>;">
                                            <?php echo e($post['status']); ?>

                                        </span>
                                    </div>

                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em]" style="background: rgba(var(--theme-border-color-rgb),0.08); color: var(--theme-muted-text-color);">
                                            <?php echo e($post['media_type']); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post['media_count'] > 1): ?>
                                            <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold" style="background: rgba(91,124,250,0.08); color: #5b7cfa;">
                                                <?php echo e(__(':count files', ['count' => $post['media_count']])); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <span
                                            class="rounded-full px-2.5 py-1 text-[10px] font-semibold"
                                            style="<?php echo e($post['campaign']
                                                ? 'background: color-mix(in srgb, '.($post['campaign_color'] ?: '#c9802a').' 14%, white); color: '.($post['campaign_color'] ?: '#c9802a').';'
                                                : 'background: rgba(243,181,98,0.08); color: #c9802a;'); ?>"
                                        >
                                            <?php echo e($post['campaign'] ?: __('No campaign')); ?>

                                        </span>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post['caption']): ?>
                                        <p class="mt-3 text-xs leading-5" style="color: var(--theme-muted-text-color); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?php echo e($post['caption']); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post['status'] === __('Failed') && $post['error']): ?>
                                        <div class="mt-3 rounded-[0.85rem] border px-3 py-2 text-xs leading-5" style="border-color: rgba(242,139,130,0.26); background: rgba(242,139,130,0.08); color: #b85b55;">
                                            <span class="font-semibold"><?php echo e(__('Error')); ?>:</span>
                                            <?php echo e($post['error']); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div class="mt-3 flex items-center justify-between gap-3 border-t pt-3 text-[11px]" style="border-color: rgba(var(--theme-border-color-rgb),0.38); color: var(--theme-muted-text-color);">
                                        <div class="flex items-center gap-3">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post['scheduled']): ?>
                                                <span class="inline-flex items-center gap-1.5">
                                                    <i class="fa-light fa-calendar-clock"></i>
                                                    <?php echo e($post['scheduled']); ?>

                                                </span>
                                            <?php elseif($post['created']): ?>
                                                <span class="inline-flex items-center gap-1.5">
                                                    <i class="fa-light fa-clock"></i>
                                                    <?php echo e($post['created']); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post['post_url'] && $post['status'] === __('Published')): ?>
                                            <span class="inline-flex items-center gap-1.5 font-semibold" style="color: var(--theme-accent);">
                                                <i class="fa-light fa-arrow-up-right-from-square"></i>
                                                <?php echo e(__('View post')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 font-semibold" style="color: var(--theme-accent);">
                                                <i class="fa-light <?php echo e($post['editable'] ? 'fa-pen-to-square' : 'fa-calendar-lines-pen'); ?>"></i>
                                                <?php echo e($post['editable'] ? __('Edit post') : __('Open calendar')); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.empty','data' => ['icon' => 'fa-light fa-clock-rotate-left','title' => __('No recent posts yet'),'description' => __('Recent scheduled, draft, and published posts with media previews will appear here once the queue starts filling up.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-clock-rotate-left','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No recent posts yet')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Recent scheduled, draft, and published posts with media previews will appear here once the queue starts filling up.'))]); ?>
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
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14)): ?>
<?php $attributes = $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14; ?>
<?php unset($__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal768f58f25b9a8ce19e8fe883a0495f14)): ?>
<?php $component = $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14; ?>
<?php unset($__componentOriginal768f58f25b9a8ce19e8fe883a0495f14); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppPublishing\Providers/../Resources/views/dashboard/user-summary.blade.php ENDPATH**/ ?>