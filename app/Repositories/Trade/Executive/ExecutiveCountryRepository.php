<?php

declare(strict_types=1);

namespace App\Repositories\Trade\Executive;

use App\Models\TradeStatistic;
use Illuminate\Support\Facades\DB;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive Country Repository
 * ==========================================================================
 *
 * Executive Report Country Analytics
 *
 * Responsible for:
 *
 * - Top Export Countries
 * - Top Import Countries
 * - Country Ranking
 * - Country Market Share
 *
 * Repository only retrieves raw data.
 *
 * Used by:
 * - ExecutiveAnalyticsService
 * - ExecutiveReportService
 */
class ExecutiveCountryRepository
{
    /**
     * --------------------------------------------------------------------------
     * Top Destination Countries
     * --------------------------------------------------------------------------
     */
    public function topCountries(array $filters = []): array
{
    $currentYear = $filters['year'] ?? now()->year;

    $previousYear = $filters['previous_year']
        ?? ($currentYear - 1);

    $tradeFlow = $filters['trade_flow'] ?? 'export';

    $months = $filters['months'] ?? [1, 2, 3, 4];

    $hsPrefixes = $filters['hs_prefix'] ?? [];

    /*
    |--------------------------------------------------------------------------
    | Export Query
    |--------------------------------------------------------------------------
    */

    $query = TradeStatistic::query()

        ->select([
            'country_code',
            'country_name',
        ])

        ->selectRaw("
            SUM(
                CASE
                    WHEN year = {$currentYear}
                    THEN trade_value
                    ELSE 0
                END
            ) AS current_value
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN year = {$previousYear}
                    THEN trade_value
                    ELSE 0
                END
            ) AS previous_value
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN year = {$currentYear}
                    THEN trade_volume
                    ELSE 0
                END
            ) AS current_volume
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN year = {$previousYear}
                    THEN trade_volume
                    ELSE 0
                END
            ) AS previous_volume
        ")

        ->where('trade_flow', $tradeFlow)

        ->whereIn('year', [
            $currentYear,
            $previousYear,
        ])

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

    /*
    |--------------------------------------------------------------------------
    | Total Export
    |--------------------------------------------------------------------------
    */

    $totalExport = (clone $query)

        ->where('year', $currentYear)
        ->sum('trade_value');
    /*
    |--------------------------------------------------------------------------
    | Import Query
    |--------------------------------------------------------------------------
    */

    $importQuery = TradeStatistic::query()

        ->select('country_code')
        ->selectRaw('SUM(trade_value) AS import_value')
        ->selectRaw('SUM(trade_volume) AS import_volume')
        ->where('trade_flow', 'import')
        ->where('year', $currentYear)
        ->whereIn('month', $months);

    if (!empty($hsPrefixes)) {

        $importQuery->where(function ($q) use ($hsPrefixes) {

            foreach ($hsPrefixes as $prefix) {

                $q->orWhere(
                    'hs_code',
                    'like',
                    $prefix . '%'
                );
            }
        });
    }

    $imports = $importQuery

        ->groupBy('country_code')

        ->get()

        ->keyBy('country_code');

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    return $query

        ->groupBy(
            'country_code',
            'country_name'
        )

        ->orderByDesc('current_value')

        ->limit(10)

        ->get()

        ->map(function ($row, $index) use (
            $totalExport,
            $imports
        ) {
                 
            /*
            |--------------------------------------------------------------------------
            | Growth
            |--------------------------------------------------------------------------
            */

            $growth = null;

            if ($row->previous_value > 0) {
                $growth = round(
                    (
                        ($row->current_value - $row->previous_value)
                        / $row->previous_value
                    ) * 100,
                    1
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Volume Growth
            |--------------------------------------------------------------------------
            */

            $volumeGrowth = null;
            if ($row->previous_volume > 0) {
                $volumeGrowth = round(

                    (
                        ($row->current_volume - $row->previous_volume)
                        / $row->previous_volume
                    ) * 100,

                    1
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Import
            |--------------------------------------------------------------------------
            */

            $import = $imports->get(
                $row->country_code
            );

            /*
            |--------------------------------------------------------------------------
            | Trade Balance
            |--------------------------------------------------------------------------
            */

            $tradeBalance =

                (float) $row->current_value

                -

                (float) (
                    $import?->import_value ?? 0
                );

            return [

                /*
                |--------------------------------------------------------------------------
                | Identity
                |--------------------------------------------------------------------------
                */

                'rank' => $index + 1,
                'country_code' => $row->country_code,
                'country_name' => $row->country_name,

                /*
                |--------------------------------------------------------------------------
                | Export
                |--------------------------------------------------------------------------
                */

                'export_value' =>

                    (float) $row->current_value,

                'export_million' =>

                    round(
                        $row->current_value / 1000000,
                        1
                    ),

                'export_volume' =>

                    (float) $row->current_volume,

                /*
                |--------------------------------------------------------------------------
                | Import
                |--------------------------------------------------------------------------
                */

                'import_value' =>

                    (float) (
                        $import?->import_value ?? 0
                    ),

                'import_volume' =>

                    (float) (
                        $import?->import_volume ?? 0
                    ),

                /*
                |--------------------------------------------------------------------------
                | Trade Balance
                |--------------------------------------------------------------------------
                */

                'trade_balance' =>

                    $tradeBalance,

                'trade_balance_million' =>

                    round(
                        $tradeBalance / 1000000,
                        1
                    ),

                /*
                |--------------------------------------------------------------------------
                | Intelligence
                |--------------------------------------------------------------------------
                */

                'share' =>

                    $totalExport > 0

                        ? round(

                            (
                                $row->current_value

                                / $totalExport

                            ) * 100,

                            2
                        )

                        : 0,

                'growth' =>
                    $growth,
                'growth_volume' =>
                    $volumeGrowth,

                /*
                |--------------------------------------------------------------------------
                | Historical
                |--------------------------------------------------------------------------
                */
                'previous_value' =>
                    (float) $row->previous_value,
                'previous_volume' =>
                    (float) $row->previous_volume,
            ];
        })
        ->values()
        ->toArray();
}


}