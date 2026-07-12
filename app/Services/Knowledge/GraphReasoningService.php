<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Graph Reasoning Service
 * ==========================================================================
 *
 * Performs semantic reasoning over the Textile Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * • Executive AI Reasoning
 * • Export Readiness
 * • Capability Gap Analysis
 * • Supply Chain Analysis
 * • Recommendation Engine
 * • Knowledge Explanation
 *
 * Used by:
 *
 * • Executive AI
 * • Company Intelligence
 * • Recommendation Engine
 *
 */

class GraphReasoningService
{
    public function __construct(
        protected GraphTraversalService $graph,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Executive Summary
    |--------------------------------------------------------------------------
    */

    public function executiveSummary(
        string $businessRole
    ): array {

        return [

            'technologies'
                => $this->graph
                    ->recommendedTechnologies($businessRole),

            'certifications'
                => $this->requiredCertifications($businessRole),

            'markets'
                => $this->recommendedMarkets($businessRole),

            'recommendation'
                => $this->recommendations($businessRole),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Technology
    |--------------------------------------------------------------------------
    */

    public function recommendedTechnologies(
        string $businessRole
    ): Collection {

        return $this->graph
            ->recommendedTechnologies($businessRole);

    }

    /*
    |--------------------------------------------------------------------------
    | Certification
    |--------------------------------------------------------------------------
    */

    public function requiredCertifications(
        string $businessRole
    ): Collection {

        return $this->recommendedTechnologies($businessRole)

            ->flatMap(

                fn ($technology)

                    => $this->graph

                        ->requiredCertifications(

                            $technology->id()

                        )

            )

            ->unique(

                fn ($node)

                    => $node->id()

            )

            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Markets
    |--------------------------------------------------------------------------
    */

    public function recommendedMarkets(
        string $businessRole
    ): Collection {

        return $this->requiredCertifications(

            $businessRole

        )

        ->flatMap(

            fn ($certification)

                => $this->graph

                    ->exportMarkets(

                        $certification->id()

                    )

        )

        ->unique(

            fn ($market)

                => $market->id()

        )

        ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Recommendations
    |--------------------------------------------------------------------------
    */

    public function recommendations(
        string $businessRole
    ): array {

        return [

            'technology_count'

                => $this

                    ->recommendedTechnologies($businessRole)

                    ->count(),

            'certification_count'

                => $this

                    ->requiredCertifications($businessRole)

                    ->count(),

            'market_count'

                => $this

                    ->recommendedMarkets($businessRole)

                    ->count(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Readiness Score
    |--------------------------------------------------------------------------
    */

    public function readinessScore(
        string $businessRole
    ): int {

        $technology = $this
            ->recommendedTechnologies($businessRole)
            ->count();

        $certification = $this
            ->requiredCertifications($businessRole)
            ->count();

        $market = $this
            ->recommendedMarkets($businessRole)
            ->count();

        return min(

            100,

            ($technology * 5)

            + ($certification * 10)

            + ($market * 5)

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Explain
    |--------------------------------------------------------------------------
    */

    public function explain(
        string $businessRole
    ): array {

        return [

            'business_role'

                => $businessRole,

            'reasoning_path' => [

                'Business Role',

                'Technology',

                'Certification',

                'Market',

                'Buyer',

            ],

            'readiness_score'

                => $this->readinessScore(

                    $businessRole

                ),

        ];
    }
}