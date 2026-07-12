<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Textile Business Ecosystem
|--------------------------------------------------------------------------
|
| This configuration defines the global textile value chain and
| supporting ecosystem used by:
|
| • Smart Business Matching
| • Business Ecosystem Intelligence
| • Executive AI
| • RFQ Matching
| • Collective Sourcing
| • Digital Material Store
|
| The objective is NOT to recommend similar companies.
| The objective is to recommend the most relevant
| business ecosystem.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Fiber
    |--------------------------------------------------------------------------
    */

    'fiber' => [

        'name' => 'Fiber Producer',

        'upstream' => [],

        'downstream' => [

            'spinning',

        ],

        'needs' => [

            'machinery',

            'laboratory',

            'logistics',

            'warehouse',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Spinning
    |--------------------------------------------------------------------------
    */

    'spinning' => [

        'name' => 'Spinning Mill',

        'upstream' => [

            'fiber',

        ],

        'downstream' => [

            'knitting',

            'weaving',

        ],

        'needs' => [

            'machinery',

            'testing',

            'chemicals',

            'technology',

            'logistics',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Knitting
    |--------------------------------------------------------------------------
    */

    'knitting' => [

        'name' => 'Knitting Mill',

        'upstream' => [

            'spinning',

        ],

        'downstream' => [

            'dyeing',

            'garment',

        ],

        'needs' => [

            'machinery',

            'needles',

            'chemicals',

            'testing',

            'technology',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Weaving
    |--------------------------------------------------------------------------
    */

    'weaving' => [

        'name' => 'Weaving Mill',

        'upstream' => [

            'spinning',

        ],

        'downstream' => [

            'dyeing',

            'garment',

        ],

        'needs' => [

            'looms',

            'sizing',

            'chemicals',

            'technology',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Dyeing & Finishing
    |--------------------------------------------------------------------------
    */

    'dyeing' => [

        'name' => 'Dyeing & Finishing',

        'upstream' => [

            'knitting',

            'weaving',

        ],

        'downstream' => [

            'garment',

            'home_textile',

            'technical_textile',

        ],

        'needs' => [

            'chemicals',

            'auxiliaries',

            'laboratory',

            'testing',

            'wastewater',

            'boiler',

            'energy',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Garment
    |--------------------------------------------------------------------------
    */

    'garment' => [

        'name' => 'Garment Manufacturer',

        'upstream' => [

            'knitting',

            'weaving',

            'dyeing',

        ],

        'downstream' => [

            'brand',

            'buying_office',

            'retail',

        ],

        'needs' => [

            'fabric',

            'thread',

            'accessories',

            'printing',

            'embroidery',

            'packaging',

            'machinery',

            'technology',

            'testing',

            'inspection',

            'logistics',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Home Textile
    |--------------------------------------------------------------------------
    */

    'home_textile' => [

        'name' => 'Home Textile',

        'upstream' => [

            'dyeing',

        ],

        'downstream' => [

            'retail',

        ],

        'needs' => [

            'fabric',

            'packaging',

            'buyers',

            'technology',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Technical Textile
    |--------------------------------------------------------------------------
    */

    'technical_textile' => [

        'name' => 'Technical Textile',

        'upstream' => [

            'dyeing',

        ],

        'downstream' => [

            'industrial',

            'medical',

            'automotive',

        ],

        'needs' => [

            'specialty_fiber',

            'testing',

            'laboratory',

            'technology',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

    'brand' => [

        'name' => 'Brand Owner',

        'upstream' => [

            'garment',

        ],

        'downstream' => [

            'retail',

        ],

        'needs' => [

            'manufacturers',

            'technology',

            'logistics',

            'inspection',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Buying Office
    |--------------------------------------------------------------------------
    */

    'buying_office' => [

        'name' => 'Buying Office',

        'upstream' => [

            'garment',

        ],

        'downstream' => [

            'brand',

        ],

        'needs' => [

            'manufacturers',

            'inspection',

            'testing',

            'logistics',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Supporting Ecosystem
    |--------------------------------------------------------------------------
    */

    'supporting' => [

        'machinery',

        'chemicals',

        'technology',

        'laboratory',

        'testing',

        'inspection',

        'logistics',

        'warehouse',

        'energy',

        'wastewater',

        'finance',

        'insurance',

        'consultant',

        'association',

        'media',

        'education',

        'research',

        'government',

    ],

];