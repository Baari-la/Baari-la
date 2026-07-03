<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * Percentage Formatter
 */
class PercentageFormatter
{
    /**
     * Format Percentage
     */
    public static function format(
        float|int|null $value,
        int $decimals = 2
    ): string {

        return number_format($value ?? 0, $decimals) . '%';
    }

    /**
     * Growth Badge
     */
    public static function growth(float|int|null $value): string
    {
        $value = $value ?? 0;

        if ($value > 0) {
            return '+' . number_format($value, 2) . '%';
        }

        return number_format($value, 2) . '%';
    }

    /**
     * Trend Direction
     */
    public static function direction(float|int|null $value): string
    {
        $value = $value ?? 0;

        return match (true) {
            $value > 0 => 'up',
            $value < 0 => 'down',
            default => 'flat',
        };
    }
}