<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * Currency Formatter
 */
class CurrencyFormatter
{
    /**
     * Format Currency
     */
    public static function format(
        float|int|null $amount,
        string $currency = 'USD',
        int $decimals = 2
    ): string {

        return $currency . ' ' .
            number_format($amount ?? 0, $decimals);
    }

    /**
     * USD
     */
    public static function usd(float|int|null $amount): string
    {
        return self::format($amount, 'USD');
    }

    /**
     * Rupiah
     */
    public static function idr(float|int|null $amount): string
    {
        return 'Rp ' . number_format($amount ?? 0, 0);
    }

    /**
     * Euro
     */
    public static function eur(float|int|null $amount): string
    {
        return self::format($amount, 'EUR');
    }

    /**
     * Yen
     */
    public static function jpy(float|int|null $amount): string
    {
        return 'JPY ' . number_format($amount ?? 0, 0);
    }
}