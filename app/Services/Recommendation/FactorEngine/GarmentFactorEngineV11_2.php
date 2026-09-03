<?php

declare(strict_types=1);
namespace App\Services\Recommendation\FactorEngine;

final class GarmentFactorEngineV11_2
{
    public const VERSION = '1.0';
    public const MAPPING_VERSION = 'Mapping v2 FINAL / FINAL-VALIDATED-PASS3';
    public const FACTOR_MASTER_VERSION = 'Factor Master FINAL';

    /** @var array<string,float> */
    private const WEIGHTS = [
        'article_family' => 0.30,
        'material_group' => 0.25,
        'gender_user' => 0.15,
        'construction' => 0.15,
        'special_attribute' => 0.15,
    ];

    /**
     * @param array<string,mixed> $request
     * @param array<string,mixed> $candidate Canonical HS factor profile.
     * @return array<string,mixed>
     */
    public function score(array $request, array $candidate): array
    {
        $trace = [];
        $hardFail = false;
        $incompatible = false;
        $weightedPoints = 0.0;
        $knownWeight = 0.0;
        $requestedWeight = 0.0;

        foreach (self::WEIGHTS as $dimension => $weight) {
            $requested = $request[$dimension] ?? null;
            $candidateValue = $candidate[$dimension] ?? null;

            if ($this->isUnconstrained($requested)) {
                $trace[] = $this->trace(
                    $candidate,
                    $dimension,
                    $requested,
                    $candidateValue,
                    'CR-015',
                    'UNCONSTRAINED',
                    null,
                    0.0
                );

                continue;
            }

            $requestedWeight += $weight;

            if ($this->isUnknown($candidateValue)) {
                $trace[] = $this->trace(
                    $candidate,
                    $dimension,
                    $requested,
                    $candidateValue,
                    'CR-014',
                    'UNKNOWN',
                    null,
                    0.0
                );

                continue;
            }

            [$ruleId, $result, $factorScore, $isHardFail] =
                $this->compare(
                    $dimension,
                    $requested,
                    $candidateValue
                );

            if ($isHardFail) {
                $hardFail = true;
            }

            /*
             * UNKNOWN is not a contradiction.
             * Only explicit MISMATCH makes the candidate incompatible.
             */
            if ($result === 'MISMATCH') {
                $incompatible = true;
            }

            $knownWeight += $weight;
            $weightedPoints += $weight * $factorScore;

            $trace[] = $this->trace(
                $candidate,
                $dimension,
                $requested,
                $candidateValue,
                $ruleId,
                $result,
                $factorScore,
                $weight * $factorScore
            );
        }

        /*
         * Evidence-adjusted score:
         *
         * fit_score:
         *   performance only where evidence exists.
         *
         * evidence_coverage:
         *   fraction of requested weight with known candidate evidence.
         *
         * ranking_score:
         *   transparent product of fit and coverage.
         */
        $fitScore = $knownWeight > 0
            ? ($weightedPoints / $knownWeight) * 100.0
            : 0.0;

        $coverage = $requestedWeight > 0
            ? ($knownWeight / $requestedWeight) * 100.0
            : 100.0;

        $rankingScore = ($fitScore * $coverage) / 100.0;

        /*
         * Eligibility gate precedes ranking.
         */
        $status = $hardFail
            ? 'REJECT'
            : ($incompatible ? 'INCOMPATIBLE' : 'QUALIFY');

        return [
            'engine_version' => self::VERSION,
            'mapping_version' => self::MAPPING_VERSION,
            'factor_master_version' => self::FACTOR_MASTER_VERSION,
            'hs_code' => (string) ($candidate['hs_code'] ?? ''),
            'status' => $status,
            'fit_score' => round($fitScore, 2),
            'evidence_coverage' => round($coverage, 2),
            'ranking_score' => round($rankingScore, 2),
            'hard_fail' => $hardFail,
            'trace' => $trace,
        ];
    }

