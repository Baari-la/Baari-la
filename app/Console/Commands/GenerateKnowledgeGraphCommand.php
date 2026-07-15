<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MasterData\KnowledgeGraph\Builder\KnowledgeGraphBuilder;
use App\Services\MasterData\KnowledgeGraph\GraphExporter;
use App\Services\MasterData\KnowledgeGraph\GraphValidator;
use App\Services\MasterData\KnowledgeGraph\Model\GraphValidationResult;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use Illuminate\Console\Command;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Generate Knowledge Graph Command
 * ==========================================================================
 *
 * Generates the DIGESTEX Knowledge Graph.
 *
 * Pipeline
 * --------
 *
 * KnowledgeGraphBuilder
 *          ↓
 * GraphRepository
 *          ↓
 * GraphValidator
 *          ↓
 * GraphValidationResult
 *          ↓
 * GraphExporter
 *
 * ==========================================================================
 */
final class GenerateKnowledgeGraphCommand extends Command
{
    protected $signature = 'digestex:graph:generate
                            {--preview : Preview only}
                            {--json : Export JSON}';

    protected $description =
        'Generate DIGESTEX Knowledge Graph';

    public function __construct(
        protected KnowledgeGraphBuilder $builder,
        protected GraphValidator $validator,
        protected GraphExporter $exporter,
    ) {
        parent::__construct();
    }

    /**
     * =========================================================================
     * Handle
     * =========================================================================
     */
    public function handle(): int
    {
        $started = microtime(true);

        $this->renderHeader();

        $this->newLine();

        $this->info('Building Knowledge Graph...');

        $repository = $this->builder->build();

        $validation = $this->validator
            ->validate($repository);


        if (! $validation->isValid()) {

            $this->renderValidationErrors(
                $validation
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Preview
        |--------------------------------------------------------------------------
        */

        if ($this->option('preview')) {

            $this->renderPreview(
                $repository,
                $validation
            );

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | Export
        |--------------------------------------------------------------------------
        */

        $output = $this->exporter
            ->export($repository);

        if ($this->option('json')) {

            $this->exporter
                ->exportJson($repository);

        }

        $this->renderSummary(

            $repository,

            $validation,

            $output,

            microtime(true) - $started,

        );

        return self::SUCCESS;
    }

    /**
     * =========================================================================
     * Header
     * =========================================================================
     */
    protected function renderHeader(): void
    {
        $this->line(str_repeat('=', 70));

        $this->info(
            ' DIGESTEX KNOWLEDGE GRAPH GENERATOR '
        );

        $this->line(str_repeat('=', 70));
    }

    /**
     * =========================================================================
     * Validation Errors
     * =========================================================================
     */
    protected function renderValidationErrors(
        GraphValidationResult $validation
    ): void
    {
        $this->newLine();

        $this->error(
            'Knowledge Graph validation failed.'
        );

        foreach ($validation->errors() as $error) {

            $this->line(
                sprintf(
                    '- %s',
                    $error
                )
            );
        }
    }

    /**
     * =========================================================================
     * Preview
     * =========================================================================
     */
    protected function renderPreview(
        GraphRepository $repository,
        GraphValidationResult $validation
    ): void
    {
        $this->newLine();

        $this->info('GRAPH SUMMARY');

        $this->line(str_repeat('-', 70));

        $this->line(sprintf(
            'Nodes    : %d',
            $repository->nodeCount()
        ));

        $this->line(sprintf(
            'Edges    : %d',
            $repository->edgeCount()
        ));

        $this->line(sprintf(
            'Warnings : %d',
            $validation->warningCount()
        ));

        $this->line(sprintf(
            'Errors   : %d',
            $validation->errorCount()
        ));

        $this->line(str_repeat('-', 70));
    }

    /**
     * =========================================================================
     * Summary
     * =========================================================================
     */
  
    protected function renderSummary(
        GraphRepository $repository,
        GraphValidationResult $validation,
        string $output,
        float $executionTime
    ): void
    {
        $this->newLine();

        $this->table(

            [

                'Metric',

                'Value',

            ],

            [

                [

                    'Nodes',

                    number_format(
                        $repository->nodeCount()
                    ),

                ],

                [

                    'Edges',

                    number_format(
                        $repository->edgeCount()
                    ),

                ],

                [

                    'Warnings',

                    $validation->warningCount(),

                ],

                [

                    'Errors',

                    $validation->errorCount(),

                ],

                [

                    'Validation',

                    $validation->isValid()
                        ? 'PASSED'
                        : 'FAILED',

                ],

                [

                    'Output',

                    $output,

                ],

                [

                    'Execution',

                    number_format(
                        $executionTime,
                        4
                    ) . ' sec',

                ],

            ]

        );

        $this->newLine();

        $this->info(
            'Knowledge Graph generated successfully.'
        );

        $this->line(str_repeat('=', 70));
    }
}