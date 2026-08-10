<?php ($unreadNotifications = $notifications->filter(fn ($notification) => is_null($notification->read_at))->values()); ?>

<div class="border-b px-4 pt-3">
    <div class="flex items-center gap-5 text-sm font-semibold">
        <button type="button" class="relative pb-3 transition" :class="tab === 'unread' ? 'text-[var(--theme-accent)]' : 'text-slate-500'" x-on:click="tab = 'unread'">
            <?php echo e(__('Unread')); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                <span class="ml-2 inline-flex min-w-6 items-center justify-center rounded-md bg-rose-500 px-1.5 py-0.5 text-[11px] font-semibold text-white"><?php echo e($unreadCount); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span x-show="tab === 'unread'" class="absolute inset-x-0 bottom-0 h-0.5 rounded-full bg-[var(--theme-accent)]"></span>
        </button>
        <button type="button" class="relative pb-3 transition" :class="tab === 'all' ? 'text-[var(--theme-accent)]' : 'text-slate-500'" x-on:click="tab = 'all'">
            <?php echo e(__('All')); ?>

            <span x-show="tab === 'all'" class="absolute inset-x-0 bottom-0 h-0.5 rounded-full bg-[var(--theme-accent)]"></span>
        </button>
    </div>
</div>

<div class="max-h-[calc(100vh-14rem)] overflow-y-auto sm:max-h-[26rem]" x-show="tab === 'unread'">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="border-b px-4 py-4 last:border-b-0" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md bg-slate-900 px-2 py-0.5 text-[11px] font-semibold text-white dark:bg-slate-100 dark:text-slate-900"><?php echo e(__('New')); ?></span>
                        <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($notification->resolvedTitle()); ?></p>
                    </div>
                    <div class="text-sm leading-7" style="color: var(--theme-header-text-color);"><?php echo nl2br(e($notification->resolvedMessage())); ?></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notification->url): ?>
                        <a href="<?php echo e($notification->url); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-[var(--theme-accent)]" target="_blank">
                            <?php echo e(__('Open link')); ?>

                            <i class="fa-light fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(optional($notification->created_at)->diffForHumans()); ?></p>
                </div>

                <button type="button" class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border text-[var(--theme-accent)] transition hover:bg-[color:rgba(var(--theme-accent-rgb),0.08)]" style="border-color: color-mix(in srgb, var(--theme-accent) 18%, var(--theme-border-color));" onclick="return window.adminNotificationsPanel.markRead('<?php echo e($notification->actionKey); ?>');">
                    <i class="fa-light fa-check text-sm"></i>
                </button>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="px-4 py-10 text-center text-sm" style="color: var(--theme-muted-text-color);">
            <?php echo e(__('No unread notifications.')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<div class="max-h-[calc(100vh-14rem)] overflow-y-auto sm:max-h-[26rem]" x-show="tab === 'all'" x-cloak>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="border-b px-4 py-4 last:border-b-0" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 space-y-2">
                    <div class="flex items-center gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_null($notification->read_at)): ?>
                            <span class="inline-flex items-center rounded-md bg-slate-900 px-2 py-0.5 text-[11px] font-semibold text-white dark:bg-slate-100 dark:text-slate-900"><?php echo e(__('New')); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($notification->resolvedTitle()); ?></p>
                    </div>
                    <div class="text-sm leading-7" style="color: var(--theme-header-text-color);"><?php echo nl2br(e($notification->resolvedMessage())); ?></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notification->url): ?>
                        <a href="<?php echo e($notification->url); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-[var(--theme-accent)]" target="_blank">
                            <?php echo e(__('Open link')); ?>

                            <i class="fa-light fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs" style="color: var(--theme-muted-text-color);"><?php echo e(optional($notification->created_at)->diffForHumans()); ?></p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_null($notification->read_at)): ?>
                    <button type="button" class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border text-[var(--theme-accent)] transition hover:bg-[color:rgba(var(--theme-accent-rgb),0.08)]" style="border-color: color-mix(in srgb, var(--theme-accent) 18%, var(--theme-border-color));" onclick="return window.adminNotificationsPanel.markRead('<?php echo e($notification->actionKey); ?>');">
                        <i class="fa-light fa-check text-sm"></i>
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="px-4 py-10 text-center text-sm" style="color: var(--theme-muted-text-color);">
            <?php echo e(__('No notifications found.')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AdminNotifications\Providers/../Resources/views/partials/panel-items.blade.php ENDPATH**/ ?>