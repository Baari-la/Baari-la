<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

use App\Models\CompanyIdentityBusiness;

class CategoryResolver
{
    /**
     * Resolve the company's primary business category.
     */
    public static function resolve(
        CompanyIdentityBusiness $business
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Manufacturer
        |--------------------------------------------------------------------------
        */

        if (

            $business->is_fiber_producer ||
            $business->is_spinner ||
            $business->is_weaving ||
            $business->is_knitting ||
            $business->is_dyeing_finishing ||
            $business->is_printing ||
            $business->is_garment

        ) {

            return 'manufacturer';

        }

        /*
        |--------------------------------------------------------------------------
        | Quality Infrastructure
        |--------------------------------------------------------------------------
        */

        if (

            $business->is_testing_laboratory ||
            $business->is_certification_body

        ) {

            return 'quality_infrastructure';

        }

        /*
        |--------------------------------------------------------------------------
        | Supporting Industry
        |--------------------------------------------------------------------------
        */

        if (

            $business->is_machinery_supplier ||
            $business->is_accessories_supplier ||
            $business->is_chemical_supplier

        ) {

            return 'supporting_industry';

        }

        /*
        |--------------------------------------------------------------------------
        | Commercial
        |--------------------------------------------------------------------------
        */

        if (

            $business->is_trader ||
            $business->is_brand ||
            $business->is_buying_office

        ) {

            return 'commercial';

        }

        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        return 'general';

    }
}