<?php

namespace App\Services\TradeIntelligence\Current;

use Illuminate\Support\Collection;

class CurrentOverviewBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Build Executive Overview
    |--------------------------------------------------------------------------
    |
    | Responsible only for:
    |
    |   - Import trade value
    |   - Export trade value
    |   - Import / export growth
    |   - Official KG volume
    |   - KG volume growth
    |   - Derived PCS intelligence
    |   - PCS conversion coverage
    |
    | This component does NOT:
    |
    |   - build products
    |   - build countries
    |   - build subsectors
    |   - build trends
    |   - build HS-8 products
    |   - perform PCS conversion
    |
    | PCS conversion must already have been performed
    | by the upstream trade-data normalization layer.
    |
    */

    public function build(
        Collection $current,
        Collection $previous,
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        */

        $currentImport =
            $this->sumByFlow(
                $current,
                'import'
            );

        $previousImport =
            $this->sumByFlow(
                $previous,
                'import'
            );

        $currentImportKg =
            $this->sumVolumeKgByFlow(
                $current,
                'import'
            );

        $previousImportKg =
            $this->sumVolumeKgByFlow(
                $previous,
                'import'
            );

        $currentImportPcs =
            $this->sumDerivedPcsByFlow(
                $current,
                'import'
            );

        $previousImportPcs =
            $this->sumDerivedPcsByFlow(
                $previous,
                'import'
            );


        /*
        |--------------------------------------------------------------------------
        | Export
        |--------------------------------------------------------------------------
        */

        $currentExport =
            $this->sumByFlow(
                $current,
                'export'
            );

        $previousExport =
            $this->sumByFlow(
                $previous,
                'export'
            );

        $currentExportKg =
            $this->sumVolumeKgByFlow(
                $current,
                'export'
            );

        $previousExportKg =
            $this->sumVolumeKgByFlow(
                $previous,
                'export'
            );

        $currentExportPcs =
            $this->sumDerivedPcsByFlow(
                $current,
                'export'
            );

        $previousExportPcs =
            $this->sumDerivedPcsByFlow(
                $previous,
                'export'
            );


        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [

            'import' => [

                /*
                |--------------------------------------------------------------------------
                | Trade Value
                |--------------------------------------------------------------------------
                */

                'current' =>
                    $currentImport,

                'previous' =>
                    $previousImport,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentImport,
                        $previousImport
                    ),


                /*
                |--------------------------------------------------------------------------
                | Official KG
                |--------------------------------------------------------------------------
                */

                'physical_volume_kg' =>
                    $currentImportKg,

                'previous_physical_volume_kg' =>
                    $previousImportKg,

                'physical_volume_growth_percent' =>
                    $this->growthPercent(
                        $currentImportKg,
                        $previousImportKg
                    ),


                /*
                |--------------------------------------------------------------------------
                | Derived PCS
                |--------------------------------------------------------------------------
                */

                'physical_volume_pcs' =>
                    $currentImportPcs['pcs'],

                'previous_physical_volume_pcs' =>
                    $previousImportPcs['pcs'],

                'physical_volume_coverage_percent' =>
                    $currentImportPcs['coverage_percent'],

                'physical_volume_converted_rows' =>
                    $currentImportPcs['converted_rows'],

                'physical_volume_total_rows' =>
                    $currentImportPcs['total_rows'],
            ],


            'export' => [

                /*
                |--------------------------------------------------------------------------
                | Trade Value
                |--------------------------------------------------------------------------
                */

                'current' =>
                    $currentExport,

                'previous' =>
                    $previousExport,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentExport,
                        $previousExport
                    ),


                /*
                |--------------------------------------------------------------------------
                | Official KG
                |--------------------------------------------------------------------------
                */

                'physical_volume_kg' =>
                    $currentExportKg,

                'previous_physical_volume_kg' =>
                    $previousExportKg,

                'physical_volume_growth_percent' =>
                    $this->growthPercent(
                        $currentExportKg,
                        $previousExportKg
                    ),


                /*
                |--------------------------------------------------------------------------
                | Derived PCS
                |--------------------------------------------------------------------------
                */

                'physical_volume_pcs' =>
                    $currentExportPcs['pcs'],

                'previous_physical_volume_pcs' =>
                    $previousExportPcs['pcs'],

                'physical_volume_coverage_percent' =>
                    $currentExportPcs['coverage_percent'],

                'physical_volume_converted_rows' =>
                    $currentExportPcs['converted_rows'],

                'physical_volume_total_rows' =>
                    $currentExportPcs['total_rows'],
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Sum Trade Value By Flow
    |--------------------------------------------------------------------------
    */

    protected function sumByFlow(
        Collection $rows,
        string $flow
    ): float {
        return (float) $rows
            ->filter(
                static function ($row) use ($flow) {
                    return (
                        ($row['flow'] ?? null)
                        === $flow
                    );
                }
            )
            ->sum(
                'value'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Sum Official KG By Flow
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | Official trade volume remains authoritative.
    | Derived PCS never replaces KG.
    |
    */

    protected function sumVolumeKgByFlow(
        Collection $rows,
        string $flow
    ): float {
        return (float) $rows
            ->filter(
                static function ($row) use ($flow) {
                    return (
                        ($row['flow'] ?? null)
                        === $flow
                    );
                }
            )
            ->sum(
                static function ($row) {
                    return (float) (
                        $row['volume']
                        ?? 0
                    );
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Sum Derived PCS By Flow
    |--------------------------------------------------------------------------
    |
    | This method ONLY aggregates PCS that have already
    | been validated and converted upstream.
    |
    | It NEVER calculates PCS itself.
    |
    */

    protected function sumDerivedPcsByFlow(
        Collection $rows,
        string $flow
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Filter Flow
        |--------------------------------------------------------------------------
        */

        $flowRows =
            $rows->filter(
                static function ($row) use ($flow) {
                    return (
                        ($row['flow'] ?? null)
                        === $flow
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Valid Converted Rows
        |--------------------------------------------------------------------------
        */

        $convertedRows =
            $flowRows->filter(
                static function ($row) {

                    return
                        ($row['conversion_status'] ?? null)
                            === 'CONVERTED'
                        &&
                        ($row['derived_pcs'] ?? null)
                            !== null;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Coverage
        |--------------------------------------------------------------------------
        */

        $totalRows =
            $flowRows->count();

        $convertedCount =
            $convertedRows->count();


        /*
        |--------------------------------------------------------------------------
        | PCS
        |--------------------------------------------------------------------------
        */

        $pcs =
            $convertedCount > 0
                ? (float) $convertedRows->sum(
                    static function ($row) {
                        return (float) (
                            $row['derived_pcs']
                            ?? 0
                        );
                    }
                )
                : null;


        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return [

            'pcs' =>
                $pcs,

            'converted_rows' =>
                $convertedCount,

            'total_rows' =>
                $totalRows,

            'coverage_percent' =>
                $totalRows > 0
                    ? round(
                        (
                            $convertedCount
                            /
                            $totalRows
                        ) * 100,
                        2
                    )
                    : 0.0,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Growth
    |--------------------------------------------------------------------------
    |
    | Preserve the existing production behavior:
    |
    |   previous = 0
    |   current  > 0
    |       => 100%
    |
    |   previous = 0
    |   current  = 0
    |       => 0%
    |
    */

    protected function growthPercent(
        float $current,
        float $previous
    ): float {
        if ($previous == 0.0) {
            return $current > 0.0
                ? 100.0
                : 0.0;
        }

        return round(
            (
                (
                    $current
                    - $previous
                )
                / $previous
            ) * 100,
            2
        );
    }
}