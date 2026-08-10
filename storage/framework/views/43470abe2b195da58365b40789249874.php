<?php
    $limit = (int) ($metrics['limit'] ?? -1);
    $usagePercent = $metrics['usage_percent'] ?? null;
    $limitLabel = $limit > -1 ? number_format($limit) : __('Unlimited');
?>

<?php if (isset($component)) { $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dashboard-module','data' => ['eyebrow' => __('Channels'),'title' => null,'description' => null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dashboard-module'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Channels')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="space-y-4">
        <div class="max-w-full overflow-hidden border" style="border-radius: var(--theme-card-radius, 1.15rem); border-color: rgba(var(--theme-accent-rgb),0.12); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.03))); box-shadow: 0 26px 60px -52px rgba(15,23,42,0.22);">
            <div class="flex min-w-0 flex-col gap-5 p-4 sm:p-5 xl:p-6">
                <div class="min-w-0 rounded-[1.2rem] border px-4 py-4 sm:px-6" style="border-color: rgba(var(--theme-border-color-rgb),0.5); background:
                    radial-gradient(circle at top left, rgba(var(--theme-accent-rgb),0.1), transparent 34%),
                    linear-gradient(135deg, color-mix(in srgb, var(--theme-surface-overlay) 95%, transparent), color-mix(in srgb, var(--theme-surface-base) 93%, rgba(var(--theme-accent-rgb),0.03)));">
                    <div class="flex min-w-0 flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="border-color: rgba(var(--theme-accent-rgb),0.16); background: rgba(var(--theme-accent-rgb),0.08); color: var(--theme-accent);">
                                    <i class="fa-light fa-share-nodes mr-1.5 text-[10px]"></i><?php echo e(__('Channel overview')); ?>

                                </span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="background: rgba(var(--theme-success-color-rgb),0.1); color: var(--theme-success-color);">
                                    <?php echo e(__('Publishing ready')); ?>

                                </span>
                            </div>
                            <h3 class="mt-3 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(__('See coverage, activity, and provider spread at a glance')); ?></h3>
                            <p class="mt-2 max-w-3xl text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Track connected destinations, active accounts, and recent growth before moving into publishing operations.')); ?></p>
                        </div>

                        <div class="flex w-full min-w-0 flex-wrap items-center gap-3 lg:w-auto lg:shrink-0">
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $item['route'] ?? route('portal.channels'),'size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['route'] ?? route('portal.channels')),'size' => 'sm','class' => 'w-full justify-center px-3 text-center whitespace-normal sm:w-auto','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Open channels')); ?> <?php echo $__env->renderComponent(); ?>
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

                <div class="grid min-w-0 gap-5 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
                <div class="min-w-0 space-y-4">
                    <div class="grid min-w-0 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(var(--theme-accent-rgb),0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(var(--theme-accent-rgb),0.32);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(var(--theme-accent-rgb),0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(var(--theme-accent-rgb),0.32);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, var(--theme-accent), rgba(var(--theme-accent-rgb),0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['total'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Total channels')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('All connected accounts')); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(var(--theme-success-color-rgb),0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(var(--theme-success-color-rgb),0.24);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(var(--theme-success-color-rgb),0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(var(--theme-success-color-rgb),0.24);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, var(--theme-success-color), rgba(var(--theme-success-color-rgb),0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['active'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Active')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Ready for publishing and sync')); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['padding' => 'md','style' => 'border-color: rgba(var(--theme-warning-color-rgb),0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(var(--theme-warning-color-rgb),0.22);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'md','style' => 'border-color: rgba(var(--theme-warning-color-rgb),0.16); background: color-mix(in srgb, var(--theme-surface-overlay) 90%, transparent); box-shadow: 0 18px 38px -34px rgba(var(--theme-warning-color-rgb),0.22);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="mb-4 block h-1.5 w-11 rounded-full" style="background: linear-gradient(90deg, var(--theme-warning-color), rgba(var(--theme-warning-color-rgb),0.22));"></span>
                            <p class="text-[2rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['providers'] ?? 0))); ?></p>
                            <p class="mt-2 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Providers')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Distinct networks connected here')); ?></p>
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
                                <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Channel limit')); ?></p>
                                <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);">
                                    <?php echo e($limit > -1
                                        ? __(':used of :limit channels are currently in use.', ['used' => number_format((int) ($metrics['total'] ?? 0)), 'limit' => $limitLabel])
                                        : __('This plan does not enforce a channel cap.')); ?>

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
                                style="width: <?php echo e($usagePercent ?? 14); ?>%; background: linear-gradient(90deg, var(--theme-accent), rgba(var(--theme-accent-rgb),0.72));"
                            ></div>
                        </div>

                        <div class="grid min-w-0 gap-4 sm:grid-cols-3">
                            <div class="rounded-[1rem] border p-4" style="border-color: rgba(var(--theme-danger-color-rgb),0.16); background: rgba(var(--theme-danger-color-rgb),0.04);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Paused')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['paused'] ?? 0))); ?></p>
                            </div>

                            <div class="rounded-[1rem] border p-4" style="border-color: rgba(var(--theme-accent-rgb),0.16); background: rgba(var(--theme-accent-rgb),0.05);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('OAuth')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['oauth'] ?? 0))); ?></p>
                            </div>

                            <div class="rounded-[1rem] border p-4" style="border-color: rgba(var(--theme-info-color-rgb),0.16); background: rgba(var(--theme-info-color-rgb),0.05);">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Last 30 days')); ?></p>
                                <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['recent'] ?? 0))); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.chart','data' => ['class' => 'min-w-0','title' => __('Provider mix'),'description' => __('Where your connected channels are concentrated right now.'),'type' => 'bar','series' => $providerChart,'height' => 420]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'min-w-0','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Provider mix')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Where your connected channels are concentrated right now.')),'type' => 'bar','series' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($providerChart),'height' => 420]); ?>
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

        <section class="min-w-0 space-y-4 rounded-[var(--theme-card-radius,1.15rem)] border p-4 sm:p-6" style="border-color: rgba(var(--theme-border-color-rgb),0.56); background:
            linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.02)));
            box-shadow: 0 24px 56px -46px rgba(15,23,42,0.14);">
            <div class="flex min-w-0 items-center justify-between gap-3 border-b pb-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42);">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Recent channels')); ?></p>
                    <p class="mt-2 text-[1.6rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Latest connected accounts')); ?></p>
                    <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('The newest accounts that were added to your channel mix.')); ?></p>
                </div>
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border" style="border-color: rgba(var(--theme-accent-rgb),0.12); background: rgba(var(--theme-accent-rgb),0.08); color: var(--theme-accent);">
                    <i class="fa-light fa-share-nodes text-base"></i>
                </span>
            </div>

            <div class="grid min-w-0 gap-3 lg:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php ($usesLocalSocialAvatar = str_contains((string) ($account['avatar_url'] ?? ''), '/media/public/social-accounts/') || str_contains((string) ($account['avatar_url'] ?? ''), '/storage/social-accounts/')); ?>
                    <a href="<?php echo e(route('portal.channels')); ?>" wire:navigate class="group flex items-center gap-3 rounded-[1rem] border px-4 py-3 transition hover:-translate-y-[1px]" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: color-mix(in srgb, var(--theme-surface-overlay) 84%, transparent); box-shadow: 0 14px 28px -28px rgba(15,23,42,0.12);">
                        <span class="inline-flex h-12 w-12 items-center justify-center overflow-hidden rounded-full border" style="border-color: rgba(var(--theme-border-color-rgb),0.38); background: <?php echo e($usesLocalSocialAvatar ? 'rgba(255,255,255,0.96)' : 'rgba(var(--theme-accent-rgb),0.05)'); ?>; color: var(--theme-accent);">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($account['avatar_url']): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($usesLocalSocialAvatar): ?>
                                    <span class="inline-flex h-8 w-8 items-center justify-center overflow-hidden rounded-[0.7rem] bg-white shadow-[0_8px_16px_-12px_rgba(15,23,42,0.22)] ring-1 ring-slate-200/70">
                                        <img src="<?php echo e($account['avatar_url']); ?>" alt="<?php echo e($account['name']); ?>" class="h-full w-full object-contain">
                                    </span>
                                <?php else: ?>
                                    <img src="<?php echo e($account['avatar_url']); ?>" alt="<?php echo e($account['name']); ?>" class="h-full w-full object-cover">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <i class="fa-light fa-user-group"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($account['name']); ?></p>
                                    <p class="mt-1 truncate text-xs" style="color: var(--theme-muted-text-color);">
                                        <?php echo e($account['provider']); ?><?php echo e($account['handle'] ? ' • '.$account['handle'] : ''); ?>

                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em]" style="background: <?php echo e($account['active'] ? 'rgba(var(--theme-success-color-rgb),0.12)' : 'rgba(var(--theme-danger-color-rgb),0.1)'); ?>; color: <?php echo e($account['active'] ? 'var(--theme-success-color)' : 'var(--theme-danger-color)'); ?>;">
                                    <?php echo e($account['active'] ? __('Active') : __('Paused')); ?>

                                </span>
                            </div>
                            <div class="mt-2 flex items-center justify-between gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($account['connected_at']): ?>
                                    <p class="text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(__('Connected')); ?>: <?php echo e($account['connected_at']); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-[0.14em] opacity-0 transition group-hover:opacity-100" style="color: var(--theme-accent);">
                                    <?php echo e(__('Open')); ?>

                                    <i class="fa-light fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.empty','data' => ['icon' => 'fa-light fa-share-nodes','title' => __('No channels yet'),'description' => __('Connected accounts will appear here once you start adding social channels.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-share-nodes','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No channels yet')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Connected accounts will appear here once you start adding social channels.'))]); ?>
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
<?php if (isset($__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14)): ?>
<?php $attributes = $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14; ?>
<?php unset($__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal768f58f25b9a8ce19e8fe883a0495f14)): ?>
<?php $component = $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14; ?>
<?php unset($__componentOriginal768f58f25b9a8ce19e8fe883a0495f14); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppChannels\Providers/../Resources/views/dashboard/user-summary.blade.php ENDPATH**/ ?>