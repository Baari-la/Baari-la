<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fiber
    |--------------------------------------------------------------------------
    */

    'fiber' => [

        'slug' => 'fiber',

        'title_en' => 'Fiber',
        'title_id' => 'Serat',

        'description_en' =>
            'Natural and man-made fibers.',

        'description_id' =>
            'Serat alam dan serat buatan.',

        'icon' => '🌾',

        'hs' => [
            '50',
            '51',
            '52',
            '53',
            '54',
            '55',
        ],

        'children' => [

            'natural' => [

                'slug' => 'natural-fiber',

                'title_en' => 'Natural Fiber',
                'title_id' => 'Serat Alam',

                'children' => [
                    'cotton',
                    'wool',
                    'silk',
                    'flax',
                    'hemp',
                    'jute',
                ],
            ],

            'man_made' => [

                'slug' => 'man-made-fiber',

                'title_en' => 'Man-Made Fiber',
                'title_id' => 'Serat Buatan',

                'children' => [
                    'polyester',
                    'nylon',
                    'acrylic',
                    'rayon',
                    'viscose',
                    'spandex',
                    'polypropylene',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Yarn
    |--------------------------------------------------------------------------
    */

    'yarn' => [

        'slug' => 'yarn',

        'title_en' => 'Yarn',
        'title_id' => 'Benang',

        'description_en' =>
            'Spun, filament, fancy, and blended yarn.',

        'description_id' =>
            'Benang pintal, filament, fancy, dan campuran.',

        'icon' => '🧵',

        'hs' => [
            '50',
            '51',
            '52',
            '54',
            '55',
        ],

        'children' => [

            'spun_yarn',
            'filament_yarn',
            'fancy_yarn',
            'blended_yarn',

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fabric
    |--------------------------------------------------------------------------
    */

    'fabric' => [

        'slug' => 'fabric',

        'title_en' => 'Fabric',
        'title_id' => 'Kain',

        'description_en' =>
            'Woven, knitted, and nonwoven fabrics.',

        'description_id' =>
            'Kain tenun, rajut, dan nonwoven.',

        'icon' => '🧶',

        'hs' => [
            '56',
            '57',
            '58',
            '59',
            '60',
        ],

        'children' => [

            'woven',
            'knitted',
            'nonwoven',

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Apparel
    |--------------------------------------------------------------------------
    */

    'apparel' => [

        'slug' => 'apparel',

        'title_en' => 'Apparel',
        'title_id' => 'Pakaian Jadi',

        'description_en' =>
            'Garments and finished textile products.',

        'description_id' =>
            'Produk pakaian jadi dan tekstil jadi.',

        'icon' => '👔',

        'hs' => [
            '61',
            '62',
            '63',
        ],

        'children' => [

            'menswear',
            'womenswear',
            'kidswear',
            'sportswear',
            'workwear',

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Home Textile
    |--------------------------------------------------------------------------
    */

    'home_textile' => [

        'slug' => 'home-textile',

        'title_en' => 'Home Textile',
        'title_id' => 'Tekstil Rumah Tangga',

        'description_en' =>
            'Home furnishing and household textile products.',

        'description_id' =>
            'Produk tekstil untuk rumah tangga.',

        'icon' => '🏠',

        'hs' => [],

    ],

    /*
    |--------------------------------------------------------------------------
    | Technical Textile
    |--------------------------------------------------------------------------
    */

    'technical_textile' => [

        'slug' => 'technical-textile',

        'title_en' => 'Technical Textile',
        'title_id' => 'Tekstil Teknis',

        'description_en' =>
            'Industrial and high-performance textile applications.',

        'description_id' =>
            'Aplikasi tekstil industri dan berperforma tinggi.',

        'icon' => '🛡',

        'hs' => [],

    ],

    /*
    |--------------------------------------------------------------------------
    | Machinery
    |--------------------------------------------------------------------------
    */

    'machinery' => [

        'slug' => 'machinery',

        'title_en' => 'Machinery',
        'title_id' => 'Mesin',

        'description_en' =>
            'Textile machinery and manufacturing equipment.',

        'description_id' =>
            'Mesin dan peralatan manufaktur tekstil.',

        'icon' => '⚙',

        'hs' => [],

    ],

    /*
    |--------------------------------------------------------------------------
    | Chemicals
    |--------------------------------------------------------------------------
    */

    'chemicals' => [

        'slug' => 'chemicals',

        'title_en' => 'Chemicals',
        'title_id' => 'Kimia',

        'description_en' =>
            'Dyes, auxiliaries, and textile chemicals.',

        'description_id' =>
            'Pewarna, bahan pembantu, dan bahan kimia tekstil.',

        'icon' => '🧪',

        'hs' => [],

    ],

    /*
    |--------------------------------------------------------------------------
    | Accessories
    |--------------------------------------------------------------------------
    */

    'accessories' => [

        'slug' => 'accessories',

        'title_en' => 'Accessories',
        'title_id' => 'Aksesoris',

        'description_en' =>
            'Buttons, zippers, labels, and garment accessories.',

        'description_id' =>
            'Kancing, ritsleting, label, dan aksesoris pakaian.',

        'icon' => '🧷',

        'hs' => [],

    ],

];