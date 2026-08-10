<?php
    $storageBytes = (int) ($metrics['storage_bytes'] ?? 0);
    $limitMb = (int) ($metrics['limit_mb'] ?? 0);
    $limitBytes = $limitMb > 0 ? $limitMb * 1024 * 1024 : 0;
    $usagePercent = $limitBytes > 0 ? min(100, (int) round(($storageBytes / $limitBytes) * 100)) : null;
    $averageFileBytes = (int) ($metrics['average_file_bytes'] ?? 0);

    $formatBytes = static function (int $bytes): string {
        return $bytes >= 1073741824
            ? number_format($bytes / 1073741824, 2).' GB'
            : ($bytes >= 1048576
                ? number_format($bytes / 1048576, 2).' MB'
                : ($bytes >= 1024
                    ? number_format($bytes / 1024, 2).' KB'
                    : $bytes.' B'));
    };

    $storageLabel = $formatBytes($storageBytes);
    $averageFileLabel = $formatBytes($averageFileBytes);

    $limitLabel = $limitMb > 0 ? number_format($limitMb).' MB' : __('No storage limit');
    $categoryPalette = [
        'image' => 'rgba(var(--theme-accent-rgb), 0.88)',
        'video' => 'rgba(16,185,129,0.92)',
        'audio' => 'rgba(245,158,11,0.92)',
        'document' => 'rgba(99,102,241,0.88)',
        'spreadsheet' => 'rgba(14,165,233,0.9)',
        'pdf' => 'rgba(239,68,68,0.88)',
        'archive' => 'rgba(168,85,247,0.9)',
        'other' => 'rgba(100,116,139,0.88)',
    ];
    $categoryLabels = [
        'image' => __('Images'),
        'video' => __('Videos'),
        'audio' => __('Audio'),
        'document' => __('Documents'),
        'spreadsheet' => __('Sheets'),
        'pdf' => __('PDF'),
        'archive' => __('Archives'),
        'other' => __('Other'),
    ];
    $totalCategorizedBytes = collect($storageByCategory ?? [])->sum();
    $dominantCategory = collect($storageByCategory ?? [])->sortDesc()->keys()->first();
    $dominantCategoryBytes = $dominantCategory ? (int) (($storageByCategory[$dominantCategory] ?? 0)) : 0;
    $dominantCategoryShare = $totalCategorizedBytes > 0 && $dominantCategoryBytes > 0
        ? (int) round(($dominantCategoryBytes / $totalCategorizedBytes) * 100)
        : 0;
    $dominantCategoryLabel = $dominantCategory ? ($categoryLabels[$dominantCategory] ?? ucfirst((string) $dominantCategory)) : __('No assets yet');
    $dominantCategoryTitle = match ($dominantCategory) {
        'image' => __('Image-heavy library'),
        'video' => __('Video-heavy library'),
        'audio' => __('Audio-heavy library'),
        'document', 'spreadsheet', 'pdf' => __('Document-led library'),
        'archive' => __('Archive-heavy library'),
        'other' => __('Mixed asset library'),
        default => __('No assets yet'),
    };
?>

