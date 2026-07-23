<?php

declare(strict_types=1);

namespace App\Services\Trade\Executive;

use App\Models\TradeStatistic;

class ExecutiveExportMonitorService
{
    /**
     * --------------------------------------------------------------------------
     * Build Executive Export Monitor
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

        $topExports =

            $this->topHs(
                clone $base,
                'export'
            );

        $topImports =

            $this->topHs(
                clone $base,
                'import'
            );

        return [

            'top_exports' =>

                $topExports,

            'top_imports' =>

                $topImports,

            'largest_export_hs' =>

                $topExports[0]
                ?? null,

            'largest_import_hs' =>

                $topImports[0]
                ?? null,

            'executive_insight' =>

                $this->insight(
                    $topExports,
                    $topImports
                ),
        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Top HS
     * --------------------------------------------------------------------------
     */
    protected function topHs(
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

            ->limit(5)

            ->get()

            ->map(function ($row) {

                return [

                    'hs_code' =>

                        $row->hs_code,

                    'trade_value' =>

                        (float) $row->total,
                ];
            })

            ->toArray();
    }

    /**
     * --------------------------------------------------------------------------
     * AI Insight
     * --------------------------------------------------------------------------
     */
    protected function insight(
        array $exports,
        array $imports
    ): string {

        $export = $exports[0]['hs_code']
            ?? 'N/A';

        $import = $imports[0]['hs_code']
            ?? 'N/A';

        return sprintf(
            'HS %s remains the largest export contributor while HS %s represents the largest import dependency.',
            $export,
            $import
        );
    }
}