<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Readiness\Capability;

use App\Services\Trade\Intelligence\Capability\CapabilityOrchestrator;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Capability Readiness Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Consolidate supplier capability intelligence into a unified
 * readiness layer for executive decision making.
 *
 * Capability Readiness evaluates what a company is capable of
 * offering to buyers, brands and global business partners.
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
class CapabilityReadinessService
{
    public function __construct(
        protected CapabilityOrchestrator $capability,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Material Capability
     * --------------------------------------------------------------------------
     */
    public function material(): array
    {
        return $this->capability->material();
    }

    /**
     * --------------------------------------------------------------------------
     * Production Capability
     * --------------------------------------------------------------------------
     */
    public function production(): array
    {
        return $this->capability->production();
    }

    /**
     * --------------------------------------------------------------------------
     * Development Capability
     * --------------------------------------------------------------------------
     */
    public function development(): array
    {
        return $this->capability->development();
    }

    /**
     * --------------------------------------------------------------------------
     * Innovation Capability
     * --------------------------------------------------------------------------
     */
    public function innovation(): array
    {
        return $this->capability->innovation();
    }

    /**
     * --------------------------------------------------------------------------
     * Sampling Capability
     * --------------------------------------------------------------------------
     */
    public function sampling(): array
    {
        return $this->capability->sampling();
    }

    /**
     * --------------------------------------------------------------------------
     * Commercial Capability
     * --------------------------------------------------------------------------
     */
    public function commercial(): array
    {
        return $this->capability->commercial();
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability Capability
     * --------------------------------------------------------------------------
     */
    public function sustainability(): array
    {
        return $this->capability->sustainability();
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(): array
    {
        return [

            'material' => $this->material(),

            'production' => $this->production(),

            'development' => $this->development(),

            'innovation' => $this->innovation(),

            'sampling' => $this->sampling(),

            'commercial' => $this->commercial(),

            'sustainability' => $this->sustainability(),

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Available Capability Groups
     * --------------------------------------------------------------------------
     */
    public function groups(): array
    {
        return [

            'material',

            'production',

            'development',

            'innovation',

            'sampling',

            'commercial',

            'sustainability',

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
     * Complete Capability Readiness
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