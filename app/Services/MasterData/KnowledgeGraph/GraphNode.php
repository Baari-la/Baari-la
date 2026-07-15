<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Node
 * ==========================================================================
 *
 * Immutable representation of one Knowledge Graph node.
 *
 * A node represents one Master Data entity.
 *
 * Examples
 * --------
 *
 * Business Role
 * Buyer Segment
 * Supplier Segment
 * Certification
 * Product Category
 * Industry Segment
 *
 * ==========================================================================
 */
final class GraphNode
{
    /**
     * Constructor.
     *
     * @param array<string,mixed> $properties
     */
    public function __construct(
        protected string $id,
        protected string $label,
        protected string $schema,
        protected string $type,
        protected array $properties = [],
    ) {
    }

    /**
     * =========================================================================
     * ID
     * =========================================================================
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * =========================================================================
     * Label
     * =========================================================================
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * =========================================================================
     * Schema
     * =========================================================================
     */
    public function schema(): string
    {
        return $this->schema;
    }

    /**
     * =========================================================================
     * Node Type
     * =========================================================================
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * =========================================================================
     * Properties
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function properties(): array
    {
        return $this->properties;
    }

    /**
     * =========================================================================
     * Property
     * =========================================================================
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function property(
        string $key,
        mixed $default = null
    ): mixed
    {
        return $this->properties[$key] ?? $default;
    }

    /**
     * =========================================================================
     * Has Property
     * =========================================================================
     */
    public function hasProperty(
        string $key
    ): bool
    {
        return array_key_exists(
            $key,
            $this->properties
        );
    }

    /**
     * =========================================================================
     * Array Representation
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'id' => $this->id,

            'label' => $this->label,

            'schema' => $this->schema,

            'type' => $this->type,

            'properties' => $this->properties,

        ];
    }

    /**
     * =========================================================================
     * JSON Representation
     * =========================================================================
     */
    public function toJson(
        int $options = JSON_PRETTY_PRINT
    ): string
    {
        return (string) json_encode(

            $this->toArray(),

            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | $options

        );
    }

    /**
     * =========================================================================
     * String Representation
     * =========================================================================
     */
    public function __toString(): string
    {
        return sprintf(

            '%s (%s)',

            $this->label,

            $this->id

        );
    }
}