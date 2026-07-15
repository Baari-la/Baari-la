<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Model;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Node
 * ==========================================================================
 *
 * Represents one node in the Digestex Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Store node identity
 * - Store classification
 * - Store metadata
 * - Store graph connectivity
 *
 * This class DOES NOT:
 *
 * - Query repositories
 * - Build graphs
 * - Traverse graphs
 * - Perform analytics
 *
 * ==========================================================================
 */
final class GraphNode
{
    /**
     * Constructor.
     *
     * @param array<string,mixed> $attributes
     * @param array<string,mixed> $metadata
     * @param array<int,string>   $incoming
     * @param array<int,string>   $outgoing
     */
    public function __construct(

        protected string $id,

        protected string $label,

        protected string $type,

        protected float $confidence = 100.0,

        protected array $attributes = [],

        protected array $metadata = [],

        protected array $incoming = [],

        protected array $outgoing = [],

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
     * Type
     * =========================================================================
     */
    public function type(): string
    {
        return $this->type;
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
     * Incoming
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function incoming(): array
    {
        return $this->incoming;
    }

    /**
     * =========================================================================
     * Outgoing
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function outgoing(): array
    {
        return $this->outgoing;
    }

    /**
     * =========================================================================
     * Add Incoming
     * =========================================================================
     */
    public function addIncoming(
        string $edgeId
    ): self
    {
        if (! in_array($edgeId, $this->incoming, true)) {

            $this->incoming[] = $edgeId;

        }

        return $this;
    }

    /**
     * =========================================================================
     * Add Outgoing
     * =========================================================================
     */
    public function addOutgoing(
        string $edgeId
    ): self
    {
        if (! in_array($edgeId, $this->outgoing, true)) {

            $this->outgoing[] = $edgeId;

        }

        return $this;
    }

    /**
     * =========================================================================
     * Degree
     * =========================================================================
     */
    public function degree(): int
    {
        return count($this->incoming)
            + count($this->outgoing);
    }

    /**
     * =========================================================================
     * In Degree
     * =========================================================================
     */
    public function inDegree(): int
    {
        return count(
            $this->incoming
        );
    }

    /**
     * =========================================================================
     * Out Degree
     * =========================================================================
     */
    public function outDegree(): int
    {
        return count(
            $this->outgoing
        );
    }

    /**
     * =========================================================================
     * Is Isolated
     * =========================================================================
     */
    public function isIsolated(): bool
    {
        return $this->degree() === 0;
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

            'id' => $this->id,

            'label' => $this->label,

            'type' => $this->type,

            'confidence' => $this->confidence,

            'attributes' => $this->attributes,

            'metadata' => $this->metadata,

            'incoming' => $this->incoming,

            'outgoing' => $this->outgoing,

            'degree' => $this->degree(),

        ];
    }
}