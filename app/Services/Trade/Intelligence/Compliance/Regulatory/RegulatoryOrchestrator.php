<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Regulatory;

use App\Services\Trade\Intelligence\Compliance\Regulatory\Domestic\DomesticRegulatoryComplianceService;
use App\Services\Trade\Intelligence\Compliance\Regulatory\Market\MarketRegulatoryComplianceService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Regulatory Orchestrator
 * ==========================================================================
 *
 * Central orchestrator for Regulatory Intelligence.
 *
 * Responsible for:
 *
 * • Domestic Regulatory Knowledge
 * • Market Regulatory Knowledge
 *
 * This service NEVER:
 *
 * • Queries database
 * • Performs analytics
 * • Executes matching
 *
 * Used by:
 *
 * - ComplianceOrchestrator
 * - Supplier Readiness Intelligence
 * - MatchingEngine
 * - Company Intelligence
 * - Market Intelligence
 */
class RegulatoryOrchestrator
{
    public function __construct(

        protected DomesticRegulatoryComplianceService $domestic,

        protected MarketRegulatoryComplianceService $market,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Domestic Regulatory
     * --------------------------------------------------------------------------
     */
    public function domestic(): array
    {
        return $this->domestic->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Market Regulatory
     * --------------------------------------------------------------------------
     */
    public function market(): array
    {
        return $this->market->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Regulatory Intelligence
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            'domestic' => $this->domestic(),

            'market' => $this->market(),

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

            'domestic',

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
}