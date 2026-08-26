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