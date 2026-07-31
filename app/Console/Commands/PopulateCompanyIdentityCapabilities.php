<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CompanyIdentity;
use App\Models\CompanyIdentityCapability;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PopulateCompanyIdentityCapabilities extends Command
{
    /**
     * Populate canonical company capabilities from legacy capability evidence.
     *
     * Default mode is READ ONLY.
     *
     * Examples:
     *
     * php artisan companies:populate-identity-capabilities
     *
     * php artisan companies:populate-identity-capabilities --name=KAHATEX
     *
     * php artisan companies:populate-identity-capabilities --limit=20
     *
     * php artisan companies:populate-identity-capabilities --commit
     */
    protected $signature =
        'companies:populate-identity-capabilities
        {--commit : Write canonical capabilities to the database}
        {--name= : Filter by canonical or normalized company name}
        {--limit=20 : Maximum identities displayed in the output}';

    protected $description =
        'Aggregate canonical company capabilities from legacy company capability evidence';

    /**
     * Execute the command.
     */
    public function handle(): int
    {
        $commit = (bool) $this->option('commit');

        $name = trim(
            (string) ($this->option('name') ?? '')
        );

        $limit = max(
            1,
            (int) $this->option('limit')
        );

        $this->newLine();

        $this->info(
            'DIGESTEX Company Identity Capability Aggregator'
        );

        $this->line(
            'Mode: ' .
            ($commit ? 'COMMIT' : 'READ ONLY')
        );

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        |
        | Only READY identities are eligible.
        |
        | REVIEW identities were intentionally excluded during canonical
        | identity population and should not participate here.
        |
        */

        $query = CompanyIdentity::query()
            ->where(
                'identity_status',
                'READY'
            )
            ->orderBy('id');

        if ($name !== '') {
            $query->where(function ($q) use ($name) {
                $q->where(
                    'canonical_name',
                    'like',
                    '%' . $name . '%'
                )->orWhere(
                    'normalized_name',
                    'like',
                    '%' . $name . '%'
                );
            });
        }

        $totalIdentities = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'identities_processed' => 0,
            'identities_with_capabilities' => 0,
            'identities_without_capabilities' => 0,
            'capabilities_discovered' => 0,
            'capabilities_created' => 0,
            'existing_capabilities_retained' => 0,
        ];

        $displayed = 0;

        /*
        |--------------------------------------------------------------------------
        | Process Identities
        |--------------------------------------------------------------------------
        |
        | chunkById() prevents loading the entire canonical identity dataset
        | into memory.
        |
        */

        $query->chunkById(
            200,
            function (Collection $identities) use (
                &$stats,
                &$displayed,
                $limit,
                $commit
            ) {
                foreach ($identities as $identity) {
                    $stats['identities_processed']++;

                    $capabilities =
                        $this->resolveCapabilities(
                            (int) $identity->id
                        );

                    if ($capabilities->isEmpty()) {
                        $stats[
                            'identities_without_capabilities'
                        ]++;

                        continue;
                    }

                    $stats[
                        'identities_with_capabilities'
                    ]++;

                    $stats[
                        'capabilities_discovered'
                    ] += $capabilities->count();

                    /*
                    |--------------------------------------------------------------------------
                    | Display Preview
                    |--------------------------------------------------------------------------
                    */

                    if ($displayed < $limit) {
                        $this->displayIdentity(
                            $identity,
                            $capabilities
                        );

                        $displayed++;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | READ ONLY
                    |--------------------------------------------------------------------------
                    */

                    if (!$commit) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | COMMIT
                    |--------------------------------------------------------------------------
                    |
                    | updateOrCreate() makes the command idempotent.
                    |
                    | Existing canonical capabilities are retained rather than
                    | duplicated.
                    |
                    */

                    foreach ($capabilities as $capability) {
                        $existing =
                            CompanyIdentityCapability::query()
                                ->where(
                                    'company_identity_id',
                                    $identity->id
                                )
                                ->where(
                                    'capability',
                                    $capability
                                )
                                ->first();

                        if ($existing) {
                            $stats[
                                'existing_capabilities_retained'
                            ]++;

                            continue;
                        }

                        CompanyIdentityCapability::create([
                            'company_identity_id' =>
                                $identity->id,

                            'capability' =>
                                $capability,

                            'source' =>
                                'legacy_directory',

                            'is_verified' =>
                                false,

                            'verified_at' =>
                                null,
                        ]);

                        $stats[
                            'capabilities_created'
                        ]++;
                    }
                }
            },
            'id'
        );

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->table(
            [
                'Metric',
                'Total',
            ],
            [
                [
                    'READY identities selected',
                    $totalIdentities,
                ],
                [
                    'Identities processed',
                    $stats[
                        'identities_processed'
                    ],
                ],
                [
                    'Identities with capability evidence',
                    $stats[
                        'identities_with_capabilities'
                    ],
                ],
                [
                    'Identities without capability evidence',
                    $stats[
                        'identities_without_capabilities'
                    ],
                ],
                [
                    'Canonical capabilities discovered',
                    $stats[
                        'capabilities_discovered'
                    ],
                ],
                [
                    'Capabilities created',
                    $commit
                        ? $stats[
                            'capabilities_created'
                        ]
                        : 0,
                ],
                [
                    'Existing capabilities retained',
                    $commit
                        ? $stats[
                            'existing_capabilities_retained'
                        ]
                        : 0,
                ],
            ]
        );

        $this->newLine();

        if (!$commit) {
            $this->warn(
                'READ ONLY mode. No database records were changed.'
            );

            $this->line(
                'Run again with --commit only after reviewing the results.'
            );

            return self::SUCCESS;
        }

        $this->info(
            'Canonical capability population complete.'
        );

        $this->line(
            'Legacy company records and legacy capabilities were not modified or deleted.'
        );

        return self::SUCCESS;
    }

    /**
     * Resolve the union of capabilities belonging to all legacy
     * company records mapped to one canonical company identity.
     */
    private function resolveCapabilities(
        int $identityId
    ): Collection {
        return DB::table(
            'company_identity_sources as cis'
        )
            ->join(
                'company_capabilities as cc',
                'cc.company_id',
                '=',
                'cis.company_id'
            )
            ->where(
                'cis.company_identity_id',
                $identityId
            )
            ->whereNotNull(
                'cc.capability'
            )
            ->where(
                'cc.capability',
                '<>',
                ''
            )
            ->pluck(
                'cc.capability'
            )
            ->map(
                fn ($capability) =>
                    trim((string) $capability)
            )
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Display one canonical identity and its capability union.
     */
    private function displayIdentity(
        CompanyIdentity $identity,
        Collection $capabilities
    ): void {
        $sourceIds = DB::table(
            'company_identity_sources'
        )
            ->where(
                'company_identity_id',
                $identity->id
            )
            ->orderBy(
                'company_id'
            )
            ->pluck(
                'company_id'
            );

        $this->line(
            'Identity: ' .
            $identity->canonical_name
        );

        $this->line(
            'Identity status: ' .
            $identity->identity_status
        );

        $this->line(
            'Source records: ' .
            $sourceIds->count()
        );

        $this->line(
            'Source company IDs: ' .
            $sourceIds->implode(', ')
        );

        $this->line(
            'Capability union: ' .
            $capabilities->implode(', ')
        );

        $this->line(
            'Capability count: ' .
            $capabilities->count()
        );

        $this->newLine();
    }
}