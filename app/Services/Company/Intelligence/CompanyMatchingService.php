<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;
use Illuminate\Support\Collection;

class CompanyMatchingService
{
    /**
     * ==========================================================================
     * DIGESTEX CORE
     * ==========================================================================
     * Smart Business Matching
     * ==========================================================================
     *
     * Rule-based intelligence layer.
     *
     * The engine evaluates:
     *
     * - Business role
     * - Business category
     * - Products
     * - Capabilities
     * - Machinery
     * - Certifications
     * - Markets
     * - Capacity
     * - Location
     * - Company readiness
     *
     * AI can be added later on top of this deterministic foundation.
     */

    public function __construct(
        protected BusinessRoleService $roleService,
        protected BusinessEcosystemService $ecosystemService,
        protected BusinessNeedService $needService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Smart Business Matching
     * --------------------------------------------------------------------------
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Resolve Business Role
        |--------------------------------------------------------------------------
        */

        $role = $this->roleService->resolve($company);

        /*
        |--------------------------------------------------------------------------
        | Resolve Business Ecosystem
        |--------------------------------------------------------------------------
        */

        $ecosystem = $this->ecosystemService->resolve($role);

        /*
        |--------------------------------------------------------------------------
        | Build Business Needs
        |--------------------------------------------------------------------------
        */

        $categories = $this->needService
            ->matchingPayload($ecosystem);

        /*
        |--------------------------------------------------------------------------
        | Populate Every Category
        |--------------------------------------------------------------------------
        */

        foreach ($categories as &$category) {

            $category['companies'] = $this->recommendCompanies(
                company: $company,
                category: $category['category'],
            );
        }

        unset($category);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [
            'title' => 'Smart Business Matching',

            'description' =>
                'AI-powered business ecosystem recommendations.',

            'role' => $role,

            'company_type' =>
                $ecosystem['name'] ?? null,

            'ecosystem' =>
                $ecosystem,

            'categories' =>
                $categories,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Recommend Companies
     * --------------------------------------------------------------------------
     */
    protected function recommendCompanies(
        Company $company,
        string $category,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Load Target Company Intelligence
        |--------------------------------------------------------------------------
        */

        $this->loadMatchingRelations($company);

        /*
        |--------------------------------------------------------------------------
        | Candidate Pool
        |--------------------------------------------------------------------------
        |
        | We intentionally retrieve more than 5 candidates.
        | Scoring will determine the final top 5.
        |
        */

        $candidates = Company::query()
            ->with([
                'products',
                'markets',
                'certifications',
                'capacities',
                'machines',
            ])
            ->whereKeyNot($company->id)
            ->where(function ($query) use ($company) {

                /*
                |--------------------------------------------------------------------------
                | Never recommend another legacy record belonging to
                | the same canonical company identity.
                |--------------------------------------------------------------------------
                */

                if ($company->company_identity_id) {
                    $query->whereNull('company_identity_id')
                        ->orWhere(
                            'company_identity_id',
                            '!=',
                            $company->company_identity_id
                        );
                }
            })
            ->limit(500)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Remove duplicate canonical identities
        |--------------------------------------------------------------------------
        */

        $candidates = $this->uniqueCanonicalCompanies(
            $candidates
        );

        /*
        |--------------------------------------------------------------------------
        | Score Candidates
        |--------------------------------------------------------------------------
        */

        return $candidates

            ->map(function (Company $candidate) use (
                $company,
                $category
            ) {

                return $this->scoreCandidate(
                    company: $company,
                    candidate: $candidate,
                    category: $category,
                );

            })

            ->filter(function (array $result) {

                /*
                |--------------------------------------------------------------------------
                | Do not show meaningless matches.
                |--------------------------------------------------------------------------
                */

                return $result['matching_score'] >= 35;
            })

            ->sortByDesc('matching_score')

            ->take(5)

            ->values()

            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Load Matching Relations
     * --------------------------------------------------------------------------
     */
    protected function loadMatchingRelations(
        Company $company
    ): void {

        $company->loadMissing([
            'products',
            'markets',
            'certifications',
            'capacities',
            'machines',
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Canonical Company Deduplication
     * --------------------------------------------------------------------------
     *
     * Multiple legacy company rows can belong to one CompanyIdentity.
     *
     * We only recommend one representative record.
     */
    protected function uniqueCanonicalCompanies(
        Collection $companies
    ): Collection {

        return $companies
            ->sortBy(function (Company $company) {

                /*
                |--------------------------------------------------------------------------
                | Prefer records that have canonical identity.
                |--------------------------------------------------------------------------
                */

                return [
                    $company->company_identity_id ? 0 : 1,
                    $company->id,
                ];
            })
            ->groupBy(function (Company $company) {

                return $company->company_identity_id
                    ? 'identity:' . $company->company_identity_id
                    : 'company:' . $company->id;
            })
            ->map(function (Collection $group) {

                return $group->first();
            })
            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Candidate Scoring
     * --------------------------------------------------------------------------
     */
    protected function scoreCandidate(
        Company $company,
        Company $candidate,
        string $category,
    ): array {

        $signals = [];

        /*
        |--------------------------------------------------------------------------
        | Category Relevance
        |--------------------------------------------------------------------------
        */

        $categoryScore = $this->categoryScore(
            $candidate,
            $category,
            $signals
        );

        /*
        |--------------------------------------------------------------------------
        | Product Compatibility
        |--------------------------------------------------------------------------
        */

        $productScore = $this->productCompatibility(
            $company,
            $candidate,
            $signals
        );

        /*
        |--------------------------------------------------------------------------
        | Capability Compatibility
        |--------------------------------------------------------------------------
        */

        $capabilityScore = $this->capabilityCompatibility(
            $company,
            $candidate,
            $signals
        );

        /*
        |--------------------------------------------------------------------------
        | Market Compatibility
        |--------------------------------------------------------------------------
        */

        $marketScore = $this->marketCompatibility(
            $company,
            $candidate,
            $signals
        );

        /*
        |--------------------------------------------------------------------------
        | Geographic Compatibility
        |--------------------------------------------------------------------------
        */

        $locationScore = $this->locationCompatibility(
            $company,
            $candidate,
            $signals
        );

        /*
        |--------------------------------------------------------------------------
        | Readiness
        |--------------------------------------------------------------------------
        */

        $readinessScore = $this->readinessScore(
            $candidate,
            $signals
        );

        /*
        |--------------------------------------------------------------------------
        | Weighted Score
        |--------------------------------------------------------------------------
        */

        $score = round(
            ($categoryScore * 0.35) +
            ($productScore * 0.20) +
            ($capabilityScore * 0.20) +
            ($marketScore * 0.10) +
            ($locationScore * 0.05) +
            ($readinessScore * 0.10)
        );

        $score = max(0, min(100, $score));

        /*
        |--------------------------------------------------------------------------
        | Fallback Reason
        |--------------------------------------------------------------------------
        */

        if ($signals === []) {

            $signals[] =
                'Potential business ecosystem compatibility';
        }

        /*
        |--------------------------------------------------------------------------
        | Matching Level
        |--------------------------------------------------------------------------
        */

        $level = match (true) {
            $score >= 90 => 'Excellent Match',
            $score >= 80 => 'Strong Match',
            $score >= 65 => 'Good Match',
            default => 'Potential Match',
        };

        return [

            'company_id' =>
                $candidate->id,

            'company_name' =>
                $candidate->nama_perusahaan,

            'membership' =>
                $candidate->membership_type,

            'country' =>
                $candidate->country_name,

            'city' =>
                $candidate->city,

            'matching_score' =>
                $score,

            'matching_level' =>
                $level,

            'matching_reasons' =>
                array_values(
                    array_unique(
                        array_slice($signals, 0, 4)
                    )
                ),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Category Score
     * --------------------------------------------------------------------------
     */
    protected function categoryScore(
        Company $candidate,
        string $category,
        array &$signals
    ): int {

        $text = strtolower(
            implode(' ', [
                (string) $candidate->nama_perusahaan,
                (string) $candidate->sektor,
                (string) $candidate->company_role,
                (string) $candidate->company_type,
                (string) $candidate->produk,
            ])
        );

        $keywords = $this->categoryKeywords($category);

        $hits = 0;

        foreach ($keywords as $keyword) {

            if (str_contains($text, strtolower($keyword))) {
                $hits++;
            }
        }

        if ($hits === 0) {
            return 20;
        }

        $score = min(
            100,
            40 + ($hits * 15)
        );

        if ($score >= 70) {

            $signals[] =
                $this->categoryReason($category);
        }

        return $score;
    }

    /**
     * --------------------------------------------------------------------------
     * Category Keywords
     * --------------------------------------------------------------------------
     */
    protected function categoryKeywords(
        string $category
    ): array {

        return match (strtolower($category)) {

            'machinery',
            'machine',
            'equipment' => [
                'machine',
                'machinery',
                'equipment',
                'mesin',
                'spinning',
                'weaving',
                'knitting',
                'dyeing',
                'printing',
                'finishing',
                'textile machinery',
            ],

            'laboratory',
            'lab' => [
                'laboratory',
                'lab',
                'testing',
                'test',
                'quality',
                'inspection',
                'testing service',
                'textile testing',
            ],

            'logistics' => [
                'logistics',
                'logistic',
                'freight',
                'forwarding',
                'shipping',
                'transport',
                'export',
                'import',
                'port',
            ],

            'warehouse',
            'warehousing' => [
                'warehouse',
                'warehousing',
                'storage',
                'distribution',
                'logistics',
                'depot',
                'fulfillment',
            ],

            default => [
                $category,
            ],
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Category Reason
     * --------------------------------------------------------------------------
     */
    protected function categoryReason(
        string $category
    ): string {

        return match (strtolower($category)) {

            'machinery',
            'machine',
            'equipment' =>
                'Relevant machinery and equipment capability',

            'laboratory',
            'lab' =>
                'Relevant laboratory and testing capability',

            'logistics' =>
                'Relevant logistics and international trade capability',

            'warehouse',
            'warehousing' =>
                'Relevant warehousing and distribution capability',

            default =>
                'Relevant business capability',
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Product Compatibility
     * --------------------------------------------------------------------------
     */
    protected function productCompatibility(
        Company $company,
        Company $candidate,
        array &$signals
    ): int {

        $targetProducts = $this->productTokens($company);

        $candidateProducts = $this->productTokens($candidate);

        if ($targetProducts === [] || $candidateProducts === []) {
            return 40;
        }

        $intersection = array_intersect(
            $targetProducts,
            $candidateProducts
        );

        if ($intersection === []) {
            return 30;
        }

        $ratio = count($intersection) /
            max(1, min(
                count($targetProducts),
                count($candidateProducts)
            ));

        $score = min(
            100,
            50 + (int) round($ratio * 50)
        );

        if ($score >= 65) {

            $signals[] =
                'Product and business capability compatibility';
        }

        return $score;
    }

    /**
     * --------------------------------------------------------------------------
     * Product Tokens
     * --------------------------------------------------------------------------
     */
    protected function productTokens(
        Company $company
    ): array {

        $tokens = collect();

        if ($company->relationLoaded('products')) {

            foreach ($company->products as $product) {

                foreach ([
                    $product->product_name ?? null,
                    $product->product_name_en ?? null,
                    $product->category ?? null,
                    $product->hs_code ?? null,
                ] as $value) {

                    $tokens = $tokens->merge(
                        $this->tokenize($value)
                    );
                }
            }
        }

        $tokens = $tokens->merge(
            $this->tokenize($company->produk)
        );

        return $tokens
            ->filter(fn ($token) => strlen($token) >= 3)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Capability Compatibility
     * --------------------------------------------------------------------------
     */
    protected function capabilityCompatibility(
        Company $company,
        Company $candidate,
        array &$signals
    ): int {

        $target = $this->capabilityTokens($company);

        $candidateCapabilities =
            $this->capabilityTokens($candidate);

        if ($target === [] || $candidateCapabilities === []) {
            return 45;
        }

        $intersection = array_intersect(
            $target,
            $candidateCapabilities
        );

        if ($intersection === []) {
            return 35;
        }

        $score = min(
            100,
            55 + (count($intersection) * 10)
        );

        if ($score >= 65) {

            $signals[] =
                'Compatible production capabilities';
        }

        return $score;
    }

    /**
     * --------------------------------------------------------------------------
     * Capability Tokens
     * --------------------------------------------------------------------------
     */
    protected function capabilityTokens(
        Company $company
    ): array {

        $tokens = collect();

        if ($company->relationLoaded('machines')) {

            foreach ($company->machines as $machine) {

                foreach ([
                    $machine->machine_category ?? null,
                    $machine->machine_brand ?? null,
                    $machine->machine_model ?? null,
                ] as $value) {

                    $tokens = $tokens->merge(
                        $this->tokenize($value)
                    );
                }
            }
        }

        if ($company->relationLoaded('capacities')) {

            foreach ($company->capacities as $capacity) {

                foreach ([
                    $capacity->item_name ?? null,
                    $capacity->capacity_type ?? null,
                ] as $value) {

                    $tokens = $tokens->merge(
                        $this->tokenize($value)
                    );
                }
            }
        }

        $tokens = $tokens->merge(
            $this->tokenize($company->sektor)
        );

        $tokens = $tokens->merge(
            $this->tokenize($company->company_role)
        );

        return $tokens
            ->filter(fn ($token) => strlen($token) >= 3)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Market Compatibility
     * --------------------------------------------------------------------------
     */
    protected function marketCompatibility(
        Company $company,
        Company $candidate,
        array &$signals
    ): int {

        $targetMarkets =
            $this->marketTokens($company);

        $candidateMarkets =
            $this->marketTokens($candidate);

        if ($targetMarkets === [] || $candidateMarkets === []) {
            return 45;
        }

        $intersection = array_intersect(
            $targetMarkets,
            $candidateMarkets
        );

        if ($intersection === []) {
            return 35;
        }

        $score = min(
            100,
            55 + (count($intersection) * 10)
        );

        if ($score >= 65) {

            $signals[] =
                'Compatible export and market coverage';
        }

        return $score;
    }

    /**
     * --------------------------------------------------------------------------
     * Market Tokens
     * --------------------------------------------------------------------------
     */
    protected function marketTokens(
        Company $company
    ): array {

        $tokens = collect();

        $tokens = $tokens->merge(
            $this->tokenize($company->pasar_ekspor)
        );

        if ($company->relationLoaded('markets')) {

            foreach ($company->markets as $market) {

                foreach ([
                    $market->country_name ?? null,
                    $market->region ?? null,
                ] as $value) {

                    $tokens = $tokens->merge(
                        $this->tokenize($value)
                    );
                }
            }
        }

        return $tokens
            ->filter(fn ($token) => strlen($token) >= 3)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Location Compatibility
     * --------------------------------------------------------------------------
     */
    protected function locationCompatibility(
        Company $company,
        Company $candidate,
        array &$signals
    ): int {

        $targetCity =
            strtolower(trim((string) $company->city));

        $candidateCity =
            strtolower(trim((string) $candidate->city));

        if (
            $targetCity !== '' &&
            $candidateCity !== '' &&
            $targetCity === $candidateCity
        ) {

            $signals[] =
                'Located in the same industrial area';

            return 100;
        }

        $targetCountry =
            strtolower(trim((string) $company->country_name));

        $candidateCountry =
            strtolower(trim((string) $candidate->country_name));

        if (
            $targetCountry !== '' &&
            $candidateCountry !== '' &&
            $targetCountry === $candidateCountry
        ) {

            return 75;
        }

        return 40;
    }

    /**
     * --------------------------------------------------------------------------
     * Readiness Score
     * --------------------------------------------------------------------------
     */
    protected function readinessScore(
        Company $candidate,
        array &$signals
    ): int {

        $score = 0;

        if ($candidate->nama_perusahaan) {
            $score += 20;
        }

        if ($candidate->city) {
            $score += 15;
        }

        if ($candidate->country_name) {
            $score += 15;
        }

        if ($candidate->produk) {
            $score += 15;
        }

        if ($candidate->sektor) {
            $score += 10;
        }

        if ($candidate->pasar_ekspor) {
            $score += 10;
        }

        if ($candidate->relationLoaded('products') &&
            $candidate->products->isNotEmpty()) {

            $score += 5;
        }

        if ($candidate->relationLoaded('certifications') &&
            $candidate->certifications->isNotEmpty()) {

            $score += 5;
        }

        if ($score >= 70) {

            $signals[] =
                'Strong company profile readiness';
        }

        return min(100, $score);
    }

    /**
     * --------------------------------------------------------------------------
     * Tokenizer
     * --------------------------------------------------------------------------
     */
    protected function tokenize(
        mixed $value
    ): array {

        if ($value === null) {
            return [];
        }

        $text = strtolower(
            strip_tags((string) $value)
        );

        $text = preg_replace(
            '/[^a-z0-9]+/i',
            ' ',
            $text
        );

        return collect(
            preg_split(
                '/\s+/',
                trim($text)
            ) ?: []
        )
            ->filter(fn ($token) =>
                strlen($token) >= 3
            )
            ->values()
            ->all();
    }
}