<?php

declare(strict_types=1);

namespace App\Services\Home;

use App\Services\Dashboard\DashboardStatisticsCacheService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Home Directory Service
 * ==========================================================================
 *
 * Provides directory statistics for Home Dashboard.
 *
 * Responsibilities:
 *
 * - Company Statistics
 * - Product Statistics
 * - Market Statistics
 *
 * This service does NOT query the database directly.
 * All statistics are retrieved from DashboardStatisticsCacheService.
 *
 * Used by:
 *
 * - HomeController
 */
class HomeDirectoryService
{
    public function __construct(
        protected DashboardStatisticsCacheService $statisticsCache,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Home Directory Dataset
     * --------------------------------------------------------------------------
     */
    public function getData(): array
    {
        return [

            'directoryStats' => $this->statisticsCache->get(),

        ];
    }
}