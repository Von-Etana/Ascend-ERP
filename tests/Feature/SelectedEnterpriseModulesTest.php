<?php

use App\Models\InstallationDispatch;
use App\Models\WarrantySerial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('manager can schedule field installation dispatch and complete sign-off', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'tasks'])
        ->set('dispatchForm.client_name', 'Maitama Solar Villa')
        ->set('dispatchForm.location_address', 'Plot 402 Maitama, Abuja')
        ->set('dispatchForm.engineer_name', 'Engr. Babatunde Adeleke')
        ->set('dispatchForm.system_type', 'Ascend 5.5kVA Hybrid Inverter + 10.2kWh Battery')
        ->call('createInstallationDispatch');

    $dispatch = InstallationDispatch::where('client_name', 'Maitama Solar Villa')->first();
    expect($dispatch)->not->toBeNull();
    expect($dispatch->engineer_name)->toBe('Engr. Babatunde Adeleke');
    expect($dispatch->status)->toBe('scheduled');

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'tasks'])
        ->call('updateDispatchStatus', $dispatch->id, 'completed');

    $dispatch->refresh();
    expect($dispatch->status)->toBe('completed');
});

test('manager can register 5-year warranty serial and send whatsapp maintenance alert', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'inventory'])
        ->set('warrantyForm.serial_number', 'SN-INV-2026-99001')
        ->set('warrantyForm.product_name', 'Ascend 5.5kVA Hybrid Inverter')
        ->set('warrantyForm.client_name', 'Sterling Finance Corp')
        ->set('warrantyForm.client_phone', '+234 808 222 3344')
        ->call('registerWarrantySerial');

    $warranty = WarrantySerial::where('serial_number', 'SN-INV-2026-99001')->first();
    expect($warranty)->not->toBeNull();
    expect($warranty->client_name)->toBe('Sterling Finance Corp');
    expect($warranty->status)->toBe('active');

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'inventory'])
        ->call('triggerWhatsAppMaintenanceAlert', $warranty->id);

    $warranty->refresh();
    expect($warranty->maintenance_alerts_sent)->toBe(1);
});

test('executive ai cash flow forecast view renders properly in finance module', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, [
        'moduleKey' => 'finance',
        'activeTab' => 'ai_forecasting',
    ])
    ->assertSee('Executive Revenue Projection & Cash Runway Analytics')
    ->assertSee('₦48,500,000.00');
});
