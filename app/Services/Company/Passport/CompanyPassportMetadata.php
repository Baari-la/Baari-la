<?php

declare(strict_types=1);

namespace App\Services\Company\Passport;

use App\Models\Company;
use Illuminate\Support\Carbon;

class CompanyPassportMetadata
{
    /**
     * --------------------------------------------------------------------------
     * Build Passport Metadata
     * --------------------------------------------------------------------------
     */
    public function build(Company $company): array
    {
        $generatedAt = now();

        return [

            /*
            |--------------------------------------------------------------------------
            | Passport Identity
            |--------------------------------------------------------------------------
            */

            'passport_id' => sprintf(
                'DGX-%08d',
                $company->id
            ),

            'company_id' => $company->id,

            /*
            |--------------------------------------------------------------------------
            | Framework
            |--------------------------------------------------------------------------
            */

            'framework' => 'DIGESTEX Company Intelligence',

            'framework_version' => '1.0.0',

            'passport_version' => '1.0.0',

            /*
            |--------------------------------------------------------------------------
            | Generated
            |--------------------------------------------------------------------------
            */

            'generated_at' => $generatedAt->toDateTimeString(),

            'generated_at_iso' => $generatedAt->toIso8601String(),

            /*
            |--------------------------------------------------------------------------
            | Company Data
            |--------------------------------------------------------------------------
            */

            'last_company_update' => optional(
                $company->last_updated_at
            )?->toDateTimeString(),

            'last_verified_at' => optional(
                $company->last_verified_at
            )?->toDateTimeString(),

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'verification_status' => $company->verification_status,

            'data_source' => $company->data_source,

            /*
            |--------------------------------------------------------------------------
            | Freshness
            |--------------------------------------------------------------------------
            */

            'data_freshness' => $this->freshness(
                $company
            ),

            /*
            |--------------------------------------------------------------------------
            | Generator
            |--------------------------------------------------------------------------
            */

            'generated_by' => 'CompanyIntelligenceOrchestrator',

            'generator' => 'DIGESTEX',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Data Freshness
     * --------------------------------------------------------------------------
     */
    protected function freshness(
        Company $company
    ): string {

        if (!$company->last_updated_at) {
            return 'Unknown';
        }

        $days = Carbon::parse(
            $company->last_updated_at
        )->diffInDays(now());

        return match (true) {

            $days <= 30 => 'Fresh',

            $days <= 90 => 'Recent',

            $days <= 180 => 'Aging',

            default => 'Needs Update',

        };
    }
}