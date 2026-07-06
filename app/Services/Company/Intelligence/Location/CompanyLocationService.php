<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence\Location;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Location Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Manage standardized geographic and logistics information
 * for the Digital Company Passport.
 *
 * Company Location provides geographical intelligence used by
 * Supply Chain Intelligence, Trade Intelligence and
 * Business Opportunity Engine.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * • Geographic Information
 * • Factory Location
 * • Logistics Accessibility
 * • Regional Classification
 *
 * This service NEVER:
 *
 * • Calculates logistics cost
 * • Performs route optimization
 * • Generates AI recommendations
 *
 * Used by:
 *
 * - DigitalCompanyPassportService
 * - Supply Chain Intelligence
 * - Matching Intelligence
 * - Executive Dashboard
 */
class CompanyLocationService
{
    /**
     * --------------------------------------------------------------------------
     * Geographic Information
     * --------------------------------------------------------------------------
     */
    public function geography(Company $company): array
    {
        return [

            'country' => 'Indonesia',

            'province' => $company->wilayah,

            'city' => $company->city,

            'district' => null,

            'postal_code' => null,

            'latitude' => null,

            'longitude' => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Address Information
     * --------------------------------------------------------------------------
     */
    public function address(Company $company): array
    {
        return [

            'head_office' => $company->alamat_lengkap,

            'factory_address' => null,

            'registered_address' => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Industrial Zone
     * --------------------------------------------------------------------------
     */
    public function industrialZone(): array
    {
        return [

            'industrial_estate' => null,

            'economic_zone' => null,

            'free_trade_zone' => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Logistics Access
     * --------------------------------------------------------------------------
     */
    public function logistics(): array
    {
        return [

            'nearest_port' => null,

            'nearest_airport' => null,

            'nearest_toll_road' => null,

            'nearest_rail_terminal' => null,

            'container_access' => true,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Regional Intelligence
     * --------------------------------------------------------------------------
     */
    public function regional(): array
    {
        return [

            'manufacturing_cluster' => null,

            'textile_cluster' => null,

            'labor_availability' => null,

            'infrastructure_score' => null,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Location Summary
     * --------------------------------------------------------------------------
     */
    public function summary(Company $company): array
    {
        return [

            'geography' => $this->geography($company),

            'address' => $this->address($company),

            'industrial_zone' => $this->industrialZone(),

            'logistics' => $this->logistics(),

            'regional' => $this->regional(),

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

            'framework' => 'Company Location',

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Location Passport
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