<?php

declare(strict_types=1);

namespace App\Services\MasterData\Generator;

use App\Services\MasterData\Classification\SchemaClassifier;
use App\Services\MasterData\Reference\SchemaReferenceDetector;
use Illuminate\Support\Collection;
use SplFileInfo;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Master Data Schema Generator
 * ==========================================================================
 *
 * Orchestrates the complete schema generation pipeline.
 *
 * Pipeline
 * --------
 *
 * Scan
 *      ↓
 * Analyze
 *      ↓
 * Resolve Field Requirements
 *      ↓
 * Infer Types
 *      ↓
 * Detect References
 *      ↓
 * Classify
 *      ↓
 * Build Schema
 *      ↓
 * Export
 *
 * ==========================================================================
 */
final class MasterDataSchemaGenerator
{
    /**
     * Constructor.
     */
    public function __construct(
        protected SchemaFileScanner $scanner,
        protected SchemaFieldAnalyzer $analyzer,
        protected FieldRequirementResolver $requirements,
        protected SchemaTypeInferer $inferer,
        protected SchemaReferenceDetector $referenceDetector,
        protected SchemaClassifier $classifier,
        protected SchemaBuilder $builder,
        protected SchemaExporter $exporter,
    ) {
    }

    /**
     * =========================================================================
     * Generate
     * =========================================================================
     *
     * @return array<string,SchemaDefinition>
     */
    public function generate(): array
    {
        $definitions = [];

        foreach ($this->scanner->scan() as $file) {

            $definitions[
                $this->scanner->relativePath($file)
                ] = $this->buildDefinition($file);

        }

        ksort($definitions);

        return $definitions;
    }

    /**
     * =========================================================================
     * Preview
     * =========================================================================
     *
     * @return array<string,SchemaDefinition>
     */
    public function preview(): array
    {
        return $this->generate();
    }

    /**
     * =========================================================================
     * Scan
     * =========================================================================
     *
     * @return Collection<int,SplFileInfo>
     */
    public function scan(): Collection
    {
        return $this->scanner->scan();
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @param array<string,SchemaDefinition> $definitions
     */
    public function export(
        array $definitions
    ): string
    {
        return $this->exporter->export(
            $definitions
        );
    }

    /**
     * =========================================================================
     * Build Definition
     * =========================================================================
     */
    protected function buildDefinition(
        SplFileInfo $file
    ): SchemaDefinition
    {
        /*
        |--------------------------------------------------------------------------
        | Analyze
        |--------------------------------------------------------------------------
        */

        $analysis = $this->analyzer->analyze(
            $file
        );

        /*
        |--------------------------------------------------------------------------
        | Resolve Required / Optional
        |--------------------------------------------------------------------------
        */

        $requirements = $this->requirements->resolve(
            $analysis
        );

        $analysis['required'] = $requirements['required'];

        $analysis['optional'] = $requirements['optional'];

        /*
        |--------------------------------------------------------------------------
        | Infer Types
        |--------------------------------------------------------------------------
        */

        $types = $this->inferer->infer(
            $analysis
        );

        /*
        |--------------------------------------------------------------------------
        | Detect References
        |--------------------------------------------------------------------------
        */

        $references = $this->referenceDetector
            ->detect($analysis);

        /*
        |--------------------------------------------------------------------------
        | Classification
        |--------------------------------------------------------------------------
        */

        $classification = $this->classifier
            ->classify($analysis);

        /*
        |--------------------------------------------------------------------------
        | Build
        |--------------------------------------------------------------------------
        */

        return $this->builder->build(

            analysis: $analysis,

            types: $types,

            references: $references,

            classification: $classification

        );
    }
}