<?php

declare(strict_types=1);

namespace App\Services\Trade;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TradeReportingPeriodResolver
{
    /*
    |--------------------------------------------------------------------------
    | DIGESTEX REPORTING RULES
    |--------------------------------------------------------------------------
    |
    | The reporting period is determined from actual validated data
    | available in trade_statistics.
    |
    | Normal behavior:
    |   Latest available month - 1 month buffer = display period
    |
    | Adaptive behavior:
    |   On/after the 28th of the month, if the next expected month
    |   is still unavailable, the current buffer may be promoted.
    |
    */

    /**
     * Normal reporting buffer.
     */
    public const DEFAULT_BUFFER_MONTHS = 1;

    /**
     * Digestex monthly operational checkpoint.
     *
     * On or after this day, the current buffer may be promoted
     * when the expected next month is still unavailable.
     */
    public const DEFAULT_PROMOTION_DAY = 28;

    /**
     * Source table.
     */
    protected const TABLE = 'trade_statistics';


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected int $bufferMonths = self::DEFAULT_BUFFER_MONTHS,
        protected int $promotionDay = self::DEFAULT_PROMOTION_DAY,
    ) {
        if ($this->bufferMonths < 0) {
            throw new RuntimeException(
                'Trade reporting buffer must be zero or greater.'
            );
        }

        if (
            $this->promotionDay < 1 ||
            $this->promotionDay > 31
        ) {
            throw new RuntimeException(
                'Trade reporting promotion day must be between 1 and 31.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve the current Digestex reporting period.
     *
     * Important:
     * - Uses the latest month actually present in trade_statistics.
     * - Does not assume a fixed government release date.
     * - Applies a normal one-month buffer.
     * - Allows buffer promotion from the 28th onward when
     *   the next expected month is still unavailable.
     */
    public function resolve(?Carbon $now = null): array
    {
        $now ??= now();

        $latestAvailable =
            $this->latestAvailablePeriod();

        /*
        |--------------------------------------------------------------------------
        | No Data
        |--------------------------------------------------------------------------
        */

        if ($latestAvailable === null) {
            return $this->emptyResult();
        }

        $latestPeriod = Carbon::create(
            $latestAvailable['year'],
            $latestAvailable['month'],
            1
        )->startOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Normal One-Month Buffer
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Latest available = June 2026
        | Normal display   = May 2026
        |
        */

        $normalDisplayPeriod = $latestPeriod
            ->copy()
            ->subMonths(
                $this->bufferMonths
            );


        /*
        |--------------------------------------------------------------------------
        | Next Expected Period
        |--------------------------------------------------------------------------
        */

        $nextExpectedPeriod = $latestPeriod
            ->copy()
            ->addMonth()
            ->startOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Operational Checkpoint
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Latest available = June 2026
        | Next expected    = July 2026
        |
        | On/after 28th:
        | If July is still unavailable, June may be promoted.
        |
        */

        $checkpointReached =
            $now->day >= $this->promotionDay;


        /*
        |--------------------------------------------------------------------------
        | Adaptive Buffer Promotion
        |--------------------------------------------------------------------------
        */

        $bufferPromoted =
            $checkpointReached;

        if ($bufferPromoted) {

            $displayPeriod =
                $latestPeriod;

            $bufferStatus =
                'buffer_promoted';

            $dataStatus =
                'awaiting_latest_data';

        } else {

            $displayPeriod =
                $normalDisplayPeriod;

            $bufferStatus =
                'buffer';

            $dataStatus =
                'available';
        }


        /*
        |--------------------------------------------------------------------------
        | Comparison Period
        |--------------------------------------------------------------------------
        |
        | Same month range in the previous year.
        |--------------------------------------------------------------------------
        */

        $comparisonPeriod =
            $displayPeriod
                ->copy()
                ->subYear()
                ->startOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Reporting Labels
        |--------------------------------------------------------------------------
        */

        $periodLabel =
            $comparisonPeriod->year .
            ' vs ' .
            $displayPeriod->year;


        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Latest Available
            |--------------------------------------------------------------------------
            */

            'latest_available_year' =>
                $latestPeriod->year,

            'latest_available_month' =>
                $latestPeriod->month,

            'latest_available_period' =>
                $latestPeriod->format('Y-m'),


            /*
            |--------------------------------------------------------------------------
            | Display Period
            |--------------------------------------------------------------------------
            */

            'display_through_year' =>
                $displayPeriod->year,

            'display_through_month' =>
                $displayPeriod->month,

            'display_through_period' =>
                $displayPeriod->format('Y-m'),


            /*
            |--------------------------------------------------------------------------
            | Comparison Period
            |--------------------------------------------------------------------------
            */

            'comparison_year' =>
                $comparisonPeriod->year,

            'comparison_through_month' =>
                $comparisonPeriod->month,

            'comparison_through_period' =>
                $comparisonPeriod->format('Y-m'),


            /*
            |--------------------------------------------------------------------------
            | Buffer
            |--------------------------------------------------------------------------
            */

            'buffer_months' =>
                $this->bufferMonths,

            'buffer_month' =>
                $latestPeriod->month,

            'promotion_day' =>
                $this->promotionDay,

            'checkpoint_reached' =>
                $checkpointReached,

            'buffer_status' =>
                $bufferStatus,

            'data_status' =>
                $dataStatus,


            /*
            |--------------------------------------------------------------------------
            | Next Expected Period
            |--------------------------------------------------------------------------
            */

            'next_expected_year' =>
                $nextExpectedPeriod->year,

            'next_expected_month' =>
                $nextExpectedPeriod->month,

            'next_expected_period' =>
                $nextExpectedPeriod->format('Y-m'),


            /*
            |--------------------------------------------------------------------------
            | Labels
            |--------------------------------------------------------------------------
            |
            | Digestex standard:
            | 2025 vs 2026
            |--------------------------------------------------------------------------
            */

            'period_label_en' =>
                $periodLabel,

            'period_label_id' =>
                $periodLabel,


            /*
            |--------------------------------------------------------------------------
            | Display Labels
            |--------------------------------------------------------------------------
            */

            'display_period_label_en' =>
                $this->buildDisplayLabel(
                    $displayPeriod,
                    'en'
                ),

            'display_period_label_id' =>
                $this->buildDisplayLabel(
                    $displayPeriod,
                    'id'
                ),


            /*
            |--------------------------------------------------------------------------
            | Comparison Labels
            |--------------------------------------------------------------------------
            */

            'comparison_period_label_en' =>
                $this->buildComparisonLabel(
                    $displayPeriod,
                    $comparisonPeriod
                ),

            'comparison_period_label_id' =>
                $this->buildComparisonLabel(
                    $displayPeriod,
                    $comparisonPeriod
                ),


            /*
            |--------------------------------------------------------------------------
            | Machine-Readable Boundaries
            |--------------------------------------------------------------------------
            */

            'current_start' =>
                $displayPeriod
                    ->copy()
                    ->startOfYear()
                    ->format('Y-m-d'),

            'current_end' =>
                $displayPeriod
                    ->copy()
                    ->endOfMonth()
                    ->format('Y-m-d'),

            'comparison_start' =>
                $comparisonPeriod
                    ->copy()
                    ->startOfYear()
                    ->format('Y-m-d'),

            'comparison_end' =>
                $comparisonPeriod
                    ->copy()
                    ->endOfMonth()
                    ->format('Y-m-d'),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC: Snapshot Period Helper
    |--------------------------------------------------------------------------
    */

    public function snapshotPeriod(
        ?Carbon $now = null
    ): array {
        $period =
            $this->resolve($now);

        return [
            'current_year' =>
                $period['display_through_year'],

            'comparison_year' =>
                $period['comparison_year'],

            'through_month' =>
                $period['display_through_month'],

            'comparison_through_month' =>
                $period['comparison_through_month'],

            'period_label_en' =>
                $period['period_label_en'],

            'period_label_id' =>
                $period['period_label_id'],

            'display_period_label_en' =>
                $period['display_period_label_en'],

            'display_period_label_id' =>
                $period['display_period_label_id'],

            'comparison_period_label_en' =>
                $period['comparison_period_label_en'],

            'comparison_period_label_id' =>
                $period['comparison_period_label_id'],

            'current_start' =>
                $period['current_start'],

            'current_end' =>
                $period['current_end'],

            'comparison_start' =>
                $period['comparison_start'],

            'comparison_end' =>
                $period['comparison_end'],

            'latest_available_period' =>
                $period['latest_available_period'],

            'buffer_status' =>
                $period['buffer_status'],

            'data_status' =>
                $period['data_status'],

            'promotion_day' =>
                $period['promotion_day'],

            'checkpoint_reached' =>
                $period['checkpoint_reached'],

            'next_expected_period' =>
                $period['next_expected_period'],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Available Period
    |--------------------------------------------------------------------------
    |
    | The resolver uses the latest valid month actually present
    | in trade_statistics.
    |
    */

    protected function latestAvailablePeriod(): ?array
    {
        $row = DB::table(self::TABLE)
            ->select(
                'year',
                'month'
            )
            ->whereNotNull('year')
            ->whereNotNull('month')
            ->whereBetween(
                'month',
                [1, 12]
            )
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        if ($row === null) {
            return null;
        }

        return [
            'year' =>
                (int) $row->year,

            'month' =>
                (int) $row->month,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Display Label
    |--------------------------------------------------------------------------
    */

    protected function buildDisplayLabel(
        Carbon $period,
        string $locale
    ): string {
        $month =
            $this->monthName(
                $period->month,
                $locale
            );

        return $locale === 'en'
            ? 'Data through ' .
                $month .
                ' ' .
                $period->year
            : 'Data sampai ' .
                $month .
                ' ' .
                $period->year;
    }


    /*
    |--------------------------------------------------------------------------
    | Comparison Label
    |--------------------------------------------------------------------------
    */

    protected function buildComparisonLabel(
        Carbon $currentPeriod,
        Carbon $comparisonPeriod
    ): string {
        $currentMonth =
            $this->monthName(
                $currentPeriod->month,
                'en'
            );

        $comparisonMonth =
            $this->monthName(
                $comparisonPeriod->month,
                'en'
            );

        return sprintf(
            'Jan %d–%s %d vs Jan %d–%s %d',
            $comparisonPeriod->year,
            $comparisonMonth,
            $comparisonPeriod->year,
            $currentPeriod->year,
            $currentMonth,
            $currentPeriod->year
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Month Names
    |--------------------------------------------------------------------------
    */

    protected function monthName(
        int $month,
        string $locale
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
            ?? (string) $month;
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Result
    |--------------------------------------------------------------------------
    */

    protected function emptyResult(): array
    {
        return [

            'latest_available_year' =>
                null,

            'latest_available_month' =>
                null,

            'latest_available_period' =>
                null,


            'display_through_year' =>
                null,

            'display_through_month' =>
                null,

            'display_through_period' =>
                null,


            'comparison_year' =>
                null,

            'comparison_through_month' =>
                null,

            'comparison_through_period' =>
                null,


            'buffer_months' =>
                $this->bufferMonths,

            'buffer_month' =>
                null,

            'promotion_day' =>
                $this->promotionDay,

            'checkpoint_reached' =>
                false,

            'buffer_status' =>
                'unavailable',

            'data_status' =>
                'no_data',


            'next_expected_year' =>
                null,

            'next_expected_month' =>
                null,

            'next_expected_period' =>
                null,


            'period_label_en' =>
                null,

            'period_label_id' =>
                null,


            'display_period_label_en' =>
                'No validated trade data available',

            'display_period_label_id' =>
                'Belum tersedia data perdagangan tervalidasi',


            'comparison_period_label_en' =>
                null,

            'comparison_period_label_id' =>
                null,


            'current_start' =>
                null,

            'current_end' =>
                null,

            'comparison_start' =>
                null,

            'comparison_end' =>
                null,
        ];
    }
}