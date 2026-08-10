<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'sections' => [],
    'dashboardSwitch' => null,
    'planCard' => null,
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
    'sections' => [],
    'dashboardSwitch' => null,
    'planCard' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-cloak
    x-show="sidebarOpen"
    class="fixed inset-0 lg:hidden"
    style="z-index: 140;"
    x-on:keydown.escape.window="sidebarOpen = false"
>
    <div class="absolute inset-0 bg-slate-950/60" x-on:click="sidebarOpen = false"></div>

    <div
        class="relative flex h-full w-[14.75rem] max-w-[85vw] flex-col border-r border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-950"
        style="z-index: 141; color: var(--theme-sidebar-text-color); border-color: var(--theme-border-color); background: var(--theme-sidebar-bg);"
        x-data="{ sidebarContentVisible: true, sidebarCollapsed: false, sidebarPanelExpanded: true }"
    >
        <div class="relative h-[76px] px-5 py-4">
            <div class="flex h-10 items-center justify-start">
                <img
                    x-show="appearanceResolved !== 'dark'"
                    src="<?php echo e(theme_asset('assets/img/logo-brand-dark.png', 'app')); ?>"
                    alt="<?php echo e(config('app.name', 'Stackposts')); ?>"
                    class="block h-8 w-auto max-w-none shrink-0"
                >
                <img
                    x-show="appearanceResolved === 'dark'"
                    src="<?php echo e(theme_asset('assets/img/logo-brand-light.png', 'app')); ?>"
                    alt="<?php echo e(config('app.name', 'Stackposts')); ?>"
                    class="block h-8 w-auto max-w-none shrink-0"
                >
            </div>

            <button
                type="button"
                class="absolute -right-3 top-1/2 inline-flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-md border border-slate-300/80 bg-white text-slate-500 shadow-[0_6px_14px_-12px_rgba(15,23,42,0.2)] transition hover:border-slate-400 hover:bg-slate-50 hover:text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-slate-600 dark:hover:text-slate-200"
                x-on:click="sidebarOpen = false"
                title="Close menu"
            >
                <i class="fa-light fa-xmark text-[11px]"></i>
            </button>
        </div>

        <div class="app-sidebar-scroll min-h-0 flex-1 overflow-y-auto px-3 pt-2 pb-3">
            <?php if (isset($component)) { $__componentOriginal49e4f297ef488e629570372b52aa76c6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49e4f297ef488e629570372b52aa76c6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::layout.sidebar-menu','data' => ['sections' => $sections,'mode' => 'desktop']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.sidebar-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sections' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sections),'mode' => 'desktop']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal49e4f297ef488e629570372b52aa76c6)): ?>
<?php $attributes = $__attributesOriginal49e4f297ef488e629570372b52aa76c6; ?>
<?php unset($__attributesOriginal49e4f297ef488e629570372b52aa76c6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal49e4f297ef488e629570372b52aa76c6)): ?>
<?php $component = $__componentOriginal49e4f297ef488e629570372b52aa76c6; ?>
<?php unset($__componentOriginal49e4f297ef488e629570372b52aa76c6); ?>
<?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dashboardSwitch || $planCard): ?>
                <div class="mt-4 border-t pt-4" style="border-color: var(--theme-border-color);">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dashboardSwitch): ?>
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => $dashboardSwitch['route'],'class' => 'w-full justify-center','size' => 'sm','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dashboardSwitch['route']),'class' => 'w-full justify-center','size' => 'sm','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <i class="fa-light fa-arrow-right-arrow-left mr-2 text-[12px]"></i>
                            <?php echo e(__($dashboardSwitch['label'])); ?>

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
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($planCard): ?>
                        <div
                            class="mt-3 rounded-2xl border p-3"
                            style="border-color: var(--theme-border-color); background: color-mix(in srgb, var(--theme-sidebar-bg) 84%, white 16%);"
                        >
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Your plan')); ?></p>

                            <div class="mt-3 flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);">
                                        <i class="fa-solid fa-crown mr-1 text-[11px]"></i><?php echo e($planCard['name']); ?>

                                    </p>
                                    <div class="mt-2 flex items-center gap-1 text-xs whitespace-nowrap" style="color: var(--theme-muted-text-color);">
                                        <span><?php echo e(__('Expire:')); ?></span>
                                        <span class="font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($planCard['expiry']); ?></span>
                                    </div>
                                </div>

                                <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-[11px] font-semibold whitespace-nowrap <?php echo e($planCard['badge_tone'] === 'success' ? 'bg-emerald-400/12 text-emerald-500' : 'bg-slate-400/10 text-slate-500 dark:text-slate-300'); ?>">
                                    <?php echo e($planCard['badge']); ?>

                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-2 text-xs">
                                <span style="color: var(--theme-muted-text-color);"><?php echo e(__('Credits used')); ?></span>
                                <span style="color: var(--theme-header-text-color);"><?php echo e($planCard['credits_used_label']); ?> / <?php echo e($planCard['credits_limit_label']); ?></span>
                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full" style="background: color-mix(in srgb, var(--theme-sidebar-bg) 72%, black 28%);">
                                <div
                                    class="h-full rounded-full"
                                    style="width: <?php echo e($planCard['unlimited'] ? 100 : ($planCard['credits_percent'] ?? 0)); ?>%; background: linear-gradient(90deg, var(--theme-accent,#2563eb) 0%, color-mix(in srgb, var(--theme-accent,#2563eb) 72%, #8b5cf6 28%) 100%);"
                                ></div>
                            </div>

                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => ''.e($planCard['details_route']).'','class' => 'mt-4 w-full justify-center','size' => 'sm','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($planCard['details_route']).'','class' => 'mt-4 w-full justify-center','size' => 'sm','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                <?php echo e(__('Upgrade / Details')); ?>

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
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/layout/mobile-sidebar.blade.php ENDPATH**/ ?>