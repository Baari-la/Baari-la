<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Services\Support\CacheService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Market History Cache Service
 * ==========================================================================
 *
 * Cache layer for Market Intelligence.
 *
 * Used by:
 *
 * - HomeMarketService
 * - Dashboard
 * - API
 */
class MarketHistoryCacheService
{
    protected const CACHE_KEY =
        'digestex.dashboard.market-history';

    protected int $ttl = 1800; // 30 Minutes

    public function __construct(
        protected MarketHistoryService $marketService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Get Cached Market Dataset
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        return CacheService::remember(

            self::CACHE_KEY,

            $this->ttl,

            fn () => $this->marketService->get()

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
        CacheService::forget(
            self::CACHE_KEY
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Cache Status
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'cache_key' => self::CACHE_KEY,

            'cache_exists' => CacheService::has(
                self::CACHE_KEY
            ),

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}