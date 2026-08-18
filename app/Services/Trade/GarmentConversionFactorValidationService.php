<?php

declare(strict_types=1);

namespace App\Services\Trade;

class GarmentConversionFactorValidationService
{
    /**
     * Validate a candidate conversion factor.
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database.
     * - Does not activate a factor.
     * - Preserves HS-8 identity and evidence provenance.
     */
    public function validate(array $candidate): array
    {
        $candidateStatus = strtoupper(
            trim((string) ($candidate['status'] ?? ''))
        );

        if ($candidateStatus !== 'CANDIDATE') {
            return $this->review(
                'INVALID_CANDIDATE_STATUS',
                'Factor validation requires a CANDIDATE factor.',
                $candidate
            );
        }

        $factor = $candidate['candidate_factor'] ?? null;

        if (
            $factor === null
            || !is_numeric($factor)
            || (float) $factor <= 0
        ) {
            return $this->reject(
                'INVALID_CANDIDATE_FACTOR',
                'Candidate factor must be a positive numeric value.',
                $candidate
            );
        }

        $factor = (float) $factor;

        $methodology = strtoupper(
            trim((string) ($candidate['methodology'] ?? ''))
        );

        if ($methodology === '') {
            return $this->review(
                'MISSING_METHODOLOGY',
                'Candidate factor has no methodology.',
                $candidate
            );
        }

        $evidenceCount = (int) (
            $candidate['evidence_count'] ?? 0
        );

        if ($evidenceCount < 1) {
            return $this->review(
                'INSUFFICIENT_EVIDENCE_COUNT',
                'At least one validated evidence record is required.',
                $candidate
            );
        }

        $totalSampleSize = (int) (
            $candidate['total_sample_size'] ?? 0
        );

        if ($totalSampleSize < 1) {
            return $this->review(
                'INSUFFICIENT_SAMPLE_SIZE',
                'Total validated sample size must be greater than zero.',
                $candidate
            );
        }

        $references =
            $candidate['evidence_references'] ?? [];

        if (
            !is_array($references)
            || count($references) !== $evidenceCount
        ) {
            return $this->review(
                'EVIDENCE_TRACEABILITY_FAILURE',
                'Candidate factor evidence references do not match the evidence count.',
                $candidate
            );
        }

        foreach ($references as $reference) {
            if (!is_array($reference)) {
                return $this->review(
                    'INVALID_EVIDENCE_REFERENCE',
                    'Evidence reference must be an array.',
                    $candidate
                );
            }

            if (
                strtoupper(
                    trim(
                        (string) (
                            $reference['validation_status']
                            ?? ''
                        )
                    )
                ) !== 'VALIDATED'
            ) {
                return $this->review(
                    'UNVALIDATED_EVIDENCE_REFERENCE',
                    'All evidence references must have VALIDATED status.',
                    $candidate
                );
            }

            if (
                !isset($reference['average_weight'])
                || !is_numeric($reference['average_weight'])
                || (float) $reference['average_weight'] <= 0
            ) {
                return $this->review(
                    'INVALID_EVIDENCE_WEIGHT',
                    'Evidence reference contains an invalid average weight.',
                    $candidate
                );
            }

            if (
                !isset($reference['sample_size'])
                || (int) $reference['sample_size'] < 1
            ) {
                return $this->review(
                    'INVALID_EVIDENCE_SAMPLE',
                    'Evidence reference contains an invalid sample size.',
                    $candidate
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Methodology Consistency
        |--------------------------------------------------------------------------
        */

        $methodologies = collect($references)
            ->pluck('methodology')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        if ($methodologies->count() !== 1) {
            return $this->review(
                'METHODOLOGY_CONFLICT',
                'Evidence references contain conflicting methodologies.',
                $candidate
            );
        }

        if ($methodologies->first() !== $methodology) {
            return $this->review(
                'METHODOLOGY_MISMATCH',
                'Candidate methodology does not match the evidence methodology.',
                $candidate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Type Consistency
        |--------------------------------------------------------------------------
        */

        $evidenceTypes = collect($references)
            ->pluck('evidence_type')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        if ($evidenceTypes->count() !== 1) {
            return $this->review(
                'EVIDENCE_TYPE_CONFLICT',
                'Evidence references contain conflicting evidence types.',
                $candidate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Weight Unit Consistency
        |--------------------------------------------------------------------------
        */

        $weightUnits = collect($references)
            ->pluck('weight_unit')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        if (
            $weightUnits->count() !== 1
            || $weightUnits->first() !== 'KG'
        ) {
            return $this->review(
                'WEIGHT_UNIT_CONFLICT',
                'All evidence must use KG as the common weight unit.',
                $candidate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Observed Range
        |--------------------------------------------------------------------------
        */

        $observedMinimum =
            isset($candidate['observed_minimum'])
                ? (float) $candidate['observed_minimum']
                : null;

        $observedMaximum =
            isset($candidate['observed_maximum'])
                ? (float) $candidate['observed_maximum']
                : null;

        if (
            $observedMinimum !== null
            && $factor < $observedMinimum
        ) {
            return $this->review(
                'FACTOR_OUTSIDE_OBSERVED_RANGE',
                'Candidate factor is below the observed evidence range.',
                $candidate
            );
        }

        if (
            $observedMaximum !== null
            && $factor > $observedMaximum
        ) {
            return $this->review(
                'FACTOR_OUTSIDE_OBSERVED_RANGE',
                'Candidate factor is above the observed evidence range.',
                $candidate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Strong Source Requirement
        |--------------------------------------------------------------------------
        */

        $sourceTypes = collect($references)
            ->pluck('source_type')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        $strongSources = [
            'INTERNAL_SAMPLE',
            'FACTORY_PRODUCTION_DATA',
            'LABORATORY_MEASUREMENT',
            'CERTIFIED_TEST_REPORT',
            'VERIFIED_SUPPLIER_DATA',
        ];

        $hasStrongSource = $sourceTypes->contains(
            fn ($source) =>
                in_array(
                    $source,
                    $strongSources,
                    true
                )
        );

        if (!$hasStrongSource) {
            return $this->review(
                'NO_STRONG_SOURCE',
                'Candidate factor has no strong evidence source.',
                $candidate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Final Decision
        |--------------------------------------------------------------------------
        */

        return [
            'status' =>
                'APPROVED_CANDIDATE',

            'validation_code' =>
                'FACTOR_CANDIDATE_VALIDATED',

            'confidence_level' =>
                'HIGH',

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            'hs_code' =>
                $candidate['hs_code'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Factor
            |--------------------------------------------------------------------------
            */

            'candidate_factor' =>
                round($factor, 6),

            'methodology' =>
                $methodology,

            'evidence_type' =>
                $candidate['evidence_type']
                    ?? $evidenceTypes->first(),

            'weight_unit' =>
                $candidate['weight_unit']
                    ?? $weightUnits->first(),

            /*
            |--------------------------------------------------------------------------
            | Evidence
            |--------------------------------------------------------------------------
            */

            'evidence_count' =>
                $evidenceCount,

            'total_sample_size' =>
                $totalSampleSize,

            'evidence_references' =>
                $references,

            /*
            |--------------------------------------------------------------------------
            | Calculation Context
            |--------------------------------------------------------------------------
            */

            'calculation_method' =>
                $candidate['calculation_method']
                    ?? null,

            'observed_minimum' =>
                $candidate['observed_minimum']
                    ?? null,

            'observed_maximum' =>
                $candidate['observed_maximum']
                    ?? null,

            'factor_eligible' =>
                true,

            /*
            |--------------------------------------------------------------------------
            | Activation
            |--------------------------------------------------------------------------
            */

            'activation_status' =>
                'NOT_ACTIVE',

            'reason' =>
                'Candidate factor passed structural, traceability, methodology, evidence, range, and source validation. Factor is not yet active.',
        ];
    }

    protected function review(
        string $code,
        string $reason,
        array $candidate = []
    ): array {
        return [
            'status' =>
                'REVIEW',

            'validation_code' =>
                $code,

            'confidence_level' =>
                null,

            'hs_code' =>
                $candidate['hs_code'] ?? null,

            'candidate_factor' =>
                null,

            'methodology' =>
                $candidate['methodology'] ?? null,

            'evidence_type' =>
                $candidate['evidence_type'] ?? null,

            'weight_unit' =>
                $candidate['weight_unit'] ?? null,

            'evidence_count' =>
                $candidate['evidence_count'] ?? 0,

            'total_sample_size' =>
                $candidate['total_sample_size'] ?? 0,

            'evidence_references' =>
                $candidate['evidence_references'] ?? [],

            'factor_eligible' =>
                false,

            'activation_status' =>
                'NOT_ACTIVE',

            'reason' =>
                $reason,
        ];
    }

    protected function reject(
        string $code,
        string $reason,
        array $candidate = []
    ): array {
        return [
            'status' =>
                'REJECTED',

            'validation_code' =>
                $code,

            'confidence_level' =>
                null,

            'hs_code' =>
                $candidate['hs_code'] ?? null,

            'candidate_factor' =>
                null,

            'methodology' =>
                $candidate['methodology'] ?? null,

            'evidence_type' =>
                $candidate['evidence_type'] ?? null,

            'weight_unit' =>
                $candidate['weight_unit'] ?? null,

            'evidence_count' =>
                $candidate['evidence_count'] ?? 0,

            'total_sample_size' =>
                $candidate['total_sample_size'] ?? 0,

            'evidence_references' =>
                $candidate['evidence_references'] ?? [],

            'factor_eligible' =>
                false,

            'activation_status' =>
                'NOT_ACTIVE',

            'reason' =>
                $reason,
        ];
    }
}