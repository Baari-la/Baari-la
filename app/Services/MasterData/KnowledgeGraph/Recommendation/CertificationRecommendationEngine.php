<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Recommendation;

use App\Services\MasterData\KnowledgeGraph\GraphNode;
use App\Services\MasterData\KnowledgeGraph\GraphQuery;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Certification Recommendation Engine
 * ==========================================================================
 *
 * Recommends certifications using the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Certification recommendation
 * - Compliance recommendation
 * - Sustainability recommendation
 * - Certification ranking
 *
 * This engine operates on Master Data only.
 *
 * ==========================================================================
 */
final class CertificationRecommendationEngine
{
    /**
     * Constructor.
     */
    public function __construct(
        protected GraphQuery $query,
        protected SimilarityEngine $similarity,
    ) {
    }

    /**
     * =========================================================================
     * Recommend Certifications
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function recommend(
        string $nodeId,
        int $limit = 10
    ): Collection
    {
        $source = $this->query->find($nodeId);

        if ($source === null) {
            return collect();
        }

        $recommendations = collect();

        /*
        |--------------------------------------------------------------------------
        | Direct Neighbor Certifications
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->neighbors($nodeId)

                ->filter(fn (GraphNode $node) =>

                    str_contains(

                        strtolower($node->schema()),

                        'certification'

                    )

                )

        );

        /*
        |--------------------------------------------------------------------------
        | Reachable Certifications
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->reachable($nodeId)

                ->filter(fn (GraphNode $node) =>

                    str_contains(

                        strtolower($node->schema()),

                        'certification'

                    )

                )

        );

        /*
        |--------------------------------------------------------------------------
        | Similar Certifications
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->similar($nodeId)

                ->filter(fn (GraphNode $node) =>

                    str_contains(

                        strtolower($node->schema()),

                        'certification'

                    )

                )

        );

        return $recommendations

            ->unique(

                fn (GraphNode $node) =>

                    $node->id()

            )

            ->map(

                fn (GraphNode $candidate) =>

                    $this->buildRecommendation(

                        $source,

                        $candidate

                    )

            )

            ->sortByDesc('score')

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Certification Catalog
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function certifications(): Collection
    {
        return $this->query

            ->bySchema(
                'certifications.php'
            )

            ->map(fn (GraphNode $node) => [

                'id' => $node->id(),

                'label' => $node->label(),

                'schema' => $node->schema(),

                'type' => $node->type(),

            ])

            ->values();
    }

    /**
     * =========================================================================
     * Best Recommendation
     * =========================================================================
     *
     * @return array<string,mixed>|null
     */
    public function best(
        string $nodeId
    ): ?array
    {
        return $this->recommend(
            $nodeId,
            1
        )->first();
    }

    /**
     * =========================================================================
     * Build Recommendation
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function buildRecommendation(
        GraphNode $source,
        GraphNode $candidate
    ): array
    {
        return [

            'id' => $candidate->id(),

            'label' => $candidate->label(),

            'schema' => $candidate->schema(),

            'type' => $candidate->type(),

            'score' =>

                $this->similarity

                    ->score(

                        $source,

                        $candidate

                    ),

            'analysis' =>

                $this->similarity

                    ->compare(

                        $source,

                        $candidate

                    ),

            'reason' => $this->reason(
                $source,
                $candidate
            ),

        ];
    }

    /**
     * =========================================================================
     * Recommendation Reason
     * =========================================================================
     */
    protected function reason(
        GraphNode $source,
        GraphNode $candidate
    ): string
    {
        if (
            $source->schema() ===
            $candidate->schema()
        ) {
            return 'similar_certification';
        }

        if (
            $this->query
                ->neighbors($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'direct_requirement';
        }

        if (
            $this->query
                ->reachable($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'related_requirement';
        }

        return 'recommended_certification';
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<int,array<string,mixed>>
     */
    public function toArray(
        string $nodeId,
        int $limit = 10
    ): array
    {
        return $this->recommend(
            $nodeId,
            $limit
        )->all();
    }
}