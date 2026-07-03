<?php

declare(strict_types=1);

namespace App\Services\Support;

use Carbon\Carbon;

/**
 * DIGESTEX CORE
 * --------------------------------------------------------------------------
 * Date Formatter
 * --------------------------------------------------------------------------
 * Standard date formatting.
 */
class DateFormatter
{
    /**
     * Default Date
     */
    public static function date(
        string|null $date,
        string $format = 'd M Y'
    ): string {

        if (empty($date)) {
            return '-';
        }

        return Carbon::parse($date)
            ->format($format);
    }

    /**
     * Date Time
     */
    public static function dateTime(
        string|null $date
    ): string {

        if (empty($date)) {
            return '-';
        }

        return Carbon::parse($date)
            ->format('d M Y H:i');
    }

    /**
     * Month Year
     */
    public static function monthYear(
        string|null $date
    ): string {

        if (empty($date)) {
            return '-';
        }

        return Carbon::parse($date)
            ->format('M Y');
    }

    /**
     * Year
     */
    public static function year(
        string|null $date
    ): string {

        if (empty($date)) {
            return '-';
        }

        return Carbon::parse($date)
            ->format('Y');
    }

    /**
     * Human Readable
     */
    public static function diffForHumans(
        string|null $date
    ): string {

        if (empty($date)) {
            return '-';
        }

        return Carbon::parse($date)
            ->diffForHumans();
    }
}