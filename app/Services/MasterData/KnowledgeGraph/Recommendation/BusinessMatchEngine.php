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
 * Business Match Engine
 * ==========================================================================
 *
 * Intelligent business matchmaking powered by Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Business partner recommendation
 * - Buyer ↔ Supplier matching
 * - Similar company matching
 * - Ecosystem partner discovery
 * - Match scoring
 *
 * Business-specific rules should be implemented by extending this engine.
 *
 * ==========================================================================
 */
final class BusinessMatchEngine
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
     * Match
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function match(
        string $nodeId,
        int $limit = 10
    ): Collection
    {
        $source = $this->query->find($nodeId);

        if ($source === null) {

            return collect();

        }

        return $this->query

            ->reachable($nodeId)

            ->reject(

                fn (GraphNode $node) =>

                    $node->id() === $nodeId

            )

            ->map(

                fn (GraphNode $candidate) =>

                    $this->buildMatch(

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
     * Similar Business
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function similarBusinesses(
        string $nodeId,
        int $limit = 10
    ): Collection
    {
        $source = $this->query->find($nodeId);

        if ($source === null) {

            return collect();

        }

        return $this->query

            ->similar($nodeId)

            ->map(

                fn (GraphNode $candidate) =>

                    $this->buildMatch(

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
     * Ecosystem Partners
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function ecosystemPartners(
        string $schema,
        int $limit = 20
    ): Collection
    {
        return $this->query

            ->bySchema($schema)

            ->map(function (GraphNode $node) {

                return [

                    'id' => $node->id(),

                    'label' => $node->label(),

                    'schema' => $node->schema(),

                    'type' => $node->type(),

                ];

            })

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Build Match
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function buildMatch(
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

        ];
    }

    /**
     * =========================================================================
     * Best Match
     * =========================================================================
     *
     * @return array<string,mixed>|null
     */
    public function bestMatch(
        string $nodeId
    ): ?array
    {
        return $this->match(
            $nodeId,
            1
        )->first();
    }

    /**
     * =========================================================================
     * Match Score
     * =========================================================================
     */
    public function score(
        string $sourceId,
        string $candidateId
    ): float
    {
        $source = $this->query->find($sourceId);

        $candidate = $this->query->find($candidateId);

        if (
            $source === null ||
            $candidate === null
        ) {

            return 0.0;

        }

        return $this->similarity

            ->score(

                $source,

                $candidate

            );
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
        return $this->match(
            $nodeId,
            $limit
        )->all();
    }
}