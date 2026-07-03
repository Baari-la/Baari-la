<?php

declare(strict_types=1);

namespace App\Repositories\Trade\Executive;

use App\Models\TradeStatistic;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive Summary Repository
 * ==========================================================================
 *
 * Executive KPI Repository.
 *
 * Responsible for:
 *
 * - Executive KPI Summary
 * - Export Value
 * - Import Value
 * - Trade Balance
 * - Growth Summary
 *
 * Repository only retrieves raw data.
 *
 * Used by:
 *
 * - TradeAnalyticsService
 * - ExecutiveAnalyticsService
 * - ExecutiveReportService
 */
class ExecutiveSummaryRepository
{
    /**
     * --------------------------------------------------------------------------
     * Executive KPI Summary
     * --------------------------------------------------------------------------
     */
    public function summary(array $filters = []): array
    {
        $tradeFlow = $filters['trade_flow'] ?? 'export';

        $baseYear = $filters['base_year']
            ?? now()->year - 1;

        $compareYear = $filters['compare_year']
            ?? now()->year;

        $months = $filters['months']
            ?? [1, 2, 3, 4];

        $hsPrefixes = $filters['hs_prefix'] ?? [];

        $query = TradeStatistic::query()

            ->where('trade_flow', $tradeFlow)

            ->whereIn('year', [
                $baseYear,
                $compareYear,
            ])

            ->whereIn('month', $months);

        if (!empty($hsPrefixes)) {

            $query->where(function ($q) use ($hsPrefixes) {

                foreach ($hsPrefixes as $prefix) {

                    $q->orWhere(
                        'hs_code',
                        'like',
                        $prefix . '%'
                    );

                }

            });

        }

        $baseValue = (clone $query)

            ->where('year', $baseYear)

            ->sum('trade_value');

        $compareValue = (clone $query)

            ->where('year', $compareYear)

            ->sum('trade_value');

        $growth = $baseValue > 0
            ? (($compareValue - $baseValue) / $baseValue) * 100
            : 0;

        return [

            'base_year' => $baseYear,

            'compare_year' => $compareYear,

            'base_value' => (float) $baseValue,

            'compare_value' => (float) $compareValue,

            'growth' => round($growth, 2),

            'difference' => (float) ($compareValue - $baseValue),

        ];
    }
   

}