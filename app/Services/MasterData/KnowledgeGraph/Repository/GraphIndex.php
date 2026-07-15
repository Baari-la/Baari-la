<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Repository;

use App\Services\MasterData\KnowledgeGraph\Model\GraphEdge;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Index
 * ==========================================================================
 *
 * Maintains all graph relationship indexes.
 *
 * Responsibilities
 * ----------------
 * - Incoming index
 * - Outgoing index
 * - Adjacency index
 * - Degree calculation
 *
 * This class NEVER:
 *
 * - Stores GraphNode
 * - Stores GraphEdge
 * - Traverses graph
 * - Performs analytics
 *
 * ==========================================================================
 */
final class GraphIndex
{
    /**
     * source => edge ids
     *
     * @var array<string,array<int,string>>
     */
    protected array $outgoing = [];

    /**
     * target => edge ids
     *
     * @var array<string,array<int,string>>
     */
    protected array $incoming = [];

    /**
     * source => target => edge id
     *
     * @var array<string,array<string,string>>
     */
    protected array $adjacency = [];

    /**
     * =========================================================================
     * Index Edge
     * =========================================================================
     */
    public function add(
        GraphEdge $edge
    ): self
    {
        $id = $edge->id();

        $this->outgoing[$edge->source()][] = $id;

        $this->incoming[$edge->target()][] = $id;

        $this->adjacency
            [$edge->source()]
            [$edge->target()]
            = $id;

        if ($edge->bidirectional()) {

            $this->outgoing[$edge->target()][] = $id;

            $this->incoming[$edge->source()][] = $id;

            $this->adjacency
                [$edge->target()]
                [$edge->source()]
                = $id;
        }

        return $this;
    }

    /**
     * =========================================================================
     * Remove Edge
     * =========================================================================
     */
    public function remove(
        GraphEdge $edge
    ): self
    {
        $id = $edge->id();

        $this->outgoing[$edge->source()] = array_values(

            array_filter(

                $this->outgoing[$edge->source()] ?? [],

                static fn (string $edgeId): bool =>

                    $edgeId !== $id

            )

        );

        $this->incoming[$edge->target()] = array_values(

            array_filter(

                $this->incoming[$edge->target()] ?? [],

                static fn (string $edgeId): bool =>

                    $edgeId !== $id

            )

        );

        unset(

            $this->adjacency
                [$edge->source()]
                [$edge->target()]

        );

        if ($edge->bidirectional()) {

            $this->outgoing[$edge->target()] = array_values(

                array_filter(

                    $this->outgoing[$edge->target()] ?? [],

                    static fn (string $edgeId): bool =>

                        $edgeId !== $id

                )

            );

            $this->incoming[$edge->source()] = array_values(

                array_filter(

                    $this->incoming[$edge->source()] ?? [],

                    static fn (string $edgeId): bool =>

                        $edgeId !== $id

                )

            );

            unset(

                $this->adjacency
                    [$edge->target()]
                    [$edge->source()]

            );
        }

        return $this;
    }

    /**
     * =========================================================================
     * Outgoing
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function outgoing(
        string $node
    ): array
    {
        return $this->outgoing[$node] ?? [];
    }

    /**
     * =========================================================================
     * Incoming
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function incoming(
        string $node
    ): array
    {
        return $this->incoming[$node] ?? [];
    }

    /**
     * =========================================================================
     * Neighbors
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function neighbors(
        string $node
    ): array
    {
        return array_keys(

            $this->adjacency[$node] ?? []

        );
    }

    /**
     * =========================================================================
     * Has Connection
     * =========================================================================
     */
    public function connected(
        string $source,
        string $target
    ): bool
    {
        return isset(

            $this->adjacency
                [$source]
                [$target]

        );
    }

    /**
     * =========================================================================
     * Edge ID
     * =========================================================================
     */
    public function edgeId(
        string $source,
        string $target
    ): ?string
    {
        return $this->adjacency
            [$source]
            [$target]
            ?? null;
    }

    /**
     * =========================================================================
     * In Degree
     * =========================================================================
     */
    public function inDegree(
        string $node
    ): int
    {
        return count(

            $this->incoming($node)

        );
    }

    /**
     * =========================================================================
     * Out Degree
     * =========================================================================
     */
    public function outDegree(
        string $node
    ): int
    {
        return count(

            $this->outgoing($node)

        );
    }

    /**
     * =========================================================================
     * Degree
     * =========================================================================
     */
    public function degree(
        string $node
    ): int
    {
        return $this->inDegree($node)
            + $this->outDegree($node);
    }

    /**
     * =========================================================================
     * Node Count
     * =========================================================================
     */
    public function indexedNodes(): int
    {
        return count(

            array_unique(

                array_merge(

                    array_keys($this->incoming),

                    array_keys($this->outgoing)

                )

            )

        );
    }

    /**
     * =========================================================================
     * Edge Count
     * =========================================================================
     */
    public function indexedEdges(): int
    {
        $ids = [];

        foreach ($this->outgoing as $edges) {

            foreach ($edges as $edgeId) {

                $ids[$edgeId] = true;

            }
        }

        return count($ids);
    }

    /**
     * =========================================================================
     * Clear
     * =========================================================================
     */
    public function clear(): void
    {
        $this->incoming = [];

        $this->outgoing = [];

        $this->adjacency = [];
    }

    /**
     * =========================================================================
     * Statistics
     * =========================================================================
     *
     * @return array<string,int>
     */
    public function statistics(): array
    {
        return [

            'indexed_nodes' => $this->indexedNodes(),

            'indexed_edges' => $this->indexedEdges(),

        ];
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'incoming' => $this->incoming,

            'outgoing' => $this->outgoing,

            'adjacency' => $this->adjacency,

        ];
    }
}