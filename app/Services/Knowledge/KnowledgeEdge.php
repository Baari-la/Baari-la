<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Knowledge Edge
 * ==========================================================================
 *
 * Represents the relationship between two Knowledge Nodes.
 *
 * Examples:
 *
 * Company -------- owns ------------> Product
 * Product -------- uses ------------> Technology
 * Technology ----- requires --------> Machinery
 * Certification -- recognized_in ---> Market
 *
 * Used by:
 *
 * - KnowledgeGraphService
 * - KnowledgeEvaluationService
 * - KnowledgeRecommendationService
 * - Executive AI
 *
 */

class KnowledgeEdge
{
    /**
     * Source Node ID.
     */
    protected string|int $from;

    /**
     * Destination Node ID.
     */
    protected string|int $to;

    /**
     * Relationship name.
     */
    protected string $relationship;

    /**
     * Relationship weight.
     */
    protected float $weight = 1.0;

    /**
     * Optional metadata.
     */
    protected array $metadata = [];

    /**
     * Confidence (0-100).
     */
    protected ?float $confidence = null;

    /**
     * Constructor.
     */
    public function __construct(
        string|int $from,
        string|int $to,
        string $relationship,
        float $weight = 1.0,
        array $metadata = []
    ) {
        $this->from = $from;

        $this->to = $to;

        $this->relationship = $relationship;

        $this->weight = $weight;

        $this->metadata = $metadata;
    }

    /**
     * Source node.
     */
    public function from(): string|int
    {
        return $this->from;
    }

    /**
     * Destination node.
     */
    public function to(): string|int
    {
        return $this->to;
    }

    /**
     * Relationship.
     */
    public function relationship(): string
    {
        return $this->relationship;
    }

    /**
     * Weight.
     */
    public function weight(): float
    {
        return $this->weight;
    }

    /**
     * Set weight.
     */
    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Metadata.
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * Get metadata value.
     */
    public function meta(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Set metadata.
     */
    public function setMeta(string $key, mixed $value): self
    {
        $this->metadata[$key] = $value;

        return $this;
    }

    /**
     * Confidence.
     */
    public function confidence(): ?float
    {
        return $this->confidence;
    }

    /**
     * Set confidence.
     */
    public function setConfidence(float $confidence): self
    {
        $this->confidence = $confidence;

        return $this;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [

            'from' => $this->from,

            'to' => $this->to,

            'relationship' => $this->relationship,

            'weight' => $this->weight,

            'metadata' => $this->metadata,

            'confidence' => $this->confidence,

        ];
    }
}