<?php

use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\InfluencerAmbassador;
use App\Models\WebLeadCapture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('submitting comprehensive lead form calculates AI score and syncs CRM pipeline with opportunity', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    $ambassador = InfluencerAmbassador::create([
        'name' => 'Tunde Solar',
        'handle' => '@tundesolar',
        'platform' => 'Instagram',
        'referral_code' => 'TUNDESOLAR10',
        'leads_count' => 0,
    ]);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, [
        'moduleKey' => 'marketing',
        'activeTab' => 'web_lead_capture',
    ])
    ->set('webLeadForm.client_name', 'Dr. Aliyu Mohammed')
    ->set('webLeadForm.company_name', 'Maitama Specialist Hospital')
    ->set('webLeadForm.phone', '+234 803 555 7788')
    ->set('webLeadForm.email', 'aliyu@maitamahospital.ng')
    ->set('webLeadForm.city_location', 'Abuja')
    ->set('webLeadForm.property_type', 'Hospital / Healthcare Clinic')
    ->set('webLeadForm.installation_address', 'Plot 1202 Gana Street, Maitama')
    ->set('webLeadForm.system_interest', 'Ascend 20kVA-50kVA Industrial Microgrid')
    ->set('webLeadForm.daily_generator_hours', 14)
    ->set('webLeadForm.monthly_fuel_spend_ngn', 650000.00)
    ->set('webLeadForm.roof_mounting_type', 'Concrete Roof Deck')
    ->set('webLeadForm.estimated_budget_ngn', 12500000.00)
    ->set('webLeadForm.purchasing_timeline', 'immediate')
    ->set('webLeadForm.financing_preference', 'milestone_70_30')
    ->set('webLeadForm.referral_code', 'TUNDESOLAR10')
    ->set('webLeadForm.special_notes', 'Critical 24/7 ICU & Theatre backup needed with zero transfer time')
    ->call('submitWebLeadCaptureForm');

    // 1. Verify WebLeadCapture record
    $webLead = WebLeadCapture::where('client_name', 'Dr. Aliyu Mohammed')->first();
    expect($webLead)->not->toBeNull();
    expect($webLead->company_name)->toBe('Maitama Specialist Hospital');
    expect($webLead->property_type)->toBe('Hospital / Healthcare Clinic');
    expect($webLead->daily_generator_hours)->toBe(14);
    expect((float)$webLead->monthly_fuel_spend_ngn)->toBe(650000.00);
    expect($webLead->ai_lead_score)->toBeGreaterThanOrEqual(95);

    // 2. Verify CRM Lead record
    $crmLead = CrmLead::where('phone', '+234 803 555 7788')->first();
    expect($crmLead)->not->toBeNull();
    expect((float)$crmLead->deal_value)->toBe(12500000.00);
    expect($crmLead->ai_lead_score)->toBeGreaterThanOrEqual(95);

    // 3. Verify CRM Deal record
    $deal = CrmDeal::where('crm_lead_id', $crmLead->id)->first();
    expect($deal)->not->toBeNull();
    expect((float)$deal->value)->toBe(12500000.00);

    // 4. Verify Ambassador referral attribution
    $ambassador->refresh();
    expect($ambassador->leads_count)->toBe(1);
});

test('public lead capture API endpoint captures external website leads', function () {
    $response = $this->postJson('/api/leads/capture', [
        'client_name' => 'Engr. Sarah Briggs',
        'company_name' => 'Briggs Logistics Port Harcourt',
        'phone' => '+234 809 111 2233',
        'email' => 'sarah@briggslogistics.com',
        'city_location' => 'Port Harcourt',
        'property_type' => 'Industrial / Factory / Warehouse',
        'system_interest' => 'Ascend 15kVA Commercial Solar Array',
        'estimated_budget_ngn' => 6000000.00,
        'purchasing_timeline' => 'immediate',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
        ]);

    $lead = WebLeadCapture::where('client_name', 'Engr. Sarah Briggs')->first();
    expect($lead)->not->toBeNull();
    expect($lead->city_location)->toBe('Port Harcourt');
    expect($lead->ai_lead_score)->toBeGreaterThanOrEqual(90);
});
