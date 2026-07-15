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
 * Validates every Digestex Master Data definition.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * • Discover Master Data
 * • Dynamic Schema Validation
 * • Structure Validation
 * • Canonical ID Validation
 * • Cross Reference Validation
 * • Duplicate Detection
 * • Data Quality Analysis
 * • Health Reporting
 *
 * This service is an ORCHESTRATOR.
 *
 * It delegates:
 *
 * MasterDataCanonicalizer
 *      ↓
 * Canonical ID Validation
 *
 * MasterDataReferenceResolver
 *      ↓
 * Cross Reference Validation
 *
 * ==========================================================================
 */
class MasterDataValidationService
{
    /**
     * =========================================================================
     * Master Data Location
     * =========================================================================
     */
    protected string $basePath;

    /**
     * =========================================================================
     * Canonicalizer
     * =========================================================================
     */
    protected MasterDataCanonicalizer $canonicalizer;

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
     * Validation Statistics
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
        ?MasterDataCanonicalizer $canonicalizer = null,
        ?MasterDataReferenceResolver $resolver = null
    ) {
        $this->basePath = config_path('masterdata');

        $this->canonicalizer =
            $canonicalizer
            ?? new MasterDataCanonicalizer();

        $this->resolver =
            $resolver
            ?? new MasterDataReferenceResolver();
    }

    /**
     * =========================================================================
     * Validate
     * =========================================================================
     *
     * Executes full master data validation.
     *
     * @return array{
     *     success:bool,
     *     status:string,
     *     summary:array<string,mixed>,
     *     statistics:array<string,int>,
     *     errors:array<int,array<string,mixed>>,
     *     warnings:array<int,array<string,mixed>>
     * }
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
     * Reset Validator
     * =========================================================================
     *
     * Resets validator state before each validation session.
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
 */
protected function discoverMasterDataFiles(): Collection
{
    return collect(
        File::allFiles($this->basePath)
    )
    ->filter(function (SplFileInfo $file): bool {

        return $file->getExtension() === 'php'
            && ! in_array(
                $file->getFilename(),
                [
                    'schemas.php',
                    'aliases.php',
                ],
                true
            );

    })
    ->sortBy(
        fn (SplFileInfo $file) => $this->relativeFile($file)
    )
    ->values();
}

