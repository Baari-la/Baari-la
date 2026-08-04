<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

use App\Models\CompanyIdentityBusiness;

class SecondaryCategoryResolver
{
    /**
     * Resolve secondary business categories.
     *
     * Example:
     *
     * Primary : Manufacturer
     * Secondary :
     *      - Commercial
     *      - Supporting Industry
     */
    public static function resolve(
        CompanyIdentityBusiness $business,
        string $primaryCategory
    ): array {

        $categories = [];

        /*
        |--------------------------------------------------------------------------
        | Manufacturer
        |--------------------------------------------------------------------------
        */

        if (
            $primaryCategory !== 'manufacturer' &&
            (
                $business->is_fiber_producer ||
                $business->is_spinner ||
                $business->is_weaving ||
                $business->is_knitting ||
                $business->is_dyeing_finishing ||
                $business->is_printing ||
                $business->is_garment
            )
        ) {

            $categories[] = 'manufacturer';

        }

        /*
        |--------------------------------------------------------------------------
        | Quality Infrastructure
        |--------------------------------------------------------------------------
        */

        if (
            $primaryCategory !== 'quality_infrastructure' &&
            (
                $business->is_testing_laboratory ||
                $business->is_certification_body
            )
        ) {

            $categories[] = 'quality_infrastructure';

        }

        /*
        |--------------------------------------------------------------------------
        | Supporting Industry
        |--------------------------------------------------------------------------
        */

        if (
            $primaryCategory !== 'supporting_industry' &&
            (
                $business->is_machinery_supplier ||
                $business->is_accessories_supplier ||
                $business->is_chemical_supplier
            )
        ) {

            $categories[] = 'supporting_industry';

        }

        /*
        |--------------------------------------------------------------------------
        | Commercial
        |--------------------------------------------------------------------------
        */

        if (
            $primaryCategory !== 'commercial' &&
            (
                $business->is_trader ||
                $business->is_brand ||
                $business->is_buying_office
            )
        ) {

            $categories[] = 'commercial';

        }

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return array_values(
            array_unique($categories)
        );

    }
}