<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Services\Support\CacheService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Dashboard Statistics Cache Service
 * ==========================================================================
 *
 * Cache layer for Dashboard Statistics.
 *
 * Used by:
 *
 * - HomeController
 * - Admin Dashboard
 * - API
 */
class DashboardStatisticsCacheService
{
    /**
     * Cache Key
     */
    protected const CACHE_KEY =
        'digestex.dashboard.statistics';

    /**
     * Cache Lifetime
     */
    protected int $ttl = 3600; // 1 hour

    public function __construct(
        protected DashboardStatisticsService $statisticsService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Get Cached Statistics
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        return CacheService::remember(

            self::CACHE_KEY,

            $this->ttl,

            fn () => $this->statisticsService->get()

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
     * Cache Status
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