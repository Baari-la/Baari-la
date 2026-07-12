<?php

declare(strict_types=1);

namespace App\Services\Company\DTO;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Passport Data Transfer Object
 * ==========================================================================
 *
 * Single data contract between Backend and Frontend.
 *
 * Consumed by:
 *
 * • Digital Company Passport
 * • Executive Dashboard
 * • Smart Business Matching™
 * • Build My Supply Chain™
 * • Buyer Discovery
 * • Executive AI
 * • REST API
 * • Mobile Apps
 *
 * Version:
 * DIGESTEX Intelligence Framework v2.0
 */
final readonly class CompanyPassportData
{
    public function __construct(

        /*
        |--------------------------------------------------------------------------
        | Passport
        |--------------------------------------------------------------------------
        */

        public array $metadata,

        public array $summary,

        public array $passport,

        /*
        |--------------------------------------------------------------------------
        | Business Intelligence
        |--------------------------------------------------------------------------
        */

        public ?string $role,

        public array $ecosystem,

        public array $business_needs,

        public array $matching,

        /*
|--------------------------------------------------------------------------
| Build My Supply Chain
|--------------------------------------------------------------------------
*/

public array $build_supply_chain,

/*
|--------------------------------------------------------------------------
| Executive Intelligence
|--------------------------------------------------------------------------
*/

public array $scores,

public array $recommendations,

        /*
        |--------------------------------------------------------------------------
        | Executive Intelligence
        |--------------------------------------------------------------------------
        */

       
        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        public array $statistics,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Create DTO From Array
     * --------------------------------------------------------------------------
     */
    public static function fromArray(array $data): self
    {
        return new self(

            /*
            |--------------------------------------------------------------------------
            | Passport
            |--------------------------------------------------------------------------
            */

            metadata: $data['metadata'] ?? [],

            summary: $data['summary'] ?? [],

            passport: $data['passport'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Business Intelligence
            |--------------------------------------------------------------------------
            */

            role: $data['role'] ?? null,

            ecosystem: $data['ecosystem'] ?? [],

            business_needs: $data['business_needs'] ?? [],

            matching: $data['matching'] ?? [],

            build_supply_chain:
            $data['build_supply_chain'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Executive Intelligence
            |--------------------------------------------------------------------------
            */

            scores: $data['scores'] ?? [],

            recommendations: $data['recommendations'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            statistics: $data['statistics'] ?? [],

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Convert DTO To Array
     * --------------------------------------------------------------------------
     */
    public function toArray(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Passport
            |--------------------------------------------------------------------------
            */

            'metadata' => $this->metadata,

            'summary' => $this->summary,

            'passport' => $this->passport,

            /*
            |--------------------------------------------------------------------------
            | Business Intelligence
            |--------------------------------------------------------------------------
            */

            'role' => $this->role,

            'ecosystem' => $this->ecosystem,

            'business_needs' => $this->business_needs,

            'matching' => $this->matching,
            'build_supply_chain' => $this->build_supply_chain,
            /*
            |--------------------------------------------------------------------------
            | Executive Intelligence
            |--------------------------------------------------------------------------
            */

            'scores' => $this->scores,

            'recommendations' => $this->recommendations,

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'statistics' => $this->statistics,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Score
     * --------------------------------------------------------------------------
     */
    public function executiveScore(): float
    {
        return (float) (

            $this->scores['score']['overall']

            ?? 0

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Company Name
     * --------------------------------------------------------------------------
     */
    public function companyName(): string
    {
        return (string) (

            $this->summary['company_name']

            ?? ''

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Company ID
     * --------------------------------------------------------------------------
     */
    public function companyId(): int
    {
        return (int) (

            $this->summary['company_id']

            ?? 0

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Passport ID
     * --------------------------------------------------------------------------
     */
    public function passportId(): string
    {
        return (string) (

            $this->metadata['passport_id']

            ?? ''

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Framework Version
     * --------------------------------------------------------------------------
     */
    public function frameworkVersion(): string
    {
        return (string) (

            $this->metadata['framework_version']

            ?? ''

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Generated At
     * --------------------------------------------------------------------------
     */
    public function generatedAt(): ?string
    {
        return

            $this->metadata['generated_at']

            ?? null;
    }

    /**
     * --------------------------------------------------------------------------
     * Has Recommendations
     * --------------------------------------------------------------------------
     */
    public function hasRecommendations(): bool
    {
        return ! empty($this->recommendations);
    }

    /**
     * --------------------------------------------------------------------------
     * Has Smart Business Matching
     * --------------------------------------------------------------------------
     */
    public function hasMatching(): bool
    {
        return ! empty($this->matching);
    }

    /**
     * --------------------------------------------------------------------------
     * Business Role
     * --------------------------------------------------------------------------
     */
    public function businessRole(): ?string
    {
        return $this->role;
    }

    /**
     * --------------------------------------------------------------------------
     * Ecosystem Categories
     * --------------------------------------------------------------------------
     */
    public function ecosystemCategories(): array
    {
        return $this->ecosystem;
    }

    /**
     * --------------------------------------------------------------------------
     * Business Needs
     * --------------------------------------------------------------------------
     */
    public function businessNeeds(): array
    {
        return $this->business_needs;
    }
/**
 * --------------------------------------------------------------------------
 * Build My Supply Chain
 * --------------------------------------------------------------------------
 */
public function buildSupplyChain(): array
{
    return $this->build_supply_chain;
}
    
}