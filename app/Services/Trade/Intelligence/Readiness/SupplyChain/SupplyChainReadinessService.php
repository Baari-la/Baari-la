<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Readiness\SupplyChain;

use App\Services\Trade\Intelligence\SupplyChain\SupplyChainOrchestrator;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Supply Chain Readiness Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Consolidate supply chain intelligence into a unified
 * business readiness layer.
 *
 * Supply Chain Readiness evaluates how prepared a company is
 * to support reliable, responsive and sustainable supply chains.
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
class SupplyChainReadinessService
{
    public function __construct(
        protected SupplyChainOrchestrator $supplyChain,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Supply Risk
     * --------------------------------------------------------------------------
     */
    public function supplyRisk(): array
    {
        return $this->supplyChain->supplyRisk();
    }

    /**
     * --------------------------------------------------------------------------
     * Lead Time
     * --------------------------------------------------------------------------
     */
    public function leadTime(): array
    {
        return $this->supplyChain->leadTime();
    }

    /**
     * --------------------------------------------------------------------------
     * Raw Material
     * --------------------------------------------------------------------------
     */
    public function rawMaterial(): array
    {
        return $this->supplyChain->rawMaterial();
    }

    /**
     * --------------------------------------------------------------------------
     * Capacity
     * --------------------------------------------------------------------------
     */
    public function capacity(): array
    {
        return $this->supplyChain->capacity();
    }

    /**
     * --------------------------------------------------------------------------
     * Logistics
     * --------------------------------------------------------------------------
     */
    public function logistics(): array
    {
        return $this->supplyChain->logistics();
    }

    /**
     * --------------------------------------------------------------------------
     * Supplier Network
     * --------------------------------------------------------------------------
     */
    public function supplierNetwork(): array
    {
        return $this->supplyChain->supplierNetwork();
    }

    /**
     * --------------------------------------------------------------------------
     * Traceability
     * --------------------------------------------------------------------------
     */
    public function traceability(): array
    {
        return $this->supplyChain->traceability();
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(): array
    {
        return [

            'supply_risk' => $this->supplyRisk(),

            'lead_time' => $this->leadTime(),

            'raw_material' => $this->rawMaterial(),

            'capacity' => $this->capacity(),

            'logistics' => $this->logistics(),

            'supplier_network' => $this->supplierNetwork(),

            'traceability' => $this->traceability(),

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

            'supply_risk',

            'lead_time',

            'raw_material',

            'capacity',

            'logistics',

            'supplier_network',

            'traceability',

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
     * Complete Supply Chain Readiness
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