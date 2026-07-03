<?php

declare(strict_types=1);

namespace App\Services\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * DIGESTEX CORE
 * --------------------------------------------------------------------------
 * Cache Service
 * --------------------------------------------------------------------------
 * Standard cache wrapper used across:
 * - Dashboard
 * - Executive Report
 * - Trade Intelligence
 * - Company Intelligence
 */
class CacheService
{
    /**
     * Remember Cache
     */
    public static function remember(
        string $key,
        int $seconds,
        Closure $callback
    ): mixed {

        return Cache::remember(
            $key,
            now()->addSeconds($seconds),
            $callback
        );
    }

    /**
     * Store Cache Forever
     */
    public static function forever(
        string $key,
        mixed $value
    ): void {

        Cache::forever($key, $value);
    }

    /**
     * Get Cache
     */
    public static function get(
        string $key,
        mixed $default = null
    ): mixed {

        return Cache::get($key, $default);
    }

    /**
     * Put Cache
     */
    public static function put(
        string $key,
        mixed $value,
        int $seconds = 3600
    ): void {

        Cache::put(
            $key,
            $value,
            now()->addSeconds($seconds)
        );
    }

    /**
     * Forget Cache
     */
    public static function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Clear All Cache
     */
    public static function flush(): void
    {
        Cache::flush();
    }

    /**
     * Cache Exists?
     */
    public static function has(string $key): bool
    {
        return Cache::has($key);
    }
}