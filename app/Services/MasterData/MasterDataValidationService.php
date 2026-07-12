<?php

declare(strict_types=1);

namespace App\Services\MasterData;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SplFileInfo;
use Throwable;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Master Data Validation Service
 * ==========================================================================
 *
 * Central validation engine for all Digestex Master Data.
 *
 * Responsibilities
 * ----------------
 * • Dynamic Schema Validation
 * • Canonical ID Validation
 * • Cross Reference Validation
 * • Duplicate Detection
 * • Data Quality Analysis
 * • Health Reporting
 *
 * Architecture
 * ------------
 * This service acts as the orchestration layer.
 *
 * Canonicalization
 *      ↓
 * MasterDataCanonicalizer
 *
 * Reference Resolution
 *      ↓
 * MasterDataReferenceResolver
 *
 * Reporting
 *      ↓
 * ValidateMasterDataCommand
 *
 * ==========================================================================
 */

class MasterDataValidationService
{
    /**
     * =========================================================================
     * Master Data Path
     * =========================================================================
     */
    protected string $basePath;

    /**
     * =========================================================================
     * Reference Resolver
     * =========================================================================
     */
    protected MasterDataReferenceResolver $resolver;

    /**
     * =========================================================================
     * Validation Errors
     * =========================================================================
     *
     * @var array<int,array<string,mixed>>
     */
    protected array $errors = [];

    /**
     * =========================================================================
     * Validation Warnings
     * =========================================================================
     *
     * @var array<int,array<string,mixed>>
     */
    protected array $warnings = [];

    /**
     * =========================================================================
     * Statistics
     * =========================================================================
     *
     * @var array<string,int>
     */
    protected array $stats = [

        'files' => 0,

        'records' => 0,

        'errors' => 0,

        'warnings' => 0,

    ];

    /**
     * =========================================================================
     * Constructor
     * =========================================================================
     */
    public function __construct(
        ?MasterDataReferenceResolver $resolver = null
    ) {
        $this->basePath = config_path('masterdata');

        $this->resolver = $resolver
            ?? new MasterDataReferenceResolver();
    }

    /**
     * =========================================================================
     * Validate
     * =========================================================================
     *
     * Validate every registered master data file.
     */
    public function validate(): array
    {
        $this->reset();

        $files = $this->discoverMasterDataFiles();

        foreach ($files as $file) {

            $this->validateFile($file);

        }

        $this->stats['errors'] = count(
            $this->errors
        );

        $this->stats['warnings'] = count(
            $this->warnings
        );

        return [

            'success' => empty(
                $this->errors
            ),

            'status' => empty(
                $this->errors
            )
                ? 'HEALTHY'
                : 'FAILED',

            'summary' => $this->buildSummary(),

            'statistics' => $this->stats,

            'errors' => $this->errors,

            'warnings' => $this->warnings,

        ];
    }
        /**
     * =========================================================================
     * Reset
     * =========================================================================
     *
     * Resets validator state before validation starts.
     */
    protected function reset(): void
    {
        $this->errors = [];

        $this->warnings = [];

        $this->stats = [

            'files' => 0,

            'records' => 0,

            'errors' => 0,

            'warnings' => 0,

        ];
    }

    /**
     * =========================================================================
     * Discover Master Data Files
     * =========================================================================
     *
     * Returns every PHP file inside config/masterdata
     * except root configuration files.
     */
    protected function discoverMasterDataFiles(): Collection
    {
        return collect(

            File::allFiles($this->basePath)

        )
        ->filter(function (SplFileInfo $file) {

            $relative = Str::after(

                $file->getRealPath(),

                $this->basePath . DIRECTORY_SEPARATOR

            );

            /*
            |--------------------------------------------------------------------------
            | Ignore root configuration files
            |--------------------------------------------------------------------------
            */

            if (! str_contains(
                $relative,
                DIRECTORY_SEPARATOR
            )) {

                return false;

            }

            return Str::endsWith(

                $file->getFilename(),

                '.php'

            );

        })
        ->sortBy(function (SplFileInfo $file) {

            return str_replace(

                $this->basePath . DIRECTORY_SEPARATOR,

                '',

                $file->getRealPath()

            );

        })
        ->values();
    }

