<?php

declare(strict_types=1);

namespace App\Services\Business\Resolvers;

class FrameworkBuilder
{
    /**
     * Build the DIGESTEX Business Framework™.
     */
    public static function build(
        string $primaryCategory,
        string $primaryLineOfBusiness
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Resolve Profiles
        |--------------------------------------------------------------------------
        */

        $capabilityProfile = CapabilityProfileResolver::resolve(
            $primaryCategory,
            $primaryLineOfBusiness
        );

        $manufacturingProfile = ManufacturingProfileResolver::resolve(
            $primaryCategory,
            $primaryLineOfBusiness
        );

        /*
        |--------------------------------------------------------------------------
        | Build Framework
        |--------------------------------------------------------------------------
        */

        return [

            'capability_profile' => $capabilityProfile,

            'manufacturing_profile' => $manufacturingProfile,

            'modules' => self::modules(
                $capabilityProfile
            ),

            'routes' => self::routes(
                $capabilityProfile
            ),

            'dashboard' => self::dashboard(
                $capabilityProfile
            ),

            'permissions' => self::permissions(
                $capabilityProfile
            ),

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Modules
    |--------------------------------------------------------------------------
    */

    protected static function modules(
        string $profile
    ): array {

        return match ($profile) {

            /*
            |--------------------------------------------------------------------------
            | Fiber
            |--------------------------------------------------------------------------
            */

            'manufacturer_fiber' => [

                'capacity',

                'materials',

                'production',

                'commercial',

            ],

            /*
            |--------------------------------------------------------------------------
            | Spinner
            |--------------------------------------------------------------------------
            */

            'manufacturer_spinner' => [

                'capacity',

                'spindle',

                'fiber',

                'count_range',

                'commercial',

            ],

            /*
            |--------------------------------------------------------------------------
            | Weaving
            |--------------------------------------------------------------------------
            */

            'manufacturer_weaving' => [

                'capacity',

                'loom',

                'fabric',

                'commercial',

            ],

            /*
            |--------------------------------------------------------------------------
            | Knitting
            |--------------------------------------------------------------------------
            */

            'manufacturer_knitting' => [

                'capacity',

                'machine',

                'fabric',

                'commercial',

            ],

            /*
            |--------------------------------------------------------------------------
            | Dyeing
            |--------------------------------------------------------------------------
            */

            'manufacturer_dyeing' => [

                'capacity',

                'process',

                'finishing',

                'commercial',

            ],

            /*
            |--------------------------------------------------------------------------
            | Printing
            |--------------------------------------------------------------------------
            */

            'manufacturer_printing' => [

                'capacity',

                'printing',

                'ink',

                'commercial',

            ],

            /*
            |--------------------------------------------------------------------------
            | Garment
            |--------------------------------------------------------------------------
            */

            'manufacturer_garment' => [

                'capacity',

                'production',

                'sampling',

                'commercial',

            ],

            /*
            |--------------------------------------------------------------------------
            | Laboratory
            |--------------------------------------------------------------------------
            */

            'quality_laboratory' => [

                'testing',

                'laboratory',

                'accreditation',

                'certification',

            ],

            /*
            |--------------------------------------------------------------------------
            | Certification
            |--------------------------------------------------------------------------
            */

            'quality_certification' => [

                'certification',

                'auditing',

                'accreditation',

            ],

            /*
            |--------------------------------------------------------------------------
            | Machinery
            |--------------------------------------------------------------------------
            */

            'supporting_machinery' => [

                'products',

                'technical_support',

                'distribution',

                'after_sales',

            ],

            /*
            |--------------------------------------------------------------------------
            | Accessories
            |--------------------------------------------------------------------------
            */

            'supporting_accessories' => [

                'products',

                'inventory',

                'distribution',

            ],

            /*
            |--------------------------------------------------------------------------
            | Chemical
            |--------------------------------------------------------------------------
            */

            'supporting_chemical' => [

                'products',

                'sds',

                'technical_support',

            ],

            /*
            |--------------------------------------------------------------------------
            | Commercial
            |--------------------------------------------------------------------------
            */

            'commercial_trader',

            'commercial_brand',

            'commercial_buying_office'

                => [

                    'markets',

                    'buyers',

                    'suppliers',

                    'commercial',

                ],

            default => [

                'overview',

            ],

        };

    }

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */

    protected static function routes(
        string $profile
    ): array {

        return [

            'capability',

            'manufacturing',

            'products',

            'sustainability',

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgets
    |--------------------------------------------------------------------------
    */

    protected static function dashboard(
        string $profile
    ): array {

        return [

            'company_score',

            'buyer_readiness',

            'executive_summary',

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    protected static function permissions(
        string $profile
    ): array {

        return [

            'edit',

            'view',

        ];

    }
}