<?php

declare(strict_types=1);

namespace App\Services\MasterData\Reference;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Reference Repository
 * ==========================================================================
 *
 * Central repository for all known Master Data references.
 *
 * Responsibilities
 * ----------------
 * - Store canonical reference definitions
 * - Resolve aliases
 * - Expose reference metadata
 *
 * Used by:
 *
 * - SchemaReferenceDetector
 * - KnowledgeGraphBuilder
 * - MasterDataValidator
 * - Executive AI
 *
 * ==========================================================================
 */
final class SchemaReferenceRepository
{
    /**
     * =========================================================================
     * Reference Definitions
     * =========================================================================
     *
     * target
     * relation
     * collection
     * confidence
     *
     * @var array<string,array<string,mixed>>
     */
    protected array $references = [

        /*
        |--------------------------------------------------------------------------
        | Business
        |--------------------------------------------------------------------------
        */

        'business_roles' => [

            'target'      => 'Business/business_roles.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'buyer_segments' => [

            'target'      => 'Business/buyer_segments.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'supplier_segments' => [

            'target'      => 'Business/supplier_segments.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'business_ecosystems' => [

            'target'      => 'Business/business_ecosystems.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        'products' => [

            'target'      => 'Products/product_categories.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'product_categories' => [

            'target'      => 'Products/product_categories.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'product_applications' => [

            'target'      => 'Products/product_applications.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'industry_segments' => [

            'target'      => 'Products/industry_segments.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'machinery_categories' => [

            'target'      => 'Products/machinery_categories.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        /*
        |--------------------------------------------------------------------------
        | Certification
        |--------------------------------------------------------------------------
        */

        'certifications' => [

            'target'      => 'Certification/certifications.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'certification_categories' => [

            'target'      => 'Certification/certification_categories.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'certification_markets' => [

            'target'      => 'Certification/certification_markets.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

        'certification_scopes' => [

            'target'      => 'Certification/certification_scopes.php',

            'relation'    => 'belongs_to',

            'collection'  => false,

            'confidence'  => 100,

        ],

    ];

    /**
     * =========================================================================
     * Alias Map
     * =========================================================================
     *
     * Maps semantic field names into canonical references.
     *
     * @var array<string,string>
     */
    protected array $aliases = [

        /*
        |--------------------------------------------------------------------------
        | Business
        |--------------------------------------------------------------------------
        */

        'business_role' => 'business_roles',
        'role' => 'business_roles',
        'roles' => 'business_roles',

        'buyer_segment' => 'buyer_segments',
        'supplier_segment' => 'supplier_segments',

        'business_ecosystem' => 'business_ecosystems',
        'ecosystem' => 'business_ecosystems',

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        'product' => 'products',

        'product_category' => 'product_categories',
        'category' => 'product_categories',

        'product_application' => 'product_applications',
        'application' => 'product_applications',

        'industry' => 'industry_segments',
        'industry_segment' => 'industry_segments',

        'machine_category' => 'machinery_categories',
        'machinery_category' => 'machinery_categories',

        /*
        |--------------------------------------------------------------------------
        | Certifications
        |--------------------------------------------------------------------------
        */

        'certification' => 'certifications',

        'certification_category' => 'certification_categories',

        'market' => 'certification_markets',

        'scope' => 'certification_scopes',

    ];

    /**
     * =========================================================================
     * Resolve
     * =========================================================================
     *
     * @return array<string,mixed>|null
     */
    public function resolve(
        string $key
    ): ?array
    {
        $canonical = $this->aliases[$key] ?? $key;

        return $this->references[$canonical] ?? null;
    }

    /**
     * =========================================================================
     * Has
     * =========================================================================
     */
    public function has(
        string $key
    ): bool
    {
        return $this->resolve($key) !== null;
    }

    /**
     * =========================================================================
     * References
     * =========================================================================
     *
     * @return array<string,array<string,mixed>>
     */
    public function references(): array
    {
        return $this->references;
    }

    /**
     * =========================================================================
     * Aliases
     * =========================================================================
     *
     * @return array<string,string>
     */
    public function aliases(): array
    {
        return $this->aliases;
    }

    /**
     * =========================================================================
     * Collection
     * =========================================================================
     */
    public function collection(): Collection
    {
        return collect(
            $this->references
        );
    }

    /**
     * =========================================================================
     * Count
     * =========================================================================
     */
    public function count(): int
    {
        return count(
            $this->references
        );
    }
}