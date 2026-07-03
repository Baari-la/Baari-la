<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Services\Support\CacheService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Country Cache Service
 * ==========================================================================
 *
 * Cache layer for Country Master Data.
 *
 * Used by:
 *
 * - Home
 * - Dashboard
 * - API
 */
class CountryCacheService
{
    protected const CACHE_KEY =
        'digestex.dashboard.countries';

    protected int $ttl = 86400; // 24 hours

    public function __construct(
        protected CountryService $countryService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Get Cached Countries
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        return CacheService::remember(

            self::CACHE_KEY,

            $this->ttl,

            fn () => $this->countryService->get()

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Refresh Cache
     * --------------------------------------------------------------------------
     */
    public function refresh(): array
    {
        $this->forget();

        return $this->get();
    }

    /**
     * --------------------------------------------------------------------------
     * Forget Cache
     * --------------------------------------------------------------------------
     */
    public function forget(): void
    {
        CacheService::forget(self::CACHE_KEY);
    }

    /**
     * --------------------------------------------------------------------------
     * Cache Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'cache_key' => self::CACHE_KEY,

            'cache_exists' => CacheService::has(self::CACHE_KEY),

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}