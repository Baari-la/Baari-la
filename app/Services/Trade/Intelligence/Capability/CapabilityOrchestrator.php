<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability;

use App\Services\Trade\Intelligence\Capability\Material\MaterialCapabilityService;
use App\Services\Trade\Intelligence\Capability\Production\ProductionCapabilityService;
use App\Services\Trade\Intelligence\Capability\Development\DevelopmentCapabilityService;
use App\Services\Trade\Intelligence\Capability\Innovation\InnovationCapabilityService;
use App\Services\Trade\Intelligence\Capability\Sampling\SamplingCapabilityService;
use App\Services\Trade\Intelligence\Capability\Commercial\CommercialCapabilityService;
use App\Services\Trade\Intelligence\Capability\Sustainability\SustainabilityCapabilityService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Capability Orchestrator
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for Capability Intelligence.
 *
 * This service aggregates all supplier capabilities into
 * a unified intelligence layer used throughout DIGESTEX.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What capabilities does this supplier have?
 * • What business value can this supplier offer?
 * • Which capabilities are relevant to buyer requirements?
 * • How ready is this supplier for global opportunities?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase strengths
 * • Improve visibility
 * • Support business development
 *
 * Buyer / Brand
 *
 * • Evaluate supplier capabilities
 * • Reduce sourcing risks
 * • Accelerate supplier selection
 *
 * Industry
 *
 * • Standardize capability assessment
 * • Strengthen ecosystem collaboration
 *
 * This service NEVER:
 *
 * • Query the database
 * • Perform analytics
 * • Execute matching
 * • Generate recommendations
 *
 * Used by:
 *
 * - SupplierReadinessIntelligence
 * - MatchingEngine
 * - OpportunityEngine
 * - Company Intelligence
 */
class CapabilityOrchestrator
{
    public function __construct(

        protected MaterialCapabilityService $material,

        protected ProductionCapabilityService $production,

        protected DevelopmentCapabilityService $development,

        protected InnovationCapabilityService $innovation,

        protected SamplingCapabilityService $sampling,

        protected CommercialCapabilityService $commercial,

        protected SustainabilityCapabilityService $sustainability,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Material Capability
     * --------------------------------------------------------------------------
     */
    public function material(): array
    {
        return $this->material->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Production Capability
     * --------------------------------------------------------------------------
     */
    public function production(): array
    {
        return $this->production->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Development Capability
     * --------------------------------------------------------------------------
     */
    public function development(): array
    {
        return $this->development->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Innovation Capability
     * --------------------------------------------------------------------------
     */
    public function innovation(): array
    {
        return $this->innovation->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Sampling Capability
     * --------------------------------------------------------------------------
     */
    public function sampling(): array
    {
        return $this->sampling->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Commercial Capability
     * --------------------------------------------------------------------------
     */
    public function commercial(): array
    {
        return $this->commercial->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability Capability
     * --------------------------------------------------------------------------
     */
    public function sustainability(): array
    {
        return $this->sustainability->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Capability Intelligence
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            'material' => $this->material(),

            'production' => $this->production(),

            'development' => $this->development(),

            'innovation' => $this->innovation(),

            'sampling' => $this->sampling(),

            'commercial' => $this->commercial(),

            'sustainability' => $this->sustainability(),

        ];
    }
}