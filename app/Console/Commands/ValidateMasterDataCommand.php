<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MasterData\MasterDataValidationService;
use Illuminate\Console\Command;

class ValidateMasterDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'digestex:validate-masterdata';

    /**
     * The console command description.
     */
    protected $description = 'Validate all Digestex Master Data';

    
    /**
 * =========================================================================
 * Header
 * =========================================================================
 */

protected function renderHeader(): void
{
    $this->line(str_repeat('=', 65));

    $this->info(' DIGESTEX MASTER DATA VALIDATOR');

    $this->line(str_repeat('=', 65));

    $this->line('');
}


    /**
     * Execute the console command.
     */
        
    public function handle(
        MasterDataValidationService $validator
    ): int
    {
        $start = microtime(true);

        $this->renderHeader();

        $this->info('Scanning Master Data...');
        $this->newLine();

        $report = $validator->healthReport();

        $this->renderSummary($report);

        $this->renderIssues($report);

        $this->renderFooter($report, $start);

        return $report['status'] === 'HEALTHY'
            ? self::SUCCESS
            : self::FAILURE;
        }
                /**
     * =========================================================================
     * Validation Summary
     * =========================================================================
     */

    protected function renderSummary(array $report): void
    {
        $summary = $report['summary'];

        $this->line('');

        $this->table(
            ['Metric', 'Value'],
            [

                ['Status', $report['status']],

                ['Coverage', number_format(
                    $summary['coverage'],
                    2
                ) . ' %'],

                ['Files', number_format(
                    $summary['total_files']
                )],

                ['Records', number_format(
                    $summary['total_records']
                )],

                ['Errors', number_format(
                    $summary['error_count']
                )],

                ['Warnings', number_format(
                    $summary['warning_count']
                )],

                ['Knowledge Graph',
                    $summary['knowledge_ready']
                        ? 'READY'
                        : 'NOT READY'
                ],

                ['Executive AI',
                    $summary['executive_ai_ready']
                        ? 'READY'
                        : 'NOT READY'
                ],

            ]
        );

        $this->line('');
    }

        /**
     * =========================================================================
     * Validation Issues
     * =========================================================================
     */

    protected function renderIssues(array $report): void
    {
        $issues = $report['issues'];

        /*
        |--------------------------------------------------------------------------
        | Errors
        |--------------------------------------------------------------------------
        */

        if (! empty($issues['errors'])) {

            $this->error('ERRORS');

            $this->line('');

            foreach ($issues['errors'] as $error) {

                $this->line(
                    sprintf(
                        '[ERROR] %s',
                        $error['file']
                    )
                );

                $this->line(
                    '  └─ ' . $error['message']
                );

                $this->line('');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Warnings
        |--------------------------------------------------------------------------
        */

        if (! empty($issues['warnings'])) {

            $this->warn('WARNINGS');

            $this->line('');

            foreach ($issues['warnings'] as $warning) {

                $this->line(
                    sprintf(
                        '[WARNING] %s',
                        $warning['file']
                    )
                );

                $this->line(
                    '  └─ ' . $warning['message']
                );

                $this->line('');
            }
        }

        if (
            empty($issues['errors']) &&
            empty($issues['warnings'])
        ) {

            $this->info(
                '✓ No validation issues found.'
            );

            $this->line('');
        }
    }
        /**
     * =========================================================================
     * Footer
     * =========================================================================
     */

    protected function renderFooter(
        array $report,
        float $start
    ): void
    {
        $duration = round(
            microtime(true) - $start,
            2
        );

        $this->line(str_repeat('-', 65));

        if ($report['status'] === 'HEALTHY') {

            $this->info('✓ MASTER DATA HEALTHY');

            if (
                $report['summary']['knowledge_ready']
            ) {
                $this->info('✓ Knowledge Graph Ready');
            }

            if (
                $report['summary']['executive_ai_ready']
            ) {
                $this->info('✓ Executive AI Ready');
            }

        } else {

            $this->error('✗ MASTER DATA VALIDATION FAILED');

            $this->error(
                'Please resolve all validation errors before deployment.'
            );
        }

        $this->line('');

        $this->info(
            sprintf(
                'Completed in %.2f seconds',
                $duration
            )
        );

        $this->line(str_repeat('=', 65));
    }
}