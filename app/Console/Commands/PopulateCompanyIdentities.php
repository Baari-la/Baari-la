<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyIdentity;
use App\Models\CompanyIdentitySource;
use App\Services\Company\Identity\CompanyIdentityResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class PopulateCompanyIdentities extends Command
{
    protected $signature = 'companies:populate-identities
                            {--commit : Write READY identities and source mappings to database}
                            {--name= : Filter identities by normalized company name}
                            {--limit= : Limit number of identity candidates}';

    protected $description =
        'Populate canonical company identities from legacy company records';

    public function handle(
        CompanyIdentityResolver $resolver
    ): int {
        $commit = (bool) $this->option('commit');
        $nameFilter = trim((string) $this->option('name'));
        $limit = $this->parseLimit();

        $this->newLine();
        $this->info('DIGESTEX Company Identity Populator');
        $this->line(
            'Mode: ' . ($commit ? 'COMMIT' : 'DRY RUN')
        );
        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Load Legacy Companies
        |--------------------------------------------------------------------------
        |
        | Resolver expects actual Company models because recordFromCompany()
        | also reads the capabilities relationship.
        |
        */

        $companies = $this->loadLegacyCompanies();

        if ($companies->isEmpty()) {
            $this->warn('No legacy company records found.');

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Canonical Identity Candidates
        |--------------------------------------------------------------------------
        */

        $identities = $resolver
            ->resolve($companies)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Optional Name Filter
        |--------------------------------------------------------------------------
        */

        if ($nameFilter !== '') {
            $needle = $resolver->normalizeCompanyName(
                $nameFilter
            );

            $identities = $identities
                ->filter(
                    fn (array $identity): bool =>
                        str_contains(
                            (string) (
                                $identity['identity'] ?? ''
                            ),
                            $needle
                        )
                )
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Optional Limit
        |--------------------------------------------------------------------------
        */

        if ($limit !== null) {
            $identities = $identities
                ->take($limit)
                ->values();
        }

        if ($identities->isEmpty()) {
            $this->warn('No matching identities found.');

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | READY / REVIEW
        |--------------------------------------------------------------------------
        */

        $ready = $identities
            ->filter(
                fn (array $identity): bool =>
                    ($identity['identity_status'] ?? null)
                    === 'READY'
            )
            ->values();

        $review = $identities
            ->reject(
                fn (array $identity): bool =>
                    ($identity['identity_status'] ?? null)
                    === 'READY'
            )
            ->values();

        $sourceMappings = $ready->sum(
            fn (array $identity): int =>
                count(
                    $identity['source_ids'] ?? []
                )
        );

        /*
        |--------------------------------------------------------------------------
        | Planning Summary
        |--------------------------------------------------------------------------
        */

        $this->table(
            ['Metric', 'Total'],
            [
                [
                    'Legacy company records loaded',
                    $companies->count(),
                ],
                [
                    'Identity candidates selected',
                    $identities->count(),
                ],
                [
                    'READY identities',
                    $ready->count(),
                ],
                [
                    'REVIEW identities skipped',
                    $review->count(),
                ],
                [
                    'Source mappings planned',
                    $sourceMappings,
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Display Candidates
        |--------------------------------------------------------------------------
        */

        foreach ($identities as $identity) {
            $this->displayIdentity($identity);
        }

        /*
        |--------------------------------------------------------------------------
        | DRY RUN
        |--------------------------------------------------------------------------
        */

        if (!$commit) {
            $this->newLine();

            $this->info(
                'Dry run complete. No database records were changed.'
            );

            $this->line(
                'Run with --commit only after reviewing this plan.'
            );

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        if ($ready->isEmpty()) {
            $this->warn(
                'No READY identities available for commit.'
            );

            return self::SUCCESS;
        }

        $this->newLine();
        $this->warn('COMMIT mode enabled.');

        $this->line(
            'Legacy companies will NOT be modified or deleted.'
        );

        $createdIdentities = 0;
        $updatedIdentities = 0;
        $createdSources = 0;
        $existingSources = 0;

        try {
            DB::transaction(
                function () use (
                    $ready,
                    &$createdIdentities,
                    &$updatedIdentities,
                    &$createdSources,
                    &$existingSources
                ): void {
                    foreach ($ready as $identityData) {
                        $normalizedName = trim(
                            (string) (
                                $identityData['identity'] ?? ''
                            )
                        );

                        if ($normalizedName === '') {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Country Evidence
                        |--------------------------------------------------------------------------
                        |
                        | Resolver currently returns normalized country evidence
                        | as one collection/array.
                        |
                        | READY guarantees that there is not more than one
                        | country evidence value.
                        |
                        */

                        $countries = collect(
                            $identityData['countries'] ?? []
                        )
                            ->filter()
                            ->values();

                        $countryName = $countries->first();

                        /*
                        |--------------------------------------------------------------------------
                        | Canonical Identity
                        |--------------------------------------------------------------------------
                        */

                        $identity = CompanyIdentity::query()
                            ->where(
                                'normalized_name',
                                $normalizedName
                            )
                            ->first();

                        if ($identity === null) {
                            $identity = CompanyIdentity::create([
                                'canonical_name' =>
                                    $normalizedName,

                                'normalized_name' =>
                                    $normalizedName,

                                'country_code' =>
                                    null,

                                'country_name' =>
                                    $countryName,

                                'identity_status' =>
                                    'READY',

                                'verification_status' =>
                                    'unverified',

                                'created_from' =>
                                    'legacy_directory',
                            ]);

                            $createdIdentities++;
                        } else {
                            /*
                             * Do not reset verification_status or verified_at.
                             */

                            $identity->fill([
                                'canonical_name' =>
                                    $normalizedName,

                                'country_name' =>
                                    $countryName
                                    ?? $identity->country_name,

                                'identity_status' =>
                                    'READY',
                            ]);

                            if ($identity->isDirty()) {
                                $identity->save();

                                $updatedIdentities++;
                            }
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Legacy Source Mapping
                        |--------------------------------------------------------------------------
                        */

                        foreach (
                            $identityData['source_ids'] ?? []
                            as $companyId
                        ) {
                            $companyId = (int) $companyId;

                            if ($companyId <= 0) {
                                continue;
                            }

                            $existing =
                                CompanyIdentitySource::query()
                                    ->where(
                                        'company_id',
                                        $companyId
                                    )
                                    ->first();

                            if ($existing !== null) {
                                /*
                                 * Same mapping = safe idempotent rerun.
                                 */

                                if (
                                    (int) $existing->company_identity_id
                                    === (int) $identity->id
                                ) {
                                    $existingSources++;

                                    continue;
                                }

                                /*
                                 * Never silently move legacy evidence between
                                 * canonical identities.
                                 */

                                throw new RuntimeException(
                                    sprintf(
                                        'Legacy company ID %d is already mapped to identity ID %d; attempted identity ID %d.',
                                        $companyId,
                                        $existing->company_identity_id,
                                        $identity->id
                                    )
                                );
                            }

                            CompanyIdentitySource::create([
                                'company_identity_id' =>
                                    $identity->id,

                                'company_id' =>
                                    $companyId,

                                'source_type' =>
                                    'legacy_directory',
                            ]);

                            $createdSources++;
                        }
                    }
                }
            );
        } catch (Throwable $e) {
            $this->newLine();

            $this->error(
                'Population failed. Transaction rolled back.'
            );

            $this->error(
                $e->getMessage()
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Company identity population complete.'
        );

        $this->table(
            ['Result', 'Total'],
            [
                [
                    'Identities created',
                    $createdIdentities,
                ],
                [
                    'Identities updated',
                    $updatedIdentities,
                ],
                [
                    'Source mappings created',
                    $createdSources,
                ],
                [
                    'Existing mappings retained',
                    $existingSources,
                ],
                [
                    'REVIEW identities skipped',
                    $review->count(),
                ],
            ]
        );

        $this->line(
            'Legacy companies were not modified or deleted.'
        );

        return self::SUCCESS;
    }

    /**
     * Resolver requires Company models and capabilities.
     */
    private function loadLegacyCompanies(): Collection
    {
        return Company::query()
            ->with('capabilities')
            ->orderBy('id')
            ->get();
    }

    private function parseLimit(): ?int
    {
        $value = $this->option('limit');

        if ($value === null || $value === '') {
            return null;
        }

        $limit = (int) $value;

        return $limit > 0
            ? $limit
            : null;
    }

    private function displayIdentity(
        array $identity
    ): void {
        $this->newLine();

        $name = (string) (
            $identity['identity'] ?? 'UNKNOWN'
        );

        $status = (string) (
            $identity['identity_status'] ?? 'REVIEW'
        );

        $sourceIds = collect(
            $identity['source_ids'] ?? []
        )->values();

        $capabilities = collect(
            $identity['capability_union'] ?? []
        )->values();

        $sectors = collect(
            $identity['legacy_sectors'] ?? []
        )->values();

        $this->line(
            'Identity: ' . $name
        );

        $this->line(
            'Identity status: ' . $status
        );

        $this->line(
            'Source records: ' .
            $sourceIds->count()
        );

        $this->line(
            'Source company IDs: ' .
            (
                $sourceIds->isNotEmpty()
                    ? $sourceIds->implode(', ')
                    : '-'
            )
        );

        $this->line(
            'Legacy sectors: ' .
            (
                $sectors->isNotEmpty()
                    ? $sectors->implode(', ')
                    : '-'
            )
        );

        $this->line(
            'Capability union: ' .
            (
                $capabilities->isNotEmpty()
                    ? $capabilities->implode(', ')
                    : '-'
            )
        );

        if ($status !== 'READY') {
            $this->warn(
                'Skipped: identity requires review.'
            );
        }
    }
}