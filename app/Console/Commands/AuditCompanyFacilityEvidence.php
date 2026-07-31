<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Company Facility Evidence Auditor
 * ==========================================================================
 *
 * READ-ONLY auditor for physical-location evidence contained in legacy
 * directory records.
 *
 * Responsibilities:
 *
 * - Resolve duplicate legacy records into one normalized company identity
 * - Read existing company_locations evidence
 * - Preserve source company IDs and legacy sectors
 * - Detect geographic/place evidence from legacy addresses
 * - Group recurring location evidence
 * - Show capabilities associated with source records
 * - Flag suspicious / weak address data
 *
 * IMPORTANT:
 *
 * This command DOES NOT:
 *
 * - create facilities
 * - update company_locations
 * - delete duplicate companies
 * - classify a location as factory/head office automatically
 * - assign capabilities to facilities
 *
 * Version:
 * 1.0
 */
class AuditCompanyFacilityEvidence extends Command
{
    protected $signature = 'companies:audit-facility-evidence
                            {--name= : Filter by normalized company name}
                            {--limit=20 : Maximum company identities to display}';

    protected $description =
        'Audit legacy company records for possible physical facility evidence';

    /**
     * Generic values that are not useful as company identities.
     */
    private const GENERIC_IDENTITIES = [
        'WEAVING',
        'SPINNING',
        'KNITTING',
        'GARMENT',
        'TEXTILE',
        'TRADING',
        'BATIK',
        'FIBER',
        'DYEING',
        'PRINTING',
        'FINISHING',
    ];

    /**
     * Common geographic indicators used only as evidence.
     *
     * These are NOT authoritative facility classifications.
     */
    private const PLACE_HINTS = [
        'JAKARTA',
        'BANDUNG',
        'CIMAHI',
        'SUMEDANG',
        'MAJALAYA',
        'PADALARANG',
        'BATUJAJAR',
        'TANGERANG',
        'BOGOR',
        'PURWAKARTA',
        'BEKASI',
        'KARAWANG',
        'SUBANG',
        'SUKABUMI',
        'SEMARANG',
        'SOLO',
        'SURAKARTA',
        'YOGYAKARTA',
        'SLEMAN',
        'SURABAYA',
        'SIDOARJO',
        'MALANG',
        'PASURUAN',
        'MOJOKERTO',
        'PEKALONGAN',
        'TEGAL',
        'CIREBON',
        'SERANG',
        'CILEGON',
    ];

