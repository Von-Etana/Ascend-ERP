<?php
    $embedOptions = app(\Modules\AdminSettings\Support\OptionStore::class);
    $embedEnabled = (string) $embedOptions->get('embed_code_status', '0') === '1';
    $embedCode = trim((string) $embedOptions->get('embed_code_app_body_end', ''));
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($embedEnabled && $embedCode !== ''): ?>
    <?php echo $embedCode; ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes\app\default/resources/views/partials/embed-code-body.blade.php ENDPATH**/ ?>