<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\GarmentConversionEvidence;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class GarmentConversionFactorCalculationService
{
    /**
     * Calculate a candidate garment conversion factor from
     * VALIDATED database evidence.
     *
     * CANONICAL METHODOLOGY:
     *
     * KG_PER_PCS
     *
     * Meaning:
     *
     *     1 PCS garment = X KG
     *
     * Example:
     *
     *     KG_PER_PCS = 0.193333
     *
     * This factor is later used to convert official trade
     * quantity reported in KG into estimated garment quantity
     * in PCS:
     *
     *     PCS = KG / KG_PER_PCS
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database records.
     * - Does not approve a factor.
     * - Does not activate a factor.
     * - Only VALIDATED evidence is eligible.
     * - Does not perform KG_TO_PCS conversion itself.
     */
    public function calculate(
        string $hsCode
    ): array {
        $hsCode = trim($hsCode);

        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 code is required.'
            );
        }

        $evidence = GarmentConversionEvidence::query()
            ->where('hs_code', $hsCode)
            ->where('validation_status', 'VALIDATED')
            ->orderBy('id')
            ->get();

        if ($evidence->isEmpty()) {
            return [
                'status' => 'NO_VALIDATED_EVIDENCE',

                'hs_code' => $hsCode,

                'candidate_factor' => null,

                'evidence_count' => 0,

                'total_sample_size' => 0,

                'methodology' => null,

                'evidence_type' => null,

                'weight_unit' => null,

                'calculation_method' => null,

                'observed_minimum' => null,

                'observed_maximum' => null,

                'evidence_references' => [],

                'reason' =>
                    'No VALIDATED evidence is available for this HS-8.',
            ];
        }

        return $this->calculateValidatedCollection(
            $hsCode,
            $evidence
        );
    }

    /**
     * Calculate candidate factor from an explicitly supplied
     * evidence collection.
     *
     * Useful for unit testing and controlled calculations
     * without database persistence.
     */
    public function calculateFromEvidence(
        Collection $evidence
    ): array {
        if ($evidence->isEmpty()) {
            return [
                'status' => 'NO_VALIDATED_EVIDENCE',

                'candidate_factor' => null,

                'evidence_count' => 0,

                'total_sample_size' => 0,

                'methodology' => null,

                'evidence_type' => null,

                'weight_unit' => null,

                'calculation_method' => null,

                'observed_minimum' => null,

                'observed_maximum' => null,

                'evidence_references' => [],

                'reason' =>
                    'No evidence is available for calculation.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | HS-8 Consistency
        |--------------------------------------------------------------------------
        */

        $hsCodes = $evidence
            ->pluck('hs_code')
            ->filter()
            ->unique()
            ->values();

        if ($hsCodes->count() !== 1) {
            return [
                'status' => 'REVIEW',

                'candidate_factor' => null,

                'evidence_count' => $evidence->count(),

                'total_sample_size' => 0,

                'methodology' => null,

                'evidence_type' => null,

                'weight_unit' => null,

                'calculation_method' => null,

                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),

                'reason' =>
                    'Evidence collection contains multiple HS-8 codes.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Only VALIDATED Evidence
        |--------------------------------------------------------------------------
        */

        $invalidStatuses = $evidence->filter(
            fn ($item) =>
                strtoupper(
                    trim(
                        (string) $item->validation_status
                    )
                ) !== 'VALIDATED'
        );

        if ($invalidStatuses->isNotEmpty()) {
            return [
                'status' => 'REVIEW',

                'hs_code' => (string) $hsCodes->first(),

                'candidate_factor' => null,

                'evidence_count' => $evidence->count(),

                'total_sample_size' => 0,

                'methodology' => null,

                'evidence_type' => null,

                'weight_unit' => null,

                'calculation_method' => null,

                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),

                'reason' =>
                    'Candidate calculation requires VALIDATED evidence only.',
            ];
        }

        return $this->calculateValidatedCollection(
            (string) $hsCodes->first(),
            $evidence
        );
    }

    /**
     * Perform the actual candidate calculation after
     * all eligibility checks have passed.
     */
    protected function calculateValidatedCollection(
        string $hsCode,
        Collection $evidence
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Methodology Consistency
        |--------------------------------------------------------------------------
        |
        | Canonical methodology:
        |
        |     KG_PER_PCS
        |
        | Meaning:
        |
        |     average KG per garment PCS
        |
        */

        $methodologies = $evidence
            ->pluck('methodology')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        if ($methodologies->count() !== 1) {
            return [
                'status' => 'REVIEW',

                'hs_code' => $hsCode,

                'candidate_factor' => null,

                'evidence_count' => $evidence->count(),

                'total_sample_size' =>
                    $this->totalSampleSize($evidence),

                'methodology' => $methodologies->all(),

                'evidence_type' => null,

                'weight_unit' => null,

                'calculation_method' => null,

                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),

                'reason' =>
                    'Validated evidence contains conflicting conversion methodologies.',
            ];
        }

        $methodology = $methodologies->first();

        /*
        |--------------------------------------------------------------------------
        | Canonical Methodology Gate
        |--------------------------------------------------------------------------
        */

        if ($methodology !== 'KG_PER_PCS') {
            return [
                'status' => 'REVIEW',

                'hs_code' => $hsCode,

                'candidate_factor' => null,

                'evidence_count' => $evidence->count(),

                'total_sample_size' =>
                    $this->totalSampleSize($evidence),

                'methodology' => $methodology,

                'evidence_type' => null,

                'weight_unit' => null,

                'calculation_method' => null,

                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),

                'reason' =>
                    'Only KG_PER_PCS is supported as the canonical garment conversion factor methodology.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Type Consistency
        |--------------------------------------------------------------------------
        */

        $evidenceTypes = $evidence
            ->pluck('evidence_type')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        if ($evidenceTypes->count() !== 1) {
            return [
                'status' => 'REVIEW',

                'hs_code' => $hsCode,

                'candidate_factor' => null,

                'evidence_count' => $evidence->count(),

                'total_sample_size' =>
                    $this->totalSampleSize($evidence),

                'methodology' => $methodology,

                'evidence_type' => $evidenceTypes->all(),

                'weight_unit' => null,

                'calculation_method' => null,

                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),

                'reason' =>
                    'Validated evidence contains conflicting evidence types.',
            ];
        }

        $evidenceType = $evidenceTypes->first();

        /*
        |--------------------------------------------------------------------------
        | Canonical Evidence Type
        |--------------------------------------------------------------------------
        */

        if ($evidenceType !== 'AVERAGE_WEIGHT_PER_PIECE') {
            return [
                'status' => 'REVIEW',

                'hs_code' => $hsCode,

                'candidate_factor' => null,

                'evidence_count' => $evidence->count(),

                'total_sample_size' =>
                    $this->totalSampleSize($evidence),

                'methodology' => $methodology,

                'evidence_type' => $evidenceType,

                'weight_unit' => null,

                'calculation_method' => null,

                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),

                'reason' =>
                    'KG_PER_PCS requires AVERAGE_WEIGHT_PER_PIECE evidence.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Weight Unit Consistency
        |--------------------------------------------------------------------------
        */

        $weightUnits = $evidence
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
            return [
                'status' => 'REVIEW',

                'hs_code' => $hsCode,

                'candidate_factor' => null,

                'evidence_count' => $evidence->count(),

                'total_sample_size' =>
                    $this->totalSampleSize($evidence),

                'methodology' => $methodology,

                'evidence_type' => $evidenceType,

                'weight_unit' => $weightUnits->all(),

                'calculation_method' => null,

                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),

                'reason' =>
                    'KG_PER_PCS evidence must use one consistent weight unit: KG.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Quantitative Validation
        |--------------------------------------------------------------------------
        */

        foreach ($evidence as $item) {
            if (
                $item->sample_size === null
                || (int) $item->sample_size < 1
            ) {
                return $this->invalidEvidence(
                    $hsCode,
                    $evidence,
                    'Validated evidence contains an invalid sample size.'
                );
            }

            if (
                $item->average_weight === null
                || !is_numeric($item->average_weight)
                || (float) $item->average_weight <= 0
            ) {
                return $this->invalidEvidence(
                    $hsCode,
                    $evidence,
                    'Validated evidence contains an invalid average weight.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sample-Size Weighted Average
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Evidence A:
        |   10 samples × 0.180 KG
        |
        | Evidence B:
        |   20 samples × 0.200 KG
        |
        | Result:
        |
        |   (10 × 0.180 + 20 × 0.200) / 30
        |   = 0.193333 KG_PER_PCS
        |
        */

        $totalSampleSize =
            $this->totalSampleSize($evidence);

        if ($totalSampleSize <= 0) {
            return $this->invalidEvidence(
                $hsCode,
                $evidence,
                'Total sample size is zero.'
            );
        }

        $weightedWeightTotal = $evidence->sum(
            fn ($item) =>
                (float) $item->average_weight
                * (int) $item->sample_size
        );

        $candidateFactor =
            $weightedWeightTotal
            / $totalSampleSize;

        /*
        |--------------------------------------------------------------------------
        | Observed Range
        |--------------------------------------------------------------------------
        */

        $observedMinimum = $evidence->min(
            fn ($item) =>
                (float) $item->average_weight
        );

        $observedMaximum = $evidence->max(
            fn ($item) =>
                (float) $item->average_weight
        );

        /*
        |--------------------------------------------------------------------------
        | Candidate Result
        |--------------------------------------------------------------------------
        */

        return [
            'status' => 'CANDIDATE',

            'hs_code' => $hsCode,

            /*
            |--------------------------------------------------------------------------
            | Canonical Factor
            |--------------------------------------------------------------------------
            |
            | Unit:
            |
            |     KG_PER_PCS
            |
            | Meaning:
            |
            |     KG per one garment PCS
            |
            */

            'candidate_factor' =>
                round($candidateFactor, 6),

            'evidence_count' =>
                $evidence->count(),

            'total_sample_size' =>
                $totalSampleSize,

            'methodology' =>
                'KG_PER_PCS',

            'evidence_type' =>
                $evidenceType,

            'weight_unit' =>
                'KG',

            'calculation_method' =>
                'SAMPLE_WEIGHTED_AVERAGE',

            'observed_minimum' =>
                round((float) $observedMinimum, 6),

            'observed_maximum' =>
                round((float) $observedMaximum, 6),

            /*
            |--------------------------------------------------------------------------
            | Traceability
            |--------------------------------------------------------------------------
            */

            'evidence_references' =>
                $this->buildEvidenceReferences($evidence),

            'reason' =>
                'Candidate KG_PER_PCS factor calculated from VALIDATED evidence using sample-size-weighted average. Factor is not approved.',
        ];
    }

    /**
     * Build auditable evidence references.
     *
     * Works both with persisted and unsaved model instances.
     */
    protected function buildEvidenceReferences(
        Collection $evidence
    ): array {
        return $evidence
            ->values()
            ->map(
                function ($item): array {
                    return [
                        'id' =>
                            $item->getKey(),

                        'hs_code' =>
                            $item->hs_code,

                        'methodology' =>
                            strtoupper(
                                trim(
                                    (string) $item->methodology
                                )
                            ),

                        'evidence_type' =>
                            strtoupper(
                                trim(
                                    (string) $item->evidence_type
                                )
                            ),

                        'sample_size' =>
                            $item->sample_size,

                        'average_weight' =>
                            $item->average_weight,

                        'weight_unit' =>
                            strtoupper(
                                trim(
                                    (string) $item->weight_unit
                                )
                            ),

                        'validation_status' =>
                            strtoupper(
                                trim(
                                    (string) $item->validation_status
                                )
                            ),

                        'source_type' =>
                            $item->source_type,

                        'source_reference' =>
                            $item->source_reference,
                    ];
                }
            )
            ->all();
    }

    /**
     * Calculate total sample size.
     */
    protected function totalSampleSize(
        Collection $evidence
    ): int {
        return (int) $evidence->sum(
            fn ($item) =>
                (int) $item->sample_size
        );
    }

    /**
     * Invalid evidence helper.
     */
    protected function invalidEvidence(
        string $hsCode,
        Collection $evidence,
        string $reason
    ): array {
        return [
            'status' => 'REVIEW',

            'hs_code' => $hsCode,

            'candidate_factor' => null,

            'evidence_count' =>
                $evidence->count(),

            'total_sample_size' =>
                $this->totalSampleSize($evidence),

            'methodology' => null,

            'evidence_type' => null,

            'weight_unit' => null,

            'calculation_method' => null,

            'evidence_references' =>
                $this->buildEvidenceReferences($evidence),

            'reason' => $reason,
        ];
    }
}