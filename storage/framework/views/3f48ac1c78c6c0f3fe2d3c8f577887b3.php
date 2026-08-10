<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [],
    'columns' => 'md:grid-cols-3',
    'gap' => 'gap-4',
    'cardStyle' => 'border-color: var(--theme-border-color); background-color: color-mix(in srgb, var(--theme-surface-base) 98%, transparent);',
    'progressTrackStyle' => 'background-color: color-mix(in srgb, var(--theme-border-color) 55%, transparent);',
    'showIcons' => true,
    'showProgress' => true,
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
    'items' => [],
    'columns' => 'md:grid-cols-3',
    'gap' => 'gap-4',
    'cardStyle' => 'border-color: var(--theme-border-color); background-color: color-mix(in srgb, var(--theme-surface-base) 98%, transparent);',
    'progressTrackStyle' => 'background-color: color-mix(in srgb, var(--theme-border-color) 55%, transparent);',
    'showIcons' => true,
    'showProgress' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->class("grid {$gap} {$columns}")); ?>>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php
            $tone = $item['tone'] ?? 'var(--theme-accent)';
            $progress = (int) ($item['progress'] ?? 0);
            $resolvedCardStyle = trim(($cardStyle ?? '').' '.($item['cardStyle'] ?? ''));
            $resolvedProgressTrackStyle = $item['progressTrackStyle'] ?? $progressTrackStyle;
            $iconSurface = $item['iconSurface'] ?? 'transparent';
            $iconBorder = $item['iconBorder'] ?? 'var(--theme-border-color)';
        ?>

        <div class="rounded-[1.35rem] border p-5" style="<?php echo e($resolvedCardStyle); ?>">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['label'])): ?>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em]" style="color: <?php echo e($tone); ?>;"><?php echo e($item['label']); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="mt-4 flex items-end gap-2">
                        <p class="text-3xl font-semibold leading-none" style="color: var(--theme-header-text-color);"><?php echo e($item['value'] ?? '0'); ?></p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['suffix'])): ?>
                            <span class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e($item['suffix']); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showIcons && !empty($item['icon'])): ?>
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border text-sm" style="border-color: <?php echo e($iconBorder); ?>; background: <?php echo e($iconSurface); ?>; color: <?php echo e($tone); ?>;">
                        <i class="<?php echo e(str_contains($item['icon'], 'fa-') ? $item['icon'] : 'fa-light '.$item['icon']); ?>"></i>
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['description'])): ?>
                <p class="mt-5 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e($item['description']); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showProgress): ?>
                <div class="mt-5 flex items-center gap-3">
                    <div class="h-2 flex-1 overflow-hidden rounded-full" style="<?php echo e($resolvedProgressTrackStyle); ?>">
                        <div class="h-full rounded-full" style="width: <?php echo e($progress); ?>%; background-color: <?php echo e($tone); ?>;"></div>
                    </div>
                    <span class="text-xs font-semibold" style="color: var(--theme-muted-text-color);"><?php echo e($progress); ?>%</span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/metric-strip.blade.php ENDPATH**/ ?>