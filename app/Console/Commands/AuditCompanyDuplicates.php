<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Company Identity Duplicate Audit
 * ==========================================================================
 *
 * Identifies potential duplicate company identities in legacy directory data.
 *
 * READ ONLY:
 *
 * - Does NOT update companies
 * - Does NOT delete companies
 * - Does NOT move relationships
 * - Does NOT modify capabilities
 *
 * Version:
 * 1.0
 */
class AuditCompanyDuplicates extends Command
{
    /**
     * Command signature.
     */
    protected $signature = 'companies:audit-duplicates
                            {--limit=20 : Maximum duplicate groups to display}
                            {--name= : Show duplicate groups matching a company name}';

    /**
     * Command description.
     */
    protected $description =
        'Audit potential duplicate company identities without modifying data';

    /**
     * Execute command.
     */
    public function handle(): int
    {
        $this->newLine();

        $this->info('DIGESTEX Company Identity Duplicate Audit');
        $this->line('Mode: READ ONLY');

        $this->newLine();

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
                'country_code',
                'country_name',
                'alamat_lengkap',
                'city',
                'telepon',
                'email_web',
                'produk',
                'pasar_ekspor',
                'membership_type',
                'status_verifikasi',
                'data_source',
                'last_verified_at',
                'updated_at',
            ])
            ->with([
                'capabilities:id,company_id,capability',
            ])
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Normalize Identities
        |--------------------------------------------------------------------------
        */

        $normalized = $companies
            ->map(function (Company $company): array {

                return [
                    'id' => $company->id,

                    'name' =>
                        $company->nama_perusahaan,

                    'normalized_name' =>
                        $this->normalizeCompanyName(
                            $company->nama_perusahaan
                        ),

                    'sector' =>
                        $company->sektor,

                    'country' =>
                        $company->country_name
                        ?? $company->country_code,

                    'address' =>
                        $company->alamat_lengkap,

                    'city' =>
                        $company->city,

                    'phone' =>
                        $company->telepon,

                    'email_web' =>
                        $company->email_web,

                    'product' =>
                        $company->produk,

                    'export_market' =>
                        $company->pasar_ekspor,

                    'membership' =>
                        $company->membership_type,

                    'verification_status' =>
                        $company->status_verifikasi,

                    'data_source' =>
                        $company->data_source,

                    'last_verified_at' =>
                        $company->last_verified_at,

                    'updated_at' =>
                        $company->updated_at,

                    'capabilities' =>
                        $company->capabilities
                            ->pluck('capability')
                            ->filter()
                            ->unique()
                            ->values()
                            ->all(),
                ];
            })
            ->filter(
                fn (array $company): bool =>
                    $company['normalized_name'] !== ''
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Duplicate Groups
        |--------------------------------------------------------------------------
        */

        $duplicates = $normalized
            ->groupBy('normalized_name')
            ->filter(
                fn (Collection $group): bool =>
                    $group->count() > 1
            )
            ->sortByDesc(
                fn (Collection $group): int =>
                    $group->count()
            );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $recordsInsideDuplicateGroups = $duplicates
            ->sum(
                fn (Collection $group): int =>
                    $group->count()
            );

        $potentialRedundantRecords = $duplicates
            ->sum(
                fn (Collection $group): int =>
                    max(0, $group->count() - 1)
            );

        $this->table(
            ['Metric', 'Total'],
            [
                [
                    'Company records',
                    $companies->count(),
                ],
                [
                    'Normalized identities',
                    $normalized
                        ->pluck('normalized_name')
                        ->unique()
                        ->count(),
                ],
                [
                    'Duplicate identity groups',
                    $duplicates->count(),
                ],
                [
                    'Records inside duplicate groups',
                    $recordsInsideDuplicateGroups,
                ],
                [
                    'Potential redundant records',
                    $potentialRedundantRecords,
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Optional Name Filter
        |--------------------------------------------------------------------------
        */

        $nameFilter = $this->option('name');

        if (
            is_string($nameFilter)
            && trim($nameFilter) !== ''
        ) {
            $needle = $this->normalizeCompanyName(
                $nameFilter
            );

            $duplicates = $duplicates
                ->filter(
                    fn (Collection $group, string $normalizedName): bool =>
                        str_contains(
                            $normalizedName,
                            $needle
                        )
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Limit Output
        |--------------------------------------------------------------------------
        */

        $limit = max(
            1,
            (int) $this->option('limit')
        );

        $groupsToDisplay = $duplicates
            ->take($limit);

        if ($groupsToDisplay->isEmpty()) {

            $this->newLine();

            $this->warn(
                'No duplicate groups matched the requested criteria.'
            );

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | Display Duplicate Groups
        |--------------------------------------------------------------------------
        */

        foreach ($groupsToDisplay as $normalizedName => $group) {

            $this->newLine();

            $this->info(
                "Identity: {$normalizedName}"
            );

            $rows = $group
                ->map(function (array $company): array {

                    return [
                        $company['id'],
                        $company['name'],
                        $company['sector'] ?? '-',
                        implode(
                            ', ',
                            $company['capabilities']
                        ),
                        $company['country'] ?? '-',
                    ];
                })
                ->values()
                ->all();

            $this->table(
                [
                    'ID',
                    'Company',
                    'Legacy Sector',
                    'Capabilities',
                    'Country',
                ],
                $rows
            );

            /*
            |--------------------------------------------------------------------------
            | Capability Union
            |--------------------------------------------------------------------------
            */

            $capabilityUnion = $group
                ->flatMap(
                    fn (array $company): array =>
                        $company['capabilities']
                )
                ->filter()
                ->unique()
                ->sort()
                ->values();

            $this->line(
                'Capability union: '
                . (
                    $capabilityUnion->isNotEmpty()
                        ? $capabilityUnion->implode(', ')
                        : '[none]'
                )
            );

            /*
            |--------------------------------------------------------------------------
            | Data Completeness Preview
            |--------------------------------------------------------------------------
            */

            $completeness = $group
                ->map(function (array $company): array {

                    return [
                        'ID' =>
                            $company['id'],

                        'Name' =>
                            $company['name'],

                        'Completeness' =>
                            $this->completenessScore(
                                $company
                            ),
                    ];
                })
                ->sortByDesc('Completeness')
                ->values()
                ->all();

            $this->table(
                [
                    'ID',
                    'Name',
                    'Completeness',
                ],
                $completeness
            );
        }

        $this->newLine();

        $this->info(
            'Audit complete. No database records were changed.'
        );

        return self::SUCCESS;
    }

    /**
     * Normalize company identity for duplicate detection.
     *
     * This is intentionally conservative.
     */
    private function normalizeCompanyName(
        ?string $name
    ): string {

        if ($name === null) {
            return '';
        }

        $name = Str::upper(
            trim($name)
        );

        /*
         * Normalize punctuation and separators.
         */
        $name = preg_replace(
            '/[.,;:\/\\\\()\[\]{}]+/u',
            ' ',
            $name
        ) ?? $name;

        /*
         * Normalize common Indonesian legal entity markers.
         *
         * PT is removed only as an identity suffix/prefix token.
         * We are NOT performing fuzzy company matching here.
         */
        $name = preg_replace(
            '/\bPT\b/u',
            ' ',
            $name
        ) ?? $name;

        /*
         * Normalize whitespace.
         */
        $name = preg_replace(
            '/\s+/u',
            ' ',
            $name
        ) ?? $name;

        return trim($name);
    }

    /**
     * Simple completeness score for master-candidate inspection.
     *
     * This score does NOT automatically choose the consolidation master.
     */
    private function completenessScore(
        array $company
    ): int {

        $fields = [
            'address',
            'city',
            'phone',
            'email_web',
            'product',
            'export_market',
            'membership',
            'verification_status',
            'data_source',
        ];

        $score = 0;

        foreach ($fields as $field) {

            $value = $company[$field] ?? null;

            if (
                $value !== null
                && trim((string) $value) !== ''
            ) {
                $score++;
            }
        }

        if (
            ! empty(
                $company['capabilities']
            )
        ) {
            $score++;
        }

        if (
            $company['last_verified_at']
            ?? null
        ) {
            $score += 2;
        }

        return $score;
    }
}