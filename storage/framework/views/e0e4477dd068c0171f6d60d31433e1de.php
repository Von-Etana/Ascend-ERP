<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'chart-'.\Illuminate\Support\Str::uuid(),
    'title' => null,
    'description' => null,
    'type' => 'line',
    'categories' => [],
    'series' => [],
    'height' => 260,
    'options' => [],
    'legend' => false,
    'donut' => false,
    'class' => '',
    'footerStats' => [],
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
    'id' => 'chart-'.\Illuminate\Support\Str::uuid(),
    'title' => null,
    'description' => null,
    'type' => 'line',
    'categories' => [],
    'series' => [],
    'height' => 260,
    'options' => [],
    'legend' => false,
    'donut' => false,
    'class' => '',
    'footerStats' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $resolvedChartType = $type === 'line' ? 'areaspline' : $type;
    $resolvedOptions = $options;

    if ($resolvedOptions === []) {
        $resolvedOptions = [
            'chart' => [
                'type' => $resolvedChartType,
                'spacing' => [10, 8, 10, 8],
                'style' => [
                    'fontFamily' => 'inherit',
                ],
            ],
            'title' => ['text' => null],
            'legend' => ['enabled' => $legend],
            'credits' => ['enabled' => false],
            'xAxis' => [
                'categories' => $categories,
                'lineColor' => '#e2e8f0',
                'lineWidth' => 1,
                'tickWidth' => 0,
                'labels' => [
                    'style' => [
                        'fontSize' => '12px',
                        'color' => '#64748b',
                    ],
                ],
            ],
            'yAxis' => [
                'gridLineWidth' => 1,
                'gridLineDashStyle' => 'Dash',
                'gridLineColor' => '#dbe7ee',
                'title' => ['text' => null],
                'labels' => [
                    'style' => [
                        'fontSize' => '12px',
                        'color' => '#64748b',
                    ],
                ],
            ],
            'tooltip' => [
                'shared' => true,
                'backgroundColor' => '#ffffff',
                'borderColor' => '#dbe7ee',
                'borderRadius' => 10,
                'shadow' => false,
                'style' => [
                    'color' => '#0f172a',
                    'fontSize' => '12px',
                ],
            ],
            'colors' => ['#147d78'],
            'series' => $series,
        ];

        if (in_array($type, ['pie', 'donut'], true)) {
            $resolvedOptions['chart']['type'] = 'pie';
            $resolvedOptions['plotOptions'] = [
                'pie' => [
                    'innerSize' => $donut || $type === 'donut' ? '68%' : '0%',
                    'borderWidth' => 0,
                    'size' => '88%',
                    'dataLabels' => [
                        'enabled' => false,
                    ],
                    'showInLegend' => $legend,
                    'states' => [
                        'inactive' => [
                            'opacity' => 1,
                        ],
                    ],
                ],
                'series' => [
                    'states' => [
                        'inactive' => [
                            'opacity' => 1,
                        ],
                    ],
                ],
            ];
            $resolvedOptions['tooltip'] = [
                'pointFormat' => '<b>{point.y}</b>',
            ];
        } else {
            $resolvedOptions['plotOptions'] = [
                'column' => [
                    'borderRadius' => 999,
                    'borderWidth' => 0,
                    'groupPadding' => 0.16,
                    'pointPadding' => 0.08,
                ],
                'bar' => [
                    'borderRadius' => 999,
                    'borderWidth' => 0,
                    'groupPadding' => 0.16,
                    'pointPadding' => 0.08,
                ],
                'area' => [
                    'fillOpacity' => 0.14,
                    'lineWidth' => 3,
                    'marker' => [
                        'enabled' => false,
                    ],
                ],
                'areaspline' => [
                    'fillOpacity' => 0.16,
                    'lineWidth' => 3.5,
                    'marker' => [
                        'enabled' => false,
                    ],
                ],
                'spline' => [
                    'lineWidth' => 3.5,
                    'marker' => [
                        'enabled' => false,
                    ],
                ],
                'line' => [
                    'lineWidth' => 3.5,
                    'marker' => [
                        'enabled' => false,
                    ],
                ],
                'series' => [
                    'borderRadius' => 8,
                    'lineWidth' => in_array($type, ['line', 'area', 'spline', 'areaspline'], true) ? 3.5 : null,
                    'marker' => [
                        'enabled' => false,
                    ],
                    'states' => [
                        'hover' => [
                            'lineWidthPlus' => 0,
                            'halo' => [
                                'size' => 0,
                            ],
                        ],
                        'inactive' => [
                            'opacity' => 1,
                        ],
                    ],
                ],
            ];

            if (in_array($resolvedChartType, ['area', 'areaspline'], true)) {
                $resolvedOptions['plotOptions'][$resolvedChartType]['fillColor'] = [
                    'linearGradient' => [0, 0, 0, 1],
                    'stops' => [
                        [0, 'rgba(20, 125, 120, 0.20)'],
                        [1, 'rgba(20, 125, 120, 0.02)'],
                    ],
                ];
            }
        }
    }
?>

<div <?php echo e($attributes->class(['min-w-0 max-w-full overflow-hidden rounded-[1rem] border p-5 shadow-[0_12px_30px_-24px_rgba(15,23,42,0.18)]', $class])); ?> style="border-color: rgba(var(--theme-border-color-rgb), 0.58); background: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($title) || filled($description)): ?>
        <div class="mb-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($title)): ?>
                <p class="text-[15px] font-semibold tracking-[-0.02em]" style="color: var(--theme-header-text-color);"><?php echo e($title); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($description)): ?>
                <p class="mt-1 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e($description); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal375447c43f5bc8906cac5148c57a2461 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal375447c43f5bc8906cac5148c57a2461 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b4daf8ec10943ca139c28d628e8f62c::highchart','data' => ['id' => $id,'options' => $resolvedOptions,'height' => $height]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shared::highchart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($id),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($resolvedOptions),'height' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($height)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal375447c43f5bc8906cac5148c57a2461)): ?>
<?php $attributes = $__attributesOriginal375447c43f5bc8906cac5148c57a2461; ?>
<?php unset($__attributesOriginal375447c43f5bc8906cac5148c57a2461); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal375447c43f5bc8906cac5148c57a2461)): ?>
<?php $component = $__componentOriginal375447c43f5bc8906cac5148c57a2461; ?>
<?php unset($__componentOriginal375447c43f5bc8906cac5148c57a2461); ?>
<?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($footerStats)): ?>
        <div class="mt-5 grid overflow-hidden rounded-[1rem] border" style="border-color: rgba(var(--theme-border-color-rgb), 0.56); grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $footerStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="border-t px-5 py-4 first:border-t-0 sm:border-l sm:first:border-l-0 sm:border-t-0" style="border-color: rgba(var(--theme-border-color-rgb), 0.56); background: color-mix(in srgb, var(--theme-surface-soft) 88%, transparent);">
                    <p class="text-sm" style="color: var(--theme-muted-text-color);"><?php echo e($stat['label'] ?? '--'); ?></p>
                    <p class="mt-2 text-[1.75rem] font-semibold leading-none" style="color: var(--theme-header-text-color);">
                        <?php echo e(number_format((float) ($stat['value'] ?? 0), (int) ($stat['decimals'] ?? 0))); ?><?php echo e($stat['suffix'] ?? ''); ?>

                    </p>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/chart.blade.php ENDPATH**/ ?>