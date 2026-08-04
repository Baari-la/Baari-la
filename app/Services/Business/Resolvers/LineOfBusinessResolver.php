<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

use App\Models\CompanyIdentityBusiness;

class LineOfBusinessResolver
{
    /**
     * Resolve all Lines of Business.
     */
    public static function resolve(
        CompanyIdentityBusiness $business
    ): array {

        $lines = [];

        /*
        |--------------------------------------------------------------------------
        | Manufacturer
        |--------------------------------------------------------------------------
        */

        if ($business->is_fiber_producer) {
            $lines[] = 'fiber';
        }

        if ($business->is_spinner) {
            $lines[] = 'spinner';
        }

        if ($business->is_weaving) {
            $lines[] = 'weaving';
        }

        if ($business->is_knitting) {
            $lines[] = 'knitting';
        }

        if ($business->is_dyeing_finishing) {
            $lines[] = 'dyeing_finishing';
        }

        if ($business->is_printing) {
            $lines[] = 'printing';
        }

        if ($business->is_garment) {
            $lines[] = 'garment';
        }

        /*
        |--------------------------------------------------------------------------
        | Quality Infrastructure
        |--------------------------------------------------------------------------
        */

        if ($business->is_testing_laboratory) {
            $lines[] = 'testing_laboratory';
        }

        if ($business->is_certification_body) {
            $lines[] = 'certification_body';
        }

        /*
        |--------------------------------------------------------------------------
        | Supporting Industry
        |--------------------------------------------------------------------------
        */

        if ($business->is_machinery_supplier) {
            $lines[] = 'machinery_supplier';
        }

        if ($business->is_accessories_supplier) {
            $lines[] = 'accessories_supplier';
        }

        if ($business->is_chemical_supplier) {
            $lines[] = 'chemical_supplier';
        }

        /*
        |--------------------------------------------------------------------------
        | Commercial
        |--------------------------------------------------------------------------
        */

        if ($business->is_trader) {
            $lines[] = 'trader';
        }

        if ($business->is_brand) {
            $lines[] = 'brand_owner';
        }

        if ($business->is_buying_office) {
            $lines[] = 'buying_office';
        }

        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        if (empty($lines)) {
            $lines[] = 'general';
        }

        return array_values(
            array_unique($lines)
        );
    }

    /**
     * Resolve the primary Line of Business.
     */
    public static function primary(
        array $lines
    ): string {

        return $lines[0] ?? 'general';

    }
}