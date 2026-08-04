<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

use App\Models\CompanyIdentityBusiness;

class ValueChainResolver
{
    /**
     * Resolve the company's position in the textile value chain.
     */
    public static function resolve(
        CompanyIdentityBusiness $business,
        string $primaryCategory
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Upstream
        |--------------------------------------------------------------------------
        */

        if (
            $business->is_fiber_producer ||
            $business->is_spinner
        ) {

            return 'upstream';

        }

        /*
        |--------------------------------------------------------------------------
        | Midstream
        |--------------------------------------------------------------------------
        */

        if (
            $business->is_weaving ||
            $business->is_knitting ||
            $business->is_dyeing_finishing ||
            $business->is_printing
        ) {

            return 'midstream';

        }

        /*
        |--------------------------------------------------------------------------
        | Downstream
        |--------------------------------------------------------------------------
        */

        if (
            $business->is_garment ||
            $business->is_brand
        ) {

            return 'downstream';

        }

        /*
        |--------------------------------------------------------------------------
        | Supporting Ecosystem
        |--------------------------------------------------------------------------
        */

        if (
            $primaryCategory === 'supporting_industry' ||
            $primaryCategory === 'quality_infrastructure'
        ) {

            return 'supporting';

        }

        /*
        |--------------------------------------------------------------------------
        | Commercial Ecosystem
        |--------------------------------------------------------------------------
        */

        if ($primaryCategory === 'commercial') {

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