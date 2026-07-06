<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Formula\Trade;

use App\Services\Trade\Analytics\ExecutiveAnalyticsService;
use App\Services\Trade\Intelligence\Formula\Contracts\FormulaEngine;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Volatility Formula
 * ==========================================================================
 */
class VolatilityFormula implements FormulaEngine
{
    public function __construct(
        protected ExecutiveAnalyticsService $analytics,
    ) {
    }

    public function calculate(array $filters = []): array
    {
        $comparison = $this->analytics->monthlyComparison($filters);

        return [

            'volatility' => 0.0,

            'series' => $comparison,

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}