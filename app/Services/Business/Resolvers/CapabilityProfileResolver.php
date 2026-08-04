<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

class CapabilityProfileResolver
{
    /**
     * Resolve Capability Profile.
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
                => 'manufacturer_fiber',

            'spinner'
                => 'manufacturer_spinner',

            'weaving'
                => 'manufacturer_weaving',

            'knitting'
                => 'manufacturer_knitting',

            'dyeing_finishing'
                => 'manufacturer_dyeing',

            'printing'
                => 'manufacturer_printing',

            'garment'
                => 'manufacturer_garment',

            /*
            |--------------------------------------------------------------------------
            | Quality Infrastructure
            |--------------------------------------------------------------------------
            */

            'testing_laboratory'
                => 'quality_laboratory',

            'certification_body'
                => 'quality_certification',

            /*
            |--------------------------------------------------------------------------
            | Supporting Industry
            |--------------------------------------------------------------------------
            */

            'machinery_supplier'
                => 'supporting_machinery',

            'accessories_supplier'
                => 'supporting_accessories',

            'chemical_supplier'
                => 'supporting_chemical',

            /*
            |--------------------------------------------------------------------------
            | Commercial
            |--------------------------------------------------------------------------
            */

            'trader'
                => 'commercial_trader',

            'brand_owner'
                => 'commercial_brand',

            'buying_office'
                => 'commercial_buying_office',

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