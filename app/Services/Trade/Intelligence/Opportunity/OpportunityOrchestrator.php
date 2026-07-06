<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Opportunity;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Opportunity Orchestrator
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for Business Opportunity Intelligence.
 *
 * Business Opportunity Intelligence consolidates all opportunity
 * domains into a unified framework for identifying and evaluating
 * business opportunities across the global textile ecosystem.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which RFQs should be prioritized?
 * • Which material opportunities fit our capability?
 * • Which buyers should we approach?
 * • Which technology investments are relevant?
 * • Which partnerships should be developed?
 * • Which innovation opportunities deserve attention?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturers
 * • Discover qualified business opportunities
 * • Prioritize high-value opportunities
 * • Expand into new markets
 *
 * Buyers & Brands
 * • Identify capable manufacturing partners
 * • Reduce sourcing risk
 *
 * Technology Providers
 * • Reach companies with genuine technology needs
 *
 * Investors
 * • Discover investment-ready companies
 *
 * This service NEVER:
 * • Queries database
 * • Calculates scores
 * • Performs AI reasoning
 * • Executes recommendations
 *
 * Used by:
 * - Executive Dashboard
 * - Executive AI
 * - Company Intelligence
 * - Business Opportunity Center
 */
class OpportunityOrchestrator
{
    public function __construct(

        protected RFQOpportunityService $rfq,

        protected MaterialOpportunityService $material,

        protected BuyerOpportunityService $buyer,

        protected TechnologyOpportunityService $technology,

        protected InvestmentOpportunityService $investment,

        protected PartnershipOpportunityService $partnership,

        protected InnovationOpportunityService $innovation,

        protected OpportunityScoreService $score,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * RFQ Opportunity
     * --------------------------------------------------------------------------
     */
    public function rfq(): array
    {
        return $this->rfq->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Material Opportunity
     * --------------------------------------------------------------------------
     */
    public function material(): array
    {
        return $this->material->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Buyer Opportunity
     * --------------------------------------------------------------------------
     */
    public function buyer(): array
    {
        return $this->buyer->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Technology Opportunity
     * --------------------------------------------------------------------------
     */
    public function technology(): array
    {
        return $this->technology->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Investment Opportunity
     * --------------------------------------------------------------------------
     */
    public function investment(): array
    {
        return $this->investment->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Partnership Opportunity
     * --------------------------------------------------------------------------
     */
    public function partnership(): array
    {
        return $this->partnership->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Innovation Opportunity
     * --------------------------------------------------------------------------
     */
    public function innovation(): array
    {
        return $this->innovation->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Opportunity Score Framework
     * --------------------------------------------------------------------------
     */
    public function score(): array
    {
        return $this->score->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Business Opportunity Intelligence
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            'rfq' => $this->rfq(),

            'material' => $this->material(),

            'buyer' => $this->buyer(),

            'technology' => $this->technology(),

            'investment' => $this->investment(),

            'partnership' => $this->partnership(),

            'innovation' => $this->innovation(),

            'score' => $this->score(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Opportunity Domains
     * --------------------------------------------------------------------------
     */
    public function groups(): array
    {
        return [

            'rfq',

            'material',

            'buyer',

            'technology',

            'investment',

            'partnership',

            'innovation',

            'score',

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

            'domains' => count($this->groups()),

            'framework' => 'Business Opportunity Intelligence',

            'version' => '1.0',

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(): array
    {
        return [

            'framework' => 'Business Opportunity Intelligence',

            'domains' => $this->groups(),

            'statistics' => $this->statistics(),

        ];
    }
}