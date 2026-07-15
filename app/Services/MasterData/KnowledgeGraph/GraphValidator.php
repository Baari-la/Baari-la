<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use App\Services\MasterData\KnowledgeGraph\Model\GraphValidationResult;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Validator
 * ==========================================================================
 *
 * Validates Knowledge Graph integrity.
 *
 * Responsibilities
 * ----------------
 * - Validate nodes
 * - Validate edges
 * - Detect missing references
 * - Detect self-loops
 * - Produce validation report
 *
 * ==========================================================================
 */
final class GraphValidator
{
    /**
     * Validation errors.
     *
     * @var array<int,array<string,mixed>>
     */
    protected array $errors = [];

    /**
     * Validation warnings.
     *
     * @var array<int,array<string,mixed>>
     */
    protected array $warnings = [];

    /**
     * =========================================================================
     * Validate
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function validate(
    GraphRepository $repository
): GraphValidationResult
    {
        $this->reset();

        $this->validateEdges($repository);

        $this->validateOrphans($repository);

        return new GraphValidationResult(

    valid: empty($this->errors),

    errors: $this->errors,

    warnings: $this->warnings,

    statistics: [

        'nodes' => $repository->nodeCount(),

        'edges' => $repository->edgeCount(),

        'errors' => count($this->errors),

        'warnings' => count($this->warnings),

    ],

          );
    }

    /**
     * =========================================================================
     * Validate Edges
     * =========================================================================
     */
    protected function validateEdges(
        GraphRepository $repository
    ): void
    {
        foreach ($repository->edges() as $edge) {

            if (! $repository->hasNode($edge->source())) {

                $this->addError(

                    'missing_source',

                    sprintf(
                        'Source node [%s] does not exist.',
                        $edge->source()
                    )

                );

            }

            if (! $repository->hasNode($edge->target())) {

                $this->addError(

                    'missing_target',

                    sprintf(
                        'Target node [%s] does not exist.',
                        $edge->target()
                    )

                );

            }

            if (
                $edge->source() === $edge->target()
            ) {

                $this->addWarning(

                    'self_loop',

                    sprintf(
                        'Self-loop detected on [%s].',
                        $edge->source()
                    )

                );

            }

        }
    }

   /*
|--------------------------------------------------------------------------
| Orphan Nodes
|--------------------------------------------------------------------------
|
| Lookup taxonomies may legitimately have no relationships.
| These warnings are informational and do not invalidate the graph.
|
*/
    protected function validateOrphans(
        GraphRepository $repository
    ): void
    {
        foreach ($repository->nodes() as $node) {

            $incoming = $repository
                ->incoming(
                    $node->id()
                );

            $outgoing = $repository
                ->outgoing(
                    $node->id()
                );

            if (
                 empty($incoming)
                    &&
                    empty($outgoing)
            ) {

                $this->addWarning(

                    'orphan_node',

                    sprintf(
                        'Node [%s] has no relationships.',
                        $node->id()
                    )

                );

            }

        }
    }

    /**
     * =========================================================================
     * Add Error
     * =========================================================================
     */
    protected function addError(
        string $code,
        string $message
    ): void
    {
        $this->errors[] = [

            'code' => $code,

            'message' => $message,

        ];
    }

    /**
     * =========================================================================
     * Add Warning
     * =========================================================================
     */
    protected function addWarning(
        string $code,
        string $message
    ): void
    {
        $this->warnings[] = [

            'code' => $code,

            'message' => $message,

        ];
    }

    /**
     * =========================================================================
     * Reset
     * =========================================================================
     */
    protected function reset(): void
    {
        $this->errors = [];

        $this->warnings = [];
    }
}