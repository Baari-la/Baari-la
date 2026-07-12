<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Fashion Brands
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'luxury_fashion_brand',

        'label' => 'Luxury Fashion Brand',

        'category' => 'Fashion',

        'icon' => '👗',

        'priority' => 100,

        'typical_products' => [
            'fashion_apparel',
            'premium_fabric',
            'luxury_accessories',
        ],

        'required_certifications' => [
            'gots',
            'oeko_tex',
            'grs',
        ],

        'preferred_sustainability' => [
            'traceability',
            'organic',
            'circular_economy',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'japan',
        ],

        'description'
            => 'Global luxury fashion houses.',
    ],

    [
        'id' => 'premium_fashion_brand',

        'label' => 'Premium Fashion Brand',

        'category' => 'Fashion',

        'icon' => '🧥',

        'priority' => 95,

        'typical_products' => [
            'fashion_apparel',
            'woven_fabric',
            'knitted_fabric',
        ],

        'required_certifications' => [
            'oeko_tex',
            'iso9001',
        ],

        'preferred_sustainability' => [
            'recycled_material',
            'water_saving',
        ],

        'typical_markets' => [
            'eu',
            'usa',
        ],

        'description'
            => 'Premium fashion retailers and brands.',
    ],

    [
        'id' => 'fast_fashion_brand',

        'label' => 'Fast Fashion Brand',

        'category' => 'Fashion',

        'icon' => '👖',

        'priority' => 95,

        'typical_products' => [
            'fashion_apparel',
            'casualwear',
            'denim',
        ],

        'required_certifications' => [
            'oeko_tex',
            'bci',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'asean',
        ],

        'description'
            => 'High-volume fast fashion companies.',
    ],

    [
        'id' => 'contemporary_brand',

        'label' => 'Contemporary Fashion Brand',

        'category' => 'Fashion',

        'icon' => '👚',

        'priority' => 90,

        'typical_products' => [
            'fashion_apparel',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'organic',
        ],

        'typical_markets' => [
            'eu',
            'usa',
        ],

        'description'
            => 'Contemporary apparel brands.',
    ],

    [
        'id' => 'private_label_brand',

        'label' => 'Private Label Brand',

        'category' => 'Fashion',

        'icon' => '🏷️',

        'priority' => 90,

        'typical_products' => [
            'fashion_apparel',
            'basic_apparel',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Retail private label owners.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Sports & Outdoor Brands
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'sportswear_brand',

        'label' => 'Sportswear Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '🏃',

        'priority' => 100,

        'typical_products' => [
            'sportswear',
            'performance_fabric',
            'activewear',
            'compression_wear',
        ],

        'required_certifications' => [
            'grs',
            'oeko_tex',
            'bluesign',
        ],

        'preferred_sustainability' => [
            'recycled_material',
            'traceability',
            'carbon_reduction',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'japan',
        ],

        'description'
            => 'Global sportswear and activewear brands.',
    ],

    [
        'id' => 'outdoor_brand',

        'label' => 'Outdoor Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '🏔️',

        'priority' => 98,

        'typical_products' => [
            'outdoor_apparel',
            'technical_fabric',
            'waterproof_fabric',
        ],

        'required_certifications' => [
            'bluesign',
            'grs',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
            'renewable_energy',
            'traceability',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'canada',
        ],

        'description'
            => 'Outdoor and adventure equipment brands.',
    ],

    [
        'id' => 'athletic_brand',

        'label' => 'Athletic Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '🏅',

        'priority' => 96,

        'typical_products' => [
            'sportswear',
            'training_wear',
            'teamwear',
        ],

        'required_certifications' => [
            'oeko_tex',
            'grs',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Athletic apparel and sports equipment brands.',
    ],

    [
        'id' => 'cycling_brand',

        'label' => 'Cycling Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '🚴',

        'priority' => 90,

        'typical_products' => [
            'cycling_wear',
            'performance_knit',
            'compression_wear',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'eu',
            'usa',
        ],

        'description'
            => 'Cycling apparel and equipment brands.',
    ],

    [
        'id' => 'running_brand',

        'label' => 'Running Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '👟',

        'priority' => 92,

        'typical_products' => [
            'running_wear',
            'performance_fabric',
            'technical_knit',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'carbon_reduction',
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Running apparel and footwear brands.',
    ],

    [
        'id' => 'yoga_brand',

        'label' => 'Yoga Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '🧘',

        'priority' => 88,

        'typical_products' => [
            'yoga_wear',
            'leggings',
            'stretch_fabric',
        ],

        'required_certifications' => [
            'oeko_tex',
            'gots',
        ],

        'preferred_sustainability' => [
            'organic',
            'recycled_material',
        ],

        'typical_markets' => [
            'usa',
            'eu',
        ],

        'description'
            => 'Yoga and wellness apparel brands.',
    ],

    [
        'id' => 'golf_brand',

        'label' => 'Golf Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '⛳',

        'priority' => 86,

        'typical_products' => [
            'golf_wear',
            'performance_polo',
            'woven_shorts',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'water_saving',
        ],

        'typical_markets' => [
            'usa',
            'japan',
            'korea',
        ],

        'description'
            => 'Golf apparel and accessories brands.',
    ],

    [
        'id' => 'swimwear_brand',

        'label' => 'Swimwear Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '🏊',

        'priority' => 90,

        'typical_products' => [
            'swimwear',
            'chlorine_resistant_fabric',
            'stretch_knit',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_polyester',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Swimwear and beachwear brands.',
    ],

    [
        'id' => 'teamwear_brand',

        'label' => 'Teamwear Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '⚽',

        'priority' => 90,

        'typical_products' => [
            'team_uniform',
            'jersey',
            'training_wear',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Professional teamwear and club apparel brands.',
    ],

    [
        'id' => 'workwear_brand',

        'label' => 'Workwear Brand',

        'category' => 'Sports & Outdoor',

        'icon' => '🦺',

        'priority' => 95,

        'typical_products' => [
            'workwear',
            'protective_clothing',
            'high_visibility',
        ],

        'required_certifications' => [
            'iso9001',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'durability',
            'recycled_material',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'australia',
        ],

        'description'
            => 'Industrial workwear and occupational safety brands.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Retail & Trading Channels
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'department_store',

        'label' => 'Department Store',

        'category' => 'Retail & Trading',

        'icon' => '🏬',

        'priority' => 95,

        'typical_products' => [
            'fashion_apparel',
            'home_textile',
            'accessories',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Multi-brand department store chains.',
    ],

    [
        'id' => 'specialty_retail_chain',

        'label' => 'Specialty Retail Chain',

        'category' => 'Retail & Trading',

        'icon' => '🏪',

        'priority' => 92,

        'typical_products' => [
            'fashion_apparel',
            'sportswear',
            'footwear',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Specialized retail store chains.',
    ],

    [
        'id' => 'hypermarket',

        'label' => 'Hypermarket',

        'category' => 'Retail & Trading',

        'icon' => '🛒',

        'priority' => 88,

        'typical_products' => [
            'basic_apparel',
            'home_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Large-scale retail hypermarkets.',
    ],

    [
        'id' => 'supermarket_chain',

        'label' => 'Supermarket Chain',

        'category' => 'Retail & Trading',

        'icon' => '🛍️',

        'priority' => 82,

        'typical_products' => [
            'basic_apparel',
            'home_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'National and international supermarket chains.',
    ],

    [
        'id' => 'ecommerce_brand',

        'label' => 'E-Commerce Brand',

        'category' => 'Retail & Trading',

        'icon' => '💻',

        'priority' => 96,

        'typical_products' => [
            'fashion_apparel',
            'sportswear',
            'home_textile',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'traceability',
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Digital native and online-first brands.',
    ],

    [
        'id' => 'marketplace_seller',

        'label' => 'Marketplace Seller',

        'category' => 'Retail & Trading',

        'icon' => '📦',

        'priority' => 80,

        'typical_products' => [
            'fashion_apparel',
            'home_textile',
            'accessories',
        ],

        'required_certifications' => [],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Professional marketplace merchants.',
    ],

    [
        'id' => 'importer',

        'label' => 'Importer',

        'category' => 'Retail & Trading',

        'icon' => '🚢',

        'priority' => 97,

        'typical_products' => [
            'yarn',
            'fabric',
            'garment',
            'home_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'International textile and apparel importers.',
    ],

    [
        'id' => 'distributor',

        'label' => 'Distributor',

        'category' => 'Retail & Trading',

        'icon' => '🚚',

        'priority' => 90,

        'typical_products' => [
            'fabric',
            'garment',
            'technical_textile',
        ],

        'required_certifications' => [],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Regional and national distributors.',
    ],

    [
        'id' => 'wholesaler',

        'label' => 'Wholesaler',

        'category' => 'Retail & Trading',

        'icon' => '📦',

        'priority' => 88,

        'typical_products' => [
            'fabric',
            'garment',
            'accessories',
        ],

        'required_certifications' => [],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Bulk textile and apparel wholesalers.',
    ],

    [
        'id' => 'buying_office',

        'label' => 'Buying Office',

        'category' => 'Retail & Trading',

        'icon' => '🏢',

        'priority' => 99,

        'typical_products' => [
            'fashion_apparel',
            'sportswear',
            'home_textile',
            'footwear',
        ],

        'required_certifications' => [
            'grs',
            'gots',
            'oeko_tex',
            'wrap',
        ],

        'preferred_sustainability' => [
            'traceability',
            'carbon_reduction',
            'circular_economy',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'japan',
        ],

        'description'
            => 'International buying and sourcing offices.',
    ],

    [
        'id' => 'sourcing_agency',

        'label' => 'Sourcing Agency',

        'category' => 'Retail & Trading',

        'icon' => '🤝',

        'priority' => 95,

        'typical_products' => [
            'textile',
            'garment',
            'home_textile',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Professional sourcing and procurement agencies.',
    ],

    [
        'id' => 'trading_house',

        'label' => 'Trading House',

        'category' => 'Retail & Trading',

        'icon' => '🌐',

        'priority' => 96,

        'typical_products' => [
            'fiber',
            'yarn',
            'fabric',
            'garment',
            'technical_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'International textile trading companies.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Industrial & Institutional Buyers
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'automotive_oem',

        'label' => 'Automotive OEM',

        'category' => 'Industrial',

        'icon' => '🚗',

        'priority' => 100,

        'typical_products' => [
            'automotive_textile',
            'seat_fabric',
            'airbag_fabric',
            'headliner',
        ],

        'required_certifications' => [
            'iatf16949',
            'iso9001',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Original Equipment Manufacturers for automotive industry.',
    ],

    [
        'id' => 'automotive_tier1_supplier',

        'label' => 'Automotive Tier 1 Supplier',

        'category' => 'Industrial',

        'icon' => '🚘',

        'priority' => 98,

        'typical_products' => [
            'automotive_fabric',
            'interior_trim',
            'composite_textile',
        ],

        'required_certifications' => [
            'iatf16949',
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Tier 1 automotive component suppliers.',
    ],

    [
        'id' => 'medical_device_manufacturer',

        'label' => 'Medical Device Manufacturer',

        'category' => 'Healthcare',

        'icon' => '🏥',

        'priority' => 99,

        'typical_products' => [
            'medical_textile',
            'nonwoven',
            'surgical_gown',
            'medical_fabric',
        ],

        'required_certifications' => [
            'iso13485',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'clean_production',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of medical devices and healthcare textiles.',
    ],

    [
        'id' => 'hospital_group',

        'label' => 'Hospital Group',

        'category' => 'Healthcare',

        'icon' => '🩺',

        'priority' => 90,

        'typical_products' => [
            'hospital_linen',
            'medical_uniform',
            'medical_textile',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'antibacterial',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Public and private hospital networks.',
    ],

    [
        'id' => 'ppe_manufacturer',

        'label' => 'PPE Manufacturer',

        'category' => 'Industrial',

        'icon' => '🦺',

        'priority' => 98,

        'typical_products' => [
            'protective_clothing',
            'fr_fabric',
            'high_visibility',
        ],

        'required_certifications' => [
            'iso9001',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'durability',
        ],

        'typical_markets' => [
            'eu',
            'usa',
        ],

        'description'
            => 'Manufacturers of personal protective equipment.',
    ],

    [
        'id' => 'military_defense',

        'label' => 'Military & Defense',

        'category' => 'Government',

        'icon' => '🪖',

        'priority' => 100,

        'typical_products' => [
            'military_uniform',
            'ballistic_textile',
            'camouflage_fabric',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Defense procurement organizations.',
    ],

    [
        'id' => 'police_security',

        'label' => 'Police & Security',

        'category' => 'Government',

        'icon' => '👮',

        'priority' => 92,

        'typical_products' => [
            'uniform',
            'protective_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Police and private security organizations.',
    ],

    [
        'id' => 'fire_fighting',

        'label' => 'Fire Fighting Equipment',

        'category' => 'Industrial',

        'icon' => '🚒',

        'priority' => 95,

        'typical_products' => [
            'fire_resistant_fabric',
            'protective_clothing',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'durability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of fire protection equipment.',
    ],

    [
        'id' => 'aerospace_manufacturer',

        'label' => 'Aerospace Manufacturer',

        'category' => 'Industrial',

        'icon' => '✈️',

        'priority' => 98,

        'typical_products' => [
            'composite_textile',
            'technical_textile',
        ],

        'required_certifications' => [
            'as9100',
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Aircraft and aerospace manufacturers.',
    ],

    [
        'id' => 'railway_manufacturer',

        'label' => 'Railway Manufacturer',

        'category' => 'Industrial',

        'icon' => '🚆',

        'priority' => 90,

        'typical_products' => [
            'seat_fabric',
            'interior_textile',
            'technical_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'durability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Railway rolling stock manufacturers.',
    ],

    [
        'id' => 'marine_equipment',

        'label' => 'Marine Equipment',

        'category' => 'Industrial',

        'icon' => '🚢',

        'priority' => 88,

        'typical_products' => [
            'marine_canvas',
            'technical_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'uv_resistance',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Marine and boating equipment manufacturers.',
    ],

    [
        'id' => 'construction_material',

        'label' => 'Construction Material Manufacturer',

        'category' => 'Industrial',

        'icon' => '🏗️',

        'priority' => 90,

        'typical_products' => [
            'geotextile',
            'construction_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Construction and infrastructure material manufacturers.',
    ],

    [
        'id' => 'furniture_manufacturer',

        'label' => 'Furniture Manufacturer',

        'category' => 'Home & Living',

        'icon' => '🛋️',

        'priority' => 92,

        'typical_products' => [
            'upholstery',
            'furniture_fabric',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Residential and commercial furniture manufacturers.',
    ],

    [
        'id' => 'mattress_manufacturer',

        'label' => 'Mattress Manufacturer',

        'category' => 'Home & Living',

        'icon' => '🛏️',

        'priority' => 88,

        'typical_products' => [
            'mattress_fabric',
            'quilted_fabric',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Mattress and bedding manufacturers.',
    ],

    [
        'id' => 'hospitality_group',

        'label' => 'Hospitality Group',

        'category' => 'Hospitality',

        'icon' => '🏨',

        'priority' => 90,

        'typical_products' => [
            'hotel_linen',
            'towel',
            'curtain',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'organic',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Hotels, resorts and hospitality chains.',
    ],

    [
        'id' => 'government_procurement',

        'label' => 'Government Procurement',

        'category' => 'Government',

        'icon' => '🏛️',

        'priority' => 95,

        'typical_products' => [
            'uniform',
            'technical_textile',
            'hospital_textile',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Central and local government procurement agencies.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Home Textile, Lifestyle & Consumer Goods
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'home_textile_brand',

        'label' => 'Home Textile Brand',

        'category' => 'Home & Lifestyle',

        'icon' => '🏡',

        'priority' => 96,

        'typical_products' => [
            'bed_linen',
            'bath_linen',
            'table_linen',
            'decorative_textile',
        ],

        'required_certifications' => [
            'oeko_tex',
            'gots',
        ],

        'preferred_sustainability' => [
            'organic',
            'recycled_material',
        ],

        'typical_markets' => [
            'eu',
            'usa',
            'japan',
        ],

        'description'
            => 'Brands specializing in home textile products.',
    ],

    [
        'id' => 'bedding_brand',

        'label' => 'Bedding Brand',

        'category' => 'Home & Lifestyle',

        'icon' => '🛏️',

        'priority' => 95,

        'typical_products' => [
            'bed_sheet',
            'duvet_cover',
            'pillow_case',
            'comforter',
        ],

        'required_certifications' => [
            'oeko_tex',
            'gots',
        ],

        'preferred_sustainability' => [
            'organic',
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers and brands of bedding products.',
    ],

    [
        'id' => 'bath_textile_brand',

        'label' => 'Bath Textile Brand',

        'category' => 'Home & Lifestyle',

        'icon' => '🛁',

        'priority' => 90,

        'typical_products' => [
            'towel',
            'bathrobe',
            'bath_mat',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'organic',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Brands producing bath and spa textile products.',
    ],

    [
        'id' => 'window_covering_brand',

        'label' => 'Window Covering Brand',

        'category' => 'Home & Lifestyle',

        'icon' => '🪟',

        'priority' => 88,

        'typical_products' => [
            'curtain',
            'blind',
            'drapery',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers and brands of curtains and window coverings.',
    ],

    [
        'id' => 'interior_design_company',

        'label' => 'Interior Design Company',

        'category' => 'Home & Lifestyle',

        'icon' => '🏠',

        'priority' => 90,

        'typical_products' => [
            'upholstery',
            'curtain',
            'decorative_fabric',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'low_chemical',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Interior designers and project contractors.',
    ],

    [
        'id' => 'baby_product_brand',

        'label' => 'Baby Product Brand',

        'category' => 'Consumer Goods',

        'icon' => '👶',

        'priority' => 98,

        'typical_products' => [
            'baby_clothing',
            'baby_blanket',
            'baby_bedding',
        ],

        'required_certifications' => [
            'gots',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'organic',
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers and brands of baby textile products.',
    ],

    [
        'id' => 'pet_product_brand',

        'label' => 'Pet Product Brand',

        'category' => 'Consumer Goods',

        'icon' => '🐶',

        'priority' => 82,

        'typical_products' => [
            'pet_bedding',
            'pet_accessories',
            'pet_apparel',
        ],

        'required_certifications' => [],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Brands producing textile products for pets.',
    ],

    [
        'id' => 'lifestyle_brand',

        'label' => 'Lifestyle Brand',

        'category' => 'Consumer Goods',

        'icon' => '✨',

        'priority' => 90,

        'typical_products' => [
            'fashion_accessories',
            'home_accessories',
            'gift_items',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Lifestyle and premium consumer brands.',
    ],

    [
        'id' => 'promotional_merchandise_company',

        'label' => 'Promotional Merchandise Company',

        'category' => 'Corporate',

        'icon' => '🎁',

        'priority' => 85,

        'typical_products' => [
            'tshirt',
            'cap',
            'bag',
            'uniform',
        ],

        'required_certifications' => [],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Corporate promotional merchandise suppliers.',
    ],

    [
        'id' => 'corporate_uniform_company',

        'label' => 'Corporate Uniform Company',

        'category' => 'Corporate',

        'icon' => '👔',

        'priority' => 93,

        'typical_products' => [
            'corporate_uniform',
            'workwear',
            'hospital_uniform',
        ],

        'required_certifications' => [
            'oeko_tex',
            'iso9001',
        ],

        'preferred_sustainability' => [
            'durability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Suppliers of uniforms for corporations and institutions.',
    ],
        /*
    |--------------------------------------------------------------------------
    | Textile Industry (B2B Manufacturing)
    |--------------------------------------------------------------------------
    */

    [
        'id' => 'fiber_producer',

        'label' => 'Fiber Producer',

        'category' => 'Textile Manufacturing',

        'icon' => '🧵',

        'priority' => 98,

        'typical_products' => [
            'natural_fiber',
            'synthetic_fiber',
            'specialty_fiber',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers purchasing raw materials for fiber production.',
    ],

    [
        'id' => 'yarn_spinner',

        'label' => 'Yarn Spinner',

        'category' => 'Textile Manufacturing',

        'icon' => '🪢',

        'priority' => 100,

        'typical_products' => [
            'cotton',
            'polyester_staple_fiber',
            'viscose',
            'wool',
            'acrylic',
        ],

        'required_certifications' => [
            'oeko_tex',
            'grs',
        ],

        'preferred_sustainability' => [
            'organic',
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Spinning mills purchasing textile fibers.',
    ],

    [
        'id' => 'thread_manufacturer',

        'label' => 'Thread Manufacturer',

        'category' => 'Textile Manufacturing',

        'icon' => '🧶',

        'priority' => 95,

        'typical_products' => [
            'spun_yarn',
            'filament_yarn',
            'industrial_yarn',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Industrial sewing thread manufacturers.',
    ],

    [
        'id' => 'weaving_mill',

        'label' => 'Weaving Mill',

        'category' => 'Textile Manufacturing',

        'icon' => '🪡',

        'priority' => 100,

        'typical_products' => [
            'ring_spun_yarn',
            'filament_yarn',
            'fancy_yarn',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'traceability',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Woven fabric manufacturers.',
    ],

    [
        'id' => 'knitting_mill',

        'label' => 'Knitting Mill',

        'category' => 'Textile Manufacturing',

        'icon' => '🧥',

        'priority' => 100,

        'typical_products' => [
            'cotton_yarn',
            'polyester_yarn',
            'spandex_yarn',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Knitted fabric manufacturers.',
    ],

    [
        'id' => 'dyeing_finishing_mill',

        'label' => 'Dyeing & Finishing Mill',

        'category' => 'Textile Manufacturing',

        'icon' => '🎨',

        'priority' => 98,

        'typical_products' => [
            'grey_fabric',
            'knitted_fabric',
            'woven_fabric',
        ],

        'required_certifications' => [
            'zdhc',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'water_saving',
            'chemical_management',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Textile dyeing and finishing companies.',
    ],

    [
        'id' => 'textile_printer',

        'label' => 'Textile Printer',

        'category' => 'Textile Manufacturing',

        'icon' => '🖨️',

        'priority' => 90,

        'typical_products' => [
            'printed_fabric',
            'digital_print',
            'rotary_print',
        ],

        'required_certifications' => [
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'water_saving',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Textile printing companies.',
    ],

    [
        'id' => 'garment_manufacturer',

        'label' => 'Garment Manufacturer',

        'category' => 'Textile Manufacturing',

        'icon' => '👕

',

        'priority' => 100,

        'typical_products' => [
            'finished_fabric',
            'trim',
            'accessories',
        ],

        'required_certifications' => [
            'wrap',
            'bci',
            'oeko_tex',
        ],

        'preferred_sustainability' => [
            'traceability',
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Cut-Make-Trim and full package garment manufacturers.',
    ],

    [
        'id' => 'oem_manufacturer',

        'label' => 'OEM Manufacturer',

        'category' => 'Textile Manufacturing',

        'icon' => '🏭',

        'priority' => 96,

        'typical_products' => [
            'finished_product',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
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

        'category' => 'Textile Manufacturing',

        'icon' => '🏢',

        'priority' => 95,

        'typical_products' => [
            'fashion_product',
            'consumer_product',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
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
        'id' => 'textile_converter',

        'label' => 'Textile Converter',

        'category' => 'Textile Manufacturing',

        'icon' => '📐',

        'priority' => 88,

        'typical_products' => [
            'grey_fabric',
            'finished_fabric',
        ],

        'required_certifications' => [],

        'preferred_sustainability' => [],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Companies converting fabrics for specialized applications.',
    ],

    [
        'id' => 'nonwoven_manufacturer',

        'label' => 'Nonwoven Manufacturer',

        'category' => 'Textile Manufacturing',

        'icon' => '🩹',

        'priority' => 92,

        'typical_products' => [
            'polypropylene_fiber',
            'polyester_fiber',
            'viscose_fiber',
        ],

        'required_certifications' => [
            'iso9001',
        ],

        'preferred_sustainability' => [
            'recycled_material',
        ],

        'typical_markets' => [
            'global',
        ],

        'description'
            => 'Manufacturers of nonwoven textile products.',
    ],
];