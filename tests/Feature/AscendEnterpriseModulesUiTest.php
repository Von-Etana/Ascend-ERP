<?php

use App\Models\InventoryProduct;
use App\Models\Invoice;
use App\Models\PosReceipt;
use App\Models\Project;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('renders all 7 core enterprise modules inside full app shell with complete sidebar navigation', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create(['username' => 'admin_test']);

    $modules = ['crm', 'sales', 'finance', 'inventory', 'pos', 'marketing', 'ai-agents'];

    foreach ($modules as $moduleKey) {
        $response = $this->actingAs($user)->get("/portal/ascend/{$moduleKey}");
        $response->assertStatus(200);
        $response->assertSee('Ascend Systems');
        $response->assertSee('Overview');
        $response->assertSee('CRM');
        $response->assertSee('Sales');
        $response->assertSee('Finance');
        $response->assertSee('Inventory');
        $response->assertSee('POS');
        $response->assertSee('Marketing');
        $response->assertSee('AI Agents');
        $response->assertSee('Workspace');
    }
});

test('clicking New POS Sale header button opens interactive quick sale checkout modal', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create();

    \Livewire\Livewire::actingAs($user)
        ->test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'pos'])
        ->call('openCreateModal', 'pos')
        ->assertSet('showModal', true)
        ->assertSet('modalType', 'pos_sale')
        ->assertSee('New POS Quick Sale & Terminal Checkout')
        ->assertSee('Complete Sale & Print Receipt');
});

test('finance workspace banking ledger p_l report invoice reminder and create invoice flow operate correctly', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create();

    $inv = Invoice::create([
        'invoice_number' => 'INV-20499',
        'client_name' => 'Northbridge Media Nigeria',
        'issue_date' => now(),
        'due_date' => now()->addDays(14),
        'subtotal' => 450000.00,
        'tax' => 33750.00,
        'total' => 483750.00,
        'status' => 'pending',
    ]);

    \Livewire\Livewire::actingAs($user)
        ->test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'finance'])
        ->call('setTab', 'ledger')
        ->assertSee('General Ledger & Double-Entry Trial Balance Sheet')
        ->call('setTab', 'reports')
        ->assertSee('Profit & Loss (P&L) Income Statement Report')
        ->call('sendInvoiceReminder', $inv->id)
        ->assertSee('Payment reminder email dispatched')
        ->call('initiateBankTransfer', 'Access Bank HQ', 'GTBank Operations', 500000.00)
        ->assertSee('Initiated bank transfer of ₦500,000.00')
        ->call('autoGenerateInvoiceNumber')
        ->assertSee('Generated new unique Invoice number')
        ->call('openCreateModal', 'invoice')
        ->set('form.client_name', 'Horizon Media Communications')
        ->set('form.invoice_number', 'INV-88991')
        ->set('form.subtotal', '350000')
        ->set('form.tax', '26250')
        ->call('submitModalForm')
        ->assertSee('created and persisted!');

    expect(Invoice::query()->where('invoice_number', 'INV-88991')->exists())->toBeTrue();
});

test('sub-tab navigation dynamically updates active views for pos module', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create();

    \Livewire\Livewire::actingAs($user)
        ->test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'pos'])
        ->call('setTab', 'receipts')
        ->assertSee('Historical Sales Receipts')
        ->call('setTab', 'barcodes')
        ->assertSee('Barcode & Thermal Label Printing Studio')
        ->call('setTab', 'insights')
        ->assertSee('POS Insights & Cash Register Shift Logs');
});

test('pos fast retail checkout barcode scanning digital receipt thermal printing and shift closing operate correctly', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create();

    $prod = InventoryProduct::create([
        'sku' => 'POS-HDW-004',
        'name' => 'Barcode Scanner Unit',
        'category' => 'Hardware',
        'unit_price' => 85000.00,
        'cost_price' => 50000.00,
        'stock_quantity' => 100,
        'reorder_level' => 10,
        'location' => 'Lagos HQ',
    ]);

    \Livewire\Livewire::actingAs($user)
        ->test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'pos'])
        ->call('scanBarcode', 'POS-HDW-004')
        ->assertSee('Barcode scanned!')
        ->call('setPosDiscount', 5)
        ->assertSee('Applied 5% loyalty discount')
        ->call('setPosPaymentMethod', 'card')
        ->call('checkoutPos')
        ->assertSet('showModal', true)
        ->assertSet('modalType', 'pos_receipt')
        ->call('sendDigitalReceipt', 'REC-12345', 'client@ascendsystems.ng')
        ->assertSee('Digital e-Receipt')
        ->call('reprintPosReceipt', 'REC-12345')
        ->assertSet('showModal', true)
        ->assertSet('modalType', 'pos_receipt')
        ->assertSee('Ascend Systems')
        ->assertSee('Print Thermal Receipt')
        ->call('printBarcodeLabel', 'POS-HDW-004', 50)
        ->assertSet('showModal', true)
        ->assertSet('modalType', 'thermal_label')
        ->assertSee('Direct Thermal Barcode Label Printer')
        ->assertSee('Send to Thermal Printer')
        ->call('closeShiftRegister')
        ->assertSee('POS shift closed');

    expect($prod->fresh()->stock_quantity)->toBe(99);
    expect(PosReceipt::query()->count())->toBeGreaterThan(0);
});

