<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX MASTER DATA ALIASES
|--------------------------------------------------------------------------
|
| Source of truth for every normalized Master Data alias.
|
| This file is manually maintained.
|
| Used by:
|
| - Schema Generator
| - Reference Resolver
| - Knowledge Graph Builder
| - Recommendation Engine
| - AI Engine
|
| DO NOT STORE BUSINESS LOGIC HERE.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    'buyer_segment'           => 'Business/buyer_segments.php',
    'buyer_segments'          => 'Business/buyer_segments.php',

    'company'                 => 'Business/companies.php',
    'companies'               => 'Business/companies.php',

    'supplier'                => 'Business/suppliers.php',
    'suppliers'               => 'Business/suppliers.php',

    'customer'                => 'Business/customers.php',
    'customers'               => 'Business/customers.php',

   
//  Products


//  Master Data Engine V2 uses Product Categories as the canonical
//  product taxonomy until Product Intelligence schemas are introduced.



'product'                 => 'Products/product_categories.php',
'products'                => 'Products/product_categories.php',

'product_category'        => 'Products/product_categories.php',
'product_categories'      => 'Products/product_categories.php',

'fiber'                   => 'Products/product_categories.php',
'fibers'                  => 'Products/product_categories.php',

    'yarn'                    => 'Products/yarns.php',
    'yarns'                   => 'Products/yarns.php',

    'fabric'                  => 'Products/fabrics.php',
    'fabrics'                 => 'Products/fabrics.php',

    /*
    |--------------------------------------------------------------------------
    | Certification
    |--------------------------------------------------------------------------
    */

    'certification'           => 'Certification/certifications.php',
    'certifications'          => 'Certification/certifications.php',

    /*
    |--------------------------------------------------------------------------
    | Machinery
    |--------------------------------------------------------------------------
    */

    'machine'                 => 'Machinery/machines.php',
    'machines'                => 'Machinery/machines.php',

    'machine_category'        => 'Machinery/machine_categories.php',
    'machine_categories'      => 'Machinery/machine_categories.php',

    /*
    |--------------------------------------------------------------------------
    | Country
    |--------------------------------------------------------------------------
    */

    'country'                 => 'Geography/countries.php',
    'countries'               => 'Geography/countries.php',

    'region'                  => 'Geography/regions.php',
    'regions'                 => 'Geography/regions.php',

    /*
    |--------------------------------------------------------------------------
    | Sustainability
    |--------------------------------------------------------------------------
    */

    'sustainability'          => 'Sustainability/sustainability_programs.php',

    /*
    |--------------------------------------------------------------------------
    | Technology
    |--------------------------------------------------------------------------
    */

    'technology'              => 'Technology/technologies.php',

    /*
    |--------------------------------------------------------------------------
    | Logistics
    |--------------------------------------------------------------------------
    */

    'port'                    => 'Logistics/ports.php',
    'ports'                   => 'Logistics/ports.php',

    /*
    |--------------------------------------------------------------------------
    | Trade
    |--------------------------------------------------------------------------
    */

    'hs_code'                 => 'Trade/hs_codes.php',
    'hs_codes'                => 'Trade/hs_codes.php',

];