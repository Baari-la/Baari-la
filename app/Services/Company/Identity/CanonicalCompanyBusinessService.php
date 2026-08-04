<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\CompanyClaim;
use App\Models\CompanyIdentity;
use App\Models\User;

class CanonicalCompanyBusinessService
{
    /*
    |--------------------------------------------------------------------------
    | Build From User
    |--------------------------------------------------------------------------
    */

    public function buildFromUser(User $user): ?array
    {
        if (!$user->company_identity_id) {
            return null;
        }

        return $this->buildFromIdentity(
            $user->company_identity_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Build From Claim
    |--------------------------------------------------------------------------
    */

    public function buildFromClaim(
        CompanyClaim $claim
    ): ?array {

        if (!$claim->company_identity_id) {
            return null;
        }

        return $this->buildFromIdentity(
            $claim->company_identity_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Build From Identity
    |--------------------------------------------------------------------------
    */

    public function buildFromIdentity(
        int|CompanyIdentity $identity
    ): ?array {

        if (!$identity instanceof CompanyIdentity) {

            $identity = CompanyIdentity::query()
                ->with('business')
                ->find($identity);
        }

        if (!$identity) {
            return null;
        }

        return $this->build(
            $identity
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Build
    |--------------------------------------------------------------------------
    */

    public function build(
        CompanyIdentity $identity
    ): array {

        $business = $identity->business;

        return [

            'record_type' =>
                'company_business',

            'company_identity_id' =>
                $identity->id,

            'profile_exists' =>
                $business !== null,

            /*
            |--------------------------------------------------------------------------
            | Company Overview
            |--------------------------------------------------------------------------
            */

            'business_description' =>
                $business?->business_description,

            'year_established' =>
                $business?->year_established,

            'legal_entity' =>
                $business?->legal_entity,

            'employee_range' =>
                $business?->employee_range,

            'factory_count' =>
                $business?->factory_count,

            /*
            |--------------------------------------------------------------------------
            | Business Model
            |--------------------------------------------------------------------------
            */

            'is_fiber_producer' =>
                $business?->is_fiber_producer ?? false,

            'is_spinner' =>
                $business?->is_spinner ?? false,

            'is_weaving' =>
                $business?->is_weaving ?? false,

            'is_knitting' =>
                $business?->is_knitting ?? false,

            'is_dyeing_finishing' =>
                $business?->is_dyeing_finishing ?? false,

            'is_printing' =>
                $business?->is_printing ?? false,

            'is_garment' =>
                $business?->is_garment ?? false,

            'is_trader' =>
                $business?->is_trader ?? false,

            'is_brand' =>
                $business?->is_brand ?? false,

            'is_buying_office' =>
                $business?->is_buying_office ?? false,

             'is_testing_laboratory' => $business?->is_testing_laboratory ?? false,

            'is_certification_body' => $business?->is_certification_body ?? false,

            'is_machinery_supplier' => $business?->is_machinery_supplier ?? false,

            'is_accessories_supplier' => $business?->is_accessories_supplier ?? false,

            'is_chemical_supplier' => $business?->is_chemical_supplier ?? false,   

            /*
            |--------------------------------------------------------------------------
            | Strategy
            |--------------------------------------------------------------------------
            */

            'oem' =>
                $business?->oem ?? false,

            'odm' =>
                $business?->odm ?? false,

            'obm' =>
                $business?->obm ?? false,

            'private_label' =>
                $business?->private_label ?? false,

            /*
            |--------------------------------------------------------------------------
            | Market
            |--------------------------------------------------------------------------
            */

            'domestic_market' =>
                $business?->domestic_market ?? true,

            'export_market' =>
                $business?->export_market ?? false,

            'export_experience_years' =>
                $business?->export_experience_years,

            /*
            |--------------------------------------------------------------------------
            | Sustainability
            |--------------------------------------------------------------------------
            */

            'esg_program' =>
                $business?->esg_program ?? false,

            'renewable_energy' =>
                $business?->renewable_energy ?? false,

            'recycled_material' =>
                $business?->recycled_material ?? false,

            'wastewater_treatment' =>
                $business?->wastewater_treatment ?? false,

            'sustainability_notes' =>
                $business?->sustainability_notes,

        ];
    }
}