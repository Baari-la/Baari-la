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
        $tradeFlow = $filters['trade_flow'] ?? 'export';

        $year = $filters['compare_year']
            ?? now()->year;

        $months = $filters['months']
            ?? [1, 2, 3, 4];

        $hsPrefixes = $filters['hs_prefix'] ?? [];

        $query = TradeStatistic::query()

            ->select([
                'country_code',
                'country_name',
            ])

            ->selectRaw('SUM(trade_value) AS export_value')

            ->where('trade_flow', $tradeFlow)

            ->where('year', $year)

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
            ->sum('trade_value');

        return $query

            ->groupBy(
                'country_code',
                'country_name'
            )

            ->orderByDesc('export_value')

            ->limit(10)

            ->get()

            ->map(function ($row, $index) use ($totalExport) {

                return [

                    'rank' => $index + 1,

                    'country_code' => $row->country_code,

                    'country_name' => $row->country_name,

                    'export_value' => (float) $row->export_value,

                    'export_million' => round(
                        $row->export_value / 1000000,
                        1
                    ),

                    'share' => $totalExport > 0
                        ? round(
                            ($row->export_value / $totalExport) * 100,
                            2
                        )
                        : 0,

                ];

            })

            ->values()

            ->toArray();
    }


}