<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Readiness\Compliance;

use App\Services\Trade\Intelligence\Compliance\ComplianceOrchestrator;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Compliance Readiness Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Consolidate supplier compliance intelligence into a unified
 * readiness layer for executive decision making.
 *
 * Compliance Readiness evaluates whether a company satisfies
 * internationally recognized compliance requirements expected by
 * buyers, brands, regulators and export markets.
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
class ComplianceReadinessService
{
    public function __construct(
        protected ComplianceOrchestrator $compliance,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Certification
     * --------------------------------------------------------------------------
     */
    public function certification(): array
    {
        return $this->compliance->certification();
    }

    /**
     * --------------------------------------------------------------------------
     * Certification Bodies
     * --------------------------------------------------------------------------
     */
    public function certificationBodies(): array
    {
        return $this->compliance->certificationBodies();
    }

    /**
     * --------------------------------------------------------------------------
     * Testing
     * --------------------------------------------------------------------------
     */
    public function testing(): array
    {
        return $this->compliance->testing();
    }

    /**
     * --------------------------------------------------------------------------
     * Social Compliance
     * --------------------------------------------------------------------------
     */
    public function social(): array
    {
        return $this->compliance->social();
    }

    /**
     * --------------------------------------------------------------------------
     * Environmental Compliance
     * --------------------------------------------------------------------------
     */
    public function environmental(): array
    {
        return $this->compliance->environmental();
    }

    /**
     * --------------------------------------------------------------------------
     * Regulatory Compliance
     * --------------------------------------------------------------------------
     */
    public function regulatory(): array
    {
        return $this->compliance->regulatory();
    }

    /**
     * --------------------------------------------------------------------------
     * Traceability Compliance
     * --------------------------------------------------------------------------
     */
    public function traceability(): array
    {
        return $this->compliance->traceability();
    }

    /**
     * --------------------------------------------------------------------------
     * Governance Compliance
     * --------------------------------------------------------------------------
     */
    public function governance(): array
    {
        return $this->compliance->governance();
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(): array
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

            'generated_at' => now()->toDateTimeString(),

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
     * Complete Compliance Readiness
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