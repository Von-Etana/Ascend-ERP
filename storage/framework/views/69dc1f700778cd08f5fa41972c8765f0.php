<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
    <?php ($user = auth()->user()); ?>
    <?php ($isPortal = request()->routeIs('portal.*')); ?>
    <?php ($hasBillingAccess = false); ?>
    <?php ($plan = $user?->plan); ?>
    <?php ($creditSummary = $user?->creditSummary() ?? ['limit' => null, 'used' => 0, 'remaining' => null, 'unlimited' => true]); ?>
    <?php ($creditLimit = is_numeric($creditSummary['limit'] ?? null) ? max(0, (int) $creditSummary['limit']) : null); ?>
    <?php ($creditsUsed = max(0, (int) ($creditSummary['used'] ?? 0))); ?>
    <?php ($creditsRemaining = $creditSummary['remaining'] ?? null); ?>
    <?php ($creditsUsedPercent = $creditLimit && $creditLimit > 0 ? min(100, (int) round(($creditsUsed / $creditLimit) * 100)) : null); ?>

    <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu','data' => ['align' => 'right','width' => 'auto','class' => 'min-w-[16rem]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => 'auto','class' => 'min-w-[16rem]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('trigger', null, []); ?> 
            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-[0.85rem] border border-slate-200/80 shadow-[0_12px_24px_-20px_rgba(15,23,42,0.22)] transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/80"
                style="background-color: rgba(var(--theme-header-surface-rgb), 0.9); border-color: var(--theme-shell-border-color);"
                aria-label="<?php echo e(__('User menu')); ?>"
            >
                <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.avatar','data' => ['src' => $user?->avatar_url,'name' => $user?->name,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user?->avatar_url),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user?->name),'size' => 'sm']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $attributes = $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $component = $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>
            </button>
         <?php $__env->endSlot(); ?>

        <div class="px-3 py-2">
            <p class="max-w-[13rem] truncate text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($user?->name); ?></p>
            <p class="mt-0.5 max-w-[13rem] truncate text-xs" style="color: var(--theme-muted-text-color);"><?php echo e($user?->email); ?></p>
        </div>

        <div class="my-1 h-px" style="background-color: color-mix(in srgb, var(--theme-border-color) 65%, transparent);"></div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPortal): ?>
            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('portal.profile'),'icon' => 'fa-light fa-user-pen','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.profile')),'icon' => 'fa-light fa-user-pen','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php echo e(__('Profile')); ?>

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

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasBillingAccess): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(saas_features_enabled()): ?>
                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('portal.packages'),'icon' => 'fa-light fa-box-open','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.packages')),'icon' => 'fa-light fa-box-open','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php echo e(__('Packages')); ?>

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

                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('portal.credits'),'icon' => 'fa-light fa-bolt','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.credits')),'icon' => 'fa-light fa-bolt','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php echo e(__('Credits')); ?>

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

                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('portal.billing'),'icon' => 'fa-light fa-credit-card','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.billing')),'icon' => 'fa-light fa-credit-card','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php echo e(__('Billing')); ?>

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

                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('portal.invoices'),'icon' => 'fa-light fa-receipt','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.invoices')),'icon' => 'fa-light fa-receipt','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php echo e(__('Invoices')); ?>

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

            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('portal.activity'),'icon' => 'fa-light fa-clipboard-list-check','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.activity')),'icon' => 'fa-light fa-clipboard-list-check','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php echo e(__('Activity Log')); ?>

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

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user?->canUsePlanFeature('ai_studio')): ?>
                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('portal.ai-studio.settings'),'icon' => 'fa-light fa-sparkles','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('portal.ai-studio.settings')),'icon' => 'fa-light fa-sparkles','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <?php echo e(__('AI Settings')); ?>

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
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('admin-users.edit', $user),'icon' => 'fa-light fa-user-pen','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin-users.edit', $user)),'icon' => 'fa-light fa-user-pen','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php echo e(__('Profile')); ?>

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

            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('admin-user-logs.index'),'icon' => 'fa-light fa-clipboard-list-check','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin-user-logs.index')),'icon' => 'fa-light fa-clipboard-list-check','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php echo e(__('User Logs')); ?>

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

            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['href' => route('settings.general'),'icon' => 'fa-light fa-gear','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.general')),'icon' => 'fa-light fa-gear','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php echo e(__('Settings')); ?>

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

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(false): ?>
        <div class="mx-1.5 my-2 rounded-[1rem] border px-3 py-3.5" style="border-color: color-mix(in srgb, var(--theme-border-color) 72%, transparent 28%); background: linear-gradient(180deg, color-mix(in srgb, var(--theme-accent,#2563eb) 7%, var(--theme-surface-base) 93%) 0%, var(--theme-surface-base) 100%);">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($plan?->name ?? __('No plan assigned')); ?></p>
                    <p class="mt-1 text-xs font-medium <?php echo e($creditSummary['unlimited'] ? 'text-emerald-500' : 'text-slate-500 dark:text-slate-400'); ?>">
                        <?php echo e($creditSummary['unlimited']
                            ? __('Unlimited')
                            : ($creditsRemaining !== null
                                ? __(':credits credits left', ['credits' => number_format((int) $creditsRemaining)])
                                : __('Credits unavailable'))); ?>

                    </p>
                </div>

                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['href' => ''.e(route('portal.packages')).'','variant' => 'primary','size' => 'sm','class' => 'shrink-0','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('portal.packages')).'','variant' => 'primary','size' => 'sm','class' => 'shrink-0','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <?php echo e($planActionLabel); ?>

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

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $creditSummary['unlimited'] && $creditLimit): ?>
                <div class="mt-3 space-y-2">
                    <div class="flex items-center justify-between gap-3 text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);">
                        <span><?php echo e(__('Credits used')); ?></span>
                        <span><?php echo e($creditsUsedPercent); ?>%</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-200/70 dark:bg-slate-800">
                        <div class="h-full rounded-full" style="width: <?php echo e($creditsUsedPercent); ?>%; background: linear-gradient(90deg, var(--theme-accent,#2563eb) 0%, color-mix(in srgb, var(--theme-accent,#2563eb) 72%, #8b5cf6 28%) 100%);"></div>
                    </div>
                    <p class="text-xs leading-5" style="color: var(--theme-muted-text-color);">
                        <?php echo e(__('You have :credits credits left in this quota period.', ['credits' => number_format((int) $creditsRemaining)])); ?>

                    </p>
                </div>
            <?php elseif($creditSummary['unlimited']): ?>
                <p class="mt-3 text-xs leading-5" style="color: var(--theme-muted-text-color);">
                    <?php echo e(__('This plan has no credit cap for the current period.')); ?>

                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="my-1 h-px" style="background-color: color-mix(in srgb, var(--theme-border-color) 65%, transparent);"></div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['type' => 'submit','icon' => 'fa-light fa-arrow-right-from-bracket','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','icon' => 'fa-light fa-arrow-right-from-bracket','class' => 'w-full']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php echo e(__('Logout')); ?>

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
        </form>
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
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppProfile\Providers/../Resources/views/partials/header-user-menu.blade.php ENDPATH**/ ?>