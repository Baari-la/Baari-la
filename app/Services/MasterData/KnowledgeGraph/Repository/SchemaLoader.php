<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Repository;

use App\Services\MasterData\Generator\SchemaDefinition;
use App\Services\MasterData\Identity\SchemaIdentityResolver;
use RuntimeException;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Loader
 * ==========================================================================
 *
 * Loads generated Master Data schemas.
 *
 * Responsibilities
 * ----------------
 * - Load schemas.generated.php
 * - Validate generated schema file
 * - Hydrate SchemaDefinition objects
 *
 * This class DOES NOT:
 *
 * - Build schemas
 * - Modify schemas
 * - Build Knowledge Graph
 * - Cache schemas
 *
 * ==========================================================================
 */
final class SchemaLoader
{
    /**
     * Generated schema file.
     */
    protected string $file;
protected SchemaIdentityResolver $identityResolver;
    /**
     * Constructor.
     */
    public function __construct(
    ?string $file = null,
    ?SchemaIdentityResolver $identityResolver = null,
) {
    $this->file = $file
        ?? config_path('masterdata/schemas.generated.php');

    $this->identityResolver = $identityResolver
        ?? new SchemaIdentityResolver();
}

    /**
     * =========================================================================
     * Load
     * =========================================================================
     *
     * @return array<string,SchemaDefinition>
     */
    public function load(): array
    {
        if (! file_exists($this->file)) {

            throw new RuntimeException(

                sprintf(
                    'Generated schema file not found: %s',
                    $this->file
                )

            );

        }

        /** @var mixed $schemas */
        $schemas = require $this->file;

        if (! is_array($schemas)) {

            throw new RuntimeException(
                'Generated schema file must return an array.'
            );

        }

        $definitions = [];

        foreach ($schemas as $key => $definition) {

    if (! is_string($key)) {

        throw new RuntimeException(
            'Schema key must be a string.'
        );

    }

    if ($definition instanceof SchemaDefinition) {

        if (! $definition->hasIdentity()) {

            $definition->setIdentity(

                $this->identityResolver
                    ->resolve($key)

            );

        }

        $definitions[$key] = $definition;

        continue;
    }

    if (! is_array($definition)) {

        throw new RuntimeException(

            sprintf(
                'Schema "%s" must be an array or SchemaDefinition.',
                $key
            )

        );
    }

    $definitions[$key] = $this->hydrate(
        $key,
        $definition
    );
}

        ksort($definitions);

        return $definitions;
    }

    /**
     * =========================================================================
     * Hydrate
     * =========================================================================
     */
    protected function hydrate(
    string $path,
    array $definition
): SchemaDefinition
{
    $schema = SchemaDefinition::fromArray(
        $definition
    );

    if (! $schema->hasIdentity()) {

        $schema->setIdentity(

            $this->identityResolver->resolve(

                relativePath: $path,

                type: $schema->type(),

            )

        );

    }

    return $schema;
}

    /**
     * =========================================================================
     * Exists
     * =========================================================================
     */
    public function exists(): bool
    {
        return file_exists(
            $this->file
        );
    }

    /**
     * =========================================================================
     * File
     * =========================================================================
     */
    public function file(): string
    {
        return $this->file;
    }

    /**
     * =========================================================================
     * Count
     * =========================================================================
     */
    public function count(): int
    {
        return count(
            $this->load()
        );
    }
}