test('inventory hub stock tracking warehouse availability supplier PO and SKU creation operate correctly', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create();

    \Livewire\Livewire::actingAs($user)
        ->test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'inventory'])
        ->call('autoGenerateSku')
        ->assertSee('Generated new unique SKU code')
        ->call('orderSupplierStock', 'Apex Hardware Supplies Ltd', 'POS-HDW-004')
        ->assertSee('Purchase Order')
        ->call('transferStock', 'POS-HDW-004', 'Lagos HQ Central Warehouse', 'Abuja Regional Distribution Hub', 10)
        ->assertSee('Transferred 10 units')
        ->call('openCreateModal', 'product')
        ->set('form.name', 'Enterprise Thermal Printer')
        ->set('form.sku', 'PRN-9988')
        ->set('form.unit_price', '95000')
        ->set('form.cost_price', '65000')
        ->set('form.stock_quantity', '40')
        ->set('form.reorder_level', '8')
        ->set('form.location', 'Lagos HQ Central Warehouse')
        ->call('submitModalForm')
        ->assertSee('created and persisted!');

    expect(InventoryProduct::query()->where('sku', 'PRN-9988')->exists())->toBeTrue();
});

test('marketing hub multi-channel campaigns social channels audience blasts and new campaign creation flow operate correctly', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create();

    \Livewire\Livewire::actingAs($user)
        ->test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'marketing'])
        ->assertSee('Multi-Channel Marketing Campaigns')
        ->assertSee('Total Ad Spend')
        ->assertSee('4.8x ROAS')
        ->call('setTab', 'social')
        ->assertSee('Social Media Channel Management')
        ->assertSee('Connect New Social Media Account')
        ->call('syncChannelStats', 'Facebook')
        ->assertSee('Synced API analytics')
        ->call('setTab', 'blasts')
        ->assertSee('Create & Dispatch Audience Broadcast')
        ->call('sendTestBlast')
        ->assertSee('Test email blast preview sent')
        ->set('blastForm.subject', 'Q3 ERP Feature Broadcast')
        ->set('blastForm.segment', 'All Active Clients')
        ->call('sendAudienceBlast')
        ->assertSee('dispatched successfully')
        ->call('setTab', 'analytics')
        ->assertSee('Campaign Analytics & ROAS Attribution')
        ->assertSee('Export ROAS Report CSV')
        ->call('openCreateModal', 'marketing')
        ->assertSet('showModal', true)
        ->assertSet('modalType', 'campaign')
        ->assertSee('Create New Marketing Campaign')
        ->set('form.title', 'Q4 West Africa SaaS Growth Blitz')
        ->set('form.category', 'Multi-Channel (Meta, LinkedIn, Google)')
        ->set('form.subtotal', '3500000')
        ->call('submitModalForm')
        ->assertSee('created and persisted!');
});

test('workflow automation hub triggers rules testing simulation and add rule creation flow operate correctly', function () {
    $user = \Modules\AdminUser\Models\User::query()->firstWhere('username', 'admin') 
        ?? \Modules\AdminUser\Models\User::factory()->create();

    \Livewire\Livewire::actingAs($user)
        ->test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'automation'])
        ->assertSee('Workflow Automation Rules & Webhooks')
        ->assertSee('Active Rules')
        ->assertSee('99.8% Success')
        ->call('testAutomationRule', 1)
        ->assertSee('Test execution triggered')
        ->call('toggleAutomationRule', 1)
        ->assertSee('Automation rule')
        ->call('setTab', 'triggers')
        ->assertSee('Event Registry & Webhook Triggers')
        ->call('simulateTriggerEvent', 'crm.lead_qualified')
        ->assertSee('Simulated trigger event')
        ->call('setTab', 'logs')
        ->assertSee('Automation Execution Audit Logs')
        ->call('openCreateModal', 'automation')
        ->assertSet('showModal', true)
        ->assertSet('modalType', 'rule')
        ->assertSee('Add New Automation Rule')
        ->set('form.title', 'Auto-send POS Digital Receipt via Email')
        ->set('form.category', 'POS Checkout Completed')
        ->set('form.notes', 'Send POS Receipt via Email')
        ->call('submitModalForm')
        ->assertSee('created and persisted!');
});
