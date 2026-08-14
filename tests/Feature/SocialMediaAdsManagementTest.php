<?php

use App\Models\ScheduledSocialPost;
use App\Models\SocialAdCampaign;
use App\Models\SocialInboxMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('manager can launch ad campaign and view calculated ROAS metric', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'marketing'])
        ->set('adForm.campaign_name', 'Meta Ads Lithium Battery Promo Q3')
        ->set('adForm.platform', 'Meta Ads (Facebook & IG)')
        ->set('adForm.budget_ngn', 500000.00)
        ->call('createAdCampaign');

    $campaign = SocialAdCampaign::where('campaign_name', 'Meta Ads Lithium Battery Promo Q3')->first();
    expect($campaign)->not->toBeNull();
    expect((float)$campaign->budget_ngn)->toBe(500000.00);
    expect($campaign->roas)->toBeGreaterThan(0);
});

test('manager can generate ai copy and schedule social media post', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'marketing'])
        ->call('generateAiSocialCaption', 'Solar Inverter Promo', 'Instagram')
        ->call('scheduleSocialPost');

    $post = ScheduledSocialPost::where('platform', 'Instagram')->first();
    expect($post)->not->toBeNull();
    expect($post->caption)->toContain('5.5kVA');
    expect($post->status)->toBe('scheduled');
});

test('manager can reply to customer pricing inquiry in unified social inbox', function () {
    $role = AdminRole::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'permissions' => ['*']]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $message = SocialInboxMessage::create([
        'sender_name' => 'Kano Solar Reseller',
        'sender_handle' => '@kano_solar',
        'channel' => 'Instagram DM',
        'message_body' => 'What is your current wholesale price for 550W mono solar panels?',
        'ai_suggested_reply' => 'Hello Kano Solar! Our wholesale price for 550W Mono Solar Panels is ₦82,000 per panel.',
    ]);

    $this->actingAs($user);

    Livewire::test(\Modules\AppAscend\Livewire\AscendModuleViewer::class, ['moduleKey' => 'marketing'])
        ->call('replyToSocialMessage', $message->id);

    $message->refresh();
    expect($message->is_replied)->toBeTrue();
    expect($message->replied_text)->toContain('82,000');
});
