<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

use Illuminate\Support\Arr;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Knowledge Rules Service
 * ==========================================================================
 *
 * Centralized access to all Knowledge Rules.
 *
 * Responsibilities
 * ----------------
 * • Business Role Rules
 * • Business Ecosystem Rules
 * • Industry Segment Rules
 * • Technology Rules
 * • Certification Rules
 * • Sustainability Rules
 * • Market Rules
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • KnowledgeEvaluationService
 * • KnowledgeRecommendationService
 * • Executive AI
 *
 */

class KnowledgeRulesService
{
    /**
     * Cached configuration.
     */
    protected array $rules;

    public function __construct()
    {
        $this->rules = config('masterdata');
    }

    /*
    |--------------------------------------------------------------------------
    | Generic
    |--------------------------------------------------------------------------
    */

    public function get(string $path, mixed $default = []): mixed
    {
        return Arr::get($this->rules, $path, $default);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Role
    |--------------------------------------------------------------------------
    */

    public function forBusinessRole(string $role): array
    {
        return $this->get(
            "role_rules.{$role}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Business Ecosystem
    |--------------------------------------------------------------------------
    */

    public function forBusinessEcosystem(string $ecosystem): array
    {
        return $this->get(
            "business_ecosystem_rules.{$ecosystem}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Industry Segment
    |--------------------------------------------------------------------------
    */

    public function forIndustrySegment(string $segment): array
    {
        return $this->get(
            "industry_segment_rules.{$segment}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Technology
    |--------------------------------------------------------------------------
    */

    public function forTechnology(string $technology): array
    {
        return $this->get(
            "technology_rules.{$technology}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Machinery
    |--------------------------------------------------------------------------
    */

    public function forMachinery(string $machine): array
    {
        return $this->get(
            "machinery_rules.{$machine}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Certification
    |--------------------------------------------------------------------------
    */

    public function forCertification(string $certification): array
    {
        return $this->get(
            "certification_rules.{$certification}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Sustainability
    |--------------------------------------------------------------------------
    */

    public function forSustainability(string $tag): array
    {
        return $this->get(
            "sustainability_rules.{$tag}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Country
    |--------------------------------------------------------------------------
    */

    public function forCountry(string $country): array
    {
        return $this->get(
            "country_rules.{$country}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Region
    |--------------------------------------------------------------------------
    */

    public function forRegion(string $region): array
    {
        return $this->get(
            "region_rules.{$region}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Market
    |--------------------------------------------------------------------------
    */

    public function forMarket(string $market): array
    {
        return $this->get(
            "market_rules.{$market}",
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Convenience Helpers
    |--------------------------------------------------------------------------
    */

    public function technologies(string $role): array
    {
        return $this->forBusinessRole($role)['technologies'] ?? [];
    }

    public function machineries(string $role): array
    {
        return $this->forBusinessRole($role)['machineries'] ?? [];
    }

    public function certifications(string $role): array
    {
        return $this->forBusinessRole($role)['certifications'] ?? [];
    }

    public function sustainability(string $role): array
    {
        return $this->forBusinessRole($role)['sustainability'] ?? [];
    }

    public function products(string $role): array
    {
        return $this->forBusinessRole($role)['products'] ?? [];
    }

    public function markets(string $role): array
    {
        return $this->forBusinessRole($role)['markets'] ?? [];
    }

    /*
    |--------------------------------------------------------------------------
    | Exists
    |--------------------------------------------------------------------------
    */

    public function has(string $path): bool
    {
        return Arr::has($this->rules, $path);
    }

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        return $this->rules;
    }
}