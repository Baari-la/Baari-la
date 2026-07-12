<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Raw Material & Fiber Suppliers
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'cotton_farmer',

        'label' => 'Cotton Farmer',

        'category' => 'Raw Materials',

        'icon' => '🌱',

        'priority' => 95,

        'typical_products' => [
            'seed_cotton',
            'organic_cotton',
        ],

        'common_business_roles' => [
            'raw_material_supplier',
            'agriculture',
        ],

        'common_buyer_segments' => [
            'cotton_ginner',
            'spinner',
        ],

        'common_certifications' => [
            'better_cotton',
            'organic',
        ],

        'common_sustainability' => [
            'traceability',
            'water_management',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Agricultural producers supplying seed cotton.',
    ],

    [
        'id' => 'cotton_ginner',

        'label' => 'Cotton Ginner',

        'category' => 'Raw Materials',

        'icon' => '🌾',

        'priority' => 98,

        'typical_products' => [
            'cotton_lint',
            'cotton_seed',
        ],

        'common_business_roles' => [
            'fiber_processor',
        ],

        'common_buyer_segments' => [
            'yarn_spinner',
        ],

        'common_certifications' => [
            'better_cotton',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Processing seed cotton into cotton lint.',
    ],

    [
        'id' => 'cotton_merchant',

        'label' => 'Cotton Merchant',

        'category' => 'Raw Materials',

        'icon' => '🚢',

        'priority' => 92,

        'typical_products' => [
            'cotton_lint',
            'organic_cotton',
        ],

        'common_business_roles' => [
            'trader',
        ],

        'common_buyer_segments' => [
            'yarn_spinner',
            'thread_manufacturer',
        ],

        'common_certifications' => [
            'better_cotton',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'International cotton trading companies.',
    ],

    [
        'id' => 'wool_producer',

        'label' => 'Wool Producer',

        'category' => 'Raw Materials',

        'icon' => '🐑',

        'priority' => 90,

        'typical_products' => [
            'greasy_wool',
            'washed_wool',
        ],

        'common_business_roles' => [
            'fiber_supplier',
        ],

        'common_buyer_segments' => [
            'wool_spinner',
        ],

        'common_certifications' => [
            'rws',
        ],

        'common_sustainability' => [
            'animal_welfare',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Suppliers of natural wool fibers.',
    ],

    [
        'id' => 'silk_producer',

        'label' => 'Silk Producer',

        'category' => 'Raw Materials',

        'icon' => '🕸️',

        'priority' => 88,

        'typical_products' => [
            'raw_silk',
            'silk_yarn',
        ],

        'common_business_roles' => [
            'fiber_supplier',
        ],

        'common_buyer_segments' => [
            'silk_spinner',
            'weaving_mill',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Silk cocoon and silk fiber producers.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Synthetic & Regenerated Fiber Producers
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'polyester_fiber_producer',

        'label' => 'Polyester Fiber Producer',

        'category' => 'Synthetic Fiber',

        'icon' => '🧵',

        'priority' => 100,

        'typical_products' => [
            'polyester_staple_fiber',
            'polyester_filament',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'yarn_spinner',
            'thread_manufacturer',
            'nonwoven_manufacturer',
        ],

        'common_certifications' => [
            'grs',
            'iso9001',
        ],

        'common_sustainability' => [
            'recycled_material',
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of polyester staple and filament fibers.',
    ],

    [
        'id' => 'recycled_polyester_producer',

        'label' => 'Recycled Polyester Producer',

        'category' => 'Synthetic Fiber',

        'icon' => '♻️',

        'priority' => 100,

        'typical_products' => [
            'recycled_polyester_staple',
            'recycled_polyester_filament',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'yarn_spinner',
            'sportswear_brand',
            'outdoor_brand',
        ],

        'common_certifications' => [
            'grs',
        ],

        'common_sustainability' => [
            'circular_economy',
            'carbon_reduction',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of recycled polyester fibers.',
    ],

    [
        'id' => 'nylon_fiber_producer',

        'label' => 'Nylon Fiber Producer',

        'category' => 'Synthetic Fiber',

        'icon' => '🪢',

        'priority' => 96,

        'typical_products' => [
            'nylon6',
            'nylon66',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'yarn_spinner',
            'industrial_yarn_producer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of nylon textile fibers.',
    ],

    [
        'id' => 'acrylic_fiber_producer',

        'label' => 'Acrylic Fiber Producer',

        'category' => 'Synthetic Fiber',

        'icon' => '🧶',

        'priority' => 90,

        'typical_products' => [
            'acrylic_fiber',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'spinner',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of acrylic staple fibers.',
    ],

    [
        'id' => 'polypropylene_fiber_producer',

        'label' => 'Polypropylene Fiber Producer',

        'category' => 'Synthetic Fiber',

        'icon' => '📦',

        'priority' => 92,

        'typical_products' => [
            'polypropylene_fiber',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'nonwoven_manufacturer',
            'industrial_textile_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of polypropylene textile fibers.',
    ],

    [
        'id' => 'viscose_fiber_producer',

        'label' => 'Viscose Fiber Producer',

        'category' => 'Regenerated Fiber',

        'icon' => '🌿',

        'priority' => 95,

        'typical_products' => [
            'viscose_staple_fiber',
            'viscose_filament',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'spinner',
        ],

        'common_certifications' => [
            'fsc',
            'pefc',
        ],

        'common_sustainability' => [
            'responsible_forestry',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of viscose rayon fibers.',
    ],

    [
        'id' => 'lyocell_producer',

        'label' => 'Lyocell Producer',

        'category' => 'Regenerated Fiber',

        'icon' => '🍃',

        'priority' => 95,

        'typical_products' => [
            'lyocell_fiber',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'premium_fashion_brand',
            'spinner',
        ],

        'common_certifications' => [
            'fsc',
        ],

        'common_sustainability' => [
            'closed_loop',
            'renewable_resource',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of Lyocell fibers.',
    ],

    [
        'id' => 'modal_fiber_producer',

        'label' => 'Modal Fiber Producer',

        'category' => 'Regenerated Fiber',

        'icon' => '🌱',

        'priority' => 92,

        'typical_products' => [
            'modal_fiber',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'spinner',
            'fashion_brand',
        ],

        'common_certifications' => [
            'fsc',
        ],

        'common_sustainability' => [
            'responsible_forestry',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of modal fibers.',
    ],

    [
        'id' => 'acetate_fiber_producer',

        'label' => 'Acetate Fiber Producer',

        'category' => 'Regenerated Fiber',

        'icon' => '🪡',

        'priority' => 88,

        'typical_products' => [
            'acetate_fiber',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'weaving_mill',
            'fashion_brand',
        ],

        'common_certifications' => [],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of acetate textile fibers.',
    ],

    [
        'id' => 'spandex_producer',

        'label' => 'Spandex Producer',

        'category' => 'Synthetic Fiber',

        'icon' => '🤸',

        'priority' => 97,

        'typical_products' => [
            'spandex',
            'elastane',
        ],

        'common_business_roles' => [
            'fiber_manufacturer',
        ],

        'common_buyer_segments' => [
            'knitting_mill',
            'sportswear_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of elastane and spandex fibers.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Yarn Manufacturing
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'yarn_spinner',

        'label' => 'Yarn Spinner',

        'category' => 'Yarn Manufacturing',

        'icon' => '🧶',

        'priority' => 100,

        'typical_products' => [
            'cotton_yarn',
            'polyester_yarn',
            'blended_yarn',
            'viscose_yarn',
        ],

        'common_business_roles' => [
            'spinner',
        ],

        'common_buyer_segments' => [
            'weaving_mill',
            'knitting_mill',
            'thread_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
            'oeko_tex',
            'grs',
        ],

        'common_sustainability' => [
            'traceability',
            'energy_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers producing staple and blended yarns.',
    ],

    [
        'id' => 'open_end_spinner',

        'label' => 'Open-End Spinner',

        'category' => 'Yarn Manufacturing',

        'icon' => '🧵',

        'priority' => 95,

        'typical_products' => [
            'oe_yarn',
        ],

        'common_business_roles' => [
            'spinner',
        ],

        'common_buyer_segments' => [
            'denim_mill',
            'weaving_mill',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'energy_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers producing open-end yarn.',
    ],

    [
        'id' => 'compact_spinner',

        'label' => 'Compact Spinner',

        'category' => 'Yarn Manufacturing',

        'icon' => '🪢',

        'priority' => 98,

        'typical_products' => [
            'compact_cotton_yarn',
            'compact_blended_yarn',
        ],

        'common_business_roles' => [
            'spinner',
        ],

        'common_buyer_segments' => [
            'premium_fashion_brand',
            'weaving_mill',
            'knitting_mill',
        ],

        'common_certifications' => [
            'oeko_tex',
            'grs',
            'gots',
        ],

        'common_sustainability' => [
            'traceability',
            'organic',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'japan',
        ],

        'description'
            => 'Manufacturers specializing in compact spinning technology.',
    ],

    [
        'id' => 'worsted_spinner',

        'label' => 'Worsted Spinner',

        'category' => 'Yarn Manufacturing',

        'icon' => '🐑',

        'priority' => 90,

        'typical_products' => [
            'worsted_yarn',
            'fine_wool_yarn',
        ],

        'common_business_roles' => [
            'spinner',
        ],

        'common_buyer_segments' => [
            'weaving_mill',
            'luxury_fashion_brand',
        ],

        'common_certifications' => [
            'rws',
        ],

        'common_sustainability' => [
            'animal_welfare',
        ],

        'typical_markets' => [
            'eu',
            'global',
        ],

        'description'
            => 'Manufacturers producing worsted wool yarn.',
    ],

    [
        'id' => 'filament_yarn_producer',

        'label' => 'Filament Yarn Producer',

        'category' => 'Yarn Manufacturing',

        'icon' => '🧬',

        'priority' => 98,

        'typical_products' => [
            'polyester_filament_yarn',
            'nylon_filament_yarn',
            'industrial_filament',
        ],

        'common_business_roles' => [
            'filament_producer',
        ],

        'common_buyer_segments' => [
            'knitting_mill',
            'weaving_mill',
            'industrial_textile_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers producing continuous filament yarns.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Fabric Manufacturing
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'knitting_mill',

        'label' => 'Knitting Mill',

        'category' => 'Fabric Manufacturing',

        'icon' => '🧶',

        'priority' => 100,

        'typical_products' => [
            'single_jersey',
            'rib_fabric',
            'interlock',
            'fleece',
            'piquet',
        ],

        'common_business_roles' => [
            'knitted_fabric_manufacturer',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'sportswear_brand',
            'teamwear_brand',
            'workwear_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
            'grs',
            'gots',
        ],

        'common_sustainability' => [
            'traceability',
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of knitted fabrics.',
    ],

    [
        'id' => 'circular_knitting_mill',

        'label' => 'Circular Knitting Mill',

        'category' => 'Fabric Manufacturing',

        'icon' => '⭕',

        'priority' => 98,

        'typical_products' => [
            'single_jersey',
            'rib',
            'interlock',
            'french_terry',
        ],

        'common_business_roles' => [
            'knitted_fabric_manufacturer',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'sportswear_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [
            'energy_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers using circular knitting machines.',
    ],

    [
        'id' => 'warp_knitting_mill',

        'label' => 'Warp Knitting Mill',

        'category' => 'Fabric Manufacturing',

        'icon' => '🕸️',

        'priority' => 95,

        'typical_products' => [
            'tricot',
            'raschel',
            'mesh',
            'technical_knit',
        ],

        'common_business_roles' => [
            'technical_textile_manufacturer',
        ],

        'common_buyer_segments' => [
            'sportswear_brand',
            'automotive_oem',
            'medical_device_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers specializing in warp knitted fabrics.',
    ],

    [
        'id' => 'flat_knitting_mill',

        'label' => 'Flat Knitting Mill',

        'category' => 'Fabric Manufacturing',

        'icon' => '🧥',

        'priority' => 90,

        'typical_products' => [
            'sweater_panel',
            'collar',
            'cuff',
        ],

        'common_business_roles' => [
            'flat_knitting_manufacturer',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'fashion_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [
            'material_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of flat knitted products.',
    ],

    [
        'id' => 'weaving_mill',

        'label' => 'Weaving Mill',

        'category' => 'Fabric Manufacturing',

        'icon' => '🪡',

        'priority' => 100,

        'typical_products' => [
            'woven_fabric',
            'shirting',
            'bottom_weight',
            'canvas',
        ],

        'common_business_roles' => [
            'woven_fabric_manufacturer',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'corporate_uniform_company',
            'home_textile_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
            'iso9001',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of woven fabrics.',
    ],
      [
        'id' => 'jacquard_weaving_mill',

        'label' => 'Jacquard Weaving Mill',

        'category' => 'Fabric Manufacturing',

        'icon' => '🎨',

        'priority' => 92,

        'typical_products' => [
            'jacquard_fabric',
            'upholstery',
            'decorative_fabric',
        ],

        'common_business_roles' => [
            'woven_fabric_manufacturer',
        ],

        'common_buyer_segments' => [
            'furniture_manufacturer',
            'home_textile_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of jacquard woven fabrics.',
    ],

    [
        'id' => 'denim_mill',

        'label' => 'Denim Mill',

        'category' => 'Fabric Manufacturing',

        'icon' => '👖',

        'priority' => 96,

        'typical_products' => [
            'denim',
            'stretch_denim',
            'black_denim',
        ],

        'common_business_roles' => [
            'woven_fabric_manufacturer',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'garment_manufacturer',
            'private_label_brand',
        ],

        'common_certifications' => [
            'better_cotton',
            'oeko_tex',
        ],

        'common_sustainability' => [
            'water_saving',
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers specializing in denim fabrics.',
    ],

    [
        'id' => 'narrow_fabric_manufacturer',

        'label' => 'Narrow Fabric Manufacturer',

        'category' => 'Fabric Manufacturing',

        'icon' => '🎗️',

        'priority' => 88,

        'typical_products' => [
            'elastic',
            'webbing',
            'tape',
            'ribbon',
        ],

        'common_business_roles' => [
            'accessories_manufacturer',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'footwear_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of narrow woven products.',
    ],

    [
        'id' => 'nonwoven_manufacturer',

        'label' => 'Nonwoven Manufacturer',

        'category' => 'Fabric Manufacturing',

        'icon' => '🩹',

        'priority' => 95,

        'typical_products' => [
            'spunbond',
            'meltblown',
            'needle_punch',
            'medical_nonwoven',
        ],

        'common_business_roles' => [
            'technical_textile_manufacturer',
        ],

        'common_buyer_segments' => [
            'medical_device_manufacturer',
            'ppe_manufacturer',
            'hospital_group',
        ],

        'common_certifications' => [
            'iso13485',
            'iso9001',
        ],

        'common_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of nonwoven fabrics.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Wet Processing
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'dyeing_mill',

        'label' => 'Dyeing Mill',

        'category' => 'Wet Processing',

        'icon' => '🎨',

        'priority' => 100,

        'typical_products' => [
            'dyed_knitted_fabric',
            'dyed_woven_fabric',
            'yarn_dyeing',
        ],

        'common_business_roles' => [
            'dyeing_finishing',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'home_textile_brand',
            'sportswear_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
            'zdhc',
            'iso9001',
        ],

        'common_sustainability' => [
            'water_saving',
            'chemical_management',
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Textile dyeing service providers.',
    ],

    [
        'id' => 'printing_mill',

        'label' => 'Printing Mill',

        'category' => 'Wet Processing',

        'icon' => '🖨️',

        'priority' => 95,

        'typical_products' => [
            'printed_knit',
            'printed_woven',
            'pigment_print',
            'reactive_print',
        ],

        'common_business_roles' => [
            'textile_printer',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'fashion_brand',
            'home_textile_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [
            'water_saving',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Textile printing companies.',
    ],

    [
        'id' => 'digital_printing_provider',

        'label' => 'Digital Printing Provider',

        'category' => 'Wet Processing',

        'icon' => '🖥️',

        'priority' => 98,

        'typical_products' => [
            'digital_printed_fabric',
            'custom_print',
            'short_run_print',
        ],

        'common_business_roles' => [
            'digital_printing',
        ],

        'common_buyer_segments' => [
            'sportswear_brand',
            'fashion_brand',
            'private_label_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [
            'reduced_water_usage',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Digital textile printing specialists.',
    ],

    [
        'id' => 'rotary_printing_provider',

        'label' => 'Rotary Printing Provider',

        'category' => 'Wet Processing',

        'icon' => '🔄',

        'priority' => 92,

        'typical_products' => [
            'rotary_printed_fabric',
        ],

        'common_business_roles' => [
            'textile_printer',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'garment_manufacturer',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Rotary screen textile printing companies.',
    ],

    [
        'id' => 'finishing_mill',

        'label' => 'Finishing Mill',

        'category' => 'Wet Processing',

        'icon' => '✨',

        'priority' => 100,

        'typical_products' => [
            'finished_knitted_fabric',
            'finished_woven_fabric',
            'functional_textile',
        ],

        'common_business_roles' => [
            'finishing',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'home_textile_brand',
            'automotive_oem',
        ],

        'common_certifications' => [
            'oeko_tex',
            'iso9001',
        ],

        'common_sustainability' => [
            'energy_efficiency',
            'water_saving',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Textile finishing companies.',
    ],
        [
        'id' => 'coating_provider',

        'label' => 'Coating Provider',

        'category' => 'Wet Processing',

        'icon' => '🛡️',

        'priority' => 90,

        'typical_products' => [
            'coated_fabric',
            'waterproof_fabric',
            'industrial_textile',
        ],

        'common_business_roles' => [
            'technical_finishing',
        ],

        'common_buyer_segments' => [
            'outdoor_brand',
            'ppe_manufacturer',
            'automotive_oem',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Fabric coating specialists.',
    ],

    [
        'id' => 'lamination_provider',

        'label' => 'Lamination Provider',

        'category' => 'Wet Processing',

        'icon' => '📑',

        'priority' => 88,

        'typical_products' => [
            'laminated_fabric',
            'technical_laminate',
        ],

        'common_business_roles' => [
            'technical_finishing',
        ],

        'common_buyer_segments' => [
            'outdoor_brand',
            'medical_device_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Textile lamination service providers.',
    ],

    [
        'id' => 'bonding_provider',

        'label' => 'Bonding Provider',

        'category' => 'Wet Processing',

        'icon' => '🔗',

        'priority' => 85,

        'typical_products' => [
            'bonded_fabric',
            'multi_layer_fabric',
        ],

        'common_business_roles' => [
            'technical_finishing',
        ],

        'common_buyer_segments' => [
            'sportswear_brand',
            'outdoor_brand',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Fabric bonding specialists.',
    ],

    [
        'id' => 'embossing_provider',

        'label' => 'Embossing Provider',

        'category' => 'Wet Processing',

        'icon' => '🔷',

        'priority' => 82,

        'typical_products' => [
            'embossed_fabric',
        ],

        'common_business_roles' => [
            'fabric_finishing',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'home_textile_brand',
        ],

        'common_certifications' => [],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Fabric embossing service providers.',
    ],
    /*
    |--------------------------------------------------------------------------
    | Garment Manufacturing
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'garment_manufacturer',

        'label' => 'Garment Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '👕',

        'priority' => 100,

        'typical_products' => [
            'fashion_apparel',
            'sportswear',
            'uniform',
            'outerwear',
        ],

        'common_business_roles' => [
            'garment_manufacturer',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'sportswear_brand',
            'private_label_brand',
            'buying_office',
        ],

        'common_certifications' => [
            'iso9001',
            'oeko_tex',
            'wrap',
            'amfori_bsci',
        ],

        'common_sustainability' => [
            'traceability',
            'social_compliance',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers producing finished garments.',
    ],

    [
        'id' => 'cmt_factory',

        'label' => 'CMT Factory',

        'category' => 'Garment Manufacturing',

        'icon' => '✂️',

        'priority' => 98,

        'typical_products' => [
            'cut_make_trim',
            'garment_assembly',
        ],

        'common_business_roles' => [
            'cmt_manufacturer',
        ],

        'common_buyer_segments' => [
            'buying_office',
            'private_label_brand',
            'garment_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
            'wrap',
        ],

        'common_sustainability' => [
            'social_compliance',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Factories providing Cut-Make-Trim manufacturing services.',
    ],

    [
        'id' => 'oem_manufacturer',

        'label' => 'OEM Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '🏭',

        'priority' => 98,

        'typical_products' => [
            'finished_garment',
            'private_label_product',
        ],

        'common_business_roles' => [
            'oem_manufacturer',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'sportswear_brand',
            'corporate_uniform_company',
        ],

        'common_certifications' => [
            'iso9001',
            'wrap',
            'amfori_bsci',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Original Equipment Manufacturing companies.',
    ],

    [
        'id' => 'odm_manufacturer',

        'label' => 'ODM Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '🧩',

        'priority' => 96,

        'typical_products' => [
            'fashion_collection',
            'designed_garment',
        ],

        'common_business_roles' => [
            'odm_manufacturer',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'premium_fashion_brand',
        ],

        'common_certifications' => [
            'iso9001',
            'wrap',
        ],

        'common_sustainability' => [
            'innovation',
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Original Design Manufacturing companies.',
    ],

    [
        'id' => 'obm_manufacturer',

        'label' => 'OBM Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '⭐',

        'priority' => 92,

        'typical_products' => [
            'own_brand_product',
        ],

        'common_business_roles' => [
            'obm_manufacturer',
        ],

        'common_buyer_segments' => [
            'distributor',
            'importer',
            'department_store',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers marketing products under their own brands.',
    ],
        [
        'id' => 'private_label_manufacturer',

        'label' => 'Private Label Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '🏷️',

        'priority' => 95,

        'typical_products' => [
            'private_label_apparel',
        ],

        'common_business_roles' => [
            'private_label_manufacturer',
        ],

        'common_buyer_segments' => [
            'private_label_brand',
            'department_store',
            'ecommerce_brand',
        ],

        'common_certifications' => [
            'iso9001',
            'wrap',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers specializing in private label production.',
    ],

    [
        'id' => 'uniform_manufacturer',

        'label' => 'Uniform Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '👔',

        'priority' => 94,

        'typical_products' => [
            'corporate_uniform',
            'school_uniform',
            'industrial_uniform',
        ],

        'common_business_roles' => [
            'uniform_manufacturer',
        ],

        'common_buyer_segments' => [
            'corporate_uniform_company',
            'government_procurement',
            'hospital_group',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'durability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of corporate and institutional uniforms.',
    ],

    [
        'id' => 'sportswear_manufacturer',

        'label' => 'Sportswear Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '🏃',

        'priority' => 98,

        'typical_products' => [
            'activewear',
            'performance_wear',
            'teamwear',
        ],

        'common_business_roles' => [
            'sportswear_manufacturer',
        ],

        'common_buyer_segments' => [
            'sportswear_brand',
            'teamwear_brand',
            'running_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
            'grs',
            'wrap',
        ],

        'common_sustainability' => [
            'recycled_material',
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers specializing in sportswear production.',
    ],

    [
        'id' => 'workwear_manufacturer',

        'label' => 'Workwear Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '🦺',

        'priority' => 95,

        'typical_products' => [
            'industrial_uniform',
            'protective_clothing',
        ],

        'common_business_roles' => [
            'workwear_manufacturer',
        ],

        'common_buyer_segments' => [
            'workwear_brand',
            'ppe_manufacturer',
            'government_procurement',
        ],

        'common_certifications' => [
            'iso9001',
            'oeko_tex',
        ],

        'common_sustainability' => [
            'durability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of industrial workwear and uniforms.',
    ],

    [
        'id' => 'medical_garment_manufacturer',

        'label' => 'Medical Garment Manufacturer',

        'category' => 'Garment Manufacturing',

        'icon' => '🏥',

        'priority' => 94,

        'typical_products' => [
            'surgical_gown',
            'medical_uniform',
            'isolation_gown',
        ],

        'common_business_roles' => [
            'medical_textile_manufacturer',
        ],

        'common_buyer_segments' => [
            'hospital_group',
            'medical_device_manufacturer',
        ],

        'common_certifications' => [
            'iso13485',
            'iso9001',
        ],

        'common_sustainability' => [
            'clean_production',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of garments for medical applications.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Accessories & Trims Manufacturing
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'button_manufacturer',

        'label' => 'Button Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '🔘',

        'priority' => 92,

        'typical_products' => [
            'plastic_button',
            'metal_button',
            'shell_button',
            'coconut_button',
        ],

        'common_business_roles' => [
            'trim_supplier',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'fashion_brand',
            'private_label_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of garment buttons.',
    ],

    [
        'id' => 'zipper_manufacturer',

        'label' => 'Zipper Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '🤐',

        'priority' => 98,

        'typical_products' => [
            'nylon_zipper',
            'metal_zipper',
            'plastic_zipper',
            'waterproof_zipper',
        ],

        'common_business_roles' => [
            'trim_supplier',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'sportswear_brand',
            'outdoor_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of garment zippers.',
    ],

    [
        'id' => 'elastic_manufacturer',

        'label' => 'Elastic Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '🪢',

        'priority' => 90,

        'typical_products' => [
            'woven_elastic',
            'knitted_elastic',
            'braided_elastic',
        ],

        'common_business_roles' => [
            'trim_supplier',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'underwear_brand',
            'sportswear_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of elastic tapes.',
    ],

    [
        'id' => 'sewing_thread_supplier',

        'label' => 'Sewing Thread Supplier',

        'category' => 'Accessories & Trims',

        'icon' => '🧵',

        'priority' => 100,

        'typical_products' => [
            'polyester_thread',
            'cotton_thread',
            'core_spun_thread',
            'embroidery_thread',
        ],

        'common_business_roles' => [
            'thread_supplier',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'footwear_manufacturer',
            'upholstery_manufacturer',
        ],

        'common_certifications' => [
            'oeko_tex',
            'iso9001',
        ],

        'common_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Suppliers of sewing and embroidery threads.',
    ],

    [
        'id' => 'label_manufacturer',

        'label' => 'Label Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '🏷️',

        'priority' => 94,

        'typical_products' => [
            'woven_label',
            'printed_label',
            'care_label',
            'size_label',
        ],

        'common_business_roles' => [
            'label_supplier',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'fashion_brand',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of garment labels.',
    ],
        [
        'id' => 'hangtag_manufacturer',

        'label' => 'Hangtag Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '📇',

        'priority' => 90,

        'typical_products' => [
            'paper_hangtag',
            'recycled_hangtag',
            'barcode_tag',
        ],

        'common_business_roles' => [
            'packaging_supplier',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'garment_manufacturer',
        ],

        'common_certifications' => [
            'fsc',
        ],

        'common_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of garment hangtags.',
    ],

    [
        'id' => 'packaging_manufacturer',

        'label' => 'Packaging Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '📦',

        'priority' => 92,

        'typical_products' => [
            'polybag',
            'carton_box',
            'gift_box',
        ],

        'common_business_roles' => [
            'packaging_supplier',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'home_textile_brand',
            'sportswear_brand',
        ],

        'common_certifications' => [
            'fsc',
            'iso9001',
        ],

        'common_sustainability' => [
            'recyclable_packaging',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile packaging materials.',
    ],

    [
        'id' => 'interlining_manufacturer',

        'label' => 'Interlining Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '🩹',

        'priority' => 91,

        'typical_products' => [
            'woven_interlining',
            'nonwoven_interlining',
            'fusible_interlining',
        ],

        'common_business_roles' => [
            'trim_supplier',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'uniform_manufacturer',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of garment interlinings.',
    ],

    [
        'id' => 'lace_ribbon_manufacturer',

        'label' => 'Lace & Ribbon Manufacturer',

        'category' => 'Accessories & Trims',

        'icon' => '🎀',

        'priority' => 88,

        'typical_products' => [
            'lace',
            'ribbon',
            'decorative_trim',
        ],

        'common_business_roles' => [
            'trim_supplier',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'garment_manufacturer',
            'lingerie_brand',
        ],

        'common_certifications' => [
            'oeko_tex',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of lace, ribbons and decorative trims.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Textile Chemicals
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'dyestuff_manufacturer',

        'label' => 'Dyestuff Manufacturer',

        'category' => 'Textile Chemicals',

        'icon' => '🧪',

        'priority' => 100,

        'typical_products' => [
            'reactive_dyes',
            'disperse_dyes',
            'vat_dyes',
            'direct_dyes',
            'acid_dyes',
            'basic_dyes',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'dyeing_mill',
            'printing_mill',
        ],

        'common_certifications' => [
            'iso9001',
            'zdhc',
        ],

        'common_sustainability' => [
            'chemical_management',
            'restricted_substances',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile dyes.',
    ],

    [
        'id' => 'pigment_manufacturer',

        'label' => 'Pigment Manufacturer',

        'category' => 'Textile Chemicals',

        'icon' => '🎨',

        'priority' => 92,

        'typical_products' => [
            'organic_pigment',
            'inorganic_pigment',
            'printing_pigment',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'printing_mill',
            'digital_printing_provider',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'chemical_management',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile pigments.',
    ],

    [
        'id' => 'textile_auxiliary_manufacturer',

        'label' => 'Textile Auxiliary Manufacturer',

        'category' => 'Textile Chemicals',

        'icon' => '⚗️',

        'priority' => 98,

        'typical_products' => [
            'wetting_agent',
            'leveling_agent',
            'sequestering_agent',
            'softener',
            'fixing_agent',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'dyeing_mill',
            'finishing_mill',
        ],

        'common_certifications' => [
            'iso9001',
            'zdhc',
        ],

        'common_sustainability' => [
            'chemical_management',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile auxiliaries.',
    ],

    [
        'id' => 'finishing_chemical_manufacturer',

        'label' => 'Finishing Chemical Manufacturer',

        'category' => 'Textile Chemicals',

        'icon' => '✨',

        'priority' => 96,

        'typical_products' => [
            'water_repellent',
            'flame_retardant',
            'anti_bacterial',
            'anti_static',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'finishing_mill',
            'coating_provider',
        ],

        'common_certifications' => [
            'zdhc',
            'iso9001',
        ],

        'common_sustainability' => [
            'low_impact_chemistry',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile finishing chemicals.',
    ],

    [
        'id' => 'enzyme_manufacturer',

        'label' => 'Enzyme Manufacturer',

        'category' => 'Textile Chemicals',

        'icon' => '🧬',

        'priority' => 90,

        'typical_products' => [
            'cellulase',
            'amylase',
            'catalase',
            'bio_polishing',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'finishing_mill',
            'garment_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'bio_processing',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile enzymes.',
    ],
        [
        'id' => 'silicone_manufacturer',

        'label' => 'Silicone Manufacturer',

        'category' => 'Textile Chemicals',

        'icon' => '🫧',

        'priority' => 88,

        'typical_products' => [
            'silicone_softener',
            'micro_silicone',
            'macro_silicone',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'finishing_mill',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of silicone-based textile chemicals.',
    ],

    [
        'id' => 'resin_manufacturer',

        'label' => 'Resin Manufacturer',

        'category' => 'Textile Chemicals',

        'icon' => '🧫',

        'priority' => 86,

        'typical_products' => [
            'crease_resin',
            'binder_resin',
            'coating_resin',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'finishing_mill',
            'coating_provider',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile resins.',
    ],

    [
        'id' => 'specialty_chemical_supplier',

        'label' => 'Specialty Chemical Supplier',

        'category' => 'Textile Chemicals',

        'icon' => '🧴',

        'priority' => 90,

        'typical_products' => [
            'functional_finish',
            'nano_finish',
            'uv_finish',
            'odor_control',
        ],

        'common_business_roles' => [
            'chemical_supplier',
        ],

        'common_buyer_segments' => [
            'technical_textile_manufacturer',
            'sportswear_manufacturer',
            'medical_garment_manufacturer',
        ],

        'common_certifications' => [
            'zdhc',
            'iso9001',
        ],

        'common_sustainability' => [
            'low_impact_chemistry',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Suppliers of specialty textile chemicals.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Industrial Solutions & Technology Partners
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'textile_machinery_manufacturer',

        'label' => 'Textile Machinery Manufacturer',

        'category' => 'Industrial Solutions',

        'icon' => '🏭',

        'priority' => 100,

        'typical_products' => [
            'spinning_machinery',
            'weaving_machinery',
            'knitting_machinery',
            'dyeing_machinery',
            'finishing_machinery',
        ],

        'common_business_roles' => [
            'machinery_supplier',
        ],

        'common_buyer_segments' => [
            'yarn_spinner',
            'weaving_mill',
            'knitting_mill',
            'dyeing_mill',
            'garment_manufacturer',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'energy_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of textile production machinery.',
    ],

    [
        'id' => 'textile_machinery_distributor',

        'label' => 'Textile Machinery Distributor',

        'category' => 'Industrial Solutions',

        'icon' => '🚚',

        'priority' => 98,

        'typical_products' => [
            'machinery_distribution',
            'technical_support',
            'after_sales_service',
        ],

        'common_business_roles' => [
            'machinery_distributor',
        ],

        'common_buyer_segments' => [
            'spinner',
            'knitting_mill',
            'weaving_mill',
            'garment_manufacturer',
        ],

        'common_certifications' => [],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Distributors and agents of textile machinery.',
    ],

    [
        'id' => 'automation_solution_provider',

        'label' => 'Automation Solution Provider',

        'category' => 'Industrial Solutions',

        'icon' => '🤖',

        'priority' => 95,

        'typical_products' => [
            'factory_automation',
            'robotics',
            'production_control',
        ],

        'common_business_roles' => [
            'automation_provider',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'weaving_mill',
            'knitting_mill',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'energy_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Industrial automation providers.',
    ],

    [
        'id' => 'cad_cam_solution_provider',

        'label' => 'CAD/CAM Solution Provider',

        'category' => 'Industrial Solutions',

        'icon' => '📐',

        'priority' => 96,

        'typical_products' => [
            'cad',
            'cam',
            'pattern_design',
            'marker_planning',
        ],

        'common_business_roles' => [
            'software_provider',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'fashion_brand',
        ],

        'common_certifications' => [],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'CAD/CAM software providers.',
    ],

    [
        'id' => 'plm_solution_provider',

        'label' => 'PLM Solution Provider',

        'category' => 'Industrial Solutions',

        'icon' => '💼',

        'priority' => 100,

        'typical_products' => [
            'product_lifecycle_management',
            'digital_product_development',
        ],

        'common_business_roles' => [
            'technology_solution_partner',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'sportswear_brand',
            'garment_manufacturer',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'digital_transformation',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Product Lifecycle Management solution providers.',
    ],
        [
        'id' => 'erp_solution_provider',

        'label' => 'ERP Solution Provider',

        'category' => 'Industrial Solutions',

        'icon' => '🖥️',

        'priority' => 96,

        'typical_products' => [
            'erp',
            'manufacturing_erp',
        ],

        'common_business_roles' => [
            'software_provider',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'spinner',
            'weaving_mill',
        ],

        'common_certifications' => [],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Enterprise Resource Planning providers.',
    ],

    [
        'id' => 'mes_solution_provider',

        'label' => 'MES Solution Provider',

        'category' => 'Industrial Solutions',

        'icon' => '📊',

        'priority' => 94,

        'typical_products' => [
            'manufacturing_execution_system',
            'production_monitoring',
        ],

        'common_business_roles' => [
            'software_provider',
        ],

        'common_buyer_segments' => [
            'spinner',
            'knitting_mill',
            'weaving_mill',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'production_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturing Execution System providers.',
    ],

    [
        'id' => 'digital_printing_solution_provider',

        'label' => 'Digital Printing Solution Provider',

        'category' => 'Industrial Solutions',

        'icon' => '🖨️',

        'priority' => 97,

        'typical_products' => [
            'digital_textile_printer',
            'printing_workflow',
            'rip_software',
        ],

        'common_business_roles' => [
            'technology_solution_partner',
        ],

        'common_buyer_segments' => [
            'printing_mill',
            'digital_printing_provider',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'water_saving',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Digital textile printing technology providers.',
    ],

    [
        'id' => 'ai_solution_provider',

        'label' => 'AI Solution Provider',

        'category' => 'Industrial Solutions',

        'icon' => '🧠',

        'priority' => 92,

        'typical_products' => [
            'predictive_analytics',
            'quality_ai',
            'planning_ai',
        ],

        'common_business_roles' => [
            'technology_solution_partner',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'spinner',
            'fashion_brand',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'digital_transformation',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Artificial Intelligence solution providers.',
    ],

    [
        'id' => 'industrial_iot_provider',

        'label' => 'Industrial IoT Provider',

        'category' => 'Industrial Solutions',

        'icon' => '📡',

        'priority' => 90,

        'typical_products' => [
            'iot_sensor',
            'machine_monitoring',
            'smart_factory',
        ],

        'common_business_roles' => [
            'technology_solution_partner',
        ],

        'common_buyer_segments' => [
            'spinner',
            'weaving_mill',
            'garment_manufacturer',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'energy_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Industrial IoT and smart factory solution providers.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Quality, Testing, Inspection, Certification & Logistics
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'testing_laboratory',

        'label' => 'Testing Laboratory',

        'category' => 'Quality & Compliance',

        'icon' => '🧪',

        'priority' => 100,

        'typical_products' => [
            'physical_testing',
            'chemical_testing',
            'performance_testing',
            'textile_testing',
        ],

        'common_business_roles' => [
            'testing_service_provider',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'knitting_mill',
            'weaving_mill',
            'fashion_brand',
        ],

        'common_certifications' => [
            'iso_17025',
        ],

        'common_sustainability' => [
            'product_safety',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Independent textile testing laboratories.',
    ],

    [
        'id' => 'inspection_company',

        'label' => 'Inspection Company',

        'category' => 'Quality & Compliance',

        'icon' => '🔍',

        'priority' => 98,

        'typical_products' => [
            'inline_inspection',
            'final_inspection',
            'shipment_inspection',
        ],

        'common_business_roles' => [
            'inspection_service_provider',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'buying_office',
            'garment_manufacturer',
        ],

        'common_certifications' => [
            'iso_17020',
        ],

        'common_sustainability' => [
            'quality_assurance',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Independent product inspection companies.',
    ],

    [
        'id' => 'certification_body',

        'label' => 'Certification Body',

        'category' => 'Quality & Compliance',

        'icon' => '📜',

        'priority' => 98,

        'typical_products' => [
            'management_system_certification',
            'product_certification',
            'social_audit',
        ],

        'common_business_roles' => [
            'certification_provider',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'spinner',
            'knitting_mill',
            'weaving_mill',
        ],

        'common_certifications' => [
            'iso_17021',
        ],

        'common_sustainability' => [
            'compliance',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Organizations providing certification services.',
    ],

    [
        'id' => 'calibration_laboratory',

        'label' => 'Calibration Laboratory',

        'category' => 'Quality & Compliance',

        'icon' => '📏',

        'priority' => 90,

        'typical_products' => [
            'equipment_calibration',
            'instrument_calibration',
        ],

        'common_business_roles' => [
            'calibration_service_provider',
        ],

        'common_buyer_segments' => [
            'testing_laboratory',
            'garment_manufacturer',
            'spinner',
        ],

        'common_certifications' => [
            'iso_17025',
        ],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Calibration laboratories for industrial equipment.',
    ],

    [
        'id' => 'freight_forwarder',

        'label' => 'Freight Forwarder',

        'category' => 'Logistics',

        'icon' => '🚢',

        'priority' => 100,

        'typical_products' => [
            'sea_freight',
            'air_freight',
            'multimodal_transport',
        ],

        'common_business_roles' => [
            'logistics_provider',
        ],

        'common_buyer_segments' => [
            'exporter',
            'importer',
            'garment_manufacturer',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'green_logistics',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'International freight forwarding companies.',
    ],
        [
        'id' => 'shipping_line',

        'label' => 'Shipping Line',

        'category' => 'Logistics',

        'icon' => '🚢',

        'priority' => 98,

        'typical_products' => [
            'container_shipping',
            'ocean_transport',
        ],

        'common_business_roles' => [
            'shipping_provider',
        ],

        'common_buyer_segments' => [
            'freight_forwarder',
            'exporter',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'low_emission_transport',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'International ocean shipping companies.',
    ],

    [
        'id' => 'air_cargo_provider',

        'label' => 'Air Cargo Provider',

        'category' => 'Logistics',

        'icon' => '✈️',

        'priority' => 95,

        'typical_products' => [
            'express_air_freight',
            'international_air_cargo',
        ],

        'common_business_roles' => [
            'air_logistics_provider',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'exporter',
        ],

        'common_certifications' => [],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'International air cargo service providers.',
    ],

    [
        'id' => 'customs_broker',

        'label' => 'Customs Broker',

        'category' => 'Logistics',

        'icon' => '🛃',

        'priority' => 95,

        'typical_products' => [
            'customs_clearance',
            'trade_documentation',
        ],

        'common_business_roles' => [
            'customs_service_provider',
        ],

        'common_buyer_segments' => [
            'exporter',
            'importer',
            'freight_forwarder',
        ],

        'common_certifications' => [],

        'common_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Customs brokerage and clearance services.',
    ],

    [
        'id' => 'warehouse_provider',

        'label' => 'Warehouse Provider',

        'category' => 'Logistics',

        'icon' => '🏢',

        'priority' => 90,

        'typical_products' => [
            'warehousing',
            'inventory_management',
            'distribution',
        ],

        'common_business_roles' => [
            'warehouse_service_provider',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'importer',
            'exporter',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'energy_efficiency',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Warehousing and inventory service providers.',
    ],

    [
        'id' => 'third_party_logistics_provider',

        'label' => 'Third Party Logistics (3PL)',

        'category' => 'Logistics',

        'icon' => '📦',

        'priority' => 94,

        'typical_products' => [
            '3pl',
            'distribution',
            'order_fulfillment',
            'integrated_logistics',
        ],

        'common_business_roles' => [
            'third_party_logistics',
        ],

        'common_buyer_segments' => [
            'fashion_brand',
            'garment_manufacturer',
            'ecommerce_brand',
        ],

        'common_certifications' => [
            'iso9001',
        ],

        'common_sustainability' => [
            'green_logistics',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Integrated third-party logistics providers.',
    ],

    [
        'id' => 'supply_chain_consultant',

        'label' => 'Supply Chain Consultant',

        'category' => 'Professional Services',

        'icon' => '📈',

        'priority' => 88,

        'typical_products' => [
            'supply_chain_consulting',
            'procurement_strategy',
            'network_optimization',
        ],

        'common_business_roles' => [
            'consulting_service_provider',
        ],

        'common_buyer_segments' => [
            'garment_manufacturer',
            'fashion_brand',
            'spinner',
        ],

        'common_certifications' => [],

        'common_sustainability' => [
            'supply_chain_resilience',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Supply chain and logistics consulting firms.',
    ],
];