<?php

declare(strict_types=1);

namespace App\Services\MasterData;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Master Data Reference Resolver
 * ==========================================================================
 *
 * Resolves master data references into canonical IDs.
 *
 * Used by:
 *
 * • Master Data Validator
 * • Knowledge Graph
 * • Executive AI
 * • Company Intelligence
 * • Recommendation Engine
 * • Semantic Search
 *
 * ==========================================================================
 */
class MasterDataReferenceResolver
{
    /**
     * =========================================================================
     * Canonicalizer
     * =========================================================================
     */
    protected MasterDataCanonicalizer $canonicalizer;

    /**
     * =========================================================================
     * Reference Index
     * =========================================================================
     *
     * @var array<string,array<string,bool>>
     */
    protected array $referenceIndex = [];

    /**
     * =========================================================================
     * Base Path
     * =========================================================================
     */
    protected string $basePath;

    /**
     * =========================================================================
     * Constructor
     * =========================================================================
     */
    public function __construct(
        ?MasterDataCanonicalizer $canonicalizer = null
    ) {
        $this->canonicalizer = $canonicalizer
            ?? new MasterDataCanonicalizer();

        $this->basePath = config_path('masterdata');
    }

    /**
     * =========================================================================
     * Resolve
     * =========================================================================
     *
     * Resolves a reference inside a master data file.
     *
     * Example:
     *
     * resolve(
     *      'Certification/certifications.php',
     *      'ISO9001'
     * );
     *
     * -> iso_9001
     *
     * Returns NULL if no reference exists.
     */
    public function resolve(
        string $file,
        string $value
    ): ?string
    {
        $value = $this->canonicalizer
            ->canonicalize($value);

        $index = $this->referenceIndex($file);

        return isset($index[$value])
            ? $value
            : null;
    }

    /**
 * =========================================================================
 * Reference Index
 * =========================================================================
 *
 * Returns cached reference index.
 *
 * @return array<string,bool>
 */
protected function referenceIndex(
    string $file
): array
{
    if (! isset($this->referenceIndex[$file])) {

        $this->referenceIndex[$file]
            = $this->loadReferenceIndex($file);

    }

    return $this->referenceIndex[$file];
}

/**
 * =========================================================================
 * Load Reference Index
 * =========================================================================
 *
 * Loads all canonical IDs from a master data file.
 *
 * @return array<string,bool>
 */
protected function loadReferenceIndex(
    string $file
): array
{
    $path = $this->basePath
        . DIRECTORY_SEPARATOR
        . $file;

    if (! File::exists($path)) {

        return [];

    }

    $records = require $path;

    if (! is_array($records)) {

        return [];

    }

    $index = [];

    foreach ($records as $record) {

        if (! is_array($record)) {
            continue;
        }

        $id = $record['id'] ?? null;

        if (! is_string($id) || $id === '') {
            continue;
        }

        $id = $this->canonicalizer
            ->canonicalize($id);

        $index[$id] = true;
    }

    ksort($index);

    return $index;
}

/**
 * =========================================================================
 * All IDs
 * =========================================================================
 *
 * Returns all available IDs for a master data file.
 *
 * @return array<int,string>
 */
public function allIds(
    string $file
): array
{
    return array_keys(

        $this->referenceIndex($file)

    );
}
/**
 * =========================================================================
 * Suggest
 * =========================================================================
 *
 * Suggests the nearest valid reference.
 *
 * Example:
 *
 * iso9001
 *      -> iso_9001
 *
 * fashionbrand
 *      -> fashion_brand
 *
 * Returns NULL if no reasonable suggestion exists.
 */
public function suggest(
    string $file,
    string $value
): ?string
{
    $value = $this->canonicalizer
        ->canonicalize($value);

    $ids = $this->allIds($file);

    if (empty($ids)) {
        return null;
    }

    return $this->bestMatch(
        $value,
        $ids
    );
}

/**
 * =========================================================================
 * Best Match
 * =========================================================================
 *
 * Finds the closest matching ID.
 *
 * @param array<int,string> $candidates
 */
protected function bestMatch(
    string $value,
    array $candidates
): ?string
{
    $best = null;

    $highestScore = 0.0;

    foreach ($candidates as $candidate) {

        $score = $this->similarity(
            $value,
            $candidate
        );

        if ($score > $highestScore) {

            $highestScore = $score;

            $best = $candidate;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Minimum confidence
    |--------------------------------------------------------------------------
    |
    | Ignore weak matches.
    |
    */

    if ($highestScore < 75.0) {
        return null;
    }

    return $best;
}

/**
 * =========================================================================
 * Similarity
 * =========================================================================
 *
 * Calculates similarity percentage.
 *
 * Returns:
 *
 * 100 = identical
 *
 * 90+ = almost identical
 *
 * 80+ = typo
 *
 * 70+ = possible suggestion
 */
protected function similarity(
    string $left,
    string $right
): float
{
    similar_text(
        $left,
        $right,
        $percent
    );

    return round(
        $percent,
        2
    );
}
    }