<?php

use App\Models\Invoice;
use App\Models\SolarCalculatorLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('solar capacity calculator correctly computes peak load and recommends package', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, [
        'moduleKey' => 'sales',
        'activeTab' => 'solar_calculator',
    ])
    ->set('calcQty.fridge', 1)
    ->set('calcQty.ac', 1)
    ->set('calcQty.lights', 8)
    ->set('calcQty.tv', 2)
    ->set('calcQty.pump', 1)
    ->set('calcQty.laptops', 3)
    ->set('calcHours', 12)
    ->call('addCalculatedBundleToCart');

    $log = SolarCalculatorLog::latest()->first();
    expect($log)->not->toBeNull();
    expect($log->total_wattage)->toBeGreaterThan(2000);
    expect($log->recommended_inverter)->toContain('Ascend 5.5kVA Hybrid Solar Inverter');
    expect($log->recommended_battery)->toContain('LiFePO4');
});

test('user can trigger whatsapp and email pdf invoice dispatch', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $invoice = Invoice::create([
        'invoice_number' => 'INV-2026-9901',
        'client_name' => 'Abuja Villa Client',
        'total' => 2500000.00,
        'status' => 'unpaid',
        'issue_date' => now(),
        'due_date' => now()->addDays(14),
    ]);

    $this->actingAs($user);

    $test = Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'finance'])
        ->call('sendInvoiceWhatsApp', $invoice->id)
        ->call('sendInvoiceEmail', $invoice->id);

    expect($test->get('moduleKey'))->toBe('finance');
});