protected function relativeFile(
    SplFileInfo $file
): string
{
    $relative = str_replace(
        $this->basePath . DIRECTORY_SEPARATOR,
        '',
        $file->getRealPath()
    );

    return str_replace('\\', '/', $relative);
}

   
    /**
     * =========================================================================
     * Validate File
     * =========================================================================
     *
     * Validates one master data definition file.
     */
    protected function validateFile(
        SplFileInfo $file
    ): void
    {

       
        $this->stats['files']++;

        $relative = $this->relativeFile($file);

        /*
        |--------------------------------------------------------------------------
        | Load File
        |--------------------------------------------------------------------------
        */

        try {

            /** @var mixed $records */
            $records = require $file->getRealPath();

        } catch (Throwable $exception) {

            $this->addError(
                $relative,
                'load_error',
                sprintf(
                    'Unable to load file (%s).',
                    $exception->getMessage()
                )
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Root Structure Validation
        |--------------------------------------------------------------------------
        */

        if (! is_array($records)) {

            $this->addError(
                $relative,
                'invalid_structure',
                'Master data file must return an array.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate ID Tracker
        |--------------------------------------------------------------------------
        */

        $duplicateIds = [];

        foreach ($records as $index => $record) {

            if (! is_array($record)) {

                $this->addError(
                    $relative,
                    'invalid_record',
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
            | Structure Validation
            |--------------------------------------------------------------------------
            */

            $this->validateStructure(
                $relative,
                $record,
                (int) $index
            );

            /*
            |--------------------------------------------------------------------------
            | ID Validation
            |--------------------------------------------------------------------------
            */

            if (array_key_exists('id', $record)) {

                $id = (string) $record['id'];

                $this->validateCanonicalId(
                    $relative,
                    $id
                );

                /*
                |--------------------------------------------------------------------------
                | Duplicate Detection
                |--------------------------------------------------------------------------
                */

                if (isset($duplicateIds[$id])) {

                    $this->addError(
                        $relative,
                        'duplicate_id',
                        sprintf(
                            'Duplicate ID [%s].',
                            $id
                        )
                    );

                } else {

                    $duplicateIds[$id] = true;

                }
            }

            /*
            |--------------------------------------------------------------------------
            | Cross Reference Validation
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
     * Schema Definition
     * =========================================================================
     *
     * Returns schema configuration for a master data file.
     */
    protected function schema(
        string $relativeFile
    ): array
    {
        $schemas = config(
            'masterdata.schemas',
            []
        );

        if (! is_array($schemas)) {
            return [];
        }

        $schema = $schemas[$relativeFile] ?? [];

        return is_array($schema)
            ? $schema
            : [];
    }

    /**
     * =========================================================================
     * Schema Exists
     * =========================================================================
     */
    protected function hasSchema(
        string $relativeFile
    ): bool
    {
        return $this->schema($relativeFile) !== [];
    }

    /**
     * =========================================================================
     * Schema Type
     * =========================================================================
     *
     * Supported:
     *
     * knowledge_node
     * lookup
     * mapping
     */
    protected function schemaType(
        string $relativeFile
    ): string
    {
        $schema = $this->schema(
            $relativeFile
        );

        return (string) (
            $schema['type']
            ?? 'knowledge_node'
        );
    }

    /**
     * =========================================================================
     * Required Fields
     * =========================================================================
     *
     * @return array<int,string>
     */
    protected function requiredFields(
        string $relativeFile
    ): array
    {
        $schema = $this->schema(
            $relativeFile
        );

        $fields = $schema['required']
            ?? [];

        return is_array($fields)
            ? array_values($fields)
            : [];
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
        $schema = $this->schema(
            $relativeFile
        );

        $fields = $schema['optional']
            ?? [];

        return is_array($fields)
            ? array_values($fields)
            : [];
    }

    /**
     * =========================================================================
     * Reference Definitions
     * =========================================================================
     *
     * Example:
     *
     * [
     *      'common_certifications'
     *          => 'Certification/certifications.php',
     *
     *      'typical_products'
     *          => 'Products/products.php',
     * ]
     *
     * @return array<string,string>
     */
    protected function schemaReferences(
        string $relativeFile
    ): array
    {
        $schema = $this->schema(
            $relativeFile
        );

        $references = $schema['references']
            ?? [];

        return is_array($references)
            ? $references
            : [];
    }

    /**
     * =========================================================================
     * Validation Rules
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function schemaValidation(
        string $relativeFile
    ): array
    {
        $schema = $this->schema(
            $relativeFile
        );

        $rules = $schema['validation']
            ?? [];

        return is_array($rules)
            ? $rules
            : [];
    }

    /**
     * =========================================================================
     * Display Configuration
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
        $schema = $this->schema(
            $relativeFile
        );

        $display = $schema['display']
            ?? [];

        return is_array($display)
            ? $display
            : [];
    }

    /**
     * =========================================================================
     * Dynamic Required Fields
     * =========================================================================
     *
     * Returns required fields after applying conditional rules.
     *
     * Reserved for future implementation.
     *
     * @return array<int,string>
     */
    protected function dynamicRequiredFields(
        string $relativeFile,
        array $record
    ): array
    {
        return $this->requiredFields(
            $relativeFile
        );
    }

    /**
     * =========================================================================
     * Schema Metadata
     * =========================================================================
     *
     * Reserved for future ontology support.
     *
     * @return array<string,mixed>
     */
    protected function schemaMetadata(
        string $relativeFile
    ): array
    {
        $schema = $this->schema(
            $relativeFile
        );

        $metadata = $schema['metadata']
            ?? [];

        return is_array($metadata)
            ? $metadata
            : [];
    }
    /**
     * =========================================================================
     * Structure Validation
     * =========================================================================
     *
     * Validates one master data record against its schema.
     */
    protected function validateStructure(
        string $file,
        array $record,
        int $index
    ): void
    {
        /*
        |--------------------------------------------------------------------------
        | Schema Exists
        |--------------------------------------------------------------------------
        */

        if (! $this->hasSchema($file)) {

            $this->addWarning(
                $file,
                'missing_schema',
                sprintf(
                    'Schema for [%s] is not registered.',
                    $file
                )
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Required Fields
        |--------------------------------------------------------------------------
        */

        foreach (
            $this->dynamicRequiredFields(
                $file,
                $record
            ) as $field
        ) {

            if (! array_key_exists($field, $record)) {

                $this->addError(
                    $file,
                    'missing_required_field',
                    sprintf(
                        'Record #%d missing required field [%s].',
                        $index,
                        $field
                    )
                );

                continue;
            }

            if (
                is_string($record[$field]) &&
                trim($record[$field]) === ''
            ) {

                $this->addWarning(
                    $file,
                    'empty_required_field',
                    sprintf(
                        'Record #%d contains empty value in [%s].',
                        $index,
                        $field
                    )
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Unknown Fields
        |--------------------------------------------------------------------------
        */

        $allowed = array_merge(
            $this->requiredFields($file),
            $this->optionalFields($file)
        );

        foreach (array_keys($record) as $field) {

            if (
                in_array(
                    $field,
                    $allowed,
                    true
                )
            ) {
                continue;
            }

            $this->addWarning(
                $file,
                'unknown_field',
                sprintf(
                    'Unknown field [%s] found in record #%d.',
                    $field,
                    $index
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Schema Specific Validation
        |--------------------------------------------------------------------------
        */

        $this->validateBySchemaType(
            $file,
            $record,
            $index
        );
    }

    /**
     * =========================================================================
     * Schema Dispatcher
     * =========================================================================
     */
    protected function validateBySchemaType(
        string $file,
        array $record,
        int $index
    ): void
    {
        $type = $this->schemaType($file);

        match ($type) {

            'knowledge_node'
                => $this->validateKnowledgeNode(
                    $file,
                    $record,
                    $index
                ),

            'lookup'
                => $this->validateLookupNode(
                    $file,
                    $record,
                    $index
                ),

            'mapping'
                => $this->validateMappingNode(
                    $file,
                    $record,
                    $index
                ),

            default
                => $this->addWarning(
                    $file,
                    'unknown_schema_type',
                    sprintf(
                        'Unknown schema type [%s].',
                        $type
                    )
                ),
        };
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
        $rules = $this->schemaValidation($file);

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
                'invalid_priority',
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

        $minimum = (int) (
            $rules['minimum_description']
            ?? 10
        );

        if (
            isset($record['description']) &&
            strlen(
                trim(
                    (string) $record['description']
                )
            ) < $minimum
        ) {

            $this->addWarning(
                $file,
                'short_description',
                sprintf(
                    'Record #%d description should contain at least %d characters.',
                    $index,
                    $minimum
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Icon
        |--------------------------------------------------------------------------
        */

        if (
            ($rules['require_icon'] ?? false) === true &&
            empty($record['icon'])
        ) {

            $this->addWarning(
                $file,
                'missing_icon',
                sprintf(
                    'Record #%d should define an icon.',
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
     * Reserved.
     */
    protected function validateLookupNode(
        string $file,
        array $record,
        int $index
    ): void
    {
        //
    }

    /**
     * =========================================================================
     * Mapping Validation
     * =========================================================================
     *
     * Reserved.
     */
    protected function validateMappingNode(
        string $file,
        array $record,
        int $index
    ): void
    {
        //
    }

    /**
     * =========================================================================
     * Canonical ID Validation
     * =========================================================================
     *
     * Validates that the record ID follows the canonical format.
     */
    protected function validateCanonicalId(
        string $file,
        string $id
    ): void
    {
        $canonical = $this->canonicalizer
            ->canonicalize($id);

        if ($canonical !== $id) {

            $this->addWarning(
                $file,
                'non_canonical_id',
                sprintf(
                    'ID [%s] should use canonical form [%s].',
                    $id,
                    $canonical
                )
            );
        }
    }

    /**
     * =========================================================================
     * Resolve Reference
     * =========================================================================
     *
     * Returns canonical ID if reference exists.
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
     *
     * Returns best candidate when reference cannot be resolved.
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
     *
     * Validates every configured reference defined in schemas.php.
     */
    protected function validateCrossReferences(
        string $file,
        array $record
    ): void
    {
        $references = $this->schemaReferences(
            $file
        );

        if ($references === []) {
            return;
        }

        foreach ($references as $field => $referenceFile) {

            if (! array_key_exists($field, $record)) {
                continue;
            }

            $values = $record[$field];

            if (! is_array($values)) {
                $values = [$values];
            }

            foreach ($values as $value) {

                if ($value === null) {
                    continue;
                }

                $reference = trim((string) $value);

                if ($reference === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Resolve Reference
                |--------------------------------------------------------------------------
                */

                $resolved = $this->resolveReference(
                    $referenceFile,
                    $reference
                );

                if ($resolved !== null) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Suggest Similar Reference
                |--------------------------------------------------------------------------
                */

                $suggestion = $this->suggestReference(
                    $referenceFile,
                    $reference
                );

                $message = sprintf(
                    'Unknown reference [%s] in field [%s].',
                    $reference,
                    $field
                );

                if ($suggestion !== null) {

                    $message .= sprintf(
                        ' Did you mean [%s]?',
                        $suggestion
                    );
                }

                $this->addError(
                    $file,
                    'unknown_reference',
                    $message
                );
            }
        }
    }
    /**
     * =========================================================================
     * Add Error
     * =========================================================================
     *
     * Registers a validation error.
     */
    protected function addError(
        string $file,
        string $type,
        string $message
    ): void
    {
        $this->errors[] = [

            'file' => $file,

            'type' => $type,

            'message' => $message,

        ];
    }

    /**
     * =========================================================================
     * Add Warning
     * =========================================================================
     *
     * Registers a validation warning.
     */
    protected function addWarning(
        string $file,
        string $type,
        string $message
    ): void
    {
        $this->warnings[] = [

            'file' => $file,

            'type' => $type,

            'message' => $message,

        ];
    }

    /**
     * =========================================================================
     * Has Errors
     * =========================================================================
     */
    protected function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    /**
     * =========================================================================
     * Has Warnings
     * =========================================================================
     */
    protected function hasWarnings(): bool
    {
        return $this->warnings !== [];
    }

    /**
     * =========================================================================
     * Error Count
     * =========================================================================
     */
    protected function errorCount(): int
    {
        return count(
            $this->errors
        );
    }

    /**
     * =========================================================================
     * Warning Count
     * =========================================================================
     */
    protected function warningCount(): int
    {
        return count(
            $this->warnings
        );
    }

    /**
     * =========================================================================
     * Group Errors
     * =========================================================================
     *
     * @return array<string,array<int,array<string,mixed>>>
     */
    protected function groupedErrors(): array
    {
        $grouped = [];

        foreach ($this->errors as $issue) {

            $grouped[
                $issue['file']
            ][] = $issue;
        }

        ksort($grouped);

        return $grouped;
    }

    /**
     * =========================================================================
     * Group Warnings
     * =========================================================================
     *
     * @return array<string,array<int,array<string,mixed>>>
     */
    protected function groupedWarnings(): array
    {
        $grouped = [];

        foreach ($this->warnings as $issue) {

            $grouped[
                $issue['file']
            ][] = $issue;
        }

        ksort($grouped);

        return $grouped;
    }

    /**
     * =========================================================================
     * Issue Statistics
     * =========================================================================
     *
     * Returns validation issue statistics.
     *
     * @return array<string,int>
     */
    protected function issueStatistics(): array
    {
        return [

            'errors' => $this->errorCount(),

            'warnings' => $this->warningCount(),

            'total' =>
                $this->errorCount()
                + $this->warningCount(),

        ];
    }
    /**
     * =========================================================================
     * Build Summary
     * =========================================================================
     *
     * Builds validation summary.
     *
     * @return array<string,mixed>
     */
    protected function buildSummary(): array
    {
        $coverage = $this->calculateCoverage();

        return [

            'coverage' => $coverage,

            'total_files' => $this->stats['files'],

            'total_records' => $this->stats['records'],

            'error_count' => $this->errorCount(),

            'warning_count' => $this->warningCount(),

            'knowledge_ready'
                => ! $this->hasErrors(),

            'executive_ai_ready'
                => ! $this->hasErrors(),

        ];
    }

    /**
     * =========================================================================
     * Calculate Coverage
     * =========================================================================
     *
     * Calculates master data quality coverage.
     */
    protected function calculateCoverage(): float
    {
        if ($this->stats['records'] === 0) {

            return 0.0;

        }

        $issues = $this->errorCount()
            + $this->warningCount();

        $coverage = 100
            - (
                ($issues / $this->stats['records'])
                * 100
            );

        return max(
            0,
            round($coverage, 2)
        );
    }

    /**
     * =========================================================================
     * Health Report
     * =========================================================================
     *
     * Returns complete validation report.
     *
     * @return array<string,mixed>
     */
    public function healthReport(): array
    {
        return [

            'status' => $this->status(),

            'summary' => $this->buildSummary(),

            'statistics' => $this->stats,

            'issues' => [

                'errors' => $this->groupedErrors(),

                'warnings' => $this->groupedWarnings(),

            ],

        ];
    }

    /**
     * =========================================================================
     * Status
     * =========================================================================
     */
    public function status(): string
    {
        return $this->hasErrors()
            ? 'FAILED'
            : 'HEALTHY';
    }
    /**
     * =========================================================================
     * Statistics
     * =========================================================================
     *
     * Returns validation statistics.
     *
     * @return array<string,int>
     */
    public function statistics(): array
    {
        return $this->stats;
    }

    /**
     * =========================================================================
     * Errors
     * =========================================================================
     *
     * Returns validation errors.
     *
     * @return array<int,array<string,mixed>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * =========================================================================
     * Warnings
     * =========================================================================
     *
     * Returns validation warnings.
     *
     * @return array<int,array<string,mixed>>
     */
    public function warnings(): array
    {
        return $this->warnings;
    }

    /**
     * =========================================================================
     * Coverage
     * =========================================================================
     *
     * Returns validation coverage percentage.
     */
    public function coverage(): float
    {
        return $this->calculateCoverage();
    }

    /**
     * =========================================================================
     * Is Healthy
     * =========================================================================
     *
     * Indicates whether the current master data set is healthy.
     */
    public function isHealthy(): bool
    {
        return ! $this->hasErrors();
    }

    /**
     * =========================================================================
     * Total Files
     * =========================================================================
     */
    public function totalFiles(): int
    {
        return $this->stats['files'];
    }

    /**
     * =========================================================================
     * Total Records
     * =========================================================================
     */
    public function totalRecords(): int
    {
        return $this->stats['records'];
    }

    /**
     * =========================================================================
     * Total Errors
     * =========================================================================
     */
    public function totalErrors(): int
    {
        return $this->errorCount();
    }

    /**
     * =========================================================================
     * Total Warnings
     * =========================================================================
     */
    public function totalWarnings(): int
    {
        return $this->warningCount();
    }

    /**
     * =========================================================================
     * Validation Result
     * =========================================================================
     *
     * Returns complete validation result.
     *
     * @return array<string,mixed>
     */
    public function result(): array
    {
        return [

            'healthy' => $this->isHealthy(),

            'status' => $this->status(),

            'coverage' => $this->coverage(),

            'statistics' => $this->statistics(),

            'summary' => $this->buildSummary(),

            'errors' => $this->errors(),

            'warnings' => $this->warnings(),

        ];
    }

        /**
     * =========================================================================
     * Quality Analysis
     * =========================================================================
     *
     * Performs additional quality analysis after validation.
     */
    protected function analyzeQuality(): void
    {
        $this->validateDuplicateLabels();

        $this->validateUnusedSchemas();

        $this->validateStatisticsConsistency();
    }

    /**
     * =========================================================================
     * Duplicate Label Validation
     * =========================================================================
     *
     * Detects duplicated labels within the same master data file.
     */
    protected function validateDuplicateLabels(): void
    {
        foreach ($this->discoverMasterDataFiles() as $file) {

            $relative = $this->relativeFile($file);

            $records = require $file->getRealPath();

            
            if (! is_array($records)) {
                continue;
            }

            $labels = [];

            foreach ($records as $record) {

                if (
                    ! is_array($record) ||
                    ! isset($record['label'])
                ) {
                    continue;
                }

                $label = mb_strtolower(
                    trim((string) $record['label'])
                );

                if (isset($labels[$label])) {

                    $this->addWarning(
                        $relative,
                        'duplicate_label',
                        sprintf(
                            'Duplicate label [%s].',
                            $record['label']
                        )
                    );

                } else {

                    $labels[$label] = true;

                }
            }
        }
    }

    /**
     * =========================================================================
     * Unused Schema Validation
     * =========================================================================
     *
     * Warns when a schema exists but no corresponding master data file exists.
     */
    protected function validateUnusedSchemas(): void
    {
        $schemas = config(
            'masterdata.schemas',
            []
        );

        if (! is_array($schemas)) {
            return;
        }

        foreach (array_keys($schemas) as $relative) {

            $path = $this->basePath
                . DIRECTORY_SEPARATOR
                . $relative;

            if (! File::exists($path)) {

                $this->addWarning(
                    $relative,
                    'unused_schema',
                    'Schema defined but master data file not found.'
                );
            }
        }
    }

    /**
     * =========================================================================
     * Statistics Consistency
     * =========================================================================
     *
     * Ensures statistics remain internally consistent.
     */
    protected function validateStatisticsConsistency(): void
    {
        if (
            $this->stats['errors']
            !== $this->errorCount()
        ) {

            $this->stats['errors']
                = $this->errorCount();
        }

        if (
            $this->stats['warnings']
            !== $this->warningCount()
        ) {

            $this->stats['warnings']
                = $this->warningCount();
        }
    }

    /**
     * =========================================================================
     * Finalize Validation
     * =========================================================================
     *
     * Executes final quality analysis before returning results.
     */
    protected function finalizeValidation(): void
    {
        $this->analyzeQuality();

        $this->stats['errors']
            = $this->errorCount();

        $this->stats['warnings']
            = $this->warningCount();
    }
    /**
     * =========================================================================
     * Refresh
     * =========================================================================
     *
     * Re-runs validation and returns the latest health report.
     *
     * @return array<string,mixed>
     */
    public function refresh(): array
    {
        $this->validate();

        return $this->healthReport();
    }

    /**
     * =========================================================================
     * Export Issues
     * =========================================================================
     *
     * Returns grouped validation issues.
     *
     * @return array<string,mixed>
     */
    public function exportIssues(): array
    {
        return [

            'errors' => $this->groupedErrors(),

            'warnings' => $this->groupedWarnings(),

        ];
    }

    /**
     * =========================================================================
     * Export Summary
     * =========================================================================
     *
     * Returns summary suitable for dashboards.
     *
     * @return array<string,mixed>
     */
    public function exportSummary(): array
    {
        return [

            'status' => $this->status(),

            'coverage' => $this->coverage(),

            'statistics' => $this->statistics(),

            'summary' => $this->buildSummary(),

        ];
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * Converts validator result into a normalized array.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'status' => $this->status(),

            'coverage' => $this->coverage(),

            'statistics' => $this->statistics(),

            'summary' => $this->buildSummary(),

            'issues' => $this->exportIssues(),

        ];
    }

    /**
     * =========================================================================
     * String Representation
     * =========================================================================
     */
    public function __toString(): string
    {
        return sprintf(

            'MasterDataValidationService(status=%s, coverage=%s%%)',

            $this->status(),

            number_format(
                $this->coverage(),
                2
            )

        );
    }
}
    