<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\Company;
use App\Models\CompanyMarket;
use App\Models\CompanyProduct;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Dashboard Statistics Service
 * ==========================================================================
 *
 * Centralized Dashboard Statistics.
 *
 * Responsible for:
 *
 * - Company Statistics
 * - Product Statistics
 * - Market Statistics
 *
 * No caching is performed here.
 * Caching is handled by DashboardStatisticsCacheService.
 */
class DashboardStatisticsService
{
    /**
     * --------------------------------------------------------------------------
     * Dashboard Statistics
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        return [

            'companies' => Company::count(),

            'verifiedCompanies' => Company::query()
                ->where('status_verifikasi', 'verified')
                ->count(),

            'pendingCompanies' => Company::query()
                ->where('status_verifikasi', 'pending')
                ->count(),

            'products' => CompanyProduct::count(),

            'markets' => CompanyMarket::count(),

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}