    /**
     * Compare one factor dimension.
     *
     * @return array{
     *     0:string,
     *     1:string,
     *     2:float,
     *     3:bool
     * }
     */
    private function compare(
        string $dimension,
        mixed $requested,
        mixed $candidate
    ): array {
        if ($dimension === 'special_attribute') {
            $requestedAtoms = $this->atoms($requested);
            $candidateAtoms = $this->atoms($candidate);

            $missing = array_values(
                array_diff(
                    $requestedAtoms,
                    $candidateAtoms
                )
            );

            $hasProtectionRequirement =
                $this->containsProtectionAttribute(
                    $requestedAtoms
                );

            if ($missing !== []) {
                if ($hasProtectionRequirement) {
                    return [
                        'CR-013',
                        'HARD_FAIL',
                        0.0,
                        true,
                    ];
                }

                return [
                    'CR-012',
                    'MISMATCH',
                    0.0,
                    false,
                ];
            }

            return [
                'CR-010',
                'MATCH',
                1.0,
                false,
            ];
        }

        $requestedValue = $this->normalizeScalar($requested);
        $candidateValue = $this->normalizeScalar($candidate);

        if ($requestedValue === $candidateValue) {
            $rule = [
                'article_family' => 'CR-001',
                'material_group' => 'CR-006',
                'gender_user' => 'CR-003',
                'construction' => 'CR-008',
            ][$dimension];

            return [
                $rule,
                'MATCH',
                1.0,
                false,
            ];
        }

        /*
         * Explicit compatibility hook.
         * No silent semantic broadening.
         */
        if (
            $dimension === 'gender_user'
            && $this->isBroaderUserGroup(
                $candidateValue,
                $requestedValue
            )
        ) {
            return [
                'CR-004',
                'COMPATIBLE',
                1.0,
                false,
            ];
        }

        $rule = [
            'article_family' => 'CR-002',
            'material_group' => 'CR-007',
            'gender_user' => 'CR-005',
            'construction' => 'CR-009',
        ][$dimension];

        return [
            $rule,
            'MISMATCH',
            0.0,
            false,
        ];
    }

    /**
     * Conservative compatibility hook.
     *
     * No broader user-group assumption is currently approved.
     */
    private function isBroaderUserGroup(
        string $candidate,
        string $requested
    ): bool {
        return false;
    }

    /**
     * Determine whether requested attributes contain a protection
     * requirement for which absence is a hard failure.
     */
    private function containsProtectionAttribute(
        array $atoms
    ): bool {
        $protected = [
            'Fire protection',
            'Chemical protection',
            'Radiation protection',
            'Protective work',
            'Anti-explosive',
        ];

        return array_intersect(
            $protected,
            $atoms
        ) !== [];
    }

    /**
     * Normalize multi-value factor attributes.
     *
     * @return array<int,string>
     */
    private function atoms(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            $values = $value;
        } else {
            $values = preg_split(
                '/\s*;\s*|\s*\|\s*/u',
                (string) $value
            ) ?: [];
        }

        $values = array_map(
            static fn ($value): string =>
                trim((string) $value),
            $values
        );

        $values = array_filter(
            $values,
            static fn (string $value): bool =>
                $value !== ''
                && $value !== 'Unspecified / not stated'
        );

        return array_values(
            array_unique($values)
        );
    }

    private function normalizeScalar(
        mixed $value
    ): string {
        return trim((string) $value);
    }

    /**
     * Candidate has no usable evidence for the requested factor.
     */
    private function isUnknown(
        mixed $value
    ): bool {
        return $value === null
            || $this->isUnspecifiedValue($value); }

private function isUnspecifiedValue(mixed $value): bool
{
    if ($value === null) {
        return true;
    }

    if (is_array($value)) {
        /*
         * An empty array is a meaningful "no attributes"
         * value for candidate special_attribute.
         *
         * It must NOT be treated as "Unspecified".
         */
        if ($value === []) {
            return false;
        }

        foreach ($value as $item) {
            if (
                is_scalar($item)
                && trim((string) $item) !== ''
                && trim((string) $item) !== 'Unspecified'
                && trim((string) $item) !== 'Unspecified / not stated'
            ) {
                return false;
            }
        }

        return true;
    }

    if (!is_scalar($value)) {
        return false;
    }

    $normalized = trim((string) $value);

    return $normalized === ''
        || $normalized === 'Unspecified'
        || $normalized === 'Unspecified / not stated';
}
    /**
     * Request does not constrain this factor dimension.
     */
    private function isUnconstrained(
        mixed $value
    ): bool {
        return $value === null
            || $value === ''
            || $value === [];
    }

    /**
     * Produce explainable and traceable factor evidence.
     *
     * @return array<string,mixed>
     */
    private function trace(
        array $candidate,
        string $dimension,
        mixed $requested,
        mixed $candidateValue,
        string $ruleId,
        string $result,
        ?float $factorScore,
        float $contribution
    ): array {
        return [
            'hs_code' =>
                (string) ($candidate['hs_code'] ?? ''),

            'source_description' =>
                (string) ($candidate['description'] ?? ''),

            'dimension' =>
                $dimension,

            'requested_value' =>
                $requested,

            'candidate_value' =>
                $candidateValue,

            'rule_id' =>
                $ruleId,

            'result' =>
                $result,

            'factor_score' =>
                $factorScore,

            'weight' =>
                self::WEIGHTS[$dimension],

            'contribution' =>
                round($contribution, 4),
        ];
    }
}