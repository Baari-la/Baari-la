<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Social;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Social Compliance Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable social compliance knowledge across the
 * global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which social compliance requirements are commonly required?
 * • Which labor practices are expected by global buyers?
 * • What social standards improve supplier readiness?
 * • How can suppliers reduce social compliance risks?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve social compliance awareness
 * • Prepare for buyer audits
 * • Strengthen workforce management
 *
 * Buyer / Brand
 *
 * • Evaluate supplier social responsibility
 * • Reduce reputational risks
 *
 * Industry
 *
 * • Promote responsible employment
 * • Encourage ethical manufacturing
 *
 * This service NEVER performs:
 *
 * • Database Query
 * • Analytics
 * • Matching
 * • Recommendation
 *
 * Used by:
 *
 * - ComplianceOrchestrator
 * - Supplier Readiness Intelligence
 * - MatchingEngine
 * - Company Intelligence
 */
class SocialComplianceService
{
    /**
     * --------------------------------------------------------------------------
     * Social Compliance Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const COMPLIANCE = [

        /*
        |--------------------------------------------------------------------------
        | Employment
        |--------------------------------------------------------------------------
        */

        'EMPLOYMENT' => [

            'Legal Employment',

            'Employment Contracts',

            'Equal Opportunity',

            'Non-Discrimination',

            'No Forced Labor',

            'No Child Labor',

        ],

        /*
        |--------------------------------------------------------------------------
        | Wages & Benefits
        |--------------------------------------------------------------------------
        */

        'WAGES' => [

            'Minimum Wage Compliance',

            'Overtime Compensation',

            'Employee Benefits',

            'Social Security',

            'Payroll Transparency',

        ],

        /*
        |--------------------------------------------------------------------------
        | Working Hours
        |--------------------------------------------------------------------------
        */

        'WORKING_HOURS' => [

            'Standard Working Hours',

            'Overtime Management',

            'Rest Days',

            'Leave Entitlements',

            'Attendance Management',

        ],

        /*
        |--------------------------------------------------------------------------
        | Occupational Health & Safety
        |--------------------------------------------------------------------------
        */

        'HEALTH_SAFETY' => [

            'Occupational Safety',

            'Fire Safety',

            'Emergency Preparedness',

            'Machine Safety',

            'Personal Protective Equipment',

            'First Aid',

            'Workplace Hygiene',

        ],

        /*
        |--------------------------------------------------------------------------
        | Worker Rights
        |--------------------------------------------------------------------------
        */

        'WORKER_RIGHTS' => [

            'Freedom of Association',

            'Collective Bargaining',

            'Grievance Mechanism',

            'Worker Representation',

            'Harassment Prevention',

        ],

        /*
        |--------------------------------------------------------------------------
        | Human Resource Management
        |--------------------------------------------------------------------------
        */

        'HR' => [

            'Employee Training',

            'Skills Development',

            'Performance Evaluation',

            'Career Development',

            'Worker Wellbeing',

        ],

        /*
        |--------------------------------------------------------------------------
        | Ethical Business Conduct
        |--------------------------------------------------------------------------
        */

        'ETHICS' => [

            'Code of Conduct',

            'Anti-Corruption',

            'Whistleblower Protection',

            'Business Integrity',

            'Responsible Employment',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Compliance Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::COMPLIANCE[strtoupper($group)] ?? [];
    }

    /**
     * Employment
     */
    public function employment(): array
    {
        return $this->get('EMPLOYMENT');
    }

    /**
     * Wages & Benefits
     */
    public function wages(): array
    {
        return $this->get('WAGES');
    }

    /**
     * Working Hours
     */
    public function workingHours(): array
    {
        return $this->get('WORKING_HOURS');
    }

    /**
     * Occupational Health & Safety
     */
    public function healthSafety(): array
    {
        return $this->get('HEALTH_SAFETY');
    }

    /**
     * Worker Rights
     */
    public function workerRights(): array
    {
        return $this->get('WORKER_RIGHTS');
    }

    /**
     * Human Resource Management
     */
    public function humanResources(): array
    {
        return $this->get('HR');
    }

    /**
     * Ethical Business Conduct
     */
    public function ethics(): array
    {
        return $this->get('ETHICS');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Social Compliance Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::COMPLIANCE;
    }
}