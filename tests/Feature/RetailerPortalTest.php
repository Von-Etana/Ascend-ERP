<?php

use App\Models\InventoryProduct;
use App\Models\Invoice;
use App\Models\RetailerOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('retailer user is redirected to retailer portal upon login', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'retailer'], ['name' => 'Retailer', 'permissions' => ['retailer.*']]);
    $user = User::factory()->create(['role_id' => $role->id, 'email' => 'solarretailer@ascendsystems.ng']);

    Livewire::test(\App\Livewire\Auth\LoginPage::class)
        ->set('identifier', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect('/portal/ascend/retailer');

    $this->assertAuthenticatedAs($user);
});

test('retailer can add solar items to cart and submit pending approval order', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'retailer'], ['name' => 'Retailer', 'permissions' => ['retailer.*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $product = InventoryProduct::create([
        'sku' => 'TEST-SLR-001',
        'name' => 'Ascend 5.5kVA Hybrid Inverter',
        'category' => 'Inverters',
        'unit_price' => 580000.00,
        'wholesale_price' => 495000.00,
        'cost_price' => 400000.00,
        'stock_quantity' => 20,
        'is_b2b_visible' => true,
    ]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'retailer'])
        ->call('addToRetailerCart', $product->id, 2)
        ->call('submitRetailerOrder', 'pending_approval');

    $order = RetailerOrder::where('retailer_user_id', $user->id)->first();
    expect($order)->not->toBeNull();
    expect($order->status)->toBe('pending_approval');
    expect((float)$order->subtotal)->toBe(990000.00); // 495000 * 2
});

test('retailer can submit instant invoice order which creates invoice immediately', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'retailer'], ['name' => 'Retailer', 'permissions' => ['retailer.*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $product = InventoryProduct::create([
        'sku' => 'TEST-BAT-002',
        'name' => 'Ascend Lithium Battery 10kWh',
        'category' => 'Batteries',
        'unit_price' => 1450000.00,
        'wholesale_price' => 1280000.00,
        'cost_price' => 1000000.00,
        'stock_quantity' => 10,
        'is_b2b_visible' => true,
    ]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'retailer'])
        ->call('addToRetailerCart', $product->id, 1)
        ->call('submitRetailerOrder', 'instant_invoice');

    $order = RetailerOrder::where('retailer_user_id', $user->id)->first();
    expect($order)->not->toBeNull();
    expect($order->status)->toBe('invoiced');
    expect($order->invoice_id)->not->toBeNull();

    $invoice = Invoice::find($order->invoice_id);
    expect($invoice)->not->toBeNull();
    expect($invoice->invoice_number)->toContain('INV-B2B');
});

test('sales rep can approve pending retailer order and generate invoice', function () {
    $retailerRole = AdminRole::firstOrCreate(['slug' => 'retailer'], ['name' => 'Retailer', 'permissions' => ['retailer.*']]);
    $retailerUser = User::factory()->create(['role_id' => $retailerRole->id]);

    $order = RetailerOrder::create([
        'order_number' => 'B2B-ORD-TEST-001',
        'retailer_user_id' => $retailerUser->id,
        'retailer_company_name' => 'Kano Solar Distributors',
        'retailer_email' => 'kano@solardist.ng',
        'items' => [
            ['sku' => 'SLR-PNL-550W', 'name' => 'Solar Panel 550W', 'quantity' => 10, 'unit_price' => 96000.00, 'line_total' => 960000.00]
        ],
        'subtotal' => 960000.00,
        'tax' => 72000.00,
        'total_amount' => 1032000.00,
        'order_type' => 'pending_approval',
        'status' => 'pending_approval',
    ]);

    $salesRepRole = AdminRole::firstOrCreate(['slug' => 'sales-rep'], ['name' => 'Sales Rep', 'permissions' => ['sales.*']]);
    $salesRepUser = User::factory()->create(['role_id' => $salesRepRole->id]);

    $this->actingAs($salesRepUser);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'sales'])
        ->call('approveRetailerOrder', $order->id);

    $order->refresh();
    expect($order->status)->toBe('approved');
    expect($order->invoice_id)->not->toBeNull();
});
