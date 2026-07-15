<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Business Roles
|--------------------------------------------------------------------------
|
| Global Textile Business Role Knowledge Base
|
| Part 1
| Raw Material & Fiber
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Natural Fiber
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'natural_fiber_producer',

        'label' => 'Natural Fiber Producer',

        'description' => 'Produces natural textile fibers.',

        'category' => 'raw_material',

        'icon' => '🌿',

        'color' => '#22C55E',

        'priority' => 10,

        'upstream' => [],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'cotton_producer',

        'label' => 'Cotton Producer',

        'description' => 'Produces raw cotton fiber.',

        'category' => 'raw_material',

        'icon' => '🌱',

        'color' => '#84CC16',

        'priority' => 20,

        'upstream' => [

            'natural_fiber_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'wool_producer',

        'label' => 'Wool Producer',

        'description' => 'Produces wool fiber.',

        'category' => 'raw_material',

        'icon' => '🐑',

        'color' => '#A3A3A3',

        'priority' => 30,

        'upstream' => [

            'natural_fiber_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'silk_producer',

        'label' => 'Silk Producer',

        'description' => 'Produces natural silk fiber.',

        'category' => 'raw_material',

        'icon' => '🕸️',

        'color' => '#EAB308',

        'priority' => 40,

        'upstream' => [

            'natural_fiber_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'flax_producer',

        'label' => 'Flax Producer',

        'description' => 'Produces flax fiber for linen.',

        'category' => 'raw_material',

        'icon' => '🌾',

        'color' => '#65A30D',

        'priority' => 50,

        'upstream' => [

            'natural_fiber_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'hemp_producer',

        'label' => 'Hemp Producer',

        'description' => 'Produces hemp fiber.',

        'category' => 'raw_material',

        'icon' => '🌿',

        'color' => '#15803D',

        'priority' => 60,

        'upstream' => [

            'natural_fiber_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'bamboo_fiber_producer',

        'label' => 'Bamboo Fiber Producer',

        'description' => 'Produces bamboo-based textile fiber.',

        'category' => 'raw_material',

        'icon' => '🎋',

        'color' => '#16A34A',

        'priority' => 70,

        'upstream' => [

            'natural_fiber_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Synthetic Polymer
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'synthetic_polymer_producer',

        'label' => 'Synthetic Polymer Producer',

        'description' => 'Produces textile-grade polymers.',

        'category' => 'raw_material',

        'icon' => '⚗️',

        'color' => '#3B82F6',

        'priority' => 80,

        'upstream' => [],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'polyester_polymer_producer',

        'label' => 'Polyester Polymer Producer',

        'description' => 'Produces polyester polymer chips.',

        'category' => 'raw_material',

        'icon' => '🧪',

        'color' => '#2563EB',

        'priority' => 90,

        'upstream' => [

            'synthetic_polymer_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'nylon_polymer_producer',

        'label' => 'Nylon Polymer Producer',

        'description' => 'Produces nylon polymer materials.',

        'category' => 'raw_material',

        'icon' => '🧪',

        'color' => '#1D4ED8',

        'priority' => 100,

        'upstream' => [

            'synthetic_polymer_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'acrylic_polymer_producer',

        'label' => 'Acrylic Polymer Producer',

        'description' => 'Produces acrylic polymer materials.',

        'category' => 'raw_material',

        'icon' => '🧪',

        'color' => '#2563EB',

        'priority' => 110,

        'upstream' => [

            'synthetic_polymer_producer',

        ],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'viscose_producer',

        'label' => 'Viscose Producer',

        'description' => 'Produces viscose raw materials.',

        'category' => 'raw_material',

        'icon' => '🌲',

        'color' => '#0F766E',

        'priority' => 120,

        'upstream' => [],

        'downstream' => [

            'fiber_manufacturer',

        ],

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Fiber Manufacturing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'fiber_manufacturer',

        'label' => 'Fiber Manufacturer',

        'description' => 'Manufactures textile fibers from natural or synthetic raw materials.',

        'category' => 'fiber',

        'icon' => '🧵',

        'color' => '#2563EB',

        'priority' => 130,

        'upstream' => [

            'natural_fiber_producer',

            'synthetic_polymer_producer',

            'polyester_polymer_producer',

            'nylon_polymer_producer',

            'acrylic_polymer_producer',

            'viscose_producer',

        ],

        'downstream' => [

            'yarn_spinner',

        ],

        'active' => true,

    ],

    [

        'id' => 'synthetic_fiber_manufacturer',

        'label' => 'Synthetic Fiber Manufacturer',

        'description' => 'Manufactures polyester, nylon and acrylic fibers.',

        'category' => 'fiber',

        'icon' => '🧶',

        'color' => '#3B82F6',

        'priority' => 140,

        'upstream' => [

            'synthetic_polymer_producer',

        ],

        'downstream' => [

            'yarn_spinner',

        ],

        'active' => true,

    ],

    [

        'id' => 'staple_fiber_manufacturer',

        'label' => 'Staple Fiber Manufacturer',

        'description' => 'Produces staple fibers for spinning.',

        'category' => 'fiber',

        'icon' => '🧵',

        'color' => '#06B6D4',

        'priority' => 150,

        'upstream' => [

            'fiber_manufacturer',

        ],

        'downstream' => [

            'yarn_spinner',

        ],

        'active' => true,

    ],

    [

        'id' => 'filament_fiber_manufacturer',

        'label' => 'Filament Fiber Manufacturer',

        'description' => 'Produces continuous filament fibers.',

        'category' => 'fiber',

        'icon' => '🧵',

        'color' => '#0EA5E9',

        'priority' => 160,

        'upstream' => [

            'fiber_manufacturer',

        ],

        'downstream' => [

            'texturizing_company',

        ],

        'active' => true,

    ],

    [

        'id' => 'recycled_fiber_producer',

        'label' => 'Recycled Fiber Producer',

        'description' => 'Produces recycled textile fibers from post-industrial or post-consumer materials.',

        'category' => 'fiber',

        'icon' => '♻️',

        'color' => '#16A34A',

        'priority' => 170,

        'upstream' => [],

        'downstream' => [

            'yarn_spinner',

        ],

        'active' => true,

    ],

    [

        'id' => 'bio_based_fiber_producer',

        'label' => 'Bio-based Fiber Producer',

        'description' => 'Produces renewable and bio-based textile fibers.',

        'category' => 'fiber',

        'icon' => '🌍',

        'color' => '#22C55E',

        'priority' => 180,

        'upstream' => [],

        'downstream' => [

            'yarn_spinner',

        ],

        'active' => true,

    ],
    /*
    |--------------------------------------------------------------------------
    | Yarn Industry
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'yarn_spinner',

        'label' => 'Yarn Spinner',

        'description' => 'Produces spun yarn from natural, synthetic or recycled fibers.',

        'category' => 'yarn',

        'icon' => '🧶',

        'color' => '#2563EB',

        'priority' => 200,

        'upstream' => [

            'fiber_manufacturer',

            'staple_fiber_manufacturer',

            'recycled_fiber_producer',

            'bio_based_fiber_producer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

            'yarn_dyeing',

        ],

        'active' => true,

    ],

    [

        'id' => 'ring_spinning_mill',

        'label' => 'Ring Spinning Mill',

        'description' => 'Produces yarn using conventional ring spinning technology.',

        'category' => 'yarn',

        'icon' => '🧵',

        'color' => '#0EA5E9',

        'priority' => 210,

        'upstream' => [

            'staple_fiber_manufacturer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'open_end_spinning_mill',

        'label' => 'Open End Spinning Mill',

        'description' => 'Produces yarn using rotor/open-end spinning technology.',

        'category' => 'yarn',

        'icon' => '🧵',

        'color' => '#06B6D4',

        'priority' => 220,

        'upstream' => [

            'staple_fiber_manufacturer',

            'recycled_fiber_producer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'compact_spinning_mill',

        'label' => 'Compact Spinning Mill',

        'description' => 'Produces premium yarn using compact spinning technology.',

        'category' => 'yarn',

        'icon' => '🧶',

        'color' => '#0284C7',

        'priority' => 230,

        'upstream' => [

            'staple_fiber_manufacturer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'texturizing_company',

        'label' => 'Texturizing Company',

        'description' => 'Produces textured filament yarn such as DTY and ATY.',

        'category' => 'yarn',

        'icon' => '🌀',

        'color' => '#8B5CF6',

        'priority' => 240,

        'upstream' => [

            'filament_fiber_manufacturer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'poy_producer',

        'label' => 'POY Producer',

        'description' => 'Produces Partially Oriented Yarn (POY).',

        'category' => 'yarn',

        'icon' => '🧶',

        'color' => '#6366F1',

        'priority' => 250,

        'upstream' => [

            'filament_fiber_manufacturer',

        ],

        'downstream' => [

            'texturizing_company',

        ],

        'active' => true,

    ],

    [

        'id' => 'fdy_producer',

        'label' => 'FDY Producer',

        'description' => 'Produces Fully Drawn Yarn (FDY).',

        'category' => 'yarn',

        'icon' => '🧶',

        'color' => '#4F46E5',

        'priority' => 260,

        'upstream' => [

            'filament_fiber_manufacturer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'dty_producer',

        'label' => 'DTY Producer',

        'description' => 'Produces Draw Textured Yarn (DTY).',

        'category' => 'yarn',

        'icon' => '🧶',

        'color' => '#7C3AED',

        'priority' => 270,

        'upstream' => [

            'texturizing_company',

            'poy_producer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'aty_producer',

        'label' => 'ATY Producer',

        'description' => 'Produces Air Textured Yarn (ATY).',

        'category' => 'yarn',

        'icon' => '🧶',

        'color' => '#9333EA',

        'priority' => 280,

        'upstream' => [

            'texturizing_company',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'yarn_twisting',

        'label' => 'Yarn Twisting',

        'description' => 'Provides yarn twisting and doubling services.',

        'category' => 'yarn',

        'icon' => '🧵',

        'color' => '#0891B2',

        'priority' => 290,

        'upstream' => [

            'yarn_spinner',

            'dty_producer',

            'fdy_producer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'yarn_dyeing',

        'label' => 'Yarn Dyeing',

        'description' => 'Provides yarn dyeing services.',

        'category' => 'yarn',

        'icon' => '🎨',

        'color' => '#EC4899',

        'priority' => 300,

        'upstream' => [

            'yarn_spinner',

            'dty_producer',

            'fdy_producer',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'fancy_yarn_producer',

        'label' => 'Fancy Yarn Producer',

        'description' => 'Produces specialty and fancy yarns.',

        'category' => 'yarn',

        'icon' => '✨',

        'color' => '#F59E0B',

        'priority' => 310,

        'upstream' => [

            'yarn_spinner',

        ],

        'downstream' => [

            'weaving_mill',

            'knitting_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'sewing_thread_manufacturer',

        'label' => 'Sewing Thread Manufacturer',

        'description' => 'Produces industrial and apparel sewing threads.',

        'category' => 'yarn',

        'icon' => '🪡',

        'color' => '#14B8A6',

        'priority' => 320,

        'upstream' => [

            'yarn_spinner',

            'dty_producer',

            'fdy_producer',

        ],

        'downstream' => [

            'garment_manufacturer',

            'home_textile_manufacturer',

            'technical_textile_manufacturer',

        ],

        'active' => true,

    ],
    
        /*
    |--------------------------------------------------------------------------
    | Fabric Industry
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'weaving_mill',

        'label' => 'Weaving Mill',

        'description' => 'Manufactures woven fabrics.',

        'category' => 'fabric',

        'icon' => '🧵',

        'color' => '#2563EB',

        'priority' => 400,

        'upstream' => [

            'yarn_spinner',
            'ring_spinning_mill',
            'open_end_spinning_mill',
            'compact_spinning_mill',
            'dty_producer',
            'fdy_producer',
            'yarn_twisting',

        ],

        'downstream' => [

            'dyeing_finishing_mill',
            'printing_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'knitting_mill',

        'label' => 'Knitting Mill',

        'description' => 'Manufactures knitted fabrics.',

        'category' => 'fabric',

        'icon' => '🪡',

        'color' => '#0EA5E9',

        'priority' => 410,

        'upstream' => [

            'yarn_spinner',
            'dty_producer',
            'fdy_producer',
            'texturizing_company',

        ],

        'downstream' => [

            'dyeing_finishing_mill',
            'printing_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'warp_knitting_mill',

        'label' => 'Warp Knitting Mill',

        'description' => 'Produces warp knitted fabrics.',

        'category' => 'fabric',

        'icon' => '🪢',

        'color' => '#0284C7',

        'priority' => 420,

        'upstream' => [

            'fdy_producer',
            'dty_producer',

        ],

        'downstream' => [

            'dyeing_finishing_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'circular_knitting_mill',

        'label' => 'Circular Knitting Mill',

        'description' => 'Produces circular knitted fabrics.',

        'category' => 'fabric',

        'icon' => '🧶',

        'color' => '#06B6D4',

        'priority' => 430,

        'upstream' => [

            'yarn_spinner',
            'dty_producer',

        ],

        'downstream' => [

            'dyeing_finishing_mill',

        ],

        'active' => true,

    ],

    [

        'id' => 'nonwoven_manufacturer',

        'label' => 'Nonwoven Manufacturer',

        'description' => 'Produces spunbond, meltblown and needle punched nonwoven fabrics.',

        'category' => 'fabric',

        'icon' => '🧻',

        'color' => '#14B8A6',

        'priority' => 440,

        'upstream' => [

            'synthetic_fiber_manufacturer',

            'recycled_fiber_producer',

        ],

        'downstream' => [

            'technical_textile_manufacturer',

            'medical_textile_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'dyeing_finishing_mill',

        'label' => 'Dyeing & Finishing Mill',

        'description' => 'Provides dyeing and finishing services for woven and knitted fabrics.',

        'category' => 'fabric',

        'icon' => '🎨',

        'color' => '#EC4899',

        'priority' => 450,

        'upstream' => [

            'weaving_mill',

            'knitting_mill',

            'warp_knitting_mill',

            'circular_knitting_mill',

        ],

        'downstream' => [

            'printing_mill',

            'digital_printing_company',

            'garment_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'printing_mill',

        'label' => 'Printing Mill',

        'description' => 'Provides conventional textile printing services.',

        'category' => 'fabric',

        'icon' => '🖨️',

        'color' => '#7C3AED',

        'priority' => 460,

        'upstream' => [

            'dyeing_finishing_mill',

        ],

        'downstream' => [

            'garment_manufacturer',

            'home_textile_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'digital_printing_company',

        'label' => 'Digital Printing Company',

        'description' => 'Provides digital textile printing solutions with pigment, reactive, acid and sublimation technologies.',

        'category' => 'fabric',

        'icon' => '🖨️',

        'color' => '#8B5CF6',

        'priority' => 470,

        'upstream' => [

            'dyeing_finishing_mill',

            'weaving_mill',

            'knitting_mill',

        ],

        'downstream' => [

            'garment_manufacturer',

            'sportswear_manufacturer',

            'fashion_manufacturer',

            'home_textile_manufacturer',

            'brand_owner',

        ],

        'technologies' => [

            'digital_printing',

            'pigment_printing',

            'reactive_printing',

            'acid_printing',

            'sublimation',

        ],

        'sustainability' => [

            'water_saving',

            'eco_ink',

            'low_carbon',

        ],

        'active' => true,

    ],

    [

        'id' => 'coating_company',

        'label' => 'Coating Company',

        'description' => 'Applies functional coating to textile fabrics.',

        'category' => 'fabric',

        'icon' => '🛡️',

        'color' => '#F59E0B',

        'priority' => 480,

        'upstream' => [

            'dyeing_finishing_mill',

        ],

        'downstream' => [

            'technical_textile_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'laminating_company',

        'label' => 'Laminating Company',

        'description' => 'Provides textile laminating services.',

        'category' => 'fabric',

        'icon' => '📑',

        'color' => '#F97316',

        'priority' => 490,

        'upstream' => [

            'coating_company',

        ],

        'downstream' => [

            'technical_textile_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'bonding_company',

        'label' => 'Bonding Company',

        'description' => 'Provides textile bonding and composite fabric services.',

        'category' => 'fabric',

        'icon' => '🔗',

        'color' => '#EA580C',

        'priority' => 500,

        'upstream' => [

            'laminating_company',

        ],

        'downstream' => [

            'technical_textile_manufacturer',

        ],

        'active' => true,

    ],
    /*
    |--------------------------------------------------------------------------
    | Finished Product Industry
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'garment_manufacturer',

        'label' => 'Garment Manufacturer',

        'description' => 'Manufactures finished apparel and garments.',

        'category' => 'finished_product',

        'icon' => '👔',

        'color' => '#2563EB',

        'priority' => 600,

        'upstream' => [

            'dyeing_finishing_mill',
            'printing_mill',
            'digital_printing_company',

        ],

        'downstream' => [

            'brand_owner',
            'exporter',
            'retailer',

        ],

        'active' => true,

    ],

    [

        'id' => 'fashion_manufacturer',

        'label' => 'Fashion Manufacturer',

        'description' => 'Produces fashion apparel for domestic and international brands.',

        'category' => 'finished_product',

        'icon' => '👗',

        'color' => '#EC4899',

        'priority' => 610,

        'upstream' => [

            'garment_manufacturer',

        ],

        'downstream' => [

            'brand_owner',

        ],

        'active' => true,

    ],

    [

        'id' => 'sportswear_manufacturer',

        'label' => 'Sportswear Manufacturer',

        'description' => 'Produces sportswear and activewear.',

        'category' => 'finished_product',

        'icon' => '🏃',

        'color' => '#16A34A',

        'priority' => 620,

        'upstream' => [

            'digital_printing_company',
            'garment_manufacturer',

        ],

        'downstream' => [

            'brand_owner',

        ],

        'active' => true,

    ],

    [

        'id' => 'uniform_manufacturer',

        'label' => 'Uniform Manufacturer',

        'description' => 'Produces uniforms for industrial, corporate and institutional markets.',

        'category' => 'finished_product',

        'icon' => '🦺',

        'color' => '#F59E0B',

        'priority' => 630,

        'upstream' => [

            'garment_manufacturer',

        ],

        'downstream' => [

            'brand_owner',

            'government_agency',

        ],

        'active' => true,

    ],

    [

        'id' => 'workwear_manufacturer',

        'label' => 'Workwear Manufacturer',

        'description' => 'Produces industrial and occupational workwear.',

        'category' => 'finished_product',

        'icon' => '🥾',

        'color' => '#F97316',

        'priority' => 640,

        'upstream' => [

            'garment_manufacturer',

            'coating_company',

        ],

        'downstream' => [

            'brand_owner',

        ],

        'active' => true,

    ],

    [

        'id' => 'home_textile_manufacturer',

        'label' => 'Home Textile Manufacturer',

        'description' => 'Produces bedding, curtains, towels, upholstery and other home textile products.',

        'category' => 'finished_product',

        'icon' => '🛏️',

        'color' => '#8B5CF6',

        'priority' => 650,

        'upstream' => [

            'dyeing_finishing_mill',

            'printing_mill',

            'digital_printing_company',

        ],

        'downstream' => [

            'brand_owner',

            'retailer',

        ],

        'active' => true,

    ],

    [

        'id' => 'technical_textile_manufacturer',

        'label' => 'Technical Textile Manufacturer',

        'description' => 'Produces industrial and functional textile products.',

        'category' => 'finished_product',

        'icon' => '🏭',

        'color' => '#0EA5E9',

        'priority' => 660,

        'upstream' => [

            'nonwoven_manufacturer',

            'coating_company',

            'laminating_company',

            'bonding_company',

        ],

        'downstream' => [

            'brand_owner',

            'exporter',

        ],

        'active' => true,

    ],

    [

        'id' => 'medical_textile_manufacturer',

        'label' => 'Medical Textile Manufacturer',

        'description' => 'Produces medical textiles and healthcare products.',

        'category' => 'finished_product',

        'icon' => '🏥',

        'color' => '#DC2626',

        'priority' => 670,

        'upstream' => [

            'technical_textile_manufacturer',

            'nonwoven_manufacturer',

        ],

        'downstream' => [

            'hospital',

            'medical_distributor',

        ],

        'active' => true,

    ],

    [

        'id' => 'automotive_textile_manufacturer',

        'label' => 'Automotive Textile Manufacturer',

        'description' => 'Produces textiles for automotive interiors and components.',

        'category' => 'finished_product',

        'icon' => '🚗',

        'color' => '#2563EB',

        'priority' => 680,

        'upstream' => [

            'technical_textile_manufacturer',

        ],

        'downstream' => [

            'automotive_oem',

        ],

        'active' => true,

    ],

    [

        'id' => 'protective_textile_manufacturer',

        'label' => 'Protective Textile Manufacturer',

        'description' => 'Produces PPE and protective textile products.',

        'category' => 'finished_product',

        'icon' => '🛡️',

        'color' => '#EF4444',

        'priority' => 690,
        'upstream' => [
            'technical_textile_manufacturer',
            'coating_company',
        ],
        'downstream' => [
            'government_agency',
            'industrial_company',
        ],
        'active' => true,
    ],

        /*
    |--------------------------------------------------------------------------
    | Trading & Market
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'brand_owner',

        'label' => 'Brand Owner',

        'description' => 'Owns and manages one or more textile, apparel or lifestyle brands.',

        'category' => 'market',

        'icon' => '🏷️',

        'color' => '#2563EB',

        'priority' => 700,

        'upstream' => [

            'garment_manufacturer',
            'fashion_manufacturer',
            'sportswear_manufacturer',
            'home_textile_manufacturer',
            'technical_textile_manufacturer',

        ],

        'downstream' => [

            'retailer',
            'ecommerce_seller',
            'distributor',

        ],

        'active' => true,

    ],

    [

        'id' => 'private_label_brand',

        'label' => 'Private Label Brand',

        'description' => 'Develops and markets private label textile products.',

        'category' => 'market',

        'icon' => '🏷️',

        'color' => '#7C3AED',

        'priority' => 710,

        'upstream' => [

            'garment_manufacturer',

            'home_textile_manufacturer',

        ],

        'downstream' => [

            'retailer',

            'ecommerce_seller',

        ],

        'active' => true,

    ],

    [

        'id' => 'buying_office',

        'label' => 'Buying Office',

        'description' => 'Represents international buyers and sourcing organizations.',

        'category' => 'market',

        'icon' => '🛒',

        'color' => '#0891B2',

        'priority' => 720,

        'upstream' => [

            'garment_manufacturer',

            'home_textile_manufacturer',

            'technical_textile_manufacturer',

        ],

        'downstream' => [

            'brand_owner',

        ],

        'active' => true,

    ],

    [

        'id' => 'trading_company',

        'label' => 'Trading Company',

        'description' => 'Trades textile products in domestic and international markets.',

        'category' => 'market',

        'icon' => '🌍',

        'color' => '#0EA5E9',

        'priority' => 730,

        'upstream' => [

            'fiber_manufacturer',
            'yarn_spinner',
            'weaving_mill',
            'garment_manufacturer',

        ],

        'downstream' => [

            'exporter',

            'importer',

            'distributor',

        ],

        'active' => true,

    ],

    [

        'id' => 'exporter',

        'label' => 'Exporter',

        'description' => 'Exports textile and apparel products to international markets.',

        'category' => 'market',

        'icon' => '🚢',

        'color' => '#16A34A',

        'priority' => 740,

        'upstream' => [

            'trading_company',

            'brand_owner',

            'garment_manufacturer',

        ],

        'downstream' => [

            'importer',

            'distributor',

        ],

        'active' => true,

    ],

    [

        'id' => 'importer',

        'label' => 'Importer',

        'description' => 'Imports textile and apparel products from overseas suppliers.',

        'category' => 'market',

        'icon' => '📥',

        'color' => '#F59E0B',

        'priority' => 750,

        'upstream' => [

            'exporter',

        ],

        'downstream' => [

            'distributor',

            'retailer',

        ],

        'active' => true,

    ],

    [

        'id' => 'distributor',

        'label' => 'Distributor',

        'description' => 'Distributes textile products through wholesale channels.',

        'category' => 'market',

        'icon' => '🚚',

        'color' => '#EA580C',

        'priority' => 760,

        'upstream' => [

            'importer',

            'brand_owner',

            'trading_company',

        ],

        'downstream' => [

            'wholesaler',

            'retailer',

        ],

        'active' => true,

    ],

    [

        'id' => 'wholesaler',

        'label' => 'Wholesaler',

        'description' => 'Supplies textile products in bulk quantities.',

        'category' => 'market',

        'icon' => '📦',

        'color' => '#6366F1',

        'priority' => 770,

        'upstream' => [

            'distributor',

        ],

        'downstream' => [

            'retailer',

        ],

        'active' => true,

    ],

    [

        'id' => 'retailer',

        'label' => 'Retailer',

        'description' => 'Sells textile and apparel products directly to consumers.',

        'category' => 'market',

        'icon' => '🏬',

        'color' => '#EC4899',

        'priority' => 780,

        'upstream' => [

            'brand_owner',

            'distributor',

            'wholesaler',

        ],

        'downstream' => [

            'consumer',

        ],

        'active' => true,

    ],

    [

        'id' => 'ecommerce_seller',

        'label' => 'E-Commerce Seller',

        'description' => 'Sells textile and apparel products through online marketplaces and digital commerce.',

        'category' => 'market',

        'icon' => '💻',

        'color' => '#14B8A6',

        'priority' => 790,

        'upstream' => [

            'brand_owner',

            'private_label_brand',

            'distributor',

        ],

        'downstream' => [

            'consumer',

        ],

        'active' => true,

    ],
    /*
    |--------------------------------------------------------------------------
    | Supporting Industry
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'textile_machinery_supplier',

        'label' => 'Textile Machinery Supplier',

        'description' => 'Supplies textile production machinery and equipment.',

        'category' => 'supporting_industry',

        'icon' => '⚙️',

        'color' => '#475569',

        'priority' => 800,

        'upstream' => [],

        'downstream' => [

            'fiber_manufacturer',
            'yarn_spinner',
            'weaving_mill',
            'knitting_mill',
            'garment_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'textile_chemical_supplier',

        'label' => 'Textile Chemical Supplier',

        'description' => 'Supplies dyes, auxiliaries and textile chemicals.',

        'category' => 'supporting_industry',

        'icon' => '🧪',

        'color' => '#2563EB',

        'priority' => 810,

        'upstream' => [],

        'downstream' => [

            'dyeing_finishing_mill',

            'printing_mill',

            'digital_printing_company',

        ],

        'active' => true,

    ],

    [

        'id' => 'accessories_supplier',

        'label' => 'Accessories Supplier',

        'description' => 'Supplies zippers, buttons, labels and trims.',

        'category' => 'supporting_industry',

        'icon' => '🧷',

        'color' => '#F97316',

        'priority' => 820,

        'upstream' => [],

        'downstream' => [

            'garment_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'packaging_supplier',

        'label' => 'Packaging Supplier',

        'description' => 'Supplies textile packaging materials.',

        'category' => 'supporting_industry',

        'icon' => '📦',

        'color' => '#A16207',

        'priority' => 830,

        'upstream' => [],

        'downstream' => [

            'exporter',

            'brand_owner',

        ],

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Textile Services
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'testing_laboratory',

        'label' => 'Testing Laboratory',

        'description' => 'Provides textile testing and laboratory services.',

        'category' => 'service',

        'icon' => '🔬',

        'color' => '#0891B2',

        'priority' => 840,

        'upstream' => [],

        'downstream' => [

            'certification_body',

        ],

        'active' => true,

    ],

    [

        'id' => 'certification_body',

        'label' => 'Certification Body',

        'description' => 'Provides certification and compliance services.',

        'category' => 'service',

        'icon' => '📜',

        'color' => '#16A34A',

        'priority' => 850,

        'upstream' => [

            'testing_laboratory',

        ],

        'downstream' => [],

        'active' => true,

    ],

    [

        'id' => 'logistics_provider',

        'label' => 'Logistics Provider',

        'description' => 'Provides logistics and transportation services.',

        'category' => 'service',

        'icon' => '🚚',

        'color' => '#2563EB',

        'priority' => 860,

        'upstream' => [],

        'downstream' => [

            'exporter',

            'importer',

        ],

        'active' => true,

    ],

    [

        'id' => 'warehouse_provider',

        'label' => 'Warehouse Provider',

        'description' => 'Provides warehousing and inventory services.',

        'category' => 'service',

        'icon' => '🏢',

        'color' => '#6366F1',

        'priority' => 870,

        'upstream' => [],

        'downstream' => [

            'logistics_provider',

        ],

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Digital Textile Industry
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'erp_provider',

        'label' => 'ERP Provider',

        'description' => 'Enterprise Resource Planning solution provider.',

        'category' => 'digital',

        'icon' => '💻',

        'color' => '#2563EB',

        'priority' => 900,

        'upstream' => [],

        'downstream' => [

            'garment_manufacturer',

            'brand_owner',

        ],

        'active' => true,

    ],

    [

        'id' => 'plm_provider',

        'label' => 'PLM Provider',

        'description' => 'Product Lifecycle Management solution provider.',

        'category' => 'digital',

        'icon' => '🧩',

        'color' => '#7C3AED',

        'priority' => 910,

        'upstream' => [],

        'downstream' => [

            'brand_owner',

            'fashion_manufacturer',

            'garment_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'mes_provider',

        'label' => 'MES Provider',

        'description' => 'Manufacturing Execution System provider.',

        'category' => 'digital',

        'icon' => '🏭',

        'color' => '#0EA5E9',

        'priority' => 920,

        'upstream' => [],

        'downstream' => [

            'fiber_manufacturer',

            'yarn_spinner',

            'weaving_mill',

            'garment_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'ai_solution_provider',

        'label' => 'AI Solution Provider',

        'description' => 'Provides Artificial Intelligence solutions for textile industry.',

        'category' => 'digital',

        'icon' => '🤖',

        'color' => '#9333EA',

        'priority' => 930,

        'upstream' => [],

        'downstream' => [

            'all_industries',

        ],

        'active' => true,

    ],

    [

        'id' => 'automation_provider',

        'label' => 'Automation Provider',

        'description' => 'Industrial automation solution provider.',

        'category' => 'digital',

        'icon' => '⚡',

        'color' => '#0284C7',

        'priority' => 940,

        'upstream' => [],

        'downstream' => [

            'all_industries',

        ],

        'active' => true,

    ],

    [

        'id' => 'iot_provider',

        'label' => 'IoT Provider',

        'description' => 'Industrial IoT and smart factory solution provider.',

        'category' => 'digital',

        'icon' => '📡',

        'color' => '#0891B2',

        'priority' => 950,

        'upstream' => [],

        'downstream' => [

            'all_industries',

        ],

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Sustainability
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'sustainable_material_supplier',

        'label' => 'Sustainable Material Supplier',

        'description' => 'Supplies environmentally responsible textile materials.',

        'category' => 'sustainability',

        'icon' => '🌱',

        'color' => '#16A34A',

        'priority' => 1000,

        'upstream' => [],

        'downstream' => [

            'fiber_manufacturer',

            'garment_manufacturer',

        ],

        'active' => true,

    ],

    [

        'id' => 'circular_textile_recycler',

        'label' => 'Circular Textile Recycler',

        'description' => 'Recycles textile waste into reusable raw materials.',

        'category' => 'sustainability',

        'icon' => '♻️',

        'color' => '#22C55E',

        'priority' => 1010,

        'upstream' => [],

        'downstream' => [

            'recycled_fiber_producer',

        ],

        'active' => true,

    ],

    [

        'id' => 'water_treatment_provider',

        'label' => 'Water Treatment Provider',

        'description' => 'Provides wastewater and water recycling solutions.',

        'category' => 'sustainability',

        'icon' => '💧',

        'color' => '#06B6D4',

        'priority' => 1020,

        'upstream' => [],

        'downstream' => [

            'dyeing_finishing_mill',

            'printing_mill',

            'digital_printing_company',

        ],

        'active' => true,

    ],

    [

        'id' => 'carbon_management_provider',

        'label' => 'Carbon Management Provider',

        'description' => 'Provides carbon footprint measurement and reduction solutions.',

        'category' => 'sustainability',

        'icon' => '🌍',

        'color' => '#15803D',

        'priority' => 1030,

        'upstream' => [],

        'downstream' => [

            'all_industries',

        ],

        'active' => true,

    ],

    [

        'id' => 'esg_consultant',

        'label' => 'ESG Consultant',

        'description' => 'Provides Environmental, Social and Governance consulting.',

        'category' => 'sustainability',

        'icon' => '📈',

        'color' => '#22C55E',

        'priority' => 1040,

        'upstream' => [],

        'downstream' => [

            'all_industries',

        ],

        'active' => true,

    ],

];
    