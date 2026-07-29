<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Readiness Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Evaluates the completeness of a company's intelligence profile across
 * the 10 dimensions of the DIGESTEX Digital Company Passport.
 *
 * IMPORTANT:
 *
 * Readiness measures PROFILE COMPLETENESS.
 *
 * It does NOT measure:
 *
 * - Company quality
 * - Manufacturing performance
 * - Export capability
 * - Supplier quality
 * - Verification status
 * - Buyer suitability
 *
 * Those assessments belong to separate intelligence services.
 *
 * Intelligence Dimensions
 * --------------------------------------------------------------------------
 *
 * 01 Identity
 * 02 Facilities
 * 03 Products
 * 04 Capacity
 * 05 Machinery
 * 06 Commercial
 * 07 Markets
 * 08 Compliance
 * 09 Contacts
 * 10 Media
 *
 * Data Source
 * --------------------------------------------------------------------------
 * CompanyProfileService::passport()
 *
 * Used By
 * --------------------------------------------------------------------------
 *
 * - Digital Company Passport
 * - Company Intelligence Profile
 * - Executive Dashboard
 * - Company Intelligence Orchestrator
 * - Future Matching / Opportunity Engines
 *
 * DIGESTEX Company Intelligence Framework
 */
class CompanyReadinessService
{
    /**
     * --------------------------------------------------------------------------
     * Dimension Weights
     * --------------------------------------------------------------------------
     *
     * Total = 100
     */
    protected array $weights = [

        '01_identity' => 10,

        '02_facilities' => 10,

        '03_products' => 15,

        '04_capacity' => 15,

        '05_machinery' => 12,

        '06_commercial' => 10,

        '07_markets' => 10,

        '08_compliance' => 10,

        '09_contacts' => 5,

        '10_media' => 3,
    ];

    protected array $dimensionLabels = [

    '01_identity' => [
        'en' => 'Identity',
        'id' => 'Identitas',
    ],

    '02_facilities' => [
        'en' => 'Facilities',
        'id' => 'Fasilitas',
    ],

    '03_products' => [
        'en' => 'Products',
        'id' => 'Produk',
    ],

    '04_capacity' => [
        'en' => 'Capacity',
        'id' => 'Kapasitas',
    ],

    '05_machinery' => [
        'en' => 'Machinery',
        'id' => 'Mesin',
    ],

    '06_commercial' => [
        'en' => 'Commercial',
        'id' => 'Komersial',
    ],

    '07_markets' => [
        'en' => 'Markets',
        'id' => 'Pasar',
    ],

    '08_compliance' => [
        'en' => 'Compliance',
        'id' => 'Kepatuhan',
    ],

    '09_contacts' => [
        'en' => 'Contacts',
        'id' => 'Kontak',
    ],

    '10_media' => [
        'en' => 'Media',
        'id' => 'Media',
    ],
];

protected array $dimensionActions = [

    '01_identity' => [
        'en' => 'Complete company identity information',
        'id' => 'Lengkapi informasi identitas perusahaan',
    ],

    '02_facilities' => [
        'en' => 'Complete facility and location information',
        'id' => 'Lengkapi informasi fasilitas dan lokasi',
    ],

    '03_products' => [
        'en' => 'Complete product intelligence',
        'id' => 'Lengkapi intelligence produk',
    ],

    '04_capacity' => [
        'en' => 'Complete production capacity information',
        'id' => 'Lengkapi informasi kapasitas produksi',
    ],

    '05_machinery' => [
        'en' => 'Add machinery information',
        'id' => 'Tambahkan informasi mesin',
    ],

    '06_commercial' => [
        'en' => 'Add MOQ and lead time information',
        'id' => 'Tambahkan informasi MOQ dan lead time',
    ],

    '07_markets' => [
        'en' => 'Complete market information',
        'id' => 'Lengkapi informasi pasar',
    ],

    '08_compliance' => [
        'en' => 'Add certifications and compliance information',
        'id' => 'Tambahkan sertifikasi dan informasi kepatuhan',
    ],

    '09_contacts' => [
        'en' => 'Complete business contact information',
        'id' => 'Lengkapi informasi kontak bisnis',
    ],

    '10_media' => [
        'en' => 'Add company images and media',
        'id' => 'Tambahkan foto dan media perusahaan',
    ],
];

