<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'value' => null,
    'error' => null,
    'help' => null,
    'presets' => ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#7c3aed', '#db2777', '#0f766e', '#0f172a'],
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
    'label' => null,
    'value' => null,
    'error' => null,
    'help' => null,
    'presets' => ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#7c3aed', '#db2777', '#0f766e', '#0f172a'],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $inputAttributes = $attributes->except('class');
?>

<?php if (isset($component)) { $__componentOriginald816e58425c8bb369623a9433739178c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald816e58425c8bb369623a9433739178c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.field','data' => ['label' => $label,'help' => $help,'error' => $error,'attributes' => $attributes->only('class')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'help' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($help),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($error),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes->only('class'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div
        class="relative"
        x-data="{
            open: false,
            value: <?php echo \Illuminate\Support\Js::from(strtolower($value ?: '#2563eb'))->toHtml() ?>,
            init() {
                this.$watch('value', () => this.syncInput());
            },
            normalize(input) {
                let next = String(input || '').trim().toLowerCase();

                if (next === '') {
                    return '#2563eb';
                }

                if (!next.startsWith('#')) {
                    next = `#${next}`;
                }

                if (/^#[0-9a-f]{3}$/.test(next)) {
                    next = `#${next[1]}${next[1]}${next[2]}${next[2]}${next[3]}${next[3]}`;
                }

                return /^#[0-9a-f]{6}$/.test(next) ? next : this.value;
            },
            sync(input) {
                this.value = this.normalize(input);
            },
            syncInput() {
                if (!this.$refs.input) {
                    return;
                }

                this.$refs.input.value = this.value || '';
                this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
                this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
            },
        }"
        x-on:keydown.escape.window="open = false"
    >
        <input
            x-ref="input"
            type="hidden"
            x-model="value"
            <?php echo e($inputAttributes); ?>

        >

        <button
            type="button"
            class="flex h-11 w-full items-center justify-between gap-3 rounded-[0.75rem] border px-3.5 text-sm font-semibold shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none transition duration-200 focus:border-[var(--theme-accent)] focus:ring-4 focus:ring-[color:rgba(var(--theme-accent-rgb),0.10)]"
            style="border-color: var(--theme-border-color); background-color: var(--theme-input-surface); color: var(--theme-input-text);"
            x-on:click="open = !open"
        >
            <span class="flex min-w-0 items-center gap-3">
                <span class="inline-flex h-6 w-6 shrink-0 rounded-full border border-white/70 shadow-[0_8px_18px_-12px_rgba(15,23,42,0.4)]" x-bind:style="`background-color: ${value}`"></span>
                <span class="truncate uppercase tracking-[0.04em]" x-text="value"></span>
            </span>

            <span class="inline-flex items-center gap-2 text-xs" style="color: var(--theme-muted-text-color);">
                <i class="fa-light fa-palette"></i>
                <i class="fa-light fa-chevron-down transition" x-bind:class="open ? 'rotate-180' : ''"></i>
            </span>
        </button>

        <div
            x-show="open"
            x-transition.opacity.scale.origin.top.left
            x-on:click.outside="open = false"
            style="display: none; background-color: var(--theme-surface-overlay); border-color: var(--theme-border-color);"
            class="absolute left-0 top-[calc(100%+0.65rem)] z-40 w-[17rem] rounded-[1rem] border p-3 shadow-[0_28px_70px_-28px_rgba(15,23,42,0.35)]"
        >
            <div class="flex items-center gap-3 rounded-[0.9rem] border px-3 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.03);">
                <label class="relative inline-flex h-10 w-10 shrink-0 cursor-pointer overflow-hidden rounded-full border border-white/70 shadow-[0_10px_20px_-14px_rgba(15,23,42,0.35)]">
                    <input
                        type="color"
                        x-model="value"
                        x-on:input="sync($event.target.value)"
                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                        aria-label="<?php echo e($label ?: __('Pick a color')); ?>"
                    >
                    <span class="h-full w-full" x-bind:style="`background-color: ${value}`"></span>
                </label>

                <input
                    type="text"
                    x-model="value"
                    x-on:input="sync($event.target.value)"
                    class="flex h-10 min-w-0 w-full rounded-[0.75rem] border px-3 text-sm font-semibold uppercase tracking-[0.04em] shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none transition duration-200 focus:border-[var(--theme-accent)] focus:ring-4 focus:ring-[color:rgba(var(--theme-accent-rgb),0.10)]"
                    style="border-color: var(--theme-border-color); background-color: var(--theme-input-surface); color: var(--theme-input-text);"
                >
            </div>

            <div class="mt-3 grid grid-cols-4 gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $presets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button
                        type="button"
                        class="group inline-flex h-10 items-center justify-center rounded-[0.9rem] border transition hover:-translate-y-0.5"
                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.03);"
                        x-on:click="value = '<?php echo e(strtolower($preset)); ?>'; open = false"
                        aria-label="<?php echo e(__('Choose color :color', ['color' => $preset])); ?>"
                    >
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full shadow-[0_8px_18px_-12px_rgba(15,23,42,0.4)]" style="background-color: <?php echo e($preset); ?>;">
                            <i x-show="value === '<?php echo e(strtolower($preset)); ?>'" class="fa-light fa-check text-[10px] text-white"></i>
                        </span>
                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald816e58425c8bb369623a9433739178c)): ?>
<?php $attributes = $__attributesOriginald816e58425c8bb369623a9433739178c; ?>
<?php unset($__attributesOriginald816e58425c8bb369623a9433739178c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald816e58425c8bb369623a9433739178c)): ?>
<?php $component = $__componentOriginald816e58425c8bb369623a9433739178c; ?>
<?php unset($__componentOriginald816e58425c8bb369623a9433739178c); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/color-picker.blade.php ENDPATH**/ ?>