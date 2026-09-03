<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\GarmentConversionEvidence;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GarmentConversionFactorCalculationService
{
    private const METHODOLOGY = 'KG_PER_PCS';

    private const VALIDATED = 'VALIDATED';

    /**
     * Canonical calculation:
     *
     *     KG_PER_PCS = average KG per garment PCS
     *
     *     PCS = KG / KG_PER_PCS
     *
     * Priority:
     *
     * 1. HS-specific validated evidence
     * 2. Validated subgroup benchmark
     * 3. Validated group benchmark
     * 4. Validated universal garment benchmark
     * 5. No recommendation
     *
     * This service is READ-ONLY.
     */
    public function calculate(
        string $hsCode
    ): array {
        $hsCode = trim($hsCode);

        $this->validateHsCode($hsCode);

        /*
         * ---------------------------------------------------------------
         * 1. HS-SPECIFIC VALIDATED EVIDENCE
         * ---------------------------------------------------------------
         */
        $evidence = GarmentConversionEvidence::query()
            ->where('hs_code', $hsCode)
            ->where('validation_status', self::VALIDATED)
            ->where('methodology', self::METHODOLOGY)
            ->orderBy('id')
            ->get();

        if ($evidence->isNotEmpty()) {
            return $this->calculateValidatedCollection(
                $hsCode,
                $evidence
            );
        }

        /*
         * ---------------------------------------------------------------
         * 2. GOVERNED FALLBACK
         * ---------------------------------------------------------------
         */
        return $this->calculateFallback($hsCode);
    }

    /**
     * Calculate directly from supplied evidence.
     *
     * Kept as the controlled calculation entry point for tests
     * and evidence workflows.
     */
    public function calculateFromEvidence(
        Collection $evidence
    ): array {
        if ($evidence->isEmpty()) {
            return $this->noRecommendation(
                null,
                'NO_VALIDATED_EVIDENCE',
                'No evidence is available for calculation.'
            );
        }

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
                'observed_minimum' => null,
                'observed_maximum' => null,
                'evidence_references' =>
                    $this->buildEvidenceReferences($evidence),
                'reason' =>
                    'Evidence collection contains multiple HS-8 codes.',
            ];
        }

        return $this->calculateValidatedCollection(
            (string) $hsCodes->first(),
            $evidence
        );
    }

    /**
     * Governed fallback hierarchy.
     *
     * The factor itself is never invented here.
     *
     * The benchmark must already exist as VALIDATED evidence.
     */
    protected function calculateFallback(
        string $hsCode
    ): array {
        /*
         * ---------------------------------------------------------------
         * Resolve the existing Garment classification.
         *
         * The classification already exists in the project and is
         * represented by product_family / conversion subgroup data.
         * ---------------------------------------------------------------
         */
        $classification = $this->resolveClassification($hsCode);

        /*
         * ---------------------------------------------------------------
         * SUBGROUP
         * ---------------------------------------------------------------
         */
        $subgroup = $classification['subgroup'] ?? null;

        if ($subgroup !== null) {
            $result = $this->calculateFromBenchmark(
                $hsCode,
                'SUBGROUP',
                $subgroup
            );

            if ($result !== null) {
                return $result;
            }
        }

        /*
         * ---------------------------------------------------------------
         * GROUP
         * ---------------------------------------------------------------
         */
        $group = $classification['group'] ?? null;

        if ($group !== null) {
            $result = $this->calculateFromBenchmark(
                $hsCode,
                'GROUP',
                $group
            );

            if ($result !== null) {
                return $result;
            }
        }

        /*
         * ---------------------------------------------------------------
         * HS UNIVERSAL
         * ---------------------------------------------------------------
         *
         * Universal is the final governed garment benchmark.
         *
         * It is not an arbitrary factor copied from another HS.
         */
        $result = $this->calculateFromUniversalBenchmark(
            $hsCode
        );

        if ($result !== null) {
            return $result;
        }

        return $this->noRecommendation(
            $hsCode,
            'NO_GOVERNED_BENCHMARK',
            'No HS-specific evidence and no validated subgroup, group, or universal garment benchmark is available.'
        );
    }

    /**
     * Resolve existing garment classification.
     *
     * IMPORTANT:
     *
     * This method intentionally does not create a new taxonomy.
     *
     * It expects the existing garment classification fields to be
     * available in the canonical HS master data.
     */
    protected function resolveClassification(
        string $hsCode
    ): array {
        $master = DB::table('mst_hscode')
            ->where('hs_code', $hsCode)
            ->first();

        if ($master === null) {
            return [];
        }

        /*
         * Prefer explicit conversion subgroup/group fields when they
         * already exist.
         */
        return [
            'subgroup' =>
                $this->firstExistingProperty(
                    $master,
                    [
                        'conversion_subgroup',
                        'conversion_sub_group',
                        'subgroup',
                        'sub_group',
                    ]
                ),

            'group' =>
                $this->firstExistingProperty(
                    $master,
                    [
                        'conversion_group',
                        'group',
                    ]
                ),

            'product_family' =>
                $this->firstExistingProperty(
                    $master,
                    [
                        'product_family',
                    ]
                ),
        ];
    }

    /**
     * Calculate from a validated benchmark represented by evidence.
     *
     * A benchmark may be represented by multiple validated evidence
     * records. The same sample-size-weighted calculation is used.
     */
    protected function calculateFromBenchmark(
        string $hsCode,
        string $scope,
        string $scopeValue
    ): ?array {
        $scopeValue = trim($scopeValue);

        if ($scopeValue === '') {
            return null;
        }

        /*
         * Benchmark evidence is identified by source_reference /
         * source_type metadata rather than by copying another HS factor.
         *
         * This query deliberately uses only VALIDATED evidence.
         */
        $evidence = GarmentConversionEvidence::query()
            ->where('validation_status', self::VALIDATED)
            ->where('methodology', self::METHODOLOGY)
            ->where(function ($query) use ($scope, $scopeValue) {
                $query
                    ->where(function ($q) use ($scopeValue) {
                        $q->where(
                            'source_reference',
                            'LIKE',
                            '%SUBGROUP:' . $scopeValue . '%'
                        );
                    })
                    ->orWhere(function ($q) use ($scope, $scopeValue) {
                        $q->where(
                            'source_reference',
                            'LIKE',
                            '%GROUP:' . $scopeValue . '%'
                        );
                    });
            })
            ->orderBy('id')
            ->get();

        if ($evidence->isEmpty()) {
            return null;
        }

        $calculated = $this->calculateValidatedCollection(
            $hsCode,
            $evidence
        );

        if (
            ($calculated['candidate_factor'] ?? null) === null
        ) {
            return null;
        }

        return array_merge(
            $calculated,
            [
                'status' => 'RECOMMENDED',
                'resolution_scope' => $scope,
                'resolution_key' => $scopeValue,
                'reason' =>
                    sprintf(
                        'KG_PER_PCS recommendation derived from validated %s benchmark: %s.',
                        strtolower($scope),
                        $scopeValue
                    ),
            ]
        );
    }

    /**
     * Universal garment benchmark.
     *
     * Universal evidence is explicitly identified as:
     *
     *     source_reference = HS_UNIVERSAL:...
     *
     * Only VALIDATED evidence is eligible.
     */
    protected function calculateFromUniversalBenchmark(
        string $hsCode
    ): ?array {
        $evidence = GarmentConversionEvidence::query()
            ->where('validation_status', self::VALIDATED)
            ->where('methodology', self::METHODOLOGY)
            ->where(
                'source_reference',
                'LIKE',
                'HS_UNIVERSAL:%'
            )
            ->orderBy('id')
            ->get();

        if ($evidence->isEmpty()) {
            return null;
        }

        $calculated = $this->calculateValidatedCollection(
            $hsCode,
            $evidence
        );

        if (
            ($calculated['candidate_factor'] ?? null) === null
        ) {
            return null;
        }

        return array_merge(
            $calculated,
            [
                'status' => 'RECOMMENDED',
                'resolution_scope' => 'HS_UNIVERSAL',
                'resolution_key' => 'HS_UNIVERSAL',
                'reason' =>
                    'KG_PER_PCS recommendation derived from the validated HS Universal garment benchmark.',
            ]
        );
    }

    /**
     * Existing weighted-average calculation.
     */
    protected function calculateValidatedCollection(
        string $hsCode,
        Collection $evidence
    ): array {
        $methodologies = $evidence
            ->pluck('methodology')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        if (
            $methodologies->count() !== 1
            || $methodologies->first() !== self::METHODOLOGY
        ) {
            return $this->invalidEvidence(
                $hsCode,
                $evidence,
                'Evidence must use KG_PER_PCS methodology.'
            );
        }

        $evidenceTypes = $evidence
            ->pluck('evidence_type')
            ->filter()
            ->map(
                fn ($value) =>
                    strtoupper(trim((string) $value))
            )
            ->unique()
            ->values();

        if (
            $evidenceTypes->count() !== 1
            || $evidenceTypes->first() !==
                'AVERAGE_WEIGHT_PER_PIECE'
        ) {
            return $this->invalidEvidence(
                $hsCode,
                $evidence,
                'KG_PER_PCS requires AVERAGE_WEIGHT_PER_PIECE evidence.'
            );
        }

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
            return $this->invalidEvidence(
                $hsCode,
                $evidence,
                'KG_PER_PCS evidence must use KG.'
            );
        }

        foreach ($evidence as $item) {
            if (
                $item->sample_size === null
                || (int) $item->sample_size < 1
            ) {
                return $this->invalidEvidence(
                    $hsCode,
                    $evidence,
                    'Evidence contains an invalid sample size.'
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
                    'Evidence contains an invalid average weight.'
                );
            }
        }

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

        $observedMinimum = $evidence->min(
            fn ($item) =>
                (float) $item->average_weight
        );

        $observedMaximum = $evidence->max(
            fn ($item) =>
                (float) $item->average_weight
        );

        return [
            'status' => 'CANDIDATE',

            'hs_code' => $hsCode,

            'candidate_factor' =>
                round($candidateFactor, 6),

            'evidence_count' =>
                $evidence->count(),

            'total_sample_size' =>
                $totalSampleSize,

            'methodology' =>
                self::METHODOLOGY,

            'evidence_type' =>
                $evidenceTypes->first(),

            'weight_unit' =>
                'KG',

            'calculation_method' =>
                'SAMPLE_WEIGHTED_AVERAGE',

            'observed_minimum' =>
                round((float) $observedMinimum, 6),

            'observed_maximum' =>
                round((float) $observedMaximum, 6),

            'evidence_references' =>
                $this->buildEvidenceReferences($evidence),

            'reason' =>
                'Candidate KG_PER_PCS factor calculated using validated sample-size-weighted evidence.',
        ];
    }

    protected function firstExistingProperty(
        object $object,
        array $properties
    ): ?string {
        foreach ($properties as $property) {
            if (
                isset($object->{$property})
                && trim((string) $object->{$property}) !== ''
            ) {
                return trim((string) $object->{$property});
            }
        }

        return null;
    }

    protected function validateHsCode(
        string $hsCode
    ): void {
        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 code is required.'
            );
        }

        if (!preg_match('/^\d{8}$/', $hsCode)) {
            throw new InvalidArgumentException(
                'A valid HS-8 code is required.'
            );
        }
    }

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
                            (int) $item->sample_size,

                        'average_weight' =>
                            (float) $item->average_weight,

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

    protected function totalSampleSize(
        Collection $evidence
    ): int {
        return (int) $evidence->sum(
            fn ($item) =>
                (int) $item->sample_size
        );
    }

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
            'methodology' => self::METHODOLOGY,
            'evidence_type' => null,
            'weight_unit' => 'KG',
            'calculation_method' => null,
            'observed_minimum' => null,
            'observed_maximum' => null,
            'evidence_references' =>
                $this->buildEvidenceReferences($evidence),
            'reason' => $reason,
        ];
    }

    protected function noRecommendation(
        ?string $hsCode,
        string $code,
        string $reason
    ): array {
        return [
            'status' => 'NO_RECOMMENDATION',
            'hs_code' => $hsCode,
            'candidate_factor' => null,
            'evidence_count' => 0,
            'total_sample_size' => 0,
            'methodology' => self::METHODOLOGY,
            'evidence_type' => null,
            'weight_unit' => 'KG',
            'calculation_method' => $code,
            'observed_minimum' => null,
            'observed_maximum' => null,
            'evidence_references' => [],
            'reason' => $reason,
        ];
    }
}