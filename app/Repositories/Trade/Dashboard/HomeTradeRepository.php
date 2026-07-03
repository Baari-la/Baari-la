<?php

declare(strict_types=1);

namespace App\Repositories\Trade\Dashboard;

use Illuminate\Support\Facades\DB;

use App\Models\Company;
use App\Models\CompanyMarket;
use App\Models\CompanyProduct;
use App\Models\MarketHistory;
use App\Models\TradeStatistic;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Home Trade Repository
 * ==========================================================================
 *
 * Repository dedicated to Home Dashboard.
 *
 * Responsible for:
 *
 * - Hero Summary
 * - Trade Summary
 * - Market Highlights
 * - Fiber Intelligence
 * - Dashboard Statistics
 *
 * NOTE:
 * Repository only retrieves raw data.
 * Business logic belongs to HomeTradeService.
 *
 * Used by:
 *
 * - HomeTradeService
 * - TradeDashboardCacheService
 */
class HomeTradeRepository
{
    /**
     * --------------------------------------------------------------------------
     * Hero Summary
     * --------------------------------------------------------------------------
     */
    public function heroSummary(array $filters = []): array
    {
        $tradeFlow = $filters['trade_flow'] ?? 'export';

        return [

            'total_trade_value' => (float) TradeStatistic::query()
                ->where('trade_flow', $tradeFlow)
                ->sum('trade_value'),

            'total_records' => TradeStatistic::query()
                ->where('trade_flow', $tradeFlow)
                ->count(),

            'total_hs_codes' => TradeStatistic::query()
                ->where('trade_flow', $tradeFlow)
                ->distinct()
                ->count('hs_code'),

            'total_countries' => TradeStatistic::query()
                ->where('trade_flow', $tradeFlow)
                ->whereNotNull('country_code')
                ->distinct()
                ->count('country_code'),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Trade Summary
     * --------------------------------------------------------------------------
     *
     * Returns raw trade volume.
     * Estimated Pieces are calculated by PiecesConversionService.
     */
    public function tradeSummary(array $filters = [])
    {
        $hsPrefixes = $filters['hs_prefix'] ?? ['61', '62'];

        $query = DB::table('trade_master_annual_hscode')
            ->select([
                'tipe_arus',
                'hs_code',
                'vol_2025',
            ]);

        if (!empty($hsPrefixes)) {

            $query->where(function ($q) use ($hsPrefixes) {

                foreach ($hsPrefixes as $prefix) {

                    $q->orWhere('hs_code', 'like', $prefix . '%');

                }

            });

        }

        return $query->get();
    }

    /**
     * --------------------------------------------------------------------------
     * Market Highlights
     * --------------------------------------------------------------------------
     */
    public function marketHighlights(): array
    {
        return [

            'market_history' => MarketHistory::query()
                ->latest('date')
                ->limit(7)
                ->get(),

            'latest_market' => MarketHistory::query()
                ->latest('date')
                ->first(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Fiber Intelligence
     * --------------------------------------------------------------------------
     *
     * Placeholder.
     *
     * Future:
     * - Cotton
     * - Polyester
     * - Viscose
     * - Nylon
     * - Acrylic
     */
    public function fiberIntelligence(array $filters = []): array
    {
        return [];
    }

    /**
     * --------------------------------------------------------------------------
     * Latest Market Update
     * --------------------------------------------------------------------------
     */
    public function latestMarketUpdate()
    {
        return MarketHistory::query()
            ->latest('date')
            ->first();
    }

    /**
     * --------------------------------------------------------------------------
     * Dashboard Statistics
     * --------------------------------------------------------------------------
     */
    public function dashboardStatistics(): array
    {
        return [

            'companies' => Company::count(),

            'verified_companies' => Company::query()
                ->where('status_verifikasi', 'verified')
                ->count(),

            'products' => CompanyProduct::count(),

            'markets' => CompanyMarket::count(),

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
}