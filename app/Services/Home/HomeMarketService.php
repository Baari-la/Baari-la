<?php

declare(strict_types=1);

namespace App\Services\Home;

use App\Services\Dashboard\MarketHistoryCacheService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Home Market Service
 * ==========================================================================
 *
 * Provides Market Intelligence dataset for Home Dashboard.
 *
 * Responsibilities:
 *
 * - Latest Cotton Price
 * - Exchange Rate
 * - Market History
 * - Inventory Snapshot
 *
 * This service NEVER queries the database directly.
 * All data is retrieved from MarketHistoryCacheService.
 *
 * Used by:
 *
 * - HomeController
 */
class HomeMarketService
{
    public function __construct(
        protected MarketHistoryCacheService $marketCache,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Home Market Dataset
     * --------------------------------------------------------------------------
     */
    public function getData(): array
    {
        return $this->marketCache->get();
    }
}