<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Profile Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide standardized company profile intelligence used across
 * the DIGESTEX Global Textile Intelligence Ecosystem.
 *
 * Company Profile is the single source of truth (SSOT)
 * describing the identity, business profile and core
 * information of a company.
 *
 * Future implementation will retrieve data from Company model
 * and related profile tables.
 *
 * This service NEVER:
 *
 * • Calculates readiness score
 * • Performs matching
 * • Generates AI recommendation
 *
 * Used by:
 *
 * - Company Intelligence
 * - Trade Intelligence
 * - Matching Engine
 * - Business Opportunity
 * - Executive Dashboard
 */
class CompanyProfileService
{
    /**
     * --------------------------------------------------------------------------
     * Basic Identity
     * --------------------------------------------------------------------------
     */
    public function identity(Company $company): array
    {
        return [

            'company_id' => $company->id,

            'company_name' => $company->nama_perusahaan,

            'slug' => $company->slug,

            'membership_type' => $company->membership_type,

            'verification_status' => $company->status_verifikasi,

            'last_verified_at' => $company->last_verified_at,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Information
     * --------------------------------------------------------------------------
     */
    public function business(Company $company): array
    {
        return [

            'sector' => $company->sektor,

            'category' => $company->category,

            'products' => $company->produk,

            'export_market' => $company->pasar_ekspor,

            'employees' => $company->tenaga_kerja,

            'director' => $company->pimpinan,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Contact Information
     * --------------------------------------------------------------------------
     */
    public function contact(Company $company): array
    {
        return [

            'phone' => $company->telepon,

            'email' => $company->email_web,

            'address' => $company->alamat_lengkap,

            'city' => $company->city,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Geographic Information
     * --------------------------------------------------------------------------
     */
    public function location(Company $company): array
    {
        return [

            'city' => $company->city,

            'province' => $company->wilayah,

            'country' => 'Indonesia',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Membership
     * --------------------------------------------------------------------------
     */
    public function membership(Company $company): array
    {
        return [

            'membership_type' => $company->membership_type,

            'member_number' => $company->nomor_anggota,

            'verified' => $company->status_verifikasi,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(Company $company): array
    {
        return [

            'identity' => $this->identity($company),

            'business' => $this->business($company),

            'contact' => $this->contact($company),

            'location' => $this->location($company),

            'membership' => $this->membership($company),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Profile Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(Company $company): array
    {
        return [

            'framework' => 'Company Profile Intelligence',

            'company_id' => $company->id,

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Company Profile
     * --------------------------------------------------------------------------
     */
    public function all(Company $company): array
    {
        return [

            'summary' => $this->summary($company),

            'statistics' => $this->statistics($company),

        ];
    }
}