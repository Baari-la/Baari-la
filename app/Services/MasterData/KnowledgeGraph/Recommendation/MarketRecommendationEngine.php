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
 * Market Recommendation Engine
 * ==========================================================================
 *
 * Recommends target markets using the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Market recommendation
 * - Export market discovery
 * - Regional recommendation
 * - Market ranking
 *
 * This engine operates on Master Data only.
 *
 * ==========================================================================
 */
final class MarketRecommendationEngine
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
     * Recommend Markets
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
        | Direct Market Relations
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->neighbors($nodeId)
                ->filter(
                    fn (GraphNode $node) =>
                        $this->isMarketNode($node)
                )

        );

        /*
        |--------------------------------------------------------------------------
        | Reachable Markets
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->reachable($nodeId)
                ->filter(
                    fn (GraphNode $node) =>
                        $this->isMarketNode($node)
                )

        );

        /*
        |--------------------------------------------------------------------------
        | Similar Markets
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->similar($nodeId)
                ->filter(
                    fn (GraphNode $node) =>
                        $this->isMarketNode($node)
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
     * Market Catalog
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function markets(): Collection
    {
        return $this->query

            ->bySchema('market_segments.php')

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
     * Best Market
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
            return 'similar_market';
        }

        if (
            $this->query
                ->neighbors($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'direct_market';
        }

        if (
            $this->query
                ->reachable($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'related_market';
        }

        return 'recommended_market';
    }

    /**
     * =========================================================================
     * Is Market Node
     * =========================================================================
     */
    protected function isMarketNode(
        GraphNode $node
    ): bool
    {
        $schema = strtolower(
            $node->schema()
        );

        return

            str_contains($schema, 'market')

            ||

            str_contains($schema, 'country')

            ||

            str_contains($schema, 'region');
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