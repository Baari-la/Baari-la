<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence\Contact;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Contact Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Manage standardized business contact information used by the
 * Digital Company Passport.
 *
 * Company Contact provides structured communication channels
 * for customers, buyers, suppliers, investors and ecosystem
 * partners.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * • Corporate Contact
 * • Department Contact
 * • Digital Contact
 * • Emergency Contact
 *
 * This service NEVER:
 *
 * • Calculates business scores
 * • Performs matching
 * • Generates AI recommendations
 *
 * Used by:
 *
 * - DigitalCompanyPassportService
 * - Executive Dashboard
 * - Business Opportunity Engine
 * - Company Intelligence
 */
class CompanyContactService
{
    /**
     * --------------------------------------------------------------------------
     * Corporate Contact
     * --------------------------------------------------------------------------
     */
    public function corporate(Company $company): array
    {
        return [

            'telephone' => $company->telepon,

            'email' => $company->email_web,

            'website' => null,

            'head_office' => $company->alamat_lengkap,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Departments
     * --------------------------------------------------------------------------
     */
    public function departments(Company $company): array
    {
        return [

            'management' => [

                'contact_person' => $company->pimpinan,

                'position' => 'Managing Director',

            ],

            'sales' => null,

            'export' => null,

            'marketing' => null,

            'procurement' => null,

            'production' => null,

            'quality' => null,

            'technical' => null,

            'finance' => null,

            'customer_service' => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Digital Contact
     * --------------------------------------------------------------------------
     */
    public function digital(): array
    {
        return [

            'website' => null,

            'linkedin' => null,

            'facebook' => null,

            'instagram' => null,

            'youtube' => null,

            'wechat' => null,

            'whatsapp' => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Communication Channels
     * --------------------------------------------------------------------------
     */
    public function communication(): array
    {
        return [

            'email_support' => true,

            'phone_support' => true,

            'video_meeting' => false,

            'factory_visit' => true,

            'online_catalog' => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Availability
     * --------------------------------------------------------------------------
     */
    public function availability(): array
    {
        return [

            'business_hours' => null,

            'timezone' => 'Asia/Jakarta',

            'preferred_language' => [

                'Indonesian',

                'English',

            ],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Contact Summary
     * --------------------------------------------------------------------------
     */
    public function summary(Company $company): array
    {
        return [

            'corporate' => $this->corporate($company),

            'departments' => $this->departments($company),

            'digital' => $this->digital(),

            'communication' => $this->communication(),

            'availability' => $this->availability(),

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

            'sections' => 5,

            'framework' => 'Company Contact',

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Contact Passport
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