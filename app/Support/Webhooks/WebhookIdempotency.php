<?php

namespace App\Support\Webhooks;

use Illuminate\Support\Facades\Cache;

class WebhookIdempotency
{
    /**
     * Determine if an incoming webhook event has already been processed.
     *
     * @param  string  $provider  Provider key (e.g. 'instagram', 'facebook', 'stripe')
     * @param  string  $eventId  Unique provider event ID or signature hash
     * @param  int  $ttlSeconds  Cache retention period in seconds (default 86400 / 24 hours)
     */
    public static function isProcessed(string $provider, string $eventId, int $ttlSeconds = 86400): bool
    {
        $key = 'webhook_event:'.md5($provider.':'.$eventId);

        return Cache::has($key);
    }

    /**
     * Mark an incoming webhook event as processed.
     *
     * @param  string  $provider  Provider key (e.g. 'instagram', 'facebook', 'stripe')
     * @param  string  $eventId  Unique provider event ID or signature hash
     * @param  int  $ttlSeconds  Cache retention period in seconds (default 86400 / 24 hours)
     */
    public static function markProcessed(string $provider, string $eventId, int $ttlSeconds = 86400): void
    {
        $key = 'webhook_event:'.md5($provider.':'.$eventId);
        Cache::put($key, now()->toIso8601String(), $ttlSeconds);
    }

    /**
     * Execute callback only if event has not been processed yet.
     * Returns array with 'executed' boolean and result.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return array{executed: bool, result: T|null}
     */
    public static function once(string $provider, string $eventId, callable $callback, int $ttlSeconds = 86400): array
    {
        if (static::isProcessed($provider, $eventId, $ttlSeconds)) {
            return ['executed' => false, 'result' => null];
        }

        $result = $callback();
        static::markProcessed($provider, $eventId, $ttlSeconds);

        return ['executed' => true, 'result' => $result];
    }
}
