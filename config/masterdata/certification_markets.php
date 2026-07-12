<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Certification Markets
|--------------------------------------------------------------------------
|
| Defines export markets where certifications are commonly recognized
| or frequently requested.
|
| Used by:
|
| • Executive AI
| • Export Readiness
| • Buyer Matching
| • Company Intelligence
|
*/

return [

    [

        'id' => 'global',

        'label' => 'Global',

        'description' => 'Recognized worldwide.',

        'priority' => 10,

        'active' => true,

    ],

    [

        'id' => 'eu',

        'label' => 'European Union',

        'description' => 'European Union member countries.',

        'priority' => 20,

        'active' => true,

    ],

    [

        'id' => 'usa',

        'label' => 'United States',

        'description' => 'United States market.',

        'priority' => 30,

        'active' => true,

    ],

    [

        'id' => 'canada',

        'label' => 'Canada',

        'description' => 'Canadian market.',

        'priority' => 40,

        'active' => true,

    ],

    [

        'id' => 'uk',

        'label' => 'United Kingdom',

        'description' => 'United Kingdom market.',

        'priority' => 50,

        'active' => true,

    ],

    [

        'id' => 'japan',

        'label' => 'Japan',

        'description' => 'Japanese market.',

        'priority' => 60,

        'active' => true,

    ],

    [

        'id' => 'south_korea',

        'label' => 'South Korea',

        'description' => 'South Korean market.',

        'priority' => 70,

        'active' => true,

    ],

    [

        'id' => 'asean',

        'label' => 'ASEAN',

        'description' => 'Association of Southeast Asian Nations.',

        'priority' => 80,

        'active' => true,

    ],

    [

        'id' => 'china',

        'label' => 'China',

        'description' => 'Chinese market.',

        'priority' => 90,

        'active' => true,

    ],

    [

        'id' => 'india',

        'label' => 'India',

        'description' => 'Indian market.',

        'priority' => 100,

        'active' => true,

    ],

    [

        'id' => 'middle_east',

        'label' => 'Middle East',

        'description' => 'Middle East markets.',

        'priority' => 110,

        'active' => true,

    ],

    [

        'id' => 'latin_america',

        'label' => 'Latin America',

        'description' => 'Central and South America.',

        'priority' => 120,

        'active' => true,

    ],

    [

        'id' => 'africa',

        'label' => 'Africa',

        'description' => 'African markets.',

        'priority' => 130,

        'active' => true,

    ],

    [

        'id' => 'australia',

        'label' => 'Australia & New Zealand',

        'description' => 'Australia and New Zealand.',

        'priority' => 140,

        'active' => true,

    ],

];