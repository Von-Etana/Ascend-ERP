<?php
    $totalPlans = (int) ($metrics['total'] ?? 0);
    $activePlans = (int) ($metrics['active'] ?? 0);
    $featuredPlans = (int) ($metrics['featured'] ?? 0);
    $freePlans = (int) ($metrics['free'] ?? 0);
    $inactivePlans = max(0, $totalPlans - $activePlans);
    $activeCoverage = $totalPlans > 0 ? (int) round(($activePlans / max(1, $totalPlans)) * 100) : 0;
    $featuredShare = $totalPlans > 0 ? (int) round(($featuredPlans / max(1, $totalPlans)) * 100) : 0;
    $freeShare = $totalPlans > 0 ? (int) round(($freePlans / max(1, $totalPlans)) * 100) : 0;
    $planSeries = [[
        'name' => __('Plans'),
        'data' => [
            ['name' => __('Active'), 'y' => $activePlans],
            ['name' => __('Inactive'), 'y' => $inactivePlans],
            ['name' => __('Featured'), 'y' => $featuredPlans],
            ['name' => __('Free'), 'y' => $freePlans],
        ],
    ]];
    $healthTone = $inactivePlans > 0 ? 'warning' : ($activePlans > 0 ? 'success' : 'neutral');
    $healthLabel = match ($healthTone) {
        'warning' => __('Needs pruning'),
        'success' => __('Healthy catalog'),
        default => __('Early setup'),
    };
    $healthCopy = match ($healthTone) {
        'warning' => __('There are inactive plans in the catalog. Review offer sprawl so pricing stays clear and intentional.'),
        'success' => __('The catalog is active and streamlined, with assignable plans ready for acquisition and upsell flows.'),
        default => __('The pricing catalog is still light. More structure may be needed before broader rollout.'),
    };
?>

<?php if (isset($component)) { $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dashboard-module','data' => ['eyebrow' => __('Finance'),'title' => null,'description' => null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dashboard-module'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Finance')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="grid gap-4">
        <div class="overflow-hidden rounded-[1.55rem] border p-5 xl:p-6" style="border-color: rgba(var(--theme-accent-rgb),0.12); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.03))); box-shadow: 0 26px 60px -52px rgba(15,23,42,0.22);">
            <div class="flex flex-col gap-5">
                <div class="rounded-[1.2rem] border px-5 py-4 sm:px-6" style="border-color: rgba(var(--theme-border-color-rgb),0.5); background:
                    radial-gradient(circle at top left, rgba(var(--theme-accent-rgb),0.1), transparent 34%),
                    linear-gradient(135deg, color-mix(in srgb, var(--theme-surface-overlay) 95%, transparent), color-mix(in srgb, var(--theme-surface-base) 93%, rgba(var(--theme-accent-rgb),0.03)));">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="border-color: rgba(var(--theme-accent-rgb),0.16); background: rgba(var(--theme-accent-rgb),0.08); color: var(--theme-accent);">
                                    <i class="fa-light fa-badge-dollar mr-1.5 text-[10px]"></i><?php echo e(__('Finance')); ?>

                                </span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="background: rgba(var(--theme-success-color-rgb),0.1); color: var(--theme-success-color);">
                                    <?php echo e(__('Admin dashboard')); ?>

                                </span>
                            </div>
                            <h3 class="mt-3 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Run pricing catalog decisions from a cleaner finance command center')); ?></h3>
                            <p class="mt-2 max-w-3xl text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Understand plan coverage, offer mix, inactive sprawl, and positioning strength before you open the full pricing workspace.')); ?></p>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-3">
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $route,'size' => 'sm','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($route),'size' => 'sm','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Open plans')); ?> <?php echo $__env->renderComponent(); ?>
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

                <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-5 overflow-hidden','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 97%, rgba(var(--theme-accent-rgb),0.02))); box-shadow: 0 22px 48px -42px rgba(15,23,42,0.16);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-5 overflow-hidden','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 97%, rgba(var(--theme-accent-rgb),0.02))); box-shadow: 0 22px 48px -42px rgba(15,23,42,0.16);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <div class="grid gap-4 xl:grid-cols-[1.15fr_0.85fr]">
                        <div class="relative overflow-hidden rounded-[1.35rem] border px-5 py-5 sm:px-6" style="border-color: rgba(var(--theme-border-color-rgb),0.5); background:
                            radial-gradient(circle at top right, rgba(var(--theme-accent-rgb),0.14), transparent 34%),
                            linear-gradient(145deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                            <div class="pointer-events-none absolute -right-10 -top-8 h-36 w-36 rounded-full blur-3xl" style="background: rgba(var(--theme-accent-rgb),0.12);"></div>

                            <div class="relative flex h-full flex-col justify-between gap-5">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="max-w-3xl">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Pricing catalog command')); ?></p>
                                        <div class="mt-3 flex flex-wrap items-end gap-x-4 gap-y-2">
                                            <p class="text-[2.9rem] font-semibold leading-none tracking-[-0.07em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($totalPlans)); ?></p>
                                            <p class="pb-1 text-sm font-medium" style="color: var(--theme-muted-text-color);"><?php echo e(__('Plans available in the active pricing catalog')); ?></p>
                                        </div>
                                        <p class="mt-4 max-w-[40rem] text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e($healthCopy); ?></p>
                                    </div>

                                    <div class="inline-flex self-start rounded-full px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.14em]" style="background: <?php echo e($healthTone === 'warning' ? 'rgba(245,158,11,0.12)' : ($healthTone === 'success' ? 'rgba(var(--theme-success-color-rgb),0.12)' : 'rgba(var(--theme-accent-rgb),0.09)')); ?>; color: <?php echo e($healthTone === 'warning' ? 'rgb(217,119,6)' : ($healthTone === 'success' ? 'var(--theme-success-color)' : 'rgba(var(--theme-accent-rgb),0.92)')); ?>;">
                                        <?php echo e($healthLabel); ?>

                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Active coverage')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e($activeCoverage); ?>%</p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Share of plans currently assignable to customers')); ?></p>
                                    </div>

                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Featured mix')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e($featuredShare); ?>%</p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Portion of catalog intentionally highlighted in pricing')); ?></p>
                                    </div>

                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Featured plans')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($featuredPlans)); ?></p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Offers intentionally pushed to the front of the pricing story.')); ?></p>
                                    </div>

                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Free entry point')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e($freeShare); ?>%</p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('How much of the catalog is reserved for free acquisition or onboarding.')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Active plans')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($activePlans)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Ready for assignment right now')); ?></p>
                            </div>

                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Inactive plans')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($inactivePlans)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Catalog entries that may need pruning')); ?></p>
                            </div>

                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Featured plans')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($featuredPlans)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Positioned for promotion in pricing')); ?></p>
                            </div>

                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Free tiers')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($freePlans)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Low-friction acquisition entry points')); ?></p>
                            </div>
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

                <?php if (isset($component)) { $__componentOriginalc751055b5fd59125696b302ff2a0d8e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc751055b5fd59125696b302ff2a0d8e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.chart','data' => ['title' => __('Plan mix'),'description' => __('How the pricing catalog is split across active, inactive, featured, and free plans.'),'type' => 'donut','series' => $planSeries,'height' => 360,'class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Plan mix')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('How the pricing catalog is split across active, inactive, featured, and free plans.')),'type' => 'donut','series' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($planSeries),'height' => 360,'class' => 'w-full']); ?>
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
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AdminPlans\Providers/../Resources/views/dashboard/snapshot.blade.php ENDPATH**/ ?>