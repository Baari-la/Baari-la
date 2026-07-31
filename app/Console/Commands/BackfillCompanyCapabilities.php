<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyCapability;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Legacy Company Capability Backfill
 * ==========================================================================
 *
 * Converts legacy company sector classifications into canonical DIGESTEX
 * business capabilities.
 *
 * IMPORTANT:
 *
 * - Does NOT modify companies
 * - Does NOT delete duplicate companies
 * - Does NOT mark legacy evidence as verified
 * - Does NOT infer OTHER TEXTILE PRODUCT automatically
 * - Can safely be executed multiple times
 *
 * Usage:
 *
 * php artisan companies:backfill-capabilities --dry-run
 * php artisan companies:backfill-capabilities
 *
 * Version:
 * 1.0
 */
class BackfillCompanyCapabilities extends Command
{
    /**
     * Command signature.
     */
    protected $signature = 'companies:backfill-capabilities
                            {--dry-run : Preview changes without writing to database}';

    /**
     * Command description.
     */
    protected $description =
        'Backfill canonical company capabilities from legacy directory sectors';

    /**
     * Legacy sector → canonical DIGESTEX capabilities.
     *
     * Conservative mapping only.
     *
     * OTHER TEXTILE PRODUCT, BATIK, and null are intentionally
     * excluded because they require additional classification.
     */
    private const SECTOR_MAP = [

        'FIBER' => [
            'fiber_manufacturer',
        ],

        'SPINNING' => [
            'yarn_spinner',
        ],

        'WEAVING' => [
            'weaving_mill',
        ],

        'KNITTING/EMBROIDERY' => [
            'knitting_mill',
        ],

        'DYEING/PRINTING/FINISHING' => [
            'dyeing_finishing_mill',
            'printing_mill',
        ],

        'GARMENT' => [
            'garment_manufacturer',
        ],

        'TRADING' => [
            'trading_company',
        ],

        'TESTING AND CERTIFICATION' => [
            'testing_laboratory',
            'certification_body',
        ],

        'Testing Laboratory' => [
            'testing_laboratory',
        ],
    ];

    /**
     * Execute command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->newLine();

        $this->info(
            'DIGESTEX Company Capability Backfill'
        );

        $this->line(
            'Mode: ' . ($dryRun ? 'DRY RUN' : 'WRITE')
        );

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Load Canonical Business Roles
        |--------------------------------------------------------------------------
        */

        $canonicalRoles = $this->canonicalRoles();

