<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Repository;

use App\Services\MasterData\Generator\SchemaDefinition;
use App\Services\MasterData\Identity\SchemaRegistry;
use App\Services\MasterData\KnowledgeGraph\Repository\SchemaCollection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Repository
 * ==========================================================================
 *
 * Central repository for generated Master Data schemas.
 *
 * Responsibilities
 * ----------------
 * - Load generated schemas
 * - Cache schemas
 * - Lookup schemas
 * - Expose strongly typed SchemaCollection
 *
 * This class NEVER:
 *
 * - Generates schemas
 * - Builds Knowledge Graphs
 * - Modifies schema definitions
 *
 * ==========================================================================
 */
final class SchemaRepository
{
    
    /**
     * Constructor.
     */
    public function __construct(
    protected SchemaLoader $loader,
    protected SchemaRegistry $registry,
    ) {
    }

    /**
     * =========================================================================
     * All
     * =========================================================================
     *
     * @return array<string,SchemaDefinition>
     */
    public function all(): array
{
    $this->ensureLoaded();

    return $this->registry->definitions();
}

/**
 * =========================================================================
 * Ensure Loaded
 * =========================================================================
 */
protected function ensureLoaded(): void
{
    if (! $this->registry->isEmpty()) {
        return;
    }

    foreach ($this->loader->load() as $schema) {

        if (! $schema->hasIdentity()) {
            continue;
        }

        $this->registry->register(
            $schema->identity(),
            $schema
        );

    }
}
    /**
     * =========================================================================
     * Collection
     * =========================================================================
     */
    public function collection(): SchemaCollection
    {
        return new SchemaCollection(
            $this->all()
        );
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
            $this->ensureLoaded();

            return $this->registry->definition(
                $schemaId
            );
        }

    /**
     * =========================================================================
     * Has
     * =========================================================================
     */
    public function has(string $schemaId): bool
        {
            $this->ensureLoaded();

            return $this->registry->has(
                $schemaId
            );
        }

    /**
     * =========================================================================
     * Keys
     * =========================================================================
     *
     * @return array<int,string>
     */
  
    public function keys(): array
    {
        $this->ensureLoaded();

        return $this->registry->ids();
    }

    /**
     * =========================================================================
     * Count
     * =========================================================================
     */
   public function count(): int
    {
        $this->ensureLoaded();

        return $this->registry->count();
    }

    /**
     * =========================================================================
     * First
     * =========================================================================
     */
    public function first(): ?SchemaDefinition
{
    $schemas = $this->all();

    return reset($schemas)
        ?: null;
}

    /**
     * =========================================================================
     * By Type
     * =========================================================================
     */
    public function byType(
        string $type
    ): SchemaCollection
    {
        return $this->collection()

            ->filter(

                static fn (
                    SchemaDefinition $schema
                ): bool =>

                    $schema->type() === $type

            );
    }

    /**
     * =========================================================================
     * Knowledge Nodes
     * =========================================================================
     */
    public function knowledgeNodes(): SchemaCollection
    {
        return $this->byType(
            'knowledge_node'
        );
    }

    /**
     * =========================================================================
     * Lookups
     * =========================================================================
     */
    public function lookups(): SchemaCollection
    {
        return $this->byType(
            'lookup'
        );
    }

    /**
     * =========================================================================
     * Relationships
     * =========================================================================
     */
    public function relationships(): SchemaCollection
    {
        return $this->byType(
            'relationship'
        );
    }

    /**
     * =========================================================================
     * Configurations
     * =========================================================================
     */
    public function configurations(): SchemaCollection
    {
        return $this->byType(
            'configuration'
        );
    }

    /**
     * =========================================================================
     * Filter
     * =========================================================================
     */
    public function filter(
        callable $callback
    ): SchemaCollection
    {
        return $this->collection()
            ->filter($callback);
    }

    /**
     * =========================================================================
     * Reload
     * =========================================================================
     */
    public function reload(): void
        {
            $this->registry->clear();

            $this->ensureLoaded();
        }

/**
 * =========================================================================
 * Registry
 * =========================================================================
 */
public function registry(): SchemaRegistry
{
    $this->ensureLoaded();

    return $this->registry;
}

/**
 * =========================================================================
 * Identities
 * =========================================================================
 *
 * @return array<string,\App\Services\MasterData\Identity\SchemaIdentity>
 */
public function identities(): array
{
    $this->ensureLoaded();

    return $this->registry->identities();
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
    $this->ensureLoaded();

    return $this->registry->statistics();
}
    /**
     * =========================================================================
     * Is Empty
     * =========================================================================
     */
    public function isEmpty(): bool
    {
        return empty(
            $this->all()
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
}