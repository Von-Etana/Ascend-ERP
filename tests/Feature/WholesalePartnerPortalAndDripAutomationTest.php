<?php

namespace Tests\Feature;

use App\Models\CrmLead;
use App\Models\CrmLeadDripLog;
use App\Models\InventoryProduct;
use App\Models\PartnerWarrantyClaim;
use App\Models\SupplierPurchaseOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;
use Modules\AppAscend\Livewire\AscendModuleViewer;
use Tests\TestCase;

class WholesalePartnerPortalAndDripAutomationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
        $this->user = User::factory()->create([
            'email' => 'distributor@ascendsystems.ng',
            'name' => 'Kano Solar Distribution Ltd',
            'distributor_tier' => 'tier1_platinum',
            'credit_limit' => 25000000.00,
            'role_id' => $role->id,
        ]);
        $this->actingAs($this->user);
    }

    public function test_distributor_certificate_can_be_opened_and_closed(): void
    {
        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'retailer'])
            ->set('activeTab', 'contracts')
            ->call('openDistributorCertificate')
            ->assertSet('showCertificateModal', true)
            ->assertSee('CERTIFICATE OF AUTHORIZED DISTRIBUTORSHIP')
            ->assertSee('Kano Solar Distribution Ltd')
            ->assertSee('Tier 1 Platinum Wholesale Partner (20% Off)')
            ->call('closeCertificateModal')
            ->assertSet('showCertificateModal', false);
    }

    public function test_partner_warranty_claim_can_be_submitted_and_updated(): void
    {
        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'retailer'])
            ->set('activeTab', 'warranty')
            ->set('partnerWarrantyForm.serial_number', 'SN-INV-2026-88331')
            ->set('partnerWarrantyForm.product_sku', 'SLR-INV-55KW')
            ->set('partnerWarrantyForm.fault_description', 'Fault Code 04: Inverter DC Overvoltage Error')
            ->set('partnerWarrantyForm.contact_person', 'Engr. Aliyu Kano')
            ->set('partnerWarrantyForm.contact_phone', '+234 803 999 1122')
            ->call('submitPartnerWarrantyClaim')
            ->assertHasNoErrors()
            ->assertSee('Warranty RMA Claim');

        $claim = PartnerWarrantyClaim::where('serial_number', 'SN-INV-2026-88331')->first();
        $this->assertNotNull($claim);
        $this->assertEquals('pending_review', $claim->status);
        $this->assertStringStartsWith('ASC-RMA-', $claim->rma_tracking_code);

        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'retailer'])
            ->set('activeTab', 'warranty')
            ->call('updateWarrantyClaimStatus', $claim->id, 'approved_replacement');

        $this->assertEquals('approved_replacement', $claim->fresh()->status);
    }

    public function test_crm_automated_lead_drip_sequences_can_be_triggered_and_replied(): void
    {
        $lead = CrmLead::create([
            'company_name' => 'Apex Agro Allied Ltd',
            'contact_person' => 'Alhaji Sanusi Umar',
            'email' => 'sanusi@apexagro.ng',
            'phone' => '+234 802 333 4455',
            'deal_value' => 8500000.00,
            'lead_type' => 'customer',
            'status' => 'new',
            'system_interest' => 'Ascend 10.2kVA Commercial Dual MPPT Inverter',
        ]);

        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'crm'])
            ->set('activeTab', 'drip')
            ->call('triggerLeadDrip', $lead->id, 'hour_1', 'whatsapp')
            ->call('triggerLeadDrip', $lead->id, 'day_2', 'whatsapp')
            ->call('triggerLeadDrip', $lead->id, 'day_5', 'email')
            ->assertHasNoErrors();

        $this->assertEquals(3, CrmLeadDripLog::where('crm_lead_id', $lead->id)->count());

        $hour1Log = CrmLeadDripLog::where('crm_lead_id', $lead->id)->where('step', 'hour_1')->first();
        $this->assertNotNull($hour1Log);
        $this->assertEquals('delivered', $hour1Log->status);

        // Simulate customer WhatsApp reply
        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'crm'])
            ->set('activeTab', 'drip')
            ->call('simulateDripReply', $hour1Log->id, 'Please send your engineer to inspect our farm in Kaduna.')
            ->assertHasNoErrors();

        $this->assertEquals('replied', $hour1Log->fresh()->status);
        $this->assertEquals('Please send your engineer to inspect our farm in Kaduna.', $hour1Log->fresh()->reply_content);
    }

    public function test_inventory_auto_reorder_po_generation_and_stock_receipt(): void
    {
        $product = InventoryProduct::create([
            'sku' => 'SLR-BAT-10KW',
            'name' => 'Ascend 10.2kWh LiFePO4 Battery Storage',
            'category' => 'Battery Storage',
            'unit_price' => 1450000.00,
            'cost_price' => 1100000.00,
            'wholesale_price' => 1250000.00,
            'stock_quantity' => 4,
            'reorder_level' => 10,
            'location' => 'Abuja Central Distribution Hub',
        ]);

        // Auto-generate PO for low-stock items
        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'inventory'])
            ->set('activeTab', 'reorder_po')
            ->call('generateAutoReorderPo', 'Abuja Central Distribution Hub')
            ->assertHasNoErrors();

        $po = SupplierPurchaseOrder::latest()->first();
        $this->assertNotNull($po);
        $this->assertEquals('draft', $po->status);
        $this->assertEquals('Abuja Central Distribution Hub', $po->destination_warehouse);

        // Dispatch PO to OEM Supplier
        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'inventory'])
            ->set('activeTab', 'reorder_po')
            ->call('sendPoToSupplier', $po->id);

        $this->assertEquals('sent', $po->fresh()->status);

        // Receive stock from shipment
        Livewire::test(AscendModuleViewer::class, ['moduleKey' => 'inventory'])
            ->set('activeTab', 'reorder_po')
            ->call('receivePoStock', $po->id);

        $this->assertEquals('received', $po->fresh()->status);
        $this->assertGreaterThan(4, $product->fresh()->stock_quantity);
    }
}
