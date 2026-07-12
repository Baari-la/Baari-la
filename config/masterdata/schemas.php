<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    'Business/business_roles.php' => [

        'type' => 'knowledge_node',

        'required' => [
            'id',
            'label',
            'category',
            'description',
        ],

        'optional' => [
            'icon',
            'priority',
        ],

    ],

    'Business/buyer_segments.php' => [

        'type' => 'knowledge_node',

        'required' => [
            'id',
            'label',
            'category',
            'description',
        ],

        'optional' => [
            'icon',
            'priority',
            'typical_products',
            'common_business_roles',
            'common_certifications',
            'common_sustainability',
            'typical_markets',
        ],

    ],

    'Business/supplier_segments.php' => [

        'type' => 'knowledge_node',

        'required' => [
            'id',
            'label',
            'category',
            'description',
        ],

        'optional' => [
            'icon',
            'priority',
            'typical_products',
            'common_business_roles',
            'common_buyer_segments',
            'common_certifications',
            'common_sustainability',
            'typical_markets',
        ],

    ],

    'Business/business_ecosystems.php' => [

        'type' => 'lookup',

        'required' => [
            'id',
            'label',
            'description',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    'Products/products.php' => [

        'type' => 'knowledge_node',

        'required' => [
            'id',
            'label',
            'category',
            'description',
        ],

        'optional' => [
            'icon',
            'priority',
        ],

    ],

    'Products/product_categories.php' => [

        'type' => 'lookup',

        'required' => [
            'id',
            'label',
            'description',
        ],

    ],

    'Products/product_applications.php' => [

        'type' => 'lookup',

        'required' => [
            'id',
            'label',
            'description',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Certifications
    |--------------------------------------------------------------------------
    */

    'Certification/certifications.php' => [

        'type' => 'knowledge_node',

        'required' => [
            'id',
            'label',
            'description',
        ],

        'optional' => [
            'icon',
            'priority',
        ],

    ],

    'Certification/certification_categories.php' => [

        'type' => 'lookup',

        'required' => [
            'id',
            'label',
            'description',
        ],

    ],

];