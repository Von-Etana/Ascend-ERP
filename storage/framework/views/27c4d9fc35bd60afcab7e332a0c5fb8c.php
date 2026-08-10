<div class="space-y-8" x-data="{ branch: <?php if ((object) ('branch') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('branch'->value()); ?>')<?php echo e('branch'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('branch'); ?>')<?php endif; ?>, period: <?php if ((object) ('period') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('period'->value()); ?>')<?php echo e('period'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('period'); ?>')<?php endif; ?> }">
    <?php if (isset($component)) { $__componentOriginal840e8050c4ab0422c87b76d7f0ecdbc7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal840e8050c4ab0422c87b76d7f0ecdbc7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.onboarding-wizard','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.onboarding-wizard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal840e8050c4ab0422c87b76d7f0ecdbc7)): ?>
<?php $attributes = $__attributesOriginal840e8050c4ab0422c87b76d7f0ecdbc7; ?>
<?php unset($__attributesOriginal840e8050c4ab0422c87b76d7f0ecdbc7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal840e8050c4ab0422c87b76d7f0ecdbc7)): ?>
<?php $component = $__componentOriginal840e8050c4ab0422c87b76d7f0ecdbc7; ?>
<?php unset($__componentOriginal840e8050c4ab0422c87b76d7f0ecdbc7); ?>
<?php endif; ?>

    <!-- Top Greeting & Header Controls -->
    <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500"><?php echo e(__('Ascend Systems operating workspace')); ?></p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 dark:text-white sm:text-4xl">
                <?php echo e(__('Good morning, :name', ['name' => auth()->user()?->first_name ?: auth()->user()?->name ?: 'there'])); ?>

            </h1>
            <p class="mt-2 text-sm text-slate-500"><?php echo e(__('Here’s what’s happening across your business today.')); ?></p>
        </div>
        <div class="flex flex-wrap gap-3">
            <label class="relative">
                <span class="sr-only"><?php echo e(__('Branch')); ?></span>
                <select wire:model.live="branch" class="h-11 min-w-40 appearance-none rounded-xl border border-slate-200 bg-white px-4 pr-10 text-sm font-semibold text-slate-700 shadow-sm outline-none focus:border-blue-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                    <option><?php echo e(__('All branches')); ?></option>
                    <option><?php echo e(__('Abuja HQ')); ?></option>
                    <option><?php echo e(__('Lagos branch')); ?></option>
                    <option><?php echo e(__('Port Harcourt branch')); ?></option>
                </select>
                <i class="fa-light fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
            </label>
            <label class="relative">
                <span class="sr-only"><?php echo e(__('Period')); ?></span>
                <select wire:model.live="period" class="h-11 min-w-36 appearance-none rounded-xl border border-slate-200 bg-white px-4 pr-10 text-sm font-semibold text-slate-700 shadow-sm outline-none focus:border-blue-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                    <option><?php echo e(__('This month')); ?></option>
                    <option><?php echo e(__('This quarter')); ?></option>
                    <option><?php echo e(__('This year')); ?></option>
                </select>
                <i class="fa-light fa-calendar pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
            </label>
        </div>
    </div>

    <!-- Status Toast Notification -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
            <i class="fa-light fa-circle-check mr-2"></i> <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Metric Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <!-- Revenue Card -->
        <a href="<?php echo e(route('admin-user-report.index')); ?>" wire:navigate class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] transition-all hover:border-blue-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400 group-hover:text-blue-600 transition-colors"><?php echo e(__('Revenue')); ?></p>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950 dark:text-white">₦1,245,780</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fa-light fa-circle-dollar text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-xs font-semibold text-emerald-600">
                <i class="fa-light fa-arrow-trend-up mr-1"></i>+18.6% <span class="font-normal text-slate-400"><?php echo e(__('vs previous period')); ?></span>
            </p>
        </a>

        <!-- Open Deals Card -->
        <a href="<?php echo e(route('portal.publishing.calendar')); ?>" wire:navigate class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] transition-all hover:border-violet-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400 group-hover:text-violet-600 transition-colors"><?php echo e(__('Open deals')); ?></p>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950 dark:text-white">56</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 group-hover:bg-violet-600 group-hover:text-white transition-colors">
                    <i class="fa-light fa-users text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-xs font-semibold text-emerald-600">
                <i class="fa-light fa-arrow-trend-up mr-1"></i>+12.0% <span class="font-normal text-slate-400"><?php echo e(__('vs previous period')); ?></span>
            </p>
        </a>

        <!-- Low Stock Card (Triggers Modal) -->
        <button type="button" wire:click="openAlertModal('low_stock')" class="group text-left rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] transition-all hover:border-amber-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400 group-hover:text-amber-600 transition-colors"><?php echo e(__('Low stock')); ?></p>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950 dark:text-white">23</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <i class="fa-light fa-triangle-exclamation text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-xs font-semibold text-rose-500">
                <i class="fa-light fa-arrow-trend-up mr-1"></i>8.0% <span class="font-normal text-slate-400"><?php echo e(__('needs attention')); ?></span>
            </p>
        </button>

        <!-- Tasks Due Card -->
        <a href="<?php echo e(route('portal.publishing.queue')); ?>" wire:navigate class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] transition-all hover:border-emerald-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400 group-hover:text-emerald-600 transition-colors"><?php echo e(__('Tasks due')); ?></p>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950 dark:text-white">19</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fa-light fa-circle-check text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-xs font-semibold text-rose-500">
                <i class="fa-light fa-clock mr-1"></i>13.6% <span class="font-normal text-slate-400"><?php echo e(__('due this week')); ?></span>
            </p>
        </a>
    </div>

    <!-- Revenue Overview & Sales Pipeline -->
    <div class="grid gap-6 xl:grid-cols-[1.08fr_1.32fr]">
        <!-- Revenue Chart Section -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Revenue overview')); ?></h2>
                    <p class="mt-1 text-sm text-slate-500"><?php echo e(__('Performance across :branch · :period', ['branch' => $branch, 'period' => $period])); ?></p>
                </div>
                <div class="relative" x-data="{ openTimeframe: false }">
                    <button type="button" @click="openTimeframe = !openTimeframe" class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        <?php echo e(__($timeframe)); ?> <i class="fa-light fa-chevron-down ml-2"></i>
                    </button>
                    <div x-show="openTimeframe" @click.away="openTimeframe = false" class="absolute right-0 z-20 mt-2 w-32 rounded-xl border border-slate-200 bg-white p-1 shadow-lg dark:border-slate-800 dark:bg-slate-900" x-cloak>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Daily', 'Weekly', 'Monthly', 'Yearly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frame): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <button type="button" wire:click="setTimeframe('<?php echo e($frame); ?>')" @click="openTimeframe = false" class="block w-full rounded-lg px-3 py-2 text-left text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800 <?php echo e($timeframe === $frame ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30' : ''); ?>">
                                <?php echo e(__($frame)); ?>

                            </button>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-7 h-48">
                <svg viewBox="0 0 640 210" class="h-full w-full" role="img" aria-label="Revenue trend chart">
                    <defs>
                        <linearGradient id="ascend-fill" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0" stop-color="#2563eb" stop-opacity=".24"/>
                            <stop offset="1" stop-color="#2563eb" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M20 170 C 78 132, 92 142, 134 151 S 183 112, 221 127 S 267 86, 301 106 S 348 69, 377 90 S 414 47, 445 76 S 489 48, 516 63 S 563 34, 620 48 L620 190 L20 190 Z" fill="url(#ascend-fill)"/>
                    <path d="M20 170 C 78 132, 92 142, 134 151 S 183 112, 221 127 S 267 86, 301 106 S 348 69, 377 90 S 414 47, 445 76 S 489 48, 516 63 S 563 34, 620 48" fill="none" stroke="#2563eb" stroke-width="4" stroke-linecap="round"/>
                    <path d="M20 190 H620 M20 145 H620 M20 100 H620 M20 55 H620" stroke="#e2e8f0" stroke-dasharray="4 8"/>
                </svg>
            </div>

            <div class="mt-5 grid grid-cols-4 divide-x divide-slate-100 dark:divide-slate-800">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['label' => 'Total revenue', 'value' => '₦1,245,780'], ['label' => 'Net revenue', 'value' => '₦1,120,450'], ['label' => 'Avg. order', 'value' => '₦385.40'], ['label' => 'Orders', 'value' => '3,248']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="px-4 first:pl-0 last:pr-0">
                        <p class="text-[11px] text-slate-400"><?php echo e(__($stat['label'])); ?></p>
                        <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white"><?php echo e($stat['value']); ?></p>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </section>

        <!-- Sales Pipeline -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Sales pipeline')); ?></h2>
                    <p class="mt-1 text-sm text-slate-500"><?php echo e(__('56 open deals · ₦1.24M total value')); ?></p>
                </div>
                <a href="<?php echo e(route('portal.publishing.calendar')); ?>" wire:navigate class="text-sm font-semibold text-blue-600 hover:underline">
                    <?php echo e(__('View all deals')); ?>

                </a>
            </div>

            <div class="mt-6 grid grid-cols-5 gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                    ['name' => 'Prospecting', 'count' => 12, 'color' => 'text-blue-600', 'amount' => '₦287,400'],
                    ['name' => 'Qualified', 'count' => 8, 'color' => 'text-violet-600', 'amount' => '₦415,600'],
                    ['name' => 'Proposal', 'count' => 6, 'color' => 'text-amber-600', 'amount' => '₦261,300'],
                    ['name' => 'Negotiation', 'count' => 4, 'color' => 'text-teal-600', 'amount' => '₦198,750'],
                    ['name' => 'Closed won', 'count' => 7, 'color' => 'text-emerald-600', 'amount' => '₦556,730']
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="min-w-0 rounded-xl bg-slate-50 p-2.5 dark:bg-slate-800/70">
                        <p class="truncate text-[11px] font-semibold <?php echo e($stage['color']); ?>">
                            <?php echo e(__($stage['name'])); ?> <span class="text-slate-400">(<?php echo e($stage['count']); ?>)</span>
                        </p>
                        <p class="mt-1 text-[10px] text-slate-500"><?php echo e($stage['amount']); ?></p>
                        <div class="mt-3 space-y-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Northbridge Ltd', 'Brighton Labs', 'Omega Corp']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="rounded-lg border border-slate-200 bg-white p-2 shadow-xs transition hover:border-blue-400 dark:border-slate-700 dark:bg-slate-900">
                                    <p class="truncate text-[11px] font-semibold text-slate-700 dark:text-slate-200"><?php echo e($deal); ?></p>
                                    <p class="mt-1 text-[10px] text-slate-400">₦<?php echo e([45000, 78400, 92000][$index]); ?></p>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Operational Panels: Alerts, Recent Activity & Tasks -->
    <div class="grid gap-6 xl:grid-cols-3">
        <!-- Operational Alerts Panel -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Operational alerts')); ?></h2>
                <i class="fa-light fa-triangle-exclamation text-amber-500"></i>
            </div>
            <div class="mt-5 space-y-3">
                <button type="button" wire:click="openAlertModal('low_stock')" class="group flex w-full items-center gap-3 rounded-xl border border-slate-100 p-3 text-left transition hover:border-amber-200 hover:bg-amber-50/50 dark:border-slate-800 dark:hover:bg-amber-950/20">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-amber-500"></span>
                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700 group-hover:text-amber-700 dark:text-slate-200 dark:group-hover:text-amber-400">
                        <?php echo e(__('23 items are low in stock')); ?>

                    </span>
                    <i class="fa-light fa-chevron-right text-xs text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                </button>
                <button type="button" wire:click="openAlertModal('po_approval')" class="group flex w-full items-center gap-3 rounded-xl border border-slate-100 p-3 text-left transition hover:border-blue-200 hover:bg-blue-50/50 dark:border-slate-800 dark:hover:bg-blue-950/20">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>
                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700 group-hover:text-blue-700 dark:text-slate-200 dark:group-hover:text-blue-400">
                        <?php echo e(__('3 purchase orders need approval')); ?>

                    </span>
                    <i class="fa-light fa-chevron-right text-xs text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                </button>
                <button type="button" wire:click="openAlertModal('invoices_overdue')" class="group flex w-full items-center gap-3 rounded-xl border border-slate-100 p-3 text-left transition hover:border-rose-200 hover:bg-rose-50/50 dark:border-slate-800 dark:hover:bg-rose-950/20">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-rose-500"></span>
                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700 group-hover:text-rose-700 dark:text-slate-200 dark:group-hover:text-rose-400">
                        <?php echo e(__('2 invoices are past due')); ?>

                    </span>
                    <i class="fa-light fa-chevron-right text-xs text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </div>
            <button type="button" wire:click="openAlertModal('low_stock')" class="mt-5 inline-flex items-center text-sm font-semibold text-blue-600 hover:underline">
                <?php echo e(__('View all')); ?> <i class="fa-light fa-arrow-right ml-2"></i>
            </button>
        </section>

        <!-- Recent Activity Panel -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Recent activity')); ?></h2>
                <i class="fa-light fa-clock-rotate-left text-blue-500"></i>
            </div>
            <div class="mt-5 space-y-3">
                <button type="button" wire:click="openActivityModal('so_created')" class="group flex w-full items-center gap-3 rounded-xl border border-slate-100 p-3 text-left transition hover:border-blue-200 hover:bg-blue-50/50 dark:border-slate-800 dark:hover:bg-blue-950/20">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>
                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700 group-hover:text-blue-700 dark:text-slate-200 dark:group-hover:text-blue-400">
                        <?php echo e(__('Sales order SO-10458 was created')); ?>

                    </span>
                    <i class="fa-light fa-chevron-right text-xs text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                </button>
                <button type="button" wire:click="openActivityModal('inv_paid')" class="group flex w-full items-center gap-3 rounded-xl border border-slate-100 p-3 text-left transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-800 dark:hover:bg-emerald-950/20">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700 group-hover:text-emerald-700 dark:text-slate-200 dark:group-hover:text-emerald-400">
                        <?php echo e(__('Invoice INV-20431 was paid')); ?>

                    </span>
                    <i class="fa-light fa-chevron-right text-xs text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                </button>
                <button type="button" wire:click="openActivityModal('lead_added')" class="group flex w-full items-center gap-3 rounded-xl border border-slate-100 p-3 text-left transition hover:border-violet-200 hover:bg-violet-50/50 dark:border-slate-800 dark:hover:bg-violet-950/20">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-violet-500"></span>
                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700 group-hover:text-violet-700 dark:text-slate-200 dark:group-hover:text-violet-400">
                        <?php echo e(__('New lead added: Horizon Media')); ?>

                    </span>
                    <i class="fa-light fa-chevron-right text-xs text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </div>
            <a href="<?php echo e(route('admin-user-logs.index')); ?>" wire:navigate class="mt-5 inline-flex items-center text-sm font-semibold text-blue-600 hover:underline">
                <?php echo e(__('View all')); ?> <i class="fa-light fa-arrow-right ml-2"></i>
            </a>
        </section>

        <!-- Tasks Panel (Interactive Checkboxes) -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_16px_45px_-34px_rgba(15,23,42,0.35)] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white"><?php echo e(__('Tasks')); ?></h2>
                <i class="fa-light fa-list-check text-emerald-500"></i>
            </div>
            <div class="mt-5 space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div wire:click="toggleTask(<?php echo e($task['id']); ?>)" class="group flex cursor-pointer items-center gap-3 rounded-xl border border-slate-100 p-3 transition hover:border-emerald-200 hover:bg-emerald-50/40 dark:border-slate-800 dark:hover:bg-emerald-950/20">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border <?php echo e($task['completed'] ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 group-hover:border-emerald-400 dark:border-slate-700'); ?> transition-colors">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task['completed']): ?>
                                <i class="fa-light fa-check text-xs"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium <?php echo e($task['completed'] ? 'line-through text-slate-400' : 'text-slate-700 dark:text-slate-200'); ?>">
                                <?php echo e(__($task['title'])); ?>

                            </p>
                            <p class="text-[10px] text-slate-400"><?php echo e($task['due']); ?> · <span class="font-semibold text-blue-600"><?php echo e($task['category']); ?></span></p>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <a href="<?php echo e(route('portal.publishing.queue')); ?>" wire:navigate class="mt-5 inline-flex items-center text-sm font-semibold text-blue-600 hover:underline">
                <?php echo e(__('View all')); ?> <i class="fa-light fa-arrow-right ml-2"></i>
            </a>
        </section>
    </div>

    <!-- Livewire Interactive Slide-Over / Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeModal && $modalData): ?>
        <div class="fixed inset-0 z-[160] flex items-center justify-center p-4" x-cloak>
            <div wire:click="closeModal" class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>
            <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-950 dark:text-white">
                        <?php echo e(__($modalData['title'] ?? 'Details')); ?>

                    </h3>
                    <button type="button" wire:click="closeModal" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                        <i class="fa-light fa-xmark text-base"></i>
                    </button>
                </div>

                <div class="mt-5 space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeModal === 'alert' && isset($modalData['items'])): ?>
                        <div class="space-y-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $modalData['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 dark:bg-slate-800/70">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                            <?php echo e($item['name'] ?? $item['vendor'] ?? $item['client'] ?? ''); ?>

                                        </p>
                                        <p class="text-xs text-slate-400">
                                            <?php echo e($item['sku'] ?? $item['po'] ?? $item['inv'] ?? ''); ?>

                                        </p>
                                    </div>
                                    <span class="text-xs font-bold text-blue-600">
                                        <?php echo e(isset($item['stock']) ? 'Stock: '.$item['stock'] : ($item['amount'] ?? '')); ?>

                                    </span>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php elseif($activeModal === 'activity'): ?>
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/70">
                            <p class="text-xs font-semibold text-blue-600"><?php echo e($modalData['time'] ?? ''); ?></p>
                            <p class="mt-2 text-sm text-slate-700 dark:text-slate-200"><?php echo e($modalData['details'] ?? ''); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t pt-4 dark:border-slate-800">
                    <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800">
                        <?php echo e(__('Close')); ?>

                    </button>
                    <a href="<?php echo e(route('admin-user-logs.index')); ?>" wire:navigate class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        <?php echo e(__('Go to module')); ?>

                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes\app\default/resources/views/livewire/portal/ascend-dashboard.blade.php ENDPATH**/ ?>