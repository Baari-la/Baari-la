<?php

declare(strict_types=1);

namespace App\Services\MasterData\Classification;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Classification Result
 * ==========================================================================
 *
 * Immutable Value Object representing schema classification result.
 *
 * Used by:
 *
 * - SchemaClassifier
 * - MasterDataSchemaGenerator
 * - GenerateMasterDataSchemaCommand
 * - MasterDataHealthService
 *
 * ==========================================================================
 */
final class ClassificationResult
{
    /**
     * Constructor.
     *
     * @param array<string,int> $scores
     */
    public function __construct(
        protected string $type,
        protected int $winningScore,
        protected float $confidence,
        protected array $scores,
    ) {
    }

    /**
     * =========================================================================
     * Schema Type
     * =========================================================================
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * =========================================================================
     * Winning Score
     * =========================================================================
     */
    public function winningScore(): int
    {
        return $this->winningScore;
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
     * Scores
     * =========================================================================
     *
     * @return array<string,int>
     */
    public function scores(): array
    {
        return $this->scores;
    }

    /**
     * =========================================================================
     * Score
     * =========================================================================
     */
    public function score(
        string $type
    ): int
    {
        return $this->scores[$type] ?? 0;
    }

    /**
     * =========================================================================
     * Has Type
     * =========================================================================
     */
    public function has(
        string $type
    ): bool
    {
        return isset(
            $this->scores[$type]
        );
    }

    /**
     * =========================================================================
     * Is
     * =========================================================================
     */
    public function is(
        string $type
    ): bool
    {
        return $this->type === $type;
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

            'type' => $this->type(),

            'winning_score' => $this->winningScore(),

            'confidence' => $this->confidence(),

            'scores' => $this->scores(),

        ];
    }

    /**
     * =========================================================================
     * String Representation
     * =========================================================================
     */
    public function __toString(): string
    {
        return sprintf(

            '%s (%.2f%%)',

            $this->type,

            $this->confidence

        );
    }
}