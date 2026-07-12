<?php

declare(strict_types=1);

namespace App\Services\MasterData;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Master Data Canonicalizer
 * ==========================================================================
 *
 * Converts arbitrary master data identifiers into
 * a canonical Digestex ID.
 *
 * Examples:
 *
 * ISO9001
 *      -> iso_9001
 *
 * ISO 9001
 *      -> iso_9001
 *
 * Oeko Tex
 *      -> oeko_tex
 *
 * Fashion Brand
 *      -> fashion_brand
 *
 * Fashion-Brand
 *      -> fashion_brand
 *
 * This service is intentionally framework independent
 * and reused by:
 *
 * - Master Data Validator
 * - Knowledge Graph
 * - Executive AI
 * - Company Intelligence
 * - Recommendation Engine
 * - Semantic Search
 *
 * ==========================================================================
 */

class MasterDataCanonicalizer
{
    /**
     * =========================================================================
     * Known aliases
     * =========================================================================
     *
     * Maps common industry spellings
     * into Digestex canonical IDs.
     *
     * @var array<string,string>
     */
    protected array $aliases = [];

    /**
     * =========================================================================
     * Constructor
     * =========================================================================
     */
    public function __construct()
    {
        $this->aliases = $this->defaultAliases();
    }

    /**
     * =========================================================================
     * Canonicalize
     * =========================================================================
     *
     * Converts any input into a canonical Digestex ID.
     *
     * Example:
     *
     * ISO9001
     *      -> iso_9001
     *
     * Oeko Tex
     *      -> oeko_tex
     *
     * Fashion Brand
     *      -> fashion_brand
     */
    public function canonicalize(
        ?string $value
    ): string
    {
        if ($value === null) {
            return '';
        }

        $value = trim($value);

        if ($value === '') {
            return '';
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize
        |--------------------------------------------------------------------------
        */

        $value = $this->normalize($value);

        /*
        |--------------------------------------------------------------------------
        | Known aliases
        |--------------------------------------------------------------------------
        */

        $value = $this->fixKnownAliases($value);

        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        return $this->slug($value);
    }

    /**
     * =========================================================================
     * Default Aliases
     * =========================================================================
     *
     * Will be expanded gradually.
     *
     * @return array<string,string>
     */
    
/**
 * =========================================================================
 * Normalize
 * =========================================================================
 *
 * Normalizes user input before canonicalization.
 *
 * Example:
 *
 * ISO 9001
 *      -> iso 9001
 *
 * OEKO-TEX
 *      -> oeko tex
 *
 * Fashion   Brand
 *      -> fashion brand
 */
protected function normalize(
    string $value
): string
{
    /*
    |--------------------------------------------------------------------------
    | Trim
    |--------------------------------------------------------------------------
    */

    $value = trim($value);

    /*
    |--------------------------------------------------------------------------
    | Lowercase
    |--------------------------------------------------------------------------
    */

    $value = mb_strtolower($value);

    /*
    |--------------------------------------------------------------------------
    | Remove punctuation noise
    |--------------------------------------------------------------------------
    */

    $value = $this->removeNoise($value);

    /*
    |--------------------------------------------------------------------------
    | Collapse multiple spaces
    |--------------------------------------------------------------------------
    */

    $value = preg_replace('/\s+/', ' ', $value);

    return trim($value);
}

/**
 * =========================================================================
 * Remove Noise
 * =========================================================================
 *
 * Removes punctuation while preserving word boundaries.
 */
protected function removeNoise(
    string $value
): string
{
    /*
    |--------------------------------------------------------------------------
    | Replace separators with spaces
    |--------------------------------------------------------------------------
    */

    $value = str_replace(
        [
            '-',
            '/',
            '\\',
            '.',
            ',',
            ';',
            ':',
            '|',
            '(',
            ')',
            '[',
            ']',
            '{',
            '}',
            '+',
        ],
        ' ',
        $value
    );

    /*
    |--------------------------------------------------------------------------
    | Remove unsupported characters
    |--------------------------------------------------------------------------
    */

    return preg_replace(
        '/[^a-z0-9\s_]/u',
        '',
        $value
    );
}

/**
 * =========================================================================
 * Slug
 * =========================================================================
 *
 * Converts normalized text into Digestex canonical ID.
 *
 * Example:
 *
 * fashion brand
 *      -> fashion_brand
 *
 * digital printing
 *      -> digital_printing
 */
protected function slug(
    string $value
): string
{
    /*
    |--------------------------------------------------------------------------
    | Replace spaces
    |--------------------------------------------------------------------------
    */

    $value = preg_replace(
        '/\s+/',
        '_',
        trim($value)
    );

    /*
    |--------------------------------------------------------------------------
    | Collapse multiple underscores
    |--------------------------------------------------------------------------
    */

    $value = preg_replace(
        '/_+/',
        '_',
        $value
    );

    return trim($value, '_');
}
/**
 * =========================================================================
 * Default Aliases
 * =========================================================================
 *
 * Maps common industry terms into canonical Digestex IDs.
 *
 * @return array<string,string>
 */
protected function defaultAliases(): array
{
    return [

        /*
        |--------------------------------------------------------------------------
        | ISO Standards
        |--------------------------------------------------------------------------
        */

        'iso9001' => 'iso_9001',
        'iso 9001' => 'iso_9001',

        'iso14001' => 'iso_14001',
        'iso 14001' => 'iso_14001',

        'iso45001' => 'iso_45001',
        'iso 45001' => 'iso_45001',

        /*
        |--------------------------------------------------------------------------
        | Textile Certifications
        |--------------------------------------------------------------------------
        */

        'oekotex' => 'oeko_tex',
        'oeko tex' => 'oeko_tex',
        'oeko-tex' => 'oeko_tex',

        'gots' => 'gots',

        'grs' => 'grs',

        'rcs' => 'rcs',

        'bluesign' => 'bluesign',

        'zdhc' => 'zdhc',

        'wrap' => 'wrap',

        'amfori bsci' => 'amfori_bsci',
        'bsci' => 'amfori_bsci',

        'sedex' => 'sedex',

        'higg' => 'higg',

        /*
        |--------------------------------------------------------------------------
        | Business
        |--------------------------------------------------------------------------
        */

        'buying office' => 'buying_office',

        'fashion brand' => 'fashion_brand',

        'private label' => 'private_label_brand',

        'private label brand' => 'private_label_brand',

        'sportswear brand' => 'sportswear_brand',

        /*
        |--------------------------------------------------------------------------
        | Manufacturing
        |--------------------------------------------------------------------------
        */

        'cmt' => 'cmt_factory',

        'oem' => 'oem_manufacturer',

        'odm' => 'odm_manufacturer',

        /*
        |--------------------------------------------------------------------------
        | Digital Technologies
        |--------------------------------------------------------------------------
        */

        'erp' => 'erp',

        'plm' => 'plm',

        'mes' => 'mes',

        'cad' => 'cad',

        'cam' => 'cam',

        'ai' => 'artificial_intelligence',

        'iot' => 'internet_of_things',

    ];
}

/**
 * =========================================================================
 * Fix Known Aliases
 * =========================================================================
 *
 * Converts well-known business aliases into
 * Digestex canonical IDs.
 */
protected function fixKnownAliases(
    string $value
): string
{
    return $this->aliases[$value] ?? $value;
}

/**
 * =========================================================================
 * Tokenize
 * =========================================================================
 *
 * Splits canonical text into tokens.
 *
 * Example:
 *
 * fashion_brand
 *      -> ['fashion', 'brand']
 *
 * iso_9001
 *      -> ['iso', '9001']
 *
 * @return array<int,string>
 */
protected function tokenize(
    string $value
): array
{
    $value = trim($value);

    if ($value === '') {
        return [];
    }

    return array_values(

        array_filter(

            explode('_', $value),

            static fn (string $token) => $token !== ''

        )

    );
}

/**
 * =========================================================================
 * Is Canonical
 * =========================================================================
 *
 * Returns true if the value already matches
 * Digestex canonical ID format.
 */
public function isCanonical(
    string $value
): bool
{
    return $value === $this->canonicalize($value);
}

/**
 * =========================================================================
 * Canonical Equals
 * =========================================================================
 *
 * Compares two values after canonicalization.
 */
public function equals(
    string $left,
    string $right
): bool
{
    return $this->canonicalize($left)
        === $this->canonicalize($right);
}

/**
 * =========================================================================
 * Tokens
 * =========================================================================
 *
 * Public helper for Knowledge Graph,
 * Search Engine and AI.
 *
 * @return array<int,string>
 */
public function tokens(
    string $value
): array
{
    return $this->tokenize(

        $this->canonicalize($value)

    );
}
    }