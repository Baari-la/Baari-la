<?php

declare(strict_types=1);

namespace App\Services\Trade\Cache;

use App\Services\Home\HomeTradeService;
use App\Services\Support\CacheService;
use App\Services\Trade\ExecutiveReport\ExecutiveReportService;
use App\Services\Trade\Metadata\TradeStatisticsMetadataService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Trade Dashboard Cache Service
 * ==========================================================================
 *
 * Central cache manager for Trade Dashboard.
 *
 * Responsible for:
 *
 * - Home Dashboard Cache
 * - Executive Report Cache
 * - Metadata Cache
 * - Cache Warmup
 * - Cache Refresh
 *
 * This service NEVER queries the database directly.
 */
class TradeDashboardCacheService
{
    /**
     * Cache Keys
     */
    protected const HOME_CACHE = 'digestex.dashboard.home';

    protected const EXECUTIVE_CACHE = 'digestex.dashboard.executive';

    protected const METADATA_CACHE = 'digestex.dashboard.metadata';

    /**
     * Cache Lifetime
     */
    protected int $dashboardTtl = 1800; // 30 minutes

    protected int $metadataTtl = 86400; // 24 hours

    public function __construct(

        protected HomeTradeService $homeService,

        protected ExecutiveReportService $executiveService,

        protected TradeStatisticsMetadataService $metadataService,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Home Dashboard
     * --------------------------------------------------------------------------
     */
    public function home(array $filters = []): array
    {
        return CacheService::remember(

            self::HOME_CACHE,

            $this->dashboardTtl,

            fn () => $this->homeService->getData()

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Report
     * --------------------------------------------------------------------------
     */
    public function executive(array $filters = []): array
    {
        return CacheService::remember(

            self::EXECUTIVE_CACHE,

            $this->dashboardTtl,

            fn () => $this->executiveService->build($filters)

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Metadata
     * --------------------------------------------------------------------------
     */
    public function metadata(): array
    {
        return CacheService::remember(

            self::METADATA_CACHE,

            $this->metadataTtl,

            fn () => $this->metadataService->build()

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Refresh All Cache
     * --------------------------------------------------------------------------
     */
    public function refresh(): void
    {
        $this->forget();

        $this->home();

        $this->executive();

        $this->metadata();
    }

    /**
     * --------------------------------------------------------------------------
     * Forget Cache
     * --------------------------------------------------------------------------
     */
    public function forget(): void
    {
        CacheService::forget(self::HOME_CACHE);

        CacheService::forget(self::EXECUTIVE_CACHE);

        CacheService::forget(self::METADATA_CACHE);
    }

    /**
     * --------------------------------------------------------------------------
     * Warmup Cache
     * --------------------------------------------------------------------------
     */
    public function warmup(): void
    {
        $this->refresh();
    }

    /**
     * --------------------------------------------------------------------------
     * Cache Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'home' => CacheService::has(self::HOME_CACHE),

            'executive' => CacheService::has(self::EXECUTIVE_CACHE),

            'metadata' => CacheService::has(self::METADATA_CACHE),

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}