<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Certification Scopes
|--------------------------------------------------------------------------
|
| Defines industrial scopes covered by certifications.
|
| Used by:
|
| • CertificationService
| • Company Intelligence
| • Executive AI
| • Buyer Matching
| • Compliance Engine
|
*/

return [

    [

        'id' => 'fiber',

        'label' => 'Fiber',

        'description' => 'Fiber manufacturing and processing.',

        'priority' => 10,

        'active' => true,

    ],

    [

        'id' => 'yarn',

        'label' => 'Yarn',

        'description' => 'Yarn manufacturing.',

        'priority' => 20,

        'active' => true,

    ],

    [

        'id' => 'fabric',

        'label' => 'Fabric',

        'description' => 'Fabric manufacturing.',

        'priority' => 30,

        'active' => true,

    ],

    [

        'id' => 'dyeing',

        'label' => 'Dyeing',

        'description' => 'Textile dyeing operations.',

        'priority' => 40,

        'active' => true,

    ],

    [

        'id' => 'printing',

        'label' => 'Printing',

        'description' => 'Textile printing operations.',

        'priority' => 50,

        'active' => true,

    ],

    [

        'id' => 'finishing',

        'label' => 'Finishing',

        'description' => 'Textile finishing operations.',

        'priority' => 60,

        'active' => true,

    ],

    [

        'id' => 'garment',

        'label' => 'Garment',

        'description' => 'Garment manufacturing.',

        'priority' => 70,

        'active' => true,

    ],

    [

        'id' => 'home_textile',

        'label' => 'Home Textile',

        'description' => 'Home textile manufacturing.',

        'priority' => 80,

        'active' => true,

    ],

    [

        'id' => 'technical_textile',

        'label' => 'Technical Textile',

        'description' => 'Technical textile manufacturing.',

        'priority' => 90,

        'active' => true,

    ],

    [

        'id' => 'nonwoven',

        'label' => 'Nonwoven',

        'description' => 'Nonwoven production.',

        'priority' => 100,

        'active' => true,

    ],

    [

        'id' => 'chemical',

        'label' => 'Chemical',

        'description' => 'Textile chemicals and auxiliaries.',

        'priority' => 110,

        'active' => true,

    ],

    [

        'id' => 'machinery',

        'label' => 'Machinery',

        'description' => 'Textile machinery and equipment.',

        'priority' => 120,

        'active' => true,

    ],

    [

        'id' => 'laboratory',

        'label' => 'Laboratory',

        'description' => 'Testing and calibration laboratories.',

        'priority' => 130,

        'active' => true,

    ],

    [

        'id' => 'logistics',

        'label' => 'Logistics',

        'description' => 'Warehousing, logistics and transportation.',

        'priority' => 140,

        'active' => true,

    ],

    [

        'id' => 'packaging',

        'label' => 'Packaging',

        'description' => 'Packaging materials and solutions.',

        'priority' => 150,

        'active' => true,

    ],

    [

        'id' => 'brand_owner',

        'label' => 'Brand Owner',

        'description' => 'Brand owners and retailers.',

        'priority' => 160,

        'active' => true,

    ],

    [

        'id' => 'retailer',

        'label' => 'Retailer',

        'description' => 'Retail operations.',

        'priority' => 170,

        'active' => true,

    ],

    [

        'id' => 'trading_company',

        'label' => 'Trading Company',

        'description' => 'Trading and sourcing companies.',

        'priority' => 180,

        'active' => true,

    ],

    [

        'id' => 'service',

        'label' => 'Service',

        'description' => 'Professional and industrial services.',

        'priority' => 190,

        'active' => true,

    ],

    [

        'id' => 'manufacturer',

        'label' => 'General Manufacturing',

        'description' => 'General manufacturing companies.',

        'priority' => 200,

        'active' => true,

    ],

    [

        'id' => 'all',

        'label' => 'All Industries',

        'description' => 'Applicable across all textile sectors.',

        'priority' => 999,

        'active' => true,

    ],

];