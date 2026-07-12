<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders;

use App\Models\Company;
use App\Services\Knowledge\KnowledgeNode;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Company Node Builder
 * ==========================================================================
 *
 * Responsible for creating Company Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class CompanyNodeBuilder
{
    /**
     * Build Company Node.
     */
    public function build(Company $company): KnowledgeNode
    {
        return new KnowledgeNode(

            id: $company->id,

            type: 'company',

            label: $company->nama_perusahaan,

            attributes: [

                /*
                |--------------------------------------------------------------------------
                | Identity
                |--------------------------------------------------------------------------
                */

                'slug' => $company->slug,

                'company_code' => $company->company_code,

                'membership_type' => $company->membership_type,

                'verification_status' => $company->status_verifikasi,

                /*
                |--------------------------------------------------------------------------
                | Classification
                |--------------------------------------------------------------------------
                */

                'category' => $company->category,

                'business_role' => $company->business_role,

                'industry_segment' => $company->industry_segment,

                'business_ecosystem' => $company->business_ecosystem,

                /*
                |--------------------------------------------------------------------------
                | Location
                |--------------------------------------------------------------------------
                */

                'country_code' => $company->country_code,

                'province' => $company->province,

                'city' => $company->city,

                /*
                |--------------------------------------------------------------------------
                | Business
                |--------------------------------------------------------------------------
                */

                'established_year' => $company->established_year,

                'employee_count' => $company->tenaga_kerja,

                'website' => $company->website,

                /*
                |--------------------------------------------------------------------------
                | Contact
                |--------------------------------------------------------------------------
                */

                'email' => $company->email,

                'phone' => $company->telepon,

                /*
                |--------------------------------------------------------------------------
                | Executive Intelligence
                |--------------------------------------------------------------------------
                */

                'executive_score' => $company->executive_score,

                'readiness_score' => $company->readiness_score,

                'capability_score' => $company->capability_score,

                'compliance_score' => $company->compliance_score,

                'market_score' => $company->market_score,

                'sustainability_score' => $company->sustainability_score,

            ]

        );
    }
}