    public function handle(): int
    {
        $this->newLine();

        $this->info(
            'DIGESTEX Company Facility Evidence Auditor'
        );

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
            ])
            ->with([
                'capabilities:id,company_id,capability',
            ])
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Normalize Company Identity
        |--------------------------------------------------------------------------
        */

        $records = $companies
            ->map(
                fn (Company $company): array =>
                    $this->companyRecord($company)
            )
            ->filter(
                fn (array $record): bool =>
                    $record['normalized_name'] !== ''
            )
            ->values();

        $groups = $records
            ->groupBy('normalized_name');

        /*
        |--------------------------------------------------------------------------
        | Optional Company Filter
        |--------------------------------------------------------------------------
        */

        $name = $this->option('name');

        if (
            is_string($name)
            && trim($name) !== ''
        ) {
            $needle = $this->normalizeCompanyName(
                $name
            );

            $groups = $groups
    ->filter(
        fn (
            Collection $group,
            string $identity
        ): bool =>
            $identity === $needle
    );
        }

        $limit = max(
            1,
            (int) $this->option('limit')
        );

        $groups = $groups->take($limit);

        if ($groups->isEmpty()) {
            $this->warn(
                'No company identities matched the requested criteria.'
            );

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | Display Each Identity
        |--------------------------------------------------------------------------
        */

        foreach (
            $groups
            as $identity => $group
        ) {
            $this->auditIdentity(
                $identity,
                $group
            );
        }

        $this->newLine();

        $this->info(
            'Facility evidence audit complete. No database records were changed.'
        );

        return self::SUCCESS;
    }

    /**
     * Build source company record.
     */
    private function companyRecord(
        Company $company
    ): array {

        return [
            'id' =>
                (int) $company->id,

            'name' =>
                (string) (
                    $company->nama_perusahaan
                    ?? ''
                ),

            'normalized_name' =>
                $this->normalizeCompanyName(
                    $company->nama_perusahaan
                ),

            'sector' =>
                $company->sektor,

            'country' =>
                $company->country_name
                ?? $company->country_code,

            'city' =>
                $company->city,

            'address' =>
                $company->alamat_lengkap,

            'phone' =>
                $company->telepon,

            'email_web' =>
                $company->email_web,

            'capabilities' =>
                $company->capabilities
                    ->pluck('capability')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
        ];
    }

    /**
     * Audit one normalized company identity.
     */
    private function auditIdentity(
        string $identity,
        Collection $group
    ): void {

        $companyIds = $group
            ->pluck('id')
            ->map(
                fn ($id): int => (int) $id
            )
            ->values()
            ->all();

        $locations = DB::table(
            'company_locations'
        )
            ->whereIn(
                'company_id',
                $companyIds
            )
            ->orderBy('company_id')
            ->orderBy('id')
            ->get();

        $this->newLine();

        $this->info(
            'Identity: ' . $identity
        );

        if (
            in_array(
                $identity,
                self::GENERIC_IDENTITIES,
                true
            )
        ) {
            $this->error(
                'Identity warning: generic/non-company identity.'
            );
        }

        $this->line(
            'Legacy company records: '
            . $group->count()
        );

        $this->line(
            'Existing location records: '
            . $locations->count()
        );

        /*
        |--------------------------------------------------------------------------
        | Source Records
        |--------------------------------------------------------------------------
        */

        $sourceRows = $group
            ->map(
                fn (array $record): array => [
                    $record['id'],
                    $record['name'],
                    $record['sector'] ?? '-',
                    implode(
                        ', ',
                        $record['capabilities']
                    ),
                    $record['city'] ?? '-',
                ]
            )
            ->all();

        $this->table(
            [
                'Company ID',
                'Company',
                'Legacy Sector',
                'Capabilities',
                'Legacy City',
            ],
            $sourceRows
        );

        /*
        |--------------------------------------------------------------------------
        | Capability Union
        |--------------------------------------------------------------------------
        */

        $capabilities = $group
            ->flatMap(
                fn (array $record): array =>
                    $record['capabilities']
            )
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $this->line(
            'Company capability union: '
            . (
                $capabilities->isNotEmpty()
                    ? $capabilities->implode(', ')
                    : '[none]'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Existing Location Evidence
        |--------------------------------------------------------------------------
        */

        if ($locations->isEmpty()) {
            $this->warn(
                'No company_locations records found.'
            );

            return;
        }

        $locationRows = $locations
            ->map(function ($location): array {

                return [
                    $location->id,
                    $location->company_id,
                    $location->location_type,
                    $location->city_name ?? '-',
                    $this->shorten(
                        $location->address,
                        90
                    ),
                    $this->shorten(
                        $location->phone,
                        45
                    ),
                ];
            })
            ->all();

        $this->table(
            [
                'Location ID',
                'Source Company',
                'Current Type',
                'Current City',
                'Address Evidence',
                'Phone Evidence',
            ],
            $locationRows
        );

        /*
        |--------------------------------------------------------------------------
        | Geographic Evidence
        |--------------------------------------------------------------------------
        */

        $evidence = $this->buildPlaceEvidence(
            $group,
            $locations
        );

        if ($evidence->isEmpty()) {
            $this->warn(
                'No recognizable geographic evidence detected.'
            );
        } else {
            $this->newLine();

            $this->info(
                'Possible physical location evidence:'
            );

            $evidenceRows = $evidence
                ->map(
                    fn (array $item): array => [
                        $item['place'],
                        $item['source_count'],
                        implode(
                            ', ',
                            $item['company_ids']
                        ),
                        implode(
                            ', ',
                            $item['sectors']
                        ),
                        implode(
                            ', ',
                            $item['capabilities']
                        ),
                        $item['confidence'],
                    ]
                )
                ->all();

            $this->table(
                [
                    'Place Evidence',
                    'Sources',
                    'Company IDs',
                    'Legacy Sectors',
                    'Source Capabilities',
                    'Confidence',
                ],
                $evidenceRows
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Suspicious Address Evidence
        |--------------------------------------------------------------------------
        */

        $suspicious = $locations
            ->filter(
                fn ($location): bool =>
                    $this->looksLikeNonAddress(
                        $location->address
                    )
            );

        if ($suspicious->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'Suspicious location records requiring manual review:'
            );

            $this->table(
                [
                    'Location ID',
                    'Company ID',
                    'Address Value',
                ],
                $suspicious
                    ->map(
                        fn ($location): array => [
                            $location->id,
                            $location->company_id,
                            $this->shorten(
                                $location->address,
                                120
                            ),
                        ]
                    )
                    ->all()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Interpretation
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->line(
            'Interpretation: geographic evidence represents possible '
            . 'physical locations only; facility type is not inferred.'
        );

        $this->line(
            'Capability evidence belongs to the company source record '
            . 'and is not yet assigned to a specific facility.'
        );
    }

    /**
     * Build place evidence from company and location records.
     */
    private function buildPlaceEvidence(
        Collection $group,
        Collection $locations
    ): Collection {

        $sourceByCompanyId = $group
            ->keyBy('id');

        $evidence = collect();

        /*
        |--------------------------------------------------------------------------
        | Evidence from company_locations
        |--------------------------------------------------------------------------
        */

        foreach ($locations as $location) {

            $source = $sourceByCompanyId->get(
                (int) $location->company_id
            );

            $text = implode(
                ' ',
                array_filter([
                    $location->city_name,
                    $location->province_name,
                    $location->address,
                ])
            );

            foreach (
                $this->extractPlaceHints($text)
                as $place
            ) {
                $evidence->push(
                    $this->evidenceItem(
                        $place,
                        (int) $location->company_id,
                        $source
                    )
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence from original company records
        |--------------------------------------------------------------------------
        */

        foreach ($group as $source) {

            $text = implode(
                ' ',
                array_filter([
                    $source['city'],
                    $source['address'],
                ])
            );

            foreach (
                $this->extractPlaceHints($text)
                as $place
            ) {
                $evidence->push(
                    $this->evidenceItem(
                        $place,
                        (int) $source['id'],
                        $source
                    )
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Aggregate Evidence
        |--------------------------------------------------------------------------
        */

        return $evidence
            ->groupBy('place')
            ->map(function (
                Collection $items,
                string $place
            ): array {

                $companyIds = $items
                    ->pluck('company_id')
                    ->unique()
                    ->sort()
                    ->values();

                $sectors = $items
                    ->pluck('sector')
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values();

                $capabilities = $items
                    ->flatMap(
                        fn (array $item): array =>
                            $item['capabilities']
                    )
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values();

                $sourceCount =
                    $companyIds->count();

                return [
                    'place' =>
                        $place,

                    'source_count' =>
                        $sourceCount,

                    'company_ids' =>
                        $companyIds->all(),

                    'sectors' =>
                        $sectors->all(),

                    'capabilities' =>
                        $capabilities->all(),

                    'confidence' =>
                        $this->evidenceConfidence(
                            $sourceCount
                        ),
                ];
            })
            ->sortByDesc(
                fn (array $item): int =>
                    $item['source_count']
            )
            ->values();
    }

    /**
     * Build one evidence item.
     */
    private function evidenceItem(
        string $place,
        int $companyId,
        ?array $source
    ): array {

        return [
            'place' =>
                $place,

            'company_id' =>
                $companyId,

            'sector' =>
                $source['sector']
                ?? null,

            'capabilities' =>
                $source['capabilities']
                ?? [],
        ];
    }

    /**
     * Extract geographic hints from legacy text.
     *
     * This intentionally uses conservative known-place evidence.
     * It does not split an address into facilities.
     */
    private function extractPlaceHints(
        ?string $text
    ): array {

        if (
            $text === null
            || trim($text) === ''
        ) {
            return [];
        }

        $normalized = Str::upper(
            $text
        );

        $found = [];

        foreach (
            self::PLACE_HINTS
            as $place
        ) {
            if (
                str_contains(
                    $normalized,
                    $place
                )
            ) {
                $found[] = $place;
            }
        }

        return array_values(
            array_unique($found)
        );
    }

    /**
     * Evidence confidence.
     *
     * HIGH means repeated across several legacy company records,
     * not that the facility type has been verified.
     */
    private function evidenceConfidence(
        int $sourceCount
    ): string {

        if ($sourceCount >= 3) {
            return 'HIGH';
        }

        if ($sourceCount === 2) {
            return 'MEDIUM';
        }

        return 'LOW';
    }

    /**
     * Detect values that appear more like product descriptions than addresses.
     */
    private function looksLikeNonAddress(
        ?string $address
    ): bool {

        if (
            $address === null
            || trim($address) === ''
        ) {
            return false;
        }

        $value = Str::upper(
            trim($address)
        );

        $productTerms = [
            'FABRIC',
            'FABRICS',
            'YARN',
            'YARNS',
            'SHIRTING',
            'SUITING',
            'GARMENT',
            'TEXTURIZING',
            'DYEING',
            'PRINTING',
            'FINISHING',
            'TEXTILE PRODUCT',
        ];

        $addressTerms = [
            'JL.',
            'JALAN',
            'STREET',
            'ROAD',
            'KAWASAN',
            'DESA',
            'DS.',
            'KEC.',
            'KAB.',
            'KM.',
            'NO.',
        ];

        $hasProductTerm = collect(
            $productTerms
        )->contains(
            fn (string $term): bool =>
                str_contains(
                    $value,
                    $term
                )
        );

        $hasAddressTerm = collect(
            $addressTerms
        )->contains(
            fn (string $term): bool =>
                str_contains(
                    $value,
                    $term
                )
        );

        return $hasProductTerm
            && ! $hasAddressTerm;
    }

    /**
     * Normalize company identity.
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

        $name = preg_replace(
            '/[.,;:\/\\\\()\[\]{}]+/u',
            ' ',
            $name
        ) ?? $name;

        $name = preg_replace(
            '/\bPT\b/u',
            ' ',
            $name
        ) ?? $name;

        $name = preg_replace(
            '/\s+/u',
            ' ',
            $name
        ) ?? $name;

        return trim($name);
    }

    /**
     * Shorten console output.
     */
    private function shorten(
        ?string $value,
        int $length
    ): string {

        if (
            $value === null
            || trim($value) === ''
        ) {
            return '-';
        }

        return Str::limit(
            preg_replace(
                '/\s+/u',
                ' ',
                trim($value)
            ) ?? trim($value),
            $length
        );
    }
}