<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\MstCountry;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Country Service
 * ==========================================================================
 *
 * Business service for Country Master Data.
 *
 * Responsible for:
 *
 * - Active Countries
 * - Region Grouping
 * - Country Lookup
 *
 * No caching is performed here.
 */
class CountryService
{
    /**
     * --------------------------------------------------------------------------
     * Active Countries
     * --------------------------------------------------------------------------
     */
    public function all(): Collection
    {
        return MstCountry::query()

            ->where('is_active', true)

            ->orderBy('country_name_en')

            ->get();
    }

    /**
     * --------------------------------------------------------------------------
     * Group Countries by Region
     * --------------------------------------------------------------------------
     */
    public function groupedByRegion(): Collection
    {
        return $this->all()

            ->groupBy('region');
    }

    /**
     * --------------------------------------------------------------------------
     * Dashboard Dataset
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        return [

            'countries' => $this->all(),

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}