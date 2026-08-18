<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\TradeUnitClassification;
use InvalidArgumentException;

class TradeUnitConversionService
{
    /**
     * Convert trade volume from the official trade unit
     * into the Digestex intelligence unit.
     *
     * Conversion is strictly HS-8 based.
     *
     * No generic fallback conversion is allowed.
     */
    public function convert(
        string $hsCode,
        float $tradeVolume
    ): array {

        $hsCode = $this->normalizeHsCode($hsCode);

        $classification =
            TradeUnitClassification::query()
                ->where('hs_code', $hsCode)
                ->where('status', 'active')
                ->first();

        /*
        |--------------------------------------------------------------------------
        | HS-8 Not Found
        |--------------------------------------------------------------------------
        */

        if (! $classification) {

            return [
                'hs_code' => $hsCode,
                'input_volume' => $tradeVolume,
                'input_unit' => null,
                'output_volume' => $tradeVolume,
                'output_unit' => null,
                'converted' => false,
                'conversion_factor' => null,
                'conversion_method' => null,
                'conversion_confidence' => null,
                'reason' => 'HS-8 classification not found',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Conversion Not Enabled
        |--------------------------------------------------------------------------
        |
        | This is the expected state for the current stage.
        |
        | We have the HS-8 master, but conversion factors have
        | not yet been approved.
        |
        */

        if (
            ! $classification->conversion_enabled
            || $classification->conversion_factor === null
        ) {

            return [
                'hs_code' => $hsCode,

                'input_volume' =>
                    $tradeVolume,

                'input_unit' =>
                    $classification->official_unit,

                'output_volume' =>
                    $tradeVolume,

                'output_unit' =>
                    $classification->intelligence_unit
                    ?: $classification->official_unit,

                'converted' => false,

                'conversion_factor' => null,

                'conversion_method' =>
                    $classification->conversion_method,

                'conversion_confidence' =>
                    $classification->conversion_confidence,

                'reason' =>
                    'Conversion not enabled',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Apply HS-8 Conversion Factor
        |--------------------------------------------------------------------------
        */

        $factor =
            (float) $classification->conversion_factor;

        $outputVolume =
            $tradeVolume * $factor;

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [
            'hs_code' => $hsCode,

            'input_volume' =>
                $tradeVolume,

            'input_unit' =>
                $classification->official_unit,

            'output_volume' =>
                round(
                    $outputVolume,
                    2
                ),

            'output_unit' =>
                $classification->intelligence_unit,

            'converted' => true,

            'conversion_factor' =>
                $factor,

            'conversion_method' =>
                $classification->conversion_method,

            'conversion_confidence' =>
                $classification->conversion_confidence,

            'reason' =>
                'HS-8 conversion applied',
        ];
    }

    /**
     * Normalize HS code.
     *
     * Digestex Unit Intelligence requires
     * an exact 8-digit HS code.
     */
    protected function normalizeHsCode(
        string $hsCode
    ): string {

        $normalized =
            preg_replace(
                '/[^0-9]/',
                '',
                $hsCode
            );

        if (
            ! $normalized
            || strlen($normalized) !== 8
        ) {

            throw new InvalidArgumentException(
                'Trade Unit Conversion requires a valid HS-8 code.'
            );
        }

        return $normalized;
    }
}