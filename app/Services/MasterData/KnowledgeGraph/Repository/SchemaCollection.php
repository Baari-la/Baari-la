<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Repository;

use App\Services\MasterData\Generator\SchemaDefinition;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Collection
 * ==========================================================================
 *
 * Strongly typed collection for SchemaDefinition objects.
 *
 * Responsibilities
 * ----------------
 * - Preserve schema identifiers
 * - Domain filtering
 * - Type filtering
 * - Reference filtering
 * - Iterator support
 *
 * ==========================================================================
 */
final class SchemaCollection implements IteratorAggregate
{
    /**
     * @var Collection<string,SchemaDefinition>
     */
    protected Collection $schemas;

    /**
     * =========================================================================
     * Constructor
     * =========================================================================
     *
     * @param iterable<string,SchemaDefinition> $schemas
     */
    public function __construct(
        iterable $schemas = []
    ) {
        $this->schemas = collect($schemas)

            ->filter(

                static fn ($schema): bool =>

                    $schema instanceof SchemaDefinition

            );

        // IMPORTANT:
        // Do NOT call values().
        // Preserve schema ids:
        //
        // Business/buyer_segments.php
        // Products/product_categories.php
    }

    /**
     * =========================================================================
     * All
     * =========================================================================
     *
     * @return Collection<string,SchemaDefinition>
     */
    public function all(): Collection
    {
        return $this->schemas;
    }

    /**
     * =========================================================================
     * Iterator
     * =========================================================================
     */
    public function getIterator(): Traversable
    {
        return $this->schemas->getIterator();
    }

    /**
     * =========================================================================
     * Count
     * =========================================================================
     */
    public function count(): int
    {
        return $this->schemas->count();
    }

    /**
     * =========================================================================
     * Is Empty
     * =========================================================================
     */
    public function isEmpty(): bool
    {
        return $this->schemas->isEmpty();
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
     * First
     * =========================================================================
     */
    public function first(): ?SchemaDefinition
    {
        return $this->schemas->first();
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
        return $this->schemas->has(
            $schemaId
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
        return $this->schemas->get(
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
        return $this->schemas
            ->keys()
            ->all();
    }

    /**
     * =========================================================================
     * Filter
     * =========================================================================
     */
    public function filter(
        callable $callback
    ): self
    {
        return new self(

            $this->schemas
                ->filter($callback)

        );
    }

    /**
     * =========================================================================
     * By Type
     * =========================================================================
     */
    public function byType(
        string $type
    ): self
    {
        return $this->filter(

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
    public function knowledgeNodes(): self
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
    public function lookups(): self
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
    public function relationships(): self
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
    public function configurations(): self
    {
        return $this->byType(
            'configuration'
        );
    }

    /**
     * =========================================================================
     * With References
     * =========================================================================
     */
    public function withReferences(): self
    {
        return $this->filter(

            static fn (
                SchemaDefinition $schema
            ): bool =>

                ! empty(
                    $schema->references()
                )

        );
    }

    /**
     * =========================================================================
     * Without References
     * =========================================================================
     */
    public function withoutReferences(): self
    {
        return $this->filter(

            static fn (
                SchemaDefinition $schema
            ): bool =>

                empty(
                    $schema->references()
                )

        );
    }

    /**
     * =========================================================================
     * Sorted
     * =========================================================================
     */
    public function sorted(): self
    {
        return new self(

            $this->schemas
                ->sortBy(

                    static fn (
                        SchemaDefinition $schema
                    ) =>

                        $schema->type()

                )

        );
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,SchemaDefinition>
     */
    public function toArray(): array
    {
        return $this->schemas->all();
    }
}