    /**
     * =========================================================================
     * Validate File
     * =========================================================================
     *
     * Validates one master data file.
     */
    protected function validateFile(
        SplFileInfo $file
    ): void
    {
        $this->stats['files']++;

        $relative = str_replace(

            $this->basePath . DIRECTORY_SEPARATOR,

            '',

            $file->getRealPath()

        );

        /*
        |--------------------------------------------------------------------------
        | Load File
        |--------------------------------------------------------------------------
        */

        try {

            $records = require $file->getRealPath();

        } catch (Throwable $exception) {

            $this->addError(

                $relative,

                sprintf(

                    'Unable to load file (%s)',

                    $exception->getMessage()

                )

            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Root Structure
        |--------------------------------------------------------------------------
        */

        if (! is_array($records)) {

            $this->addError(

                $relative,

                'Master data file must return an array.'

            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate Tracker
        |--------------------------------------------------------------------------
        */

        $duplicates = [];

        foreach ($records as $index => $record) {

            if (! is_array($record)) {

                $this->addError(

                    $relative,

                    sprintf(

                        'Record #%d must be an array.',

                        $index

                    )

                );

                continue;
            }

            $this->stats['records']++;

            /*
            |--------------------------------------------------------------------------
            | Structure
            |--------------------------------------------------------------------------
            */

            $this->validateStructure(

                $relative,

                $record,

                $index

            );

            /*
            |--------------------------------------------------------------------------
            | Canonical ID
            |--------------------------------------------------------------------------
            */

            if (isset($record['id'])) {

                $this->validateCanonicalId(

                    $relative,

                    (string) $record['id']

                );

                if (isset($duplicates[$record['id']])) {

                    $this->addError(

                        $relative,

                        sprintf(

                            'Duplicate ID [%s]',

                            $record['id']

                        )

                    );

                } else {

                    $duplicates[$record['id']] = true;

                }
            }

            /*
            |--------------------------------------------------------------------------
            | Cross References
            |--------------------------------------------------------------------------
            */

            $this->validateCrossReferences(

                $relative,

                $record

            );
        }
     }
    /**
     * =========================================================================
     * Schema
     * =========================================================================
     *
     * Returns schema definition for a master data file.
     */
    protected function schema(
        string $relativeFile
    ): array
    {
        $schemas = config(
            'masterdata.schemas',
            []
        );

        $schema = $schemas[$relativeFile] ?? [];

        return is_array($schema)
            ? $schema
            : [];
    }

    /**
     * =========================================================================
     * Required Fields
     * =========================================================================
     *
     * Returns required fields defined by schema.
     *
     * @return array<int,string>
     */
    protected function requiredFields(
        string $relativeFile
    ): array
    {
        $required = $this->schema(
            $relativeFile
        )['required'] ?? [];

        if (! is_array($required)) {

            return [];

        }

        return array_values($required);
    }

    /**
     * =========================================================================
     * Optional Fields
     * =========================================================================
     *
     * @return array<int,string>
     */
    protected function optionalFields(
        string $relativeFile
    ): array
    {
        $optional = $this->schema(
            $relativeFile
        )['optional'] ?? [];

        if (! is_array($optional)) {

            return [];

        }

        return array_values($optional);
    }

    /**
     * =========================================================================
     * Schema Type
     * =========================================================================
     *
     * Supported:
     *
     * - knowledge_node
     * - lookup
     * - mapping
     */
    protected function schemaType(
        string $relativeFile
    ): string
    {
        $type = $this->schema(
            $relativeFile
        )['type'] ?? 'lookup';

        return is_string($type)
            ? $type
            : 'lookup';
    }

    /**
     * =========================================================================
     * Schema References
     * =========================================================================
     *
     * Returns cross-reference definitions.
     *
     * Example:
     *
     * [
     *     'common_certifications'
     *          => 'Certification/certifications.php',
     *
     *     'typical_products'
     *          => 'Products/products.php',
     * ]
     *
     * @return array<string,string>
     */
    protected function schemaReferences(
        string $relativeFile
    ): array
    {
        $references = $this->schema(
            $relativeFile
        )['references'] ?? [];

        if (! is_array($references)) {

            return [];

        }

        return $references;
    }

    /**
     * =========================================================================
     * Schema Validation Rules
     * =========================================================================
     *
     * Reserved for future expansion.
     *
     * @return array<string,mixed>
     */
    protected function schemaValidation(
        string $relativeFile
    ): array
    {
        $rules = $this->schema(
            $relativeFile
        )['validation'] ?? [];

        return is_array($rules)
            ? $rules
            : [];
    }

    /**
     * =========================================================================
     * Schema Display
     * =========================================================================
     *
     * Reserved for UI metadata.
     *
     * @return array<string,mixed>
     */
    protected function schemaDisplay(
        string $relativeFile
    ): array
    {
        $display = $this->schema(
            $relativeFile
        )['display'] ?? [];

        return is_array($display)
            ? $display
            : [];
    }

        /**
     * =========================================================================
     * Structure Validation
     * =========================================================================
     *
     * Validates a record against its schema definition.
     */
    protected function validateStructure(
        string $file,
        array $record,
        int $index
    ): void
    {
        /*
        |--------------------------------------------------------------------------
        | Required Fields
        |--------------------------------------------------------------------------
        */

        foreach ($this->requiredFields($file) as $field) {

            if (! array_key_exists($field, $record)) {

                $this->addError(

                    $file,

                    sprintf(
                        'Record #%d missing required field [%s]',
                        $index,
                        $field
                    )

                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Empty String Validation
            |--------------------------------------------------------------------------
            */

            if (
                is_string($record[$field]) &&
                trim($record[$field]) === ''
            ) {

                $this->addWarning(

                    $file,

                    sprintf(
                        'Record #%d contains empty value [%s]',
                        $index,
                        $field
                    )

                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Schema Specific Validation
        |--------------------------------------------------------------------------
        */

        switch ($this->schemaType($file)) {

            case 'knowledge_node':

                $this->validateKnowledgeNode(
                    $file,
                    $record,
                    $index
                );

                break;

            case 'lookup':

                $this->validateLookupNode(
                    $file,
                    $record,
                    $index
                );

                break;

            case 'mapping':

                $this->validateMappingNode(
                    $file,
                    $record,
                    $index
                );

                break;
        }
    }

    /**
     * =========================================================================
     * Knowledge Node Validation
     * =========================================================================
     */
    protected function validateKnowledgeNode(
        string $file,
        array $record,
        int $index
    ): void
    {
        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        if (
            array_key_exists('priority', $record) &&
            ! is_numeric($record['priority'])
        ) {

            $this->addWarning(

                $file,

                sprintf(
                    'Record #%d priority should be numeric.',
                    $index
                )

            );
        }

        /*
        |--------------------------------------------------------------------------
        | Description Length
        |--------------------------------------------------------------------------
        */

        if (
            isset($record['description']) &&
            strlen(trim((string) $record['description'])) < 10
        ) {

            $this->addWarning(

                $file,

                sprintf(
                    'Record #%d description is too short.',
                    $index
                )

            );
        }

        /*
        |--------------------------------------------------------------------------
        | Icon
        |--------------------------------------------------------------------------
        */

        if (
            array_key_exists('icon', $record) &&
            empty($record['icon'])
        ) {

            $this->addWarning(

                $file,

                sprintf(
                    'Record #%d icon is empty.',
                    $index
                )

            );
        }
    }

    /**
     * =========================================================================
     * Lookup Validation
     * =========================================================================
     *
     * Reserved for future enhancement.
     */
    protected function validateLookupNode(
        string $file,
        array $record,
        int $index
    ): void
    {
        // Reserved.
    }

    /**
     * =========================================================================
     * Mapping Validation
     * =========================================================================
     *
     * Reserved for future enhancement.
     */
    protected function validateMappingNode(
        string $file,
        array $record,
        int $index
    ): void
    {
        // Reserved.
    }
    /**
     * =========================================================================
     * Canonical ID Validation
     * =========================================================================
     *
     * Validates canonical master data ID.
     */
    protected function validateCanonicalId(
        string $file,
        string $id
    ): void
    {
        $canonical = new MasterDataCanonicalizer();

        $normalized = $canonical->canonicalize($id);

        if ($normalized !== $id) {

            $this->addWarning(

                $file,

                sprintf(
                    'ID [%s] should be canonical [%s]',
                    $id,
                    $normalized
                )

            );
        }
    }

    /**
     * =========================================================================
     * Resolve Reference
     * =========================================================================
     */
    protected function resolveReference(
        string $referenceFile,
        string $reference
    ): ?string
    {
        return $this->resolver->resolve(

            $referenceFile,

            $reference

        );
    }

    /**
     * =========================================================================
     * Suggest Reference
     * =========================================================================
     */
    protected function suggestReference(
        string $referenceFile,
        string $reference
    ): ?string
    {
        return $this->resolver->suggest(

            $referenceFile,

            $reference

        );
    }

    /**
     * =========================================================================
     * Cross Reference Validation
     * =========================================================================
     */
    protected function validateCrossReferences(
        string $file,
        array $record
    ): void
    {
        foreach (

            $this->schemaReferences($file)

            as $field => $referenceFile

        ) {

            /*
            |--------------------------------------------------------------------------
            | Field Not Present
            |--------------------------------------------------------------------------
            */

            if (! array_key_exists($field, $record)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Convert To Array
            |--------------------------------------------------------------------------
            */

            $references = $record[$field];

            if (! is_array($references)) {

                $references = [$references];

            }

            /*
            |--------------------------------------------------------------------------
            | Validate Every Reference
            |--------------------------------------------------------------------------
            */

            foreach ($references as $reference) {

                if (

                    $reference === null ||

                    trim((string) $reference) === ''

                ) {

                    continue;

                }

                /*
                |--------------------------------------------------------------------------
                | Resolve
                |--------------------------------------------------------------------------
                */

                $resolved = $this->resolveReference(

                    $referenceFile,

                    (string) $reference

                );

                if ($resolved !== null) {

                    continue;

                }

                /*
                |--------------------------------------------------------------------------
                | Suggestion
                |--------------------------------------------------------------------------
                */

                $suggestion = $this->suggestReference(

                    $referenceFile,

                    (string) $reference

                );

                $message = sprintf(

                    'Unknown reference [%s] in field [%s]',

                    $reference,

                    $field

                );

                if ($suggestion !== null) {

                    $message .= sprintf(

                        '. Did you mean [%s]?',

                        $suggestion

                    );

                }

                $this->addError(

                    $file,

                    $message

                );
            }
        }
    }
     
     