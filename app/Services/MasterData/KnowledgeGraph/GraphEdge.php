<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Edge
 * ==========================================================================
 *
 * Immutable representation of one relationship between two Graph Nodes.
 *
 * Examples
 * --------
 *
 * Spinner
 *      ──upstream────► Fiber Manufacturer
 *
 * Buyer Segment
 *      ──uses────────► Certification
 *
 * Industry Segment
 *      ──belongs_to──► Business Ecosystem
 *
 * ==========================================================================
 */
final class GraphEdge
{
    /**
     * Constructor.
     *
     * @param array<string,mixed> $properties
     */
    public function __construct(
        protected string $from,
        protected string $to,
        protected string $relation,
        protected float $weight = 1.0,
        protected bool $directed = true,
        protected array $properties = [],
    ) {
    }

    /**
     * =========================================================================
     * From
     * =========================================================================
     */
    public function from(): string
    {
        return $this->from;
    }

    /**
     * =========================================================================
     * To
     * =========================================================================
     */
    public function to(): string
    {
        return $this->to;
    }

    /**
     * =========================================================================
     * Relation
     * =========================================================================
     */
    public function relation(): string
    {
        return $this->relation;
    }

    /**
     * =========================================================================
     * Weight
     * =========================================================================
     */
    public function weight(): float
    {
        return $this->weight;
    }

    /**
     * =========================================================================
     * Directed
     * =========================================================================
     */
    public function directed(): bool
    {
        return $this->directed;
    }

    /**
     * =========================================================================
     * Is Directed
     * =========================================================================
     */
    public function isDirected(): bool
    {
        return $this->directed;
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
     * Is Graph Edge
     * =========================================================================
     */
    public function isGraphEdge(): bool
    {
        return in_array(
            $this->relation,
            [
                'graph_edge',
                'upstream',
                'downstream',
            ],
            true
        );
    }

    /**
     * =========================================================================
     * Is Belongs To
     * =========================================================================
     */
    public function isBelongsTo(): bool
    {
        return $this->relation === 'belongs_to';
    }

    /**
     * =========================================================================
     * Is Many To Many
     * =========================================================================
     */
    public function isManyToMany(): bool
    {
        return $this->relation === 'many_to_many';
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

            'from' => $this->from,

            'to' => $this->to,

            'relation' => $this->relation,

            'weight' => $this->weight,

            'directed' => $this->directed,

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

            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
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

            '%s --[%s]--> %s',

            $this->from,

            $this->relation,

            $this->to

        );
    }
}