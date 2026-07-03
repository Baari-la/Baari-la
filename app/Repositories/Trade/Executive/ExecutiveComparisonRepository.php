<?php

declare(strict_types=1);

namespace App\Repositories\Trade\Executive;

use App\Models\TradeStatistic;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive Comparison Repository
 * ==========================================================================
 *
 * Executive Report Comparison Analytics
 *
 * Responsible for:
 * - Monthly Comparison
 * - Monthly Pieces Comparison
 *
 * Used by:
 * - ExecutiveAnalyticsService
 * - ExecutiveReportService
 */
class ExecutiveComparisonRepository
{
    /**
 * Executive Report
 *
 * Monthly Comparison Between Two Years
 */
public function monthlyComparison(array $filters = []): array
{
    
    $tradeFlow = $filters['trade_flow'] ?? 'export';

    $years = [
    $filters['base_year'] ?? now()->year - 1,
    $filters['compare_year'] ?? now()->year,
];

    $months = $filters['months'] ?? [1,2,3,4];

    $hsPrefixes = $filters['hs_prefix'] ?? [];

    $query = TradeStatistic::query()

        ->selectRaw("
            month,

            SUM(
                CASE
                    WHEN year = {$years[0]}
                    THEN trade_value
                    ELSE 0
                END
            ) AS year1,

            SUM(
                CASE
                    WHEN year = {$years[1]}
                    THEN trade_value
                    ELSE 0
                END
            ) AS year2
        ")

        ->where('trade_flow', $tradeFlow)

        ->whereIn('year', $years)

        ->whereIn('month', $months);

    /*
    |--------------------------------------------------------------------------
    | HS Prefix Filter
    |--------------------------------------------------------------------------
    */

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


    return $query

        ->groupBy('month')

        ->orderBy('month')

        ->get()

        ->map(function ($row) use ($years) {

            return [

                'month' => (int) $row->month,

                'label' => match ($row->month) {

                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'May',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Aug',
                    9 => 'Sep',
                    10 => 'Oct',
                    11 => 'Nov',
                    12 => 'Dec',

                },

                 'export2025' => (float) $row->year1,

    'export2026' => (float) $row->year2,

            ];

        })

        ->values()

        ->toArray();
}
/**
 * Monthly Comparison (Estimated Apparel Pieces)
 *
 * Convert official trade volume (KG)
 * into estimated apparel pieces using
 * Digestex Conversion Engine.
 */
public function monthlyComparisonPieces(array $filters = []): array
{
    $tradeFlow = $filters['trade_flow'] ?? 'export';

    $years = $filters['years'] ?? [
        now()->year - 1,
        now()->year,
    ];

    $months = $filters['months'] ?? [1, 2, 3, 4];

    $hsPrefixes = $filters['hs_prefix'] ?? [];

    $query = TradeStatistic::query()

        ->selectRaw("
            month,

            SUM(
                CASE
                    WHEN year = {$years[0]} THEN

                        CASE
                            WHEN hs_code LIKE '6109%' THEN trade_volume * 5.5
                            WHEN hs_code LIKE '6110%' THEN trade_volume * 2.5
                            WHEN hs_code LIKE '6203%' THEN trade_volume * 1.8
                            WHEN hs_code LIKE '6204%' THEN trade_volume * 1.8
                            WHEN hs_code LIKE '6111%' THEN trade_volume * 8.0
                            WHEN hs_code LIKE '6209%' THEN trade_volume * 8.0
                            ELSE trade_volume * 4.0
                        END

                    ELSE 0
                END
            ) AS year1,

            SUM(
                CASE
                    WHEN year = {$years[1]} THEN

                        CASE
                            WHEN hs_code LIKE '6109%' THEN trade_volume * 5.5
                            WHEN hs_code LIKE '6110%' THEN trade_volume * 2.5
                            WHEN hs_code LIKE '6203%' THEN trade_volume * 1.8
                            WHEN hs_code LIKE '6204%' THEN trade_volume * 1.8
                            WHEN hs_code LIKE '6111%' THEN trade_volume * 8.0
                            WHEN hs_code LIKE '6209%' THEN trade_volume * 8.0
                            ELSE trade_volume * 4.0
                        END

                    ELSE 0
                END
            ) AS year2
        ")

        ->where('trade_flow', $tradeFlow)

        ->whereIn('year', $years)

        ->whereIn('month', $months);

    /*
    |--------------------------------------------------------------------------
    | HS Prefix Filter
    |--------------------------------------------------------------------------
    */

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

    return $query

        ->groupBy('month')

        ->orderBy('month')

        ->get()

        ->map(function ($row) use ($years) {

            return [

                'month' => (int) $row->month,

                'label' => match ($row->month) {
                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'May',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Aug',
                    9 => 'Sep',
                    10 => 'Oct',
                    11 => 'Nov',
                    12 => 'Dec',
                },

                'pieces2025' => (float) $row->year1,

                'pieces2026' => (float) $row->year2,

            ];

        })

        ->values()

        ->toArray();
}
}