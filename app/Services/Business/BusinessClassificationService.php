<?php

declare(strict_types=1);

namespace App\Services\Business;

use App\Models\CompanyIdentityBusiness;

use App\Services\Business\Resolvers\CategoryResolver;
use App\Services\Business\Resolvers\SecondaryCategoryResolver;
use App\Services\Business\Resolvers\ValueChainResolver;
use App\Services\Business\Resolvers\IndustryTypeResolver;
use App\Services\Business\Resolvers\LineOfBusinessResolver;
use App\Services\Business\Resolvers\FrameworkBuilder;

class BusinessClassificationService
{
    /**
     * --------------------------------------------------------------------------
     * DIGESTEX Business Decision Engine™
     * --------------------------------------------------------------------------
     *
     * Coordinates all Business Resolvers.
     *
     * This service does not contain business rules.
     * All business rules live inside the Resolver layer.
     *
     * --------------------------------------------------------------------------
     */

    public function classify(
        CompanyIdentityBusiness $business
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Business Category
        |--------------------------------------------------------------------------
        */

        $primaryCategory =
            CategoryResolver::resolve(
                $business
            );

        $secondaryCategories =
            SecondaryCategoryResolver::resolve(
                $business,
                $primaryCategory
            );

        /*
        |--------------------------------------------------------------------------
        | Industry Intelligence
        |--------------------------------------------------------------------------
        */

        $industryType =
            IndustryTypeResolver::resolve(
                $primaryCategory
            );

        $valueChain =
            ValueChainResolver::resolve(
                $business,
                $primaryCategory
            );

        /*
        |--------------------------------------------------------------------------
        | Line Of Business
        |--------------------------------------------------------------------------
        */

        $lineOfBusiness =
            LineOfBusinessResolver::resolve(
                $business
            );

        $primaryLine =
            LineOfBusinessResolver::primary(
                $lineOfBusiness
            );

        /*
        |--------------------------------------------------------------------------
        | Dynamic Framework
        |--------------------------------------------------------------------------
        */

        $framework =
            FrameworkBuilder::build(
                $primaryCategory,
                $primaryLine
            );

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Business Classification™
            |--------------------------------------------------------------------------
            */

            'primary_business_category'
                => $primaryCategory,

            'secondary_business_categories'
                => $secondaryCategories,

            'value_chain_position'
                => $valueChain,

            /*
            |--------------------------------------------------------------------------
            | Industry Intelligence™
            |--------------------------------------------------------------------------
            */

            'industry_type'
                => $industryType,

            'primary_line_of_business'
                => $primaryLine,

            'line_of_business'
                => $lineOfBusiness,

            /*
            |--------------------------------------------------------------------------
            | Framework™
            |--------------------------------------------------------------------------
            */

            'framework'
                => $framework,

        ];

    }
}