<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * --------------------------------------------------------------------------
 * Number Formatter
 * --------------------------------------------------------------------------
 * Standard number formatting used across:
 * - Executive Dashboard
 * - Trade Intelligence
 * - Company Directory
 * - AI Summary
 * - Mobile API
 */
class NumberFormatter
{
    /**
     * Format Number
     */
    public static function format(
        float|int|null $number,
        int $decimals = 0
    ): string {
        return number_format($number ?? 0, $decimals);
    }

    /**
     * Format Million
     */
    public static function million(float|int|null $number): string
    {
        return number_format(($number ?? 0) / 1_000_000, 2);
    }

    /**
     * Format Billion
     */
    public static function billion(float|int|null $number): string
    {
        return number_format(($number ?? 0) / 1_000_000_000, 2);
    }

    /**
     * Format Trillion
     */
    public static function trillion(float|int|null $number): string
    {
        return number_format(($number ?? 0) / 1_000_000_000_000, 2);
    }

    /**
     * Short Number
     */
    public static function short(float|int|null $number): string
    {
        $number = $number ?? 0;

        if ($number >= 1_000_000_000) {
            return round($number / 1_000_000_000, 2) . 'B';
        }

        if ($number >= 1_000_000) {
            return round($number / 1_000_000, 2) . 'M';
        }

        if ($number >= 1_000) {
            return round($number / 1_000, 2) . 'K';
        }

        return number_format($number);
    }
}