<?php if (isset($component)) { $__componentOriginal768f58f25b9a8ce19e8fe883a0495f14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f58f25b9a8ce19e8fe883a0495f14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dashboard-module','data' => ['eyebrow' => __('Files'),'title' => null,'description' => null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dashboard-module'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Files')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null)]); ?>
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
                                    <i class="fa-light fa-folders mr-1.5 text-[10px]"></i><?php echo e(__('Storage and asset health')); ?>

                                </span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em]" style="background: rgba(var(--theme-success-color-rgb),0.1); color: var(--theme-success-color);">
                                    <?php echo e(__('Library snapshot')); ?>

                                </span>
                            </div>
                            <h3 class="mt-3 text-[1.55rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(__('See storage usage, asset mix, and library pressure quickly')); ?></h3>
                            <p class="mt-2 max-w-3xl text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Review total footprint, image-heavy usage, and current file-library balance before opening the full manager.')); ?></p>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-3">
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $item['route'] ?? route('portal.files.index'),'size' => 'sm','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['route'] ?? route('portal.files.index')),'size' => 'sm','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Open files')); ?> <?php echo $__env->renderComponent(); ?>
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

                <div class="grid gap-4">
        <div class="grid gap-4 xl:grid-cols-[1.16fr_0.84fr]">
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-5','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.02))); box-shadow: 0 22px 46px -42px rgba(15,23,42,0.16);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-5','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.02))); box-shadow: 0 22px 46px -42px rgba(15,23,42,0.16);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Storage usage')); ?></p>
                        <p class="mt-2 text-[2.2rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e($storageLabel); ?></p>
                        <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);">
                            <?php echo e($usagePercent !== null ? __(':used of :limit used across your file library.', ['used' => $storageLabel, 'limit' => $limitLabel]) : __('This plan does not currently enforce a storage ceiling.')); ?>

                        </p>
                    </div>

                    <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-3 text-right" style="border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 82%, transparent); box-shadow: inset 0 1px 0 color-mix(in srgb, var(--theme-surface-overlay) 72%, transparent);">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Usage')); ?></p>
                        <p class="mt-2 text-[1.8rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e($usagePercent ?? 0); ?>%</p>
                    </div>
                </div>

                <div class="h-3 overflow-hidden rounded-full" style="background: rgba(var(--theme-border-color-rgb),0.35);">
                    <div
                        class="h-full rounded-full transition-all"
                        style="width: <?php echo e($usagePercent ?? 18); ?>%; background: linear-gradient(90deg, rgba(var(--theme-accent-rgb),0.95), rgba(16,185,129,0.92));"
                    ></div>
                </div>

                <div class="grid gap-3 sm:grid-cols-4">
                    <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.48); background: color-mix(in srgb, var(--theme-surface-overlay) 88%, transparent);">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('All entries')); ?></p>
                        <p class="mt-2 text-[1.7rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['total'] ?? 0))); ?></p>
                    </div>

                    <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.48); background: color-mix(in srgb, var(--theme-surface-overlay) 88%, rgba(16,185,129,0.03));">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Images')); ?></p>
                        <p class="mt-2 text-[1.7rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['images'] ?? 0))); ?></p>
                    </div>

                    <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.48); background: color-mix(in srgb, var(--theme-surface-overlay) 88%, rgba(var(--theme-accent-rgb),0.03));">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Folders')); ?></p>
                        <p class="mt-2 text-[1.7rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['folders'] ?? 0))); ?></p>
                    </div>

                    <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb),0.48); background: color-mix(in srgb, var(--theme-surface-overlay) 88%, rgba(245,158,11,0.03));">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Avg file size')); ?></p>
                        <p class="mt-2 text-[1.35rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e($averageFileLabel); ?></p>
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

            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-4','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.02))); box-shadow: 0 22px 46px -42px rgba(15,23,42,0.14);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.56); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-surface-overlay) 96%, rgba(var(--theme-accent-rgb),0.03)), color-mix(in srgb, var(--theme-surface-base) 95%, rgba(var(--theme-accent-rgb),0.02))); box-shadow: 0 22px 46px -42px rgba(15,23,42,0.14);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Asset mix')); ?></p>
                        <p class="mt-2 text-[1.3rem] font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);">
                            <?php echo e($dominantCategoryTitle); ?>

                        </p>
                        <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dominantCategory): ?>
                                <?php echo e(__(':type currently accounts for about :share% of the occupied storage in your library.', ['type' => Str::lower($dominantCategoryLabel), 'share' => $dominantCategoryShare])); ?>

                            <?php else: ?>
                                <?php echo e(__('A quick split of the file types currently consuming space in your library.')); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dominantCategory): ?>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-medium" style="border-color: rgba(var(--theme-accent-rgb),0.14); background: rgba(var(--theme-accent-rgb),0.05); color: var(--theme-header-text-color);">
                                    <span class="h-2 w-2 rounded-full" style="background: <?php echo e($categoryPalette[$dominantCategory] ?? $categoryPalette['other']); ?>;"></span>
                                    <?php echo e($dominantCategoryLabel); ?>

                                </span>
                                <span class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-medium" style="border-color: rgba(var(--theme-border-color-rgb),0.72); background: color-mix(in srgb, var(--theme-surface-overlay) 78%, transparent); color: var(--theme-muted-text-color);">
                                    <?php echo e($formatBytes($dominantCategoryBytes)); ?>

                                </span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="rounded-[var(--theme-card-radius,1.15rem)] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 84%, transparent);">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Capacity')); ?></p>
                        <p class="mt-2 text-lg font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($limitLabel); ?></p>
                    </div>
                </div>

                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = collect($storageByCategory ?? [])->sortDesc()->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $bytes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $categoryShare = $totalCategorizedBytes > 0 ? max(3, (int) round(($bytes / $totalCategorizedBytes) * 100)) : 0;
                        ?>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full" style="background: <?php echo e($categoryPalette[$category] ?? $categoryPalette['other']); ?>;"></span>
                                    <span class="font-medium" style="color: var(--theme-header-text-color);"><?php echo e($categoryLabels[$category] ?? ucfirst((string) $category)); ?></span>
                                </div>
                                <span style="color: var(--theme-muted-text-color);"><?php echo e($formatBytes((int) $bytes)); ?></span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full" style="background: rgba(var(--theme-border-color-rgb),0.28);">
                                <div class="h-full rounded-full" style="width: <?php echo e($categoryShare); ?>%; background: <?php echo e($categoryPalette[$category] ?? $categoryPalette['other']); ?>;"></div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.empty','data' => ['icon' => 'fa-light fa-folder-open','title' => __('No files stored yet'),'description' => __('Type distribution will appear here once assets are uploaded into the library.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-folder-open','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No files stored yet')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Type distribution will appear here once assets are uploaded into the library.'))]); ?>
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

        <div class="grid gap-4 sm:grid-cols-3">
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-2','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, rgba(var(--theme-accent-rgb),0.02)); box-shadow: 0 18px 38px -36px rgba(15,23,42,0.12);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-2','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, rgba(var(--theme-accent-rgb),0.02)); box-shadow: 0 18px 38px -36px rgba(15,23,42,0.12);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('File assets')); ?></p>
                <p class="mt-2 text-[1.65rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['files'] ?? 0))); ?></p>
                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Uploaded assets excluding folders.')); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-2','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, rgba(16,185,129,0.02)); box-shadow: 0 18px 38px -36px rgba(15,23,42,0.12);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-2','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, rgba(16,185,129,0.02)); box-shadow: 0 18px 38px -36px rgba(15,23,42,0.12);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Non-image assets')); ?></p>
                <p class="mt-2 text-[1.65rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e(number_format((int) ($metrics['other_assets'] ?? 0))); ?></p>
                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Documents, videos, archives, and other stored files.')); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.card','data' => ['class' => 'space-y-2','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, rgba(var(--theme-accent-rgb),0.02)); box-shadow: 0 18px 38px -36px rgba(15,23,42,0.12);']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-2','style' => 'border-color: rgba(var(--theme-border-color-rgb),0.52); background: color-mix(in srgb, var(--theme-surface-overlay) 92%, rgba(var(--theme-accent-rgb),0.02)); box-shadow: 0 18px 38px -36px rgba(15,23,42,0.12);']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <p class="text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Storage footprint')); ?></p>
                <p class="mt-2 text-[1.65rem] font-semibold tracking-[-0.04em]" style="color: var(--theme-header-text-color);"><?php echo e($storageLabel); ?></p>
                <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Current occupied space across all stored assets.')); ?></p>
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
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppFiles\Providers/../Resources/views/dashboard/user-storage-summary.blade.php ENDPATH**/ ?>