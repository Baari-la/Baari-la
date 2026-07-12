<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;
use Illuminate\Support\Str;

class BusinessRoleService
{
    /**
     * ==========================================================================
     * DIGESTEX BUSINESS ROLE SERVICE
     * ==========================================================================
     *
     * Determines the primary business role of a company
     * within the global textile value chain.
     *
     * Version 1
     * ---------
     * Legacy Directory
     *
     * Future versions:
     *
     * - Company Self Update
     * - Multiple Business Roles
     * - Executive AI Classification
     *
     * Returns one of the keys defined in:
     *
     * config/textile_ecosystem.php
     *
     * Examples:
     *
     * fiber
     * spinning
     * knitting
     * weaving
     * dyeing
     * garment
     * home_textile
     * technical_textile
     * brand
     * buying_office
     *
     */

    public function resolve(
        Company $company
    ): string {

        $text = Str::lower(
            implode(' ', [

                $company->category,

                $company->sektor,

                $company->produk,

                $company->nama_perusahaan,

            ])
        );

        return match (true) {

            /*
            |--------------------------------------------------------------------------
            | Fiber
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'fiber'),
            str_contains($text, 'cotton'),
            str_contains($text, 'polyester staple')
                => 'fiber',

            /*
            |--------------------------------------------------------------------------
            | Spinning
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'spinning'),
            str_contains($text, 'yarn')
                => 'spinning',

            /*
            |--------------------------------------------------------------------------
            | Knitting
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'knitting'),
            str_contains($text, 'knit'),
            str_contains($text, 'rajut')
                => 'knitting',

            /*
            |--------------------------------------------------------------------------
            | Weaving
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'weaving'),
            str_contains($text, 'woven'),
            str_contains($text, 'tenun')
                => 'weaving',

            /*
            |--------------------------------------------------------------------------
            | Dyeing
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'dyeing'),
            str_contains($text, 'finishing'),
            str_contains($text, 'printing')
                => 'dyeing',

            /*
            |--------------------------------------------------------------------------
            | Garment
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'garment'),
            str_contains($text, 'apparel'),
            str_contains($text, 'fashion'),
            str_contains($text, 'underwear'),
            str_contains($text, 'shirt'),
            str_contains($text, 'pants')
                => 'garment',

            /*
            |--------------------------------------------------------------------------
            | Home Textile
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'home textile'),
            str_contains($text, 'bed sheet'),
            str_contains($text, 'towel'),
            str_contains($text, 'curtain')
                => 'home_textile',

            /*
            |--------------------------------------------------------------------------
            | Technical Textile
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'technical textile'),
            str_contains($text, 'nonwoven'),
            str_contains($text, 'geotextile'),
            str_contains($text, 'medical textile')
                => 'technical_textile',

            /*
            |--------------------------------------------------------------------------
            | Brand
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'brand')
                => 'brand',

            /*
            |--------------------------------------------------------------------------
            | Buying Office
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'buying office'),
            str_contains($text, 'sourcing')
                => 'buying_office',

            default => 'garment',

        };

    }
}