<?php

declare(strict_types=1);

namespace App\Services\Trade;

use InvalidArgumentException;

class GarmentConversionService
{
    public function __construct(
        private readonly GarmentConversionFactorResolverService $resolver
    ) {
    }

    /**
     * Convert KG to PCS using the ACTIVE HS-specific factor.
     *
     * Formula:
     *
     *     PCS = KG / (KG per PCS)
     *
     * IMPORTANT:
     * - Factor must belong to the requested HS-8.
     * - Only ACTIVE factor is accepted.
     * - No fallback to another HS-8.
     * - Read-only.
     */
    public function kgToPcs(
        string $hsCode,
        float|int $kilograms
    ): array {
        $this->validateHsCode($hsCode);

        $kilograms = (float) $kilograms;

        if ($kilograms < 0) {
            throw new InvalidArgumentException(
                'Kilograms cannot be negative.'
            );
        }

        $resolved = $this->resolver->resolve(
            $hsCode,
            'KG_PER_PCS'
        );

        if (!$this->isActiveFactorResolved($resolved)) {
            return [
                'status' => 'NOT_CONVERTIBLE',
                'conversion_code' => 'NO_ACTIVE_FACTOR',
                'direction' => 'KG_TO_PCS',
                'hs_code' => $hsCode,
                'input_quantity' => $kilograms,
                'input_unit' => 'KG',
                'output_quantity' => null,
                'output_unit' => 'PCS',
                'factor_id' => null,
                'factor' => null,
                'methodology' => 'KG_PER_PCS',
                'reason' =>
                    'No ACTIVE HS-specific conversion factor is available. Conversion was not performed.',
            ];
        }

        $factor = (float) $resolved['factor'];

        if ($factor <= 0) {
            throw new InvalidArgumentException(
                'Resolved conversion factor must be greater than zero.'
            );
        }

        $pieces = $kilograms / $factor;

        return [
            'status' => 'CONVERTED',
            'conversion_code' => 'KG_TO_PCS_CONVERTED',
            'direction' => 'KG_TO_PCS',
            'hs_code' => $hsCode,
            'input_quantity' => $kilograms,
            'input_unit' => 'KG',
            'output_quantity' => round($pieces, 6),
            'output_unit' => 'PCS',
            'factor_id' => $resolved['factor_id'],
            'factor' => $factor,
            'methodology' => $resolved['methodology'],
            'factor_status' => $resolved['status'],
            'evidence_count' => $resolved['evidence_count'],
            'total_sample_size' => $resolved['total_sample_size'],
            'calculation_method' => $resolved['calculation_method'],
            'reason' =>
                'KG converted to PCS using the ACTIVE HS-specific conversion factor.',
        ];
    }

    /**
     * Convert PCS to KG using the ACTIVE HS-specific factor.
     *
     * Formula:
     *
     *     KG = PCS × (KG per PCS)
     *
     * IMPORTANT:
     * - Factor must belong to the requested HS-8.
     * - Only ACTIVE factor is accepted.
     * - No fallback to another HS-8.
     * - Read-only.
     */
    public function pcsToKg(
        string $hsCode,
        float|int $pieces
    ): array {
        $this->validateHsCode($hsCode);

        $pieces = (float) $pieces;

        if ($pieces < 0) {
            throw new InvalidArgumentException(
                'Pieces cannot be negative.'
            );
        }

        $resolved = $this->resolver->resolve(
            $hsCode,
            'KG_PER_PCS'
        );

        if (!$this->isActiveFactorResolved($resolved)) {
            return [
                'status' => 'NOT_CONVERTIBLE',
                'conversion_code' => 'NO_ACTIVE_FACTOR',
                'direction' => 'PCS_TO_KG',
                'hs_code' => $hsCode,
                'input_quantity' => $pieces,
                'input_unit' => 'PCS',
                'output_quantity' => null,
                'output_unit' => 'KG',
                'factor_id' => null,
                'factor' => null,
                'methodology' => 'KG_PER_PCS',
                'reason' =>
                    'No ACTIVE HS-specific conversion factor is available. Conversion was not performed.',
            ];
        }

        $factor = (float) $resolved['factor'];

        if ($factor <= 0) {
            throw new InvalidArgumentException(
                'Resolved conversion factor must be greater than zero.'
            );
        }

        $kilograms = $pieces * $factor;

        return [
            'status' => 'CONVERTED',
            'conversion_code' => 'PCS_TO_KG_CONVERTED',
            'direction' => 'PCS_TO_KG',
            'hs_code' => $hsCode,
            'input_quantity' => $pieces,
            'input_unit' => 'PCS',
            'output_quantity' => round($kilograms, 6),
            'output_unit' => 'KG',
            'factor_id' => $resolved['factor_id'],
            'factor' => $factor,
            'methodology' => $resolved['methodology'],
            'factor_status' => $resolved['status'],
            'evidence_count' => $resolved['evidence_count'],
            'total_sample_size' => $resolved['total_sample_size'],
            'calculation_method' => $resolved['calculation_method'],
            'reason' =>
                'PCS converted to KG using the ACTIVE HS-specific conversion factor.',
        ];
    }

    /**
     * Generic conversion entry point.
     *
     * Supported directions:
     *
     * - KG_TO_PCS
     * - PCS_TO_KG
     */
    public function convert(
        string $hsCode,
        string $direction,
        float|int $quantity
    ): array {
        $direction = strtoupper(
            trim($direction)
        );

        return match ($direction) {
            'KG_TO_PCS' => $this->kgToPcs(
                $hsCode,
                $quantity
            ),

            'PCS_TO_KG' => $this->pcsToKg(
                $hsCode,
                $quantity
            ),

            default => throw new InvalidArgumentException(
                'Unsupported conversion direction. Use KG_TO_PCS or PCS_TO_KG.'
            ),
        };
    }

    /**
     * Resolve the factor without performing a conversion.
     */
    public function resolveFactor(
        string $hsCode
    ): array {
        $this->validateHsCode($hsCode);

        return $this->resolver->resolve(
            $hsCode,
            'KG_PER_PCS'
        );
    }

    /**
     * Confirm that the Resolver returned a valid ACTIVE factor.
     *
     * All conditions must be satisfied:
     *
     * 1. ACTIVE_FACTOR_FOUND
     * 2. status ACTIVE
     * 3. factor_id exists
     * 4. factor exists
     */
    private function isActiveFactorResolved(
        array $resolved
    ): bool {
        return
            ($resolved['resolution_code'] ?? null)
                === 'ACTIVE_FACTOR_FOUND'
            &&
            ($resolved['status'] ?? null)
                === 'ACTIVE'
            &&
            isset($resolved['factor_id'])
            &&
            $resolved['factor_id'] !== null
            &&
            isset($resolved['factor'])
            &&
            $resolved['factor'] !== null;
    }

    /**
     * Validate HS-8.
     */
    private function validateHsCode(
        string $hsCode
    ): void {
        $hsCode = trim($hsCode);

        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 is required for garment conversion.'
            );
        }

        if (!preg_match('/^\d{8}$/', $hsCode)) {
            throw new InvalidArgumentException(
                'A valid 8-digit HS code is required for garment conversion.'
            );
        }
    }

    /**
 * Resolve ACTIVE factors for multiple HS-8 codes.
 */
public function resolveFactors(
    iterable $hsCodes
): array {
    return $this->resolver->resolveMany(
        $hsCodes,
        'KG_PER_PCS'
    );
}
}