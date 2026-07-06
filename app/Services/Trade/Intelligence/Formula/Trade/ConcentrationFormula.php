<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Formula\Trade;

use App\Services\Trade\Analytics\CountryAnalyticsService;
use App\Services\Trade\Intelligence\Formula\Contracts\FormulaEngine;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Concentration Formula
 * ==========================================================================
 */
class ConcentrationFormula implements FormulaEngine
{
    public function __construct(
        protected CountryAnalyticsService $countries,
    ) {
    }

    public function calculate(array $filters = []): array
    {
        $topCountries = $this->countries->topCountries($filters);

        $largest = collect($topCountries)->first();

        return [

            'largestMarketShare' => (float) ($largest['share'] ?? 0),

            'country' => $largest['country'] ?? null,

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}