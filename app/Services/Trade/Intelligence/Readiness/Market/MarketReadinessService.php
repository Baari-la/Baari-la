<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Readiness\Market;

use App\Services\Trade\Intelligence\Compliance\Regulatory\RegulatoryOrchestrator;
use App\Services\Trade\Intelligence\Knowledge\KnowledgeOrchestrator;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Market Readiness Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Consolidate market intelligence into a unified readiness layer.
 *
 * Market Readiness evaluates how prepared a company is
 * to enter and compete in global export markets.
 *
 * This service NEVER:
 *
 * • Queries database
 * • Calculates scores
 * • Performs matching
 * • Generates recommendations
 *
 * Used by:
 *
 * - ExecutiveReadinessService
 * - ReadinessScoreService
 * - MatchingEngine
 * - OpportunityEngine
 * - Company Intelligence
 */
class MarketReadinessService
{
    public function __construct(

        protected KnowledgeOrchestrator $knowledge,

        protected RegulatoryOrchestrator $regulatory,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Market Knowledge
     * --------------------------------------------------------------------------
     */
    public function marketKnowledge(): array
    {
        return $this->knowledge->market();
    }

    /**
     * --------------------------------------------------------------------------
     * Industry Knowledge
     * --------------------------------------------------------------------------
     */
    public function industryKnowledge(): array
    {
        return $this->knowledge->industry();
    }

    /**
     * --------------------------------------------------------------------------
     * Buyer Knowledge
     * --------------------------------------------------------------------------
     */
    public function buyerKnowledge(): array
    {
        return $this->knowledge->buyer();
    }

    /**
     * --------------------------------------------------------------------------
     * Brand Knowledge
     * --------------------------------------------------------------------------
     */
    public function brandKnowledge(): array
    {
        return $this->knowledge->brand();
    }

    /**
     * --------------------------------------------------------------------------
     * Seasonality
     * --------------------------------------------------------------------------
     */
    public function seasonality(): array
    {
        return $this->knowledge->seasonality();
    }

    /**
     * --------------------------------------------------------------------------
     * Commercial Knowledge
     * --------------------------------------------------------------------------
     */
    public function commercial(): array
    {
        return $this->knowledge->commercial();
    }

    /**
     * --------------------------------------------------------------------------
     * Market Regulation
     * --------------------------------------------------------------------------
     */
    public function marketRegulation(): array
    {
        return $this->regulatory->market();
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(): array
    {
        return [

            'market' => $this->marketKnowledge(),

            'industry' => $this->industryKnowledge(),

            'buyer' => $this->buyerKnowledge(),

            'brand' => $this->brandKnowledge(),

            'seasonality' => $this->seasonality(),

            'commercial' => $this->commercial(),

            'market_regulation' => $this->marketRegulation(),

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Available Groups
     * --------------------------------------------------------------------------
     */
    public function groups(): array
    {
        return [

            'market',

            'industry',

            'buyer',

            'brand',

            'seasonality',

            'commercial',

            'market_regulation',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'groups' => count($this->groups()),

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Market Readiness
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            'summary' => $this->summary(),

            'groups' => $this->groups(),

            'statistics' => $this->statistics(),

        ];
    }
}