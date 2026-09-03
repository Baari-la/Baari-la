<?php

declare(strict_types=1);

final class GarmentFactorEngineV1
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
         * - fit_score:
         *   performance only where evidence exists.
         *
         * - coverage:
         *   fraction of requested weight with known candidate evidence.
         *
         * - ranking_score:
         *   transparent product of fit and coverage.
         *
         * This avoids pretending UNKNOWN is a match while still making
         * evidence completeness visible to the caller.
         */
        $fitScore = $knownWeight > 0
            ? ($weightedPoints / $knownWeight) * 100.0
            : 0.0;

        $coverage = $requestedWeight > 0
            ? ($knownWeight / $requestedWeight) * 100.0
            : 100.0;

        $rankingScore = ($fitScore * $coverage) / 100.0;

        $status = $hardFail
            ? 'REJECT'
            : 'QUALIFY';

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
         *
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

    private function isBroaderUserGroup(
        string $candidate,
        string $requested
    ): bool {
        /*
         * V1 deliberately conservative:
         * only explicit "Unspecified" is broader when the request itself
         * is also not exclusive.
         *
         * It is not a match for a specific user unless business taxonomy
         * later approves such behavior.
         */
        return false;
    }

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
            fn ($v) => trim((string) $v),
            $values
        );

        $values = array_filter(
            $values,
            fn ($v) =>
                $v !== ''
                && $v !== 'Unspecified / not stated'
        );

        return array_values(
            array_unique($values)
        );
    }

    private function normalizeScalar(mixed $value): string
    {
        return trim((string) $value);
    }

    private function isUnknown(mixed $value): bool
    {
        return $value === null
            || trim((string) $value) === ''
            || trim((string) $value) === 'Unspecified'
            || trim((string) $value) === 'Unspecified / not stated'
            || trim((string) $value) === 'Unspecified / not stated';
    }

    private function isUnconstrained(mixed $value): bool
    {
        return $value === null
            || $value === ''
            || $value === [];
    }

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