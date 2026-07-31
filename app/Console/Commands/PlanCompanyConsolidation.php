<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Company;
use App\Services\Company\Identity\CompanyIdentityResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Company Identity Planner
 * ==========================================================================
 *
 * Builds a READ-ONLY canonical identity plan from legacy company records.
 *
 * Identity resolution is delegated to CompanyIdentityResolver so the same
 * rules can later be reused by the canonical identity population process.
 *
 * This command DOES NOT:
 *
 * - update companies
 * - delete companies
 * - merge legacy records
 * - move child records
 * - modify capabilities
 * - create canonical identities
 * - correct legacy data
 *
 * Version: 3.0
 */
class PlanCompanyConsolidation extends Command
{
    protected $signature = 'companies:plan-consolidation
                            {--limit=20 : Maximum identities to display}
                            {--name= : Filter by normalized company name}
                            {--duplicates : Display only identities with multiple source records}
                            {--review : Display only identities requiring review}';

    protected $description =
        'Build a read-only company identity and capability-union plan';

    /**
     * Execute command.
     */
    public function handle(
        CompanyIdentityResolver $resolver
    ): int {
        $this->newLine();

        $this->info(
            'DIGESTEX Company Identity Planner'
        );

        $this->line(
            'Mode: READ ONLY'
        );

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Load Legacy Companies
        |--------------------------------------------------------------------------
        */

        $companies = Company::query()
            ->select([
                'id',
                'nama_perusahaan',
                'sektor',
                'country_code',
                'country_name',
                'data_source',
            ])
            ->with([
                'capabilities:id,company_id,capability',
            ])
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Resolve Identity Plans
        |--------------------------------------------------------------------------
        |
        | All identity-resolution logic now lives in
        | CompanyIdentityResolver.
        |
        */

        $plans = $resolver->resolve(
            $companies
        );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $usableRecords = $plans->sum(
            fn (array $plan): int =>
                $plan['source_count']
        );

        $singleRecordIdentities = $plans
            ->where(
                'status',
                'SINGLE_LEGACY_RECORD'
            )
            ->count();

        $multipleRecordIdentities = $plans
            ->where(
                'status',
                'MULTIPLE_LEGACY_RECORDS'
            )
            ->count();

        $reviewIdentities = $plans
            ->where(
                'identity_status',
                'REVIEW'
            )
            ->count();

        $potentialRedundantRecords = $plans
            ->sum(
                fn (array $plan): int =>
                    max(
                        0,
                        $plan['source_count'] - 1
                    )
            );

        $statistics = [
            'legacy_records' =>
                $companies->count(),

            'usable_records' =>
                $usableRecords,

            'unique_identities' =>
                $plans->count(),

            'single_record_identities' =>
                $singleRecordIdentities,

            'multiple_record_identities' =>
                $multipleRecordIdentities,

            'review_identities' =>
                $reviewIdentities,

            'potential_redundant_records' =>
                $potentialRedundantRecords,
        ];

        $this->table(
            [
                'Metric',
                'Total',
            ],
            [
                [
                    'Legacy company records',
                    $statistics['legacy_records'],
                ],
                [
                    'Usable company records',
                    $statistics['usable_records'],
                ],
                [
                    'Unique company identities',
                    $statistics['unique_identities'],
                ],
                [
                    'Single-record identities',
                    $statistics[
                        'single_record_identities'
                    ],
                ],
                [
                    'Multiple-record identities',
                    $statistics[
                        'multiple_record_identities'
                    ],
                ],
                [
                    'Identities requiring review',
                    $statistics[
                        'review_identities'
                    ],
                ],
                [
                    'Potential redundant legacy records',
                    $statistics[
                        'potential_redundant_records'
                    ],
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Name Filter
        |--------------------------------------------------------------------------
        */

        $nameFilter = $this->option(
            'name'
        );

        if (
            is_string($nameFilter)
            && trim($nameFilter) !== ''
        ) {
            $needle =
                $resolver->normalizeCompanyName(
                    $nameFilter
                );

            $plans = $plans
                ->filter(
                    fn (
                        array $plan,
                        string $normalizedName
                    ): bool =>
                        str_contains(
                            $normalizedName,
                            $needle
                        )
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate Filter
        |--------------------------------------------------------------------------
        */

        if (
            $this->option('duplicates')
        ) {
            $plans = $plans
                ->filter(
                    fn (array $plan): bool =>
                        $plan['source_count'] > 1
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Review Filter
        |--------------------------------------------------------------------------
        */

        if (
            $this->option('review')
        ) {
            $plans = $plans
                ->filter(
                    fn (array $plan): bool =>
                        $plan['identity_status']
                        === 'REVIEW'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Display Limit
        |--------------------------------------------------------------------------
        */

        $limit = max(
            1,
            (int) $this->option('limit')
        );

        $plans = $plans
            ->take($limit);

        if (
            $plans->isEmpty()
        ) {
            $this->newLine();

            $this->warn(
                'No company identities matched the requested criteria.'
            );

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | Display Plans
        |--------------------------------------------------------------------------
        */

        foreach (
            $plans as $plan
        ) {
            $this->displayIdentityPlan(
                $plan
            );
        }

        $this->newLine();

        $this->info(
            'Planning complete. No database records were changed.'
        );

        return self::SUCCESS;
    }

    /**
     * Display one identity plan.
     */
    private function displayIdentityPlan(
        array $plan
    ): void {
        $this->newLine();

        $this->info(
            'Identity: '
            . $plan['identity']
        );

        $this->line(
            sprintf(
                'Status: %s | Identity status: %s',
                $plan['status'],
                $plan['identity_status']
            )
        );

        $this->line(
            'Source records: '
            . $plan['source_count']
        );

        $this->line(
            'Source company IDs: '
            . $plan['source_ids']->implode(', ')
        );

        /*
        |--------------------------------------------------------------------------
        | Source IDs
        |--------------------------------------------------------------------------
        */

        $rows = [];

        foreach (
            $plan['source_ids']
            as $sourceId
        ) {
            $rows[] = [
                $sourceId,
            ];
        }

        if (
            $rows !== []
        ) {
            $this->table(
                [
                    'Legacy Company ID',
                ],
                $rows
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Legacy Names
        |--------------------------------------------------------------------------
        */

        $this->line(
            'Legacy names: '
            . (
                $plan['source_names']->isNotEmpty()
                    ? $plan['source_names']->implode(
                        ' | '
                    )
                    : '[none]'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Legacy Sectors
        |--------------------------------------------------------------------------
        */

        $this->line(
            'Legacy sectors: '
            . (
                $plan['legacy_sectors']->isNotEmpty()
                    ? $plan['legacy_sectors']->implode(
                        ', '
                    )
                    : '[none]'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Capability Union
        |--------------------------------------------------------------------------
        */

        $capabilityUnion =
            $plan['capability_union'];

        $this->line(
            'Capability union: '
            . (
                $capabilityUnion->isNotEmpty()
                    ? $capabilityUnion->implode(
                        ', '
                    )
                    : '[none]'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Country Evidence
        |--------------------------------------------------------------------------
        */

        $this->line(
            'Country evidence: '
            . (
                $plan['countries']->isNotEmpty()
                    ? $plan['countries']->implode(
                        ', '
                    )
                    : '[unknown]'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Review Warning
        |--------------------------------------------------------------------------
        */

        if (
            $plan['identity_status']
            === 'REVIEW'
        ) {
            $this->warn(
                'Identity requires review before canonicalization.'
            );
        }
    }
}