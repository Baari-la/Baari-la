<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Builder;

use App\Services\MasterData\Generator\SchemaDefinition;
use App\Services\MasterData\KnowledgeGraph\Model\GraphNode;
use App\Services\MasterData\Reference\ReferenceDefinition;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Node Builder
 * ==========================================================================
 *
 * Builds one GraphNode from a SchemaDefinition.
 *
 * Responsibilities
 * ----------------
 * - Build GraphNode
 * - Copy schema metadata
 * - Calculate schema statistics
 * - Normalize references for export
 *
 * ==========================================================================
 */
final class GraphNodeBuilder
{
    /**
     * =========================================================================
     * Build
     * =========================================================================
     */
    public function build(
        string $schemaId,
        SchemaDefinition $schema
    ): GraphNode
    {
        return new GraphNode(

            id: $schemaId,

            label: $this->label($schemaId),

            type: $schema->type(),

            attributes: $this->attributes(
                $schemaId,
                $schema
            ),

        );
    }

    /**
     * =========================================================================
     * Label
     * =========================================================================
     */
    protected function label(
        string $schemaId
    ): string
    {
        return ucfirst(

            str_replace(

                ['_', '.php'],

                [' ', ''],

                basename($schemaId)

            )

        );
    }

    /**
     * =========================================================================
     * Attributes
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function attributes(
        string $schemaId,
        SchemaDefinition $schema
    ): array
    {
        $required = $schema->required();

        $optional = $schema->optional();

        $types = $schema->types();

        $validation = $schema->validation();

        return [

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            'schema' => $schemaId,

            'type' => $schema->type(),

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'statistics' => $this->statistics(

                $required,

                $optional,

                $schema->references(),

                $types

            ),

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'required' => $required,

            'optional' => $optional,

            'types' => $types,

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Convert ReferenceDefinition objects into plain arrays.
            |--------------------------------------------------------------------------
            */

            'references' => $this->normalizeReferences(
                $schema->references()
            ),

            'validation' => $validation,

            'has_validation' => ! empty($validation),

        ];
    }

    /**
     * =========================================================================
     * Normalize References
     * =========================================================================
     *
     * @param array<string,mixed> $references
     *
     * @return array<string,mixed>
     */
    protected function normalizeReferences(
        array $references
    ): array
    {
        $normalized = [];

        foreach ($references as $field => $reference) {

            if ($reference instanceof ReferenceDefinition) {

                $normalized[$field] = $reference->toArray();

                continue;
            }

            $normalized[$field] = $reference;
        }

        ksort($normalized);

        return $normalized;
    }

    /**
     * =========================================================================
     * Statistics
     * =========================================================================
     *
     * @param array<int,string> $required
     * @param array<int,string> $optional
     * @param array<string,mixed> $references
     * @param array<string,string> $types
     *
     * @return array<string,int>
     */
    protected function statistics(
        array $required,
        array $optional,
        array $references,
        array $types
    ): array
    {
        return [

            'required_fields' => count($required),

            'optional_fields' => count($optional),

            'field_count' => count($required) + count($optional),

            'reference_count' => count($references),

            'type_count' => count($types),

        ];
    }
}