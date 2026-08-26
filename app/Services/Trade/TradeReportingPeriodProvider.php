<?php

declare(strict_types=1);

namespace App\Services\Trade;

use RuntimeException;

class TradeReportingPeriodProvider
{
    /*
    |--------------------------------------------------------------------------
    | Current Public Reporting Period
    |--------------------------------------------------------------------------
    |
    | Temporary administrative configuration.
    |
    | Current Digestex policy:
    |
    | Public Through : May 2026
    | Comparison     : May 2025
    | Buffer         : June 2026
    |
    | IMPORTANT:
    | This class is intentionally isolated so the source can later
    | be replaced by an Admin-controlled database record without
    | changing sector intelligence services.
    |
    */

    protected const PUBLIC_THROUGH_YEAR = 2026;

    protected const PUBLIC_THROUGH_MONTH = 6;

    protected const BUFFER_YEAR = 2026;

    protected const BUFFER_MONTH = 6;

    protected const STATUS = 'available';


    /*
    |--------------------------------------------------------------------------
    | Get Active Reporting Period
    |--------------------------------------------------------------------------
    */

    public function current(): TradeReportingPeriod
    {
        return TradeReportingPeriod::make(
            publicThroughYear:
                self::PUBLIC_THROUGH_YEAR,

            publicThroughMonth:
                self::PUBLIC_THROUGH_MONTH,

            bufferYear:
                self::BUFFER_YEAR,

            bufferMonth:
                self::BUFFER_MONTH,

            status:
                self::STATUS,
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Metadata
    |--------------------------------------------------------------------------
    */

    public function status(): string
    {
        return self::STATUS;
    }


    public function publicThroughYear(): int
    {
        return self::PUBLIC_THROUGH_YEAR;
    }


    public function publicThroughMonth(): int
    {
        return self::PUBLIC_THROUGH_MONTH;
    }


    public function bufferYear(): int
    {
        return self::BUFFER_YEAR;
    }


    public function bufferMonth(): int
    {
        return self::BUFFER_MONTH;
    }


    /*
    |--------------------------------------------------------------------------
    | Safety Check
    |--------------------------------------------------------------------------
    */

    public function validate(): void
    {
        $period =
            $this->current();

        if (
            $period->publicThroughYear < 2000
            || $period->publicThroughMonth < 1
            || $period->publicThroughMonth > 12
        ) {
            throw new RuntimeException(
                'Invalid Digestex public reporting period.'
            );
        }

        if (
            $period->bufferYear !== null
            && (
                $period->bufferYear < 2000
                || $period->bufferMonth < 1
                || $period->bufferMonth > 12
            )
        ) {
            throw new RuntimeException(
                'Invalid Digestex buffer reporting period.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Array Representation
    |--------------------------------------------------------------------------
    */

    public function toArray(): array
    {
        return $this->current()->toArray();
    }
}