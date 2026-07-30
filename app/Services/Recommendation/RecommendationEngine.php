<?php

declare(strict_types=1);

namespace App\Services\Recommendation;

use App\Models\Company;
use Carbon\Carbon;

use App\Services\Recommendation\Contracts\RecommendationEngineContract;
use App\Services\Recommendation\DTO\RecommendationResult;

use App\Services\Recommendation\Candidate\CandidateLoader;
use App\Services\Recommendation\Candidate\CandidateEvaluator;

use App\Services\Recommendation\Score\CompatibilityScoreService;
use App\Services\Recommendation\Score\ConfidenceScoreService;
use App\Services\Recommendation\Score\RecommendationScoreService;

use App\Services\Recommendation\Ranking\RecommendationRankingService;
use App\Services\Recommendation\Reason\RecommendationReasonService;
use App\Services\Recommendation\Explanation\RecommendationExplanationService;

use App\Services\Recommendation\Metadata\RecommendationMetadataService;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * DIGESTEX Recommendation Intelligence Engine (DRIE)
 * ==========================================================================
 *
 * Central recommendation engine responsible for generating intelligent
 * business recommendations throughout the DIGESTEX ecosystem.
 *
 * This engine powers:
 *
 * - Smart Business Matching
 * - Build My Supply Chain
 * - Buyer Discovery
 * - RFQ Intelligence
 * - Opportunity Intelligence
 * - Executive AI
 *
 * Architecture:
 *
 * Candidate Loader
 *      ↓
 * Candidate Evaluation
 *      ↓
 * Eligibility Gate
 *      ↓
 * Discovery Mode Gate
 *      ↓
 * Compatibility Score
 *      ↓
 * Recommendation Score
 *      ↓
 * Confidence Score
 *      ↓
 * Ranking
 *      ↓
 * Assign Rank
 *      ↓
 * Recommendation Reasons
 *      ↓
 * Recommendation Explanation
 *      ↓
 * Metadata
 *      ↓
 * Recommendation Result
 *
 * Version:
 * 2.1
 */
class RecommendationEngine implements RecommendationEngineContract
{
    public function __construct(
        protected CandidateLoader $loader,
        protected CandidateEvaluator $evaluator,
        protected CompatibilityScoreService $compatibility,
        protected RecommendationScoreService $score,
        protected ConfidenceScoreService $confidence,
        protected RecommendationRankingService $ranking,
        protected RecommendationReasonService $reason,
        protected RecommendationExplanationService $explanation,
        protected RecommendationMetadataService $metadata,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Generate Recommendations
     * --------------------------------------------------------------------------
     */
    public function recommend(
        Company $company,
        array $context = [],
    ): RecommendationResult {

        /*
        |--------------------------------------------------------------------------
        | Step 1 — Load Candidate Companies
        |--------------------------------------------------------------------------
        */

        $candidates = $this->loader->load(
            company: $company,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Step 2 — Evaluate Candidates
        |--------------------------------------------------------------------------
        */

        $recommendations = $this->evaluator->evaluate(
            company: $company,
            candidates: $candidates,
            context: $context,
        );

        $evaluatedCount = $recommendations->count();

        /*
        |--------------------------------------------------------------------------
        | Step 3 — Eligibility Gate
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations
            ->filter(
                fn (array $candidate): bool =>
                    (bool) ($candidate['eligible'] ?? false)
            )
            ->values();

        $eligibleCount = $recommendations->count();

        /*
        |--------------------------------------------------------------------------
        | Step 4 — Discovery Mode Gate
        |--------------------------------------------------------------------------
        */

        $discoveryMode = $context['discovery_mode'] ?? null;

        if (
            is_string($discoveryMode)
            && trim($discoveryMode) !== ''
        ) {
            $discoveryMode = strtolower(
                trim($discoveryMode)
            );

            $recommendations = $recommendations
                ->filter(
                    fn (array $candidate): bool =>
                        ($candidate['discovery_mode'] ?? null)
                        === $discoveryMode
                )
                ->values();
        }

        $discoveryMatchedCount = $recommendations->count();

        /*
        |--------------------------------------------------------------------------
        | Step 5 — Compatibility Score
        |--------------------------------------------------------------------------
        */

        $recommendations = $this->compatibility->calculate(
            company: $company,
            candidates: $recommendations,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Step 6 — Recommendation Score
        |--------------------------------------------------------------------------
        */

        $recommendations = $this->score->calculate(
            company: $company,
            candidates: $recommendations,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Step 7 — Confidence Score
        |--------------------------------------------------------------------------
        */

        $recommendations = $this->confidence->calculate(
            company: $company,
            candidates: $recommendations,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Step 8 — Ranking
        |--------------------------------------------------------------------------
        */

        $recommendations = $this->ranking->rank(
            $recommendations
        );

        /*
        |--------------------------------------------------------------------------
        | Step 9 — Assign Rank
        |--------------------------------------------------------------------------
        |
        | Ranking score and ordering have already been determined.
        | This step only assigns the visible ordinal rank.
        |
        */

        $recommendations = $recommendations
            ->values()
            ->map(
                function (
                    array $recommendation,
                    int $index
                ): array {

                    $recommendation['rank'] =
                        $index + 1;

                    return $recommendation;
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Step 10 — Recommendation Reasons
        |--------------------------------------------------------------------------
        |
        | Produces deterministic evidence-based reasons from intelligence
        | already generated by the upstream recommendation pipeline.
        |
        */

        $recommendations = $this->reason->build(
            company: $company,
            candidates: $recommendations,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Step 11 — Recommendation Explanation
        |--------------------------------------------------------------------------
        |
        | Converts structured recommendation intelligence and evidence into
        | concise human-readable business explanations.
        |
        | This step does not modify scoring or ranking.
        |
        */

        $recommendations = $this->explanation->build(
            company: $company,
            candidates: $recommendations,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Step 12 — Metadata
        |--------------------------------------------------------------------------
        */

        $metadata = $this->metadata->build(
            company: $company,
            recommendations: $recommendations,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [
            'candidate_count' =>
                $candidates->count(),

            'evaluated_count' =>
                $evaluatedCount,

            'eligible_count' =>
                $eligibleCount,

            'discovery_matched_count' =>
                $discoveryMatchedCount,

            'recommended_count' =>
                $recommendations->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Convert Collection to Array
        |--------------------------------------------------------------------------
        */

        $recommendationArray = $recommendations
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return new RecommendationResult(
            engine: 'DIGESTEX Recommendation Intelligence Engine',
            version: '2.1',
            companyId: $company->id,
            generatedAt: Carbon::now(),
            recommendations: $recommendationArray,
            statistics: $statistics,
            metadata: $metadata,
        );
    }
}