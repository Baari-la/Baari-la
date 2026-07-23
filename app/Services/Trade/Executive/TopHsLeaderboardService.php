<?php

declare(strict_types=1);

namespace App\Services\Trade\Executive;

use App\Models\TradeStatistic;

class TopHsLeaderboardService
{
    /**
     * --------------------------------------------------------------------------
     * Build HS Leaderboard
     * --------------------------------------------------------------------------
     */
    public function build(
        array $filters = []
    ): array {

        $base = TradeStatistic::query()

            ->where(
                'year',
                $filters['year']
            )

            ->whereIn(
                'month',
                $filters['months']
            )

            ->where(function ($query) use ($filters) {

                foreach (
                    $filters['hs_prefix']
                    as $prefix
                ) {

                    $query->orWhere(
                        'hs_code',
                        'like',
                        $prefix.'%'
                    );
                }
            });

        return [

            'top_export_hs' =>

                $this->leaderboard(
                    clone $base,
                    'export'
                ),

            'top_import_hs' =>

                $this->leaderboard(
                    clone $base,
                    'import'
                ),

            'fastest_growing_hs' =>

                $this->fastestGrowing(
                    clone $base
                ),

            'declining_hs' =>

                $this->declining(
                    clone $base
                ),

            'executive_insight' =>

                'HS code concentration remains a critical indicator of sector competitiveness.',
        ];
    }
    /**
     * --------------------------------------------------------------------------
     * HS Leaderboard
     * --------------------------------------------------------------------------
     */
    protected function leaderboard(
        $query,
        string $tradeFlow
    ): array {

        return $query

            ->where(
                'trade_flow',
                $tradeFlow
            )

            ->selectRaw(
                '
                hs_code,
                SUM(trade_value) as total
                '
            )

            ->groupBy(
                'hs_code'
            )

            ->orderByDesc(
                'total'
            )

            ->limit(10)

            ->get()

            ->map(function (
                $row,
                $index
            ) {

                return [

                    'rank' =>

                        $index + 1,

                    'hs_code' =>

                        $row->hs_code,

                    'trade_value' =>

                        (float)
                        $row->total,
                ];
            })

            ->toArray();
    }

    /**
     * --------------------------------------------------------------------------
     * Fastest Growing
     * --------------------------------------------------------------------------
     */
    protected function fastestGrowing(
        $query
    ): array {

        // Placeholder:
        // dapat diganti YOY growth nanti.

        return [];
    }

    /**
     * --------------------------------------------------------------------------
     * Declining
     * --------------------------------------------------------------------------
     */
    protected function declining(
        $query
    ): array {

        // Placeholder:
        // dapat diganti YOY decline nanti.

        return [];
    }
}
    