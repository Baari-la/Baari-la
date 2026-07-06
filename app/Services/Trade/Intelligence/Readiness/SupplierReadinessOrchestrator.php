<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Readiness;

use App\Services\Trade\Intelligence\Readiness\Executive\ExecutiveReadinessService;
use App\Services\Trade\Intelligence\Readiness\Capability\CapabilityReadinessService;
use App\Services\Trade\Intelligence\Readiness\Compliance\ComplianceReadinessService;
use App\Services\Trade\Intelligence\Readiness\SupplyChain\SupplyChainReadinessService;
use App\Services\Trade\Intelligence\Readiness\Market\MarketReadinessService;
use App\Services\Trade\Intelligence\Readiness\Opportunity\OpportunityReadinessService;
use App\Services\Trade\Intelligence\Readiness\Score\ReadinessScoreService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Supplier Readiness Orchestrator
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for Supplier Readiness Intelligence.
 *
 * Supplier Readiness consolidates all readiness domains into
 * a unified business intelligence layer for manufacturers,
 * buyers, brands and ecosystem partners.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Is this supplier ready?
 * • Which readiness domains are available?
 * • What are the supplier's strengths?
 * • Which areas require improvement?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve business readiness
 * • Increase buyer confidence
 * • Support export readiness
 *
 * Buyer / Brand
 *
 * • Evaluate supplier readiness
 * • Reduce sourcing risks
 *
 * Industry
 *
 * • Promote transparent supplier evaluation
 * • Strengthen global textile ecosystem
 *
 * This service NEVER:
 *
 * • Queries database
 * • Calculates scores
 * • Executes matching
 * • Generates AI
 *
 * Used by:
 *
 * - Executive Dashboard
 * - Company Intelligence
 * - Executive AI Summary
 * - Matching Engine
 * - Business Opportunity Engine
 */
class SupplierReadinessOrchestrator
{
    public function __construct(

        protected ExecutiveReadinessService $executive,

        protected CapabilityReadinessService $capability,

        protected ComplianceReadinessService $compliance,

        protected SupplyChainReadinessService $supplyChain,

        protected MarketReadinessService $market,

        protected OpportunityReadinessService $opportunity,

        protected ReadinessScoreService $score,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Readiness
     * --------------------------------------------------------------------------
     */
    public function executive(): array
    {
        return $this->executive->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Capability Readiness
     * --------------------------------------------------------------------------
     */
    public function capability(): array
    {
        return $this->capability->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Compliance Readiness
     * --------------------------------------------------------------------------
     */
    public function compliance(): array
    {
        return $this->compliance->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Supply Chain Readiness
     * --------------------------------------------------------------------------
     */
    public function supplyChain(): array
    {
        return $this->supplyChain->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Market Readiness
     * --------------------------------------------------------------------------
     */
    public function market(): array
    {
        return $this->market->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Opportunity Readiness
     * --------------------------------------------------------------------------
     */
    public function opportunity(): array
    {
        return $this->opportunity->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Readiness Framework
     * --------------------------------------------------------------------------
     */
    public function score(): array
    {
        return $this->score->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Supplier Readiness Intelligence
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            'executive' => $this->executive(),

            'capability' => $this->capability(),

            'compliance' => $this->compliance(),

            'supply_chain' => $this->supplyChain(),

            'market' => $this->market(),

            'opportunity' => $this->opportunity(),

            'score' => $this->score(),

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

            'executive',

            'capability',

            'compliance',

            'supply_chain',

            'market',

            'opportunity',

            'score',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Framework Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'domains' => count($this->groups()),

            'framework' => 'Supplier Readiness Intelligence',

            'version' => '1.0',

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}