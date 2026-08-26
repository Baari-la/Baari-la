<?php

namespace App\Services\Trade\Historical;

use Illuminate\Support\Collection;

class HistoricalYearlyNormalizer
{
    /*
    |--------------------------------------------------------------------------
    | Normalize Yearly Summary
    |--------------------------------------------------------------------------
    |
    | Input:
    |
    |   trade_year
    |   trade_flow
    |   trade_value
    |   trade_volume
    |
    | Output:
    |
    | [
    |     2019 => [
    |         'year' => 2019,
    |         'import' => [
    |             'trade_value' => ...,
    |             'trade_volume' => ...,
    |         ],
    |         'export' => [
    |             'trade_value' => ...,
    |             'trade_volume' => ...,
    |         ],
    |     ],
    | ]
    |
    */

    public function yearly(
        iterable $rows
    ): array {

        $result = [];

        foreach ($rows as $row) {

            $year =
                (int) (
                    $row->trade_year
                    ?? $row['trade_year']
                    ?? $row->year
                    ?? 0
                );

            if ($year <= 0) {
                continue;
            }

            $flow =
                $this->normalizeFlow(
                    $row->trade_flow
                    ?? $row['trade_flow']
                    ?? $row->flow
                    ?? null
                );

            /*
            |--------------------------------------------------------------------------
            | Ignore Unexpected Flow
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $flow,
                    [
                        'import',
                        'export',
                    ],
                    true
                )
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Initialize Year
            |--------------------------------------------------------------------------
            */

            if (
                !isset(
                    $result[$year]
                )
            ) {

                $result[$year] = [

                    'year' =>
                        $year,

                    'import' =>
                        $this->emptyFlow(),

                    'export' =>
                        $this->emptyFlow(),
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Assign Flow
            |--------------------------------------------------------------------------
            */

            $result[$year][$flow] = [

                'trade_value' =>
                    $this->toFloat(
                        $row->trade_value
                        ?? $row['trade_value']
                        ?? $row->value
                        ?? 0
                    ),

                'trade_volume' =>
                    $this->toFloat(
                        $row->trade_volume
                        ?? $row['trade_volume']
                        ?? $row->volume
                        ?? 0
                    ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric Year Ordering
        |--------------------------------------------------------------------------
        */

        ksort(
            $result,
            SORT_NUMERIC
        );

        return array_values(
            $result
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Country Results
    |--------------------------------------------------------------------------
    |
    | Input:
    |
    |   trade_year
    |   trade_flow
    |   country
    |   trade_value
    |   trade_volume
    |
    | Output:
    |
    | [
    |     2019 => [
    |         [
    |             'country' => ...,
    |             'trade_value' => ...,
    |             'trade_volume' => ...,
    |         ],
    |     ],
    | ]
    |
    | This method does NOT enrich Canonical Country Identity.
    |
    */

    public function countries(
        iterable $rows
    ): array {

        $result = [];

        foreach ($rows as $row) {

            $year =
                (int) (
                    $row->trade_year
                    ?? $row['trade_year']
                    ?? $row->year
                    ?? 0
                );

            if ($year <= 0) {
                continue;
            }

            $flow =
                $this->normalizeFlow(
                    $row->trade_flow
                    ?? $row['trade_flow']
                    ?? $row->flow
                    ?? null
                );

            if (
                !in_array(
                    $flow,
                    [
                        'import',
                        'export',
                    ],
                    true
                )
            ) {
                continue;
            }

            $country =
                trim(
                    (string) (
                        $row->country
                        ?? $row['country']
                        ?? $row->trade_country
                        ?? $row['trade_country']
                        ?? ''
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | Ignore Empty Country
            |--------------------------------------------------------------------------
            */

            if ($country === '') {
                continue;
            }

            if (
                !isset(
                    $result[$year]
                )
            ) {
                $result[$year] = [
                    'import' => [],
                    'export' => [],
                ];
            }

            $result[$year][$flow][] = [

                'country' =>
                    $country,

                'trade_country' =>
                    $country,

                'trade_value' =>
                    $this->toFloat(
                        $row->trade_value
                        ?? $row['trade_value']
                        ?? $row->value
                        ?? 0
                    ),

                'trade_volume' =>
                    $this->toFloat(
                        $row->trade_volume
                        ?? $row['trade_volume']
                        ?? $row->volume
                        ?? 0
                    ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize Year Ordering
        |--------------------------------------------------------------------------
        */

        ksort(
            $result,
            SORT_NUMERIC
        );

        /*
        |--------------------------------------------------------------------------
        | Sort Countries by Trade Value
        |--------------------------------------------------------------------------
        |
        | This gives downstream country builder a stable
        | starting order.
        |
        */

        foreach (
            $result as &$yearData
        ) {

            foreach (
                [
                    'import',
                    'export',
                ] as $flow
            ) {

                usort(
                    $yearData[$flow],
                    static function (
                        array $a,
                        array $b
                    ): int {

                        return
                            $b['trade_value']
                            <=>
                            $a['trade_value'];
                    }
                );
            }
        }

        unset(
            $yearData
        );

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Complete Query Result
    |--------------------------------------------------------------------------
    |
    | Convenience method for HistoricalYearlyDatasetBuilder.
    |
    */

    public function normalize(
        array $queries
    ): array {

        return [

            'yearly' =>
                $this->yearly(
                    $queries['summary']
                    ?? []
                ),

            'countries' =>
                $this->countries(
                    $queries['country']
                    ?? []
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Flow
    |--------------------------------------------------------------------------
    */

    protected function emptyFlow(): array
    {
        return [

            'trade_value' =>
                0.0,

            'trade_volume' =>
                0.0,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Flow Normalization
    |--------------------------------------------------------------------------
    |
    | Historical source data may contain:
    |
    |   import
    |   impor
    |   i
    |   m
    |
    | or:
    |
    |   export
    |   ekspor
    |   e
    |   x
    |
    */

    protected function normalizeFlow(
        mixed $flow
    ): ?string {

        if (
            $flow === null
        ) {
            return null;
        }

        $value =
            strtolower(
                trim(
                    (string) $flow
                )
            );

        if (
            str_contains(
                $value,
                'import'
            )
            ||
            in_array(
                $value,
                [
                    'impor',
                    'i',
                    'm',
                ],
                true
            )
        ) {
            return 'import';
        }

        if (
            str_contains(
                $value,
                'export'
            )
            ||
            in_array(
                $value,
                [
                    'ekspor',
                    'e',
                    'x',
                ],
                true
            )
        ) {
            return 'export';
        }

        return $value !== ''
            ? $value
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | Numeric Normalization
    |--------------------------------------------------------------------------
    */

    protected function toFloat(
        mixed $value
    ): float {

        if (
            $value === null
        ) {
            return 0.0;
        }

        if (
            is_numeric($value)
        ) {
            return (float) $value;
        }

        $value =
            str_replace(
                ',',
                '',
                (string) $value
            );

        return is_numeric($value)
            ? (float) $value
            : 0.0;
    }
}