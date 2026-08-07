<?php

namespace App\Support\Queue;

use Illuminate\Support\Facades\Cache;
use RuntimeException;

class QueueRateLimiter
{
    /**
     * Throttle execution of a callback using atomic cache keys.
     *
     * @template T
     *
     * @param  string  $key  Unique rate limit key (e.g. 'meta-api:user-12')
     * @param  int  $maxAttempts  Maximum allowed attempts in decay window
     * @param  int  $decaySeconds  Window size in seconds
     * @param  callable(): T  $callback  Code block to execute if allowed
     * @return T
     *
     * @throws RuntimeException if rate limit exceeded
     */
    public static function throttle(string $key, int $maxAttempts, int $decaySeconds, callable $callback): mixed
    {
        $cacheKey = 'rate_limit:'.md5($key);
        $hits = (int) Cache::get($cacheKey, 0);

        if ($hits >= $maxAttempts) {
            throw new RuntimeException("Rate limit exceeded for key [{$key}]. Max attempts: {$maxAttempts} per {$decaySeconds}s.");
        }

        Cache::add($cacheKey, 0, $decaySeconds);
        Cache::increment($cacheKey);

        return $callback();
    }

    /**
     * Clear rate limit counter for a specific key.
     */
    public static function clear(string $key): void
    {
        $cacheKey = 'rate_limit:'.md5($key);
        Cache::forget($cacheKey);
    }
}
