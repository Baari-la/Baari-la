<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

class IndustryTypeResolver
{
    /**
     * Resolve the company's industry type.
     */
    public static function resolve(
        string $primaryCategory
    ): string {

        return match ($primaryCategory) {

            /*
            |--------------------------------------------------------------------------
            | Manufacturing
            |--------------------------------------------------------------------------
            */

            'manufacturer'
                => 'textile_manufacturer',

            /*
            |--------------------------------------------------------------------------
            | Quality Infrastructure
            |--------------------------------------------------------------------------
            */

            'quality_infrastructure'
                => 'quality_services',

            /*
            |--------------------------------------------------------------------------
            | Supporting Industry
            |--------------------------------------------------------------------------
            */

            'supporting_industry'
                => 'textile_supporting',

            /*
            |--------------------------------------------------------------------------
            | Commercial
            |--------------------------------------------------------------------------
            */

            'commercial'
                => 'commercial_services',

            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */

            default
                => 'general',

        };

    }
}