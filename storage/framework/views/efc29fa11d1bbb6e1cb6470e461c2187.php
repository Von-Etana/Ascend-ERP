<div <?php echo e($attributes->merge(['style' => 'border-color: var(--theme-border-color); background-color: var(--theme-surface-base);'])->class('overflow-visible rounded-[1rem] border shadow-[0_18px_44px_-36px_rgba(15,23,42,0.12)]')); ?>>
    <div class="overflow-x-auto overflow-y-visible">
        <table class="min-w-full border-separate border-spacing-0">
            <?php echo e($slot); ?>

        </table>
    </div>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/table.blade.php ENDPATH**/ ?>