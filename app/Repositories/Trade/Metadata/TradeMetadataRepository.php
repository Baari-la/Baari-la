<?php

declare(strict_types=1);

namespace App\Repositories\Trade\Metadata;

use App\Models\TradeStatistic;

class TradeMetadataRepository
{
    public function metadata(
        string $tradeFlow = 'export'
    ): array {

        return [

            'trade_flow' => $tradeFlow,

            'latest_year' => TradeStatistic::where('trade_flow', $tradeFlow)->max('year'),

            'oldest_year' => TradeStatistic::where('trade_flow', $tradeFlow)->min('year'),

            'last_updated' => TradeStatistic::where('trade_flow', $tradeFlow)->max('updated_at'),

            'total_records' => TradeStatistic::where('trade_flow', $tradeFlow)->count(),

            'total_hs_codes' => TradeStatistic::where('trade_flow', $tradeFlow)
                ->distinct()
                ->count('hs_code'),

            'total_countries' => TradeStatistic::where('trade_flow', $tradeFlow)
                ->whereNotNull('country_code')
                ->distinct()
                ->count('country_code'),

            'total_trade_value' => (float) TradeStatistic::where('trade_flow', $tradeFlow)
                ->sum('trade_value'),

        ];
    }

    /**
 * --------------------------------------------------------------------------
 * Trade Metadata
 * --------------------------------------------------------------------------
 *
 * Single aggregate query for dashboard metadata.
 */
public function getMetadata(): array
{
    $metadata = TradeStatistic::query()

        ->selectRaw("
            MAX(year) AS latest_year,

            MIN(year) AS oldest_year,

            COUNT(*) AS total_records,

            COUNT(DISTINCT hs_code) AS total_hs_codes,

            COUNT(DISTINCT country_code) AS total_countries,

            MAX(updated_at) AS last_updated,

            SUM(
                CASE
                    WHEN trade_flow='export'
                    THEN trade_value
                    ELSE 0
                END
            ) AS export_value,
            SUM(
                CASE
                    WHEN trade_flow='import'
                    THEN trade_value
                    ELSE 0
                END
            ) AS import_value
        ")
        ->first();
    /*
    |--------------------------------------------------------------------------
    | Latest Month
    |--------------------------------------------------------------------------
    */
    $latestMonth = TradeStatistic::query()
        ->where('year', $metadata->latest_year)
        ->max('month');
    return [

        'latest_year'      => (int) $metadata->latest_year,
        'oldest_year'      => (int) $metadata->oldest_year,
        'latest_month'     => (int) $latestMonth,
        'latest_period'    => sprintf(
            '%04d-%02d',
            $metadata->latest_year,
            $latestMonth
        ),

        'total_records'    => (int) $metadata->total_records,
        'total_hs_codes'   => (int) $metadata->total_hs_codes,
        'total_countries'  => (int) $metadata->total_countries,
        'export_value'     => (float) $metadata->export_value,
        'import_value'     => (float) $metadata->import_value,
        'last_updated'     => $metadata->last_updated,

    ];
}
/**
 * Latest Available Year
 */
public function getLatestYear(
    string $tradeFlow = 'export'
): ?int {

    return TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->max('year');
}

/**
 * Oldest Available Year
 */
public function getOldestYear(
    string $tradeFlow = 'export'
): ?int {

    return TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->min('year');
}

/**
 * Latest Month
 */
public function getLatestMonth(
    string $tradeFlow = 'export'
): ?int {

    $year = $this->getLatestYear($tradeFlow);

    if (!$year) {
        return null;
    }

    return TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->where('year', $year)
        ->max('month');
}
/**
 * Total Trade Records
 */
public function getTotalRecords(
    string $tradeFlow = 'export'
): int {

    return TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->count();
}
/**
 * Total HS Codes
 */
public function getTotalHsCodes(
    string $tradeFlow = 'export'
): int {

    return TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->distinct('hs_code')
        ->count('hs_code');
}
/**
 * Total Countries
 */
public function getTotalCountries(
    string $tradeFlow = 'export'
): int {

    return TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->whereNotNull('country_code')
        ->distinct('country_code')
        ->count('country_code');
}
/**
 * Total Trade Value
 */
public function getTotalTradeValue(
    string $tradeFlow = 'export'
): float {

    return (float) TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->sum('trade_value');
}
/**
 * Last Updated
 */
public function getLastUpdated(
    string $tradeFlow = 'export'
): ?string {

    return TradeStatistic::query()
        ->where('trade_flow', $tradeFlow)
        ->max('updated_at');
}

}