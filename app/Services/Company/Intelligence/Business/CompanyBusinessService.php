<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence\Business;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Business Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Manage standardized business intelligence describing the
 * commercial activities of a company.
 *
 * Company Business is one of the core sections of the
 * Digital Company Passport.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * • Business Classification
 * • Products & Services
 * • Manufacturing Model
 * • Commercial Capability
 * • Market Focus
 *
 * This service NEVER:
 *
 * • Calculates readiness scores
 * • Performs matching
 * • Evaluates compliance
 * • Generates AI recommendations
 *
 * Used by:
 *
 * - DigitalCompanyPassportService
 * - Capability Intelligence
 * - Matching Intelligence
 * - Business Opportunity Engine
 */
class CompanyBusinessService
{
    /**
     * --------------------------------------------------------------------------
     * Business Classification
     * --------------------------------------------------------------------------
     */
    public function classification(Company $company): array
    {
        return [

            'industry_sector'      => $company->sektor,

            'business_category'    => $company->category,

            'company_type'         => null,

            'sub_sector'           => null,

            'hs_code_focus'        => [],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Products
     * --------------------------------------------------------------------------
     */
    public function products(Company $company): array
    {
        return [

            'main_products'        => $company->produk,

            'product_categories'   => [],

            'product_count'        => 0,

            'custom_products'      => true,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Manufacturing Model
     * --------------------------------------------------------------------------
     */
    public function manufacturing(Company $company): array
    {
        return [

            'oem'                  => false,

            'odm'                  => false,

            'obm'                  => false,

            'private_label'        => false,

            'contract_manufacturing' => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Commercial Capability
     * --------------------------------------------------------------------------
     */
    public function commercial(Company $company): array
    {
        return [

            'export_market'        => $company->pasar_ekspor,

            'minimum_order'        => null,

            'lead_time'            => null,

            'annual_capacity'      => null,

            'sampling_available'   => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Focus
     * --------------------------------------------------------------------------
     */
    public function businessFocus(Company $company): array
    {
        return [

            'domestic_market'      => true,

            'export_market'        => !empty($company->pasar_ekspor),

            'government_project'   => false,

            'brand_manufacturing'  => false,

            'retail_business'      => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Summary
     * --------------------------------------------------------------------------
     */
    public function summary(Company $company): array
    {
        return [

            'classification' => $this->classification($company),

            'products'       => $this->products($company),

            'manufacturing'  => $this->manufacturing($company),

            'commercial'     => $this->commercial($company),

            'business_focus' => $this->businessFocus($company),

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

            'framework'    => 'Company Business',

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Business Passport
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