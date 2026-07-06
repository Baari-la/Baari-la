<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Readiness\Opportunity;

use App\Services\Trade\Intelligence\Readiness\Capability\CapabilityReadinessService;
use App\Services\Trade\Intelligence\Readiness\Compliance\ComplianceReadinessService;
use App\Services\Trade\Intelligence\Readiness\SupplyChain\SupplyChainReadinessService;
use App\Services\Trade\Intelligence\Readiness\Market\MarketReadinessService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Opportunity Readiness Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Consolidate all readiness domains into a unified opportunity
 * readiness layer.
 *
 * Opportunity Readiness prepares companies for business
 * opportunities by combining capability, compliance,
 * supply chain and market readiness.
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
class OpportunityReadinessService
{
    public function __construct(

        protected CapabilityReadinessService $capability,

        protected ComplianceReadinessService $compliance,

        protected SupplyChainReadinessService $supplyChain,

        protected MarketReadinessService $market,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Capability Readiness
     * --------------------------------------------------------------------------
     */
    public function capability(): array
    {
        return $this->capability->summary();
    }

    /**
     * --------------------------------------------------------------------------
     * Compliance Readiness
     * --------------------------------------------------------------------------
     */
    public function compliance(): array
    {
        return $this->compliance->summary();
    }

    /**
     * --------------------------------------------------------------------------
     * Supply Chain Readiness
     * --------------------------------------------------------------------------
     */
    public function supplyChain(): array
    {
        return $this->supplyChain->summary();
    }

    /**
     * --------------------------------------------------------------------------
     * Market Readiness
     * --------------------------------------------------------------------------
     */
    public function market(): array
    {
        return $this->market->summary();
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(): array
    {
        return [

            'capability' => $this->capability(),

            'compliance' => $this->compliance(),

            'supply_chain' => $this->supplyChain(),

            'market' => $this->market(),

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Available Readiness Domains
     * --------------------------------------------------------------------------
     */
    public function groups(): array
    {
        return [

            'capability',

            'compliance',

            'supply_chain',

            'market',

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
     * Complete Opportunity Readiness
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