    /**
     * --------------------------------------------------------------------------
     * Constructor
     * --------------------------------------------------------------------------
     */
    public function __construct(
        protected CompanyProfileService $profileService
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Value Presence
     * --------------------------------------------------------------------------
     *
     * Determines whether a value should be considered populated.
     *
     * Important:
     * - 0 is valid data.
     * - false is valid data.
     * - Empty strings are missing.
     * - Empty arrays are missing.
     */
    protected function hasValue(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return count($value) > 0;
        }

        return true;
    }

    /**
     * --------------------------------------------------------------------------
     * Field Completion
     * --------------------------------------------------------------------------
     *
     * Returns percentage completion for a collection of values.
     */
    protected function fieldCompletion(array $values): float
    {
        if ($values === []) {
            return 0.0;
        }

        $completed = collect($values)
            ->filter(
                fn ($value) => $this->hasValue($value)
            )
            ->count();

        return round(
            ($completed / count($values)) * 100,
            2
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Average
     * --------------------------------------------------------------------------
     */
    protected function average(array $scores): float
    {
        if ($scores === []) {
            return 0.0;
        }

        return round(
            array_sum($scores) / count($scores),
            2
        );
    }

    /**
     * --------------------------------------------------------------------------
     * 01 — Identity Completion
     * --------------------------------------------------------------------------
     */
    protected function identityCompletion(array $identity): float
    {
        return $this->fieldCompletion([
            $identity['company_name'] ?? null,
            $identity['country_code'] ?? null,
            $identity['country_name'] ?? null,
            $identity['sector'] ?? null,
            $identity['category'] ?? null,
            $identity['director'] ?? null,
            $identity['employees'] ?? null,
            $identity['established_year'] ?? null,
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * 02 — Facilities Completion
     * --------------------------------------------------------------------------
     */
    protected function facilitiesCompletion(array $facilities): float
    {
        if ($facilities === []) {
            return 0.0;
        }

        $scores = [];

        foreach ($facilities as $facility) {

            $scores[] = $this->fieldCompletion([
                $facility['name'] ?? null,
                $facility['type'] ?? null,
                $facility['country_name'] ?? null,
                $facility['province'] ?? null,
                $facility['city'] ?? null,
                $facility['address'] ?? null,
                $facility['phone'] ?? null,
                $facility['email'] ?? null,
            ]);
        }

        return $this->average($scores);
    }

    /**
     * --------------------------------------------------------------------------
     * 03 — Products Completion
     * --------------------------------------------------------------------------
     */
    protected function productsCompletion(array $products): float
    {
        if ($products === []) {
            return 0.0;
        }

        $scores = [];

        foreach ($products as $product) {

            $scores[] = $this->fieldCompletion([
                $product['name'] ?? null,
                $product['hs_code'] ?? null,
                $product['category'] ?? null,
                $product['application'] ?? null,
                $product['description'] ?? null,
                $product['status'] ?? null,
            ]);
        }

        return $this->average($scores);
    }

    /**
     * --------------------------------------------------------------------------
     * 04 — Capacity Completion
     * --------------------------------------------------------------------------
     */
    protected function capacityCompletion(array $capacities): float
    {
        if ($capacities === []) {
            return 0.0;
        }

        $scores = [];

        foreach ($capacities as $capacity) {

            $scores[] = $this->fieldCompletion([
                $capacity['type'] ?? null,
                $capacity['item_name'] ?? null,
                $capacity['value'] ?? null,
                $capacity['unit'] ?? null,
                $capacity['category'] ?? null,
                $capacity['shift_info'] ?? null,
                $capacity['machine_count'] ?? null,
            ]);
        }

        return $this->average($scores);
    }

    /**
     * --------------------------------------------------------------------------
     * 05 — Machinery Completion
     * --------------------------------------------------------------------------
     */
    protected function machineryCompletion(array $machines): float
    {
        if ($machines === []) {
            return 0.0;
        }

        $scores = [];

        foreach ($machines as $machine) {

            $scores[] = $this->fieldCompletion([
                $machine['category'] ?? null,
                $machine['type'] ?? null,
                $machine['brand'] ?? null,
                $machine['model'] ?? null,
                $machine['quantity'] ?? null,
                $machine['year_installed'] ?? null,
                $machine['country_origin'] ?? null,
                $machine['production_capacity'] ?? null,
                $machine['capacity_unit'] ?? null,
                $machine['condition'] ?? null,
                $machine['automation_level'] ?? null,
            ]);
        }

        return $this->average($scores);
    }

    /**
     * --------------------------------------------------------------------------
     * 06 — Commercial Completion
     * --------------------------------------------------------------------------
     */
    protected function commercialCompletion(array $commercial): float
    {
        $moqs =
            $commercial['moqs'] ?? [];

        $leadTimes =
            $commercial['lead_times'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | MOQ
        |--------------------------------------------------------------------------
        */

        $moqScores = [];

        foreach ($moqs as $moq) {

            $moqScores[] = $this->fieldCompletion([
                $moq['product_name'] ?? null,
                $moq['minimum_quantity'] ?? null,
                $moq['unit'] ?? null,
            ]);
        }

        $moqScore =
            $this->average($moqScores);

        /*
        |--------------------------------------------------------------------------
        | Lead Time
        |--------------------------------------------------------------------------
        */

        $leadTimeScores = [];

        foreach ($leadTimes as $leadTime) {

            $leadTimeScores[] = $this->fieldCompletion([
                $leadTime['type'] ?? null,
                $leadTime['days'] ?? null,
            ]);
        }

        $leadTimeScore =
            $this->average($leadTimeScores);

        /*
        |--------------------------------------------------------------------------
        | Commercial requires both dimensions.
        |--------------------------------------------------------------------------
        */

        return round(
            ($moqScore + $leadTimeScore) / 2,
            2
        );
    }

    /**
     * --------------------------------------------------------------------------
     * 07 — Markets Completion
     * --------------------------------------------------------------------------
     */
    protected function marketsCompletion(array $markets): float
    {
        if ($markets === []) {
            return 0.0;
        }

        $scores = [];

        foreach ($markets as $market) {

            $scores[] = $this->fieldCompletion([
                $market['country_name'] ?? null,
                $market['market_type'] ?? null,
            ]);
        }

        return $this->average($scores);
    }

    /**
     * --------------------------------------------------------------------------
     * 08 — Compliance Completion
     * --------------------------------------------------------------------------
     */
    protected function complianceCompletion(
        array $certifications
    ): float {

        if ($certifications === []) {
            return 0.0;
        }

        $scores = [];

        foreach ($certifications as $certification) {

            /*
             * is_verified is intentionally NOT used for completion.
             *
             * Verification is a different concept from profile
             * completeness.
             */

            $scores[] = $this->fieldCompletion([
                $certification['name'] ?? null,
                $certification['category'] ?? null,
                $certification['issuer'] ?? null,
                $certification['certificate_number'] ?? null,
                $certification['issued_at'] ?? null,
                $certification['valid_until'] ?? null,
                $certification['status'] ?? null,
            ]);
        }

        return $this->average($scores);
    }

    /**
     * --------------------------------------------------------------------------
     * 09 — Contacts Completion
     * --------------------------------------------------------------------------
     */
    protected function contactsCompletion(
        array $connectivity
    ): float {

        $contacts =
            $connectivity['contacts'] ?? [];

        $links =
            $connectivity['links'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Contact Persons
        |--------------------------------------------------------------------------
        */

        $contactScores = [];

        foreach ($contacts as $contact) {

            $contactScores[] = $this->fieldCompletion([
                $contact['name'] ?? null,
                $contact['position'] ?? null,
                $contact['phone'] ?? null,
                $contact['email'] ?? null,
            ]);
        }

        $contactScore =
            $this->average($contactScores);

        /*
        |--------------------------------------------------------------------------
        | Digital Links
        |--------------------------------------------------------------------------
        */

        $linkScores = [];

        foreach ($links as $link) {

            $linkScores[] = $this->fieldCompletion([
                $link['type'] ?? null,
                $link['url'] ?? null,
            ]);
        }

        $linkScore =
            $this->average($linkScores);

        /*
        |--------------------------------------------------------------------------
        | Contact Intelligence
        |--------------------------------------------------------------------------
        |
        | Contact persons carry more importance than links.
        |
        | Contacts : 70%
        | Links    : 30%
        */

        return round(
            ($contactScore * 0.70) +
            ($linkScore * 0.30),
            2
        );
    }

    /**
     * --------------------------------------------------------------------------
     * 10 — Media Completion
     * --------------------------------------------------------------------------
     */
    protected function mediaCompletion(array $media): float
    {
        if ($media === []) {
            return 0.0;
        }

        $scores = [];

        foreach ($media as $image) {

            $scores[] = $this->fieldCompletion([
                $image['image_url']
                    ?? $image['image_path']
                    ?? null,

                $image['type'] ?? null,

                $image['title'] ?? null,

                $image['caption'] ?? null,
            ]);
        }

        return $this->average($scores);
    }

    /**
     * --------------------------------------------------------------------------
     * Dimension Status
     * --------------------------------------------------------------------------
     */
    protected function dimensionStatus(
        float $score
    ): string {

        return match (true) {

            $score >= 90 =>
                'complete',

            $score > 0 =>
                'partial',

            default =>
                'missing',
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Readiness Level
     * --------------------------------------------------------------------------
     *
     * This describes PROFILE COMPLETENESS only.
     */
    protected function scoreLevel(
    float $score
        ): string {

            return match (true) {

                $score >= 90 =>
                    'Complete',

                $score >= 75 =>
                    'Advanced',

                $score >= 50 =>
                    'Established',

                $score >= 25 =>
                    'Developing',

                default =>
                    'Insufficient Data',
            };
        }

    /**
     * --------------------------------------------------------------------------
     * Readiness Rating
     * --------------------------------------------------------------------------
     *
     * Rating represents data completeness, not company quality.
     */
    protected function scoreRating(
        float $score
    ): string {

        return match (true) {

            $score >= 90 => 'A',

            $score >= 75 => 'B',

            $score >= 50 => 'C',

            $score >= 25 => 'D',

            default => 'E',
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Calculate Dimension Scores
     * --------------------------------------------------------------------------
     */
    protected function dimensionScores(
        array $passport
    ): array {

        return [

            '01_identity' =>
                $this->identityCompletion(
                    $passport['01_identity'] ?? []
                ),

            '02_facilities' =>
                $this->facilitiesCompletion(
                    $passport['02_facilities'] ?? []
                ),

            '03_products' =>
                $this->productsCompletion(
                    $passport['03_products'] ?? []
                ),

            '04_capacity' =>
                $this->capacityCompletion(
                    $passport['04_capacity'] ?? []
                ),

            '05_machinery' =>
                $this->machineryCompletion(
                    $passport['05_machinery'] ?? []
                ),

            '06_commercial' =>
                $this->commercialCompletion(
                    $passport['06_commercial'] ?? []
                ),

            '07_markets' =>
                $this->marketsCompletion(
                    $passport['07_markets'] ?? []
                ),

            '08_compliance' =>
                $this->complianceCompletion(
                    $passport['08_compliance'] ?? []
                ),

            '09_contacts' =>
                $this->contactsCompletion(
                    $passport['09_contacts'] ?? []
                ),

            '10_media' =>
                $this->mediaCompletion(
                    $passport['10_media'] ?? []
                ),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Readiness Score
     * --------------------------------------------------------------------------
     *
     * Applies dimension weights to field-level completion.
     */
    protected function readinessScore(
        array $passport
    ): array {

        $dimensionScores =
            $this->dimensionScores($passport);

        $dimensions = [];

        $overall = 0.0;

        foreach (
            $this->weights as $dimension => $weight
        ) {

            $completion =
                $dimensionScores[$dimension] ?? 0.0;

            $contribution =
                round(
                    ($completion / 100) * $weight,
                    2
                );

            $overall +=
                $contribution;

            $dimensions[$dimension] = [

                'completion' =>
                    $completion,

                'weight' =>
                    $weight,

                'contribution' =>
                    $contribution,

                'status' =>
                    $this->dimensionStatus(
                        $completion
                    ),
            ];
        }

        $overall =
            round($overall, 2);

        return [

            'overall' =>
                $overall,

            'level' =>
                $this->scoreLevel($overall),

            'rating' =>
                $this->scoreRating($overall),

            'dimensions' =>
                $dimensions,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Readiness Statistics
     * --------------------------------------------------------------------------
     */
    protected function readinessStatistics(
        array $score
    ): array {

        $dimensions =
            $score['dimensions'] ?? [];

        $completed =
            collect($dimensions)
                ->where(
                    'status',
                    'complete'
                )
                ->count();

        $partial =
            collect($dimensions)
                ->where(
                    'status',
                    'partial'
                )
                ->count();

        $missing =
            collect($dimensions)
                ->where(
                    'status',
                    'missing'
                )
                ->count();

        return [

            'total_dimensions' =>
                count($this->weights),

            'completed_dimensions' =>
                $completed,

            'partial_dimensions' =>
                $partial,

            'missing_dimensions' =>
                $missing,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Missing Intelligence
     * --------------------------------------------------------------------------
     *
     * Returns dimensions that still require company data.
     */
    protected function missingIntelligence(
    array $score
): array {

    return collect(
        $score['dimensions'] ?? []
    )
        ->filter(
            fn ($dimension) =>
                ($dimension['status'] ?? null)
                !== 'complete'
        )
        ->map(
            function ($dimension, $key) {

                $completion =
                    (float) (
                        $dimension['completion']
                        ?? 0
                    );

                $weight =
                    (float) (
                        $dimension['weight']
                        ?? 0
                    );

                /*
                |--------------------------------------------------------------------------
                | Potential Score Gain
                |--------------------------------------------------------------------------
                |
                | Maximum contribution still available if this dimension
                | becomes 100% complete.
                |
                */

                $potentialGain =
                    round(
                        $weight *
                        ((100 - $completion) / 100),
                        2
                    );

                /*
                |--------------------------------------------------------------------------
                | Priority
                |--------------------------------------------------------------------------
                */

                $priority =
                    match (true) {

                        $potentialGain >= 8 =>
                            'high',

                        $potentialGain >= 3 =>
                            'medium',

                        default =>
                            'low',
                    };

                return [

                    'dimension' =>
                        $key,

                    'label' =>
                        $this->dimensionLabels[$key]
                        ?? [
                            'en' => $key,
                            'id' => $key,
                        ],

                    'action' =>
                        $this->dimensionActions[$key]
                        ?? null,

                    'completion' =>
                        $completion,

                    'missing_percentage' =>
                        round(
                            100 - $completion,
                            2
                        ),

                    'weight' =>
                        $weight,

                    'potential_gain' =>
                        $potentialGain,

                    'status' =>
                        $dimension['status']
                        ?? 'missing',

                    'priority' =>
                        $priority,
                ];
            }
        )

        /*
        |--------------------------------------------------------------------------
        | Highest Business Impact First
        |--------------------------------------------------------------------------
        */

        ->sortByDesc(
            'potential_gain'
        )

        ->values()

        ->all();
}

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    protected function executiveSummary(
        Company $company,
        array $score,
        array $passport
    ): array {

        $statistics =
            $this->readinessStatistics($score);

        return [

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'company_id' =>
                $company->id,

            'company_name' =>
                $company->nama_perusahaan,

            /*
            |--------------------------------------------------------------------------
            | Intelligence Readiness
            |--------------------------------------------------------------------------
            */

            'overall_score' =>
                $score['overall'],

            'level' =>
                $score['level'],

            'rating' =>
                $score['rating'],

            /*
            |--------------------------------------------------------------------------
            | Dimension Statistics
            |--------------------------------------------------------------------------
            */

            ...$statistics,

            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            |
            | Verification remains independent from readiness.
            */

            'verification_status' =>
                $company->status_verifikasi,

            'last_verified_at' =>
                $company->last_verified_at?->toDateTimeString(),

            'data_source' =>
                $company->data_source,

            /*
            |--------------------------------------------------------------------------
            | Dataset Statistics
            |--------------------------------------------------------------------------
            */

            'products' =>
                count(
                    $passport['03_products'] ?? []
                ),

            'facilities' =>
                count(
                    $passport['02_facilities'] ?? []
                ),

            'capacities' =>
                count(
                    $passport['04_capacity'] ?? []
                ),

            'machines' =>
                count(
                    $passport['05_machinery'] ?? []
                ),

            'markets' =>
                count(
                    $passport['07_markets'] ?? []
                ),

            'certifications' =>
                count(
                    $passport['08_compliance'] ?? []
                ),

            'contacts' =>
                count(
                    $passport['09_contacts']['contacts']
                    ?? []
                ),

            'media' =>
                count(
                    $passport['10_media'] ?? []
                ),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Company Readiness Intelligence
     * --------------------------------------------------------------------------
     *
     * Standard DIGESTEX Intelligence response.
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Digital Company Passport — SSOT
        |--------------------------------------------------------------------------
        */

        $passport =
            $this->profileService
                ->passport($company);

        /*
        |--------------------------------------------------------------------------
        | Weighted Readiness Score
        |--------------------------------------------------------------------------
        */

        $score =
            $this->readinessScore(
                $passport
            );

        /*
        |--------------------------------------------------------------------------
        | Executive Summary
        |--------------------------------------------------------------------------
        */

        $summary =
            $this->executiveSummary(

                company: $company,

                score: $score,

                passport: $passport,
            );

        /*
        |--------------------------------------------------------------------------
        | Standard Intelligence Response
        |--------------------------------------------------------------------------
        */

        return [

            'score' =>
                $score,

            'passport' =>
                $passport,

            'summary' =>
                $summary,

            'missing_intelligence' =>
                $this->missingIntelligence(
                    $score
                ),
        ];
    }
}