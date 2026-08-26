<?php

namespace App\Services\TradeIntelligence\Support;

use Illuminate\Support\Collection;

final class TradeMetricCalculator
{
    /*
    |--------------------------------------------------------------------------
    | Sum Trade Value by Flow
    |--------------------------------------------------------------------------
    |
    | Aggregates official trade value for one trade flow.
    |
    | Canonical flows:
    |
    |     import
    |     export
    |
    */

    public function sumByFlow(
        iterable $rows,
        string $flow
    ): float {
        $total = 0.0;

        foreach ($rows as $row) {
            if (
                $this->rowValue($row, 'flow') !== $flow
            ) {
                continue;
            }

            $total += $this->toFloat(
                $this->rowValue($row, 'value')
            );
        }

        return $total;
    }


    /*
    |--------------------------------------------------------------------------
    | Sum Official Volume KG by Flow
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | This is the official physical trade volume.
    |
    | It must NEVER be replaced by derived PCS.
    |
    */

    public function sumVolumeKgByFlow(
        iterable $rows,
        string $flow
    ): float {
        $total = 0.0;

        foreach ($rows as $row) {
            if (
                $this->rowValue($row, 'flow') !== $flow
            ) {
                continue;
            }

            $total += $this->toFloat(
                $this->rowValue($row, 'volume')
            );
        }

        return $total;
    }


    /*
    |--------------------------------------------------------------------------
    | Sum Derived PCS by Flow
    |--------------------------------------------------------------------------
    |
    | PCS is derived intelligence.
    |
    | NEVER derive PCS inside this calculator.
    |
    | Only rows that have already been converted by
    | the HS-8 conversion engine are included.
    |
    */

    public function sumDerivedPcsByFlow(
        iterable $rows,
        string $flow
    ): array {
        $totalRows = 0;
        $convertedRows = 0;
        $pcs = 0.0;

        foreach ($rows as $row) {
            if (
                $this->rowValue($row, 'flow') !== $flow
            ) {
                continue;
            }

            $totalRows++;

            $conversionStatus =
                $this->rowValue(
                    $row,
                    'conversion_status'
                );

            $derivedPcs =
                $this->rowValue(
                    $row,
                    'derived_pcs'
                );

            /*
            |--------------------------------------------------------------------------
            | Only Validated / Converted PCS
            |--------------------------------------------------------------------------
            */

            if (
                $conversionStatus !== 'CONVERTED'
                || $derivedPcs === null
            ) {
                continue;
            }

            $convertedRows++;

            $pcs += $this->toFloat(
                $derivedPcs
            );
        }

        return [
            'pcs' =>
                $convertedRows > 0
                    ? $pcs
                    : null,

            'converted_rows' =>
                $convertedRows,

            'total_rows' =>
                $totalRows,

            'coverage_percent' =>
                $totalRows > 0
                    ? round(
                        (
                            $convertedRows
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
    | Growth Percent
    |--------------------------------------------------------------------------
    |
    | Formula:
    |
    |     ((current - previous) / previous) * 100
    |
    | When previous is zero:
    |
    |     current > 0 => 100%
    |     current = 0 => 0%
    |
    */

    public function growthPercent(
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


    /*
    |--------------------------------------------------------------------------
    | Calculate Growth from Values
    |--------------------------------------------------------------------------
    |
    | Convenience method when the caller has raw numeric values.
    |
    */

    public function calculateGrowth(
        mixed $current,
        mixed $previous
    ): float {
        return $this->growthPercent(
            $this->toFloat($current),
            $this->toFloat($previous)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Trade Value Difference
    |--------------------------------------------------------------------------
    */

    public function difference(
        mixed $current,
        mixed $previous
    ): float {
        return
            $this->toFloat($current)
            -
            $this->toFloat($previous);
    }


    /*
    |--------------------------------------------------------------------------
    | Percentage
    |--------------------------------------------------------------------------
    |
    | Generic percentage helper.
    |
    | Returns zero when denominator is zero.
    |
    */

    public function percentage(
        mixed $value,
        mixed $total,
        int $precision = 2
    ): float {
        $value =
            $this->toFloat($value);

        $total =
            $this->toFloat($total);

        if ($total == 0.0) {
            return 0.0;
        }

        return round(
            (
                $value
                /
                $total
            ) * 100,
            $precision
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sum Numeric Field
    |--------------------------------------------------------------------------
    |
    | Generic aggregation helper.
    |
    | Used only when a builder needs to aggregate
    | a known numeric field.
    |
    */

    public function sumField(
        iterable $rows,
        string $field
    ): float {
        $total = 0.0;

        foreach ($rows as $row) {
            $total += $this->toFloat(
                $this->rowValue(
                    $row,
                    $field
                )
            );
        }

        return $total;
    }


    /*
    |--------------------------------------------------------------------------
    | Conversion Coverage
    |--------------------------------------------------------------------------
    |
    | Calculates coverage for rows that have already
    | been converted successfully.
    |
    */

    public function conversionCoverage(
        iterable $rows
    ): array {
        $totalRows = 0;
        $convertedRows = 0;

        foreach ($rows as $row) {
            $totalRows++;

            if (
                $this->rowValue(
                    $row,
                    'conversion_status'
                ) !== 'CONVERTED'
            ) {
                continue;
            }

            if (
                $this->rowValue(
                    $row,
                    'derived_pcs'
                ) === null
            ) {
                continue;
            }

            $convertedRows++;
        }

        return [
            'converted_rows' =>
                $convertedRows,

            'total_rows' =>
                $totalRows,

            'coverage_percent' =>
                $totalRows > 0
                    ? round(
                        (
                            $convertedRows
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
    | Is Converted Row
    |--------------------------------------------------------------------------
    |
    | Centralizes the definition of a valid PCS
    | conversion row.
    |
    */

    public function isConvertedRow(
        mixed $row
    ): bool {
        return
            $this->rowValue(
                $row,
                'conversion_status'
            ) === 'CONVERTED'
            &&
            $this->rowValue(
                $row,
                'derived_pcs'
            ) !== null;
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Numeric Value
    |--------------------------------------------------------------------------
    |
    | Preserve the existing backend behavior:
    |
    | null       => 0
    | numeric    => float
    | "1,234.56" => 1234.56
    | invalid    => 0
    |
    */

    public function toFloat(
        mixed $value
    ): float {
        if ($value === null) {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = str_replace(
            ',',
            '',
            (string) $value
        );

        return is_numeric($value)
            ? (float) $value
            : 0.0;
    }


    /*
    |--------------------------------------------------------------------------
    | Read Row Value
    |--------------------------------------------------------------------------
    |
    | Builders currently use arrays.
    |
    | Supporting ArrayAccess / objects here keeps the calculator
    | reusable without forcing every caller to normalize its
    | collection first.
    |
    */

    private function rowValue(
        mixed $row,
        string $key
    ): mixed {
        if (is_array($row)) {
            return $row[$key] ?? null;
        }

        if ($row instanceof ArrayAccess) {
            return $row[$key] ?? null;
        }

        if (is_object($row)) {
            return $row->{$key} ?? null;
        }

        return null;
    }
}