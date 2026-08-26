<?php

namespace App\Services\TradeIntelligence\Current;

use Illuminate\Support\Collection;

class CurrentHs8Builder
{
    /*
    |--------------------------------------------------------------------------
    | Build HS-8 Products
    |--------------------------------------------------------------------------
    |
    | Responsibilities:
    |
    | - Aggregate current-period rows by HS-8
    | - Preserve official trade value
    | - Preserve official KG volume
    | - Aggregate derived PCS
    | - Expose conversion provenance
    | - Calculate HS-level conversion status
    | - Calculate conversion coverage
    |
    | This builder NEVER:
    |
    | - derives PCS itself
    | - applies conversion factors
    | - replaces official KG volume
    | - queries the database
    |
    */

    public function build(
        Collection $rows,
        int $limit = 50
    ): array {
        return $rows
            ->groupBy('hs_code')
            ->map(
                function (
                    Collection $items,
                    $hsCode
                ): array {

                    $first =
                        $items->first();

                    /*
                    |--------------------------------------------------------------------------
                    | Official Trade Data
                    |--------------------------------------------------------------------------
                    |
                    | These remain the authoritative trade statistics.
                    |
                    */

                    $value =
                        (float) $items->sum(
                            'value'
                        );

                    $volume =
                        (float) $items->sum(
                            'volume'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Converted Rows
                    |--------------------------------------------------------------------------
                    |
                    | Only successfully converted rows contribute
                    | to derived PCS.
                    |
                    */

                    $convertedItems =
                        $this->convertedItems(
                            $items
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Derived PCS
                    |--------------------------------------------------------------------------
                    */

                    $derivedPcs =
                        $convertedItems->isNotEmpty()
                            ? (float) $convertedItems->sum(
                                'derived_pcs'
                            )
                            : null;


                    /*
                    |--------------------------------------------------------------------------
                    | Conversion Status
                    |--------------------------------------------------------------------------
                    */

                    $conversionStatus =
                        $this->resolveConversionStatus(
                            $items
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Conversion Provenance
                    |--------------------------------------------------------------------------
                    */

                    $conversionRow =
                        $this->conversionRow(
                            $items
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Conversion Coverage
                    |--------------------------------------------------------------------------
                    */

                    $totalRows =
                        $items->count();

                    $convertedRows =
                        $convertedItems->count();

                    $coveragePercent =
                        $totalRows > 0
                            ? round(
                                (
                                    $convertedRows
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
                        | HS Identity
                        |--------------------------------------------------------------------------
                        */

                        'hs_code' =>
                            $hsCode,

                        'hs4' =>
                            $first['hs4']
                            ?? null,

                        'chapter' =>
                            $first['chapter']
                            ?? null,

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
                            $first['flow']
                            ?? null,


                        /*
                        |--------------------------------------------------------------------------
                        | Official Trade Data
                        |--------------------------------------------------------------------------
                        */

                        'value' =>
                            $value,

                        'volume' =>
                            $volume,

                        'volume_unit' =>
                            'KG',


                        /*
                        |--------------------------------------------------------------------------
                        | Derived Intelligence
                        |--------------------------------------------------------------------------
                        */

                        'derived_pcs' =>
                            $derivedPcs,


                        /*
                        |--------------------------------------------------------------------------
                        | Conversion Status
                        |--------------------------------------------------------------------------
                        */

                        'conversion_status' =>
                            $conversionStatus,

                        'conversion_complete' =>
                            $this->isConversionComplete(
                                $items
                            ),

                        'conversion_rows' =>
                            $convertedRows,

                        'total_rows' =>
                            $totalRows,

                        'conversion_coverage_percent' =>
                            $coveragePercent,


                        /*
                        |--------------------------------------------------------------------------
                        | Conversion Provenance
                        |--------------------------------------------------------------------------
                        */

                        'conversion_code' =>
                            $this->conversionCode(
                                $conversionRow,
                                $conversionStatus
                            ),

                        'conversion_factor_id' =>
                            $conversionRow[
                                'conversion_factor_id'
                            ]
                            ?? null,

                        'conversion_factor' =>
                            $conversionRow[
                                'conversion_factor'
                            ]
                            ?? null,

                        'conversion_methodology' =>
                            $conversionRow[
                                'conversion_methodology'
                            ]
                            ?? 'KG_PER_PCS',

                        'conversion_factor_status' =>
                            $conversionRow[
                                'conversion_factor_status'
                            ]
                            ?? null,

                        'conversion_evidence_count' =>
                            $conversionRow[
                                'conversion_evidence_count'
                            ]
                            ?? null,

                        'conversion_total_sample_size' =>
                            $conversionRow[
                                'conversion_total_sample_size'
                            ]
                            ?? null,

                        'conversion_calculation_method' =>
                            $conversionRow[
                                'conversion_calculation_method'
                            ]
                            ?? null,
                    ];
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
    | Converted Items
    |--------------------------------------------------------------------------
    */

    protected function convertedItems(
        Collection $items
    ): Collection {
        return $items->filter(
            static function ($item): bool {

                return
                    ($item['conversion_status'] ?? null)
                        === 'CONVERTED'
                    &&
                    ($item['derived_pcs'] ?? null)
                        !== null;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Conversion Complete
    |--------------------------------------------------------------------------
    |
    | TRUE only when every row belonging to the
    | HS-8 group has been successfully converted.
    |
    */

    protected function isConversionComplete(
        Collection $items
    ): bool {

        if ($items->isEmpty()) {
            return false;
        }

        return $items->every(
            static function ($item): bool {

                return
                    ($item['conversion_status'] ?? null)
                        === 'CONVERTED'
                    &&
                    ($item['derived_pcs'] ?? null)
                        !== null;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve Conversion Status
    |--------------------------------------------------------------------------
    |
    | Rules:
    |
    |   one status
    |       -> preserve status
    |
    |   multiple statuses
    |       -> MIXED
    |
    |   no status
    |       -> NOT_CONVERTIBLE
    |
    */

    protected function resolveConversionStatus(
        Collection $items
    ): string {

        $statuses =
            $items
                ->pluck(
                    'conversion_status'
                )
                ->filter(
                    static fn ($status) =>
                        $status !== null
                        &&
                        trim((string) $status) !== ''
                )
                ->unique()
                ->values();


        if ($statuses->count() === 1) {
            return (string) $statuses->first();
        }

        if ($statuses->count() > 1) {
            return 'MIXED';
        }

        return 'NOT_CONVERTIBLE';
    }


    /*
    |--------------------------------------------------------------------------
    | Conversion Row
    |--------------------------------------------------------------------------
    |
    | Select one successfully resolved conversion row
    | as provenance for the HS-8 result.
    |
    */

    protected function conversionRow(
        Collection $items
    ): ?array {

        $row =
            $items->first(
                static function ($item): bool {

                    return
                        ($item['conversion_status'] ?? null)
                            === 'CONVERTED'
                        &&
                        ($item['conversion_factor_id'] ?? null)
                            !== null;
                }
            );

        return $row
            ? (array) $row
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | Conversion Code
    |--------------------------------------------------------------------------
    */

    protected function conversionCode(
        ?array $conversionRow,
        string $conversionStatus
    ): ?string {

        if ($conversionRow !== null) {

            return
                $conversionRow['conversion_code']
                ?? null;
        }

        if (
            $conversionStatus ===
            'NOT_CONVERTIBLE'
        ) {
            return 'NO_ACTIVE_FACTOR';
        }

        return null;
    }
}