<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'searchPlaceholder' => null,
    'headerItemsStart' => [],
    'headerItemsPrimaryNav' => [],
    'headerItemsCenter' => [],
    'headerItemsEnd' => [],
    'boxedLayout' => false,
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
    'searchPlaceholder' => null,
    'headerItemsStart' => [],
    'headerItemsPrimaryNav' => [],
    'headerItemsCenter' => [],
    'headerItemsEnd' => [],
    'boxedLayout' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $middleNavItems = count($headerItemsCenter) > 0 ? $headerItemsCenter : $headerItemsPrimaryNav;
?>

<header class="sticky top-0 z-40 border-b border-slate-200/80 backdrop-blur-xl dark:border-slate-800" style="background-color: rgba(var(--theme-header-surface-rgb), 0.68); border-color: var(--theme-shell-border-color);">
    <div class="relative mx-auto flex min-h-[78px] w-full items-center px-2 sm:px-6 xl:px-8" class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'app-theme-shell' => ! $boxedLayout,
        'max-w-[1440px]' => $boxedLayout,
    ]); ?>">
        <div class="flex min-w-0 items-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($start)): ?>
                <div class="lg:hidden">
                    <?php echo e($start); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($middleNavItems) > 0): ?>
                <div class="ml-2 xl:hidden">
                    <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu','data' => ['align' => 'left','width' => 'auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'left','width' => 'auto']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                         <?php $__env->slot('trigger', null, []); ?> 
                            <button
                                type="button"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-[0.75rem] border border-slate-200 text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-100 dark:hover:border-slate-700 dark:hover:bg-slate-900"
                                style="background-color: rgba(var(--theme-header-surface-rgb), 0.9); border-color: var(--theme-shell-border-color);"
                                aria-label="<?php echo e(__('Open workspace navigation')); ?>"
                            >
                                <i class="fa-light fa-rectangle-list text-[15px]"></i>
                            </button>
                         <?php $__env->endSlot(); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $middleNavItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['html'])): ?>
                                <?php echo $item['html']; ?>

                            <?php elseif(! empty($item['view'])): ?>
                                <?php echo $__env->make($item['view'], $item['data'] ?? [], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php elseif(! empty($item['route']) || ! empty($item['label']) || ! empty($item['icon'])): ?>
                                <?php
                                    $dropdownHref = $item['route'] ?? '#';
                                    $dropdownIcon = ! empty($item['icon']) ? $item['icon'] : null;
                                    $dropdownNavigate = ! empty($item['route']);
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dropdownNavigate): ?>
                                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => ''.e($dropdownHref).'','wire:navigate' => true,'icon' => $dropdownIcon]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($dropdownHref).'','wire:navigate' => true,'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dropdownIcon)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                        <?php echo e(__($item['label'] ?? '')); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => ''.e($dropdownHref).'','icon' => $dropdownIcon]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($dropdownHref).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dropdownIcon)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                        <?php echo e(__($item['label'] ?? '')); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($headerItemsStart) > 0): ?>
                <div class="hidden min-w-0 items-center gap-3 lg:flex lg:border-r lg:border-slate-200/80 lg:pr-4 dark:lg:border-slate-800/90" style="border-color: var(--theme-shell-border-color);">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $headerItemsStart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['html'])): ?>
                            <?php echo $item['html']; ?>

                        <?php elseif(! empty($item['view'])): ?>
                            <?php echo $__env->make($item['view'], $item['data'] ?? [], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php elseif(! empty($item['route']) || ! empty($item['label']) || ! empty($item['icon'])): ?>
                            <?php
                                $itemHref = $item['route'] ?? '#';
                                $itemIsActive = (bool) ($item['active'] ?? false);
                                $itemTitle = $item['title'] ?? $item['label'] ?? null;
                                $itemActiveClasses = $itemIsActive
                                    ? 'bg-[var(--theme-header-active)] text-white shadow-[0_12px_24px_-18px_rgba(var(--theme-header-active-rgb),0.45)] dark:bg-[var(--theme-header-active)] dark:text-white'
                                    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-950 dark:text-slate-400 dark:hover:bg-slate-800/80 dark:hover:text-white';
                                $itemClasses = $itemActiveClasses.' inline-flex h-11 items-center gap-2 rounded-[0.75rem] border border-slate-200/80 px-3 text-sm font-semibold transition dark:border-slate-700';
                                $itemStyle = $itemIsActive
                                    ? 'background-color: var(--theme-header-active); border-color: rgba(var(--theme-header-active-rgb), 0.22);'
                                    : 'background-color: rgba(var(--theme-header-surface-rgb), 0.9); border-color: var(--theme-shell-border-color);';
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['route'])): ?>
                                <a
                                    href="<?php echo e($itemHref); ?>"
                                    class="<?php echo e($itemClasses); ?>"
                                    style="<?php echo e($itemStyle); ?>"
                                    <?php if($itemTitle): ?> title="<?php echo e(__($itemTitle)); ?>" aria-label="<?php echo e(__($itemTitle)); ?>" <?php endif; ?>
                                    wire:navigate
                                >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> text-[15px]"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['label'])): ?>
                                        <span><?php echo e(__($item['label'])); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </a>
                            <?php else: ?>
                                <a
                                    href="<?php echo e($itemHref); ?>"
                                    class="<?php echo e($itemClasses); ?>"
                                    style="<?php echo e($itemStyle); ?>"
                                    <?php if($itemTitle): ?> title="<?php echo e(__($itemTitle)); ?>" aria-label="<?php echo e(__($itemTitle)); ?>" <?php endif; ?>
                                >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> text-[15px]"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['label'])): ?>
                                        <span><?php echo e(__($item['label'])); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($middleNavItems) > 0): ?>
            <div class="absolute left-1/2 top-1/2 hidden min-w-0 -translate-x-1/2 -translate-y-1/2 items-center justify-center px-4 xl:flex">
                <nav class="flex min-w-0 items-center gap-1 overflow-x-auto rounded-[0.75rem] border border-slate-200/70 p-1.5 shadow-[0_16px_30px_-28px_rgba(15,23,42,0.24)] dark:border-slate-800" style="background-color: rgba(var(--theme-header-surface-rgb), 0.9); border-color: var(--theme-shell-border-color);">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $middleNavItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['html'])): ?>
                            <?php echo $item['html']; ?>

                        <?php elseif(! empty($item['view'])): ?>
                            <?php echo $__env->make($item['view'], $item['data'] ?? [], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php elseif(! empty($item['route']) || ! empty($item['label']) || ! empty($item['icon'])): ?>
                            <?php
                                $itemHref = $item['route'] ?? '#';
                                $itemActiveClasses = ($item['active'] ?? false)
                                    ? 'bg-[var(--theme-header-active)] text-white shadow-[0_12px_24px_-18px_rgba(var(--theme-header-active-rgb),0.45)] dark:bg-[var(--theme-header-active)] dark:text-white'
                                    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-950 dark:text-slate-400 dark:hover:bg-slate-800/80 dark:hover:text-white';
                                $itemClasses = $itemActiveClasses.' inline-flex items-center gap-2 rounded-[0.65rem] px-3.5 py-2 text-sm font-semibold transition';
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['route'])): ?>
                                <a
                                    href="<?php echo e($itemHref); ?>"
                                    class="<?php echo e($itemClasses); ?>"
                                    wire:navigate
                                >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> text-[15px]"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['label'])): ?>
                                        <span><?php echo e(__($item['label'])); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </a>
                            <?php else: ?>
                                <a
                                    href="<?php echo e($itemHref); ?>"
                                    class="<?php echo e($itemClasses); ?>"
                                >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> text-[15px]"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['label'])): ?>
                                        <span><?php echo e(__($item['label'])); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </nav>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="ml-auto flex items-center gap-2.5 md:gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(trim((string) ($center ?? '')) !== ''): ?>
                <div class="hidden h-11 items-center gap-1 rounded-[0.75rem] border border-slate-200/70 px-1.5 shadow-[0_12px_24px_-20px_rgba(15,23,42,0.2)] dark:border-slate-800 md:flex" style="background-color: rgba(var(--theme-header-surface-rgb), 0.9); border-color: var(--theme-shell-border-color);">
                    <?php echo e($center ?? ''); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex items-center gap-2.5">
                <?php echo e($end ?? ''); ?>


                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $headerItemsEnd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['html'])): ?>
                        <?php echo $item['html']; ?>

                    <?php elseif(! empty($item['view'])): ?>
                        <?php echo $__env->make($item['view'], $item['data'] ?? [], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif(! empty($item['route']) || ! empty($item['label']) || ! empty($item['icon'])): ?>
                        <?php
                            $itemHref = $item['route'] ?? '#';
                            $itemIsActive = (bool) ($item['active'] ?? false);
                            $itemTitle = $item['title'] ?? $item['label'] ?? null;
                            $itemActiveClasses = $itemIsActive
                                ? 'bg-[var(--theme-header-active)] text-white shadow-[0_12px_24px_-18px_rgba(var(--theme-header-active-rgb),0.45)] dark:bg-[var(--theme-header-active)] dark:text-white'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-950 dark:text-slate-400 dark:hover:bg-slate-800/80 dark:hover:text-white';
                            $itemClasses = $itemActiveClasses.' inline-flex h-11 items-center gap-2 rounded-[0.75rem] border border-slate-200/80 px-3.5 text-sm font-semibold transition dark:border-slate-700';
                            $itemStyle = $itemIsActive
                                ? 'background-color: var(--theme-header-active); border-color: rgba(var(--theme-header-active-rgb), 0.22);'
                                : 'background-color: rgba(var(--theme-header-surface-rgb), 0.9); border-color: var(--theme-shell-border-color);';
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['route'])): ?>
                            <a
                                href="<?php echo e($itemHref); ?>"
                                class="<?php echo e($itemClasses); ?>"
                                style="<?php echo e($itemStyle); ?>"
                                <?php if($itemTitle): ?> title="<?php echo e(__($itemTitle)); ?>" aria-label="<?php echo e(__($itemTitle)); ?>" <?php endif; ?>
                                wire:navigate
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['icon'])): ?>
                                    <i class="<?php echo e($item['icon']); ?> text-[15px]"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['label'])): ?>
                                    <span><?php echo e(__($item['label'])); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </a>
                        <?php else: ?>
                            <a
                                href="<?php echo e($itemHref); ?>"
                                class="<?php echo e($itemClasses); ?>"
                                style="<?php echo e($itemStyle); ?>"
                                <?php if($itemTitle): ?> title="<?php echo e(__($itemTitle)); ?>" aria-label="<?php echo e(__($itemTitle)); ?>" <?php endif; ?>
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['icon'])): ?>
                                    <i class="<?php echo e($item['icon']); ?> text-[15px]"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($item['label'])): ?>
                                    <span><?php echo e(__($item['label'])); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/layout/header.blade.php ENDPATH**/ ?>