<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Formula\Trade;

use App\Services\Trade\Analytics\CountryAnalyticsService;
use App\Services\Trade\Intelligence\Formula\Contracts\FormulaEngine;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Diversification Formula
 * ==========================================================================
 *
 * Calculates Export Market Diversification.
 */
class DiversificationFormula implements FormulaEngine
{
    public function __construct(
        protected CountryAnalyticsService $countries,
    ) {
    }

    public function calculate(array $filters = []): array
    {
        $topCountries = $this->countries->topCountries($filters);

        $topFiveShare = collect($topCountries)
            ->take(5)
            ->sum('share');

        return [

            'diversification' => round(100 - $topFiveShare, 2),

            'topFiveShare' => round($topFiveShare, 2),

            'countries' => count($topCountries),

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}