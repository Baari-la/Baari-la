<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence\Identity;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Identity Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Manage the official corporate identity of a company.
 *
 * Company Identity is the foundation of the Digital Company Passport
 * and serves as the primary reference for all Company Intelligence.
 *
 * Responsibilities:
 *
 * • Corporate Identity
 * • Brand Identity
 * • Membership Identity
 * • Verification Identity
 *
 * This service NEVER:
 *
 * • Calculates business scores
 * • Performs matching
 * • Evaluates compliance
 * • Generates AI recommendations
 *
 * Used by:
 *
 * - DigitalCompanyPassportService
 * - Company Intelligence
 * - Executive Dashboard
 */
class CompanyIdentityService
{
    /**
     * --------------------------------------------------------------------------
     * Corporate Identity
     * --------------------------------------------------------------------------
     */
    public function corporate(Company $company): array
    {
        return [

            'company_id' => $company->id,

            'company_name' => $company->nama_perusahaan,

            'slug' => $company->slug,

            'logo' => $company->photo_url,

            'business_type' => $company->category,

            'industry_sector' => $company->sektor,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Brand Identity
     * --------------------------------------------------------------------------
     */
    public function brand(Company $company): array
    {
        return [

            'primary_brand' => null,

            'secondary_brands' => [],

            'private_label' => false,

            'oem' => false,

            'odm' => false,

            'obm' => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Membership Identity
     * --------------------------------------------------------------------------
     */
    public function membership(Company $company): array
    {
        return [

            'membership_type' => $company->membership_type,

            'membership_number' => $company->nomor_anggota,

            'status' => $company->membership_type
                ? 'Active'
                : 'Non Member',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Verification Identity
     * --------------------------------------------------------------------------
     */
    public function verification(Company $company): array
    {
        return [

            'verification_status' => $company->status_verifikasi,

            'last_verified_at' => $company->last_verified_at,

            'verified' => !empty($company->status_verifikasi),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Passport Identity
     * --------------------------------------------------------------------------
     */
    public function passport(Company $company): array
    {
        return [

            'corporate' => $this->corporate($company),

            'brand' => $this->brand($company),

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

            'sections' => 4,

            'framework' => 'Company Identity',

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Identity
     * --------------------------------------------------------------------------
     */
    public function all(Company $company): array
    {
        return [

            'passport' => $this->passport($company),

            'statistics' => $this->statistics($company),

        ];
    }
}