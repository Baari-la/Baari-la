<?php

declare(strict_types=1);

namespace App\Services\MasterData\Reference;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Reference Normalizer
 * ==========================================================================
 *
 * Converts field names into canonical Master Data reference keys.
 *
 * Responsibilities
 * ----------------
 * - Remove prefixes
 * - Remove suffixes
 * - Normalize singular/plural
 * - Apply semantic aliases
 *
 * This class DOES NOT detect references.
 * This class DOES NOT access repositories.
 *
 * ==========================================================================
 */
final class SchemaReferenceNormalizer
{
    /**
     * Semantic aliases.
     *
     * @var array<string,string>
     */
    protected array $semanticAliases = [

        /*
        |--------------------------------------------------------------------------
        | Business
        |--------------------------------------------------------------------------
        */

        'role' => 'business_roles',
        'roles' => 'business_roles',

        'buyer' => 'buyer_segments',
        'supplier' => 'supplier_segments',

        'ecosystem' => 'business_ecosystems',

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        'product' => 'products',

        'category' => 'product_categories',

        'application' => 'product_applications',

        'industry' => 'industry_segments',

        'machine' => 'machinery_categories',

        'machinery' => 'machinery_categories',

        /*
        |--------------------------------------------------------------------------
        | Certification
        |--------------------------------------------------------------------------
        */

        'certification' => 'certifications',

        'market' => 'certification_markets',

        'scope' => 'certification_scopes',

    ];

    /**
     * =========================================================================
     * Normalize
     * =========================================================================
     */
    public function normalize(
        string $field
    ): string
    {
        $field = strtolower(
            trim($field)
        );

        /*
        |--------------------------------------------------------------------------
        | Remove Common Prefixes
        |--------------------------------------------------------------------------
        */

        $field = preg_replace(

            '/^(common_|default_|preferred_|required_|available_|supported_|primary_|secondary_|related_|linked_|typical_)/',

            '',

            $field

        ) ?? $field;

        /*
        |--------------------------------------------------------------------------
        | Remove Collection Suffixes
        |--------------------------------------------------------------------------
        */

        $field = preg_replace(

            '/(_ids|_id)$/',

            '',

            $field

        ) ?? $field;

        /*
        |--------------------------------------------------------------------------
        | Remove Relation Prefixes
        |--------------------------------------------------------------------------
        */

        $field = preg_replace(

            '/^(upstream_|downstream_)/',

            '',

            $field

        ) ?? $field;

        /*
        |--------------------------------------------------------------------------
        | Direct Semantic Alias
        |--------------------------------------------------------------------------
        */

        if (isset($this->semanticAliases[$field])) {

            return $this->semanticAliases[$field];

        }

        /*
        |--------------------------------------------------------------------------
        | Partial Semantic Match
        |--------------------------------------------------------------------------
        */

        foreach (
            $this->semanticAliases as $keyword => $canonical
        ) {

            if (
                str_contains(
                    $field,
                    $keyword
                )
            ) {

                return $canonical;

            }

        }

        return $field;
    }

    /**
     * =========================================================================
     * Normalize Many
     * =========================================================================
     *
     * @param array<int,string> $fields
     *
     * @return array<int,string>
     */
    public function normalizeMany(
        array $fields
    ): array
    {
        $normalized = [];

        foreach ($fields as $field) {

            $normalized[] = $this->normalize(
                $field
            );

        }

        $normalized = array_unique(
            $normalized
        );

        sort($normalized);

        return array_values(
            $normalized
        );
    }

    /**
     * =========================================================================
     * Is Collection
     * =========================================================================
     */
    public function isCollection(
        string $field
    ): bool
    {
        return preg_match(

            '/(_ids|_roles|_segments|_products|_markets|_certifications)$/',

            strtolower($field)

        ) === 1;
    }

    /**
     * =========================================================================
     * Is Graph Relation
     * =========================================================================
     */
    public function isGraphRelation(
        string $field
    ): bool
    {
        return in_array(

            strtolower($field),

            [

                'upstream',

                'downstream',

                'upstream_roles',

                'downstream_roles',

            ],

            true

        );
    }

    /**
     * =========================================================================
     * Is Implicit Reference
     * =========================================================================
     */
    public function isImplicitReference(
        string $field
    ): bool
    {
        return $this->normalize($field)
            !== strtolower($field);
    }
}