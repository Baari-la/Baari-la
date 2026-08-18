<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX TEXTILE SECTOR TAXONOMY
|--------------------------------------------------------------------------
|
| Central HS classification layer for Digestex Textile Intelligence.
|
| Main sectors:
| - Fiber
| - Yarn
| - Thread
| - Fabric
| - Garment
| - Home Textile
| - Specialty Textile
| - Technical / Industrial Textile
|
| IMPORTANT:
| This taxonomy is intentionally conservative.
| HS classifications that still require HS-6 / HS-8 validation
| should not be forced into a core sector.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | FIBER
    |--------------------------------------------------------------------------
    */

    'fiber' => [

        'label_en' => 'Fiber',
        'label_id' => 'Serat',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Cotton
            |--------------------------------------------------------------------------
            */

            'cotton' => [

                'label_en' => 'Cotton',
                'label_id' => 'Kapas',

                /*
                | 5201 = Cotton, not carded or combed
                | 5203 = Cotton, carded or combed
                */
                'hs4' => [
                    '5201',
                    '5203',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Wool / Animal Fiber
            |--------------------------------------------------------------------------
            */

            'animal_fiber' => [

                'label_en' => 'Wool / Animal Fiber',
                'label_id' => 'Wol / Serat Hewani',

                'hs4' => [
                    '5101',
                    '5102',
                    '5103',
                    '5104',
                    '5105',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Silk
            |--------------------------------------------------------------------------
            */

            'silk' => [

                'label_en' => 'Silk',
                'label_id' => 'Sutra',

                /*
                | 5001 = Silk-worm cocoons
                | 5002 = Raw silk
                | 5003 = Silk waste
                */
                'hs4' => [
                    '5001',
                    '5002',
                    '5003',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Vegetable Fiber
            |--------------------------------------------------------------------------
            */

            'vegetable_fiber' => [

                'label_en' => 'Bast / Vegetable Fiber',
                'label_id' => 'Serat Nabati',

                /*
                | 5301 = Flax
                | 5302 = True hemp
                | 5303 = Jute / textile bast fibers
                | 5305 = Sisal / other textile fibers
                */
                'hs4' => [
                    '5301',
                    '5302',
                    '5303',
                    '5305',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Man-Made Fiber
            |--------------------------------------------------------------------------
            */

            'man_made' => [

                'label_en' => 'Man-Made Fiber',
                'label_id' => 'Serat Buatan',

                'subsectors' => [

                    /*
                    |--------------------------------------------------------------------------
                    | Synthetic Fiber
                    |--------------------------------------------------------------------------
                    */

                    'synthetic' => [

                        'label_en' => 'Synthetic Fiber',
                        'label_id' => 'Serat Sintetis',

                        /*
                        | 5501 = Synthetic filament tow
                        | 5503 = Synthetic staple fibers
                        | 5505 = Waste of synthetic fibers
                        | 5506 = Processed synthetic staple fibers
                        */
                        'hs4' => [
                            '5501',
                            '5503',
                            '5505',
                            '5506',
                        ],
                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | Artificial Fiber
                    |--------------------------------------------------------------------------
                    */

                    'artificial' => [

                        'label_en' => 'Artificial Fiber',
                        'label_id' => 'Serat Selulosa / Buatan',

                        /*
                        | 5502 = Artificial filament tow
                        | 5504 = Artificial staple fibers
                        | 5507 = Processed artificial staple fibers
                        */
                        'hs4' => [
                            '5502',
                            '5504',
                            '5507',
                        ],
                    ],
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | YARN
    |--------------------------------------------------------------------------
    */

    'yarn' => [

        'label_en' => 'Yarn',
        'label_id' => 'Benang',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Cotton Yarn
            |--------------------------------------------------------------------------
            */

            'cotton_yarn' => [

                'label_en' => 'Cotton Yarn',
                'label_id' => 'Benang Kapas',

                /*
                | 5205 = Cotton yarn
                | 5206 = Cotton yarn
                | 5207 = Cotton yarn put up for retail sale
                */
                'hs4' => [
                    '5205',
                    '5206',
                    '5207',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Silk Yarn
            |--------------------------------------------------------------------------
            */

            'silk_yarn' => [

                'label_en' => 'Silk Yarn',
                'label_id' => 'Benang Sutra',

                /*
                | 5004 = Silk yarn
                | 5005 = Yarn spun from silk waste
                | 5006 = Silk yarn / silk-worm gut
                */
                'hs4' => [
                    '5004',
                    '5005',
                    '5006',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Wool / Animal Yarn
            |--------------------------------------------------------------------------
            */

            'animal_yarn' => [

                'label_en' => 'Wool / Animal Yarn',
                'label_id' => 'Benang Wol / Serat Hewani',

                /*
                | 5106 = Yarn of carded wool
                | 5107 = Yarn of combed wool
                | 5108 = Yarn of fine animal hair
                | 5109 = Wool / fine animal hair yarn for retail
                | 5110 = Coarse animal hair / horsehair yarn
                */
                'hs4' => [
                    '5106',
                    '5107',
                    '5108',
                    '5109',
                    '5110',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Filament Yarn
            |--------------------------------------------------------------------------
            */

            'filament_yarn' => [

                'label_en' => 'Filament Yarn',
                'label_id' => 'Benang Filamen',

                /*
                | 5402 = Synthetic filament yarn
                | 5403 = Artificial filament yarn
                | 5406 = Man-made filament yarn other than sewing thread
                */
                'hs4' => [
                    '5402',
                    '5403',
                    '5406',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Staple Yarn
            |--------------------------------------------------------------------------
            */

            'staple_yarn' => [

                'label_en' => 'Staple Yarn',
                'label_id' => 'Benang Stapel',

                /*
                | 5509 = Synthetic staple yarn
                | 5510 = Artificial staple yarn
                | 5511 = Man-made yarn, retail / other than sewing thread
                */
                'hs4' => [
                    '5509',
                    '5510',
                    '5511',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Specialty Yarn
            |--------------------------------------------------------------------------
            */

            'specialty_yarn' => [

                'label_en' => 'Specialty Yarn',
                'label_id' => 'Benang Khusus',

                /*
                | 5306 = Flax yarn
                | 5307 = Jute / bast yarn
                | 5308 = Other vegetable fiber yarn
                | 5605 = Metallised yarn
                | 5606 = Gimped / chenille yarn
                */
                'hs4' => [
                    '5306',
                    '5307',
                    '5308',
                    '5605',
                    '5606',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Cotton Yarn Waste
            |--------------------------------------------------------------------------
            */

            'cotton_yarn_waste' => [

                'label_en' => 'Cotton Yarn Waste',
                'label_id' => 'Limbah Benang Kapas',

                /*
                | 5202 = Cotton yarn waste including thread waste
                |
                | Kept separate from primary cotton yarn.
                */
                'hs4' => [
                    '5202',
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | THREAD
    |--------------------------------------------------------------------------
    */

    'thread' => [

        'label_en' => 'Thread',
        'label_id' => 'Benang Jahit / Thread',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Cotton Sewing Thread
            |--------------------------------------------------------------------------
            */

            'cotton_sewing_thread' => [

                'label_en' => 'Cotton Sewing Thread',
                'label_id' => 'Benang Jahit Kapas',

                /*
                | 5204 = Cotton sewing thread
                */
                'hs4' => [
                    '5204',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Filament Sewing Thread
            |--------------------------------------------------------------------------
            */

            'filament_sewing_thread' => [

                'label_en' => 'Filament Sewing Thread',
                'label_id' => 'Benang Jahit Filamen',

                /*
                | 5401 = Sewing thread of man-made filaments
                */
                'hs4' => [
                    '5401',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Synthetic Staple Sewing Thread
            |--------------------------------------------------------------------------
            */

            'synthetic_staple_sewing_thread' => [

                'label_en' => 'Synthetic Staple Sewing Thread',
                'label_id' => 'Benang Jahit Stapel Sintetis',

                /*
                | 5508 = Sewing thread of synthetic staple fibres
                */
                'hs4' => [
                    '5508',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Specialty / Technical Thread
            |--------------------------------------------------------------------------
            */

            'specialty_technical_thread' => [

                'label_en' => 'Specialty / Technical Thread',
                'label_id' => 'Thread Khusus / Teknis',

                /*
                | Deliberately empty.
                |
                | These will be classified at HS-6 / HS-8 only
                | after a dedicated Thread audit, especially for
                | Coats-related products.
                */
                'hs4' => [],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | FABRIC
    |--------------------------------------------------------------------------
    */

    'fabric' => [

        'label_en' => 'Fabric',
        'label_id' => 'Kain',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Woven Fabric
            |--------------------------------------------------------------------------
            */

            'woven' => [

                'label_en' => 'Woven Fabric',
                'label_id' => 'Kain Woven',

                /*
                | Silk       : 5007
                | Wool       : 5111–5113
                | Cotton     : 5208–5212
                | Vegetable  : 5309–5311
                | MMF        : 5407–5408, 5512–5516
                */
                'hs4' => [

                    // Silk
                    '5007',

                    // Wool / animal hair
                    '5111',
                    '5112',
                    '5113',

                    // Cotton
                    '5208',
                    '5209',
                    '5210',
                    '5211',
                    '5212',

                    // Vegetable fibers
                    '5309',
                    '5310',
                    '5311',

                    // Man-made filament
                    '5407',
                    '5408',

                    // Man-made staple
                    '5512',
                    '5513',
                    '5514',
                    '5515',
                    '5516',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Knitted Fabric
            |--------------------------------------------------------------------------
            */

            'knitted' => [

                'label_en' => 'Knitted Fabric',
                'label_id' => 'Kain Rajut',

                /*
                | Chapter 60:
                | Knitted / crocheted fabrics
                */
                'hs4' => [
                    '6001',
                    '6002',
                    '6003',
                    '6004',
                    '6005',
                    '6006',
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | GARMENT
    |--------------------------------------------------------------------------
    */

    'garment' => [

        'label_en' => 'Garment',
        'label_id' => 'Garmen',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Knitwear
            |--------------------------------------------------------------------------
            */

            'knitwear' => [

                'label_en' => 'Knitwear',
                'label_id' => 'Pakaian Rajut',

                'chapters' => [
                    '61',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Woven Apparel
            |--------------------------------------------------------------------------
            */

            'woven_apparel' => [

                'label_en' => 'Woven Apparel',
                'label_id' => 'Pakaian Woven',

                'chapters' => [
                    '62',
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | HOME TEXTILE
    |--------------------------------------------------------------------------
    */

    'home_textile' => [

        'label_en' => 'Home Textile',
        'label_id' => 'Tekstil Rumah Tangga',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Bedding / Linen
            |--------------------------------------------------------------------------
            */

            'linen_bedding' => [

                'label_en' => 'Linen / Bedding',
                'label_id' => 'Linen / Bedding',

                /*
                | 6301 = Blankets
                | 6302 = Bed / kitchen / toilet linen
                */
                'hs4' => [
                    '6301',
                    '6302',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Curtains
            |--------------------------------------------------------------------------
            */

            'curtains' => [

                'label_en' => 'Curtains',
                'label_id' => 'Tirai',

                /*
                | 6303 = Curtains / interior blinds / valances
                */
                'hs4' => [
                    '6303',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Other Home Textile
            |--------------------------------------------------------------------------
            */

            'other_home_textile' => [

                'label_en' => 'Other Home Textile',
                'label_id' => 'Tekstil Rumah Tangga Lainnya',

                /*
                | 6304 = Other furnishing articles
                */
                'hs4' => [
                    '6304',
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | SPECIALTY TEXTILE
    |--------------------------------------------------------------------------
    */

    'specialty_textile' => [

        'label_en' => 'Specialty Textile',
        'label_id' => 'Tekstil Khusus',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Pile / Chenille Fabric
            |--------------------------------------------------------------------------
            */

            'pile_chenille_fabric' => [

                'label_en' => 'Pile / Chenille Fabric',
                'label_id' => 'Kain Pile / Chenille',

                /*
                | 5801 = Woven pile fabrics and chenille fabrics
                |
                | Material and coated/non-coated attributes
                | can later be derived at HS-8 level.
                */
                'hs4' => [
                    '5801',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Floor Covering / Carpet
            |--------------------------------------------------------------------------
            */

            'floor_covering' => [

                'label_en' => 'Floor Covering / Carpet',
                'label_id' => 'Penutup Lantai / Karpet',

                /*
                | Chapter 57:
                | 5701–5705
                */
                'hs4' => [
                    '5701',
                    '5702',
                    '5703',
                    '5704',
                    '5705',
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | TECHNICAL / INDUSTRIAL TEXTILE
    |--------------------------------------------------------------------------
    */

    'technical_textile' => [

        'label_en' => 'Technical / Industrial Textile',
        'label_id' => 'Tekstil Teknis / Industri',

        'subsectors' => [

            /*
            |--------------------------------------------------------------------------
            | Nonwoven / Felt
            |--------------------------------------------------------------------------
            */

            'nonwoven_felt' => [

                'label_en' => 'Nonwoven / Felt',
                'label_id' => 'Nonwoven / Felt',

                /*
                | 5601 = Wadding / articles thereof
                | 5602 = Felt
                | 5603 = Nonwovens
                */
                'hs4' => [
                    '5601',
                    '5602',
                    '5603',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Industrial Reinforcement Fabric
            |--------------------------------------------------------------------------
            */

            'industrial_reinforcement_fabric' => [

                'label_en' => 'Industrial Reinforcement Fabric',
                'label_id' => 'Kain Penguat Industri',

                /*
                | 5902 = Tire cord fabric
                */
                'hs4' => [
                    '5902',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Coated / Laminated Fabric
            |--------------------------------------------------------------------------
            */

            'coated_laminated_fabric' => [

                'label_en' => 'Coated / Laminated Textile Fabric',
                'label_id' => 'Kain Tekstil Coated / Laminasi',

                /*
                | 5903 = Textile fabrics impregnated, coated,
                | covered or laminated with plastics.
                |
                | Important applications:
                | - Bags
                | - Tents
                | - Outdoor products
                | - Industrial applications
                */
                'hs4' => [
                    '5903',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Industrial Yarn / Cord
            |--------------------------------------------------------------------------
            */

            'industrial_yarn_cord' => [

                'label_en' => 'Industrial Yarn / Cord',
                'label_id' => 'Benang / Cord Industri',

                /*
                | 5607 = Twine / cordage / industrial textile cord
                */
                'hs4' => [
                    '5607',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Technical Textile Articles
            |--------------------------------------------------------------------------
            */

            'technical_textile_articles' => [

                'label_en' => 'Technical Textile Articles',
                'label_id' => 'Artikel Tekstil Teknis',

                /*
                | 5911 = Textile products / articles for technical uses
                */
                'hs4' => [
                    '5911',
                ],
            ],
        ],
    ],
];