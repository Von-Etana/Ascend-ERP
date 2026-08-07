<?php

use App\Support\Queue\QueueRateLimiter;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Cache::flush();
});

test('queue rate limiter executes callback within allowed limit', function () {
    $executed = false;

    $result = QueueRateLimiter::throttle('test-key', 2, 60, function () use (&$executed) {
        $executed = true;

        return 'success';
    });

    expect($result)->toBe('success');
    expect($executed)->toBeTrue();
});

test('queue rate limiter throws exception when limit exceeded', function () {
    QueueRateLimiter::throttle('test-limit-key', 1, 60, fn () => 'pass 1');

    expect(fn () => QueueRateLimiter::throttle('test-limit-key', 1, 60, fn () => 'pass 2'))
        ->toThrow(RuntimeException::class);
});

test('queue rate limiter can be cleared', function () {
    QueueRateLimiter::throttle('clear-key', 1, 60, fn () => 'pass 1');
    QueueRateLimiter::clear('clear-key');

    $result = QueueRateLimiter::throttle('clear-key', 1, 60, fn () => 'pass after clear');
    expect($result)->toBe('pass after clear');
});
