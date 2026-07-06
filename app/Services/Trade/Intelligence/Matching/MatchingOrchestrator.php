<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Matching;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Matching Orchestrator
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for Matching Intelligence.
 *
 * Matching Intelligence consolidates all matching domains
 * into a unified decision intelligence layer used across
 * the DIGESTEX Global Textile Intelligence Ecosystem.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which supplier best matches the opportunity?
 * • Which buyer is most suitable?
 * • Which brand aligns with supplier capability?
 * • Which material satisfies buyer requirements?
 * • Which technology partner is appropriate?
 * • Which investment opportunity fits the company?
 * • Which service provider should be recommended?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Discover qualified buyers
 * • Find strategic partners
 * • Increase business opportunities
 *
 * Buyer / Brand
 *
 * • Identify qualified suppliers
 * • Reduce sourcing risk
 *
 * Industry
 *
 * • Improve ecosystem connectivity
 * • Accelerate business collaboration
 *
 * This service NEVER:
 *
 * • Queries database
 * • Calculates scores
 * • Performs AI reasoning
 * • Executes recommendations
 *
 * Used by:
 *
 * - Business Opportunity Engine
 * - Executive AI
 * - Executive Dashboard
 * - Company Intelligence
 */
class MatchingOrchestrator
{
    public function __construct(

        protected SupplierMatchingService $supplier,

        protected BuyerMatchingService $buyer,

        protected BrandMatchingService $brand,

        protected MaterialMatchingService $material,

        protected TechnologyMatchingService $technology,

        protected InvestmentMatchingService $investment,

        protected ServiceProviderMatchingService $serviceProvider,

        protected OpportunityMatchingService $opportunity,

        protected MatchingScoreService $score,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Supplier Matching
     * --------------------------------------------------------------------------
     */
    public function supplier(): array
    {
        return $this->supplier->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Buyer Matching
     * --------------------------------------------------------------------------
     */
    public function buyer(): array
    {
        return $this->buyer->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Brand Matching
     * --------------------------------------------------------------------------
     */
    public function brand(): array
    {
        return $this->brand->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Material Matching
     * --------------------------------------------------------------------------
     */
    public function material(): array
    {
        return $this->material->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Technology Matching
     * --------------------------------------------------------------------------
     */
    public function technology(): array
    {
        return $this->technology->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Investment Matching
     * --------------------------------------------------------------------------
     */
    public function investment(): array
    {
        return $this->investment->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Service Provider Matching
     * --------------------------------------------------------------------------
     */
    public function serviceProvider(): array
    {
        return $this->serviceProvider->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Opportunity Matching
     * --------------------------------------------------------------------------
     */
    public function opportunity(): array
    {
        return $this->opportunity->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Matching Score Framework
     * --------------------------------------------------------------------------
     */
    public function score(): array
    {
        return $this->score->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Matching Intelligence
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            'supplier' => $this->supplier(),

            'buyer' => $this->buyer(),

            'brand' => $this->brand(),

            'material' => $this->material(),

            'technology' => $this->technology(),

            'investment' => $this->investment(),

            'service_provider' => $this->serviceProvider(),

            'opportunity' => $this->opportunity(),

            'score' => $this->score(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Available Matching Domains
     * --------------------------------------------------------------------------
     */
    public function groups(): array
    {
        return [

            'supplier',

            'buyer',

            'brand',

            'material',

            'technology',

            'investment',

            'service_provider',

            'opportunity',

            'score',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Matching Framework Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'domains' => count($this->groups()),

            'framework' => 'Matching Intelligence',

            'version' => '1.0',

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}