<?php

namespace App\Services\TradeIntelligence\Current;

use Illuminate\Support\Collection;

class CurrentProductBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Build Top Products
    |--------------------------------------------------------------------------
    |
    | Responsible only for product-level intelligence:
    |
    |   - HS-4 aggregation
    |   - trade value
    |   - official KG
    |   - derived PCS
    |   - conversion status
    |   - conversion coverage
    |
    | This component does NOT:
    |
    |   - perform HS conversion
    |   - build countries
    |   - build subsectors
    |   - build flow performance
    |   - build trends
    |   - build HS-8 products
    |
    */

    public function build(
        Collection $rows,
        string $flow,
        int $limit = 10
    ): array {
        return $rows
            ->filter(
                static function ($row) use ($flow) {
                    return (
                        ($row['flow'] ?? null)
                        === $flow
                    );
                }
            )
            ->groupBy('hs4')
            ->map(
                function (
                    Collection $items,
                    $hs4
                ) use (
                    $flow
                ) {
                    return $this->buildProduct(
                        $items,
                        $hs4,
                        $flow
                    );
                }
            )
            ->sortByDesc(
                'value'
            )
            ->take(
                $limit
            )
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Build HS-4 Product
    |--------------------------------------------------------------------------
    */

    protected function buildProduct(
        Collection $items,
        $hs4,
        string $flow
    ): array {
        /*
        |--------------------------------------------------------------------------
        | First Row
        |--------------------------------------------------------------------------
        */

        $first =
            $items->first();


        /*
        |--------------------------------------------------------------------------
        | Conversion Complete
        |--------------------------------------------------------------------------
        |
        | The HS-4 product is considered completely converted
        | only when EVERY underlying trade row has:
        |
        |   conversion_status = CONVERTED
        |
        | and:
        |
        |   derived_pcs != null
        |
        */

        $conversionComplete =
            $items->every(
                static function ($item) {
                    return
                        ($item['conversion_status'] ?? null)
                            === 'CONVERTED'
                        &&
                        ($item['derived_pcs'] ?? null)
                            !== null;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Converted Rows
        |--------------------------------------------------------------------------
        */

        $convertedRows =
            $items->filter(
                static function ($item) {
                    return
                        ($item['conversion_status'] ?? null)
                            === 'CONVERTED'
                        &&
                        ($item['derived_pcs'] ?? null)
                            !== null;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Official Trade Value
        |--------------------------------------------------------------------------
        */

        $value =
            (float) $items->sum(
                'value'
            );


        /*
        |--------------------------------------------------------------------------
        | Official Physical Volume
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Official KG remains authoritative.
        | Derived PCS never replaces official volume.
        |
        */

        $volume =
            (float) $items->sum(
                'volume'
            );


        /*
        |--------------------------------------------------------------------------
        | Derived PCS
        |--------------------------------------------------------------------------
        |
        | PCS is returned only when the entire HS-4 group
        | has successfully converted.
        |
        | This preserves the existing behavior.
        |
        */

        $derivedPcs =
            $conversionComplete
                ? (float) $convertedRows->sum(
                    'derived_pcs'
                )
                : null;


        /*
        |--------------------------------------------------------------------------
        | Conversion Coverage
        |--------------------------------------------------------------------------
        */

        $totalRows =
            $items->count();

        $convertedCount =
            $convertedRows->count();


        $coveragePercent =
            $totalRows > 0
                ? round(
                    (
                        $convertedCount
                        /
                        $totalRows
                    ) * 100,
                    2
                )
                : 0.0;


        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Product Identity
            |--------------------------------------------------------------------------
            */

            'hs4' =>
                $hs4,

            'description' =>
                $first['description']
                    ?? null,

            'subsector' =>
                $first['subsector']
                    ?? null,

            'label_en' =>
                $first['label_en']
                    ?? null,

            'label_id' =>
                $first['label_id']
                    ?? null,
                    
            'flow' =>
                $flow,


            /*
            |--------------------------------------------------------------------------
            | Official Trade Data
            |--------------------------------------------------------------------------
            */

            'value' =>
                $value,

            'volume' =>
                $volume,


            /*
            |--------------------------------------------------------------------------
            | HS-8 Derived PCS
            |--------------------------------------------------------------------------
            */

            'derived_pcs' =>
                $derivedPcs,


            /*
            |--------------------------------------------------------------------------
            | Conversion Coverage
            |--------------------------------------------------------------------------
            */

            'conversion_complete' =>
                $conversionComplete,

            'conversion_rows' =>
                $convertedCount,

            'total_rows' =>
                $totalRows,

            'conversion_coverage_percent' =>
                $coveragePercent,
        ];
    }
}