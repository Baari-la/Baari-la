<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\GraphNode;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Community Analyzer
 * ==========================================================================
 *
 * Detects communities (connected components) inside the Knowledge Graph.
 *
 * Current Algorithm
 * -----------------
 * - Connected Components
 *
 * Future
 * ------
 * - Louvain
 * - Leiden
 * - Label Propagation
 * - Modularity Optimization
 *
 * This class NEVER modifies the graph.
 *
 * ==========================================================================
 */
final class CommunityAnalyzer
{
    /**
     * Constructor.
     */
    public function __construct(
        protected GraphRepository $repository
    ) {
    }

    /**
     * =========================================================================
     * Analyze
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function analyze(): array
    {
        return [

            'communities' => $this->communities(),

            'count' => $this->count(),

            'largest' => $this->largest(),

        ];
    }

    /**
     * =========================================================================
     * Communities
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function communities(): Collection
    {
        $visited = [];

        $communities = [];

        foreach ($this->repository->nodes() as $node) {

            if (isset($visited[$node->id()])) {
                continue;
            }

            $members = [];

            $this->explore(

                $node->id(),

                $visited,

                $members

            );

            usort(

                $members,

                static fn ($a, $b) =>

                    strcmp(

                        $a['label'],

                        $b['label']

                    )

            );

            $communities[] = [

                'size' => count($members),

                'members' => $members,

            ];

        }

        return collect($communities)

            ->sortByDesc('size')

            ->values();
    }

    /**
     * =========================================================================
     * Explore Component
     * =========================================================================
     *
     * @param array<string,bool> $visited
     * @param array<int,array<string,mixed>> $members
     */
    protected function explore(
        string $nodeId,
        array &$visited,
        array &$members
    ): void
    {
        if (isset($visited[$nodeId])) {
            return;
        }

        $node = $this->repository
            ->node($nodeId);

        if ($node === null) {
            return;
        }

        $visited[$nodeId] = true;

        $members[] = [

            'id' => $node->id(),

            'label' => $node->label(),

            'schema' => $node->schema(),

            'type' => $node->type(),

        ];

        /*
        |--------------------------------------------------------------------------
        | Outgoing
        |--------------------------------------------------------------------------
        */

        foreach (

            $this->repository
                ->outgoingEdges($nodeId)

            as $edge

        ) {

            $this->explore(

                $edge->target(),

                $visited,

                $members

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Incoming
        |--------------------------------------------------------------------------
        */

        foreach (

            $this->repository
                ->incomingEdges($nodeId)

            as $edge

        ) {

            $this->explore(

                $edge->source(),

                $visited,

                $members

            );

        }
    }

    /**
     * =========================================================================
     * Community Count
     * =========================================================================
     */
    public function count(): int
    {
        return $this->communities()
            ->count();
    }

    /**
     * =========================================================================
     * Largest Community
     * =========================================================================
     *
     * @return array<string,mixed>|null
     */
    public function largest(): ?array
    {
        return $this->communities()
            ->first();
    }

    /**
     * =========================================================================
     * Community Of Node
     * =========================================================================
     *
     * @return array<string,mixed>|null
     */
    public function communityOf(
        string $nodeId
    ): ?array
    {
        return $this->communities()

            ->first(function ($community) use ($nodeId) {

                foreach (

                    $community['members']

                    as $member

                ) {

                    if (

                        $member['id'] === $nodeId

                    ) {

                        return true;

                    }

                }

                return false;

            });
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'community_count' =>

                $this->count(),

            'largest' =>

                $this->largest(),

            'communities' =>

                $this->communities()

                    ->all(),

        ];
    }
}