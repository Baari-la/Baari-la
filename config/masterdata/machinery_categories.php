<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Machinery Categories
|--------------------------------------------------------------------------
|
| Master categories for textile machinery.
|
| Used by:
|
| • MachineryService
| • Company Intelligence
| • Executive AI
| • Company Passport
| • Supply Chain Recommendation Engine
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Fiber Manufacturing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'fiber_manufacturing',

        'label' => 'Fiber Manufacturing',

        'description' => 'Machines for synthetic and natural fiber production.',

        'icon' => '🧶',

        'color' => '#2563EB',

        'priority' => 10,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Spinning
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'spinning',

        'label' => 'Spinning',

        'description' => 'Spinning preparation and yarn production machines.',

        'icon' => '🧵',

        'color' => '#0EA5E9',

        'priority' => 20,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Yarn Processing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'yarn_processing',

        'label' => 'Yarn Processing',

        'description' => 'Twisting, winding and texturizing machines.',

        'icon' => '🪢',

        'color' => '#0284C7',

        'priority' => 30,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Weaving
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'weaving',

        'label' => 'Weaving',

        'description' => 'Weaving preparation and weaving machines.',

        'icon' => '🪡',

        'color' => '#7C3AED',

        'priority' => 40,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Knitting
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'knitting',

        'label' => 'Knitting',

        'description' => 'Circular and warp knitting machinery.',

        'icon' => '🧶',

        'color' => '#9333EA',

        'priority' => 50,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Nonwoven
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'nonwoven',

        'label' => 'Nonwoven',

        'description' => 'Nonwoven production machinery.',

        'icon' => '🧻',

        'color' => '#14B8A6',

        'priority' => 60,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Dyeing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'dyeing',

        'label' => 'Dyeing',

        'description' => 'Yarn and fabric dyeing machinery.',

        'icon' => '🎨',

        'color' => '#EC4899',

        'priority' => 70,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Printing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'printing',

        'label' => 'Printing',

        'description' => 'Textile printing equipment.',

        'icon' => '🖨️',

        'color' => '#D946EF',

        'priority' => 80,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Finishing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'finishing',

        'label' => 'Finishing',

        'description' => 'Finishing and coating machinery.',

        'icon' => '✨',

        'color' => '#F97316',

        'priority' => 90,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Garment Manufacturing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'garment',

        'label' => 'Garment Manufacturing',

        'description' => 'Cutting, sewing and garment finishing machinery.',

        'icon' => '👔',

        'color' => '#2563EB',

        'priority' => 100,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Embroidery
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'embroidery',

        'label' => 'Embroidery',

        'description' => 'Embroidery machinery.',

        'icon' => '🪡',

        'color' => '#8B5CF6',

        'priority' => 110,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Textile Testing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'testing',

        'label' => 'Testing & Laboratory',

        'description' => 'Testing laboratory equipment.',

        'icon' => '🔬',

        'color' => '#16A34A',

        'priority' => 120,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'utilities',

        'label' => 'Utilities',

        'description' => 'Boilers, compressors, chillers and utilities.',

        'icon' => '⚡',

        'color' => '#F59E0B',

        'priority' => 130,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Automation
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'automation',

        'label' => 'Automation & Industry 4.0',

        'description' => 'Automation, robotics and smart manufacturing.',

        'icon' => '🤖',

        'color' => '#0F766E',

        'priority' => 140,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Sustainability
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'environment',

        'label' => 'Environmental Equipment',

        'description' => 'Water treatment, recycling and environmental systems.',

        'icon' => '♻️',

        'color' => '#22C55E',

        'priority' => 150,

        'active' => true,

    ],

];