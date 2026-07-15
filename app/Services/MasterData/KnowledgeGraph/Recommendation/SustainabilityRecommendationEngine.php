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
 * Sustainability Recommendation Engine
 * ==========================================================================
 *
 * Recommends sustainability practices using the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Sustainability recommendation
 * - ESG recommendation
 * - Sustainability tag discovery
 * - Green manufacturing recommendation
 *
 * This engine operates on Master Data only.
 *
 * ==========================================================================
 */
final class SustainabilityRecommendationEngine
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
     * Recommend
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
        | Direct Sustainability Relations
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->neighbors($nodeId)
                ->filter(
                    fn (GraphNode $node) =>
                        $this->isSustainabilityNode($node)
                )

        );

        /*
        |--------------------------------------------------------------------------
        | Reachable Sustainability
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->reachable($nodeId)
                ->filter(
                    fn (GraphNode $node) =>
                        $this->isSustainabilityNode($node)
                )

        );

        /*
        |--------------------------------------------------------------------------
        | Similar Sustainability
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->similar($nodeId)
                ->filter(
                    fn (GraphNode $node) =>
                        $this->isSustainabilityNode($node)
                )

        );

        return $recommendations

            ->unique(
                fn (GraphNode $node) => $node->id()
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
     * Sustainability Catalog
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function sustainabilityTags(): Collection
    {
        return $this->query

            ->bySchema('sustainability_tags.php')

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

            'reason' =>

                $this->reason(
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
            return 'similar_sustainability';
        }

        if (
            $this->query
                ->neighbors($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'direct_sustainability';
        }

        if (
            $this->query
                ->reachable($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'related_sustainability';
        }

        return 'recommended_sustainability';
    }

    /**
     * =========================================================================
     * Is Sustainability Node
     * =========================================================================
     */
    protected function isSustainabilityNode(
        GraphNode $node
    ): bool
    {
        $schema = strtolower(
            $node->schema()
        );

        return

            str_contains($schema, 'sustainability')

            ||

            str_contains($schema, 'esg')

            ||

            str_contains($schema, 'green')

            ||

            str_contains($schema, 'carbon')

            ||

            str_contains($schema, 'environment');
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