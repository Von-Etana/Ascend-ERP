<?php
    $dailyCategories = collect($daily ?? [])->map(fn (array $point) => $point['label'] ?? '--')->all();
    $dailyValues = collect($daily ?? [])->map(fn (array $point) => (int) ($point['value'] ?? 0))->values();
    $dailySeries = [[
        'name' => __('Runs'),
        'data' => $dailyValues->all(),
    ]];
    $totalRuns = (int) ($metrics['total'] ?? 0);
    $queuedRuns = (int) ($metrics['queued'] ?? 0);
    $completedRuns = (int) ($metrics['completed'] ?? 0);
    $failedRuns = (int) ($metrics['failed'] ?? 0);
    $weekTotal = (int) ($metrics['week_total'] ?? 0);
    $thisMonth = (int) ($metrics['this_month'] ?? 0);
    $avgDailyRuns = $dailyValues->isNotEmpty() ? round($dailyValues->avg(), 1) : 0;
    $peakDailyRuns = (int) $dailyValues->max();
    $successRate = $totalRuns > 0 ? (int) round(($completedRuns / max(1, $totalRuns)) * 100) : 0;
    $failureRate = $totalRuns > 0 ? (int) round(($failedRuns / max(1, $totalRuns)) * 100) : 0;
    $queuePressure = $totalRuns > 0 ? (int) round(($queuedRuns / max(1, $totalRuns)) * 100) : 0;
    $healthTone = $failedRuns > 0 ? 'warning' : ($completedRuns > 0 ? 'success' : 'neutral');
    $healthLabel = match ($healthTone) {
        'warning' => __('Needs review'),
        'success' => __('Stable flow'),
        default => __('Early activity'),
    };
    $healthCopy = match ($healthTone) {
        'warning' => __('Failures are present in the current AI publishing pipeline and should be reviewed.'),
        'success' => __('AI publishing is completing cleanly with no failed runs in the current snapshot.'),
        default => __('There is not enough completed activity yet to establish a strong trend.'),
    };
    $footerStats = [
        ['label' => __('Runs (7d)'), 'value' => $weekTotal],
        ['label' => __('This month'), 'value' => $thisMonth],
        ['label' => __('Success rate'), 'value' => $successRate, 'suffix' => '%'],
        ['label' => __('Queue share'), 'value' => $queuePressure, 'suffix' => '%'],
    ];
?>

<?php if (isset($component)) { $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dashboard-module','data' => ['eyebrow' => __('AI publishing'),'title' => null,'description' => null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dashboard-module'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('AI publishing')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null)]); ?>
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
                                    <i class="fa-light fa-wand-magic-sparkles mr-1.5 text-[10px]"></i><?php echo e(__('AI publishing')); ?>

                                </span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="background: rgba(var(--theme-success-color-rgb),0.1); color: var(--theme-success-color);">
                                    <?php echo e(__('Admin dashboard')); ?>

                                </span>
                            </div>
                            <h3 class="mt-3 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Monitor AI publishing performance with real operational context')); ?></h3>
                            <p class="mt-2 max-w-3xl text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Surface throughput, queue pressure, completion quality, and weekly velocity so the team can spot pipeline issues before they slow down publishing.')); ?></p>
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
<?php echo e(__('Open AI publishing')); ?> <?php echo $__env->renderComponent(); ?>
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

                <div class="grid gap-4 xl:grid-cols-[1.08fr_0.92fr]">
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

                        <div class="grid gap-4 lg:grid-cols-[1.12fr_0.88fr]">
                            <div class="relative overflow-hidden rounded-[var(--theme-card-radius,1.15rem)] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.5); background: radial-gradient(circle at top right, rgba(var(--theme-accent-rgb),0.10), transparent 38%), linear-gradient(140deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <div class="pointer-events-none absolute -right-10 top-0 h-32 w-32 rounded-full blur-3xl" style="background: rgba(var(--theme-accent-rgb),0.10);"></div>

                                <div class="relative">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Run command center')); ?></p>
                                            <p class="mt-3 text-[2.7rem] font-semibold tracking-[-0.065em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($totalRuns)); ?></p>
                                            <p class="mt-2 text-base font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Total AI publishing runs tracked')); ?></p>
                                        </div>

                                        <div class="rounded-full px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.14em]" style="background: <?php echo e($healthTone === 'warning' ? 'rgba(245,158,11,0.12)' : ($healthTone === 'success' ? 'rgba(var(--theme-success-color-rgb),0.12)' : 'rgba(var(--theme-accent-rgb),0.09)')); ?>; color: <?php echo e($healthTone === 'warning' ? 'rgb(217,119,6)' : ($healthTone === 'success' ? 'var(--theme-success-color)' : 'rgba(var(--theme-accent-rgb),0.92)')); ?>;">
                                            <?php echo e($healthLabel); ?>

                                        </div>
                                    </div>

                                    <p class="mt-4 max-w-[34rem] text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e($healthCopy); ?></p>

                                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                        <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.44); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Completion rate')); ?></p>
                                            <p class="mt-2 text-[1.75rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e($successRate); ?>%</p>
                                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Share of tracked runs that have completed successfully')); ?></p>
                                        </div>

                                        <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.44); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Average daily volume')); ?></p>
                                            <p class="mt-2 text-[1.75rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($avgDailyRuns, 1)); ?></p>
                                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Typical daily run output across the last 7 days')); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                                <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.5); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-success-color-rgb),0.06)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-success-color-rgb),0.03)));">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Queue pressure')); ?></p>
                                    <p class="mt-2 text-[1.9rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e($queuePressure); ?>%</p>
                                    <p class="mt-1 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Portion of total runs still queued and waiting for execution.')); ?></p>
                                </div>

                                <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.5); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-warning-color-rgb),0.05)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-warning-color-rgb),0.02)));">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Failure rate')); ?></p>
                                    <p class="mt-2 text-[1.9rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e($failureRate); ?>%</p>
                                    <p class="mt-1 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Failed runs relative to the total tracked AI publishing volume.')); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[var(--theme-card-radius,1.15rem)] border p-3" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-[calc(var(--theme-card-radius,1.15rem)-0.2rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Queued now')); ?></p>
                                    <p class="mt-2 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($queuedRuns)); ?></p>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Waiting in the pipeline')); ?></p>
                                </div>

                                <div class="rounded-[calc(var(--theme-card-radius,1.15rem)-0.2rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Completed')); ?></p>
                                    <p class="mt-2 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($completedRuns)); ?></p>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Finished successfully')); ?></p>
                                </div>

                                <div class="rounded-[calc(var(--theme-card-radius,1.15rem)-0.2rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Peak day')); ?></p>
                                    <p class="mt-2 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($peakDailyRuns)); ?></p>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Highest 1-day AI run volume this week')); ?></p>
                                </div>

                                <div class="rounded-[calc(var(--theme-card-radius,1.15rem)-0.2rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Month volume')); ?></p>
                                    <p class="mt-2 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($thisMonth)); ?></p>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Runs created during the current month')); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.chart','data' => ['title' => __('AI publishing trend'),'description' => __('Daily AI publishing run volume over the last 7 days, with queue and execution context summarized below.'),'type' => 'line','categories' => $dailyCategories,'series' => $dailySeries,'height' => 380,'footerStats' => $footerStats]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('AI publishing trend')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Daily AI publishing run volume over the last 7 days, with queue and execution context summarized below.')),'type' => 'line','categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dailyCategories),'series' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dailySeries),'height' => 380,'footer-stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerStats)]); ?>
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
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppAiPublishing\Providers/../Resources/views/dashboard/admin-snapshot.blade.php ENDPATH**/ ?>