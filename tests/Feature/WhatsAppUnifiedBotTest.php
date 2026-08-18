<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\AppEmail\Models\Lead;
use Modules\AppInbox\Models\InboxConversation;
use Modules\AppInbox\Support\WhatsAppMenuFlowService;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('responds to Meta WhatsApp webhook verification challenge', function (): void {
    config()->set('modules.appinbox.whatsapp_bot.verify_token', 'my_custom_verify_token');

    $response = $this->get('/api/inbox/v1/webhooks/whatsapp?hub_mode=subscribe&hub_verify_token=my_custom_verify_token&hub_challenge=CHALLENGE_ACCEPTED');

    $response->assertStatus(200);
    expect($response->getContent())->toBe('CHALLENGE_ACCEPTED');
});

it('rejects invalid Meta WhatsApp webhook verification challenge', function (): void {
    config()->set('modules.appinbox.whatsapp_bot.verify_token', 'my_custom_verify_token');

    $response = $this->get('/api/inbox/v1/webhooks/whatsapp?hub_mode=subscribe&hub_verify_token=wrong_token&hub_challenge=CHALLENGE_ACCEPTED');

    $response->assertStatus(403);
});

it('progresses through solar qualification flow and captures lead', function (): void {
    $service = app(WhatsAppMenuFlowService::class);
    $phone = '2348011223344';
    $service->resetSession($phone);

    // 1. Trigger menu
    $res1 = $service->handle($phone, 'menu');
    expect($res1['reply'])->toContain('Welcome to Ascend Systems')
        ->and($res1['session']['state'])->toBe('MENU');

    // 2. Select Option 1 (Solar)
    $res2 = $service->handle($phone, '1');
    expect($res2['reply'])->toContain('Residential (home)')
        ->and($res2['session']['state'])->toBe('SOLAR_TYPE');

    // 3. Select Option A (Residential)
    $res3 = $service->handle($phone, 'A');
    expect($res3['reply'])->toContain('What\'s your name?')
        ->and($res3['session']['state'])->toBe('LEAD_NAME');

    // 4. Provide Name -> prompts for Buyer Type (Individual / Business / Corporate)
    $res4 = $service->handle($phone, 'Adebayo Ogunlesi');
    expect($res4['reply'])->toContain('Are you reaching out as an Individual, Business owner, or Corporate entity?')
        ->and($res4['session']['state'])->toBe('LEAD_TYPE');

    // 5. Provide Buyer Type -> prompts for Location
    $res5 = $service->handle($phone, 'Individual');
    expect($res5['reply'])->toContain('What city/area are you in?')
        ->and($res5['session']['state'])->toBe('LEAD_LOCATION');

    // 6. Provide Location -> Completed!
    $res6 = $service->handle($phone, 'Lekki Phase 1, Lagos');
    expect($res6['lead_complete'])->toBeTrue()
        ->and($res6['reply'])->toContain('A member of our team will reach out shortly')
        ->and($res6['session']['state'])->toBe('DONE');

    // Verify lead was stored in ascend_leads
    $lead = Lead::where('phone', $phone)->first();
    expect($lead)->not->toBeNull()
        ->and($lead->name)->toBe('Adebayo Ogunlesi')
        ->and($lead->metadata['interest'])->toBe('Solar — residential')
        ->and($lead->metadata['buyer_type'])->toBe('Individual')
        ->and($lead->metadata['location'])->toBe('Lekki Phase 1, Lagos');
});

it('handles inbound Meta WhatsApp webhook payload seamlessly', function (): void {
    $phone = '2348099887766';
    $payload = [
        'entry' => [
            [
                'id' => 'WHATSAPP_BUSINESS_ACCOUNT_ID',
                'changes' => [
                    [
                        'field' => 'messages',
                        'value' => [
                            'messaging_product' => 'whatsapp',
                            'metadata' => [
                                'display_phone_number' => '15550234567',
                                'phone_number_id' => '10000000000',
                            ],
                            'contacts' => [
                                [
                                    'profile' => ['name' => 'Chioma Nnamdi'],
                                    'wa_id' => $phone,
                                ],
                            ],
                            'messages' => [
                                [
                                    'from' => $phone,
                                    'id' => 'wamid.HBgLMjM0ODA5OTg4Nzc2NgUCMRICGBgz',
                                    'timestamp' => '1723900000',
                                    'text' => ['body' => 'menu'],
                                    'type' => 'text',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $response = $this->postJson('/api/inbox/v1/webhooks/whatsapp', $payload);

    $response->assertStatus(200)
        ->assertJson(['ok' => true]);

    $conversation = InboxConversation::where('provider_key', 'whatsapp')
        ->where('external_thread_id', $phone)
        ->first();

    expect($conversation)->not->toBeNull()
        ->and($conversation->contact_name)->toBe('Chioma Nnamdi')
        ->and($conversation->messages)->toHaveCount(2); // Inbound message + Outbound Bot Menu Reply
});

it('switches to human mode when customer asks to talk to human', function (): void {
    $service = app(WhatsAppMenuFlowService::class);
    $phone = '2348055667788';
    $service->resetSession($phone);

    $service->handle($phone, 'menu');
    $res = $service->handle($phone, '6'); // Talk to human

    expect($res['handoff'])->toBeTrue()
        ->and($res['session']['data']['priority'])->toBe('high');
});
