<?php

namespace App\Support\AI;

use Illuminate\Support\Facades\Log;
use Throwable;

class AiProviderFallbackManager
{
    /**
     * Attempt execution across multiple AI generation providers with automatic fallback.
     *
     * @template T
     *
     * @param  array<int, callable(): T>  $providers  List of provider callbacks ordered by priority
     * @return T
     *
     * @throws Throwable if all providers fail
     */
    public static function executeWithFallback(array $providers): mixed
    {
        $lastException = null;

        foreach ($providers as $index => $providerCallback) {
            try {
                return $providerCallback();
            } catch (Throwable $e) {
                $lastException = $e;
                Log::warning("AI Provider priority [{$index}] failed: {$e->getMessage()}. Attempting next fallback provider.");
            }
        }

        throw $lastException ?? new \RuntimeException('All configured AI providers failed.');
    }
}
