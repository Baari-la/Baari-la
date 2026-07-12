<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Metadata;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Metadata Service
 * ==========================================================================
 *
 * Generates metadata for Recommendation Engine.
 *
 * Version:
 * 1.0
 */
class RecommendationMetadataService
{
    /**
     * --------------------------------------------------------------------------
     * Build Metadata
     * --------------------------------------------------------------------------
     */
    public function build(

        Company $company,

        Collection $recommendations,

        array $context = [],

    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Engine
            |--------------------------------------------------------------------------
            */

            'engine' =>

                'DIGESTEX Recommendation Intelligence Engine',

            'engine_version' => '1.0',

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'company_id' => $company->id,

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'recommendation_count' =>

                $recommendations->count(),

            /*
            |--------------------------------------------------------------------------
            | Generated
            |--------------------------------------------------------------------------
            */

            'generated_at' =>

                now()->toDateTimeString(),

            /*
            |--------------------------------------------------------------------------
            | Future
            |--------------------------------------------------------------------------
            |
            | Buyer Intelligence Version
            | Supply Chain Version
            | Formula Version
            | AI Model
            |
            */

        ];

    }
}