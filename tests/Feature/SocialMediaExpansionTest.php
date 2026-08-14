<?php

use App\Models\CrmLead;
use App\Models\InfluencerAmbassador;
use App\Models\WebLeadCapture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('manager can generate ai video script for tiktok and reels', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    $test = Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, [
        'moduleKey' => 'marketing',
        'activeTab' => 'video_script_ai',
    ])
    ->call('generateAiVideoScript');

    expect($test->get('generatedVideoScript'))->toContain('TIKTOK & REELS SCRIPT');
});

test('web lead form submission from ascendsystems.ng creates web lead and auto injects crm lead', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, [
        'moduleKey' => 'marketing',
        'activeTab' => 'web_lead_capture',
    ])
    ->set('webLeadForm.client_name', 'Chief Emeka Nwosu')
    ->set('webLeadForm.phone', '+234 802 888 9900')
    ->set('webLeadForm.email', 'emeka@nwosugroup.ng')
    ->set('webLeadForm.city_location', 'Lagos')
    ->set('webLeadForm.system_interest', 'Ascend 10.2kWh LiFePO4 Battery + 5.5kVA Hybrid Inverter')
    ->set('webLeadForm.estimated_budget_ngn', 2500000.00)
    ->call('submitWebLeadCaptureForm');

    $webLead = WebLeadCapture::where('client_name', 'Chief Emeka Nwosu')->first();
    expect($webLead)->not->toBeNull();
    expect($webLead->phone)->toBe('+234 802 888 9900');

    $crmLead = CrmLead::where('phone', '+234 802 888 9900')->first();
    expect($crmLead)->not->toBeNull();
    expect((float)$crmLead->deal_value)->toBe(2500000.00);
});

test('manager can register brand ambassador and track referral link', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, [
        'moduleKey' => 'marketing',
        'activeTab' => 'influencers',
    ])
    ->set('influencerForm.name', 'Engr. Tunde Solar Tech')
    ->set('influencerForm.handle', '@tunde_solar_ng')
    ->set('influencerForm.platform', 'YouTube')
    ->set('influencerForm.referral_code', 'SOLARTECH2026')
    ->call('registerInfluencerAmbassador');

    $ambassador = InfluencerAmbassador::where('referral_code', 'SOLARTECH2026')->first();
    expect($ambassador)->not->toBeNull();
    expect($ambassador->handle)->toBe('@tunde_solar_ng');
});
