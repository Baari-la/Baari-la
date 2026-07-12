<?php

declare(strict_types=1);

namespace App\Services\SupplyChain\Contracts;

use App\Models\Company;
use App\Services\SupplyChain\DTO\SupplyChainRecommendationResult;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Supply Chain Recommendation Contract
 * ==========================================================================
 *
 * Contract for generating supply chain recommendations
 * based on a company's position within the textile value chain.
 *
 * Used by:
 *
 * • Build My Supply Chain™
 * • Buyer Discovery™
 * • Supply Chain Intelligence™
 * • Executive AI™
 *
 * Version:
 * 1.0
 */
interface SupplyChainRecommendationContract
{
    /**
     * --------------------------------------------------------------------------
     * Generate Supply Chain Recommendation
     * --------------------------------------------------------------------------
     *
     * Builds upstream and downstream recommendations
     * for the given company.
     */
    public function recommend(
        Company $company,
        array $context = [],
    ): SupplyChainRecommendationResult;
}