<?php if (isset($component)) { $__componentOriginalce574f703b9b7329d58617771064dcb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce574f703b9b7329d58617771064dcb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.surface-card','data' => ['class' => 'space-y-7','xData' => '{
        typingTimer: null,
        typePrompt(content) {
            const value = String(content ?? \'\');
            const textarea = this.$refs.promptTextarea;

            if (!textarea) {
                return;
            }

            if (this.typingTimer) {
                clearInterval(this.typingTimer);
                this.typingTimer = null;
            }

            textarea.focus();
            textarea.value = \'\';
            textarea.dispatchEvent(new Event(\'input\', { bubbles: true }));

            if (value.length === 0) {
                return;
            }

            let index = 0;
            const step = value.length > 180 ? 3 : (value.length > 90 ? 2 : 1);

            this.typingTimer = setInterval(() => {
                index = Math.min(value.length, index + step);
                textarea.value = value.slice(0, index);
                textarea.dispatchEvent(new Event(\'input\', { bubbles: true }));
                textarea.setSelectionRange(index, index);

                if (index >= value.length) {
                    clearInterval(this.typingTimer);
                    this.typingTimer = null;
                }
            }, 16);
        },
    }','xOn:captionTemplateSelected.window' => 'typePrompt($event.detail.content)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.surface-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-7','x-data' => '{
        typingTimer: null,
        typePrompt(content) {
            const value = String(content ?? \'\');
            const textarea = this.$refs.promptTextarea;

            if (!textarea) {
                return;
            }

            if (this.typingTimer) {
                clearInterval(this.typingTimer);
                this.typingTimer = null;
            }

            textarea.focus();
            textarea.value = \'\';
            textarea.dispatchEvent(new Event(\'input\', { bubbles: true }));

            if (value.length === 0) {
                return;
            }

            let index = 0;
            const step = value.length > 180 ? 3 : (value.length > 90 ? 2 : 1);

            this.typingTimer = setInterval(() => {
                index = Math.min(value.length, index + step);
                textarea.value = value.slice(0, index);
                textarea.dispatchEvent(new Event(\'input\', { bubbles: true }));
                textarea.setSelectionRange(index, index);

                if (index >= value.length) {
                    clearInterval(this.typingTimer);
                    this.typingTimer = null;
                }
            }, 16);
        },
    }','x-on:caption-template-selected.window' => 'typePrompt($event.detail.content)']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
        <div>
            <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Prompt builder')); ?></p>
            <p class="mt-1 max-w-2xl text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Start from a reusable template or write your own prompt, then generate platform-ready caption variations from that single instruction.')); ?></p>
        </div>
        <div class="rounded-full border px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.16em]" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent); color: var(--theme-muted-text-color);">
            <?php echo e(__('Template-driven workflow')); ?>

        </div>
    </div>

    <div class="mt-4">
        <?php if (isset($component)) { $__componentOriginal62d1193389a71cd99ff302a00abbf991 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62d1193389a71cd99ff302a00abbf991 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.textarea','data' => ['wire:model.defer' => 'promptTemplate','label' => __('Prompt'),'error' => $errors->first('promptTemplate'),'rows' => '7','placeholder' => __('Example: Write an Instagram caption for a product launch targeting busy professionals. Keep it concise, confident, and CTA-driven.'),'xRef' => 'promptTextarea','class' => 'space-y-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'promptTemplate','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Prompt')),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('promptTemplate')),'rows' => '7','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Example: Write an Instagram caption for a product launch targeting busy professionals. Keep it concise, confident, and CTA-driven.')),'x-ref' => 'promptTextarea','class' => 'space-y-1.5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($promptTemplate); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $attributes = $__attributesOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__attributesOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $component = $__componentOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__componentOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
    </div>

    <div class="grid gap-x-4 gap-y-6 mt-3 md:grid-cols-2 xl:grid-cols-3">
        <?php if (isset($component)) { $__componentOriginal141b9206ef5f6a5ed649d98abace57b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal141b9206ef5f6a5ed649d98abace57b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ai.language-field','data' => ['wire:model.defer' => 'language','name' => 'language','value' => $language,'label' => __('Language'),'preferred' => ['vi', 'en'],'class' => 'space-y-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ai.language-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'language','name' => 'language','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($language),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Language')),'preferred' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['vi', 'en']),'class' => 'space-y-1.5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal141b9206ef5f6a5ed649d98abace57b2)): ?>
<?php $attributes = $__attributesOriginal141b9206ef5f6a5ed649d98abace57b2; ?>
<?php unset($__attributesOriginal141b9206ef5f6a5ed649d98abace57b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal141b9206ef5f6a5ed649d98abace57b2)): ?>
<?php $component = $__componentOriginal141b9206ef5f6a5ed649d98abace57b2; ?>
<?php unset($__componentOriginal141b9206ef5f6a5ed649d98abace57b2); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginalb528816427b48a0b8c21b6974abb8798 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb528816427b48a0b8c21b6974abb8798 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ai.tone-field','data' => ['wire:model.defer' => 'tone','name' => 'tone','value' => $tone,'label' => __('Tone Of Voice'),'options' => $toneOptions,'class' => 'space-y-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ai.tone-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'tone','name' => 'tone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Tone Of Voice')),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($toneOptions),'class' => 'space-y-1.5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb528816427b48a0b8c21b6974abb8798)): ?>
<?php $attributes = $__attributesOriginalb528816427b48a0b8c21b6974abb8798; ?>
<?php unset($__attributesOriginalb528816427b48a0b8c21b6974abb8798); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb528816427b48a0b8c21b6974abb8798)): ?>
<?php $component = $__componentOriginalb528816427b48a0b8c21b6974abb8798; ?>
<?php unset($__componentOriginalb528816427b48a0b8c21b6974abb8798); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.defer' => 'creativity','label' => __('Creativity'),'class' => 'space-y-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'creativity','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Creativity')),'class' => 'space-y-1.5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $creativityOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <option value="<?php echo e($option['value']); ?>"><?php echo e($option['label']); ?></option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.defer' => 'hashtagMode','label' => __('Add hashtags'),'class' => 'space-y-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'hashtagMode','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Add hashtags')),'class' => 'space-y-1.5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $hashtagOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <option value="<?php echo e($option['value']); ?>"><?php echo e($option['label']); ?></option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.input','data' => ['wire:model.defer' => 'approximateWords','type' => 'number','min' => '40','max' => '320','step' => '10','label' => __('Approximate words'),'class' => 'space-y-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'approximateWords','type' => 'number','min' => '40','max' => '320','step' => '10','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Approximate words')),'class' => 'space-y-1.5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.input','data' => ['wire:model.defer' => 'totalResults','type' => 'number','min' => '1','max' => '8','step' => '1','label' => __('Total results'),'class' => 'space-y-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'totalResults','type' => 'number','min' => '1','max' => '8','step' => '1','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Total results')),'class' => 'space-y-1.5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
    </div>

    <div class="flex flex-wrap items-center gap-3 pt-5">
        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','wire:click' => 'generate','wire:loading.attr' => 'disabled','wire:target' => 'generate','disabled' => !($creditPreview['enough'] ?? true)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'generate','wire:loading.attr' => 'disabled','wire:target' => 'generate','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!($creditPreview['enough'] ?? true))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="fa-light fa-wand-magic-sparkles"></i>
            <span wire:loading.remove wire:target="generate"><?php echo e(__('Generate content')); ?></span>
            <span wire:loading wire:target="generate"><?php echo e(__('Generating...')); ?></span>
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

        <div class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-sm" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent); color: var(--theme-muted-text-color);">
            <i class="fa-light fa-coins text-xs" style="color: var(--theme-accent);"></i>
            <span><?php echo e(__(':credits credits', ['credits' => $creditPreview['amount'] ?? 0])); ?></span>
            <span>&bull;</span>
            <span><?php echo e(($creditPreview['unlimited'] ?? false) ? __('Unlimited plan') : __(':credits left', ['credits' => $creditPreview['remaining'] ?? 0])); ?></span>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTemplate): ?>
            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-sm" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent); color: var(--theme-muted-text-color);">
                <span class="font-medium"><?php echo e(__('Template category:')); ?></span>
                <span style="color: var(--theme-header-text-color);"><?php echo e($selectedTemplate->category?->name ?: __('Template')); ?></span>
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!($creditPreview['enough'] ?? true)): ?>
        <p class="pt-1 text-sm font-medium" style="color: var(--theme-danger-color);"><?php echo e(__('Not enough credits remaining for this action.')); ?></p>
        <?php echo $__env->make(theme_view('partials.credit-topup-cta', 'app'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div wire:loading.flex wire:target="generate" class="mt-4 items-center gap-3 rounded-[1rem] border px-4 py-3 text-sm" style="border-color: rgba(var(--theme-accent-rgb), 0.16); background-color: rgba(var(--theme-accent-rgb), 0.06); color: var(--theme-muted-text-color);">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background-color: rgba(var(--theme-accent-rgb), 0.12); color: var(--theme-accent);">
            <i class="fa-light fa-loader animate-spin"></i>
        </span>
        <div>
            <p class="font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Generating content')); ?></p>
            <p class="mt-1"><?php echo e(__('AI is combining your prompt and settings into multiple content variations.')); ?></p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tags): ?>
        <div class="pt-2 flex flex-wrap gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <span class="rounded-full px-2.5 py-1 text-[11px] font-medium" style="background-color: rgba(var(--theme-accent-rgb), 0.1); color: var(--theme-accent);"><?php echo e($tag); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $attributes = $__attributesOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__attributesOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce574f703b9b7329d58617771064dcb7)): ?>
<?php $component = $__componentOriginalce574f703b9b7329d58617771064dcb7; ?>
<?php unset($__componentOriginalce574f703b9b7329d58617771064dcb7); ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppAIContent\Providers/../Resources/views/partials/builder-panel.blade.php ENDPATH**/ ?>