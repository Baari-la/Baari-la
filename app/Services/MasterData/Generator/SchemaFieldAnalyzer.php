<?php

declare(strict_types=1);

namespace App\Services\MasterData\Generator;

use SplFileInfo;
use Throwable;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Field Analyzer
 * ==========================================================================
 *
 * Analyzes one Master Data definition file.
 *
 * Responsibilities
 * ----------------
 * - Load master data records
 * - Discover available fields
 * - Count field frequency
 * - Capture example values
 * - Collect basic metadata
 *
 * This class DOES NOT:
 * - Resolve required fields
 * - Resolve optional fields
 * - Infer data types
 * - Detect references
 * - Classify schemas
 * - Build schemas
 *
 * ==========================================================================
 */
final class SchemaFieldAnalyzer
{
    /**
     * =========================================================================
     * Analyze
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function analyze(
        SplFileInfo $file
    ): array
    {
        $records = $this->loadRecords($file);

        $analysis = [

            /*
            |--------------------------------------------------------------------------
            | File Metadata
            |--------------------------------------------------------------------------
            */

            'file' => $file->getFilename(),

            'path' => $file->getRealPath(),

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'records' => count($records),

            /*
            |--------------------------------------------------------------------------
            | Field Discovery
            |--------------------------------------------------------------------------
            */

            'fields' => [],

            'frequency' => [],

            'examples' => [],

        ];

        foreach ($records as $record) {

            foreach ($record as $field => $value) {

                if (! isset($analysis['fields'][$field])) {

                    $analysis['fields'][$field] = true;

                    $analysis['frequency'][$field] = 0;

                    $analysis['examples'][$field] = $value;

                }

                $analysis['frequency'][$field]++;

            }

        }

        ksort($analysis['fields']);
        ksort($analysis['frequency']);
        ksort($analysis['examples']);

        $analysis['fields'] = array_keys(
            $analysis['fields']
        );

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $analysis['field_count'] = count(
            $analysis['fields']
        );

        return $analysis;
    }

    /**
     * =========================================================================
     * Load Records
     * =========================================================================
     *
     * @return array<int,array<string,mixed>>
     */
    protected function loadRecords(
        SplFileInfo $file
    ): array
    {
        try {

            /** @var mixed $records */
            $records = require $file->getRealPath();

        } catch (Throwable) {

            return [];

        }

        if (! is_array($records)) {

            return [];

        }

        return array_values(

            array_filter(

                $records,

                static fn (mixed $record): bool => is_array($record)

            )

        );
    }
}