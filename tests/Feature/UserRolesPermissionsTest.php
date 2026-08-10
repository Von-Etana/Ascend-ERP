<?php

use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;
use Modules\AppAscend\Livewire\AscendModuleViewer;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user hasPermission checks role permissions correctly', function () {
    $role = AdminRole::create([
        'name' => 'Finance Specialist',
        'slug' => 'finance-specialist',
        'permissions' => ['finance.view', 'finance.create'],
    ]);

    $user = User::factory()->create([
        'role_id' => $role->id,
        'is_super_admin' => false,
    ]);

    expect($user->hasPermission('finance.view'))->toBeTrue();
    expect($user->hasPermission('finance.create'))->toBeTrue();
    expect($user->hasPermission('users.edit'))->toBeFalse();
});

test('super admin bypasses permission checks', function () {
    $user = User::factory()->create([
        'is_super_admin' => true,
    ]);

    expect($user->isSuperAdmin())->toBeTrue();
    expect($user->hasPermission('any.random.permission'))->toBeTrue();
});

test('ascend module viewer creates backend user and assigns role', function () {
    $admin = User::factory()->create(['is_super_admin' => true]);
    $role = AdminRole::create([
        'name' => 'Inventory Auditor',
        'slug' => 'inventory-auditor',
        'permissions' => ['inventory.view'],
    ]);

    Livewire::actingAs($admin)
        ->test(AscendModuleViewer::class, ['moduleKey' => 'administration'])
        ->set('newUserForm.name', 'Auditor User')
        ->set('newUserForm.email', 'auditor@ascendsystems.ng')
        ->set('newUserForm.role_id', $role->id)
        ->call('createNewUser')
        ->assertSee('auditor@ascendsystems.ng');

    $newUser = User::where('email', 'auditor@ascendsystems.ng')->first();
    expect($newUser)->not()->toBeNull();
    expect($newUser->role_id)->toBe($role->id);
});

test('ascend module viewer toggles super admin status on user', function () {
    $admin = User::factory()->create(['is_super_admin' => true]);
    $targetUser = User::factory()->create(['is_super_admin' => false]);

    Livewire::actingAs($admin)
        ->test(AscendModuleViewer::class, ['moduleKey' => 'administration'])
        ->call('toggleUserSuperAdmin', $targetUser->id);

    expect($targetUser->fresh()->is_super_admin)->toBeTrue();
});
