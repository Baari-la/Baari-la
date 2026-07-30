<?php

declare(strict_types=1);

namespace App\Services\Recommendation\SupplyChain;

use App\Services\Company\Classification\BusinessRoleSemanticService;
use SplQueue;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Supply Chain Depth Service
 * ==========================================================================
 *
 * Resolves shortest supply-chain distance between two canonical business
 * roles using the DIGESTEX Master Data Framework (DMF).
 *
 * Canonical topology source:
 *
 * config/masterdata/Business/business_roles.php
 *
 * This service does NOT maintain its own relationship topology.
 *
 * Responsibilities:
 *
 * - Resolve shortest upstream/downstream path
 * - Calculate supply-chain depth
 * - Preserve canonical DMF topology
 * - Prevent traversal cycles
 * - Provide path evidence for recommendation intelligence
 *
 * Version:
 * 1.0
 */
class SupplyChainDepthService
{
    public function __construct(
        protected BusinessRoleSemanticService $semantic,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Resolve Supply Chain Depth
     * --------------------------------------------------------------------------
     *
     * Depth:
     *
     * 0 = same role
     * 1 = direct relationship
     * 2 = one intermediary
     * 3 = two intermediaries
     *
     */
    public function resolve(
        string $sourceRole,
        string $targetRole,
        int $maxDepth = 8,
    ): array {

        $sourceRole = $this->normalizeRole($sourceRole);
        $targetRole = $this->normalizeRole($targetRole);

        /*
        |--------------------------------------------------------------------------
        | Invalid Roles
        |--------------------------------------------------------------------------
        */

        if (
            $sourceRole === '' ||
            $targetRole === '' ||
            ! $this->semantic->exists($sourceRole) ||
            ! $this->semantic->exists($targetRole)
        ) {
            return $this->unreachable(
                sourceRole: $sourceRole,
                targetRole: $targetRole,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Same Role
        |--------------------------------------------------------------------------
        */

        if ($sourceRole === $targetRole) {
            return [
                'source_role' => $sourceRole,
                'target_role' => $targetRole,
                'reachable' => true,
                'depth' => 0,
                'direction' => 'same_role',
                'path' => [
                    $sourceRole,
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Downstream Search
        |--------------------------------------------------------------------------
        */

        $downstream = $this->search(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            direction: 'downstream',
            maxDepth: $maxDepth,
        );

        /*
        |--------------------------------------------------------------------------
        | Upstream Search
        |--------------------------------------------------------------------------
        */

        $upstream = $this->search(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            direction: 'upstream',
            maxDepth: $maxDepth,
        );

        /*
        |--------------------------------------------------------------------------
        | No Path
        |--------------------------------------------------------------------------
        */

        if ($downstream === null && $upstream === null) {
            return $this->unreachable(
                sourceRole: $sourceRole,
                targetRole: $targetRole,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Downstream Only
        |--------------------------------------------------------------------------
        */

        if ($downstream !== null && $upstream === null) {
            return $downstream;
        }

        /*
        |--------------------------------------------------------------------------
        | Upstream Only
        |--------------------------------------------------------------------------
        */

        if ($upstream !== null && $downstream === null) {
            return $upstream;
        }

        /*
        |--------------------------------------------------------------------------
        | Both Paths Exist
        |--------------------------------------------------------------------------
        |
        | In a highly connected taxonomy it is possible for both searches to
        | find a path. Prefer the shortest canonical path.
        |
        */

        if (
            ($downstream['depth'] ?? PHP_INT_MAX) <=
            ($upstream['depth'] ?? PHP_INT_MAX)
        ) {
            return $downstream;
        }

        return $upstream;
    }

    /**
     * --------------------------------------------------------------------------
     * Search
     * --------------------------------------------------------------------------
     *
     * Breadth-first search guarantees the shortest path in an unweighted
     * canonical role graph.
     *
     */
    protected function search(
        string $sourceRole,
        string $targetRole,
        string $direction,
        int $maxDepth,
    ): ?array {

        $queue = new SplQueue();

        $queue->enqueue([
            'role' => $sourceRole,
            'path' => [
                $sourceRole,
            ],
            'depth' => 0,
        ]);

        $visited = [
            $sourceRole => true,
        ];

        while (! $queue->isEmpty()) {

            $current = $queue->dequeue();

            $currentRole =
                (string) $current['role'];

            $currentPath =
                (array) $current['path'];

            $currentDepth =
                (int) $current['depth'];

            /*
            |--------------------------------------------------------------------------
            | Maximum Traversal Depth
            |--------------------------------------------------------------------------
            */

            if ($currentDepth >= $maxDepth) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Adjacent Roles
            |--------------------------------------------------------------------------
            */

           $adjacentRoles = $this->adjacentRoles(
                role: $currentRole,
                direction: $direction,
            );
            
            foreach ($adjacentRoles as $adjacentRole) {

                $adjacentRole =
                    $this->normalizeRole(
                        (string) $adjacentRole
                    );

                if ($adjacentRole === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Prevent Cycles
                |--------------------------------------------------------------------------
                */

                if (isset($visited[$adjacentRole])) {
                    continue;
                }

                $nextDepth =
                    $currentDepth + 1;

                $nextPath = [
                    ...$currentPath,
                    $adjacentRole,
                ];

                /*
                |--------------------------------------------------------------------------
                | Target Found
                |--------------------------------------------------------------------------
                */

                if ($adjacentRole === $targetRole) {

                    return [
                        'source_role' => $sourceRole,
                        'target_role' => $targetRole,
                        'reachable' => true,
                        'depth' => $nextDepth,
                        'direction' => $direction,
                        'path' => $nextPath,
                    ];
                }

                /*
                |--------------------------------------------------------------------------
                | Continue Traversal
                |--------------------------------------------------------------------------
                */

                $visited[$adjacentRole] = true;

                $queue->enqueue([
                    'role' => $adjacentRole,
                    'path' => $nextPath,
                    'depth' => $nextDepth,
                ]);
            }
        }

        return null;
    }


/**
 * --------------------------------------------------------------------------
 * Resolve Adjacent Roles
 * --------------------------------------------------------------------------
 *
 * Canonical DMF relationships may be declared from either side:
 *
 * A downstream => B
 *
 * or:
 *
 * B upstream => A
 *
 * Both declarations represent the same directed supply-chain edge:
 *
 * A -> B
 *
 * This resolver combines explicit and implicit inverse relationships so
 * traversal remains correct even when the taxonomy is asymmetrical.
 *
 */
protected function adjacentRoles(
    string $role,
    string $direction,
): array {

    /*
    |--------------------------------------------------------------------------
    | Explicit Relationships
    |--------------------------------------------------------------------------
    */

    $explicit = $direction === 'upstream'
        ? $this->semantic->upstream($role)
        : $this->semantic->downstream($role);

    /*
    |--------------------------------------------------------------------------
    | Implicit Inverse Relationships
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | nonwoven_manufacturer:
    |     upstream:
    |         synthetic_fiber_manufacturer
    |
    | Therefore:
    |
    | synthetic_fiber_manufacturer
    |     downstream:
    |         nonwoven_manufacturer
    |
    */

    $inverse = [];

    foreach ($this->semantic->all() as $definition) {

    /*
    |--------------------------------------------------------------------------
    | Resolve Candidate Role ID
    |--------------------------------------------------------------------------
    |
    | semantic->all() returns a numerically indexed collection.
    | The canonical role identifier is stored inside definition['id'].
    |
    */

    $candidateRole = $this->normalizeRole(
        (string) ($definition['id'] ?? '')
    );

    if (
        $candidateRole === '' ||
        $candidateRole === $role
    ) {
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Downstream Inverse Relationship
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | nonwoven_manufacturer.upstream contains
    | synthetic_fiber_manufacturer
    |
    | Therefore:
    |
    | synthetic_fiber_manufacturer
    |     -> nonwoven_manufacturer
    |
    */

    if ($direction === 'downstream') {

        $candidateUpstream =
            $this->semantic->upstream(
                $candidateRole
            );

        if (
            in_array(
                $role,
                $candidateUpstream,
                true
            )
        ) {
            $inverse[] = $candidateRole;
        }

        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Upstream Inverse Relationship
    |--------------------------------------------------------------------------
    |
    | If candidateRole declares the current role as downstream,
    | candidateRole is an upstream neighbor of the current role.
    |
    */

    $candidateDownstream =
        $this->semantic->downstream(
            $candidateRole
        );

    if (
        in_array(
            $role,
            $candidateDownstream,
            true
        )
    ) {
        $inverse[] = $candidateRole;
    }
}

    /*
    |--------------------------------------------------------------------------
    | Merge + Normalize + Unique
    |--------------------------------------------------------------------------
    */

    return collect([
        ...$explicit,
        ...$inverse,
    ])
        ->map(
            fn ($item) =>
                $this->normalizeRole((string) $item)
        )
        ->filter(
            fn (string $item) =>
                $item !== ''
        )
        ->unique()
        ->values()
        ->all();
}
    
    /**
     * --------------------------------------------------------------------------
     * Is Direct
     * --------------------------------------------------------------------------
     */
    public function isDirect(
        string $sourceRole,
        string $targetRole,
    ): bool {

        $result = $this->resolve(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
        );

        return
            ($result['reachable'] ?? false) === true &&
            ($result['depth'] ?? null) === 1;
    }

    /**
     * --------------------------------------------------------------------------
     * Depth
     * --------------------------------------------------------------------------
     */
    public function depth(
        string $sourceRole,
        string $targetRole,
    ): ?int {

        $result = $this->resolve(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
        );

        if (! ($result['reachable'] ?? false)) {
            return null;
        }

        return (int) $result['depth'];
    }
/**
 * --------------------------------------------------------------------------
 * Resolve Discovery
 * --------------------------------------------------------------------------
 *
 * Converts canonical graph distance into recommendation intelligence.
 *
 * Depth 0 = same business role
 * Depth 1 = direct supply-chain match
 * Depth 2 = strategic supply-chain match
 * Depth 3 = extended ecosystem match
 * Depth >3 = exploration only
 *
 */
public function resolveDiscovery(
    string $sourceRole,
    string $targetRole,
    int $maxDepth = 3,
): array {

    $result = $this->resolve(
        sourceRole: $sourceRole,
        targetRole: $targetRole,
        maxDepth: $maxDepth,
    );

    if (! ($result['reachable'] ?? false)) {
        return [
            ...$result,

            'discovery_eligible' => false,
            'depth_class' => 'unreachable',
            'depth_score' => 0,
        ];
    }

    $depth = (int) ($result['depth'] ?? 0);

    return [
        ...$result,

        'discovery_eligible' =>
            $this->isDiscoveryEligibleDepth($depth),

        'depth_class' =>
            $this->depthClass($depth),

        'depth_score' =>
            $this->depthScore($depth),
    ];
}

/**
 * --------------------------------------------------------------------------
 * Discovery Eligible Depth
 * --------------------------------------------------------------------------
 */
public function isDiscoveryEligibleDepth(
    int $depth,
): bool {

    return $depth >= 1 && $depth <= 3;
}

/**
 * --------------------------------------------------------------------------
 * Depth Classification
 * --------------------------------------------------------------------------
 */
public function depthClass(
    int $depth,
): string {

    return match (true) {

        $depth === 0 =>
            'same_role',

        $depth === 1 =>
            'direct',

        $depth === 2 =>
            'strategic',

        $depth === 3 =>
            'extended',

        default =>
            'exploration',
    };
}

/**
 * --------------------------------------------------------------------------
 * Depth Score
 * --------------------------------------------------------------------------
 *
 * Structural proximity score only.
 *
 * This is NOT the final recommendation score.
 *
 */
public function depthScore(
    int $depth,
): float {

    return match (true) {

        $depth === 0 =>
            100.0,

        $depth === 1 =>
            100.0,

        $depth === 2 =>
            80.0,

        $depth === 3 =>
            60.0,

        $depth === 4 =>
            40.0,

        $depth === 5 =>
            20.0,

        default =>
            0.0,
    };
}

    
    /**
     * --------------------------------------------------------------------------
     * Unreachable Result
     * --------------------------------------------------------------------------
     */
    protected function unreachable(
        string $sourceRole,
        string $targetRole,
    ): array {

        return [
            'source_role' => $sourceRole,
            'target_role' => $targetRole,
            'reachable' => false,
            'depth' => null,
            'direction' => null,
            'path' => [],
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize Role
     * --------------------------------------------------------------------------
     */
    protected function normalizeRole(
        string $role,
    ): string {

        return strtolower(
            trim($role)
        );
    }
}