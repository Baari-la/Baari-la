<?php

declare(strict_types=1);

namespace App\Services\MasterData\Health;

use App\Services\MasterData\Generator\MasterDataSchemaGenerator;
use App\Services\MasterData\Validation\MasterDataValidationService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Master Data Health Service
 * ==========================================================================
 *
 * Responsible for monitoring the overall health of the Master Data ecosystem.
 *
 * Responsibilities
 * ----------------
 * - Validation status
 * - Schema coverage
 * - File coverage
 * - Record statistics
 * - Error statistics
 * - Warning statistics
 * - Knowledge Graph readiness
 * - Executive AI readiness
 *
 * Used by
 * -------
 * - Admin Dashboard
 * - Executive Dashboard
 * - Health Widget
 * - Scheduled Health Check
 *
 * ==========================================================================
 */
class MasterDataHealthService
{
    /**
     * Constructor.
     */
    public function __construct(
        protected MasterDataValidationService $validator,
        protected MasterDataSchemaGenerator $generator,
    ) {
    }

    /**
     * =========================================================================
     * Health Report
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function report(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validator->validate();

        $validation = $this->validator->healthReport();

        /*
        |--------------------------------------------------------------------------
        | Schema Preview
        |--------------------------------------------------------------------------
        */

        $schemas = $this->generator->preview();

        return [

            'status' => $this->status($validation),

            'summary' => [

                'files' => $validation['statistics']['files'],

                'records' => $validation['statistics']['records'],

                'schemas' => count($schemas),

                'errors' => $validation['statistics']['errors'],

                'warnings' => $validation['statistics']['warnings'],

            ],

            'coverage' => [

                'schema' => $this->schemaCoverage(

                    $validation,

                    $schemas

                ),

            ],

            'readiness' => [

                'knowledge_graph'
                    => $this->knowledgeGraphReady(
                        $validation
                    ),

                'executive_ai'
                    => $this->executiveAiReady(
                        $validation
                    ),

            ],

            'issues'
                => $validation['issues'],

        ];
    }

    /**
     * =========================================================================
     * Overall Status
     * =========================================================================
     */
    protected function status(
        array $validation
    ): string
    {
        if (
            $validation['statistics']['errors'] > 0
        ) {
            return 'FAILED';
        }

        if (
            $validation['statistics']['warnings'] > 0
        ) {
            return 'WARNING';
        }

        return 'HEALTHY';
    }

    /**
     * =========================================================================
     * Schema Coverage
     * =========================================================================
     */
    protected function schemaCoverage(
        array $validation,
        array $schemas
    ): float
    {
        $files = max(
            1,
            (int) $validation['statistics']['files']
        );

        return round(

            count($schemas)
            / $files
            * 100,

            2

        );
    }

    /**
     * =========================================================================
     * Knowledge Graph Ready
     * =========================================================================
     */
    protected function knowledgeGraphReady(
        array $validation
    ): bool
    {
        return
            $validation['statistics']['errors'] === 0;
    }

    /**
     * =========================================================================
     * Executive AI Ready
     * =========================================================================
     */
    protected function executiveAiReady(
        array $validation
    ): bool
    {
        return
            $validation['statistics']['errors'] === 0;
    }
}