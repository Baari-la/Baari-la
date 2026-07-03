<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * Pieces Formatter
 *
 * Used by Executive Report
 * Garment Intelligence
 * Apparel Dashboard
 */
class PiecesFormatter
{
    /**
     * Format Pieces
     */
    public static function format(float|int|null $pieces): string
    {
        return number_format($pieces ?? 0) . ' pcs';
    }

    /**
     * Million Pieces
     */
    public static function million(float|int|null $pieces): string
    {
        return number_format(($pieces ?? 0) / 1_000_000, 2) . ' M pcs';
    }

    /**
     * Billion Pieces
     */
    public static function billion(float|int|null $pieces): string
    {
        return number_format(($pieces ?? 0) / 1_000_000_000, 2) . ' B pcs';
    }

    /**
     * Compact Pieces
     */
    public static function short(float|int|null $pieces): string
    {
        return NumberFormatter::short($pieces) . ' pcs';
    }
}