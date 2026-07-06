<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance;

use App\Services\Trade\Intelligence\Compliance\Certification\CertificationComplianceService;
use App\Services\Trade\Intelligence\Compliance\Certification\CertificationBodyService;
use App\Services\Trade\Intelligence\Compliance\Testing\TestingComplianceService;
use App\Services\Trade\Intelligence\Compliance\Social\SocialComplianceService;
use App\Services\Trade\Intelligence\Compliance\Environmental\EnvironmentalComplianceService;
use App\Services\Trade\Intelligence\Compliance\Regulatory\RegulatoryOrchestrator;
use App\Services\Trade\Intelligence\Compliance\Traceability\TraceabilityComplianceService;
use App\Services\Trade\Intelligence\Compliance\Governance\GovernanceComplianceService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Compliance Orchestrator
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for Compliance Intelligence.
 *
 * Compliance Intelligence consolidates all compliance domains
 * required by buyers, brands, regulators and global markets.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Is this supplier compliant?
 * • Which compliance areas are available?
 * • Which compliance domains support buyer requirements?
 * • How can compliance readiness be evaluated?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve compliance readiness
 * • Increase buyer confidence
 * • Reduce business risks
 *
 * Buyer / Brand
 *
 * • Evaluate supplier compliance
 * • Reduce sourcing risks
 *
 * Industry
 *
 * • Promote responsible manufacturing
 * • Improve transparency
 *
 * This service NEVER performs:
 *
 * • Database Query
 * • Analytics
 * • Matching
 * • Recommendation
 *
 * Used by:
 *
 * - SupplierReadinessIntelligence
 * - MatchingEngine
 * - OpportunityEngine
 * - Company Intelligence
 */
class ComplianceOrchestrator
{
    public function __construct(

        protected CertificationComplianceService $certification,

        protected CertificationBodyService $certificationBody,

        protected TestingComplianceService $testing,

        protected SocialComplianceService $social,

        protected EnvironmentalComplianceService $environment,

        protected RegulatoryOrchestrator $regulatory,

        protected TraceabilityComplianceService $traceability,

        protected GovernanceComplianceService $governance,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Certification
     * --------------------------------------------------------------------------
     */
    public function certification(): array
    {
        return $this->certification->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Certification Bodies
     * --------------------------------------------------------------------------
     */
    public function certificationBodies(): array
    {
        return $this->certificationBody->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Testing
     * --------------------------------------------------------------------------
     */
    public function testing(): array
    {
        return $this->testing->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Social
     * --------------------------------------------------------------------------
     */
    public function social(): array
    {
        return $this->social->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Environmental
     * --------------------------------------------------------------------------
     */
    public function environmental(): array
    {
        return $this->environment->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Regulatory
     * --------------------------------------------------------------------------
     */
    public function regulatory(): array
    {
        return $this->regulatory->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Traceability
     * --------------------------------------------------------------------------
     */
    public function traceability(): array
    {
        return $this->traceability->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Governance
     * --------------------------------------------------------------------------
     */
    public function governance(): array
    {
        return $this->governance->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Compliance Intelligence
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            'certification' => $this->certification(),

            'certification_bodies' => $this->certificationBodies(),

            'testing' => $this->testing(),

            'social' => $this->social(),

            'environmental' => $this->environmental(),

            'regulatory' => $this->regulatory(),

            'traceability' => $this->traceability(),

            'governance' => $this->governance(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Available Compliance Groups
     * --------------------------------------------------------------------------
     */
    public function groups(): array
    {
        return [

            'certification',

            'certification_bodies',

            'testing',

            'social',

            'environmental',

            'regulatory',

            'traceability',

            'governance',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Compliance Statistics
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