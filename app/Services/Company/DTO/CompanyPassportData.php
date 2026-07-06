<?php

declare(strict_types=1);

namespace App\Services\Company\DTO;

final readonly class CompanyPassportData
{
    public function __construct(
        public array $company,
        public array $statistics,
        public array $scores,
        public array $passports,
        public array $recommendations,
        public array $intelligence,
        public array $metadata,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Convert DTO to Array
     * --------------------------------------------------------------------------
     */
    public function toArray(): array
    {
        return [

            'company' => $this->company,
            'statistics' => $this->statistics,
            'scores' => $this->scores,
            'passports' => $this->passports,
            'recommendations' => $this->recommendations,
            'intelligence' => $this->intelligence,
            'metadata' => $this->metadata,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Score
     * --------------------------------------------------------------------------
     */
    public function executiveScore(): int
    {
        return (int) (
            $this->scores['overall'] ?? 0
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
            $this->company['company_name'] ?? ''
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
            $this->metadata['passport_id'] ?? ''
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
            $this->metadata['framework_version'] ?? ''
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Generated At
     * --------------------------------------------------------------------------
     */
    public function generatedAt(): ?string
    {
        return $this->metadata['generated_at'] ?? null;
    }

    /**
     * --------------------------------------------------------------------------
     * Check Recommendation Availability
     * --------------------------------------------------------------------------
     */
    public function hasRecommendations(): bool
    {
        return !empty(
            $this->recommendations
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Check Market Intelligence
     * --------------------------------------------------------------------------
     */
    public function hasMarketIntelligence(): bool
    {
        return !empty(
            $this->intelligence['market'] ?? []
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Check Matching Intelligence
     * --------------------------------------------------------------------------
     */
    public function hasMatchingIntelligence(): bool
    {
        return !empty(
            $this->intelligence['matching'] ?? []
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Check Opportunity Intelligence
     * --------------------------------------------------------------------------
     */
    public function hasOpportunityIntelligence(): bool
    {
        return !empty(
            $this->intelligence['opportunities'] ?? []
        );
    }
}