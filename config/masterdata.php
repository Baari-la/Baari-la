<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
|
| Single Source of Truth
|
| Used by:
|
| • Company Profile
| • Marketplace
| • Buyer Discovery
| • Supply Chain Intelligence
| • Recommendation Engine
| • Executive AI
| • REST API
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Product Categories
    |--------------------------------------------------------------------------
    */

    'product_categories' => [

        [
            'id' => 'fiber',
            'label' => 'Fiber',
        ],

        [
            'id' => 'yarn',
            'label' => 'Yarn',
        ],

        [
            'id' => 'fabric',
            'label' => 'Fabric',
        ],

        [
            'id' => 'garment',
            'label' => 'Garment',
        ],

        [
            'id' => 'home_textile',
            'label' => 'Home Textile',
        ],

        [
            'id' => 'technical_textile',
            'label' => 'Technical Textile',
        ],

        [
            'id' => 'nonwoven',
            'label' => 'Nonwoven',
        ],

        [
            'id' => 'chemical',
            'label' => 'Chemical',
        ],

        [
            'id' => 'machinery',
            'label' => 'Machinery',
        ],

        [
            'id' => 'accessories',
            'label' => 'Accessories',
        ],

        [
            'id' => 'packaging',
            'label' => 'Packaging',
        ],

        [
            'id' => 'service',
            'label' => 'Service',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Product Applications
    |--------------------------------------------------------------------------
    */

    'product_applications' => [

        [
            'id' => 'apparel',
            'label' => 'Apparel',
        ],

        [
            'id' => 'home_textile',
            'label' => 'Home Textile',
        ],

        [
            'id' => 'technical_textile',
            'label' => 'Technical Textile',
        ],

        [
            'id' => 'industrial',
            'label' => 'Industrial',
        ],

        [
            'id' => 'automotive',
            'label' => 'Automotive',
        ],

        [
            'id' => 'medical',
            'label' => 'Medical',
        ],

        [
            'id' => 'sports',
            'label' => 'Sports',
        ],

        [
            'id' => 'footwear',
            'label' => 'Footwear',
        ],

        [
            'id' => 'packaging',
            'label' => 'Packaging',
        ],

        [
            'id' => 'military',
            'label' => 'Military',
        ],

        [
            'id' => 'protective',
            'label' => 'Protective',
        ],

        [
            'id' => 'others',
            'label' => 'Others',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Product Statuses
    |--------------------------------------------------------------------------
    */

    'product_statuses' => [

        [
            'id' => 'active',
            'label' => 'Active',
        ],

        [
            'id' => 'new',
            'label' => 'New Product',
        ],

        [
            'id' => 'featured',
            'label' => 'Featured Product',
        ],

        [
            'id' => 'on_request',
            'label' => 'On Request',
        ],

        [
            'id' => 'seasonal',
            'label' => 'Seasonal',
        ],

        [
            'id' => 'discontinued',
            'label' => 'Discontinued',
        ],

    ],

'business_roles' => [

    [
        'id' => 'fiber_manufacturer',

        'label' => 'Fiber Manufacturer',

        'icon' => '🧶',

        'color' => '#2563EB',

        'priority' => 10,

        'upstream' => [],

        'downstream' => [

            'yarn_spinner',

        ],
    ],

    
],
    
];