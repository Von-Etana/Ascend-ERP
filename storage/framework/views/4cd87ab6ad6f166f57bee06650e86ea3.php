<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => null,
    'label' => null,
    'value' => null,
    'error' => null,
    'placeholder' => null,
    'placement' => 'bottom',
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
    'placement' => 'bottom',
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
    $inputName = $name;

    if (! $inputName) {
        foreach (['wire:model', 'wire:model.live', 'wire:model.defer', 'wire:model.lazy'] as $bindingAttribute) {
            $boundModel = $attributes->get($bindingAttribute);

            if (filled($boundModel)) {
                $inputName = $boundModel;
                break;
            }
        }
    }
?>

<div <?php echo e($attributes->only('class')->class('space-y-2.5')); ?>>
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

    <div
        class="relative z-20"
        x-data="{
            open: false,
            value: <?php echo \Illuminate\Support\Js::from($value)->toHtml() ?>,
            preferredPlacement: <?php echo \Illuminate\Support\Js::from($placement)->toHtml() ?>,
            actualPlacement: <?php echo \Illuminate\Support\Js::from($placement === 'top' ? 'top' : 'bottom')->toHtml() ?>,
            viewDate: null,
            dayNames: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            init() {
                this.viewDate = this.parseDate(this.value) ?? this.today();
                this.$watch('value', () => this.syncInput());
            },
            toggleOpen() {
                if (this.open) {
                    this.open = false;

                    return;
                }

                this.open = true;
                this.$nextTick(() => this.calculatePlacement());
            },
            calculatePlacement() {
                if (!this.$refs.panel) {
                    return;
                }

                const rootRect = this.$el.getBoundingClientRect();
                const panelHeight = this.$refs.panel.offsetHeight || 360;
                const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
                const gap = 12;
                const spaceBelow = viewportHeight - rootRect.bottom - gap;
                const spaceAbove = rootRect.top - gap;

                if (this.preferredPlacement === 'top') {
                    this.actualPlacement = spaceAbove >= panelHeight || spaceAbove >= spaceBelow ? 'top' : 'bottom';

                    return;
                }

                this.actualPlacement = spaceBelow >= panelHeight || spaceBelow >= spaceAbove ? 'bottom' : 'top';
            },
            today() {
                const now = new Date();
                return new Date(now.getFullYear(), now.getMonth(), now.getDate());
            },
            parseDate(value) {
                if (!value) {
                    return null;
                }

                const parts = value.split('-').map(Number);

                if (parts.length !== 3 || parts.some(Number.isNaN)) {
                    return null;
                }

                return new Date(parts[0], parts[1] - 1, parts[2]);
            },
            formatValue(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            },
            formatDisplay(value) {
                const date = this.parseDate(value);

                if (!date) {
                    return <?php echo \Illuminate\Support\Js::from($placeholder ?: __('Select a date'))->toHtml() ?>;
                }

                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');

                return `${day}/${month}/${date.getFullYear()}`;
            },
            monthLabel() {
                return `${this.monthNames[this.viewDate.getMonth()]} ${this.viewDate.getFullYear()}`;
            },
            previousMonth() {
                this.viewDate = new Date(this.viewDate.getFullYear(), this.viewDate.getMonth() - 1, 1);
            },
            nextMonth() {
                this.viewDate = new Date(this.viewDate.getFullYear(), this.viewDate.getMonth() + 1, 1);
            },
            selectDate(date) {
                this.value = this.formatValue(date);
                this.viewDate = new Date(date.getFullYear(), date.getMonth(), 1);
                this.open = false;
            },
            clear() {
                this.value = '';
                this.open = false;
            },
            pickToday() {
                this.selectDate(this.today());
            },
            isSelected(day) {
                return day.currentMonth && this.value === this.formatValue(day.date);
            },
            isToday(day) {
                const today = this.today();

                return day.date.getFullYear() === today.getFullYear()
                    && day.date.getMonth() === today.getMonth()
                    && day.date.getDate() === today.getDate();
            },
            days() {
                const year = this.viewDate.getFullYear();
                const month = this.viewDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const startOffset = firstDay.getDay();
                const gridStart = new Date(year, month, 1 - startOffset);
                const days = [];

                for (let index = 0; index < 42; index += 1) {
                    const date = new Date(gridStart.getFullYear(), gridStart.getMonth(), gridStart.getDate() + index);

                    days.push({
                        key: `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`,
                        date,
                        number: date.getDate(),
                        currentMonth: date.getMonth() === month,
                    });
                }

                return days;
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
        x-on:resize.window="if (open) calculatePlacement()"
        x-on:scroll.window.passive="if (open) calculatePlacement()"
    >
        <input x-ref="input" type="hidden" <?php if($inputName): ?> name="<?php echo e($inputName); ?>" <?php endif; ?> x-model="value" <?php echo e($inputAttributes); ?>>

        <button
            type="button"
            class="flex h-11 w-full items-center justify-between gap-3 border px-4 text-left text-sm font-medium shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none transition duration-200 focus:border-[var(--theme-accent)] focus:ring-4 focus:ring-[color:rgba(var(--theme-accent-rgb),0.10)]"
            style="border-radius: var(--theme-input-radius, 0.75rem); border-color: var(--theme-border-color); background-color: var(--theme-input-surface); color: var(--theme-input-text);"
            x-on:click="toggleOpen()"
        >
            <span :class="value ? '' : 'text-[var(--theme-input-placeholder)]'" x-text="formatDisplay(value)"></span>
            <i class="fa-light fa-calendar-days text-sm" style="color: var(--theme-muted-text-color);"></i>
        </button>

        <div
            x-ref="panel"
            x-show="open"
            x-transition.opacity.scale.90
            x-on:click.outside="open = false"
            style="display: none; background-color: var(--theme-surface-overlay); border-color: var(--theme-border-color);"
            class="absolute left-0 z-[120] w-[19rem] rounded-[1rem] border p-4 shadow-[0_28px_70px_-28px_rgba(15,23,42,0.35)]"
            :class="actualPlacement === 'top'
                ? 'bottom-[calc(100%+0.65rem)] origin-bottom-left'
                : 'top-[calc(100%+0.65rem)] origin-top-left'"
        >
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Calendar')); ?></p>
                    <p class="mt-1 text-sm font-semibold" style="color: var(--theme-header-text-color);" x-text="monthLabel()"></p>
                </div>

                <div class="flex items-center gap-1.5">
                    <button
                        type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-[0.8rem] border transition hover:bg-slate-50 dark:hover:bg-slate-800"
                        style="border-color: var(--theme-border-color); color: var(--theme-header-text-color);"
                        x-on:click="previousMonth()"
                        aria-label="<?php echo e(__('Previous month')); ?>"
                    >
                        <i class="fa-light fa-chevron-left text-xs"></i>
                    </button>
                    <button
                        type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-[0.8rem] border transition hover:bg-slate-50 dark:hover:bg-slate-800"
                        style="border-color: var(--theme-border-color); color: var(--theme-header-text-color);"
                        x-on:click="nextMonth()"
                        aria-label="<?php echo e(__('Next month')); ?>"
                    >
                        <i class="fa-light fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-1 text-center">
                <template x-for="dayName in dayNames" :key="dayName">
                    <div class="pb-2 text-[11px] font-semibold uppercase tracking-[0.12em]" style="color: var(--theme-muted-text-color);" x-text="dayName"></div>
                </template>

                <template x-for="day in days()" :key="day.key">
                    <button
                        type="button"
                        class="inline-flex h-10 items-center justify-center rounded-[0.8rem] text-sm font-semibold transition"
                        :class="{
                            'text-slate-400 dark:text-slate-500': !day.currentMonth && !isSelected(day),
                            'text-[var(--theme-header-text-color)]': day.currentMonth && !isSelected(day),
                            'bg-[var(--theme-accent)] text-white shadow-[0_14px_24px_-16px_rgba(var(--theme-accent-rgb),0.8)]': isSelected(day),
                            'ring-1 ring-[color:rgba(var(--theme-accent-rgb),0.28)]': isToday(day) && !isSelected(day),
                        }"
                        x-on:click="selectDate(day.date)"
                    >
                        <span x-text="day.number"></span>
                    </button>
                </template>
            </div>

            <div class="mt-4 flex items-center justify-between border-t pt-4" style="border-color: var(--theme-border-color);">
                <button
                    type="button"
                    class="text-sm font-semibold transition hover:opacity-80"
                    style="color: var(--theme-muted-text-color);"
                    x-on:click="clear()"
                >
                    <?php echo e(__('Clear')); ?>

                </button>

                <button
                    type="button"
                    class="text-sm font-semibold transition hover:opacity-80"
                    style="color: var(--theme-accent);"
                    x-on:click="pickToday()"
                >
                    <?php echo e(__('Today')); ?>

                </button>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
        <p class="text-sm font-medium" style="color: var(--theme-danger-color);"><?php echo e($error); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/date-picker.blade.php ENDPATH**/ ?>