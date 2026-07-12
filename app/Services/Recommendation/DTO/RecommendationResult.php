<?php

declare(strict_types=1);

namespace App\Services\Recommendation\DTO;

use Carbon\Carbon;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Result DTO
 * ==========================================================================
 *
 * Standard DTO returned by Recommendation Engine.
 *
 * Used by:
 *
 * • Smart Business Matching™
 * • Build My Supply Chain™
 * • Buyer Discovery™
 * • Executive AI™
 *
 * Version:
 * 1.0
 */
final readonly class RecommendationResult
{
    public function __construct(

        public string $engine,

        public string $version,

        public int $companyId,

        public Carbon $generatedAt,

        public array $recommendations,

        public array $statistics = [],

        public array $metadata = [],

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Convert DTO to Array
     * --------------------------------------------------------------------------
     */
    public function toArray(): array
    {
        return [

            'engine' => $this->engine,

            'version' => $this->version,

            'company_id' => $this->companyId,

            'generated_at' => $this->generatedAt
                ->toDateTimeString(),

            'recommendations' => $this->recommendations,

            'statistics' => $this->statistics,

            'metadata' => $this->metadata,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Total Recommendations
     * --------------------------------------------------------------------------
     */
    public function total(): int
    {
        return count(

            $this->recommendations

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Has Recommendations
     * --------------------------------------------------------------------------
     */
    public function hasRecommendations(): bool
    {
        return ! empty(

            $this->recommendations

        );
    }

    /**
     * --------------------------------------------------------------------------
     * First Recommendation
     * --------------------------------------------------------------------------
     */
    public function first(): ?array
    {
        return

            $this->recommendations[0]

            ?? null;
    }

    /**
     * --------------------------------------------------------------------------
     * Recommendation Collection
     * --------------------------------------------------------------------------
     */
    public function recommendations(): array
    {
        return

            $this->recommendations;
    }

    /**
     * --------------------------------------------------------------------------
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return

            $this->statistics;
    }

    /**
     * --------------------------------------------------------------------------
     * Metadata
     * --------------------------------------------------------------------------
     */
    public function metadata(): array
    {
        return

            $this->metadata;
    }
}