<?php

declare(strict_types=1);

namespace App\Services\Trade;

use Carbon\Carbon;
use InvalidArgumentException;

final class TradeReportingPeriod
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public function __construct(
        public readonly int $publicThroughYear,
        public readonly int $publicThroughMonth,
        public readonly int $comparisonYear,
        public readonly int $comparisonThroughMonth,
        public readonly ?int $bufferYear = null,
        public readonly ?int $bufferMonth = null,
        public readonly string $status = 'available',
        public readonly string $mode = 'ytd',
    ) {
        $this->validate();
    }


    /*
    |--------------------------------------------------------------------------
    | Factory: Latest Public Period
    |--------------------------------------------------------------------------
    |
    | Existing system behavior is preserved.
    |
    | Example:
    |
    | Public  : Jan-Jun 2026
    | Compare : Jan-Jun 2025
    |
    */

    public static function make(
        int $publicThroughYear,
        int $publicThroughMonth,
        ?int $bufferYear = null,
        ?int $bufferMonth = null,
        string $status = 'available',
    ): self {
        return new self(
            publicThroughYear:
                $publicThroughYear,

            publicThroughMonth:
                $publicThroughMonth,

            comparisonYear:
                $publicThroughYear - 1,

            comparisonThroughMonth:
                $publicThroughMonth,

            bufferYear:
                $bufferYear,

            bufferMonth:
                $bufferMonth,

            status:
                $status,

            mode:
                'ytd',
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Factory: Custom User Selection
    |--------------------------------------------------------------------------
    */

    public static function forSelection(
        int $currentYear,
        int $currentMonth,
        int $comparisonYear,
        int $comparisonMonth,
        string $mode = 'ytd',
    ): self {
        return new self(
            publicThroughYear:
                $currentYear,

            publicThroughMonth:
                $currentMonth,

            comparisonYear:
                $comparisonYear,

            comparisonThroughMonth:
                $comparisonMonth,

            bufferYear:
                null,

            bufferMonth:
                null,

            status:
                'available',

            mode:
                strtolower(
                    trim($mode)
                ),
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Mode
    |--------------------------------------------------------------------------
    */

    public function isYtd(): bool
    {
        return $this->mode === 'ytd';
    }


    public function isMonthly(): bool
    {
        return $this->mode === 'monthly';
    }


    public function isFullYear(): bool
    {
        return $this->mode === 'full_year';
    }


    /*
    |--------------------------------------------------------------------------
    | Current Period
    |--------------------------------------------------------------------------
    */

    public function currentStart(): Carbon
    {
        if ($this->isMonthly()) {
            return Carbon::create(
                $this->publicThroughYear,
                $this->publicThroughMonth,
                1,
            )->startOfDay();
        }

        return Carbon::create(
            $this->publicThroughYear,
            1,
            1,
        )->startOfDay();
    }


    public function currentEnd(): Carbon
    {
        if ($this->isFullYear()) {
            return Carbon::create(
                $this->publicThroughYear,
                12,
                1,
            )->endOfMonth();
        }

        return Carbon::create(
            $this->publicThroughYear,
            $this->publicThroughMonth,
            1,
        )->endOfMonth();
    }


    /*
    |--------------------------------------------------------------------------
    | Comparison Period
    |--------------------------------------------------------------------------
    */

    public function comparisonStart(): Carbon
    {
        if ($this->isMonthly()) {
            return Carbon::create(
                $this->comparisonYear,
                $this->comparisonThroughMonth,
                1,
            )->startOfDay();
        }

        return Carbon::create(
            $this->comparisonYear,
            1,
            1,
        )->startOfDay();
    }


    public function comparisonEnd(): Carbon
    {
        if ($this->isFullYear()) {
            return Carbon::create(
                $this->comparisonYear,
                12,
                1,
            )->endOfMonth();
        }

        return Carbon::create(
            $this->comparisonYear,
            $this->comparisonThroughMonth,
            1,
        )->endOfMonth();
    }


    /*
    |--------------------------------------------------------------------------
    | Machine-readable Periods
    |--------------------------------------------------------------------------
    */

    public function currentPeriod(): string
    {
        return sprintf(
            '%04d-%02d',
            $this->publicThroughYear,
            $this->publicThroughMonth,
        );
    }


    public function comparisonPeriod(): string
    {
        return sprintf(
            '%04d-%02d',
            $this->comparisonYear,
            $this->comparisonThroughMonth,
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Snapshot Period Key
    |--------------------------------------------------------------------------
    */

    public function snapshotKey(): string
    {
        return sprintf(
            '%04d-%02d-vs-%04d-%02d-%s',
            $this->publicThroughYear,
            $this->publicThroughMonth,
            $this->comparisonYear,
            $this->comparisonThroughMonth,
            $this->mode,
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Labels
    |--------------------------------------------------------------------------
    */

    public function periodLabel(): string
    {
        return sprintf(
            '%d vs %d',
            $this->comparisonYear,
            $this->publicThroughYear,
        );
    }


    public function displayPeriodLabelEn(): string
    {
        if ($this->isMonthly()) {
            return sprintf(
                '%s %d',
                $this->monthName(
                    $this->publicThroughMonth,
                    'en',
                ),
                $this->publicThroughYear,
            );
        }

        if ($this->isFullYear()) {
            return sprintf(
                'Full Year %d',
                $this->publicThroughYear,
            );
        }

        return sprintf(
            'Data through %s %d',
            $this->monthName(
                $this->publicThroughMonth,
                'en',
            ),
            $this->publicThroughYear,
        );
    }


    public function displayPeriodLabelId(): string
    {
        if ($this->isMonthly()) {
            return sprintf(
                '%s %d',
                $this->monthName(
                    $this->publicThroughMonth,
                    'id',
                ),
                $this->publicThroughYear,
            );
        }

        if ($this->isFullYear()) {
            return sprintf(
                'Full Year %d',
                $this->publicThroughYear,
            );
        }

        return sprintf(
            'Data sampai %s %d',
            $this->monthName(
                $this->publicThroughMonth,
                'id',
            ),
            $this->publicThroughYear,
        );
    }


    public function comparisonPeriodLabelEn(): string
    {
        if ($this->isMonthly()) {
            return sprintf(
                '%s %d vs %s %d',
                $this->monthName(
                    $this->comparisonThroughMonth,
                    'en',
                ),
                $this->comparisonYear,
                $this->monthName(
                    $this->publicThroughMonth,
                    'en',
                ),
                $this->publicThroughYear,
            );
        }

        if ($this->isFullYear()) {
            return sprintf(
                'Full Year %d vs Full Year %d',
                $this->comparisonYear,
                $this->publicThroughYear,
            );
        }

        return sprintf(
            'Jan %d–%s %d vs Jan %d–%s %d',
            $this->comparisonYear,
            $this->monthName(
                $this->comparisonThroughMonth,
                'en',
            ),
            $this->comparisonYear,
            $this->publicThroughYear,
            $this->monthName(
                $this->publicThroughMonth,
                'en',
            ),
            $this->publicThroughYear,
        );
    }


    public function comparisonPeriodLabelId(): string
    {
        if ($this->isMonthly()) {
            return sprintf(
                '%s %d vs %s %d',
                $this->monthName(
                    $this->comparisonThroughMonth,
                    'id',
                ),
                $this->comparisonYear,
                $this->monthName(
                    $this->publicThroughMonth,
                    'id',
                ),
                $this->publicThroughYear,
            );
        }

        if ($this->isFullYear()) {
            return sprintf(
                'Full Year %d vs Full Year %d',
                $this->comparisonYear,
                $this->publicThroughYear,
            );
        }

        return sprintf(
            'Jan %d–%s %d vs Jan %d–%s %d',
            $this->comparisonYear,
            $this->monthName(
                $this->comparisonThroughMonth,
                'id',
            ),
            $this->comparisonYear,
            $this->publicThroughYear,
            $this->monthName(
                $this->publicThroughMonth,
                'id',
            ),
            $this->publicThroughYear,
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Buffer
    |--------------------------------------------------------------------------
    */

    public function hasBuffer(): bool
    {
        return $this->bufferYear !== null
            && $this->bufferMonth !== null;
    }


    public function bufferPeriod(): ?string
    {
        if (!$this->hasBuffer()) {
            return null;
        }

        return sprintf(
            '%04d-%02d',
            $this->bufferYear,
            $this->bufferMonth,
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }


    public function isBufferPromoted(): bool
    {
        return $this->status === 'buffer_promoted';
    }


    /*
    |--------------------------------------------------------------------------
    | Array Representation
    |--------------------------------------------------------------------------
    */

    public function toArray(): array
    {
        return [
            'public_through_year' =>
                $this->publicThroughYear,

            'public_through_month' =>
                $this->publicThroughMonth,

            'comparison_year' =>
                $this->comparisonYear,

            'comparison_through_month' =>
                $this->comparisonThroughMonth,

            'buffer_year' =>
                $this->bufferYear,

            'buffer_month' =>
                $this->bufferMonth,

            'buffer_period' =>
                $this->bufferPeriod(),

            'status' =>
                $this->status,

            'mode' =>
                $this->mode,

            'period' =>
                $this->periodLabel(),

            'current_period' =>
                $this->currentPeriod(),

            'comparison_period' =>
                $this->comparisonPeriod(),

            'snapshot_period_key' =>
                $this->snapshotKey(),

            'display_period_label_en' =>
                $this->displayPeriodLabelEn(),

            'display_period_label_id' =>
                $this->displayPeriodLabelId(),

            'comparison_period_label_en' =>
                $this->comparisonPeriodLabelEn(),

            'comparison_period_label_id' =>
                $this->comparisonPeriodLabelId(),

            'current_start' =>
                $this->currentStart()->toDateString(),

            'current_end' =>
                $this->currentEnd()->toDateString(),

            'comparison_start' =>
                $this->comparisonStart()->toDateString(),

            'comparison_end' =>
                $this->comparisonEnd()->toDateString(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function validate(): void
    {
        if ($this->publicThroughYear < 2000) {
            throw new InvalidArgumentException(
                'Public reporting year is invalid.'
            );
        }

        if (
            $this->publicThroughMonth < 1
            || $this->publicThroughMonth > 12
        ) {
            throw new InvalidArgumentException(
                'Public reporting month must be between 1 and 12.'
            );
        }

        if ($this->comparisonYear < 2000) {
            throw new InvalidArgumentException(
                'Comparison year is invalid.'
            );
        }

        if (
            $this->comparisonThroughMonth < 1
            || $this->comparisonThroughMonth > 12
        ) {
            throw new InvalidArgumentException(
                'Comparison reporting month must be between 1 and 12.'
            );
        }

        if (
            $this->bufferYear === null
            xor $this->bufferMonth === null
        ) {
            throw new InvalidArgumentException(
                'Buffer year and month must be provided together.'
            );
        }

        if (
            $this->bufferMonth !== null
            && (
                $this->bufferMonth < 1
                || $this->bufferMonth > 12
            )
        ) {
            throw new InvalidArgumentException(
                'Buffer month must be between 1 and 12.'
            );
        }

        if (
            !in_array(
                $this->mode,
                [
                    'ytd',
                    'monthly',
                    'full_year',
                ],
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Reporting period mode must be ytd, monthly, or full_year.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Month Names
    |--------------------------------------------------------------------------
    */

    protected function monthName(
        int $month,
        string $locale,
    ): string {
        $names = [
            'en' => [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ],

            'id' => [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ],
        ];

        return $names[$locale][$month]
            ?? $names['en'][$month];
    }
}