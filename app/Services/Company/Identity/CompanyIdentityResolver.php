<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Company Identity Resolver
 * ==========================================================================
 *
 * Central service for resolving legacy company records into
 * canonical company identity candidates.
 *
 * Responsibilities:
 *
 * - Normalize legacy company names.
 * - Convert Company models into identity source records.
 * - Group legacy records into company identities.
 * - Build capability unions.
 * - Preserve source company IDs.
 * - Preserve legacy names and sectors as evidence.
 * - Determine whether an identity is READY or REVIEW.
 *
 * This service DOES NOT:
 *
 * - update companies
 * - delete companies
 * - merge legacy company records
 * - correct legacy data
 * - infer current addresses
 * - infer facilities or plants
 * - determine current company truth
 *
 * Legacy data remains historical evidence.
 *
 * Current company information will later come from company
 * self-update and Digestex verification.
 */
class CompanyIdentityResolver
{
    /**
     * Generic legacy values that should not automatically
     * become canonical company identities.
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
     * Resolve a collection of Company models into identity plans.
     *
     * @param Collection<int, Company> $companies
     */
    public function resolve(Collection $companies): Collection
    {
        $records = $companies
            ->map(
                fn (Company $company): array =>
                    $this->recordFromCompany($company)
            )
            ->filter(
                fn (array $record): bool =>
                    $record['normalized_name'] !== ''
            )
            ->values();

        return $records
            ->groupBy('normalized_name')
            ->sortByDesc(
                fn (Collection $group): int =>
                    $group->count()
            )
            ->map(
                fn (
                    Collection $group,
                    string $normalizedName
                ): array =>
                    $this->buildIdentityPlan(
                        $normalizedName,
                        $group
                    )
            );
    }

    /**
     * Convert one Company model into an identity source record.
     */
    public function recordFromCompany(
        Company $company
    ): array {
        return [
            'id' =>
                (int) $company->id,

            'name' =>
                trim(
                    (string) (
                        $company->nama_perusahaan
                        ?? ''
                    )
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

            'data_source' =>
                $company->data_source,

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
     * Build one identity plan from grouped legacy records.
     */
    public function buildIdentityPlan(
        string $normalizedName,
        Collection $group
    ): array {
        $sourceCount = $group->count();

        /*
        |--------------------------------------------------------------------------
        | Capability Union
        |--------------------------------------------------------------------------
        |
        | Capabilities are combined from all legacy source records.
        |
        | They remain legacy evidence and are not automatically
        | considered current verified facts.
        |
        */

        $capabilityUnion = $group
            ->flatMap(
                fn (array $record): array =>
                    $record['capabilities']
            )
            ->filter()
            ->unique()
            ->sort()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Source Company IDs
        |--------------------------------------------------------------------------
        */

        $sourceIds = $group
            ->pluck('id')
            ->map(
                fn ($id): int =>
                    (int) $id
            )
            ->sort()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Original Legacy Names
        |--------------------------------------------------------------------------
        |
        | Preserve original spelling as historical evidence.
        |
        */

        $sourceNames = $group
            ->pluck('name')
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Legacy Sectors
        |--------------------------------------------------------------------------
        */

        $legacySectors = $group
            ->pluck('sector')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Country Evidence
        |--------------------------------------------------------------------------
        */

        $countries = $group
            ->pluck('country')
            ->filter()
            ->map(
                fn ($country): string =>
                    Str::upper(
                        trim(
                            (string) $country
                        )
                    )
            )
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Legacy Record Status
        |--------------------------------------------------------------------------
        */

        $status = $sourceCount > 1
            ? 'MULTIPLE_LEGACY_RECORDS'
            : 'SINGLE_LEGACY_RECORD';

        /*
        |--------------------------------------------------------------------------
        | Identity Status
        |--------------------------------------------------------------------------
        */

        $identityStatus =
            $this->identityStatus(
                $normalizedName,
                $countries
            );

        return [
            'identity' =>
                $normalizedName,

            'source_count' =>
                $sourceCount,

            'source_ids' =>
                $sourceIds,

            'source_names' =>
                $sourceNames,

            'legacy_sectors' =>
                $legacySectors,

            'countries' =>
                $countries,

            'capability_union' =>
                $capabilityUnion,

            'status' =>
                $status,

            'identity_status' =>
                $identityStatus,
        ];
    }

    /**
     * Determine whether an identity candidate can proceed
     * automatically or requires manual review.
     */
    public function identityStatus(
        string $normalizedName,
        Collection $countries
    ): string {
        /*
        |--------------------------------------------------------------------------
        | Generic Names
        |--------------------------------------------------------------------------
        |
        | Values such as WEAVING or GARMENT may originate from
        | incorrectly imported directory columns and therefore must
        | not automatically become canonical companies.
        |
        */

        if (
            in_array(
                $normalizedName,
                self::GENERIC_IDENTITIES,
                true
            )
        ) {
            return 'REVIEW';
        }

        /*
        |--------------------------------------------------------------------------
        | Very Short Names
        |--------------------------------------------------------------------------
        */

        if (
            mb_strlen(
                str_replace(
                    ' ',
                    '',
                    $normalizedName
                )
            ) < 4
        ) {
            return 'REVIEW';
        }

        /*
        |--------------------------------------------------------------------------
        | Multiple Countries
        |--------------------------------------------------------------------------
        |
        | The same normalized name occurring in multiple countries
        | requires review before canonicalization.
        |
        */

        if ($countries->count() > 1) {
            return 'REVIEW';
        }

        return 'READY';
    }

    /**
     * Normalize company name for conservative identity matching.
     *
     * Examples:
     *
     * PT KAHATEX
     * KAHATEX, PT.
     * KAHATEX PT
     *
     * become:
     *
     * KAHATEX
     *
     * Original database values are never modified.
     */
    public function normalizeCompanyName(
        ?string $name
    ): string {
        if ($name === null) {
            return '';
        }

        $name = Str::upper(
            trim($name)
        );

        /*
        |--------------------------------------------------------------------------
        | Punctuation
        |--------------------------------------------------------------------------
        */

        $name = preg_replace(
            '/[.,;:\/\\\\()\[\]{}]+/u',
            ' ',
            $name
        ) ?? $name;

        /*
        |--------------------------------------------------------------------------
        | Indonesian PT Legal Marker
        |--------------------------------------------------------------------------
        |
        | PT is removed only from the identity matching key.
        | The original company name remains untouched.
        |
        */

        $name = preg_replace(
            '/\bPT\b/u',
            ' ',
            $name
        ) ?? $name;

        /*
        |--------------------------------------------------------------------------
        | Whitespace
        |--------------------------------------------------------------------------
        */

        $name = preg_replace(
            '/\s+/u',
            ' ',
            $name
        ) ?? $name;

        return trim(
            $name
        );
    }
}