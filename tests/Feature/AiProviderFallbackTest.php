<?php

use App\Support\AI\AiProviderFallbackManager;
use Tests\TestCase;

uses(TestCase::class);

test('ai provider fallback returns result from primary provider when successful', function () {
    $result = AiProviderFallbackManager::executeWithFallback([
        fn () => 'openai-response',
        fn () => 'anthropic-fallback',
    ]);

    expect($result)->toBe('openai-response');
});

test('ai provider fallback automatically tries secondary provider on failure', function () {
    $result = AiProviderFallbackManager::executeWithFallback([
        fn () => throw new RuntimeException('Primary rate limited'),
        fn () => 'anthropic-fallback',
    ]);

    expect($result)->toBe('anthropic-fallback');
});

test('ai provider fallback throws exception if all fail', function () {
    expect(fn () => AiProviderFallbackManager::executeWithFallback([
        fn () => throw new RuntimeException('Primary failed'),
        fn () => throw new RuntimeException('Secondary failed'),
    ]))->toThrow(RuntimeException::class);
});
