<?php

namespace App\Repositories\Trade;

use App\Models\TradeStatistic;
use Illuminate\Support\Facades\DB;

class TradeStatisticsRepository
{
    /**
     * Dashboard Summary
     */
    public function summary(array $filters = []): array
    {
        $query = TradeStatistic::query();

        if (!empty($filters['trade_flow'])) {
            $query->where('trade_flow', $filters['trade_flow']);
        }

        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        return [

            'records' => (clone $query)->count(),

            'countries' => (clone $query)
                ->distinct('country_code')
                ->count('country_code'),

            'hsCodes' => (clone $query)
                ->distinct('hs_code')
                ->count('hs_code'),

            'exportValue' => (clone $query)
                ->where('trade_flow', 'export')
                ->sum('trade_value'),

            'importValue' => (clone $query)
                ->where('trade_flow', 'import')
                ->sum('trade_value'),

            'lastUpdate' => optional(
                (clone $query)->max('updated_at')
            )?->format('d M Y H:i'),

            'source' => 'Kemendag RI',

            'coverage' => (
                (clone $query)->min('year')
            ) . ' - ' . (
                (clone $query)->max('year')
            ),

        ];
    }

    /**
     * Monthly Export vs Import Trend
     */
    public function monthlyTrend(array $filters = [])
    {
        $query = TradeStatistic::query();

        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        return $query
            ->select(
                'year',
                'month',
                'trade_flow',
                DB::raw('SUM(trade_value) as trade_value')
            )
            ->groupBy(
                'year',
                'month',
                'trade_flow'
            )
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    /**
     * Top Export & Import Countries
     */
    public function topCountries(array $filters = [])
    {
        $query = TradeStatistic::query();

        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        return $query
            ->select(
    'trade_flow',
    'country_code',
    DB::raw('SUM(trade_value) as trade_value')
)
            ->groupBy(
                'trade_flow',
                'country_code',
         )
            ->orderByDesc('trade_value')
            ->limit(10)
            ->get();
    }

    /**
     * Top HS Code
     */
    public function topHsCodes(array $filters = [])
    {
        $query = TradeStatistic::query();

        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        return $query
            ->select(
                'trade_flow',
                'hs_code',
                'hs_description',
                DB::raw('SUM(trade_value) as trade_value')
            )
            ->groupBy(
                'trade_flow',
                'hs_code',
                'hs_description'
            )
            ->orderByDesc('trade_value')
            ->limit(10)
            ->get();
    }

    public function getMonthlyTrend(int $year): array
{
    return TradeStatistic::query()
        ->selectRaw("
    country_code,
    SUM(trade_value) as export_value
")
        ->where('year', $year)
        ->groupBy('month', 'trade_flow')
        ->orderBy('month')
        ->get()
        ->groupBy('month')
        ->map(function ($rows) {

            return [

                'month' => $rows->first()->month,

                'export' => optional(
                    $rows->firstWhere('trade_flow', 'export')
                )->trade_value ?? 0,

                'import' => optional(
                    $rows->firstWhere('trade_flow', 'import')
                )->trade_value ?? 0,

                'export_volume' => optional(
                    $rows->firstWhere('trade_flow', 'export')
                )->trade_volume ?? 0,

                'import_volume' => optional(
                    $rows->firstWhere('trade_flow', 'import')
                )->trade_volume ?? 0,

            ];

        })
        ->values()
        ->toArray();
}
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
/**
 * Top Export Destination Countries
 * Apparel HS 61–63
 */
public function topGarmentExportCountries(array $filters = []): array
{
    $year = $filters['compare_year'] ?? now()->year;
    $months = $filters['months'] ?? [1, 2, 3, 4];
    $tradeFlow = $filters['trade_flow'] ?? 'export';
    $hsPrefixes = $filters['hs_prefix'] ?? ['61', '62', '63'];

    $query = TradeStatistic::query()

        ->selectRaw("
            country_code,
            SUM(trade_value) as export_value
        ")
        ->where('trade_flow', $tradeFlow)
        ->where('year', $year)
        ->whereIn('month', $months);

    /*
    |--------------------------------------------------------------------------
    | HS Prefix Filter
    |--------------------------------------------------------------------------
    */
    $query->where(function ($q) use ($hsPrefixes) {

        foreach ($hsPrefixes as $prefix) {
            $q->orWhere(
                'hs_code',
                'like',
                $prefix . '%'
            );
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Total Export (untuk menghitung share)
    |--------------------------------------------------------------------------
    */
    $totalExport = (clone $query)->sum('trade_value');

    return $query

       ->groupBy('country_code')

        ->orderByDesc('export_value')
        ->limit(5)
        ->get()
        ->values()
        ->map(function ($row, $index) use ($totalExport) {
   return [
    'rank' => $index + 1,
    'country_code' => $row->country_code,
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
        ->toArray();
}
/**
 * Top Garment Products
 */
public function topGarmentProducts(array $filters = []): array
{
    $year = $filters['compare_year'] ?? now()->year;

    $months = $filters['months'] ?? [1,2,3,4];

    $tradeFlow = $filters['trade_flow'] ?? 'export';

    $hsPrefixes = $filters['hs_prefix'] ?? ['61','62','63'];

    $query = TradeStatistic::query()
        ->selectRaw("
            hs_code,
            hs_description,
            SUM(trade_value) as export_value
        ")
        ->where('trade_flow', $tradeFlow)
        ->where('year', $year)
        ->whereIn('month', $months);

    $query->where(function ($q) use ($hsPrefixes) {

        foreach ($hsPrefixes as $prefix) {

            $q->orWhere('hs_code', 'like', $prefix.'%');

        }

    });

    $totalExport = (clone $query)->sum('trade_value');

    return $query
        ->groupBy('hs_code', 'hs_description')
        ->orderByDesc('export_value')
        ->limit(10)
        ->get()
        ->values()
        ->map(function ($row, $index) use ($totalExport) {

            return [

                'rank' => $index + 1,

                'hs_code' => $row->hs_code,

                'product' => $row->hs_description,

                'export_value' => (float) $row->export_value,

                'export_million' => round($row->export_value / 1000000, 1),

                'share' => $totalExport > 0
                    ? round(($row->export_value / $totalExport) * 100, 2)
                    : 0,

            ];

        })
        ->toArray();
}
public function topGarmentCountries(array $filters = [])
{
    return $this->repository
        ->topGarmentExportCountries($filters);
}


}