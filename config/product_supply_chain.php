<?php

return [

    'polo_shirt' => [

        'sector' => 'apparel',

        'upstream' => [
            'Cotton',
            'Polyester Staple Fiber',
        ],

        'midstream' => [
            'Spinning',
            'Knitting',
            'Dyeing',
        ],

        'downstream' => [
            'Garment Manufacturing',
            'Packaging',
        ],

        'supporting' => [
            'Labels',
            'Buttons',
            'Logistics',
        ],

        'buyers' => [
            'Uniqlo',
            'H&M',
            'Decathlon',
        ],

        'countries' => [
            'Japan',
            'USA',
            'Germany',
        ],
    ],

    'tshirt' => [

        'sector' => 'apparel',

        'upstream' => [
            'Cotton',
        ],

        'midstream' => [
            'Spinning',
            'Knitting',
        ],

        'downstream' => [
            'Garment Manufacturing',
        ],

        'buyers' => [
            'Gap',
            'Target',
        ],

        'countries' => [
            'USA',
            'Canada',
        ],
    ],

    'hoodie' => [

        'sector' => 'apparel',

        'upstream' => [
            'Cotton',
            'Polyester',
        ],

        'midstream' => [
            'Spinning',
            'Brushing',
            'Dyeing',
        ],

        'downstream' => [
            'Garment Manufacturing',
        ],

        'buyers' => [
            'Nike',
            'Adidas',
        ],

        'countries' => [
            'USA',
            'UK',
        ],
    ],

'denim' => [

    'sector' => 'fabric',

    'upstream' => [
        'Cotton',
    ],

    'midstream' => [
        'Spinning',
        'Weaving',
        'Indigo Dyeing',
    ],

    'downstream' => [
        'Finishing',
    ],

    'buyers' => [
        'Levis',
        'Wrangler',
    ],

    'countries' => [
        'USA',
        'Mexico',
    ],
],

'bedsheet' => [

    'sector' => 'home_textile',

    'upstream' => [
        'Cotton',
        'Polyester',
    ],

    'midstream' => [
        'Spinning',
        'Weaving',
        'Printing',
    ],

    'downstream' => [
        'Cut & Sew',
        'Packaging',
    ],

    'buyers' => [
        'IKEA',
        'Walmart',
    ],

    'countries' => [
        'Sweden',
        'USA',
    ],
],
];