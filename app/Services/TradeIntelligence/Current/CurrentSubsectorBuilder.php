<?php

namespace App\Services\TradeIntelligence\Current;

use Illuminate\Support\Collection;

class CurrentSubsectorBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Build Subsector Performance
    |--------------------------------------------------------------------------
    |
    | Responsible only for subsector-level performance:
    |
    |   - subsector identity
    |   - localized labels
    |   - current import value
    |   - current export value
    |   - previous import value
    |   - previous export value
    |   - import growth
    |   - export growth
    |
    | This component does NOT build:
    |
    |   - executive overview
    |   - flow performance
    |   - products
    |   - countries
    |   - trends
    |   - HS-8 products
    |
    */

    public function build(
        Collection $current,
        Collection $previous
    ): array {
        return $current
            ->groupBy('subsector')
            ->map(
                function (
                    Collection $items,
                    $subsector
                ) use (
                    $previous
                ) {
                    /*
                    |--------------------------------------------------------------------------
                    | Previous Subsector Rows
                    |--------------------------------------------------------------------------
                    */

                    $previousItems =
                        $previous->where(
                            'subsector',
                            $subsector
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Current Trade Value
                    |--------------------------------------------------------------------------
                    */

                    $currentImport =
                        $this->sumByFlow(
                            $items,
                            'import'
                        );

                    $currentExport =
                        $this->sumByFlow(
                            $items,
                            'export'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Previous Trade Value
                    |--------------------------------------------------------------------------
                    */

                    $previousImport =
                        $this->sumByFlow(
                            $previousItems,
                            'import'
                        );

                    $previousExport =
                        $this->sumByFlow(
                            $previousItems,
                            'export'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | First Row
                    |--------------------------------------------------------------------------
                    |
                    | Labels are inherited from the first row belonging
                    | to the subsector, exactly as in the existing logic.
                    |
                    */

                    $first =
                        $items->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Result
                    |--------------------------------------------------------------------------
                    */

                    return [

                        'subsector' =>
                            $subsector,

                        'label_en' =>
                            $first['label_en']
                                ?? null,

                        'label_id' =>
                            $first['label_id']
                                ?? null,

                        /*
                        |--------------------------------------------------------------------------
                        | Current
                        |--------------------------------------------------------------------------
                        */

                        'import_value' =>
                            $currentImport,

                        'export_value' =>
                            $currentExport,

                        /*
                        |--------------------------------------------------------------------------
                        | Previous
                        |--------------------------------------------------------------------------
                        */

                        'import_previous_value' =>
                            $previousImport,

                        'export_previous_value' =>
                            $previousExport,

                        /*
                        |--------------------------------------------------------------------------
                        | Growth
                        |--------------------------------------------------------------------------
                        */

                        'import_growth_percent' =>
                            $this->growthPercent(
                                $currentImport,
                                $previousImport
                            ),

                        'export_growth_percent' =>
                            $this->growthPercent(
                                $currentExport,
                                $previousExport
                            ),
                    ];
                }
            )
            ->sortByDesc(
                'import_value'
            )
            ->values()
            ->all();
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
    | Growth
    |--------------------------------------------------------------------------
    |
    | Preserve the existing production behavior.
    |
    | Previous = 0:
    |
    |   Current > 0 => 100%
    |   Current = 0 => 0%
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