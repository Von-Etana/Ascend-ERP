<?php
    $dailyCategories = collect($daily ?? [])->map(fn (array $point) => $point['label'] ?? '--')->all();
    $dailyValues = collect($daily ?? [])->map(fn (array $point) => (int) ($point['value'] ?? 0))->values();
    $dailySeries = [[
        'name' => __('Requests'),
        'data' => $dailyValues->all(),
    ]];
    $totalRequests = (int) ($metrics['total_requests'] ?? 0);
    $successRate = (int) ($metrics['success_rate'] ?? 0);
    $successfulRequests = (int) ($metrics['successful_requests'] ?? 0);
    $failedRequests = (int) ($metrics['failed_requests'] ?? 0);
    $totalTokens = (int) ($metrics['total_tokens'] ?? 0);
    $estimatedCost = (float) ($metrics['estimated_cost'] ?? 0);
    $avgLatency = (int) ($metrics['avg_latency'] ?? 0);
    $requests7d = (int) ($metrics['requests_7d'] ?? 0);
    $avgDailyRequests = $dailyValues->isNotEmpty() ? round($dailyValues->avg(), 1) : 0;
    $peakDailyRequests = (int) $dailyValues->max();
    $failureRate = $totalRequests > 0 ? (int) round(($failedRequests / max(1, $totalRequests)) * 100) : 0;
    $tokensPerRequest = $totalRequests > 0 ? (int) round($totalTokens / max(1, $totalRequests)) : 0;
    $costPerRequest = $totalRequests > 0 ? $estimatedCost / max(1, $totalRequests) : 0;
    $latencySeconds = $avgLatency > 0 ? round($avgLatency / 1000, 1) : 0;
    $healthTone = $failedRequests > 0 || $avgLatency >= 8000 ? 'warning' : ($successRate >= 90 ? 'success' : 'neutral');
    $healthLabel = match ($healthTone) {
        'warning' => __('Needs review'),
        'success' => __('Healthy'),
        default => __('Observing'),
    };
    $healthCopy = match ($healthTone) {
        'warning' => __('Failure or latency signals are elevated. Review request logs and model routing before traffic grows further.'),
        'success' => __('Request quality is stable, failures are low, and current response behavior looks healthy.'),
        default => __('Traffic exists, but the signal is still too light to draw strong conclusions from the current window.'),
    };
    $chartFooterStats = [
        ['label' => __('7-day requests'), 'value' => $requests7d],
        ['label' => __('Success rate'), 'value' => $successRate, 'suffix' => '%'],
        ['label' => __('Failed'), 'value' => $failedRequests],
        ['label' => __('Avg latency'), 'value' => $avgLatency, 'suffix' => 'ms'],
    ];
?>

<?php if (isset($component)) { $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dashboard-module','data' => ['eyebrow' => __('AI center'),'title' => null,'description' => null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dashboard-module'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('AI center')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null)]); ?>
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
                                    <i class="fa-light fa-sparkles mr-1.5 text-[10px]"></i><?php echo e(__('AI center')); ?>

                                </span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="background: rgba(var(--theme-success-color-rgb),0.1); color: var(--theme-success-color);">
                                    <?php echo e(__('Admin dashboard')); ?>

                                </span>
                            </div>
                            <h3 class="mt-3 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Run a real AI operations command center from the dashboard')); ?></h3>
                            <p class="mt-2 max-w-3xl text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Track request volume, quality, latency, token intensity, and spend efficiency from one place before diving into logs and reporting.')); ?></p>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-3">
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $logsRoute,'variant' => 'outline','size' => 'sm','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logsRoute),'variant' => 'outline','size' => 'sm','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Open logs')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $reportRoute,'size' => 'sm','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportRoute),'size' => 'sm','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Open report')); ?> <?php echo $__env->renderComponent(); ?>
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
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('AI operations command')); ?></p>
                                        <div class="mt-3 flex flex-wrap items-end gap-x-4 gap-y-2">
                                            <p class="text-[2.9rem] font-semibold leading-none tracking-[-0.07em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($totalRequests)); ?></p>
                                            <p class="pb-1 text-sm font-medium" style="color: var(--theme-muted-text-color);"><?php echo e(__('Total AI requests tracked')); ?></p>
                                        </div>
                                        <p class="mt-4 max-w-[40rem] text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e($healthCopy); ?></p>
                                    </div>

                                    <div class="inline-flex self-start rounded-full px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.14em]" style="background: <?php echo e($healthTone === 'warning' ? 'rgba(245,158,11,0.12)' : ($healthTone === 'success' ? 'rgba(var(--theme-success-color-rgb),0.12)' : 'rgba(var(--theme-accent-rgb),0.09)')); ?>; color: <?php echo e($healthTone === 'warning' ? 'rgb(217,119,6)' : ($healthTone === 'success' ? 'var(--theme-success-color)' : 'rgba(var(--theme-accent-rgb),0.92)')); ?>;">
                                        <?php echo e($healthLabel); ?>

                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Success rate')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e($successRate); ?>%</p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Share of AI requests that completed successfully')); ?></p>
                                    </div>

                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Avg latency')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($avgLatency)); ?><span class="text-base">ms</span></p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('About :seconds seconds per request.', ['seconds' => number_format($latencySeconds, 1)])); ?></p>
                                    </div>

                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Average daily demand')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($avgDailyRequests, 1)); ?></p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Typical daily request volume across the last 7 days')); ?></p>
                                    </div>

                                    <div class="rounded-[1.1rem] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.42); background: color-mix(in srgb, var(--theme-surface-base) 90%, rgba(var(--theme-accent-rgb),0.03));">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Spend efficiency')); ?></p>
                                        <p class="mt-2 text-[1.85rem] font-semibold tracking-[-0.045em]" style="color: var(--theme-header-text-color);">$<?php echo e(number_format($costPerRequest, 4)); ?></p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Estimated average cost per request.')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('7-day demand')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($requests7d)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Requests handled this week')); ?></p>
                            </div>

                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Successful')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($successfulRequests)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Requests completed cleanly')); ?></p>
                            </div>

                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Peak day')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($peakDailyRequests)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Highest 1-day request volume this week')); ?></p>
                            </div>

                            <div class="rounded-[1.25rem] border px-5 py-5" style="border-color: rgba(var(--theme-border-color-rgb),0.46); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 94%, rgba(var(--theme-accent-rgb),0.04)), color-mix(in srgb, var(--theme-surface-base) 96%, rgba(var(--theme-accent-rgb),0.02)));">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Tokens / request')); ?></p>
                                <p class="mt-3 text-[2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format($tokensPerRequest)); ?></p>
                                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Average token intensity per call')); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.chart','data' => ['title' => __('AI request trend'),'description' => __('Daily request volume over the last 7 days, with quality and latency summarized below.'),'type' => 'line','categories' => $dailyCategories,'series' => $dailySeries,'height' => 360,'footerStats' => $chartFooterStats,'class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('AI request trend')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Daily request volume over the last 7 days, with quality and latency summarized below.')),'type' => 'line','categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dailyCategories),'series' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dailySeries),'height' => 360,'footer-stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chartFooterStats),'class' => 'w-full']); ?>
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
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AdminAI\Providers/../Resources/views/dashboard/snapshot.blade.php ENDPATH**/ ?>