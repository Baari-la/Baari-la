<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Services\Company\Classification\BusinessRoleResolver;
use App\Models\Company;
use Illuminate\Support\Str;

class BusinessRoleService
{
    /**
     * ==========================================================================
     * DIGESTEX BUSINESS ROLE SERVICE
     * ==========================================================================
     *
     * Determines the primary business role of a company
     * within the global textile value chain.
     *
     * Version 1
     * ---------
     * Legacy Directory
     *
     * Future versions:
     *
     * - Company Self Update
     * - Multiple Business Roles
     * - Executive AI Classification
     *
     * Returns one of the keys defined in:
     *
     * config/textile_ecosystem.php
     *
     * Examples:
     *
     * fiber
     * spinning
     * knitting
     * weaving
     * dyeing
     * garment
     * home_textile
     * technical_textile
     * brand
     * buying_office
     *
     */

    public function __construct(
        protected BusinessRoleResolver $canonicalResolver,
    ) {
    }

    public function resolve(
        Company $company
    ): string {

        $text = Str::lower(
            implode(' ', [

                $company->category,

                $company->sektor,

                $company->produk,

                $company->nama_perusahaan,

            ])
        );

        return match (true) {

            /*
            |--------------------------------------------------------------------------
            | Fiber
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'fiber'),
            str_contains($text, 'cotton'),
            str_contains($text, 'polyester staple')
                => 'fiber',

            /*
            |--------------------------------------------------------------------------
            | Spinning
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'spinning'),
            str_contains($text, 'yarn')
                => 'spinning',

            /*
            |--------------------------------------------------------------------------
            | Knitting
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'knitting'),
            str_contains($text, 'knit'),
            str_contains($text, 'rajut')
                => 'knitting',

            /*
            |--------------------------------------------------------------------------
            | Weaving
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'weaving'),
            str_contains($text, 'woven'),
            str_contains($text, 'tenun')
                => 'weaving',

            /*
            |--------------------------------------------------------------------------
            | Dyeing
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'dyeing'),
            str_contains($text, 'finishing'),
            str_contains($text, 'printing')
                => 'dyeing',

            /*
            |--------------------------------------------------------------------------
            | Garment
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'garment'),
            str_contains($text, 'apparel'),
            str_contains($text, 'fashion'),
            str_contains($text, 'underwear'),
            str_contains($text, 'shirt'),
            str_contains($text, 'pants')
                => 'garment',

            /*
            |--------------------------------------------------------------------------
            | Home Textile
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'home textile'),
            str_contains($text, 'bed sheet'),
            str_contains($text, 'towel'),
            str_contains($text, 'curtain')
                => 'home_textile',

            /*
            |--------------------------------------------------------------------------
            | Technical Textile
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'technical textile'),
            str_contains($text, 'nonwoven'),
            str_contains($text, 'geotextile'),
            str_contains($text, 'medical textile')
                => 'technical_textile',

            /*
            |--------------------------------------------------------------------------
            | Brand
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'brand')
                => 'brand',

            /*
            |--------------------------------------------------------------------------
            | Buying Office
            |--------------------------------------------------------------------------
            */

            str_contains($text, 'buying office'),
            str_contains($text, 'sourcing')
                => 'buying_office',

            default => 'garment',

        };

    }
/**
 * ==========================================================================
 * BUSINESS ROLE CLASSIFICATION V2
 * ==========================================================================
 *
 * Produces structured business-role intelligence without changing the
 * legacy ecosystem role returned by resolve().
 */
public function classify(Company $company): array
{
    /*
    |--------------------------------------------------------------------------
    | Legacy Ecosystem Role
    |--------------------------------------------------------------------------
    */

    $ecosystemRole = $this->resolve($company);

    $scores = [];
    $evidence = [];
    $sources = [];

    /*
    |--------------------------------------------------------------------------
    | 1. Explicit Company Role
    |--------------------------------------------------------------------------
    |
    | Explicit structured company role remains authoritative, but must first
    | pass through the canonical resolver.
    |
    */

    if (filled($company->company_role)) {

        $explicitRole = trim((string) $company->company_role);

        $canonicalRole =
            $this->canonicalResolver->resolve($explicitRole);

        if ($canonicalRole !== null) {

            $this->addRoleScore(
                $scores,
                $canonicalRole,
                100
            );

            $evidence[] =
                'company_role:' . $explicitRole;

            $sources[] =
                'explicit_company_role';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Structured Products
    |--------------------------------------------------------------------------
    |
    | Primary products receive stronger weight than secondary products.
    |
    */

    $products = $company->relationLoaded('products')
        ? $company->products
        : collect();

    foreach ($products as $product) {

        if (! filled($product->product_name)) {
            continue;
        }

        $text =
            strtolower(
                trim((string) $product->product_name)
            );

        $isPrimary =
            (bool) ($product->is_primary ?? false);

        $weight =
            $isPrimary
                ? 50
                : 30;

        $roles =
            $this->inferRolesFromText($text);

        foreach ($roles as $role) {

            $this->addRoleScore(
                $scores,
                $role,
                $weight
            );
        }

        $evidence[] =
            ($isPrimary ? 'primary_product:' : 'product:')
            . $text;

        $sources[] =
            $isPrimary
                ? 'primary_structured_product'
                : 'structured_product';
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Sector
    |--------------------------------------------------------------------------
    */

    if (filled($company->sektor)) {

        $sector =
            strtolower(
                trim((string) $company->sektor)
            );

        foreach (
            $this->inferRolesFromText($sector)
            as $role
        ) {

            $this->addRoleScore(
                $scores,
                $role,
                25
            );
        }

        $evidence[] =
            'sector:' . trim((string) $company->sektor);

        $sources[] =
            'sector_inference';
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Legacy Product
    |--------------------------------------------------------------------------
    */

    if (filled($company->produk)) {

        $legacyProduct =
            strtolower(
                trim((string) $company->produk)
            );

        foreach (
            $this->inferRolesFromText($legacyProduct)
            as $role
        ) {

            $this->addRoleScore(
                $scores,
                $role,
                15
            );
        }

        $evidence[] =
            'legacy_product:' . trim((string) $company->produk);

        $sources[] =
            'legacy_product_inference';
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Ecosystem Fallback
    |--------------------------------------------------------------------------
    |
    | Gives the legacy ecosystem classification a small supporting score.
    | It must never overpower strong structured evidence.
    |
    */

    $fallbackRole =
        $this->canonicalFromEcosystemRole(
            $ecosystemRole
        );

    if ($fallbackRole !== null) {

        $this->addRoleScore(
            $scores,
            $fallbackRole,
            10
        );

        $sources[] =
            'ecosystem_fallback';
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Canonical Roles
    |--------------------------------------------------------------------------
    */

    $scores = collect($scores)
        ->filter(
            fn ($score, $role) =>
                $this->canonicalResolver->isCanonical($role)
        )
        ->sortDesc()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | Canonical Primary Role
    |--------------------------------------------------------------------------
    */

    $canonicalRole =
        array_key_first($scores)
        ?? $fallbackRole;

    /*
    |--------------------------------------------------------------------------
    | Specific Roles
    |--------------------------------------------------------------------------
    |
    | Preserve meaningful secondary roles, but prevent weak incidental
    | evidence from polluting the business-role profile.
    |
    */

    $specificRoles = collect($scores)
        ->filter(
            fn ($score) => $score >= 25
        )
        ->keys()
        ->values()
        ->all();

    if (
        $canonicalRole !== null &&
        ! in_array(
            $canonicalRole,
            $specificRoles,
            true
        )
    ) {
        array_unshift(
            $specificRoles,
            $canonicalRole
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Confidence
    |--------------------------------------------------------------------------
    */

    $topScore =
        $canonicalRole !== null
            ? ($scores[$canonicalRole] ?? 0)
            : 0;

    $confidence =
        $this->confidenceFromScore(
            $topScore
        );

    /*
    |--------------------------------------------------------------------------
    | Source
    |--------------------------------------------------------------------------
    */

    $source = match (true) {

        in_array(
            'explicit_company_role',
            $sources,
            true
        ) =>
            'explicit_company_role',

        in_array(
            'primary_structured_product',
            $sources,
            true
        ) =>
            'structured_product_inference',

        in_array(
            'structured_product',
            $sources,
            true
        ) =>
            'structured_product_inference',

        in_array(
            'sector_inference',
            $sources,
            true
        ) =>
            'sector_inference',

        in_array(
            'legacy_product_inference',
            $sources,
            true
        ) =>
            'legacy_inference',

        default =>
            'ecosystem_fallback',
    };

    return [

        'ecosystem_role' =>
            $ecosystemRole,

        'canonical_role' =>
            $canonicalRole,

        'specific_roles' =>
            $specificRoles,

        'confidence' =>
            $confidence,

        'source' =>
            $source,

        'evidence' =>
            array_values(
                array_unique($evidence)
            ),

        'role_scores' =>
            $scores,
    ];
}

/**
 * ==========================================================================
 * INFER CANONICAL ROLES FROM TEXT
 * ==========================================================================
 */
protected function inferRolesFromText(
    string $text
): array {

    $text =
        Str::lower(
            trim($text)
        );

    if ($text === '') {
        return [];
    }

    $roles = [];

    /*
|--------------------------------------------------------------------------
| Synthetic Polymer
|--------------------------------------------------------------------------
|
| Polymer roles require explicit polymer / chips / resin evidence.
| Yarn or fiber terminology alone must not imply polymer production.
|
*/

if (
    str_contains($text, 'pet chip') ||
    str_contains($text, 'pet chips') ||
    str_contains($text, 'polyester chip') ||
    str_contains($text, 'polyester chips') ||
    str_contains($text, 'polyester polymer')
) {
    $roles[] =
        'polyester_polymer_producer';
}

/*
|--------------------------------------------------------------------------
| Nylon / Polyamide Polymer
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'nylon polymer') ||
    str_contains($text, 'nylon chip') ||
    str_contains($text, 'nylon chips') ||
    str_contains($text, 'polyamide polymer') ||
    str_contains($text, 'polyamide chip') ||
    str_contains($text, 'polyamide chips') ||
    str_contains($text, 'polyamid chip') ||
    str_contains($text, 'polyamid chips') ||
    str_contains($text, 'chip polyamide') ||
    str_contains($text, 'chips polyamide') ||
    str_contains($text, 'chip polyamid') ||
    str_contains($text, 'chips polyamid')
) {
    $roles[] = 'nylon_polymer_producer';
}

if (
    str_contains($text, 'acrylic polymer')
) {
    $roles[] =
        'acrylic_polymer_producer';
}

if (
    str_contains($text, 'synthetic polymer')
) {
    $roles[] =
        'synthetic_polymer_producer';
}
    
   /*
|--------------------------------------------------------------------------
| Fiber
|--------------------------------------------------------------------------
|
| Prefer the most specific canonical role available.
| Generic fiber_manufacturer is used only when no more specific
| fiber classification can be inferred from the same evidence.
|
*/

$fiberSpecificRoleFound = false;

/*
|--------------------------------------------------------------------------
| Viscose Producer
|--------------------------------------------------------------------------
|
| Viscose yarn / rayon yarn does not imply viscose fiber production.
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'viscose fiber') ||
    str_contains($text, 'viscose fibers') ||
    str_contains($text, 'viscose fibre') ||
    str_contains($text, 'viscose fibres') ||
    str_contains($text, 'fiber viscose') ||
    str_contains($text, 'fibre viscose') ||
    str_contains($text, 'viscose staple fiber') ||
    str_contains($text, 'viscose staple fibre') ||
    str_contains($text, 'viscose rayon staple fiber') ||
    str_contains($text, 'viscose rayon staple fibres') ||
    str_contains($text, 'rayon staple fiber') ||
    str_contains($text, 'rayon staple fibre')
) {
    $roles[] = 'viscose_producer';

    $fiberSpecificRoleFound = true;
}

/*
|--------------------------------------------------------------------------
| Staple Fiber
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'staple fiber') ||
    str_contains($text, 'staple fibre')
) {
    $roles[] =
        'staple_fiber_manufacturer';

    $fiberSpecificRoleFound = true;
}

/*
|--------------------------------------------------------------------------
| Synthetic / Polyester Fiber
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'polyester fiber') ||
    str_contains($text, 'polyester fibre')
) {
    $roles[] =
        'synthetic_fiber_manufacturer';

    $fiberSpecificRoleFound = true;
}

/*
|--------------------------------------------------------------------------
| Acrylic Fiber
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'acrylic fiber') ||
    str_contains($text, 'acrylic fibre') ||
    str_contains($text, 'acrylic fibers') ||
    str_contains($text, 'acrylic fibres')
) {
    $roles[] =
        'synthetic_fiber_manufacturer';

    $fiberSpecificRoleFound = true;
}

/*
|--------------------------------------------------------------------------
| Generic Fiber
|--------------------------------------------------------------------------
|
| Only use the generic parent when this evidence does not provide
| a more specific fiber classification.
|
*/

if (
    ! $fiberSpecificRoleFound &&
    (
        str_contains($text, 'fiber') ||
        str_contains($text, 'fibre')
    )
) {
    $roles[] =
        'fiber_manufacturer';
}

/*
|--------------------------------------------------------------------------
| Filament
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'filament yarn') ||
    str_contains($text, 'filament yarns') ||
    str_contains($text, 'multifilament') ||
    str_contains($text, 'monofilament')
) {
    $roles[] =
        'filament_fiber_manufacturer';
}

/*
|--------------------------------------------------------------------------
| Filament Yarn Types
|--------------------------------------------------------------------------
*/

if (
    preg_match('/\bpoy\b/i', $text)
) {
    $roles[] =
        'poy_producer';
}

if (
    preg_match('/\bfdy\b/i', $text)
) {
    $roles[] =
        'fdy_producer';
}

if (
    preg_match('/\bdty\b/i', $text)
) {
    $roles[] =
        'dty_producer';
}

if (
    preg_match('/\baty\b/i', $text)
) {
    $roles[] =
        'aty_producer';
}

    /*
    |--------------------------------------------------------------------------
    | Yarn / Spinning
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'spinning') ||
        str_contains($text, 'spun yarn')
    ) {
        $roles[] =
            'yarn_spinner';
    }

    if (
        str_contains($text, 'ring spinning')
    ) {
        $roles[] =
            'ring_spinning_mill';
    }

    if (
        str_contains($text, 'open end') ||
        str_contains($text, 'open-end')
    ) {
        $roles[] =
            'open_end_spinning_mill';
    }

    /*
|--------------------------------------------------------------------------
| Sewing Thread
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'sewing thread') ||
    str_contains($text, 'sewing threads')
) {
    $roles[] = 'sewing_thread_manufacturer';
}

/*
|--------------------------------------------------------------------------
| Yarn Twisting
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'twisting') ||
    str_contains($text, 'twist yarn') ||
    str_contains($text, 'twist yarns') ||
    str_contains($text, 'twisted yarn')
) {
    $roles[] = 'yarn_twisting';
}

/*
|--------------------------------------------------------------------------
| Texturizing
|--------------------------------------------------------------------------
|
| Detects yarn texturizing activities.
|
| Examples:
| - Texturized Yarn
| - Texturized Yarns
| - Textured Yarn
| - Texturizing
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'texturized yarn') ||
    str_contains($text, 'texturized yarns') ||
    str_contains($text, 'texturised yarn') ||
    str_contains($text, 'texturised yarns') ||
    str_contains($text, 'textured yarn') ||
    str_contains($text, 'textured yarns') ||
    str_contains($text, 'texturizing') ||
    str_contains($text, 'texturising')
) {
    $roles[] = 'texturizing_company';
}

    /*
    |--------------------------------------------------------------------------
    | Fabric
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'weaving') ||
        str_contains($text, 'woven fabric')
    ) {
        $roles[] =
            'weaving_mill';
    }

    if (
        str_contains($text, 'knitting') ||
        str_contains($text, 'knitted fabric')
    ) {
        $roles[] =
            'knitting_mill';
    }

    if (
        str_contains($text, 'warp knitting')
    ) {
        $roles[] =
            'warp_knitting_mill';
    }

    if (
        str_contains($text, 'circular knitting')
    ) {
        $roles[] =
            'circular_knitting_mill';
    }

    /*
    |--------------------------------------------------------------------------
    | Nonwoven
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'nonwoven') ||
        str_contains($text, 'non-woven')
    ) {
        $roles[] =
            'nonwoven_manufacturer';
    }

    /*
|--------------------------------------------------------------------------
| Technical Textile
|--------------------------------------------------------------------------
*/

if (
    str_contains($text, 'tire cord') ||
    str_contains($text, 'tyre cord') ||
    str_contains($text, 'technical textile') ||
    str_contains($text, 'geotextile')
) {
    $roles[] =
        'technical_textile_manufacturer';
}

    /*
    |--------------------------------------------------------------------------
    | Processing
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'dyeing') ||
        str_contains($text, 'finishing')
    ) {
        $roles[] =
            'dyeing_finishing_mill';
    }

    if (
        str_contains($text, 'digital printing')
    ) {
        $roles[] =
            'digital_printing_company';

    } elseif (
        str_contains($text, 'printing')
    ) {
        $roles[] =
            'printing_mill';
    }

    /*
    |--------------------------------------------------------------------------
    | Garment
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'garment') ||
        str_contains($text, 'apparel')
    ) {
        $roles[] =
            'garment_manufacturer';
    }

    /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

    return collect($roles)
        ->filter(
            fn ($role) =>
                $this->canonicalResolver->isCanonical($role)
        )
        ->unique()
        ->values()
        ->all();
}

/**
 * ==========================================================================
 * ADD ROLE SCORE
 * ==========================================================================
 */
protected function addRoleScore(
    array &$scores,
    string $role,
    int $weight
): void {

    if (
        ! $this->canonicalResolver->isCanonical($role)
    ) {
        return;
    }

    $scores[$role] =
        ($scores[$role] ?? 0)
        + $weight;
}

/**
 * ==========================================================================
 * CONFIDENCE FROM SCORE
 * ==========================================================================
 */
protected function confidenceFromScore(
    int $score
): float {

    return match (true) {

        $score >= 100 => 1.00,

        $score >= 80 => 0.95,

        $score >= 60 => 0.90,

        $score >= 40 => 0.80,

        $score >= 25 => 0.70,

        $score >= 10 => 0.60,

        default => 0.0,
    };
}

/**
 * Map legacy ecosystem roles to a safe canonical parent role.
 */
protected function canonicalFromEcosystemRole(
    string $ecosystemRole
): ?string {

    $role = match ($ecosystemRole) {

        'fiber' =>
            'fiber_manufacturer',

        'spinning' =>
            'yarn_spinner',

        'knitting' =>
            'knitting_mill',

        'weaving' =>
            'weaving_mill',

        'dyeing' =>
            'dyeing_finishing_mill',

        'garment' =>
            'garment_manufacturer',

        'home_textile' =>
            'home_textile_manufacturer',

        'technical_textile' =>
            'technical_textile_manufacturer',

        'brand' =>
            'brand_owner',

        'buying_office' =>
            'buying_office',

        default =>
            null,
    };

    return $role !== null &&
        $this->canonicalResolver->isCanonical($role)
            ? $role
            : null;
}
    
}