<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\GarmentConversionFactor;
use InvalidArgumentException;

class GarmentConversionFactorResolverService
{
    /**
     * Resolve the currently ACTIVE conversion factor for an HS-8.
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database.
     * - Factor is always HS-8 specific.
     * - Never falls back to another HS-8 factor.
     * - Only ACTIVE factors are eligible.
     */
    public function resolve(
        string $hsCode,
        string $methodology = 'KG_PER_PCS'
    ): array {
        $hsCode = trim($hsCode);

        $methodology = strtoupper(
            trim($methodology)
        );

        /*
        |--------------------------------------------------------------------------
        | HS-8 Validation
        |--------------------------------------------------------------------------
        */

        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 is required for conversion factor resolution.'
            );
        }

        if (!preg_match('/^\d{8}$/', $hsCode)) {
            throw new InvalidArgumentException(
                'A valid HS-8 code is required for conversion factor resolution.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Methodology Validation
        |--------------------------------------------------------------------------
        */

        if ($methodology !== 'KG_PER_PCS') {
            throw new InvalidArgumentException(
                'Unsupported garment conversion methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Active Factor Lookup
        |--------------------------------------------------------------------------
        */

        $factor = GarmentConversionFactor::query()
            ->where('hs_code', $hsCode)
            ->where('methodology', $methodology)
            ->where('status', 'ACTIVE')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | No HS-Specific Factor
        |--------------------------------------------------------------------------
        */

        if ($factor === null) {
            return [
                'status' =>
                    'NOT_AVAILABLE',

                'resolution_code' =>
                    'NO_ACTIVE_FACTOR',

                'hs_code' =>
                    $hsCode,

                'methodology' =>
                    $methodology,

                'factor' =>
                    null,

                'factor_id' =>
                    null,

                'status_detail' =>
                    'NO_ACTIVE_HS_SPECIFIC_FACTOR',

                'reason' =>
                    'No ACTIVE conversion factor exists for this HS-8 and methodology. No factor from another HS-8 is used.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Resolved Factor
        |--------------------------------------------------------------------------
        */

        return [
            'status' =>
                'RESOLVED',

            'resolution_code' =>
                'ACTIVE_FACTOR_FOUND',

            'hs_code' =>
                $factor->hs_code,

            'methodology' =>
                $factor->methodology,

            'factor_id' =>
                $factor->id,

            'factor' =>
                (float) $factor->factor,

            'evidence_type' =>
                $factor->evidence_type,

            'weight_unit' =>
                $factor->weight_unit,

            'evidence_count' =>
                (int) $factor->evidence_count,

            'total_sample_size' =>
                (int) $factor->total_sample_size,

            'calculation_method' =>
                $factor->calculation_method,

            'observed_minimum' =>
                $factor->observed_minimum !== null
                    ? (float) $factor->observed_minimum
                    : null,

            'observed_maximum' =>
                $factor->observed_maximum !== null
                    ? (float) $factor->observed_maximum
                    : null,

            'status' =>
                $factor->status,

            'reason' =>
                'ACTIVE HS-specific conversion factor resolved successfully.',
        ];
    }

    /**
     * Resolve an ACTIVE factor or throw when unavailable.
     *
     * Useful for trade calculations where conversion is mandatory.
     */
    public function require(
        string $hsCode,
        string $methodology = 'KG_PER_PCS'
    ): array {
        $resolved = $this->resolve(
            $hsCode,
            $methodology
        );

        if ($resolved['status'] !== 'RESOLVED') {
            throw new InvalidArgumentException(
                sprintf(
                    'No ACTIVE conversion factor exists for HS-8 %s using %s.',
                    $hsCode,
                    $methodology
                )
            );
        }

        return $resolved;
    }
/**
 * Resolve ACTIVE conversion factors for multiple HS-8 codes.
 *
 * IMPORTANT:
 * - HS-8 specific only.
 * - Only ACTIVE factors are eligible.
 * - No fallback to another HS-8.
 * - Missing factors are explicitly returned as NOT_AVAILABLE.
 * - Read-only.
 */
public function resolveMany(
    iterable $hsCodes,
    string $methodology = 'KG_PER_PCS'
): array {
    $methodology = strtoupper(
        trim($methodology)
    );

    if ($methodology !== 'KG_PER_PCS') {
        throw new InvalidArgumentException(
            'Unsupported garment conversion methodology.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize + Validate HS-8
    |--------------------------------------------------------------------------
    */

    $codes = collect($hsCodes)
        ->map(
            fn ($hsCode) =>
                trim((string) $hsCode)
        )
        ->filter(
            fn ($hsCode) =>
                preg_match('/^\d{8}$/', $hsCode)
        )
        ->unique()
        ->values();

    if ($codes->isEmpty()) {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | ONE Database Query
    |--------------------------------------------------------------------------
    */

    $factors =
        GarmentConversionFactor::query()
            ->whereIn(
                'hs_code',
                $codes->all()
            )
            ->where(
                'methodology',
                $methodology
            )
            ->where(
                'status',
                'ACTIVE'
            )
            ->get()
            ->keyBy('hs_code');


    /*
    |--------------------------------------------------------------------------
    | Build Complete Resolution Map
    |--------------------------------------------------------------------------
    |
    | Every requested HS-8 receives a result.
    |
    */

    $result = [];

    foreach ($codes as $hsCode) {

        $factor =
            $factors->get($hsCode);

        /*
        |--------------------------------------------------------------------------
        | No ACTIVE Factor
        |--------------------------------------------------------------------------
        */

        if ($factor === null) {

            $result[$hsCode] = [
                'status' =>
                    'NOT_AVAILABLE',

                'resolution_code' =>
                    'NO_ACTIVE_FACTOR',

                'hs_code' =>
                    $hsCode,

                'methodology' =>
                    $methodology,

                'factor' =>
                    null,

                'factor_id' =>
                    null,

                'status_detail' =>
                    'NO_ACTIVE_HS_SPECIFIC_FACTOR',

                'reason' =>
                    'No ACTIVE conversion factor exists for this HS-8 and methodology. No factor from another HS-8 is used.',
            ];

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVE Factor Resolved
        |--------------------------------------------------------------------------
        */

        $result[$hsCode] = [
            'status' =>
                'RESOLVED',

            'resolution_code' =>
                'ACTIVE_FACTOR_FOUND',

            'hs_code' =>
                $factor->hs_code,

            'methodology' =>
                $factor->methodology,

            'factor_id' =>
                $factor->id,

            'factor' =>
                (float) $factor->factor,

            'evidence_type' =>
                $factor->evidence_type,

            'weight_unit' =>
                $factor->weight_unit,

            'evidence_count' =>
                (int) $factor->evidence_count,

            'total_sample_size' =>
                (int) $factor->total_sample_size,

            'calculation_method' =>
                $factor->calculation_method,

            'observed_minimum' =>
                $factor->observed_minimum !== null
                    ? (float) $factor->observed_minimum
                    : null,

            'observed_maximum' =>
                $factor->observed_maximum !== null
                    ? (float) $factor->observed_maximum
                    : null,

            'status' =>
                $factor->status,

            'reason' =>
                'ACTIVE HS-specific conversion factor resolved successfully.',
        ];
    }

    return $result;
}
    
}