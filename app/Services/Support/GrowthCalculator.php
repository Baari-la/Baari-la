<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * --------------------------------------------------------------------------
 * Growth Calculator
 * --------------------------------------------------------------------------
 * Calculate business growth across:
 * - Executive Report
 * - Market Intelligence
 * - Country Intelligence
 * - AI Summary
 */
class GrowthCalculator
{
    /**
     * Calculate Growth (%)
     */
    public static function calculate(
        float|int|null $current,
        float|int|null $previous
    ): float {

        $current = (float) ($current ?? 0);
        $previous = (float) ($previous ?? 0);

        if ($previous == 0) {
            return 0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    /**
     * Positive Growth?
     */
    public static function isPositive(float $growth): bool
    {
        return $growth > 0;
    }

    /**
     * Negative Growth?
     */
    public static function isNegative(float $growth): bool
    {
        return $growth < 0;
    }

    /**
     * No Growth?
     */
    public static function isFlat(float $growth): bool
    {
        return abs($growth) < 0.0001;
    }
}