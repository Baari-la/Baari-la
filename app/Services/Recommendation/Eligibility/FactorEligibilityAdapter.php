<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Eligibility;

use App\Services\Recommendation\FactorEngine\GarmentFactorEngineV11_2;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use RuntimeException;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Factor Eligibility Adapter
 * ==========================================================================
 *
 * Integration boundary between the Factor Engine and the
 * DIGESTEX Recommendation Engine.
 *
 * Responsibilities:
 * - Execute the production Factor Engine for each candidate.
 * - Translate Factor Engine status into recommendation eligibility.
 * - Fail closed when a canonical Factor Profile is unavailable.
 * - Preserve complete Factor Engine evidence and traceability.
 *
 * This adapter DOES NOT:
 * - define factor rules;
 * - calculate factor scores;
 * - calculate compatibility scores;
 * - calculate recommendation scores;
 * - calculate ranking scores;
 * - modify the Factor Engine semantic contract.
 *
 * Eligibility contract:
 *
 *   QUALIFY       -> eligible = true
 *   INCOMPATIBLE  -> candidate removed
 *   REJECT        -> candidate removed
 *
 * Missing Factor Profile:
 *
 *   -> candidate removed
 *
 * Production Engine:
 *
 *   GarmentFactorEngineV11_2
 *
 * Version:
 *   1.0
 */
final class FactorEligibilityAdapter
{
    public const VERSION = '1.0';

    public function __construct(
    private readonly GarmentFactorEngineV11_2 $engine,
) {
}

    /**
     * Apply the Factor Eligibility Gate.
     *
     * Only candidates with Factor Engine status QUALIFY
     * are allowed to continue into the Recommendation pipeline.
     *
     * @param Collection<int,array<string,mixed>> $candidates
     * @param array<string,mixed> $request
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function filter(
        Collection $candidates,
        array $request,
    ): Collection {
        return $candidates
            ->map(function (array $candidate) use ($request): ?array {
                $factorProfile = $this->resolveFactorProfile($candidate);

                /*
                 * FAIL CLOSED
                 *
                 * A candidate without a canonical Factor Profile
                 * must never bypass the Factor Eligibility Gate.
                 */
                if ($factorProfile === null) {
                    return null;
                }

                $evaluation = $this->engine->score(
                    $request,
                    $factorProfile,
                );

                $this->validateEvaluation($evaluation);

                /*
                 * Preserve the complete Factor Engine result.
                 *
                 * No factor score is recalculated here.
                 */
                $candidate['factor_evaluation'] = $evaluation;

                $candidate['factor_engine_version'] =
                    $evaluation['engine_version'];

                $candidate['factor_mapping_version'] =
                    $evaluation['mapping_version'];

                $candidate['factor_master_version'] =
                    $evaluation['factor_master_version'];

                $candidate['factor_hs_code'] =
                    $evaluation['hs_code'];

                $candidate['factor_status'] =
                    $evaluation['status'];

                $candidate['factor_fit_score'] =
                    $evaluation['fit_score'];

                $candidate['factor_evidence_coverage'] =
                    $evaluation['evidence_coverage'];

                $candidate['factor_ranking_score'] =
                    $evaluation['ranking_score'];

                $candidate['factor_hard_fail'] =
                    $evaluation['hard_fail'];

                $candidate['factor_trace'] =
                    $evaluation['trace'];

                /*
                 * ==========================================================
                 * FACTOR ELIGIBILITY GATE
                 * ==========================================================
                 *
                 * Factor Engine is authoritative for factor eligibility.
                 *
                 * QUALIFY       -> continue
                 * INCOMPATIBLE  -> stop
                 * REJECT        -> stop
                 */
                if ($evaluation['status'] !== 'QUALIFY') {
                    return null;
                }

                /*
                 * Do not inherit an earlier eligible value.
                 *
                 * Eligibility is explicitly established here.
                 */
                $candidate['eligible'] = true;

                return $candidate;
            })
            ->filter(
                static fn (?array $candidate): bool =>
                    $candidate !== null
                    && ($candidate['eligible'] ?? false) === true,
            )
            ->values();
    }

    /**
     * Resolve the canonical HS Factor Profile.
     *
     * Preferred source:
     *   candidate['factor_profile']
     *
     * Fallback:
     *   explicit canonical factor dimensions already present
     *   on the candidate.
     *
     * The adapter deliberately does NOT infer factor values from
     * free-text company descriptions or other uncontrolled sources.
     *
     * @param array<string,mixed> $candidate
     *
     * @return array<string,mixed>|null
     */
    private function resolveFactorProfile(
        array $candidate,
    ): ?array {
        /*
         * Preferred canonical profile.
         */
        if (
            isset($candidate['factor_profile'])
            && is_array($candidate['factor_profile'])
        ) {
            return $candidate['factor_profile'];
        }

        /*
         * Explicit canonical fields.
         */
        if (
            !isset($candidate['hs_code'])
            || !is_scalar($candidate['hs_code'])
        ) {
            return null;
        }

        $dimensions = [
            'article_family',
            'material_group',
            'gender_user',
            'construction',
            'special_attribute',
        ];

        foreach ($dimensions as $dimension) {
            if (!array_key_exists($dimension, $candidate)) {
                return null;
            }
        }

        return [
            'hs_code' => (string) $candidate['hs_code'],

            'description' => (string) (
                $candidate['description'] ?? ''
            ),

            'article_family' =>
                $candidate['article_family'],

            'material_group' =>
                $candidate['material_group'],

            'gender_user' =>
                $candidate['gender_user'],

            'construction' =>
                $candidate['construction'],

            'special_attribute' =>
                $candidate['special_attribute'],
        ];
    }

    /**
     * Validate the Factor Engine response before it is trusted.
     *
     * @param array<string,mixed> $evaluation
     */
    private function validateEvaluation(
        array $evaluation,
    ): void {
        $requiredKeys = [
            'engine_version',
            'mapping_version',
            'factor_master_version',
            'hs_code',
            'status',
            'fit_score',
            'evidence_coverage',
            'ranking_score',
            'hard_fail',
            'trace',
        ];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $evaluation)) {
                throw new RuntimeException(
                    sprintf(
                        'Factor Engine response is missing required key: %s',
                        $key,
                    ),
                );
            }
        }

        $allowedStatuses = [
            'QUALIFY',
            'INCOMPATIBLE',
            'REJECT',
        ];

        if (
            !is_string($evaluation['status'])
            || !in_array(
                $evaluation['status'],
                $allowedStatuses,
                true,
            )
        ) {
            throw new InvalidArgumentException(
                'Invalid Factor Engine eligibility status.',
            );
        }

        if (!is_array($evaluation['trace'])) {
            throw new RuntimeException(
                'Factor Engine trace must be an array.',
            );
        }

        if (!is_bool($evaluation['hard_fail'])) {
            throw new RuntimeException(
                'Factor Engine hard_fail must be boolean.',
            );
        }
    }
}