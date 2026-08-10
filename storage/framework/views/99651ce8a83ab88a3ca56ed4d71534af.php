<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
    <?php ($payload = app(\Modules\AdminNotifications\Services\NotificationService::class)->panelPayload((int) auth()->id(), 20)); ?>

    <div class="relative" x-data="{ open: false, tab: 'unread' }" x-on:keydown.escape.window="open = false">
        <button
            type="button"
            class="relative inline-flex h-11 w-11 items-center justify-center rounded-[0.75rem] border border-slate-200/80 text-slate-500 transition hover:bg-slate-50 hover:text-slate-950 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800/80 dark:hover:text-white"
            style="background-color: rgba(var(--theme-header-surface-rgb), 0.9); border-color: var(--theme-shell-border-color);"
            x-on:click="open = ! open"
            aria-label="<?php echo e(__('Notifications')); ?>"
        >
            <i
                id="admin-notifications-bell-icon"
                class="fa-light fa-bell text-[15px] <?php if($payload['unreadCount'] > 0): ?> admin-notifications-bell-ring <?php endif; ?>"
            ></i>
            <span
                id="admin-notifications-badge"
                class="absolute -right-1 -top-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[11px] font-semibold leading-none text-white"
                <?php if($payload['unreadCount'] < 1): ?> style="display: none;" <?php endif; ?>
            >
                <span id="admin-notifications-badge-count"><?php echo e($payload['unreadCount']); ?></span>
            </span>
        </button>

        <div
            x-cloak
            x-show="open"
            x-transition.opacity.scale.95
            x-on:click.outside="open = false"
            class="fixed inset-x-3 top-16 z-[90] overflow-hidden rounded-[1rem] border shadow-[0_32px_80px_-34px_rgba(15,23,42,0.32)] sm:absolute sm:right-0 sm:left-auto sm:top-full sm:z-40 sm:mt-3 sm:w-[25rem]"
            style="border-color: color-mix(in srgb, var(--theme-border-color) 58%, transparent); background-color: var(--theme-surface-overlay);"
        >
            <div class="flex items-center justify-between border-b px-4 py-4" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
                <h3 class="text-[1.05rem] font-semibold tracking-[-0.02em] text-slate-950 dark:text-white"><?php echo e(__('Notification')); ?></h3>
                <button type="button" class="text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200" x-on:click="open = false">
                    <i class="fa-light fa-xmark text-lg"></i>
                </button>
            </div>

            <div id="admin-notifications-panel-list">
                <?php echo $__env->make('adminnotifications::partials.panel-items', [
                    'notifications' => $payload['notifications'],
                    'unreadCount' => $payload['unreadCount'],
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <div class="grid grid-cols-2 gap-3 border-t bg-slate-50/70 px-4 py-4 dark:bg-slate-900/40" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','onclick' => 'return window.adminNotificationsPanel.markAllRead();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','onclick' => 'return window.adminNotificationsPanel.markAllRead();']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <?php echo e(__('Mark all as read')); ?>

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
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','onclick' => 'return window.adminNotificationsPanel.archiveAll();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','onclick' => 'return window.adminNotificationsPanel.archiveAll();']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <?php echo e(__('Archive all')); ?>

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
        </div>
    </div>

    <?php if (! $__env->hasRenderedOnce('d44e8ef2-1afd-4061-814a-52c8124fba41')): $__env->markAsRenderedOnce('d44e8ef2-1afd-4061-814a-52c8124fba41'); ?>
        <script>
            window.adminNotificationsPanel = {
                updateBadge(unreadCount) {
                    const badge = document.getElementById('admin-notifications-badge');
                    const badgeCount = document.getElementById('admin-notifications-badge-count');
                    const bellIcon = document.getElementById('admin-notifications-bell-icon');

                    if (!badge || !badgeCount || !bellIcon) {
                        return;
                    }

                    badgeCount.textContent = unreadCount;
                    badge.style.display = unreadCount > 0 ? 'inline-flex' : 'none';
                    bellIcon.classList.toggle('admin-notifications-bell-ring', unreadCount > 0);
                },
                updatePanel(html) {
                    const panel = document.getElementById('admin-notifications-panel-list');

                    if (panel) {
                        panel.innerHTML = html;

                        if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                            window.Alpine.initTree(panel);
                        }
                    }
                },
                async request(url, method = 'GET') {
                    const response = await fetch(url, {
                        method,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Notification panel request failed.');
                    }

                    const result = await response.json();
                    this.updateBadge(result.unread_count ?? 0);
                    this.updatePanel(result.html ?? '');

                    return false;
                },
                async refresh() {
                    return this.request('<?php echo e(route('admin-notifications.panel')); ?>', 'GET');
                },
                async markRead(id) {
                    return this.request('<?php echo e(route('admin-notifications.read', ['notificationKey' => '__NOTIFICATION_KEY__'])); ?>'.replace('__NOTIFICATION_KEY__', encodeURIComponent(id)), 'POST');
                },
                async markAllRead() {
                    return this.request('<?php echo e(route('admin-notifications.read-all')); ?>', 'POST');
                },
                async archiveAll() {
                    return this.request('<?php echo e(route('admin-notifications.archive-all')); ?>', 'POST');
                },
            };
        </script>
        <style>
            @keyframes adminNotificationsBellRing {
                0%, 100% { transform: rotate(0deg); }
                8% { transform: rotate(16deg); }
                16% { transform: rotate(-14deg); }
                24% { transform: rotate(12deg); }
                32% { transform: rotate(-10deg); }
                40% { transform: rotate(6deg); }
                48% { transform: rotate(-4deg); }
                56% { transform: rotate(0deg); }
            }

            .admin-notifications-bell-ring {
                transform-origin: top center;
                animation: adminNotificationsBellRing 2.2s ease-in-out infinite;
            }
        </style>
    <?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AdminNotifications\Providers/../Resources/views/partials/header-item-admin.blade.php ENDPATH**/ ?>