<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Digital Company Passport Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for the Digital Company Passport.
 *
 * The Digital Company Passport is the official verified
 * business identity of a company within the DIGESTEX
 * Global Textile Intelligence Ecosystem.
 *
 * It consolidates company profile information into a
 * standardized enterprise format consumed by:
 *
 * • Company Intelligence
 * • Trade Intelligence
 * • Matching Intelligence
 * • Business Opportunity Engine
 * • Executive Dashboard
 * • Executive AI
 *
 * This service NEVER:
 *
 * • Calculates business scores
 * • Performs matching
 * • Generates AI recommendations
 * • Queries external systems
 */
class DigitalCompanyPassportService
{
    public function __construct(
        protected CompanyProfileService $profile,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Passport Metadata
     * --------------------------------------------------------------------------
     */
    public function metadata(): array
    {
        return [

            'passport' => 'Digital Company Passport',

            'framework' => 'Company Intelligence',

            'version' => '1.0',

            'status' => 'Enterprise',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Identity
     * --------------------------------------------------------------------------
     */
    public function identity(Company $company): array
    {
        return $this->profile->identity($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Business
     * --------------------------------------------------------------------------
     */
    public function business(Company $company): array
    {
        return $this->profile->business($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Contact
     * --------------------------------------------------------------------------
     */
    public function contact(Company $company): array
    {
        return $this->profile->contact($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Location
     * --------------------------------------------------------------------------
     */
    public function location(Company $company): array
    {
        return $this->profile->location($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Legal Entity
     * --------------------------------------------------------------------------
     */
    public function legal(Company $company): array
    {
        return [

            'status' => 'Available',

            'source' => 'Company Legal',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Factory
     * --------------------------------------------------------------------------
     */
    public function factory(Company $company): array
    {
        return [

            'status' => 'Available',

            'source' => 'Factory Intelligence',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Export Experience
     * --------------------------------------------------------------------------
     */
    public function export(Company $company): array
    {
        return [

            'status' => 'Available',

            'source' => 'Export Intelligence',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Membership
     * --------------------------------------------------------------------------
     */
    public function membership(Company $company): array
    {
        return $this->profile->membership($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Verification
     * --------------------------------------------------------------------------
     */
    public function verification(Company $company): array
    {
        return [

            'verified' => $company->status_verifikasi,

            'last_verified_at' => $company->last_verified_at,

            'framework' => 'Verification Intelligence',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Passport Sections
     * --------------------------------------------------------------------------
     */
    public function passport(Company $company): array
    {
        return [

            'identity' => $this->identity($company),

            'business' => $this->business($company),

            'contact' => $this->contact($company),

            'location' => $this->location($company),

            'legal' => $this->legal($company),

            'factory' => $this->factory($company),

            'export' => $this->export($company),

            'membership' => $this->membership($company),

            'verification' => $this->verification($company),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(Company $company): array
    {
        return [

            'company_id' => $company->id,

            'sections' => 9,

            'framework' => 'Digital Company Passport',

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Passport
     * --------------------------------------------------------------------------
     */
    public function all(Company $company): array
    {
        return [

            'metadata' => $this->metadata(),

            'passport' => $this->passport($company),

            'statistics' => $this->statistics($company),

        ];
    }
}