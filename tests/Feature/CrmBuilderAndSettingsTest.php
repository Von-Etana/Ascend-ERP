<?php

use App\Models\CrmDeal;
use App\Models\CrmLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $role = AdminRole::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Admin', 'permissions' => ['*']]);
    $this->user = User::factory()->create([
        'email' => 'admin@ascendsystems.ng',
        'name' => 'System Admin',
        'role_id' => $role->id,
    ]);
});

test('crm header add lead button navigates to leads tab and opens modal', function () {
    $this->actingAs($this->user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->call('navigateToAddLead')
        ->assertSet('activeTab', 'leads')
        ->assertSet('showModal', true)
        ->assertSet('modalType', 'lead');
});

test('crm builder can switch sub tabs', function () {
    $this->actingAs($this->user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->assertSet('crmBuilderTab', 'fields')
        ->call('setCrmBuilderTab', 'stages')
        ->assertSet('crmBuilderTab', 'stages')
        ->call('setCrmBuilderTab', 'preferences')
        ->assertSet('crmBuilderTab', 'preferences')
        ->call('setCrmBuilderTab', 'preview')
        ->assertSet('crmBuilderTab', 'preview');
});

test('crm builder can toggle custom field properties', function () {
    $this->actingAs($this->user);

    $component = Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder');

    $initialRequired = $component->get('crmCustomFields.0.required');

    $component->call('toggleCustomField', 0, 'required');

    expect($component->get('crmCustomFields.0.required'))->not->toBe($initialRequired);
});

test('crm builder can add and remove custom field', function () {
    $this->actingAs($this->user);

    $component = Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->set('newCustomField', [
            'label' => 'Monthly Diesel Spend (NGN)',
            'key' => 'diesel_spend',
            'type' => 'number',
            'required' => true,
            'enabled' => true,
            'options' => '',
        ])
        ->call('addCustomField');

    $fields = $component->get('crmCustomFields');
    $added = collect($fields)->firstWhere('key', 'diesel_spend');
    expect($added)->not->toBeNull();
    expect($added['label'])->toBe('Monthly Diesel Spend (NGN)');

    // Remove the custom field
    $lastIndex = count($fields) - 1;
    $component->call('removeCustomField', $lastIndex);

    $fieldsAfter = $component->get('crmCustomFields');
    expect(collect($fieldsAfter)->firstWhere('key', 'diesel_spend'))->toBeNull();
});

test('crm builder can add and remove pipeline stage', function () {
    $this->actingAs($this->user);

    $component = Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->set('newCrmStage', [
            'category' => 'lead',
            'label' => 'Technical Sizing Approved',
            'key' => 'sizing_approved',
            'color' => 'emerald',
            'probability' => 75,
            'auto_action' => 'Auto-Generate Price Quotation',
        ])
        ->call('addPipelineStage');

    $leadStages = $component->get('crmLeadStages');
    $added = collect($leadStages)->firstWhere('key', 'sizing_approved');
    expect($added)->not->toBeNull();
    expect($added['probability'])->toBe(75);

    // Remove the stage
    $lastIndex = count($leadStages) - 1;
    $component->call('removePipelineStage', 'lead', $lastIndex);

    $leadStagesAfter = $component->get('crmLeadStages');
    expect(collect($leadStagesAfter)->firstWhere('key', 'sizing_approved'))->toBeNull();
});

test('crm workflow settings save and reset', function () {
    $this->actingAs($this->user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->set('crmWorkflowSettings.followup_sla_hours', 12)
        ->call('saveCrmSettings')
        ->call('resetCrmDefaults')
        ->assertSet('crmWorkflowSettings.followup_sla_hours', 24);
});

test('crm test simulator submits lead and auto converts high scoring deal', function () {
    $this->actingAs($this->user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->set('testLeadForm', [
            'client_name' => 'Dr. Amina Danjuma',
            'company_name' => 'Danjuma Specialist Clinic',
            'phone' => '+234 802 333 4455',
            'email' => 'amina@danjumaclinic.ng',
            'city_location' => 'Abuja',
            'property_type' => 'Hospital / Healthcare',
            'system_interest' => 'Ascend 20kVA-50kVA Industrial Microgrid',
            'deal_value' => '15000000',
            'purchasing_timeline' => 'immediate',
            'notes' => 'Critical hospital 24/7 power backup required.',
        ])
        ->call('submitTestLead');

    $this->assertDatabaseHas('crm_leads', [
        'company_name' => 'Danjuma Specialist Clinic',
        'contact_person' => 'Dr. Amina Danjuma',
        'email' => 'amina@danjumaclinic.ng',
    ]);

    $lead = CrmLead::where('email', 'amina@danjumaclinic.ng')->first();
    expect($lead)->not->toBeNull();
    expect($lead->ai_lead_score)->toBeGreaterThanOrEqual(80);

    // High score auto-converts to Deal
    $this->assertDatabaseHas('crm_deals', [
        'crm_lead_id' => $lead->id,
        'deal_name' => 'Deal — Danjuma Specialist Clinic',
    ]);
});

test('crm builder can switch between customer and partner form schemas', function () {
    $this->actingAs($this->user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->assertSet('activeFormSchema', 'customer')
        ->call('setActiveFormSchema', 'partner')
        ->assertSet('activeFormSchema', 'partner')
        ->call('setActiveFormSchema', 'customer')
        ->assertSet('activeFormSchema', 'customer');
});

test('crm builder can add and remove partner custom field', function () {
    $this->actingAs($this->user);

    $component = Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->set('newPartnerCustomField', [
            'label' => 'Warehouse Square Footage',
            'key' => 'warehouse_sqft',
            'type' => 'number',
            'required' => true,
            'enabled' => true,
            'options' => '',
        ])
        ->call('addPartnerCustomField');

    $partnerFields = $component->get('partnerCustomFields');
    $added = collect($partnerFields)->firstWhere('key', 'warehouse_sqft');
    expect($added)->not->toBeNull();
    expect($added['label'])->toBe('Warehouse Square Footage');

    // Remove the custom partner field
    $lastIndex = count($partnerFields) - 1;
    $component->call('removePartnerCustomField', $lastIndex);

    $partnerFieldsAfter = $component->get('partnerCustomFields');
    expect(collect($partnerFieldsAfter)->firstWhere('key', 'warehouse_sqft'))->toBeNull();
});

test('crm partner test simulator creates partner lead and auto converts wholesale deal', function () {
    $this->actingAs($this->user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->set('activeTab', 'builder')
        ->set('testPartnerLeadForm', [
            'client_name' => 'Engr. Chukwuemeka Obi',
            'job_title' => 'Chief Executive Officer',
            'company_name' => 'Obi Renewable Power Systems Ltd',
            'country' => 'Nigeria',
            'website' => 'https://obipowersystems.ng',
            'phone' => '+234 803 777 9900',
            'email' => 'obi@obipowersystems.ng',
            'monthly_sales_volume' => '₦50,000,000 - ₦100,000,000 / Month',
            'product_interest' => 'Ascend Pure Sine Wave Hybrid Solar Inverters',
            'customer_type' => 'Wholesale Distributor / Regional Reseller',
            'deal_value' => '45000000',
            'notes' => 'Seeking regional tier-1 wholesale dealership.',
        ])
        ->call('submitTestPartnerLead');

    $this->assertDatabaseHas('crm_leads', [
        'lead_type' => 'partner',
        'company_name' => 'Obi Renewable Power Systems Ltd',
        'contact_person' => 'Engr. Chukwuemeka Obi',
        'job_title' => 'Chief Executive Officer',
        'country' => 'Nigeria',
        'website' => 'https://obipowersystems.ng',
        'email' => 'obi@obipowersystems.ng',
        'monthly_sales_volume' => '₦50,000,000 - ₦100,000,000 / Month',
        'customer_type' => 'Wholesale Distributor / Regional Reseller',
    ]);

    $lead = CrmLead::where('email', 'obi@obipowersystems.ng')->first();
    expect($lead)->not->toBeNull();
    expect($lead->lead_type)->toBe('partner');

    $this->assertDatabaseHas('crm_deals', [
        'crm_lead_id' => $lead->id,
        'deal_name' => 'Wholesale Deal — Obi Renewable Power Systems Ltd',
    ]);
});

test('crm modal can create partner lead with all required partner fields', function () {
    $this->actingAs($this->user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'crm'])
        ->call('openCreateModal', 'partner_lead')
        ->set('form.lead_type', 'partner')
        ->set('form.client_name', 'Musa Ibrahim')
        ->set('form.job_title', 'Procurement Director')
        ->set('form.company_name', 'Arewa Solar Distribution Ltd')
        ->set('form.country', 'Nigeria')
        ->set('form.website', 'https://arewasolar.ng')
        ->set('form.phone', '+234 805 111 2233')
        ->set('form.email', 'musa@arewasolar.ng')
        ->set('form.product_interest', 'Ascend LiFePO4 Lithium Battery Storage Systems')
        ->set('form.monthly_sales_volume', '₦20,000,000 - ₦50,000,000 / Month')
        ->set('form.customer_type', 'Wholesale Distributor / Regional Reseller')
        ->set('form.amount', '30000000')
        ->set('form.notes', 'Exclusive Kano distributor inquiry')
        ->call('submitModalForm');

    $this->assertDatabaseHas('crm_leads', [
        'lead_type' => 'partner',
        'contact_person' => 'Musa Ibrahim',
        'job_title' => 'Procurement Director',
        'company_name' => 'Arewa Solar Distribution Ltd',
        'website' => 'https://arewasolar.ng',
        'email' => 'musa@arewasolar.ng',
        'country' => 'Nigeria',
        'customer_type' => 'Wholesale Distributor / Regional Reseller',
    ]);
});

test('public partner lead intake api endpoint captures b2b distributor applications', function () {
    $payload = [
        'client_name' => 'Kwame Mensah',
        'job_title' => 'Managing Director',
        'company_name' => 'Accra Solar Engineering Ltd',
        'country' => 'Ghana',
        'website' => 'https://accrasolar.gh',
        'phone' => '+233 24 555 6789',
        'email' => 'kwame@accrasolar.gh',
        'monthly_sales_volume' => '₦50,000,000 - ₦100,000,000 / Month',
        'product_interest' => 'Ascend Pure Sine Wave Hybrid Solar Inverters',
        'customer_type' => 'Renewable Energy Installer / EPC Contractor',
        'estimated_budget_ngn' => 28000000,
        'special_notes' => 'Looking to distribute Ascend inverters across West African region.',
    ];

    $response = $this->postJson(route('api.leads.partner-capture'), $payload);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseHas('web_lead_captures', [
        'lead_type' => 'partner',
        'client_name' => 'Kwame Mensah',
        'job_title' => 'Managing Director',
        'company_name' => 'Accra Solar Engineering Ltd',
        'country' => 'Ghana',
        'email' => 'kwame@accrasolar.gh',
    ]);

    $this->assertDatabaseHas('crm_leads', [
        'lead_type' => 'partner',
        'contact_person' => 'Kwame Mensah',
        'job_title' => 'Managing Director',
        'company_name' => 'Accra Solar Engineering Ltd',
        'country' => 'Ghana',
        'customer_type' => 'Renewable Energy Installer / EPC Contractor',
    ]);
});

