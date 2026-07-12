<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Technology Knowledge Base
|--------------------------------------------------------------------------
|
| Global Textile Manufacturing Technologies
|
| Used by:
|
| • Company Intelligence
| • Company Passport
| • Executive AI
| • Supply Chain Recommendation Engine
| • Marketplace
| • Buyer Discovery
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Fiber Manufacturing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'polymerization',

        'label' => 'Polymerization',

        'category' => 'fiber',

        'description' => 'Polymer production technology.',

        'priority' => 10,

        'active' => true,

    ],

    [

        'id' => 'melt_spinning',

        'label' => 'Melt Spinning',

        'category' => 'fiber',

        'description' => 'Synthetic fiber spinning process.',

        'priority' => 20,

        'active' => true,

    ],

    [

        'id' => 'dry_spinning',

        'label' => 'Dry Spinning',

        'category' => 'fiber',

        'description' => 'Dry spinning process.',

        'priority' => 30,

        'active' => true,

    ],

    [

        'id' => 'wet_spinning',

        'label' => 'Wet Spinning',

        'category' => 'fiber',

        'description' => 'Wet spinning technology.',

        'priority' => 40,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Yarn Manufacturing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'ring_spinning',

        'label' => 'Ring Spinning',

        'category' => 'yarn',

        'description' => 'Conventional ring spinning.',

        'priority' => 100,

        'active' => true,

    ],

    [

        'id' => 'compact_spinning',

        'label' => 'Compact Spinning',

        'category' => 'yarn',

        'description' => 'Compact spinning technology.',

        'priority' => 110,

        'active' => true,

    ],

    [

        'id' => 'open_end_spinning',

        'label' => 'Open-End Spinning',

        'category' => 'yarn',

        'description' => 'Rotor spinning technology.',

        'priority' => 120,

        'active' => true,

    ],

    [

        'id' => 'air_jet_spinning',

        'label' => 'Air Jet Spinning',

        'category' => 'yarn',

        'description' => 'Murata air jet spinning.',

        'priority' => 130,

        'active' => true,

    ],

    [

        'id' => 'vortex_spinning',

        'label' => 'Vortex Spinning',

        'category' => 'yarn',

        'description' => 'Vortex spinning technology.',

        'priority' => 140,

        'active' => true,

    ],

    [

        'id' => 'texturizing',

        'label' => 'Texturizing',

        'category' => 'yarn',

        'description' => 'DTY / ATY texturizing.',

        'priority' => 150,

        'active' => true,

    ],

    [

        'id' => 'air_texturing',

        'label' => 'Air Texturing',

        'category' => 'yarn',

        'description' => 'Air texturing process.',

        'priority' => 160,

        'active' => true,

    ],

    [

        'id' => 'yarn_twisting',

        'label' => 'Yarn Twisting',

        'category' => 'yarn',

        'description' => 'Yarn twisting technology.',

        'priority' => 170,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Fabric Manufacturing
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'rapier_weaving',

        'label' => 'Rapier Weaving',

        'category' => 'fabric',

        'description' => 'Rapier weaving technology.',

        'priority' => 200,

        'active' => true,

    ],

    [

        'id' => 'air_jet_weaving',

        'label' => 'Air Jet Weaving',

        'category' => 'fabric',

        'description' => 'Air jet weaving.',

        'priority' => 210,

        'active' => true,

    ],

    [

        'id' => 'water_jet_weaving',

        'label' => 'Water Jet Weaving',

        'category' => 'fabric',

        'description' => 'Water jet weaving.',

        'priority' => 220,

        'active' => true,

    ],

    [

        'id' => 'projectile_weaving',

        'label' => 'Projectile Weaving',

        'category' => 'fabric',

        'description' => 'Projectile weaving technology.',

        'priority' => 230,

        'active' => true,

    ],

    [

        'id' => 'circular_knitting',

        'label' => 'Circular Knitting',

        'category' => 'fabric',

        'description' => 'Circular knitting technology.',

        'priority' => 240,

        'active' => true,

    ],

    [

        'id' => 'warp_knitting',
        'label' => 'Warp Knitting',
        'category' => 'fabric',
        'description' => 'Warp knitting technology.',
        'priority' => 250,
        'active' => true,

    ],

    [

        'id' => 'nonwoven',
        'label' => 'Nonwoven Technology',
        'category' => 'fabric',
        'description' => 'Spunbond, meltblown and needle punch.',
        'priority' => 260,
        'active' => true,

    ],
    /*
    |--------------------------------------------------------------------------
    | Dyeing Technologies
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'jet_dyeing',

        'label' => 'Jet Dyeing',

        'category' => 'dyeing',

        'description' => 'Jet dyeing technology for woven and knitted fabrics.',

        'priority' => 300,

        'active' => true,

    ],

    [

        'id' => 'beam_dyeing',

        'label' => 'Beam Dyeing',

        'category' => 'dyeing',

        'description' => 'Beam dyeing process.',

        'priority' => 310,

        'active' => true,

    ],

    [

        'id' => 'package_dyeing',

        'label' => 'Package Dyeing',

        'category' => 'dyeing',

        'description' => 'Package dyeing for yarn.',

        'priority' => 320,

        'active' => true,

    ],

    [

        'id' => 'continuous_dyeing',

        'label' => 'Continuous Dyeing',

        'category' => 'dyeing',

        'description' => 'Continuous fabric dyeing.',

        'priority' => 330,

        'active' => true,

    ],

    [

        'id' => 'cold_pad_batch',

        'label' => 'Cold Pad Batch',

        'category' => 'dyeing',

        'description' => 'Cold Pad Batch dyeing process.',

        'priority' => 340,

        'active' => true,

    ],

    [

        'id' => 'foam_dyeing',

        'label' => 'Foam Dyeing',

        'category' => 'dyeing',

        'description' => 'Low-water foam dyeing technology.',

        'priority' => 350,

        'active' => true,

    ],

    [

        'id' => 'waterless_dyeing',

        'label' => 'Waterless Dyeing',

        'category' => 'dyeing',

        'description' => 'Waterless dyeing technology.',

        'priority' => 360,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Finishing Technologies
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'heat_setting',

        'label' => 'Heat Setting',

        'category' => 'finishing',

        'description' => 'Heat setting process.',

        'priority' => 400,

        'active' => true,

    ],

    [

        'id' => 'sanforizing',

        'label' => 'Sanforizing',

        'category' => 'finishing',

        'description' => 'Fabric shrinkage control.',

        'priority' => 410,

        'active' => true,

    ],

    [

        'id' => 'calendering',

        'label' => 'Calendering',

        'category' => 'finishing',

        'description' => 'Surface finishing using calender rolls.',

        'priority' => 420,

        'active' => true,

    ],

    [

        'id' => 'coating',

        'label' => 'Coating',

        'category' => 'finishing',

        'description' => 'Functional textile coating.',

        'priority' => 430,

        'active' => true,

    ],

    [

        'id' => 'laminating',

        'label' => 'Laminating',

        'category' => 'finishing',

        'description' => 'Fabric lamination process.',

        'priority' => 440,

        'active' => true,

    ],

    [

        'id' => 'bonding',

        'label' => 'Bonding',

        'category' => 'finishing',

        'description' => 'Composite textile bonding.',

        'priority' => 450,

        'active' => true,

    ],

    [

        'id' => 'antimicrobial_finish',

        'label' => 'Antimicrobial Finish',

        'category' => 'finishing',

        'description' => 'Antimicrobial textile finishing.',

        'priority' => 460,

        'active' => true,

    ],

    [

        'id' => 'water_repellent_finish',

        'label' => 'Water Repellent Finish',

        'category' => 'finishing',

        'description' => 'Durable Water Repellent (DWR) finishing.',

        'priority' => 470,

        'active' => true,

    ],

    [

        'id' => 'flame_retardant_finish',

        'label' => 'Flame Retardant Finish',

        'category' => 'finishing',

        'description' => 'Flame retardant textile finishing.',

        'priority' => 480,

        'active' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Printing Technologies
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'rotary_printing',

        'label' => 'Rotary Screen Printing',

        'category' => 'printing',

        'description' => 'Rotary screen textile printing.',

        'priority' => 500,

        'active' => true,

    ],

    [

        'id' => 'flatbed_printing',

        'label' => 'Flatbed Printing',

        'category' => 'printing',

        'description' => 'Flatbed screen printing.',

        'priority' => 510,

        'active' => true,

    ],

    [

        'id' => 'digital_printing',

        'label' => 'Digital Textile Printing',

        'category' => 'printing',

        'description' => 'Digital textile printing technology.',

        'priority' => 520,

        'active' => true,

    ],

    [

        'id' => 'pigment_printing',

        'label' => 'Pigment Printing',

        'category' => 'printing',

        'description' => 'Pigment-based digital printing.',

        'priority' => 530,

        'active' => true,

    ],

    [

        'id' => 'reactive_printing',

        'label' => 'Reactive Printing',

        'category' => 'printing',

        'description' => 'Reactive dye digital printing.',

        'priority' => 540,

        'active' => true,

    ],

    [

        'id' => 'acid_printing',

        'label' => 'Acid Printing',

        'category' => 'printing',

        'description' => 'Acid dye digital printing.',

        'priority' => 550,

        'active' => true,

    ],

    [

        'id' => 'disperse_printing',

        'label' => 'Disperse Printing',

        'category' => 'printing',

        'description' => 'Disperse dye printing.',

        'priority' => 560,

        'active' => true,

    ],

    [

        'id' => 'sublimation',

        'label' => 'Sublimation Printing',

        'category' => 'printing',

        'description' => 'Digital sublimation printing.',

        'priority' => 570,

        'active' => true,

    ],

    [

        'id' => 'dtf_printing',

        'label' => 'Direct to Film (DTF)',

        'category' => 'printing',

        'description' => 'Direct-to-film digital printing.',

        'priority' => 580,

        'active' => true,

    ],

    [

        'id' => 'dtg_printing',

        'label' => 'Direct to Garment (DTG)',

        'category' => 'printing',

        'description' => 'Direct-to-garment digital printing.',

        'priority' => 590,

        'active' => true,

    ],
    /*
    |--------------------------------------------------------------------------
    | Garment Manufacturing Technologies
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'cad_pattern_design',

        'label' => 'CAD Pattern Design',

        'category' => 'garment',

        'description' => 'Computer Aided Pattern Design.',

        'priority' => 600,

        'active' => true,

    ],

    [

        'id' => '3d_garment_design',

        'label' => '3D Garment Design',

        'category' => 'garment',

        'description' => '3D garment visualization and virtual sampling.',

        'priority' => 610,

        'active' => true,

    ],

    [

        'id' => 'marker_making',

        'label' => 'Marker Making',

        'category' => 'garment',

        'description' => 'Automatic marker planning.',

        'priority' => 620,

        'active' => true,

    ],

    [

        'id' => 'automatic_fabric_spreading',

        'label' => 'Automatic Fabric Spreading',

        'category' => 'garment',

        'description' => 'Automatic fabric spreading system.',

        'priority' => 630,

        'active' => true,

    ],

    [

        'id' => 'automatic_cutting',

        'label' => 'Automatic Cutting',

        'category' => 'garment',

        'description' => 'CNC automatic fabric cutting.',

        'priority' => 640,

        'active' => true,

    ],

    [

        'id' => 'laser_cutting',

        'label' => 'Laser Cutting',

        'category' => 'garment',

        'description' => 'Laser cutting technology.',

        'priority' => 650,

        'active' => true,

    ],

    [

        'id' => 'ultrasonic_cutting',

        'label' => 'Ultrasonic Cutting',

        'category' => 'garment',

        'description' => 'Ultrasonic textile cutting.',

        'priority' => 660,

        'active' => true,

    ],

    [

        'id' => 'computerized_sewing',

        'label' => 'Computerized Sewing',

        'category' => 'garment',

        'description' => 'Computer-controlled sewing machines.',

        'priority' => 670,

        'active' => true,

    ],

    [

        'id' => 'programmable_sewing',

        'label' => 'Programmable Sewing',

        'category' => 'garment',

        'description' => 'Programmable sewing automation.',

        'priority' => 680,

        'active' => true,

    ],

    [

        'id' => 'seam_sealing',

        'label' => 'Seam Sealing',

        'category' => 'garment',

        'description' => 'Waterproof seam sealing technology.',

        'priority' => 690,

        'active' => true,

    ],

    [

        'id' => 'heat_transfer',

        'label' => 'Heat Transfer',

        'category' => 'garment',

        'description' => 'Heat transfer decoration technology.',

        'priority' => 700,

        'active' => true,

    ],

    [

        'id' => 'embroidery',

        'label' => 'Embroidery',

        'category' => 'garment',

        'description' => 'Computerized embroidery technology.',

        'priority' => 710,

        'active' => true,

    ],

    [

        'id' => 'digital_embroidery',

        'label' => 'Digital Embroidery',

        'category' => 'garment',

        'description' => 'Multi-head digital embroidery.',

        'priority' => 720,

        'active' => true,

    ],

    [

        'id' => 'bondingGarment',

        'label' => 'Bonding',

        'category' => 'garment',

        'description' => 'Bonded garment manufacturing.',

        'priority' => 730,

        'active' => true,

    ],

    [

        'id' => 'laser_engraving',

        'label' => 'Laser Engraving',

        'category' => 'garment',

        'description' => 'Laser engraving and finishing.',

        'priority' => 740,

        'active' => true,

    ],

    [

        'id' => 'garment_washing',

        'label' => 'Garment Washing',

        'category' => 'garment',

        'description' => 'Industrial garment washing.',

        'priority' => 750,

        'active' => true,

    ],

    [

        'id' => 'ozone_washing',

        'label' => 'Ozone Washing',

        'category' => 'garment',

        'description' => 'Low-water ozone washing technology.',

        'priority' => 760,

        'active' => true,

    ],

    [

        'id' => 'enzyme_washing',

        'label' => 'Enzyme Washing',

        'category' => 'garment',

        'description' => 'Enzyme finishing technology.',

        'priority' => 770,

        'active' => true,

    ],

    [

        'id' => 'automatic_hanger_system',

        'label' => 'Automatic Hanger System',

        'category' => 'garment',

        'description' => 'Overhead automatic hanger production system.',

        'priority' => 780,

        'active' => true,

    ],

    [

        'id' => 'lean_manufacturing',

        'label' => 'Lean Manufacturing',

        'category' => 'garment',

        'description' => 'Lean production methodology.',

        'priority' => 790,

        'active' => true,

    ],

    [

        'id' => 'modular_production',

        'label' => 'Modular Production',

        'category' => 'garment',

        'description' => 'Modular garment manufacturing system.',

        'priority' => 800,

        'active' => true,

    ],
        /*
    |--------------------------------------------------------------------------
    | Digital Manufacturing Technologies
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'erp',

        'label' => 'Enterprise Resource Planning (ERP)',

        'category' => 'digital',

        'description' => 'Enterprise Resource Planning platform.',

        'priority' => 900,

        'active' => true,

    ],

    [

        'id' => 'mes',

        'label' => 'Manufacturing Execution System (MES)',

        'category' => 'digital',

        'description' => 'Manufacturing execution and shop floor control.',

        'priority' => 910,

        'active' => true,

    ],

    [

        'id' => 'plm',

        'label' => 'Product Lifecycle Management (PLM)',

        'category' => 'digital',

        'description' => 'Product lifecycle management platform.',

        'priority' => 920,

        'active' => true,

    ],

    [

        'id' => 'aps',

        'label' => 'Advanced Planning & Scheduling (APS)',

        'category' => 'digital',

        'description' => 'Production planning and scheduling.',

        'priority' => 930,

        'active' => true,

    ],

    [

        'id' => 'wms',

        'label' => 'Warehouse Management System (WMS)',

        'category' => 'digital',

        'description' => 'Warehouse operations management.',

        'priority' => 940,

        'active' => true,

    ],

    [

        'id' => 'scm',

        'label' => 'Supply Chain Management (SCM)',

        'category' => 'digital',

        'description' => 'Supply chain planning and execution.',

        'priority' => 950,

        'active' => true,

    ],

    [

        'id' => 'crm',

        'label' => 'Customer Relationship Management (CRM)',

        'category' => 'digital',

        'description' => 'Customer relationship management platform.',

        'priority' => 960,

        'active' => true,

    ],

    [

        'id' => 'bi',

        'label' => 'Business Intelligence (BI)',

        'category' => 'digital',

        'description' => 'Business analytics and executive dashboard.',

        'priority' => 970,

        'active' => true,

    ],

    [

        'id' => 'digital_twin',

        'label' => 'Digital Twin',

        'category' => 'digital',

        'description' => 'Digital representation of production systems.',

        'priority' => 980,

        'active' => true,

    ],

    [

        'id' => 'iot',

        'label' => 'Industrial IoT',

        'category' => 'digital',

        'description' => 'Industrial Internet of Things connectivity.',

        'priority' => 990,

        'active' => true,

    ],

    [

        'id' => 'machine_monitoring',

        'label' => 'Machine Monitoring',

        'category' => 'digital',

        'description' => 'Real-time machine monitoring system.',

        'priority' => 1000,

        'active' => true,

    ],

    [

        'id' => 'predictive_maintenance',

        'label' => 'Predictive Maintenance',

        'category' => 'digital',

        'description' => 'AI-based maintenance prediction.',

        'priority' => 1010,

        'active' => true,

    ],

    [

        'id' => 'ai_vision_inspection',

        'label' => 'AI Vision Inspection',

        'category' => 'digital',

        'description' => 'Artificial intelligence visual quality inspection.',

        'priority' => 1020,

        'active' => true,

    ],

    [

        'id' => 'robotics',

        'label' => 'Industrial Robotics',

        'category' => 'digital',

        'description' => 'Robotic manufacturing automation.',

        'priority' => 1030,

        'active' => true,

    ],

    [

        'id' => 'agv',

        'label' => 'Automated Guided Vehicle (AGV)',

        'category' => 'digital',

        'description' => 'Autonomous material handling.',

        'priority' => 1040,

        'active' => true,

    ],

    [

        'id' => 'rfid',

        'label' => 'RFID Tracking',

        'category' => 'digital',

        'description' => 'RFID-based production and inventory tracking.',

        'priority' => 1050,

        'active' => true,

    ],

    [

        'id' => 'barcode_tracking',

        'label' => 'Barcode Tracking',

        'category' => 'digital',

        'description' => 'Barcode production tracking.',

        'priority' => 1060,

        'active' => true,

    ],

    [

        'id' => 'digital_product_passport',

        'label' => 'Digital Product Passport',

        'category' => 'digital',

        'description' => 'Digital Product Passport (DPP) for traceability.',

        'priority' => 1070,

        'active' => true,

    ],

    [

        'id' => 'blockchain_traceability',

        'label' => 'Blockchain Traceability',

        'category' => 'digital',

        'description' => 'Blockchain-based supply chain traceability.',

        'priority' => 1080,

        'active' => true,

    ],

    [

        'id' => 'cloud_manufacturing',

        'label' => 'Cloud Manufacturing',

        'category' => 'digital',

        'description' => 'Cloud-connected manufacturing operations.',

        'priority' => 1090,

        'active' => true,

    ],

    [

        'id' => 'api_integration',

        'label' => 'API Integration',

        'category' => 'digital',

        'description' => 'Enterprise system integration using APIs.',

        'priority' => 1100,

        'active' => true,

    ],
       /*
    |--------------------------------------------------------------------------
    | Sustainability Technologies
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'renewable_energy',

        'label' => 'Renewable Energy',

        'category' => 'sustainability',

        'description' => 'Use of renewable energy in manufacturing.',

        'priority' => 1200,

        'active' => true,

    ],

    [

        'id' => 'solar_power',

        'label' => 'Solar Power',

        'category' => 'sustainability',

        'description' => 'Solar photovoltaic energy system.',

        'priority' => 1210,

        'active' => true,

    ],

    [

        'id' => 'biomass_energy',

        'label' => 'Biomass Energy',

        'category' => 'sustainability',

        'description' => 'Biomass-based renewable energy.',

        'priority' => 1220,

        'active' => true,

    ],

    [

        'id' => 'energy_management_system',

        'label' => 'Energy Management System',

        'category' => 'sustainability',

        'description' => 'System for monitoring and optimizing energy consumption.',

        'priority' => 1230,

        'active' => true,

    ],

    [

        'id' => 'water_recycling',

        'label' => 'Water Recycling',

        'category' => 'sustainability',

        'description' => 'Industrial water recycling system.',

        'priority' => 1240,

        'active' => true,

    ],

    [

        'id' => 'zero_liquid_discharge',

        'label' => 'Zero Liquid Discharge (ZLD)',

        'category' => 'sustainability',

        'description' => 'Zero Liquid Discharge wastewater technology.',

        'priority' => 1250,

        'active' => true,

    ],

    [

        'id' => 'rainwater_harvesting',

        'label' => 'Rainwater Harvesting',

        'category' => 'sustainability',

        'description' => 'Rainwater collection and utilization system.',

        'priority' => 1260,

        'active' => true,

    ],

    [

        'id' => 'wastewater_treatment',

        'label' => 'Wastewater Treatment',

        'category' => 'sustainability',

        'description' => 'Industrial wastewater treatment plant.',

        'priority' => 1270,

        'active' => true,

    ],

    [

        'id' => 'closed_loop_manufacturing',

        'label' => 'Closed Loop Manufacturing',

        'category' => 'sustainability',

        'description' => 'Closed-loop production system.',

        'priority' => 1280,

        'active' => true,

    ],

    [

        'id' => 'textile_recycling',

        'label' => 'Textile Recycling',

        'category' => 'sustainability',

        'description' => 'Textile waste recycling technology.',

        'priority' => 1290,

        'active' => true,

    ],

    [

        'id' => 'fiber_to_fiber_recycling',

        'label' => 'Fiber-to-Fiber Recycling',

        'category' => 'sustainability',

        'description' => 'Converts used textile fibers into new fibers.',

        'priority' => 1300,

        'active' => true,

    ],

    [

        'id' => 'chemical_recycling',

        'label' => 'Chemical Recycling',

        'category' => 'sustainability',

        'description' => 'Chemical recycling of textile materials.',

        'priority' => 1310,

        'active' => true,

    ],

    [

        'id' => 'mechanical_recycling',

        'label' => 'Mechanical Recycling',

        'category' => 'sustainability',

        'description' => 'Mechanical textile recycling process.',

        'priority' => 1320,

        'active' => true,

    ],

    [

        'id' => 'bio_based_material',

        'label' => 'Bio-based Material',

        'category' => 'sustainability',

        'description' => 'Bio-based renewable textile materials.',

        'priority' => 1330,

        'active' => true,

    ],

    [

        'id' => 'recycled_polyester',

        'label' => 'Recycled Polyester',

        'category' => 'sustainability',

        'description' => 'Use of recycled polyester fibers.',

        'priority' => 1340,

        'active' => true,

    ],

    [

        'id' => 'organic_cotton',

        'label' => 'Organic Cotton',

        'category' => 'sustainability',

        'description' => 'Organic cotton production and sourcing.',

        'priority' => 1350,

        'active' => true,

    ],

    [

        'id' => 'eco_ink',

        'label' => 'Eco Ink',

        'category' => 'sustainability',

        'description' => 'Environmentally friendly textile printing ink.',

        'priority' => 1360,

        'active' => true,

    ],

    [

        'id' => 'low_carbon_manufacturing',

        'label' => 'Low Carbon Manufacturing',

        'category' => 'sustainability',

        'description' => 'Low-carbon manufacturing practices.',

        'priority' => 1370,

        'active' => true,

    ],

    [

        'id' => 'carbon_footprint_monitoring',

        'label' => 'Carbon Footprint Monitoring',

        'category' => 'sustainability',

        'description' => 'Monitoring and reporting greenhouse gas emissions.',

        'priority' => 1380,

        'active' => true,

    ],

    [

        'id' => 'life_cycle_assessment',

        'label' => 'Life Cycle Assessment (LCA)',

        'category' => 'sustainability',

        'description' => 'Life Cycle Assessment methodology.',

        'priority' => 1390,

        'active' => true,

    ],

    [

        'id' => 'environmental_management_system',

        'label' => 'Environmental Management System',

        'category' => 'sustainability',

        'description' => 'Environmental management and continuous improvement system.',

        'priority' => 1400,

        'active' => true,
    ],
    
        /*
    |--------------------------------------------------------------------------
    | Quality, Laboratory & Traceability Technologies
    |--------------------------------------------------------------------------
    */

    [

        'id' => 'laboratory_information_management',

        'label' => 'Laboratory Information Management System (LIMS)',

        'category' => 'quality',

        'description' => 'Laboratory Information Management System.',

        'priority' => 1500,

        'active' => true,

    ],

    [

        'id' => 'quality_management_system',

        'label' => 'Quality Management System (QMS)',

        'category' => 'quality',

        'description' => 'Digital quality management platform.',

        'priority' => 1510,

        'active' => true,

    ],

    [

        'id' => 'statistical_process_control',

        'label' => 'Statistical Process Control (SPC)',

        'category' => 'quality',

        'description' => 'Real-time statistical process monitoring.',

        'priority' => 1520,

        'active' => true,

    ],

    [

        'id' => 'online_quality_monitoring',

        'label' => 'Online Quality Monitoring',

        'category' => 'quality',

        'description' => 'Real-time production quality monitoring.',

        'priority' => 1530,

        'active' => true,

    ],

    [

        'id' => 'ai_quality_prediction',

        'label' => 'AI Quality Prediction',

        'category' => 'quality',

        'description' => 'Artificial Intelligence based quality prediction.',

        'priority' => 1540,

        'active' => true,

    ],

    [

        'id' => 'fabric_inspection_system',

        'label' => 'Fabric Inspection System',

        'category' => 'quality',

        'description' => 'Automated fabric inspection technology.',

        'priority' => 1550,

        'active' => true,

    ],

    [

        'id' => 'shade_management',

        'label' => 'Shade Management',

        'category' => 'quality',

        'description' => 'Digital shade and color consistency management.',

        'priority' => 1560,

        'active' => true,

    ],

    [

        'id' => 'spectrophotometer',

        'label' => 'Spectrophotometer',

        'category' => 'laboratory',

        'description' => 'Digital color measurement technology.',

        'priority' => 1570,

        'active' => true,

    ],

    [

        'id' => 'color_management_system',

        'label' => 'Color Management System',

        'category' => 'laboratory',

        'description' => 'Digital color management platform.',

        'priority' => 1580,

        'active' => true,

    ],

    [

        'id' => 'digital_traceability',

        'label' => 'Digital Traceability',

        'category' => 'traceability',

        'description' => 'End-to-end digital supply chain traceability.',

        'priority' => 1600,

        'active' => true,

    ],

    [

        'id' => 'qr_product_tracking',

        'label' => 'QR Product Tracking',

        'category' => 'traceability',

        'description' => 'QR Code based product tracking.',

        'priority' => 1610,

        'active' => true,

    ],

    [

        'id' => 'batch_traceability',

        'label' => 'Batch Traceability',

        'category' => 'traceability',

        'description' => 'Production batch traceability.',

        'priority' => 1620,

        'active' => true,

    ],

    [

        'id' => 'supply_chain_visibility',

        'label' => 'Supply Chain Visibility',

        'category' => 'traceability',

        'description' => 'End-to-end supply chain visibility.',

        'priority' => 1630,

        'active' => true,

    ],

    [

        'id' => 'executive_dashboard',

        'label' => 'Executive Dashboard',

        'category' => 'executive',

        'description' => 'Executive KPI dashboard.',

        'priority' => 1700,

        'active' => true,

    ],

    [

        'id' => 'executive_analytics',

        'label' => 'Executive Analytics',

        'category' => 'executive',

        'description' => 'Executive analytics and decision support.',

        'priority' => 1710,

        'active' => true,

    ],

    [

        'id' => 'executive_ai',

        'label' => 'Executive AI',

        'category' => 'executive',

        'description' => 'Artificial Intelligence for executive decision making.',

        'priority' => 1720,

        'active' => true,

    ],

    [

        'id' => 'predictive_analytics',

        'label' => 'Predictive Analytics',

        'category' => 'executive',

        'description' => 'AI-powered predictive analytics.',

        'priority' => 1730,

        'active' => true,

    ],

    [

        'id' => 'decision_support_system',

        'label' => 'Decision Support System',

        'category' => 'executive',

        'description' => 'Strategic decision support platform.',

        'priority' => 1740,

        'active' => true,

    ],

];