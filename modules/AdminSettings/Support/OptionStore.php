<?php

namespace Modules\AdminSettings\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class OptionStore
{
    protected static ?bool $hasTable = null;
    protected static array $memoryCache = [];

    protected function cacheKey(string $key): string
    {
        return "options.v2.{$key}";
    }

    protected function optionsTableExists(): bool
    {
        if (static::$hasTable === null) {
            static::$hasTable = Schema::hasTable('options');
        }

        return static::$hasTable;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, static::$memoryCache)) {
            return static::$memoryCache[$key];
        }

        try {
            if (! $this->optionsTableExists()) {
                return $default;
            }

            $payload = Cache::rememberForever($this->cacheKey($key), function () use ($key) {
                $value = DB::table('options')->where('name', $key)->value('value');

                return [
                    'exists' => $value !== null,
                    'value' => $value,
                ];
            });

            if (! is_array($payload) || ! ($payload['exists'] ?? false)) {
                static::$memoryCache[$key] = $default;
                return $default;
            }

            static::$memoryCache[$key] = $payload['value'];
            return $payload['value'];
        } catch (Throwable) {
            return $default;
        }
    }

    public function set(string $key, mixed $value): void
    {
        try {
            if (! $this->optionsTableExists()) {
                return;
            }

            DB::table('options')->updateOrInsert(
                ['name' => $key],
                ['value' => is_scalar($value) || $value === null ? $value : json_encode($value)]
            );

            unset(static::$memoryCache[$key]);
            Cache::forget("options.{$key}");
            Cache::forget($this->cacheKey($key));
        } catch (Throwable) {
            //
        }
    }

    public function forget(string $key): void
    {
        try {
            if (! $this->optionsTableExists()) {
                return;
            }

            DB::table('options')->where('name', $key)->delete();
            unset(static::$memoryCache[$key]);
            Cache::forget("options.{$key}");
            Cache::forget($this->cacheKey($key));
        } catch (Throwable) {
            //
        }
    }
}
