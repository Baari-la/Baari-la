<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Contracts;

use App\Models\Company;
use App\Services\Recommendation\DTO\RecommendationResult;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Engine Contract
 * ==========================================================================
 *
 * Defines the contract for every Recommendation Engine implementation.
 *
 * Implementations:
 *
 * • RecommendationEngine
 * • AIRecommendationEngine (future)
 * • EnterpriseRecommendationEngine (future)
 *
 * Version:
 * 1.0
 */
interface RecommendationEngineContract
{
    /**
     * --------------------------------------------------------------------------
     * Generate Recommendation
     * --------------------------------------------------------------------------
     */
    public function recommend(

        Company $company,

        array $context = [],

    ): RecommendationResult;
}