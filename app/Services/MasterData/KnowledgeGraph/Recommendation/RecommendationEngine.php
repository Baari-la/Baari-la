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
 * Recommendation Engine
 * ==========================================================================
 *
 * Generic recommendation engine for the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Related nodes
 * - Similar nodes
 * - Neighbor recommendations
 * - Reachable recommendations
 * - Recommendation scoring
 *
 * Business-specific recommendation engines should extend this service.
 *
 * ==========================================================================
 */
final class RecommendationEngine
{
    /**
     * Constructor.
     */
    public function __construct(
        protected GraphQuery $query,
    ) {
    }

    /**
     * =========================================================================
     * Recommend
     * =========================================================================
     *
     * Returns the best recommendations for a node.
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function recommend(
        string $nodeId,
        int $limit = 10
    ): Collection
    {
        $recommendations = collect();

        /*
        |--------------------------------------------------------------------------
        | Direct Neighbors
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->neighborRecommendations($nodeId)

        );

        /*
        |--------------------------------------------------------------------------
        | Similar Schema
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->similarRecommendations($nodeId)

        );

        /*
        |--------------------------------------------------------------------------
        | Reachable Nodes
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->reachableRecommendations($nodeId)

        );

        return $recommendations

            ->groupBy('id')

            ->map(function (Collection $group) {

                $item = $group->first();

                $item['score'] = $group->sum('score');

                return $item;

            })

            ->sortByDesc('score')

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Neighbor Recommendations
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    protected function neighborRecommendations(
        string $nodeId
    ): Collection
    {
        return $this->query

            ->neighbors($nodeId)

            ->map(fn (GraphNode $node) =>

                $this->makeRecommendation(

                    $node,

                    'neighbor',

                    100

                )

            );
    }

    /**
     * =========================================================================
     * Similar Recommendations
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    protected function similarRecommendations(
        string $nodeId
    ): Collection
    {
        return $this->query

            ->similar($nodeId)

            ->map(fn (GraphNode $node) =>

                $this->makeRecommendation(

                    $node,

                    'similar_schema',

                    60

                )

            );
    }

    /**
     * =========================================================================
     * Reachable Recommendations
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    protected function reachableRecommendations(
        string $nodeId
    ): Collection
    {
        return $this->query

            ->reachable($nodeId)

            ->reject(

                fn (GraphNode $node) =>

                    $node->id() === $nodeId

            )

            ->map(fn (GraphNode $node) =>

                $this->makeRecommendation(

                    $node,

                    'reachable',

                    30

                )

            );
    }

    /**
     * =========================================================================
     * Make Recommendation
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function makeRecommendation(
        GraphNode $node,
        string $reason,
        int $score
    ): array
    {
        return [

            'id' => $node->id(),

            'label' => $node->label(),

            'schema' => $node->schema(),

            'type' => $node->type(),

            'reason' => $reason,

            'score' => $score,

        ];
    }

    /**
     * =========================================================================
     * Related Nodes
     * =========================================================================
     *
     * Alias of recommend().
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function related(
        string $nodeId,
        int $limit = 10
    ): Collection
    {
        return $this->recommend(
            $nodeId,
            $limit
        );
    }

    /**
     * =========================================================================
     * Recommendations by Schema
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function bySchema(
        string $schema
    ): Collection
    {
        return $this->query

            ->bySchema($schema)

            ->map(fn (GraphNode $node) =>

                $this->makeRecommendation(

                    $node,

                    'schema',

                    50

                )

            )

            ->values();
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