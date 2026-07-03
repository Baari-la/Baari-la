<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * --------------------------------------------------------------------------
 * Trade Formatter
 * --------------------------------------------------------------------------
 * Standard formatter used by:
 * - Executive Dashboard
 * - Trade Intelligence
 * - Executive Report
 */
class TradeFormatter
{
    /**
     * Trade Value (USD)
     */
    public static function value(
        float|int|null $value
    ): string {

        return CurrencyFormatter::usd($value);
    }

    /**
     * Trade Volume (KG)
     */
    public static function volume(
        float|int|null $volume
    ): string {

        return NumberFormatter::format($volume, 0) . ' KG';
    }

    /**
     * Pieces
     */
    public static function pieces(
        float|int|null $pieces
    ): string {

        return PiecesFormatter::short($pieces);
    }

    /**
     * Growth
     */
    public static function growth(
        float|int|null $growth
    ): string {

        return PercentageFormatter::growth($growth);
    }

    /**
     * Trend
     */
    public static function trend(
        float|int|null $growth
    ): array {

        $growth = (float) ($growth ?? 0);

        return [
            'value' => self::growth($growth),
            'direction' => TrendCalculator::direction($growth),
            'icon' => TrendCalculator::icon($growth),
            'color' => TrendCalculator::color($growth),
            'score' => TrendCalculator::score($growth),
        ];
    }
}