        if ($canonicalRoles->isEmpty()) {
            $this->error(
                'No canonical business roles found in masterdata.Business.business_roles.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Mapping Before Processing
        |--------------------------------------------------------------------------
        */

        $invalidCapabilities = collect(self::SECTOR_MAP)
            ->flatten()
            ->unique()
            ->reject(
                fn (string $capability): bool =>
                    $canonicalRoles->contains($capability)
            )
            ->values();

        if ($invalidCapabilities->isNotEmpty()) {

            $this->error(
                'Backfill aborted: mapping contains non-canonical capabilities.'
            );

            foreach ($invalidCapabilities as $capability) {
                $this->line(
                    " - {$capability}"
                );
            }

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Load Companies
        |--------------------------------------------------------------------------
        */

        $companies = Company::query()
            ->select([
                'id',
                'nama_perusahaan',
                'sektor',
            ])
            ->orderBy('id')
            ->get();

        $companyCount = $companies->count();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $mappedCompanies = 0;
        $skippedCompanies = 0;
        $plannedCapabilities = 0;
        $createdCapabilities = 0;
        $existingCapabilities = 0;

        $skippedBySector = [];

        /*
        |--------------------------------------------------------------------------
        | Progress
        |--------------------------------------------------------------------------
        */

        $progress = $this->output->createProgressBar(
            $companyCount
        );

        $progress->start();

        foreach ($companies as $company) {

            $sector = $this->normalizeSector(
                $company->sektor
            );

            $capabilities = $this->capabilitiesForSector(
                $sector
            );

            /*
            |--------------------------------------------------------------------------
            | Unmapped / Intentionally Skipped
            |--------------------------------------------------------------------------
            */

            if ($capabilities === []) {

                $skippedCompanies++;

                $sectorLabel =
                    $sector !== ''
                        ? $sector
                        : '[NULL / EMPTY]';

                $skippedBySector[$sectorLabel] =
                    ($skippedBySector[$sectorLabel] ?? 0) + 1;

                $progress->advance();

                continue;
            }

            $mappedCompanies++;

            /*
            |--------------------------------------------------------------------------
            | Capability Backfill
            |--------------------------------------------------------------------------
            */

            foreach ($capabilities as $capability) {

                $plannedCapabilities++;

                $exists = CompanyCapability::query()
                    ->where(
                        'company_id',
                        $company->id
                    )
                    ->where(
                        'capability',
                        $capability
                    )
                    ->exists();

                if ($exists) {

                    $existingCapabilities++;

                    continue;
                }

                if ($dryRun) {
                    continue;
                }

                CompanyCapability::create([
                    'company_id' =>
                        $company->id,

                    'capability' =>
                        $capability,

                    'is_primary' =>
                        false,

                    'source' =>
                        'legacy_directory',

                    'is_verified' =>
                        false,

                    'verified_at' =>
                        null,
                ]);

                $createdCapabilities++;
            }

            $progress->advance();
        }

        $progress->finish();

        $this->newLine(2);

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $this->table(
            ['Metric', 'Total'],
            [
                [
                    'Company records scanned',
                    $companyCount,
                ],
                [
                    'Mapped company records',
                    $mappedCompanies,
                ],
                [
                    'Skipped company records',
                    $skippedCompanies,
                ],
                [
                    'Capability assignments planned',
                    $plannedCapabilities,
                ],
                [
                    'Existing capability assignments',
                    $existingCapabilities,
                ],
                [
                    $dryRun
                        ? 'Capability records that would be created'
                        : 'Capability records created',
                    $dryRun
                        ? $plannedCapabilities - $existingCapabilities
                        : $createdCapabilities,
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Skipped Sector Report
        |--------------------------------------------------------------------------
        */

        if ($skippedBySector !== []) {

            ksort($skippedBySector);

            $this->newLine();

            $this->warn(
                'Skipped / unmapped legacy sectors:'
            );

            $rows = [];

            foreach ($skippedBySector as $sector => $count) {
                $rows[] = [
                    $sector,
                    $count,
                ];
            }

            $this->table(
                ['Legacy Sector', 'Records'],
                $rows
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Final Status
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        if ($dryRun) {

            $this->info(
                'DRY RUN complete. No database records were changed.'
            );

        } else {

            $this->info(
                'Company capability backfill completed successfully.'
            );

        }

        return self::SUCCESS;
    }

    /**
     * Get capabilities for normalized legacy sector.
     */
    private function capabilitiesForSector(
        string $sector
    ): array {

        /*
         * Preserve support for the mixed-case legacy value
         * "Testing Laboratory" after normalization.
         */
        if ($sector === 'TESTING LABORATORY') {
            return [
                'testing_laboratory',
            ];
        }

        return self::SECTOR_MAP[$sector] ?? [];
    }

    /**
     * Normalize legacy sector value.
     */
    private function normalizeSector(
        ?string $sector
    ): string {

        if ($sector === null) {
            return '';
        }

        return strtoupper(
            trim($sector)
        );
    }

    /**
     * Load canonical business role IDs from DIGESTEX Master Data Framework.
     */
    private function canonicalRoles(): Collection
    {
        return collect(
            config(
                'masterdata.Business.business_roles',
                []
            )
        )
            ->pluck('id')
            ->filter(
                fn ($role): bool =>
                    is_string($role)
                    && trim($role) !== ''
            )
            ->map(
                fn (string $role): string =>
                    trim($role)
            )
            ->unique()
            ->values();
    }
}