<?php

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\PriceQuote;
use App\Models\CrmLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\User;
use Modules\AppAscend\Livewire\AscendModuleViewer;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('filters finance overview by time period pills', function (): void {
    $user = User::factory()->create(['is_super_admin' => true]);

    Livewire::actingAs($user)
        ->test(AscendModuleViewer::class, ['moduleKey' => 'finance'])
        ->call('setFinancePeriodFilter', 'this_week')
        ->assertSet('financePeriodFilter', 'this_week')
        ->call('setFinancePeriodFilter', 'today')
        ->assertSet('financePeriodFilter', 'today');
});

it('supports attaching multiple receipts to an expense transaction', function (): void {
    $user = User::factory()->create(['is_super_admin' => true]);

    Livewire::actingAs($user)
        ->test(AscendModuleViewer::class, ['moduleKey' => 'finance'])
        ->set('newExpenseReceiptUrl', 'https://app.ascendsystems.ng/receipts/slip_001.pdf')
        ->call('addExpenseReceiptUrl')
        ->set('newExpenseReceiptUrl', 'https://app.ascendsystems.ng/receipts/bank_transfer.png')
        ->call('addExpenseReceiptUrl')
        ->assertSet('expenseReceiptUrls', [
            'https://app.ascendsystems.ng/receipts/slip_001.pdf',
            'https://app.ascendsystems.ng/receipts/bank_transfer.png',
        ])
        ->call('removeExpenseReceiptUrl', 0)
        ->assertSet('expenseReceiptUrls', [
            'https://app.ascendsystems.ng/receipts/bank_transfer.png',
        ]);
});

it('fetches existing invoice details in POS checkout terminal and settles payment', function (): void {
    $user = User::factory()->create(['is_super_admin' => true]);

    $invoice = Invoice::create([
        'invoice_number' => 'INV-2026-999',
        'client_name' => 'Kano Solar Distribution Hub',
        'issue_date' => now(),
        'due_date' => now()->addDays(7),
        'subtotal' => 1000000.00,
        'tax' => 75000.00,
        'total' => 1075000.00,
        'paid_amount' => 0.00,
        'status' => 'pending',
    ]);

    Livewire::actingAs($user)
        ->test(AscendModuleViewer::class, ['moduleKey' => 'pos'])
        ->set('posInvoiceQuery', 'INV-2026-999')
        ->call('lookupInvoiceInPos')
        ->assertSet('fetchedInvoiceDetails.number', 'INV-2026-999')
        ->call('settleFetchedInvoiceInPos');

    $invoice->refresh();
    expect($invoice->status)->toBe('paid')
        ->and((float) $invoice->paid_amount)->toBe(1075000.00);
});

it('auto saves leads from Meta Ads into CRM and triggers drip sequence', function (): void {
    $user = User::factory()->create(['is_super_admin' => true]);

    Livewire::actingAs($user)
        ->test(AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->call('ingestMetaAdsLead', [
            'company_name' => 'Wuse Commercial Complex',
            'full_name' => 'Architect Aliyu Sadiq',
            'email' => 'aliyu@wusecomplex.ng',
            'phone' => '+234 809 111 2233',
            'deal_value' => 3800000.00,
            'system_interest' => 'Ascend 5.5kVA Hybrid Solar Inverter',
        ]);

    $lead = CrmLead::where('email', 'aliyu@wusecomplex.ng')->first();
    expect($lead)->not->toBeNull()
        ->and($lead->company_name)->toBe('Wuse Commercial Complex')
        ->and((float) $lead->deal_value)->toBe(3800000.00);
});
