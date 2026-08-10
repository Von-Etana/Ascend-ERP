<?php $__env->startComponent(theme_view('layouts.auth', 'guest'), ['title' => __('Log in')]); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split(\App\Livewire\Auth\LoginPage::class);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-242854908-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes\guest\default/resources/views/auth/login.blade.php ENDPATH**/ ?>