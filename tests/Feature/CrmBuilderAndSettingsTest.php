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
