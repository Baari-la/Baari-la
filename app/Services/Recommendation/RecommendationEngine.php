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

use App\Services\Recommendation\Reason\RecommendationReasonService;

use App\Services\Recommendation\Ranking\RecommendationRankingService;

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
 * • Smart Business Matching™
 * • Build My Supply Chain™
 * • Buyer Discovery™
 * • RFQ Intelligence™
 * • Opportunity Intelligence™
 * • Executive AI™
 *
 * Architecture
 *
 * Candidate Loader
 *          ↓
 * Candidate Evaluation
 *          ↓
 * Compatibility Score
 *          ↓
 * Recommendation Score
 *          ↓
 * Recommendation Reason
 *          ↓
 * Confidence Score
 *          ↓
 * Ranking
 *
 * Version:
 * 1.0
 */
class RecommendationEngine implements RecommendationEngineContract
{
    public function __construct(

        /*
        |--------------------------------------------------------------------------
        | Candidate
        |--------------------------------------------------------------------------
        */

        protected CandidateLoader $loader,

        protected CandidateEvaluator $evaluator,

        /*
        |--------------------------------------------------------------------------
        | Score
        |--------------------------------------------------------------------------
        */

        protected CompatibilityScoreService $compatibility,

        protected RecommendationScoreService $score,

        protected ConfidenceScoreService $confidence,

        /*
        |--------------------------------------------------------------------------
        | Recommendation
        |--------------------------------------------------------------------------
        */

        protected RecommendationReasonService $reason,

        protected RecommendationRankingService $ranking,

        /*
        |--------------------------------------------------------------------------
        | Metadata
        |--------------------------------------------------------------------------
        */

        protected RecommendationMetadataService $metadata,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Generate Recommendation
     * --------------------------------------------------------------------------
     */
    public function recommend(
        Company $company,
        array $context = [],
    ): RecommendationResult {

        /*
        |--------------------------------------------------------------------------
        | Step 1
        | Load Candidate Companies
        |--------------------------------------------------------------------------
        */

        $candidates = $this->loader->load(

            company: $company,

            context: $context,

        );

        /*
        |--------------------------------------------------------------------------
        | Step 2
        | Evaluate Candidates
        |--------------------------------------------------------------------------
        */

        $evaluated = $this->evaluator->evaluate(

            company: $company,

            candidates: $candidates,

            context: $context,

        );

        /*
        |--------------------------------------------------------------------------
        | Step 3
        | Compatibility Score
        |--------------------------------------------------------------------------
        */

        $evaluated = $this->compatibility->calculate(

            company: $company,

            candidates: $evaluated,

            context: $context,

        );

        /*
        |--------------------------------------------------------------------------
        | Step 4
        | Recommendation Score
        |--------------------------------------------------------------------------
        */

        $evaluated = $this->score->calculate(

            company: $company,

            candidates: $evaluated,

            context: $context,

        );

        /*
        |--------------------------------------------------------------------------
        | Step 5
        | Recommendation Reasons
        |--------------------------------------------------------------------------
        */

        $evaluated = $this->reason->build(

            company: $company,

            candidates: $evaluated,

            context: $context,

        );

        /*
        |--------------------------------------------------------------------------
        | Step 6
        | Confidence Score
        |--------------------------------------------------------------------------
        */

        $evaluated = $this->confidence->calculate(

            company: $company,

            candidates: $evaluated,

            context: $context,

        );

       /*
|--------------------------------------------------------------------------
| Step 7
| Ranking
|--------------------------------------------------------------------------
/*
|--------------------------------------------------------------------------
| Step 7
| Ranking
|--------------------------------------------------------------------------
*/

$recommendations = $this->ranking->rank(

    $evaluated

);

        /*
        |--------------------------------------------------------------------------
        | Step 8
        | Metadata
        |--------------------------------------------------------------------------
        */

        $metadata = $this->metadata->build(

            company: $company,

            recommendations: $recommendations,

            context: $context,

        );

        /*
|--------------------------------------------------------------------------
| Convert Collection to Array
|--------------------------------------------------------------------------
*/

$recommendations =

    $recommendations

        ->values()

        ->all();
        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return new RecommendationResult(

            engine: 'DIGESTEX Recommendation Intelligence Engine',

            version: '1.0',

            companyId: $company->id,

            generatedAt: Carbon::now(),

            recommendations: $recommendations,

            statistics: [

                'candidate_count' => count($candidates),

                'recommended_count' => count($recommendations),

            ],

            metadata: $metadata,

        );
    }
}