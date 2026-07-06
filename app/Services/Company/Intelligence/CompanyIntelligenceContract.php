<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Intelligence Contract
 * ==========================================================================
 *
 * Standard response structure for every Company Intelligence Service.
 *
 * This class serves as the official response specification used by:
 *
 * • CompanyCapabilityService
 * • CompanyComplianceService
 * • CompanyMarketService
 * • CompanySupplyChainService
 * • CompanyReadinessService
 * • CompanyScoreService
 *
 * Every intelligence service should return:
 *
 * [
 *     'score' => [],
 *     'passport' => [],
 *     'summary' => [],
 * ]
 *
 * This file contains no business logic.
 * It acts as the canonical response structure documentation.
 */
final class CompanyIntelligenceContract
{
    /**
     * Default response structure.
     */
    public static function response(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Executive Score
            |--------------------------------------------------------------------------
            */

            'score' => [

                'overall' => 0,

                'level' => null,

                'rating' => null,

            ],

            /*
            |--------------------------------------------------------------------------
            | Passport Data
            |--------------------------------------------------------------------------
            */

            'passport' => [],

            /*
            |--------------------------------------------------------------------------
            | Executive Summary
            |--------------------------------------------------------------------------
            */

            'summary' => [],

        ];
    }
}