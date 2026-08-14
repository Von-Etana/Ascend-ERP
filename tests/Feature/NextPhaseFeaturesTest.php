<?php

use App\Models\InventoryProduct;
use App\Models\Invoice;
use App\Models\RetailerOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('tier 1 platinum distributor receives 20% discount on wholesale price', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'retailer'], ['name' => 'Retailer', 'permissions' => ['retailer.*']]);
    $user = User::factory()->create([
        'role_id' => $role->id,
        'distributor_tier' => 'tier1_platinum',
        'credit_limit' => 5000000.00,
        'credit_balance' => 3500000.00,
    ]);

    $product = InventoryProduct::create([
        'sku' => 'SLR-INV-55KW',
        'name' => 'Ascend 5.5kVA Hybrid Inverter',
        'category' => 'Inverters',
        'unit_price' => 580000.00,
        'wholesale_price' => 500000.00,
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
    // Base wholesale price 500,000 * 0.80 (20% off) = 400,000 * 2 units = 800,000
    expect((float)$order->subtotal)->toBe(800000.00);
});

test('retailer can pay order online via paystack reference', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'retailer'], ['name' => 'Retailer', 'permissions' => ['retailer.*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $order = RetailerOrder::create([
        'order_number' => 'B2B-ORD-PST-001',
        'retailer_user_id' => $user->id,
        'retailer_company_name' => 'Port Harcourt Solar Hub',
        'retailer_email' => 'ph@solarhub.ng',
        'items' => [['sku' => 'SLR-PNL-550W', 'quantity' => 5, 'unit_price' => 90000.00, 'line_total' => 450000.00]],
        'subtotal' => 450000.00,
        'tax' => 33750.00,
        'total_amount' => 483750.00,
        'order_type' => 'instant_invoice',
        'status' => 'invoiced',
        'paystack_status' => 'unpaid',
    ]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'retailer'])
        ->call('payRetailerOrderViaPaystack', $order->id);

    $order->refresh();
    expect($order->paystack_status)->toBe('paid');
    expect($order->paystack_reference)->toContain('PST-B2B');
});

test('warehouse staff can scan barcode to verify sku and dispatch order', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $staff = User::factory()->create(['role_id' => $role->id]);

    $product = InventoryProduct::create([
        'sku' => 'SLR-BAT-10KW',
        'name' => 'Ascend 10.2kWh LiFePO4 Battery',
        'category' => 'Batteries',
        'unit_price' => 1450000.00,
        'wholesale_price' => 1280000.00,
        'stock_quantity' => 15,
        'is_b2b_visible' => true,
    ]);

    $order = RetailerOrder::create([
        'order_number' => 'B2B-ORD-WH-002',
        'retailer_user_id' => $staff->id,
        'retailer_company_name' => 'Kano Energy Ltd',
        'retailer_email' => 'kano@energy.ng',
        'items' => [
            ['product_id' => $product->id, 'sku' => 'SLR-BAT-10KW', 'name' => 'Ascend 10.2kWh LiFePO4 Battery', 'quantity' => 2, 'unit_price' => 1280000.00, 'line_total' => 2560000.00]
        ],
        'subtotal' => 2560000.00,
        'tax' => 192000.00,
        'total_amount' => 2752000.00,
        'status' => 'approved',
    ]);

    $this->actingAs($staff);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'inventory'])
        ->call('selectDispatchOrder', $order->id)
        ->call('scanBarcodeForDispatch', 'SLR-BAT-10KW')
        ->call('confirmWarehouseDispatch', $order->id);

    $order->refresh();
    expect($order->status)->toBe('dispatched');
    expect($order->dispatched_at)->not->toBeNull();

    // Check stock was decremented from 15 to 13
    $product->refresh();
    expect($product->stock_quantity)->toBe(13);
});
