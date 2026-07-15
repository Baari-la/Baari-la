<?php

declare(strict_types=1);

namespace App\Services\MasterData\Classification;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Scoring Engine
 * ==========================================================================
 *
 * Calculates schema classification scores.
 *
 * Pipeline
 * --------
 *
 * Normalize Fields
 *        ↓
 * Mandatory Validation
 *        ↓
 * Weighted Scoring
 *        ↓
 * Minimum Score Validation
 *        ↓
 * Score Matrix
 *
 * ==========================================================================
 */
final class ScoringEngine
{
    /**
     * Constructor.
     */
    public function __construct(
        protected ClassificationRules $rules
    ) {
    }

    /**
     * =========================================================================
     * Score
     * =========================================================================
     *
     * @param array<int,string> $fields
     *
     * @return array<string,int>
     */
    public function score(
        array $fields
    ): array
    {
        $fields = $this->normalizeFields(
            $fields
        );

        $scores = [];

        foreach ($this->rules->types() as $type) {

            $scores[$type] = $this->calculateScore(

                $fields,

                $this->rules->rule($type)

            );

        }

        arsort($scores);

        return $scores;
    }

    /**
     * =========================================================================
     * Calculate Score
     * =========================================================================
     *
     * @param array<int,string> $fields
     * @param array<string,mixed> $rule
     */
    protected function calculateScore(
        array $fields,
        array $rule
    ): int
    {
        /*
        |--------------------------------------------------------------------------
        | Mandatory Check
        |--------------------------------------------------------------------------
        */

        if (! $this->passesMandatory(
            $fields,
            $rule['mandatory'] ?? []
        )) {

            return 0;

        }

        /*
        |--------------------------------------------------------------------------
        | Weighted Score
        |--------------------------------------------------------------------------
        */

        $score = 0;

        foreach (
            $rule['weights'] ?? [] as $field => $weight
        ) {

            if (
                in_array(
                    $field,
                    $fields,
                    true
                )
            ) {

                $score += (int) $weight;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Score
        |--------------------------------------------------------------------------
        */

        if (
            $score < ($rule['minimum_score'] ?? 0)
        ) {

            return 0;

        }

        return $score;
    }

    /**
     * =========================================================================
     * Mandatory Validation
     * =========================================================================
     *
     * @param array<int,string> $fields
     * @param array<int,string> $mandatory
     */
    protected function passesMandatory(
        array $fields,
        array $mandatory
    ): bool
    {
        foreach ($mandatory as $field) {

            if (! in_array(
                $field,
                $fields,
                true
            )) {

                return false;

            }

        }

        return true;
    }

    /**
     * =========================================================================
     * Normalize Fields
     * =========================================================================
     *
     * @param array<int,string> $fields
     *
     * @return array<int,string>
     */
    protected function normalizeFields(
        array $fields
    ): array
    {
        $normalized = [];

        foreach ($fields as $field) {

            $normalized[] = strtolower(
                trim($field)
            );

        }

        $normalized = array_unique(
            $normalized
        );

        sort($normalized);

        return array_values(
            $normalized
        );
    }

    /**
     * =========================================================================
     * Winning Type
     * =========================================================================
     */
    public function winningType(
        array $scores
    ): string
    {
        if ($scores === []) {

            return 'lookup';

        }

        arsort($scores);

        return (string) array_key_first(
            $scores
        );
    }

    /**
     * =========================================================================
     * Highest Score
     * =========================================================================
     */
    public function highestScore(
        array $scores
    ): int
    {
        if ($scores === []) {

            return 0;

        }

        return max($scores);
    }

    /**
     * =========================================================================
     * Confidence
     * =========================================================================
     *
     * Returns confidence percentage.
     */
    public function confidence(
        array $scores
    ): float
    {
        if ($scores === []) {

            return 0.0;

        }

        $winner = max($scores);

        $total = array_sum($scores);

        if ($winner === 0 || $total === 0) {

            return 0.0;

        }

        return round(

            ($winner / $total) * 100,

            2

        );
    }
}