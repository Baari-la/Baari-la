<?php

declare(strict_types=1);

namespace App\Services\MasterData\Reference;

use Illuminate\Support\Collection;
use App\Services\MasterData\Identity\SchemaIdentityResolver;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Reference Detector
 * ==========================================================================
 *
 * Detects Master Data references from analyzed fields.
 *
 * Pipeline
 * --------
 *
 * Raw Field
 *      ↓
 * Normalizer
 *      ↓
 * Repository
 *      ↓
 * ReferenceDefinition
 *
 * This class contains NO alias definitions.
 *
 * ==========================================================================
 */
final class SchemaReferenceDetector
{
    /**
     * Constructor.
     */
    public function __construct(
        protected SchemaReferenceRepository $repository,
        protected SchemaReferenceNormalizer $normalizer,
        protected SchemaIdentityResolver $resolver
    ) {
    }

    /**
     * =========================================================================
     * Detect References
     * =========================================================================
     *
     * @param array<string,mixed> $analysis
     *
     * @return array<string,ReferenceDefinition>
     */
    public function detect(
        array $analysis
    ): array
    {
        $references = [];

        foreach (
            $analysis['fields'] ?? [] as $field
        ) {

            $definition = $this->detectField(
                $field
            );

            if ($definition === null) {
                continue;
            }

            $references[
                $field
            ] = $definition;

        }

        ksort($references);

        return $references;
    }

    /**
     * =========================================================================
     * Detect One Field
     * =========================================================================
     */
    protected function detectField(
        string $field
    ): ?ReferenceDefinition
    {
        $canonical = $this->normalizer
            ->normalize($field);

        $reference = $this->repository
            ->resolve($canonical);

        if ($reference === null) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Relation Type
        |--------------------------------------------------------------------------
        */

        $relation = $reference['relation'];

        if (
            $this->normalizer
                ->isGraphRelation($field)
        ) {

            $relation = 'graph_edge';

        } elseif (
            $this->normalizer
                ->isCollection($field)
        ) {

            $relation = 'many_to_many';

        }

       /*
|--------------------------------------------------------------------------
| Build Definition
|--------------------------------------------------------------------------
*/

$target = $this->targetId(
    $reference['target']
);

if ($target === '') {
    return null;
}

return new ReferenceDefinition(

    field: $field,

    target: $target,

    relation: $relation,

    collection: $this->normalizer
        ->isCollection($field),

    confidence: $reference['confidence'],

    reason: $this->buildReason(
        $field,
        $canonical,
        $target
    )

);
    }

    /**
     * =========================================================================
     * Build Reason
     * =========================================================================
     */
    protected function buildReason(
    string $field,
    string $canonical,
    string $target
): string
{
    if ($field !== $canonical) {

        return sprintf(

            'Normalized "%s" to "%s" (%s).',

            $field,
            $canonical,
            $target

        );
    }

    return sprintf(
        'Direct reference match (%s).',
        $target
    );
}

    /**
     * =========================================================================
     * Has Reference
     * =========================================================================
     */
    public function hasReference(
        string $field
    ): bool
    {
        return $this->detectField(
            $field
        ) !== null;
    }

    /**
     * =========================================================================
     * Detect Many
     * =========================================================================
     *
     * @param array<int,string> $fields
     *
     * @return Collection<int,ReferenceDefinition>
     */
    public function detectMany(
        array $fields
    ): Collection
    {
        $definitions = [];

        foreach ($fields as $field) {

            $definition = $this->detectField(
                $field
            );

            if ($definition !== null) {

                $definitions[] = $definition;

            }

        }

        return collect(
            $definitions
        );
    }

    /**
     * =========================================================================
     * Count References
     * =========================================================================
     */
    public function count(
        array $analysis
    ): int
    {
        return count(
            $this->detect($analysis)
        );
    }

  /**
 * =========================================================================
 * Target ID
 * =========================================================================
 */
protected function targetId(
    string $path
): string
{
    try {

        return $this->resolver
            ->resolve($path)
            ->id();

    } catch (\Throwable) {

        /*
        |--------------------------------------------------------------------------
        | Transitional Compatibility
        |--------------------------------------------------------------------------
        |
        | Allows legacy references to continue working while
        | Master Data Engine V2 is being migrated.
        |
        */

        return $path;
    }
}
}