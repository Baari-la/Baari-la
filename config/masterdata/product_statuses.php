<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Master Data Framework (DMF)
|--------------------------------------------------------------------------
| Product Statuses
|--------------------------------------------------------------------------
|
| Master Product Statuses used throughout the DIGESTEX ecosystem.
|
| Used by:
|
| • Company Profile
| • Product Intelligence
| • Marketplace
| • Buyer Discovery
| • Executive AI
|
*/

return [

    [
        'id' => 'active',

        'label' => 'Active',

        'description' => 'Product is actively produced and available.',

        'icon' => '✅',

        'color' => '#22C55E',

        'priority' => 10,

        'active' => true,
    ],

    [
        'id' => 'new',

        'label' => 'New Product',

        'description' => 'Recently introduced product.',

        'icon' => '🆕',

        'color' => '#3B82F6',

        'priority' => 20,

        'active' => true,
    ],

    [
        'id' => 'featured',

        'label' => 'Featured Product',

        'description' => 'Highlighted product recommended by the company.',

        'icon' => '⭐',

        'color' => '#F59E0B',

        'priority' => 30,

        'active' => true,
    ],

    [
        'id' => 'on_request',

        'label' => 'On Request',

        'description' => 'Available upon customer request or quotation.',

        'icon' => '📩',

        'color' => '#8B5CF6',

        'priority' => 40,

        'active' => true,
    ],

    [
        'id' => 'seasonal',

        'label' => 'Seasonal',

        'description' => 'Produced only during certain seasons or demand periods.',

        'icon' => '🍂',

        'color' => '#F97316',

        'priority' => 50,

        'active' => true,
    ],

    [
        'id' => 'limited',

        'label' => 'Limited Edition',

        'description' => 'Produced in limited quantities.',

        'icon' => '🎯',

        'color' => '#A855F7',

        'priority' => 60,

        'active' => true,
    ],

    [
        'id' => 'prototype',

        'label' => 'Prototype',

        'description' => 'Prototype or product under development.',

        'icon' => '🧪',

        'color' => '#06B6D4',

        'priority' => 70,

        'active' => true,
    ],

    [
        'id' => 'custom',

        'label' => 'Custom Made',

        'description' => 'Manufactured based on customer specifications.',

        'icon' => '🛠️',

        'color' => '#0F766E',

        'priority' => 80,

        'active' => true,
    ],

    [
        'id' => 'oem',

        'label' => 'OEM Product',

        'description' => 'Manufactured for OEM customers.',

        'icon' => '🏭',

        'color' => '#2563EB',

        'priority' => 90,

        'active' => true,
    ],

    [
        'id' => 'odm',

        'label' => 'ODM Product',

        'description' => 'Designed and manufactured as an ODM product.',

        'icon' => '💡',

        'color' => '#7C3AED',

        'priority' => 100,

        'active' => true,
    ],

    [
        'id' => 'eco',

        'label' => 'Eco Product',

        'description' => 'Environmentally friendly or sustainable product.',

        'icon' => '🌱',

        'color' => '#16A34A',

        'priority' => 110,

        'active' => true,
    ],

    [
        'id' => 'discontinued',

        'label' => 'Discontinued',

        'description' => 'No longer manufactured or supplied.',

        'icon' => '⛔',

        'color' => '#EF4444',

        'priority' => 999,

        'active' => true,
    ],

];