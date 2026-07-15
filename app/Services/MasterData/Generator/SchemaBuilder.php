<?php

declare(strict_types=1);

namespace App\Services\MasterData\Generator;

use App\Services\MasterData\Classification\ClassificationResult;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Builder
 * ==========================================================================
 *
 * Builds SchemaDefinition from analyzed Master Data.
 *
 * Responsibilities
 * ----------------
 * - Build SchemaDefinition
 * - Populate schema metadata
 * - Apply default validation
 *
 * This class DOES NOT:
 * - Read files
 * - Analyze fields
 * - Infer types
 * - Detect references
 * - Classify schemas
 * - Export files
 *
 * ==========================================================================
 */
final class SchemaBuilder
{
    /**
     * =========================================================================
     * Build
     * =========================================================================
     *
     * @param array<string,mixed> $analysis
     * @param array<string,string> $types
     * @param array<int,string> $references
     */
    public function build(
        array $analysis,
        array $types,
        array $references,
        ClassificationResult $classification
    ): SchemaDefinition
    {
        $definition = new SchemaDefinition(
            $classification->type()
        );

        /*
        |--------------------------------------------------------------------------
        | Field Requirements
        |--------------------------------------------------------------------------
        */

        $definition
            ->setRequired(
                $analysis['required'] ?? []
            )
            ->setOptional(
                $analysis['optional'] ?? []
            );

        /*
        |--------------------------------------------------------------------------
        | Types
        |--------------------------------------------------------------------------
        */

        $definition
            ->setTypes(
                $types
            );

        /*
        |--------------------------------------------------------------------------
        | References
        |--------------------------------------------------------------------------
        */

        $definition
            ->setReferences(
                $references
            );

        /*
        |--------------------------------------------------------------------------
        | Classification Metadata
        |--------------------------------------------------------------------------
        */

        if (method_exists(
            $definition,
            'setClassification'
        )) {

            $definition->setClassification(
                $classification->toArray()
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $definition->setValidation(
            $this->defaultValidation()
        );

        return $definition;
    }

    /**
     * =========================================================================
     * Default Validation
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function defaultValidation(): array
    {
        return [

            'minimum_records'      => 1,

            'allow_duplicate_id'   => false,

            'allow_unknown_fields' => false,

        ];
    }
}