<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Builder;

use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use App\Services\MasterData\KnowledgeGraph\Repository\SchemaCollection;
use App\Services\MasterData\KnowledgeGraph\Repository\SchemaRepository;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Knowledge Graph Builder
 * ==========================================================================
 *
 * Orchestrates complete Knowledge Graph generation.
 *
 * Pipeline
 * --------
 *
 * SchemaRepository
 *      ↓
 * GraphNodeBuilder
 *      ↓
 * GraphEdgeBuilder
 *      ↓
 * GraphRepository
 *
 * ==========================================================================
 */
final class KnowledgeGraphBuilder
{
    /**
     * @var array<int,string>
     */
    protected array $warnings = [];

    /**
     * @var array<int,string>
     */
    protected array $errors = [];

    /**
     * Constructor.
     */
    public function __construct(
        protected SchemaRepository $schemas,
        protected GraphNodeBuilder $nodeBuilder,
        protected GraphEdgeBuilder $edgeBuilder,
        protected GraphRepository $graph,
    ) {
    }

    /**
     * =========================================================================
     * Build
     * =========================================================================
     */
    public function build(): GraphRepository
    {

               $this->reset();

        $schemas = $this->loadSchemas();

        $this->buildNodes($schemas);

        $this->buildEdges($schemas);

        $this->validateGraph();


        return $this->graph;
    }

    /**
     * =========================================================================
     * Reset
     * =========================================================================
     */
    protected function reset(): void
    {
        $this->warnings = [];
        $this->errors = [];

        $this->graph->clear();
    }

    /**
     * =========================================================================
     * Load Schemas
     * =========================================================================
     */
    protected function loadSchemas(): SchemaCollection
    {
        return $this->schemas->collection();
    }

    /**
     * =========================================================================
     * Build Nodes
     * =========================================================================
     */
    protected function buildNodes(
        SchemaCollection $schemas
    ): void
    {
        foreach ($schemas->all() as $schemaId => $schema) {

              $this->graph->addNode(

                $this->nodeBuilder->build(

                    (string) $schemaId,

                    $schema

                )

            );

        }
    }

    /**
     * =========================================================================
     * Build Edges
     * =========================================================================
     */
    protected function buildEdges(
        SchemaCollection $schemas
    ): void
    {
        foreach ($schemas->all() as $schemaId => $schema) {

            foreach (

                $this->edgeBuilder->build(

                    (string) $schemaId,

                    $schema

                )

                as $edge

            ) {

                if (! $this->graph->hasNode($edge->source())) {

                    $this->warnings[] = sprintf(
                        'Missing source "%s".',
                        $edge->source()
                    );

                    continue;
                }

                if (! $this->graph->hasNode($edge->target())) {

                     $this->warnings[] = sprintf(
                        'Missing target "%s" referenced from "%s".',
                        $edge->target(),
                        $edge->source()
                    );

                    continue;
                }

                $this->graph->addEdge($edge);

            }

        }
    }

    /**
     * =========================================================================
     * Validate Graph
     * =========================================================================
     */
    protected function validateGraph(): void
    {
        if ($this->graph->nodeCount() === 0) {

            $this->errors[] =
                'Knowledge Graph contains no nodes.';
        }

        if ($this->graph->edgeCount() === 0) {

            $this->warnings[] =
                'Knowledge Graph contains no edges.';
        }
    }

    /**
     * =========================================================================
     * Repository
     * =========================================================================
     */
    public function repository(): GraphRepository
    {
        return $this->graph;
    }

    /**
     * =========================================================================
     * Warnings
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function warnings(): array
    {
        return $this->warnings;
    }

    /**
     * =========================================================================
     * Errors
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}