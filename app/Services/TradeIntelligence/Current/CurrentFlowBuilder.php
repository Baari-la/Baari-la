<?php

namespace App\Services\TradeIntelligence\Current;

use Illuminate\Support\Collection;

class CurrentFlowBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Build Flow Performance
    |--------------------------------------------------------------------------
    |
    | Responsible only for flow-level performance:
    |
    |   - import
    |   - export
    |   - current trade value
    |   - previous trade value
    |   - growth percentage
    |
    | This component does NOT build:
    |
    |   - executive overview
    |   - subsectors
    |   - products
    |   - countries
    |   - market share
    |   - trends
    |   - HS-8 intelligence
    |
    */

    public function build(
        Collection $current,
        Collection $previous
    ): array {
        $result = [];

        /*
        |--------------------------------------------------------------------------
        | Supported Trade Flows
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'import',
                'export',
            ] as $flow
        ) {

            /*
            |--------------------------------------------------------------------------
            | Current Value
            |--------------------------------------------------------------------------
            */

            $currentValue =
                $this->sumByFlow(
                    $current,
                    $flow
                );


            /*
            |--------------------------------------------------------------------------
            | Previous Value
            |--------------------------------------------------------------------------
            */

            $previousValue =
                $this->sumByFlow(
                    $previous,
                    $flow
                );


            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */

            $result[] = [

                'flow' =>
                    $flow,

                'value' =>
                    $currentValue,

                'previous_value' =>
                    $previousValue,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentValue,
                        $previousValue
                    ),
            ];
        }

        return $result;
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
    | Preserve existing production behavior.
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