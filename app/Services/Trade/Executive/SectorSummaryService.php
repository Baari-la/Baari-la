<?php

declare(strict_types=1);

namespace App\Services\Trade\Executive;

use App\Models\TradeStatistic;

class SectorSummaryService
{
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
                        $prefix . '%'
                    );
                }
            });

        /*
        |--------------------------------------------------------------------------
        | Export
        |--------------------------------------------------------------------------
        */

        $exportValue = (clone $base)

            ->where(
                'trade_flow',
                'export'
            )

            ->sum(
                'trade_value'
            );

        $exportVolume = (clone $base)

            ->where(
                'trade_flow',
                'export'
            )

            ->sum(
                'trade_volume'
            );

        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        */

        $importValue = (clone $base)

            ->where(
                'trade_flow',
                'import'
            )

            ->sum(
                'trade_value'
            );

        $importVolume = (clone $base)

            ->where(
                'trade_flow',
                'import'
            )

            ->sum(
                'trade_volume'
            );

        /*
        |--------------------------------------------------------------------------
        | Top Buyer
        |--------------------------------------------------------------------------
        */

        $topBuyer = (clone $base)

            ->where(
                'trade_flow',
                'export'
            )

            ->selectRaw('country_code, SUM(trade_value) as total')

            ->groupBy('country_code')

            ->orderByDesc('total')

            ->value('country_code');

        /*
        |--------------------------------------------------------------------------
        | Top Supplier
        |--------------------------------------------------------------------------
        */

        $topSupplier = (clone $base)

            ->where(
                'trade_flow',
                'import'
            )

            ->selectRaw('country_code, SUM(trade_value) as total')

            ->groupBy('country_code')

            ->orderByDesc('total')

            ->value('country_code');

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [

            'export_value' =>
                (float) $exportValue,

            'import_value' =>
                (float) $importValue,

            'trade_balance' =>
                (float) (
                    $exportValue - $importValue
                ),

            'export_volume' =>
                (float) $exportVolume,

            'import_volume' =>
                (float) $importVolume,

            'top_buyer' =>
                $topBuyer,

            'top_supplier' =>
                $topSupplier,

            'fastest_growing' =>
                null, // next step

        ];
    }
}