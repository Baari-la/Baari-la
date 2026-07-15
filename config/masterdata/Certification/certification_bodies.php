<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Certification Bodies
|--------------------------------------------------------------------------
|
| Global Certification Organizations
|
| Used by:
|
| • CertificationService
| • Company Intelligence
| • Executive AI
| • Compliance Engine
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | International Standards
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'iso',

        'name' => 'International Organization for Standardization',

        'short_name' => 'ISO',

        'country' => 'CH',

        'website' => 'https://www.iso.org',

        'category' => 'international_standard',

        'description' => 'Global developer of international standards.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'iec',

        'name' => 'International Electrotechnical Commission',

        'short_name' => 'IEC',

        'country' => 'CH',

        'website' => 'https://www.iec.ch',

        'category' => 'international_standard',

        'description' => 'International electrotechnical standards organization.',

        'recognized_globally' => true,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Textile Sustainability
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'textile_exchange',

        'name' => 'Textile Exchange',

        'short_name' => 'Textile Exchange',

        'country' => 'US',

        'website' => 'https://textileexchange.org',

        'category' => 'textile',

        'description' => 'Global nonprofit driving preferred fibers and materials.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'gots',

        'name' => 'Global Standard gGmbH',

        'short_name' => 'GOTS',

        'country' => 'DE',

        'website' => 'https://global-standard.org',

        'category' => 'textile',

        'description' => 'Owner of the Global Organic Textile Standard.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'oeko_tex',

        'name' => 'OEKO-TEX® Association',

        'short_name' => 'OEKO-TEX®',

        'country' => 'CH',

        'website' => 'https://www.oeko-tex.com',

        'category' => 'textile',

        'description' => 'International association for textile safety and sustainability certifications.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'bluesign',

        'name' => 'bluesign technologies ag',

        'short_name' => 'bluesign®',

        'country' => 'CH',

        'website' => 'https://www.bluesign.com',

        'category' => 'textile',

        'description' => 'Provider of the bluesign® sustainability system.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'zdhc',

        'name' => 'Zero Discharge of Hazardous Chemicals Foundation',

        'short_name' => 'ZDHC',

        'country' => 'NL',

        'website' => 'https://www.roadmaptozero.com',

        'category' => 'chemical',

        'description' => 'Global foundation promoting safer chemical management.',

        'recognized_globally' => true,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Social Compliance
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'amfori',

        'name' => 'amfori',

        'short_name' => 'amfori',

        'country' => 'BE',

        'website' => 'https://www.amfori.org',

        'category' => 'social',

        'description' => 'Global business association supporting responsible trade.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'sedex',

        'name' => 'Sedex',

        'short_name' => 'Sedex',

        'country' => 'GB',

        'website' => 'https://www.sedex.com',

        'category' => 'social',

        'description' => 'Global platform for ethical trade and supply chain transparency.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'wrap',

        'name' => 'Worldwide Responsible Accredited Production',

        'short_name' => 'WRAP',

        'country' => 'US',

        'website' => 'https://www.wrapcompliance.org',

        'category' => 'social',

        'description' => 'Certification program for socially responsible manufacturing.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'sai',

        'name' => 'Social Accountability International',

        'short_name' => 'SAI',

        'country' => 'US',

        'website' => 'https://sa-intl.org',

        'category' => 'social',

        'description' => 'Developer of SA8000 social accountability standard.',

        'recognized_globally' => true,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | ESG & Sustainability
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'ecovadis',

        'name' => 'EcoVadis',

        'short_name' => 'EcoVadis',

        'country' => 'FR',

        'website' => 'https://ecovadis.com',

        'category' => 'esg',

        'description' => 'Global sustainability ratings provider.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'cdp',

        'name' => 'CDP',

        'short_name' => 'CDP',

        'country' => 'GB',

        'website' => 'https://www.cdp.net',

        'category' => 'esg',

        'description' => 'Global environmental disclosure system.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'un_global_compact',

        'name' => 'United Nations Global Compact',

        'short_name' => 'UN Global Compact',

        'country' => 'US',

        'website' => 'https://unglobalcompact.org',

        'category' => 'esg',

        'description' => 'United Nations corporate sustainability initiative.',

        'recognized_globally' => true,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Forest & Packaging
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'fsc',

        'name' => 'Forest Stewardship Council',

        'short_name' => 'FSC',

        'country' => 'DE',

        'website' => 'https://fsc.org',

        'category' => 'forest',

        'description' => 'International certification for responsible forest management.',

        'recognized_globally' => true,

        'active' => true,

    ],

    [

        'id' => 'pefc',

        'name' => 'Programme for the Endorsement of Forest Certification',

        'short_name' => 'PEFC',

        'country' => 'CH',

        'website' => 'https://www.pefc.org',

        'category' => 'forest',

        'description' => 'Global forest certification organization.',

        'recognized_globally' => true,

        'active' => true,

    ],

];