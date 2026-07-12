<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships;

use App\Services\Knowledge\KnowledgeEdge;
use App\Services\Knowledge\Relationships\Contracts\EdgeBuilderInterface;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Abstract Edge Builder
 * ==========================================================================
 *
 * Base class for all Knowledge Edge Builders.
 *
 * Responsibilities
 * ----------------
 * • Build KnowledgeEdge objects
 * • Standardize metadata
 * • Standardize weight
 * • Standardize confidence
 *
 * Extended by:
 *
 * • BusinessRoleTechnologyEdgeBuilder
 * • TechnologyMachineryEdgeBuilder
 * • ProductTechnologyEdgeBuilder
 * • TechnologyCertificationEdgeBuilder
 * • CertificationMarketEdgeBuilder
 * • MarketBuyerEdgeBuilder
 *
 */

abstract class AbstractEdgeBuilder implements EdgeBuilderInterface
{
    /**
     * Relationship name.
     *
     * Example:
     *
     * uses
     * requires
     * manufactured_using
     */
    abstract protected function relationship(): string;

    /**
     * Default edge weight.
     */
    protected function weight(): float
    {
        return 1.0;
    }

    /**
     * Default confidence.
     */
    protected function confidence(): float
    {
        return 100;
    }

    /**
     * Default metadata.
     */
    protected function metadata(): array
    {
        return [

            'source' => 'DMF',

            'version' => config('app.version'),

            'relationship' => $this->relationship(),

        ];
    }

    /**
     * Create one Knowledge Edge.
     */
    protected function edge(
        string|int $from,
        string|int $to,
        array $metadata = []
    ): KnowledgeEdge {

        $edge = new KnowledgeEdge(

            from: $from,

            to: $to,

            relationship: $this->relationship(),

            weight: $this->weight(),

            metadata: array_merge(

                $this->metadata(),

                $metadata

            )

        );

        $edge->setConfidence(

            $this->confidence()

        );

        return $edge;
    }

    /**
     * Create multiple edges.
     *
     * Example:
     *
     * [
     *      'cad',
     *      'plm',
     *      'mes'
     * ]
     */
    protected function edges(
        string|int $from,
        iterable $targets,
        callable $resolver = null
    ): array {

        $edges = [];

        foreach ($targets as $target) {

            $targetId = $resolver
                ? $resolver($target)
                : $target;

            $edges[] = $this->edge(

                $from,

                $targetId

            );

        }

        return $edges;
    }
}