<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Sustainability Tags
|--------------------------------------------------------------------------
|
| Defines sustainability practices adopted by companies.
|
| Used by:
|
| • Company Intelligence
| • Sustainability Score
| • Executive AI
| • Buyer Discovery
| • ESG Readiness
| • Company Passport
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Circular Economy
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'circular_economy',

        'label' => 'Circular Economy',

        'category' => 'circularity',

        'description' => 'Business model supporting circular textile economy.',

        'priority' => 10,

        'active' => true,

    ],

    [

        'id' => 'textile_recycling',

        'label' => 'Textile Recycling',

        'category' => 'circularity',

        'description' => 'Recycling textile waste.',

        'priority' => 20,

        'active' => true,

    ],

    [

        'id' => 'fiber_to_fiber',

        'label' => 'Fiber-to-Fiber Recycling',

        'category' => 'circularity',

        'description' => 'Recycling textile fibers into new fibers.',

        'priority' => 30,

        'active' => true,

    ],

    [

        'id' => 'zero_waste',

        'label' => 'Zero Waste',

        'category' => 'circularity',

        'description' => 'Zero waste manufacturing.',

        'priority' => 40,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Energy
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'renewable_energy',

        'label' => 'Renewable Energy',

        'category' => 'energy',

        'description' => 'Uses renewable energy.',

        'priority' => 100,

        'active' => true,

    ],

    [

        'id' => 'solar_power',

        'label' => 'Solar Power',

        'category' => 'energy',

        'description' => 'Solar photovoltaic system.',

        'priority' => 110,

        'active' => true,

    ],

    [

        'id' => 'biomass',

        'label' => 'Biomass Energy',

        'category' => 'energy',

        'description' => 'Biomass energy utilization.',

        'priority' => 120,

        'active' => true,

    ],

    [

        'id' => 'energy_efficiency',

        'label' => 'Energy Efficiency',

        'category' => 'energy',

        'description' => 'Energy efficiency initiatives.',

        'priority' => 130,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Water
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'water_recycling',

        'label' => 'Water Recycling',

        'category' => 'water',

        'description' => 'Industrial water recycling.',

        'priority' => 200,

        'active' => true,

    ],

    [

        'id' => 'zero_liquid_discharge',

        'label' => 'Zero Liquid Discharge',

        'category' => 'water',

        'description' => 'Zero Liquid Discharge (ZLD).',

        'priority' => 210,

        'active' => true,

    ],

    [

        'id' => 'waterless_dyeing',

        'label' => 'Waterless Dyeing',

        'category' => 'water',

        'description' => 'Waterless dyeing technology.',

        'priority' => 220,

        'active' => true,

    ],

    [

        'id' => 'rainwater_harvesting',

        'label' => 'Rainwater Harvesting',

        'category' => 'water',

        'description' => 'Rainwater harvesting system.',

        'priority' => 230,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Carbon
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'carbon_reduction',

        'label' => 'Carbon Reduction',

        'category' => 'carbon',

        'description' => 'Carbon reduction initiatives.',

        'priority' => 300,

        'active' => true,

    ],

    [

        'id' => 'carbon_neutral',

        'label' => 'Carbon Neutral',

        'category' => 'carbon',

        'description' => 'Carbon neutral operation.',

        'priority' => 310,

        'active' => true,

    ],

    [

        'id' => 'net_zero',

        'label' => 'Net Zero',

        'category' => 'carbon',

        'description' => 'Net Zero emission commitment.',

        'priority' => 320,

        'active' => true,

    ],

    [

        'id' => 'life_cycle_assessment',

        'label' => 'Life Cycle Assessment',

        'category' => 'carbon',

        'description' => 'Life Cycle Assessment (LCA).',

        'priority' => 330,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Materials
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'organic_material',

        'label' => 'Organic Materials',

        'category' => 'materials',

        'description' => 'Uses organic textile materials.',

        'priority' => 400,

        'active' => true,

    ],

    [

        'id' => 'recycled_material',

        'label' => 'Recycled Materials',

        'category' => 'materials',

        'description' => 'Uses recycled textile materials.',

        'priority' => 410,

        'active' => true,

    ],

    [

        'id' => 'bio_based_material',

        'label' => 'Bio-based Materials',

        'category' => 'materials',

        'description' => 'Uses bio-based materials.',

        'priority' => 420,

        'active' => true,

    ],

    [

        'id' => 'responsible_sourcing',

        'label' => 'Responsible Sourcing',

        'category' => 'materials',

        'description' => 'Responsible sourcing practices.',

        'priority' => 430,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Chemicals
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'green_chemistry',

        'label' => 'Green Chemistry',

        'category' => 'chemical',

        'description' => 'Environmentally safer chemistry.',

        'priority' => 500,

        'active' => true,

    ],

    [

        'id' => 'eco_ink',

        'label' => 'Eco Ink',

        'category' => 'chemical',

        'description' => 'Eco-friendly textile ink.',

        'priority' => 510,

        'active' => true,

    ],

    [

        'id' => 'restricted_substance_management',

        'label' => 'Restricted Substance Management',

        'category' => 'chemical',

        'description' => 'Restricted substance management practices.',

        'priority' => 520,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Social
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'fair_labor',

        'label' => 'Fair Labor',

        'category' => 'social',

        'description' => 'Fair labor practices.',

        'priority' => 600,

        'active' => true,

    ],

    [

        'id' => 'worker_wellbeing',

        'label' => 'Worker Wellbeing',

        'category' => 'social',

        'description' => 'Employee wellbeing initiatives.',

        'priority' => 610,

        'active' => true,

    ],

    [

        'id' => 'community_engagement',

        'label' => 'Community Engagement',

        'category' => 'social',

        'description' => 'Community development programs.',

        'priority' => 620,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Governance
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'esg_reporting',

        'label' => 'ESG Reporting',

        'category' => 'governance',

        'description' => 'Environmental, Social & Governance reporting.',

        'priority' => 700,

        'active' => true,

    ],

    [

        'id' => 'supply_chain_transparency',

        'label' => 'Supply Chain Transparency',

        'category' => 'governance',

        'description' => 'Transparent supply chain management.',

        'priority' => 710,

        'active' => true,

    ],

    [

        'id' => 'digital_product_passport',

        'label' => 'Digital Product Passport',

        'category' => 'governance',

        'description' => 'Digital Product Passport implementation.',

        'priority' => 720,

        'active' => true,

    ],

];