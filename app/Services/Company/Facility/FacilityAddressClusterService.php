<?php

declare(strict_types=1);

namespace App\Services\Company\Facility;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Facility Address Cluster Service
 * ==========================================================================
 *
 * Groups legacy physical-location records that appear to represent the same
 * facility/location.
 *
 * This service is intentionally conservative.
 *
 * It DOES NOT:
 *
 * - create company_locations
 * - update company_locations
 * - delete legacy records
 * - infer factory/head-office/warehouse type
 * - assign company capabilities to a facility
 *
 * Version:
 * 1.1
 */
class FacilityAddressClusterService
{
    /**
     * Cluster legacy location records.
     *
     * Expected input fields:
     *
     * company_id
     * location_id
     * address
     * phone
     * city
     * sector
     * capabilities
     */
    public function cluster(
        Collection|array $records
    ): Collection {

        $records = collect($records)
            ->map(
                fn ($record): array =>
                    $this->normalizeRecord(
                        is_array($record)
                            ? $record
                            : (array) $record
                    )
            )
            ->filter(
                fn (array $record): bool =>
                    $record['normalized_address'] !== ''
            )
            ->values();

        $clusters = collect();

        foreach ($records as $record) {

            $bestIndex = null;
            $bestScore = 0.0;

            foreach (
                $clusters
                as $index => $cluster
            ) {
                $score = $this->clusterSimilarity(
                    $record,
                    $cluster
                );

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestIndex = $index;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Conservative merge threshold
            |--------------------------------------------------------------------------
            |
            | 0.72 prevents records from being merged merely because they share
            | a broad city such as Bandung or Padalarang.
            |
            */

            if (
                $bestIndex !== null
                && $bestScore >= 0.72
            ) {
                $cluster = $clusters->get(
                    $bestIndex
                );

                $cluster['records'][] =
                    $record;

                $cluster['last_match_score'] =
                    round(
                        $bestScore,
                        4
                    );

                $clusters->put(
                    $bestIndex,
                    $this->refreshCluster(
                        $cluster
                    )
                );

                continue;
            }

            $clusters->push(
                $this->newCluster(
                    $record
                )
            );
        }

        return $clusters
            ->map(
                fn (array $cluster): array =>
                    $this->finalizeCluster(
                        $cluster
                    )
            )
            ->values();
    }

    /**
     * Normalize source record.
     */
    private function normalizeRecord(
        array $record
    ): array {

        $address = $this->cleanText(
            $record['address']
            ?? null
        );

        $phone = $this->cleanText(
            $record['phone']
            ?? null
        );

        $city = $this->cleanText(
            $record['city']
            ?? null
        );

        return [
            'company_id' =>
                isset($record['company_id'])
                    ? (int) $record['company_id']
                    : null,

            'location_id' =>
                isset($record['location_id'])
                    ? (int) $record['location_id']
                    : null,

            'address' =>
                $address,

            'phone' =>
                $phone,

            'city' =>
                $city,

            'sector' =>
                $record['sector']
                ?? null,

            'capabilities' =>
                array_values(
                    array_unique(
                        array_filter(
                            $record['capabilities']
                            ?? []
                        )
                    )
                ),

            'normalized_address' =>
                $this->normalizeAddress(
                    $address
                ),

            'address_tokens' =>
                $this->addressTokens(
                    $address
                ),

            'phone_tokens' =>
                $this->phoneTokens(
                    $phone
                ),

            'postal_codes' =>
                $this->postalCodes(
                    $address
                ),
        ];
    }

    /**
     * Create new cluster.
     */
    private function newCluster(
        array $record
    ): array {

        return $this->refreshCluster([
            'records' => [
                $record,
            ],

            'last_match_score' =>
                1.0,
        ]);
    }

    /**
     * Rebuild aggregate cluster evidence.
     */
    private function refreshCluster(
        array $cluster
    ): array {

        $records = collect(
            $cluster['records']
        );

        $cluster['company_ids'] =
            $records
                ->pluck('company_id')
                ->filter(
                    fn ($id): bool =>
                        $id !== null
                )
                ->unique()
                ->sort()
                ->values()
                ->all();

        $cluster['location_ids'] =
            $records
                ->pluck('location_id')
                ->filter(
                    fn ($id): bool =>
                        $id !== null
                )
                ->unique()
                ->sort()
                ->values()
                ->all();

        $cluster['addresses'] =
            $records
                ->pluck('address')
                ->filter()
                ->unique()
                ->values()
                ->all();

        $cluster['cities'] =
            $records
                ->pluck('city')
                ->filter()
                ->unique()
                ->values()
                ->all();

        $cluster['phones'] =
            $records
                ->pluck('phone')
                ->filter()
                ->unique()
                ->values()
                ->all();

        $cluster['phone_tokens'] =
            $records
                ->flatMap(
                    fn (array $record): array =>
                        $record['phone_tokens']
                )
                ->unique()
                ->values()
                ->all();

        $cluster['postal_codes'] =
            $records
                ->flatMap(
                    fn (array $record): array =>
                        $record['postal_codes']
                )
                ->unique()
                ->values()
                ->all();

        $cluster['address_tokens'] =
            $records
                ->flatMap(
                    fn (array $record): array =>
                        $record['address_tokens']
                )
                ->unique()
                ->values()
                ->all();

        $cluster['sectors'] =
            $records
                ->pluck('sector')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->all();

        $cluster['capabilities'] =
            $records
                ->flatMap(
                    fn (array $record): array =>
                        $record['capabilities']
                )
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->all();

        return $cluster;
    }

    /**
     * Compare one record against an existing cluster.
     */
    private function clusterSimilarity(
        array $record,
        array $cluster
    ): float {

        $best = 0.0;

        foreach (
            $cluster['records']
            as $existing
        ) {
            $score = $this->recordSimilarity(
                $record,
                $existing
            );

            $best = max(
                $best,
                $score
            );
        }

        return $best;
    }

    /**
     * Calculate record-to-record similarity.
     */
    private function recordSimilarity(
        array $a,
        array $b
    ): float {

        /*
        |--------------------------------------------------------------------------
        | Address similarity
        |--------------------------------------------------------------------------
        */

        $addressScore =
            $this->jaccard(
                $a['address_tokens'],
                $b['address_tokens']
            );

        /*
        |--------------------------------------------------------------------------
        | Phone evidence
        |--------------------------------------------------------------------------
        */

        $phoneScore =
            $this->overlapScore(
                $a['phone_tokens'],
                $b['phone_tokens']
            );

        /*
        |--------------------------------------------------------------------------
        | Postal code evidence
        |--------------------------------------------------------------------------
        */

        $postalScore =
            $this->overlapScore(
                $a['postal_codes'],
                $b['postal_codes']
            );

        /*
        |--------------------------------------------------------------------------
        | Exact normalized address
        |--------------------------------------------------------------------------
        */

        $exactAddress =
            $a['normalized_address'] !== ''
            && $a['normalized_address']
                === $b['normalized_address'];

        if ($exactAddress) {
            return 1.0;
        }

        /*
        |--------------------------------------------------------------------------
        | Strong phone + reasonable address
        |--------------------------------------------------------------------------
        */

        if (
            $phoneScore > 0
            && $addressScore >= 0.45
        ) {
            return min(
                1.0,
                0.65
                + ($addressScore * 0.25)
                + ($postalScore * 0.10)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Strong address + postal evidence
        |--------------------------------------------------------------------------
        */

        if (
            $addressScore >= 0.75
            && $postalScore > 0
        ) {
            return min(
                1.0,
                0.70
                + ($addressScore * 0.20)
                + ($postalScore * 0.10)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | General weighted score
        |--------------------------------------------------------------------------
        */

        return round(
            ($addressScore * 0.70)
            + ($phoneScore * 0.20)
            + ($postalScore * 0.10),
            4
        );
    }

    /**
     * Final public cluster contract.
     */
    private function finalizeCluster(
        array $cluster
    ): array {

        $cluster = $this->refreshCluster(
            $cluster
        );

        $sourceCount = count(
            $cluster['company_ids']
        );

        return [
            'source_count' =>
                $sourceCount,

            'company_ids' =>
                $cluster['company_ids'],

            'location_ids' =>
                $cluster['location_ids'],

            'representative_address' =>
                $this->representativeAddress(
                    $cluster['records']
                ),

            'addresses' =>
                $cluster['addresses'],

            'cities' =>
                $cluster['cities'],

            'phones' =>
                $cluster['phones'],

            'phone_tokens' =>
                $cluster['phone_tokens'],

            'postal_codes' =>
                $cluster['postal_codes'],

            'sectors' =>
                $cluster['sectors'],

            'capabilities' =>
                $cluster['capabilities'],

            'confidence' =>
                $this->confidence(
                    $sourceCount
                ),

            'facility_type' =>
                null,

            'facility_type_status' =>
                'unverified',
        ];
    }

    /**
     * Choose the most informative address as cluster representative.
     */
    private function representativeAddress(
        array $records
    ): ?string {

        return collect($records)
            ->pluck('address')
            ->filter()
            ->sortByDesc(
                fn (string $address): int =>
                    mb_strlen($address)
            )
            ->first();
    }

    /**
     * Normalize address for comparison.
     */
    private function normalizeAddress(
        ?string $address
    ): string {

        if (
            $address === null
            || trim($address) === ''
        ) {
            return '';
        }

        $value = Str::upper(
            $address
        );

        /*
        |--------------------------------------------------------------------------
        | Normalize common OCR / formatting differences
        |--------------------------------------------------------------------------
        */

        $replacements = [
            'JALAN' => 'JL',
            'JL.' => 'JL',
            'NO.' => 'NO',
            'KM.' => 'KM',
            'DS.' => 'DS',
            'KEC.' => 'KEC',
            'KAB.' => 'KAB',
            'WEST JAVA' => 'JAWA BARAT',
        ];

        $value = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $value
        );

        $value = preg_replace(
            '/[|;,]+/u',
            ' ',
            $value
        ) ?? $value;

        $value = preg_replace(
            '/[^A-Z0-9\s\/\-]/u',
            ' ',
            $value
        ) ?? $value;

        $value = preg_replace(
            '/\s+/u',
            ' ',
            $value
        ) ?? $value;

        return trim($value);
    }

    /**
     * Address tokens used for fuzzy matching.
     */
    private function addressTokens(
        ?string $address
    ): array {

        $normalized =
            $this->normalizeAddress(
                $address
            );

        if ($normalized === '') {
            return [];
        }

        $stopWords = [
            'JL',
            'JAWA',
            'BARAT',
            'INDONESIA',
            'NO',
            'KM',
            'DS',
            'DESA',
            'KEC',
            'KECAMATAN',
            'KAB',
            'KABUPATEN',
        ];

        return collect(
            preg_split(
                '/\s+/u',
                $normalized
            ) ?: []
        )
            ->map(
                fn (string $token): string =>
                    trim($token)
            )
            ->filter(
                fn (string $token): bool =>
                    $token !== ''
                    && mb_strlen($token) >= 3
                    && ! in_array(
                        $token,
                        $stopWords,
                        true
                    )
            )
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Extract phone-number evidence.
     */
    private function phoneTokens(
        ?string $phone
    ): array {

        if (
            $phone === null
            || trim($phone) === ''
        ) {
            return [];
        }

        preg_match_all(
            '/\d+/u',
            $phone,
            $matches
        );

        $digits = $matches[0]
            ?? [];

        /*
        |--------------------------------------------------------------------------
        | Ignore very short fragments.
        |--------------------------------------------------------------------------
        */

        return collect($digits)
            ->map(
                fn (string $value): string =>
                    ltrim(
                        $value,
                        '0'
                    )
            )
            ->filter(
                fn (string $value): bool =>
                    strlen($value) >= 5
            )
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Extract Indonesian-style five-digit postal codes.
     */
    private function postalCodes(
        ?string $address
    ): array {

        if (
            $address === null
            || trim($address) === ''
        ) {
            return [];
        }

        preg_match_all(
            '/(?<!\d)\d{5}(?!\d)/u',
            $address,
            $matches
        );

        return array_values(
            array_unique(
                $matches[0]
                ?? []
            )
        );
    }

    /**
     * Jaccard similarity.
     */
    private function jaccard(
        array $a,
        array $b
    ): float {

        if (
            $a === []
            || $b === []
        ) {
            return 0.0;
        }

        $intersection = array_intersect(
            $a,
            $b
        );

        $union = array_unique(
            array_merge(
                $a,
                $b
            )
        );

        if ($union === []) {
            return 0.0;
        }

        return count($intersection)
            / count($union);
    }

    /**
     * Binary overlap evidence.
     */
    private function overlapScore(
        array $a,
        array $b
    ): float {

        if (
            $a === []
            || $b === []
        ) {
            return 0.0;
        }

        return array_intersect(
            $a,
            $b
        ) !== []
            ? 1.0
            : 0.0;
    }

    /**
     * Evidence confidence.
     *
     * This represents repetition of evidence,
     * not verification of facility identity/type.
     */
    private function confidence(
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
     * Clean source text.
     */
    private function cleanText(
        ?string $value
    ): ?string {

        if ($value === null) {
            return null;
        }

        $value = preg_replace(
            '/\s+/u',
            ' ',
            trim($value)
        ) ?? trim($value);

        return $value !== ''
            ? $value
            : null;
    }
}