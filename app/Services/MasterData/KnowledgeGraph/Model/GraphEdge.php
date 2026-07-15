<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Model;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Edge
 * ==========================================================================
 *
 * Represents one relationship between two Graph Nodes.
 *
 * Responsibilities
 * ----------------
 * - Store relationship metadata
 * - Store confidence score
 * - Store weight
 * - Store direction
 *
 * This class DOES NOT:
 *
 * - Query repositories
 * - Traverse graphs
 * - Build graphs
 * - Perform analytics
 *
 * ==========================================================================
 */
final class GraphEdge
{
    /**
     * Constructor.
     *
     * @param array<string,mixed> $attributes
     * @param array<string,mixed> $metadata
     */
    public function __construct(

        protected string $source,

        protected string $target,

        protected string $relation,

        protected float $weight = 1.0,

        protected float $confidence = 100.0,

        protected bool $collection = false,

        protected bool $bidirectional = false,

        protected array $attributes = [],

        protected array $metadata = [],

    ) {
    }

    /**
     * =========================================================================
     * Edge ID
     * =========================================================================
     */
    public function id(): string
    {
        return md5(

            implode('|', [

                $this->source,

                $this->target,

                $this->relation,

            ])

        );
    }

    /**
     * =========================================================================
     * Source
     * =========================================================================
     */
    public function source(): string
    {
        return $this->source;
    }

    /**
     * =========================================================================
     * Target
     * =========================================================================
     */
    public function target(): string
    {
        return $this->target;
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
     * Confidence
     * =========================================================================
     */
    public function confidence(): float
    {
        return $this->confidence;
    }

    /**
     * =========================================================================
     * Collection
     * =========================================================================
     */
    public function collection(): bool
    {
        return $this->collection;
    }

    /**
     * =========================================================================
     * Bidirectional
     * =========================================================================
     */
    public function bidirectional(): bool
    {
        return $this->bidirectional;
    }

    /**
     * =========================================================================
     * Attributes
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * =========================================================================
     * Metadata
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * =========================================================================
     * Attribute
     * =========================================================================
     *
     * @return mixed
     */
    public function attribute(
        string $key,
        mixed $default = null
    ): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * =========================================================================
     * Metadata Value
     * =========================================================================
     *
     * @return mixed
     */
    public function meta(
        string $key,
        mixed $default = null
    ): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * =========================================================================
     * Is Self Reference
     * =========================================================================
     */
    public function isSelfReference(): bool
    {
        return $this->source === $this->target;
    }

    /**
     * =========================================================================
     * Reverse
     * =========================================================================
     */
    public function reverse(): self
    {
        return new self(

            source: $this->target,

            target: $this->source,

            relation: $this->relation,

            weight: $this->weight,

            confidence: $this->confidence,

            collection: $this->collection,

            bidirectional: $this->bidirectional,

            attributes: $this->attributes,

            metadata: $this->metadata,

        );
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
        return [

            'id' => $this->id(),

            'source' => $this->source,

            'target' => $this->target,

            'relation' => $this->relation,

            'weight' => $this->weight,

            'confidence' => $this->confidence,

            'collection' => $this->collection,

            'bidirectional' => $this->bidirectional,

            'attributes' => $this->attributes,

            'metadata' => $this->metadata,

        ];
    }
}