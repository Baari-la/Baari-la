<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * --------------------------------------------------------------------------
 * Trend Calculator
 * --------------------------------------------------------------------------
 * Used for:
 * - Dashboard
 * - Executive Report
 * - AI Summary
 */
class TrendCalculator
{
    /**
     * Trend Direction
     */
    public static function direction(float|int $value): string
    {
        return match (true) {
            $value > 0 => 'up',
            $value < 0 => 'down',
            default => 'flat',
        };
    }

    /**
     * Trend Icon
     */
    public static function icon(float|int $value): string
    {
        return match (true) {
            $value > 0 => '▲',
            $value < 0 => '▼',
            default => '■',
        };
    }

    /**
     * Trend Color
     */
    public static function color(float|int $value): string
    {
        return match (true) {
            $value > 0 => 'success',
            $value < 0 => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Trend Score
     *
     * Normalized score:
     * 0 - 100
     */
    public static function score(float|int $growth): float
    {
        return max(
            0,
            min(
                100,
                50 + $growth
            )
        );
    }
}