<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Knowledge Node
 * ==========================================================================
 *
 * Represents one node inside the Textile Knowledge Graph.
 *
 * Examples:
 *
 * Company
 * Country
 * Product
 * Technology
 * Machinery
 * Certification
 * Sustainability
 * Market
 * Business Role
 *
 * Used by:
 *
 * - KnowledgeGraphService
 * - KnowledgeEvaluationService
 * - KnowledgeRecommendationService
 * - Executive AI
 *
 */

class KnowledgeNode
{
    /**
     * Node ID
     */
    protected string|int $id;

    /**
     * Node Type
     */
    protected string $type;

    /**
     * Display Label
     */
    protected string $label;

    /**
     * Node Attributes
     */
    protected array $attributes = [];

    /**
     * Connected Edge IDs
     */
    protected array $connections = [];

    /**
     * Optional score.
     */
    protected ?float $score = null;

    /**
     * Confidence (0-100)
     */
    protected ?float $confidence = null;

    /**
     * Constructor.
     */
    public function __construct(
        string|int $id,
        string $type,
        string $label,
        array $attributes = []
    ) {
        $this->id = $id;

        $this->type = $type;

        $this->label = $label;

        $this->attributes = $attributes;
    }

    /**
     * Node ID.
     */
    public function id(): string|int
    {
        return $this->id;
    }

    /**
     * Node type.
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Display label.
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * Attributes.
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get attribute.
     */
    public function attribute(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * Set attribute.
     */
    public function setAttribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Add relationship.
     */
    public function connect(string|int $nodeId): self
    {
        if (! in_array($nodeId, $this->connections, true)) {
            $this->connections[] = $nodeId;
        }

        return $this;
    }

    /**
     * Connected nodes.
     */
    public function connections(): array
    {
        return $this->connections;
    }

    /**
     * Score.
     */
    public function score(): ?float
    {
        return $this->score;
    }

    /**
     * Set score.
     */
    public function setScore(float $score): self
    {
        $this->score = $score;

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

            'id' => $this->id,

            'type' => $this->type,

            'label' => $this->label,

            'attributes' => $this->attributes,

            'connections' => $this->connections,

            'score' => $this->score,

            'confidence' => $this->confidence,

        ];
    }
}