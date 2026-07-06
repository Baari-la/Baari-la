<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Formula\Trade;

use App\Services\Trade\Analytics\ExecutiveAnalyticsService;
use App\Services\Trade\Intelligence\Formula\Contracts\FormulaEngine;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Growth Formula
 * ==========================================================================
 *
 * Calculates trade growth metrics.
 *
 * This class is responsible ONLY for business calculation.
 *
 * It DOES NOT:
 *
 * - Calculate score
 * - Determine status
 * - Produce AI narrative
 *
 * Used by:
 *
 * - GrowthScoreService
 * - TradeRadarService
 * - ExecutiveSummaryService
 */
class GrowthFormula implements FormulaEngine
{
    public function __construct(
        protected ExecutiveAnalyticsService $analytics,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Calculate Growth Metric
     * --------------------------------------------------------------------------
     *
     * Returns raw business metrics.
     */
    public function calculate(array $filters = []): array
    {
        /*
        |--------------------------------------------------------------------------
        | Executive Summary Dataset
        |--------------------------------------------------------------------------
        */

        $summary = $this->analytics->summary($filters);

        /*
        |--------------------------------------------------------------------------
        | Base & Compare Values
        |--------------------------------------------------------------------------
        */

        $baseValue = (float) ($summary['baseValue'] ?? 0);

        $compareValue = (float) ($summary['compareValue'] ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Growth Calculation
        |--------------------------------------------------------------------------
        */

        $changeValue = $compareValue - $baseValue;

        $growth = $baseValue > 0
            ? ($changeValue / $baseValue) * 100
            : 0;

        return [

            /*
            |--------------------------------------------------------------------------
            | Business Metric
            |--------------------------------------------------------------------------
            */

            'growth' => round($growth, 2),

            /*
            |--------------------------------------------------------------------------
            | Supporting Values
            |--------------------------------------------------------------------------
            */

            'baseValue' => $baseValue,

            'compareValue' => $compareValue,

            'changeValue' => $changeValue,

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}