<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Certification Knowledge Base
|--------------------------------------------------------------------------
|
| Global Textile Certification Knowledge Base
|
| Used by:
|
| • Company Intelligence
| • Company Passport
| • Executive AI
| • Compliance Engine
| • Sustainability Score
| • Export Readiness
| • Buyer Discovery
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Quality Management
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'iso_9001',

        'name' => 'ISO 9001',

        'full_name' => 'ISO 9001 Quality Management Systems',

        'category' => 'quality',

        'issuer' => 'ISO',

        'description' => 'International standard for quality management systems.',

        'scope' => [

            'fiber',

            'yarn',

            'fabric',

            'garment',

            'machinery',

            'chemical',

            'service',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => true,

        'supports_sustainability' => false,

        'supports_traceability' => false,

        'supports_esg' => false,

        'renewal_years' => 3,

        'active' => true,

    ],

    [

        'id' => 'iso_10002',

        'name' => 'ISO 10002',

        'full_name' => 'Customer Satisfaction Management',

        'category' => 'quality',

        'issuer' => 'ISO',

        'description' => 'Customer complaint management system.',

        'scope' => [

            'service',

            'manufacturer',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => true,

        'supports_sustainability' => false,

        'supports_traceability' => false,

        'supports_esg' => false,

        'renewal_years' => 3,

        'active' => true,

    ],

    [

        'id' => 'iso_10012',

        'name' => 'ISO 10012',

        'full_name' => 'Measurement Management Systems',

        'category' => 'quality',

        'issuer' => 'ISO',

        'description' => 'Measurement management and calibration system.',

        'scope' => [

            'laboratory',

            'manufacturer',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => true,

        'supports_sustainability' => false,

        'supports_traceability' => true,

        'supports_esg' => false,

        'renewal_years' => 3,

        'active' => true,

    ],

    [

        'id' => 'iso_17025',

        'name' => 'ISO/IEC 17025',

        'full_name' => 'Testing and Calibration Laboratories',

        'category' => 'quality',

        'issuer' => 'ISO / IEC',

        'description' => 'Competence of testing and calibration laboratories.',

        'scope' => [

            'laboratory',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => true,

        'supports_sustainability' => false,

        'supports_traceability' => true,

        'supports_esg' => false,

        'renewal_years' => 4,

        'active' => true,

    ],
    /*
    |--------------------------------------------------------------------------
    | Environmental Management
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'iso_14001',

        'name' => 'ISO 14001',

        'full_name' => 'ISO 14001 Environmental Management Systems',

        'category' => 'environment',

        'issuer' => 'ISO',

        'description' => 'International standard for environmental management systems.',

        'scope' => [

            'fiber',
            'yarn',
            'fabric',
            'garment',
            'chemical',
            'machinery',
            'service',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => false,

        'supports_sustainability' => true,

        'supports_traceability' => false,

        'supports_esg' => true,

        'renewal_years' => 3,

        'active' => true,

    ],

    [

        'id' => 'iso_50001',

        'name' => 'ISO 50001',

        'full_name' => 'ISO 50001 Energy Management Systems',

        'category' => 'environment',

        'issuer' => 'ISO',

        'description' => 'International standard for energy management systems.',

        'scope' => [

            'fiber',
            'yarn',
            'fabric',
            'garment',
            'chemical',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => false,

        'supports_sustainability' => true,

        'supports_traceability' => false,

        'supports_esg' => true,

        'renewal_years' => 3,

        'active' => true,

    ],

    [

        'id' => 'iso_14064',

        'name' => 'ISO 14064',

        'full_name' => 'Greenhouse Gas Accounting and Verification',

        'category' => 'environment',

        'issuer' => 'ISO',

        'description' => 'Framework for greenhouse gas accounting and verification.',

        'scope' => [

            'manufacturer',

            'service',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => false,

        'supports_sustainability' => true,

        'supports_traceability' => false,

        'supports_esg' => true,

        'renewal_years' => 3,

        'active' => true,

    ],

    [

        'id' => 'iso_14067',

        'name' => 'ISO 14067',

        'full_name' => 'Carbon Footprint of Products',

        'category' => 'environment',

        'issuer' => 'ISO',

        'description' => 'Specification for quantifying product carbon footprint.',

        'scope' => [

            'fiber',

            'yarn',

            'fabric',

            'garment',

        ],

        'recognized_markets' => [

            'EU',

            'US',

            'Japan',

        ],

        'supports_quality' => false,

        'supports_sustainability' => true,

        'supports_traceability' => true,

        'supports_esg' => true,

        'renewal_years' => 3,

        'active' => true,

    ],

    [

        'id' => 'iso_14040',

        'name' => 'ISO 14040',

        'full_name' => 'Life Cycle Assessment Principles and Framework',

        'category' => 'environment',

        'issuer' => 'ISO',

        'description' => 'Framework for life cycle assessment.',

        'scope' => [

            'manufacturer',

            'brand_owner',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => false,

        'supports_sustainability' => true,

        'supports_traceability' => true,

        'supports_esg' => true,

        'renewal_years' => null,

        'active' => true,

    ],

    [

        'id' => 'iso_14044',

        'name' => 'ISO 14044',

        'full_name' => 'Life Cycle Assessment Requirements and Guidelines',

        'category' => 'environment',

        'issuer' => 'ISO',

        'description' => 'Requirements and guidelines for life cycle assessment.',

        'scope' => [

            'manufacturer',

            'brand_owner',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => false,

        'supports_sustainability' => true,

        'supports_traceability' => true,

        'supports_esg' => true,

        'renewal_years' => null,

        'active' => true,

    ],

    [

        'id' => 'iso_46001',

        'name' => 'ISO 46001',

        'full_name' => 'Water Efficiency Management Systems',

        'category' => 'environment',

        'issuer' => 'ISO',

        'description' => 'Water efficiency management system.',

        'scope' => [

            'fiber',

            'yarn',

            'fabric',

            'dyeing',

            'printing',

        ],

        'recognized_markets' => [

            'Global',

        ],

        'supports_quality' => false,

        'supports_sustainability' => true,

        'supports_traceability' => false,

        'supports_esg' => true,

        'renewal_years' => 3,

        'active' => true,

    ],
    
];