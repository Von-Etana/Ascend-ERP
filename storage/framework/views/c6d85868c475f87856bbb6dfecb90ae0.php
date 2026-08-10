<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => null,
    'label' => null,
    'value' => null,
    'error' => null,
    'placeholder' => null,
    'pickerAlign' => 'left',
    'pickerPosition' => 'bottom',
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
    'name' => null,
    'label' => null,
    'value' => null,
    'error' => null,
    'placeholder' => null,
    'pickerAlign' => 'left',
    'pickerPosition' => 'bottom',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $rootAttributes = $attributes->except('name');
    $inputAttributes = $attributes->except('class')->except('x-model');
?>

<div
    <?php echo e($rootAttributes->only('class')->class('space-y-2.5')); ?>

    <?php echo e($rootAttributes->except('class')); ?>

    x-modelable="value"
    x-data="{
        open: false,
        value: <?php echo \Illuminate\Support\Js::from($value)->toHtml() ?>,
        pickerAlign: <?php echo \Illuminate\Support\Js::from($pickerAlign)->toHtml() ?>,
        pickerPosition: <?php echo \Illuminate\Support\Js::from($pickerPosition)->toHtml() ?>,
        resolvedAlign: <?php echo \Illuminate\Support\Js::from($pickerAlign === 'right' ? 'right' : 'left')->toHtml() ?>,
        resolvedPosition: <?php echo \Illuminate\Support\Js::from($pickerPosition === 'top' ? 'top' : 'bottom')->toHtml() ?>,
        hour: '09',
        minute: '00',
        meridiem: 'AM',
        hours: Array.from({ length: 12 }, (_, index) => String(index + 1).padStart(2, '0')),
        minutes: Array.from({ length: 60 }, (_, index) => String(index).padStart(2, '0')),
        init() {
            this.hydrateFromValue(this.value);
            this.$watch('value', (nextValue) => {
                this.hydrateFromValue(nextValue);
            });
        },
        toggleOpen() {
            this.open = !this.open;

            if (this.open) {
                this.$nextTick(() => this.updatePlacement());
            }
        },
        updatePlacement() {
            const trigger = this.$refs.trigger;
            const panel = this.$refs.panel;

            if (!trigger || !panel) {
                return;
            }

            const gap = 12;
            const triggerRect = trigger.getBoundingClientRect();
            const panelWidth = panel.offsetWidth || 280;
            const panelHeight = panel.offsetHeight || 320;
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            if (this.pickerPosition === 'auto') {
                const spaceBelow = viewportHeight - triggerRect.bottom;
                const spaceAbove = triggerRect.top;
                this.resolvedPosition = spaceBelow >= (panelHeight + gap) || spaceBelow >= spaceAbove ? 'bottom' : 'top';
            } else {
                this.resolvedPosition = this.pickerPosition === 'top' ? 'top' : 'bottom';
            }

            if (this.pickerAlign === 'auto') {
                const spaceRight = viewportWidth - triggerRect.left;
                const spaceLeft = triggerRect.right;
                this.resolvedAlign = spaceRight >= panelWidth || spaceRight >= spaceLeft ? 'left' : 'right';
            } else {
                this.resolvedAlign = this.pickerAlign === 'right' ? 'right' : 'left';
            }
        },
        hydrateFromValue(value) {
            const normalized = String(value || '').trim();

            if (normalized === '') {
                this.hour = '09';
                this.minute = '00';
                this.meridiem = 'AM';
                return;
            }

            const match = normalized.match(/^(\d{1,2}):(\d{2})(?:\s?(AM|PM))?$/i);

            if (!match) {
                this.hour = '09';
                this.minute = '00';
                this.meridiem = 'AM';
                return;
            }

            let hours = Number(match[1]);
            const minutes = String(match[2]).padStart(2, '0');
            const explicitMeridiem = String(match[3] || '').toUpperCase();

            if (explicitMeridiem === 'AM' || explicitMeridiem === 'PM') {
                this.meridiem = explicitMeridiem;
                this.hour = String(hours).padStart(2, '0');
                this.minute = minutes;
                return;
            }

            this.meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            this.hour = String(hours).padStart(2, '0');
            this.minute = minutes;
        },
        formatValue() {
            let hours = Number(this.hour);
            const minutes = String(this.minute).padStart(2, '0');

            if (this.meridiem === 'PM' && hours < 12) {
                hours += 12;
            }

            if (this.meridiem === 'AM' && hours === 12) {
                hours = 0;
            }

            return `${String(hours).padStart(2, '0')}:${minutes}`;
        },
        formatDisplay() {
            return `${this.hour}:${this.minute} ${this.meridiem}`;
        },
        clear() {
            this.value = '';
            this.open = false;
            this.syncInput();
        },
        pickNow() {
            const now = new Date();
            let hours = now.getHours();
            this.meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            this.hour = String(hours).padStart(2, '0');
            this.minute = String(now.getMinutes()).padStart(2, '0');
            this.commit(true);
        },
        commit(close = false) {
            this.value = this.formatValue();
            this.syncInput();

            if (close) {
                this.open = false;
            }
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
    x-on:resize.window="if (open) updatePlacement()"
    x-on:scroll.window="if (open) updatePlacement()"
    x-on:keydown.escape.window="open = false"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($label); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="relative">
        <input x-ref="input" type="hidden" <?php if($name): ?> name="<?php echo e($name); ?>" <?php endif; ?> x-bind:value="value" <?php echo e($inputAttributes); ?>>

        <button
            x-ref="trigger"
            type="button"
            class="flex h-11 w-full items-center justify-between gap-3 border px-4 text-left text-sm font-medium shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none transition duration-200 focus:border-[var(--theme-accent)] focus:ring-4 focus:ring-[color:rgba(var(--theme-accent-rgb),0.10)]"
            style="border-radius: var(--theme-input-radius, 0.75rem); border-color: var(--theme-border-color); background-color: var(--theme-input-surface); color: var(--theme-input-text);"
            x-on:click="toggleOpen()"
        >
            <span :class="value ? '' : 'text-[var(--theme-input-placeholder)]'" x-text="value ? formatDisplay() : <?php echo \Illuminate\Support\Js::from($placeholder ?: __('Select time'))->toHtml() ?>"></span>
            <i class="fa-light fa-clock text-sm" style="color: var(--theme-muted-text-color);"></i>
        </button>

        <div
            x-ref="panel"
            x-show="open"
            x-transition.opacity.scale
            x-on:click.outside="open = false"
            style="display: none; background-color: var(--theme-surface-overlay); border-color: var(--theme-border-color);"
            x-bind:class="{
                'left-0': resolvedAlign === 'left',
                'right-0': resolvedAlign === 'right',
                'top-[calc(100%+0.65rem)] origin-top': resolvedPosition === 'bottom',
                'bottom-[calc(100%+0.65rem)] origin-bottom': resolvedPosition === 'top',
            }"
            class="absolute z-40 w-[17rem] max-w-[min(17rem,calc(100vw-1rem))] overflow-hidden rounded-[1rem] border shadow-[0_28px_70px_-28px_rgba(15,23,42,0.35)]"
        >
            <div class="p-4">
                <div class="grid grid-cols-3 gap-2">
                    <div class="space-y-1.5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Hour')); ?></p>
                        <select
                            x-model="hour"
                            x-on:change="commit()"
                            class="flex h-11 w-full rounded-[0.75rem] border px-3 text-sm font-semibold shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none transition duration-200 focus:border-[var(--theme-accent)] focus:ring-4 focus:ring-[color:rgba(var(--theme-accent-rgb),0.10)]"
                            style="border-color: var(--theme-border-color); background-color: var(--theme-input-surface); color: var(--theme-input-text);"
                        >
                            <template x-for="option in hours" :key="'hour-option-'+option">
                                <option x-bind:value="option" x-text="option"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Min')); ?></p>
                        <select
                            x-model="minute"
                            x-on:change="commit()"
                            class="flex h-11 w-full rounded-[0.75rem] border px-3 text-sm font-semibold shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none transition duration-200 focus:border-[var(--theme-accent)] focus:ring-4 focus:ring-[color:rgba(var(--theme-accent-rgb),0.10)]"
                            style="border-color: var(--theme-border-color); background-color: var(--theme-input-surface); color: var(--theme-input-text);"
                        >
                            <template x-for="option in minutes" :key="'minute-option-'+option">
                                <option x-bind:value="option" x-text="option"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('AM/PM')); ?></p>
                        <select
                            x-model="meridiem"
                            x-on:change="commit()"
                            class="flex h-11 w-full rounded-[0.75rem] border px-3 text-sm font-semibold shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none transition duration-200 focus:border-[var(--theme-accent)] focus:ring-4 focus:ring-[color:rgba(var(--theme-accent-rgb),0.10)]"
                            style="border-color: var(--theme-border-color); background-color: var(--theme-input-surface); color: var(--theme-input-text);"
                        >
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between border-t pt-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                    <button
                        type="button"
                        class="text-sm font-semibold transition hover:opacity-80"
                        style="color: var(--theme-accent);"
                        x-on:click="clear()"
                    >
                        <?php echo e(__('Clear')); ?>

                    </button>

                    <button
                        type="button"
                        class="text-sm font-semibold transition hover:opacity-80"
                        style="color: var(--theme-accent);"
                        x-on:click="pickNow()"
                    >
                        <?php echo e(__('Now')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
        <p class="text-sm font-medium" style="color: var(--theme-danger-color);"><?php echo e($error); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/time-picker.blade.php ENDPATH**/ ?>