<?php

declare(strict_types=1);

namespace App\Services\SupplyChain\DTO;

use Carbon\Carbon;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Supply Chain Recommendation Result
 * ==========================================================================
 *
 * Standard DTO returned by the
 * Supply Chain Recommendation Engine.
 *
 * Used by:
 *
 * • Build My Supply Chain™
 * • Buyer Discovery™
 * • Supply Chain Intelligence™
 * • Executive AI™
 *
 * Version:
 * 1.0
 */
final readonly class SupplyChainRecommendationResult
{
    public function __construct(

        /*
        |--------------------------------------------------------------------------
        | Engine
        |--------------------------------------------------------------------------
        */

        public string $engine,

        public string $version,

        public Carbon $generatedAt,

        /*
        |--------------------------------------------------------------------------
        | Company
        |--------------------------------------------------------------------------
        */

        public int $companyId,

        public string $role,

        public string $ecosystem,

        /*
        |--------------------------------------------------------------------------
        | Recommendation
        |--------------------------------------------------------------------------
        */

        public array $upstream,

        public array $downstream,

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        public array $statistics = [],

        /*
        |--------------------------------------------------------------------------
        | Metadata
        |--------------------------------------------------------------------------
        */

        public array $metadata = [],

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Convert DTO To Array
     * --------------------------------------------------------------------------
     */
    public function toArray(): array
    {
        return [

            'engine' => $this->engine,

            'version' => $this->version,

            'generated_at' =>

                $this->generatedAt->toDateTimeString(),

            'company_id' => $this->companyId,

            'role' => $this->role,

            'ecosystem' => $this->ecosystem,

            'upstream' => $this->upstream,

            'downstream' => $this->downstream,

            'statistics' => $this->statistics,

            'metadata' => $this->metadata,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Total Upstream Companies
     * --------------------------------------------------------------------------
     */
    public function totalUpstream(): int
    {
        return count($this->upstream);
    }

    /**
     * --------------------------------------------------------------------------
     * Total Downstream Companies
     * --------------------------------------------------------------------------
     */
    public function totalDownstream(): int
    {
        return count($this->downstream);
    }

    /**
     * --------------------------------------------------------------------------
     * Total Recommendations
     * --------------------------------------------------------------------------
     */
    public function totalRecommendations(): int
    {
        return

            $this->totalUpstream()

            +

            $this->totalDownstream();
    }

    /**
     * --------------------------------------------------------------------------
     * Has Recommendations
     * --------------------------------------------------------------------------
     */
    public function hasRecommendations(): bool
    {
        return

            $this->totalRecommendations() > 0;
    }

    /**
     * --------------------------------------------------------------------------
     * Upstream Recommendations
     * --------------------------------------------------------------------------
     */
    public function upstream(): array
    {
        return $this->upstream;
    }

    /**
     * --------------------------------------------------------------------------
     * Downstream Recommendations
     * --------------------------------------------------------------------------
     */
    public function downstream(): array
    {
        return $this->downstream;
    }

    /**
     * --------------------------------------------------------------------------
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return $this->statistics;
    }

    /**
     * --------------------------------------------------------------------------
     * Metadata
     * --------------------------------------------------------------------------
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}