<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Industry Segments
|--------------------------------------------------------------------------
|
| Defines major industry segments across the global textile ecosystem.
|
| Used by:
|
| • BusinessRoleService
| • Company Intelligence
| • Executive AI
| • Buyer Discovery
| • Supply Chain Recommendation Engine
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Raw Materials
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'natural_fibers',

        'ecosystem' => 'raw_materials',

        'label' => 'Natural Fibers',

        'description' => 'Cotton, wool, silk, flax, hemp and other natural fibers.',

        'priority' => 10,

        'active' => true,

    ],

    [

        'id' => 'man_made_fibers',

        'ecosystem' => 'raw_materials',

        'label' => 'Man-Made Fibers',

        'description' => 'Synthetic and regenerated fibers.',

        'priority' => 20,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Fiber
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'fiber_production',

        'ecosystem' => 'fiber',

        'label' => 'Fiber Production',

        'description' => 'Fiber manufacturing.',

        'priority' => 100,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Yarn
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'spinning',

        'ecosystem' => 'yarn',

        'label' => 'Spinning',

        'description' => 'Yarn spinning industry.',

        'priority' => 200,

        'active' => true,

    ],

    [

        'id' => 'texturizing',

        'ecosystem' => 'yarn',

        'label' => 'Texturizing',

        'description' => 'DTY, ATY and air texturizing.',

        'priority' => 210,

        'active' => true,

    ],

    [

        'id' => 'twisting',

        'ecosystem' => 'yarn',

        'label' => 'Twisting',

        'description' => 'Yarn twisting and doubling.',

        'priority' => 220,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Fabric
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'weaving',

        'ecosystem' => 'fabric',

        'label' => 'Weaving',

        'description' => 'Woven fabric manufacturing.',

        'priority' => 300,

        'active' => true,

    ],

    [

        'id' => 'knitting',

        'ecosystem' => 'fabric',

        'label' => 'Knitting',

        'description' => 'Knitted fabric manufacturing.',

        'priority' => 310,

        'active' => true,

    ],

    [

        'id' => 'nonwoven_production',

        'ecosystem' => 'nonwoven',

        'label' => 'Nonwoven Production',

        'description' => 'Spunbond, meltblown and needle punch.',

        'priority' => 320,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Dyeing & Finishing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'piece_dyeing',

        'ecosystem' => 'dyeing_printing_finishing',

        'label' => 'Piece Dyeing',

        'description' => 'Fabric dyeing operations.',

        'priority' => 400,

        'active' => true,

    ],

    [

        'id' => 'yarn_dyeing',

        'ecosystem' => 'dyeing_printing_finishing',

        'label' => 'Yarn Dyeing',

        'description' => 'Yarn dyeing operations.',

        'priority' => 410,

        'active' => true,

    ],

    [

        'id' => 'digital_printing',

        'ecosystem' => 'digital_textile',

        'label' => 'Digital Textile Printing',

        'description' => 'Industrial digital textile printing.',

        'priority' => 420,

        'active' => true,

    ],

    [

        'id' => 'rotary_printing',

        'ecosystem' => 'dyeing_printing_finishing',

        'label' => 'Rotary Printing',

        'description' => 'Rotary screen printing.',

        'priority' => 430,

        'active' => true,

    ],

    [

        'id' => 'textile_finishing',

        'ecosystem' => 'dyeing_printing_finishing',

        'label' => 'Textile Finishing',

        'description' => 'Mechanical and chemical finishing.',

        'priority' => 440,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Garment
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'cut_make_trim',

        'ecosystem' => 'garment',

        'label' => 'Cut Make Trim (CMT)',

        'description' => 'Garment assembly services.',

        'priority' => 500,

        'active' => true,

    ],

    [

        'id' => 'full_package',

        'ecosystem' => 'garment',

        'label' => 'Full Package Manufacturing',

        'description' => 'OEM / ODM garment production.',

        'priority' => 510,

        'active' => true,

    ],

    [

        'id' => 'fashion_manufacturing',

        'ecosystem' => 'garment',

        'label' => 'Fashion Manufacturing',

        'description' => 'Fashion apparel production.',

        'priority' => 520,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Machinery
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'textile_machinery',

        'ecosystem' => 'machinery',

        'label' => 'Textile Machinery',

        'description' => 'Machinery manufacturers and distributors.',

        'priority' => 600,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Chemicals
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'textile_chemicals',

        'ecosystem' => 'chemicals',

        'label' => 'Textile Chemicals',

        'description' => 'Dyes, auxiliaries and specialty chemicals.',

        'priority' => 700,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Digital Solutions
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'erp_plm_mes',

        'ecosystem' => 'digital_solutions',

        'label' => 'ERP, PLM & MES',

        'description' => 'Enterprise software solutions.',

        'priority' => 800,

        'active' => true,

    ],

    [

        'id' => 'artificial_intelligence',

        'ecosystem' => 'digital_solutions',

        'label' => 'Artificial Intelligence',

        'description' => 'AI-powered textile solutions.',

        'priority' => 810,

        'active' => true,

    ],

    [

        'id' => 'iot_industry_4',

        'ecosystem' => 'digital_solutions',

        'label' => 'IoT & Industry 4.0',

        'description' => 'Smart manufacturing technologies.',

        'priority' => 820,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Sustainability
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'circular_textiles',

        'ecosystem' => 'sustainability',

        'label' => 'Circular Textiles',

        'description' => 'Circular economy and textile recycling.',

        'priority' => 900,

        'active' => true,

    ],

    [

        'id' => 'renewable_energy',

        'ecosystem' => 'sustainability',

        'label' => 'Renewable Energy',

        'description' => 'Renewable energy implementation.',

        'priority' => 910,

        'active' => true,

    ],

    [

        'id' => 'water_management',

        'ecosystem' => 'sustainability',

        'label' => 'Water Management',

        'description' => 'Water conservation and recycling.',

        'priority' => 920,

        'active' => true,

    ],

];