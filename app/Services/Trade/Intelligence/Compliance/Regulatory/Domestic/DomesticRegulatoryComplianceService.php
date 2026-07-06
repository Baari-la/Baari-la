<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Regulatory\Domestic;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Domestic Regulatory Compliance Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable knowledge of domestic regulatory compliance
 * applicable to textile and apparel manufacturers.
 *
 * Domestic regulations ensure that suppliers legally operate
 * within their home country before entering global markets.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Is the company legally established?
 * • Does the company comply with labor regulations?
 * • Does the company comply with environmental regulations?
 * • Is the company eligible to export?
 * • Does the company operate under applicable industrial laws?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve legal readiness
 * • Reduce regulatory risks
 * • Prepare for buyer audits
 *
 * Buyer / Brand
 *
 * • Verify supplier legal compliance
 * • Reduce sourcing risks
 *
 * Industry
 *
 * • Promote responsible manufacturing
 * • Improve industrial transparency
 *
 * This service NEVER performs:
 *
 * • Database Query
 * • Company Validation
 * • Analytics
 * • Matching
 * • Recommendation
 *
 * Used by:
 *
 * - RegulatoryOrchestrator
 * - ComplianceOrchestrator
 * - Supplier Readiness Intelligence
 * - Company Intelligence
 */
class DomesticRegulatoryComplianceService
{
    /**
     * --------------------------------------------------------------------------
     * Domestic Regulatory Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const REGULATIONS = [

        /*
        |--------------------------------------------------------------------------
        | Corporate Legal
        |--------------------------------------------------------------------------
        */

        'CORPORATE' => [

            'Business Registration',

            'Company Registration',

            'Business License',

            'Industrial License',

            'Corporate Governance',

        ],

        /*
        |--------------------------------------------------------------------------
        | Employment Regulation
        |--------------------------------------------------------------------------
        */

        'EMPLOYMENT' => [

            'Minimum Wage Compliance',

            'Working Hours',

            'Employment Contract',

            'Occupational Health & Safety',

            'Social Security',

            'Employee Welfare',

        ],

        /*
        |--------------------------------------------------------------------------
        | Environmental Regulation
        |--------------------------------------------------------------------------
        */

        'ENVIRONMENT' => [

            'Environmental Permit',

            'Wastewater Management',

            'Hazardous Waste Management',

            'Air Emission Compliance',

            'Chemical Management',

        ],

        /*
        |--------------------------------------------------------------------------
        | Tax & Financial
        |--------------------------------------------------------------------------
        */

        'FINANCE' => [

            'Tax Registration',

            'Tax Reporting',

            'Corporate Income Tax',

            'Value Added Tax',

            'Financial Audit',

        ],

        /*
        |--------------------------------------------------------------------------
        | Industrial Regulation
        |--------------------------------------------------------------------------
        */

        'INDUSTRY' => [

            'Industrial Operation Permit',

            'Factory Safety',

            'Machine Safety',

            'Production Compliance',

            'Industrial Reporting',

        ],

        /*
        |--------------------------------------------------------------------------
        | Export Readiness
        |--------------------------------------------------------------------------
        */

        'EXPORT' => [

            'Exporter Registration',

            'Certificate of Origin',

            'Customs Registration',

            'Export Documentation',

            'Trade Compliance',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Regulation Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::REGULATIONS[strtoupper($group)] ?? [];
    }

    /**
     * Corporate Legal
     */
    public function corporate(): array
    {
        return $this->get('CORPORATE');
    }

    /**
     * Employment Regulation
     */
    public function employment(): array
    {
        return $this->get('EMPLOYMENT');
    }

    /**
     * Environmental Regulation
     */
    public function environment(): array
    {
        return $this->get('ENVIRONMENT');
    }

    /**
     * Tax & Financial Regulation
     */
    public function finance(): array
    {
        return $this->get('FINANCE');
    }

    /**
     * Industrial Regulation
     */
    public function industry(): array
    {
        return $this->get('INDUSTRY');
    }

    /**
     * Export Readiness
     */
    public function export(): array
    {
        return $this->get('EXPORT');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Domestic Regulatory Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::REGULATIONS;
    }
}