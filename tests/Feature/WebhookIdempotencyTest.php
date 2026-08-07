<?php

use App\Support\Webhooks\WebhookIdempotency;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Cache::flush();
});

test('webhook idempotency correctly identifies unprocessed event', function () {
    expect(WebhookIdempotency::isProcessed('meta', 'evt_123'))->toBeFalse();
});

test('webhook idempotency marks event as processed', function () {
    WebhookIdempotency::markProcessed('meta', 'evt_123');
    expect(WebhookIdempotency::isProcessed('meta', 'evt_123'))->toBeTrue();
});

test('webhook idempotency once helper executes callback only once', function () {
    $count = 0;

    $first = WebhookIdempotency::once('stripe', 'evt_999', function () use (&$count) {
        $count++;

        return 'processed';
    });

    $second = WebhookIdempotency::once('stripe', 'evt_999', function () use (&$count) {
        $count++;

        return 'should-not-run';
    });

    expect($first['executed'])->toBeTrue();
    expect($first['result'])->toBe('processed');
    expect($second['executed'])->toBeFalse();
    expect($second['result'])->toBeNull();
    expect($count)->toBe(1);
});
