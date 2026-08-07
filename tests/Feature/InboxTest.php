<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\AppInbox\Support\InboxResponsePolicy;
use Modules\AppInbox\Support\InboxService;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('ships the reference inbox channels and seeded conversation workflow', function (): void {
    $conversations = app(InboxService::class)->seededConversations();

    expect($conversations)->not->toBeEmpty()
        ->and(collect($conversations)->pluck('provider')->unique()->sort()->values()->all())
        ->toBe(['email', 'instagram', 'messenger', 'telegram', 'whatsapp']);
});

it('keeps the hybrid handling states explicit', function (): void {
    $conversations = app(InboxService::class)->seededConversations();

    expect(collect($conversations)->pluck('mode')->unique()->all())
        ->toContain('ai')
        ->toContain('human');
});

it('hands sensitive inbox messages to a human', function (): void {
    $policy = app(InboxResponsePolicy::class);

    expect($policy->requiresHuman('I need a human agent to help with a refund'))->toBeTrue()
        ->and($policy->requiresHuman('What are your opening hours?', 0.95))->toBeFalse()
        ->and($policy->requiresHuman('What are your opening hours?', 0.40))->toBeTrue();
});
