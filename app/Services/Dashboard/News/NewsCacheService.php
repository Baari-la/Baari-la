<?php

declare(strict_types=1);

namespace App\Services\Dashboard\News;

use App\Services\Support\CacheService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Dashboard News Cache Service
 * ==========================================================================
 *
 * Cache layer for Dashboard News.
 *
 * Used by:
 *
 * - HomeIntelligenceService
 * - Dashboard
 * - REST API
 */
class NewsCacheService
{
    protected const CACHE_KEY =
        'digestex.dashboard.news';

    /**
     * 15 Minutes
     */
    protected int $ttl = 900;

    public function __construct(
        protected NewsService $newsService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Cached Dataset
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        return CacheService::remember(

            self::CACHE_KEY,

            $this->ttl,

            fn () => $this->newsService->get()

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Refresh
     * --------------------------------------------------------------------------
     */
    public function refresh(): array
    {
        $this->forget();

        return $this->get();
    }

    /**
     * --------------------------------------------------------------------------
     * Forget
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
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'cache_key' => self::CACHE_KEY,

            'ttl' => $this->ttl,

            'exists' => CacheService::has(
                self::CACHE_KEY
            ),

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}