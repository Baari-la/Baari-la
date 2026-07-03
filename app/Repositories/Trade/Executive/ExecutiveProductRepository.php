<?php

declare(strict_types=1);

namespace App\Repositories\Trade\Executive;

use App\Models\TradeStatistic;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive Product Repository
 * ==========================================================================
 *
 * Executive Report Product Analytics
 *
 * Responsible for:
 * - Top Products
 * - HS Ranking
 *
 * Used by:
 * - ExecutiveAnalyticsService
 * - ExecutiveReportService
 */
class ExecutiveProductRepository
{
    /**
     * Top Products
     */
    public function topProducts(array $filters = []): array
{
    $year = $filters['compare_year'] ?? now()->year;

    $months = $filters['months'] ?? [1, 2, 3, 4];

    $tradeFlow = $filters['trade_flow'] ?? 'export';

    $hsPrefixes = $filters['hs_prefix'] ?? ['61', '62', '63'];

    $query = TradeStatistic::query()
        ->selectRaw("
            hs_code,
            hs_description,
            SUM(trade_value) AS export_value
        ")
        ->where('trade_flow', $tradeFlow)
        ->where('year', $year)
        ->whereIn('month', $months);

    $query->where(function ($q) use ($hsPrefixes) {

        foreach ($hsPrefixes as $prefix) {
            $q->orWhere('hs_code', 'like', $prefix . '%');
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

                'export_million' => round($row->export_value / 1_000_000, 1),

                'share' => $totalExport > 0
                    ? round(($row->export_value / $totalExport) * 100, 2)
                    : 0,

            ];

        })
        ->toArray();
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
}