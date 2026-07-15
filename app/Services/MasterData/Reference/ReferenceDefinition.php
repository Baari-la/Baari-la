<?php

declare(strict_types=1);

namespace App\Services\MasterData\Reference;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Reference Definition
 * ==========================================================================
 *
 * Immutable value object representing one detected Master Data reference.
 *
 * Example
 * -------
 *
 * ecosystem
 *      ↓
 * Business/business_ecosystems.php
 *
 * ==========================================================================
 */
final class ReferenceDefinition
{
    /**
     * =========================================================================
     * Constructor
     * =========================================================================
     */
    public function __construct(
        protected string $field,
        protected string $target,
        protected string $relation = 'belongs_to',
        protected bool $collection = false,
        protected int $confidence = 100,
        protected ?string $reason = null,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Factory
    |--------------------------------------------------------------------------
    */

    /**
     * =========================================================================
     * From Array
     * =========================================================================
     *
     * Hydrates a ReferenceDefinition from generated schema data.
     *
     * @param array<string,mixed> $reference
     */
    public static function fromArray(
        array $reference
    ): self
    {
        return new self(

            field: (string) ($reference['field'] ?? ''),

            target: (string) ($reference['target'] ?? ''),

            relation: (string) (
                $reference['relation']
                ?? 'belongs_to'
            ),

            collection: (bool) (
                $reference['collection']
                ?? false
            ),

            confidence: (int) (
                $reference['confidence']
                ?? 100
            ),

            reason: isset($reference['reason'])
                ? (string) $reference['reason']
                : null,

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    /**
     * =========================================================================
     * Field
     * =========================================================================
     */
    public function field(): string
    {
        return $this->field;
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
     * Collection
     * =========================================================================
     */
    public function collection(): bool
    {
        return $this->collection;
    }

    /**
     * =========================================================================
     * Confidence
     * =========================================================================
     */
    public function confidence(): int
    {
        return $this->confidence;
    }

    /**
     * =========================================================================
     * Reason
     * =========================================================================
     */
    public function reason(): ?string
    {
        return $this->reason;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * =========================================================================
     * Has Reason
     * =========================================================================
     */
    public function hasReason(): bool
    {
        return $this->reason !== null
            && $this->reason !== '';
    }

    /**
     * =========================================================================
     * Is Collection
     * =========================================================================
     */
    public function isCollection(): bool
    {
        return $this->collection;
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
     * Is One To Many
     * =========================================================================
     */
    public function isOneToMany(): bool
    {
        return $this->relation === 'one_to_many';
    }

    /**
     * =========================================================================
     * Is One To One
     * =========================================================================
     */
    public function isOneToOne(): bool
    {
        return $this->relation === 'one_to_one';
    }

    /**
     * =========================================================================
     * Is Graph Relation
     * =========================================================================
     */
    public function isGraphRelation(): bool
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

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

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

            'field' => $this->field,

            'target' => $this->target,

            'relation' => $this->relation,

            'collection' => $this->collection,

            'confidence' => $this->confidence,

            'reason' => $this->reason,

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

            $options
            | JSON_UNESCAPED_SLASHES

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

            '%s -> %s',

            $this->field,

            $this->target

        );
    }
}