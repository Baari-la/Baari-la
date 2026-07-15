<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MasterData\Generator\MasterDataSchemaGenerator;
use Illuminate\Console\Command;
use Throwable;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Generate Master Data Schema Command
 * ==========================================================================
 *
 * Generates schemas.generated.php automatically from
 * Master Data definitions.
 *
 * Usage:
 *
 * php artisan digestex:generate-schema
 *
 * php artisan digestex:generate-schema --preview
 *
 * ==========================================================================
 */
final class GenerateMasterDataSchemaCommand extends Command
{
    protected $signature =
        'digestex:generate-schema
            {--preview : Preview generated schema only}';

    protected $description =
        'Generate Master Data schema automatically.';

    public function __construct(
        protected MasterDataSchemaGenerator $generator,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $startedAt = microtime(true);

        $this->renderHeader();

        try {

            $definitions = $this->option('preview')
                ? $this->generator->preview()
                : $this->generator->generate();

            $output = null;

            if (! $this->option('preview')) {

                $output = $this->generator
                    ->export($definitions);

            }

            $executionTime = microtime(true) - $startedAt;

            $this->renderPreview($definitions);

            $this->renderSummary(
                $definitions,
                $output,
                $executionTime,
            );

            $this->renderFooter(
                $definitions,
                $output,
                $executionTime,
            );

            return self::SUCCESS;

        } catch (Throwable $exception) {

            $this->newLine();

            $this->error('Schema generation failed.');

            $this->line($exception->getMessage());

            return self::FAILURE;

        }
    }

    /**
     * =========================================================================
     * Render Header
     * =========================================================================
     */
    protected function renderHeader(): void
    {
        $this->line(str_repeat('=', 65));

        $this->info(
            ' DIGESTEX MASTER DATA SCHEMA GENERATOR '
        );

        $this->line(str_repeat('=', 65));

        $this->newLine();

        $this->info(
            'Scanning Master Data...'
        );

        $this->newLine();
    }

    /**
 * =========================================================================
 * Render Footer
 * =========================================================================
 *
 * @param array<string,\App\Services\MasterData\Generator\SchemaDefinition> $definitions
 */
protected function renderFooter(
    array $definitions,
    ?string $output,
    float $executionTime
): void
{
    $this->newLine();

    $this->line(
        str_repeat('-', 65)
    );

    if ($output === null) {

        $this->info(
            'Preview completed successfully.'
        );

    } else {

        $this->info(
            'Schema generated successfully.'
        );

        $this->line(sprintf(
            'Output File : %s',
            $output
        ));
    }

    $this->line(sprintf(
        'Generated   : %d schema(s)',
        count($definitions)
    ));

   $this->line(sprintf(
        'Execution   : %.2f seconds',
        $executionTime
    ));

    $this->line(
        str_repeat('=', 65)
    );
}

    /**
 * =========================================================================
 * Render Summary
 * =========================================================================
 *
 * @param array<string,\App\Services\MasterData\Generator\SchemaDefinition> $definitions
 */
protected function renderSummary(
    array $definitions,
    ?string $output,
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

                'Files',

                count($definitions),

            ],

            [

                'Generated Schemas',

                count($definitions),

            ],

            [

                'Output File',

                $output ?? 'Preview Mode',

            ],

           [
    'Execution Time',

    sprintf(
        '%.2f sec',
        $executionTime
    ),

],

        ]

    );
}

/**
 * =========================================================================
 * Render Preview
 * =========================================================================
 *
 * @param array<string,\App\Services\MasterData\Generator\SchemaDefinition> $definitions
 */
protected function renderPreview(
    array $definitions
): void
{
    $this->newLine();

    $this->info('GENERATED SCHEMAS');

    $this->line(
        str_repeat('-', 65)
    );

    foreach ($definitions as $file => $definition) {

        $schema = $definition->toArray();

        $this->newLine();

        $this->line($file);

        $this->line(sprintf(
            '✓ %s',
            strtoupper(
                str_replace(
                    '_',
                    ' ',
                    $schema['type']
                )
            )
        ));

        $this->newLine();

        $this->line(sprintf(
            'Required Fields : %d',
            count(
                $schema['required'] ?? []
            )
        ));

        $this->line(sprintf(
            'Optional Fields : %d',
            count(
                $schema['optional'] ?? []
            )
        ));

        $this->line(sprintf(
            'References      : %d',
            count(
                $schema['references'] ?? []
            )
        ));

        $this->line(sprintf(
            'Detected Types  : %d',
            count(
                $schema['types'] ?? []
            )
        ));

        $this->line(
            str_repeat('-', 65)
        );
    }
}

    }