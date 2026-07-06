<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence\Legal;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Legal Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Manage standardized legal entity information for the
 * Digital Company Passport.
 *
 * Company Legal provides verified legal identity and
 * regulatory information required by buyers, brands,
 * financial institutions and business partners.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * • Legal Entity
 * • Business Registration
 * • Business Licenses
 * • Export & Import Licenses
 * • Corporate Compliance
 *
 * This service NEVER:
 *
 * • Calculates legal risk
 * • Performs compliance audits
 * • Validates government documents
 * • Generates AI recommendations
 *
 * Used by:
 *
 * - DigitalCompanyPassportService
 * - Compliance Intelligence
 * - Executive Dashboard
 * - Business Opportunity Engine
 */
class CompanyLegalService
{
    /**
     * --------------------------------------------------------------------------
     * Legal Entity
     * --------------------------------------------------------------------------
     */
    public function legalEntity(Company $company): array
    {
        return [

            'legal_name'           => $company->nama_perusahaan,

            'business_entity'      => null,

            'year_established'     => null,

            'country_of_incorporation' => 'Indonesia',

            'ownership_type'       => null,

            'corporate_group'      => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Registration
     * --------------------------------------------------------------------------
     */
    public function registration(): array
    {
        return [

            'nib'                  => null,

            'npwp'                 => null,

            'company_registration' => null,

            'business_license'     => null,

            'tax_status'           => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Export & Import Licenses
     * --------------------------------------------------------------------------
     */
    public function tradeLicenses(): array
    {
        return [

            'export_license'       => null,

            'import_license'       => null,

            'customs_registration' => null,

            'authorized_exporter'  => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * International Business Identifiers
     * --------------------------------------------------------------------------
     */
    public function international(): array
    {
        return [

            'duns_number'          => null,

            'lei'                  => null,

            'gs1_company_prefix'   => null,

            'exporter_id'          => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Corporate Governance
     * --------------------------------------------------------------------------
     */
    public function governance(): array
    {
        return [

            'legal_status'         => 'Active',

            'bankruptcy_status'    => false,

            'litigation_status'    => null,

            'insurance_available'  => null,

            'last_updated'         => now()->toDateString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Legal Summary
     * --------------------------------------------------------------------------
     */
    public function summary(Company $company): array
    {
        return [

            'legal_entity'     => $this->legalEntity($company),

            'registration'     => $this->registration(),

            'trade_licenses'   => $this->tradeLicenses(),

            'international'    => $this->international(),

            'governance'       => $this->governance(),

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

            'company_id'   => $company->id,

            'sections'     => 5,

            'framework'    => 'Company Legal',

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Legal Passport
     * --------------------------------------------------------------------------
     */
    public function all(Company $company): array
    {
        return [

            'summary'    => $this->summary($company),

            'statistics' => $this->statistics($company),

        ];
    }
}