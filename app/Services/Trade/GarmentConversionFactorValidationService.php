<?php

declare(strict_types=1);

namespace App\Services\Trade;

class GarmentConversionFactorValidationService
{
    /**
     * Validate a candidate garment conversion factor.
     *
     * CANONICAL METHODOLOGY:
     *
     *     KG_PER_PCS
     *
     * Meaning:
     *
     *     1 garment PCS = X KG
     *
     * Example:
     *
     *     0.193333 KG_PER_PCS
     *
     * This factor can later be used for:
     *
     *     PCS = Official Trade KG / KG_PER_PCS
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database.
     * - Does not approve a factor.
     * - Does not activate a factor.
     * - Preserves HS-8 identity.
     * - Preserves complete evidence provenance.
     */
    public function validate(array $candidate): array
    {
        /*
        |--------------------------------------------------------------------------
        | Candidate Status
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Candidate Factor
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | HS-8 Identity
        |--------------------------------------------------------------------------
        */

        $hsCode = trim(
            (string) ($candidate['hs_code'] ?? '')
        );

        if ($hsCode === '') {
            return $this->review(
                'MISSING_HS_CODE',
                'Candidate factor requires an HS-8 code.',
                $candidate
            );
        }

        if (!preg_match('/^\d{8}$/', $hsCode)) {
            return $this->review(
                'INVALID_HS_CODE',
                'Candidate factor requires a valid 8-digit HS code.',
                $candidate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Methodology
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Canonical Methodology Gate
        |--------------------------------------------------------------------------
        |
        | The factor itself is defined as KG_PER_PCS.
        |
        | KG_TO_PCS is a later conversion operation, NOT a factor
        | methodology.
        |
        */

        if ($methodology !== 'KG_PER_PCS') {
            return $this->review(
                'UNSUPPORTED_METHODOLOGY',
                'Only KG_PER_PCS is supported as the canonical garment conversion factor methodology.',
                $candidate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Count
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Total Sample Size
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Evidence References
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Evidence Validation
        |--------------------------------------------------------------------------
        */

        foreach ($references as $reference) {
            if (!is_array($reference)) {
                return $this->review(
                    'INVALID_EVIDENCE_REFERENCE',
                    'Evidence reference must be an array.',
                    $candidate
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validation Status
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | HS-8 Consistency
            |--------------------------------------------------------------------------
            */

            $referenceHsCode = trim(
                (string) (
                    $reference['hs_code']
                    ?? ''
                )
            );

            if ($referenceHsCode !== $hsCode) {
                return $this->review(
                    'HS_CODE_MISMATCH',
                    'Evidence reference HS-8 does not match the candidate HS-8.',
                    $candidate
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Methodology
            |--------------------------------------------------------------------------
            */

            $referenceMethodology = strtoupper(
                trim(
                    (string) (
                        $reference['methodology']
                        ?? ''
                    )
                )
            );

            if ($referenceMethodology !== 'KG_PER_PCS') {
                return $this->review(
                    'UNSUPPORTED_EVIDENCE_METHODOLOGY',
                    'All evidence references must use KG_PER_PCS methodology.',
                    $candidate
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Evidence Weight
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Sample Size
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Weight Unit
            |--------------------------------------------------------------------------
            */

            $referenceWeightUnit = strtoupper(
                trim(
                    (string) (
                        $reference['weight_unit']
                        ?? ''
                    )
                )
            );

            if ($referenceWeightUnit !== 'KG') {
                return $this->review(
                    'INVALID_EVIDENCE_WEIGHT_UNIT',
                    'All KG_PER_PCS evidence must use KG as the weight unit.',
                    $candidate
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Evidence Type
            |--------------------------------------------------------------------------
            */

            $referenceEvidenceType = strtoupper(
                trim(
                    (string) (
                        $reference['evidence_type']
                        ?? ''
                    )
                )
            );

            if (
                $referenceEvidenceType
                !== 'AVERAGE_WEIGHT_PER_PIECE'
            ) {
                return $this->review(
                    'INVALID_EVIDENCE_TYPE',
                    'KG_PER_PCS requires AVERAGE_WEIGHT_PER_PIECE evidence.',
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

        if (
            $evidenceTypes->first()
            !== 'AVERAGE_WEIGHT_PER_PIECE'
        ) {
            return $this->review(
                'EVIDENCE_TYPE_MISMATCH',
                'KG_PER_PCS requires AVERAGE_WEIGHT_PER_PIECE evidence.',
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
            && $observedMaximum !== null
            && $observedMinimum > $observedMaximum
        ) {
            return $this->review(
                'INVALID_OBSERVED_RANGE',
                'Observed minimum cannot be greater than observed maximum.',
                $candidate
            );
        }

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
                $hsCode,

            /*
            |--------------------------------------------------------------------------
            | Factor
            |--------------------------------------------------------------------------
            */

            'candidate_factor' =>
                round($factor, 6),

            'methodology' =>
                'KG_PER_PCS',

            'evidence_type' =>
                'AVERAGE_WEIGHT_PER_PIECE',

            'weight_unit' =>
                'KG',

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

            /*
            |--------------------------------------------------------------------------
            | Eligibility
            |--------------------------------------------------------------------------
            */

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
                'Candidate KG_PER_PCS factor passed structural, traceability, methodology, evidence, range, and source validation. Factor is eligible for approval but is not yet active.',
        ];
    }

    /**
     * Return REVIEW result.
     */
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

    /**
     * Return REJECTED result.
     */
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