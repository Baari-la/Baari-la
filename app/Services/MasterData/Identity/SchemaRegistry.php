<?php

declare(strict_types=1);

namespace App\Services\MasterData\Identity;

use App\Services\MasterData\Generator\SchemaDefinition;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Registry
 * ==========================================================================
 *
 * Central registry for all Master Data schemas.
 *
 * Responsibilities
 * ----------------
 * - Register schemas
 * - Lookup schemas
 * - Lookup identities
 * - Provide collections
 * - Maintain stable schema index
 *
 * This class NEVER:
 * - Reads schema files
 * - Generates schemas
 * - Builds Knowledge Graph
 * - Detects references
 * - Performs validation
 *
 * ==========================================================================
 */
final class SchemaRegistry
{
    /**
     * Registered identities.
     *
     * @var array<string,SchemaIdentity>
     */
    protected array $identities = [];

    /**
     * Registered schema definitions.
     *
     * @var array<string,SchemaDefinition>
     */
    protected array $definitions = [];

    /**
     * =========================================================================
     * Register
     * =========================================================================
     */
    public function register(
        SchemaIdentity $identity,
        SchemaDefinition $definition
    ): self
    {
        $id = $identity->id();

        $this->identities[$id] = $identity;

        $this->definitions[$id] = $definition;

        return $this;
    }

    /**
     * =========================================================================
     * Has
     * =========================================================================
     */
    public function has(
        string $schemaId
    ): bool
    {
        return isset(
            $this->definitions[$schemaId]
        );
    }

    /**
     * =========================================================================
     * Identity
     * =========================================================================
     */
    public function identity(
        string $schemaId
    ): ?SchemaIdentity
    {
        return $this->identities[$schemaId] ?? null;
    }

    /**
     * =========================================================================
     * Definition
     * =========================================================================
     */
    public function definition(
        string $schemaId
    ): ?SchemaDefinition
    {
        return $this->definitions[$schemaId] ?? null;
    }

    /**
     * =========================================================================
     * Find
     * =========================================================================
     */
    public function find(
        string $schemaId
    ): ?SchemaDefinition
    {
        return $this->definition(
            $schemaId
        );
    }

    /**
     * =========================================================================
     * All Identities
     * =========================================================================
     *
     * @return array<string,SchemaIdentity>
     */
    public function identities(): array
    {
        return $this->identities;
    }

    /**
     * =========================================================================
     * All Definitions
     * =========================================================================
     *
     * @return array<string,SchemaDefinition>
     */
    public function definitions(): array
    {
        return $this->definitions;
    }

    /**
     * =========================================================================
     * IDs
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function ids(): array
    {
        return array_keys(
            $this->definitions
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
            $this->definitions
        );
    }

    /**
     * =========================================================================
     * Is Empty
     * =========================================================================
     */
    public function isEmpty(): bool
    {
        return empty(
            $this->definitions
        );
    }

    /**
     * =========================================================================
     * Is Not Empty
     * =========================================================================
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * =========================================================================
     * Clear
     * =========================================================================
     */
    public function clear(): void
    {
        $this->identities = [];

        $this->definitions = [];
    }

    /**
     * =========================================================================
     * Remove
     * =========================================================================
     */
    public function remove(
        string $schemaId
    ): void
    {
        unset(
            $this->identities[$schemaId],
            $this->definitions[$schemaId]
        );
    }

    /**
     * =========================================================================
     * Statistics
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function statistics(): array
    {
        return [

            'schemas' => $this->count(),

            'identities' => count(
                $this->identities
            ),

            'definitions' => count(
                $this->definitions
            ),

        ];
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $schemas = [];

        foreach (
            $this->definitions as $id => $definition
        ) {

            $schemas[$id] = [

                'identity' =>

                    $this->identities[$id]
                        ->toArray(),

                'definition' =>

                    $definition->toArray(),

            ];

        }

        ksort($schemas);

        return $schemas;
    }
}