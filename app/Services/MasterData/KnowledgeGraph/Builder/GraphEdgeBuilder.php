<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Builder;

use App\Services\MasterData\Generator\SchemaDefinition;
use App\Services\MasterData\KnowledgeGraph\Model\GraphEdge;
use App\Services\MasterData\Reference\ReferenceDefinition;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Edge Builder
 * ==========================================================================
 *
 * Builds GraphEdge objects from SchemaDefinition references.
 *
 * Responsibilities
 * ----------------
 * - Convert ReferenceDefinition into GraphEdge
 * - Preserve relation metadata
 * - Preserve confidence metadata
 *
 * This class NEVER:
 * - Accesses GraphRepository
 * - Validates graph integrity
 * - Builds GraphNode
 *
 * ==========================================================================
 */
final class GraphEdgeBuilder
{
    /**
     * =========================================================================
     * Build
     * =========================================================================
     *
     * @return array<int,GraphEdge>
     */
    public function build(
        string $schemaId,
        SchemaDefinition $schema
    ): array
    {
        $edges = [];

        foreach ($schema->references() as $field => $reference) {

            /*
            |--------------------------------------------------------------------------
            | Hydrated from generated schema
            |--------------------------------------------------------------------------
            */

            if (is_array($reference)) {

                $reference = ReferenceDefinition::fromArray(
                    $reference
                );

            }

            if (! $reference instanceof ReferenceDefinition) {
                continue;
            }

            $edges[] = $this->buildEdge(

                source: $schemaId,

                field: (string) $field,

                reference: $reference,

            );

        }

        return $edges;
    }

    /**
     * =========================================================================
     * Build Edge
     * =========================================================================
     */
    protected function buildEdge(
        string $source,
        string $field,
        ReferenceDefinition $reference
    ): GraphEdge
    {
        return new GraphEdge(

            source: $source,

            target: $reference->target(),

            relation: $reference->relation(),

            attributes: [

                /*
                |--------------------------------------------------------------------------
                | Reference
                |--------------------------------------------------------------------------
                */

                'field' => $field,

                'collection' => $reference->collection(),

                'confidence' => $reference->confidence(),

                'reason' => $reference->reason(),

            ],

        );
    }

    /**
     * =========================================================================
     * Count
     * =========================================================================
     */
    public function count(
        SchemaDefinition $schema
    ): int
    {
        return count(
            $schema->references()
        );
    }

    /**
     * =========================================================================
     * Has Edges
     * =========================================================================
     */
    public function hasEdges(
        SchemaDefinition $schema
    ): bool
    {
        return ! empty(
            $schema->references()
        );
    }
}