<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

class ManufacturingProfileResolver
{
    /**
     * Resolve Manufacturing Profile.
     *
     * This profile is used by:
     *
     * - Manufacturing Router™
     * - Factory Intelligence™
     * - Production Intelligence™
     * - Executive Dashboard™
     */
    public static function resolve(
        string $primaryCategory,
        string $primaryLineOfBusiness
    ): string {

        return match ($primaryLineOfBusiness) {

            /*
            |--------------------------------------------------------------------------
            | Manufacturer
            |--------------------------------------------------------------------------
            */

            'fiber'
                => 'fiber_factory',

            'spinner'
                => 'spinning_factory',

            'weaving'
                => 'weaving_factory',

            'knitting'
                => 'knitting_factory',

            'dyeing_finishing'
                => 'dyeing_factory',

            'printing'
                => 'printing_factory',

            'garment'
                => 'garment_factory',

            /*
            |--------------------------------------------------------------------------
            | Quality Infrastructure
            |--------------------------------------------------------------------------
            */

            'testing_laboratory'
                => 'laboratory_facility',

            'certification_body'
                => 'certification_facility',

            /*
            |--------------------------------------------------------------------------
            | Supporting Industry
            |--------------------------------------------------------------------------
            */

            'machinery_supplier'
                => 'machinery_facility',

            'accessories_supplier'
                => 'accessories_facility',

            'chemical_supplier'
                => 'chemical_facility',

            /*
            |--------------------------------------------------------------------------
            | Commercial
            |--------------------------------------------------------------------------
            */

            'trader'
                => 'commercial_office',

            'brand_owner'
                => 'brand_headquarters',

            'buying_office'
                => 'buying_office',

            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */

            default
                => $primaryCategory,

        };

    }
}