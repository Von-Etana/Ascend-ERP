<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'sections' => [],
    'footer' => null,
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
    'footer' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<aside
    class="fixed inset-y-0 left-0 z-[140] hidden overflow-visible transition-[width] duration-[520ms] ease-[cubic-bezier(0.16,1,0.3,1)] will-change-[width] lg:block"
    x-bind:class="!sidebarPanelExpanded ? 'w-[70px] min-w-[70px] max-w-[70px]' : 'w-[14.75rem] min-w-[14.75rem] max-w-[14.75rem]'"
    x-on:mouseenter="startSidebarHover()"
    x-on:mouseleave="endSidebarHover()"
>
    <style>
        .app-sidebar-scroll {
            scrollbar-gutter: stable;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .app-sidebar-scroll::-webkit-scrollbar {
            display: none;
        }
    </style>

    <div
        class="flex h-full w-full flex-col border-r border-slate-300/70 bg-[var(--theme-sidebar-bg)] transition-[box-shadow] duration-[520ms] ease-[cubic-bezier(0.16,1,0.3,1)] dark:border-slate-800 dark:bg-[var(--theme-sidebar-bg)]"
        style="color: var(--theme-sidebar-text-color); border-color: var(--theme-border-color);"
    >
        <div class="relative h-[76px] px-5 py-4" x-bind:class="sidebarPanelExpanded ? 'px-5 py-4' : 'px-3 py-4'">
            <div class="flex h-10 items-center" x-bind:class="sidebarPanelExpanded ? 'justify-start' : 'justify-center'">
                <div x-cloak x-show="sidebarContentVisible" class="flex items-center gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white shadow-lg shadow-blue-900/20"><i class="fa-solid fa-arrow-up-right text-sm"></i></span>
                    <span class="text-[15px] font-bold tracking-tight text-white">Ascend Systems</span>
                </div>

                <div x-cloak x-show="!sidebarContentVisible">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white shadow-lg shadow-blue-900/20"><i class="fa-solid fa-arrow-up-right text-sm"></i></span>
                </div>
            </div>

            <button
                type="button"
                class="absolute -right-3 top-1/2 inline-flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-md border border-slate-300/80 bg-white text-slate-500 shadow-[0_6px_14px_-12px_rgba(15,23,42,0.2)] transition hover:border-slate-400 hover:bg-slate-50 hover:text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-slate-600 dark:hover:text-slate-200"
                x-on:click="toggleSidebar"
                x-bind:title="sidebarCollapsed ? 'Expand menu' : 'Collapse menu'"
            >
                <i class="fa-light text-[7px]" x-bind:class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
            </button>
        </div>

        <div class="app-sidebar-scroll flex-1 overflow-y-auto px-3 pt-2 pb-2">
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
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($footer): ?>
            <div class="border-t border-slate-300/70 dark:border-slate-800" style="border-color: var(--theme-border-color);" x-bind:class="sidebarContentVisible ? 'p-4' : 'flex justify-center p-1.5'">
                <?php echo e($footer); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</aside>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/layout/sidebar.blade.php ENDPATH**/ ?>