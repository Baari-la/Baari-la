<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\GarmentTradeIntelligenceService;
use App\Services\Trade\TradeAvailablePeriodService;
use App\Services\Trade\TradeReportingPeriod;
use App\Services\TradeIntelligence\Snapshot\HistoricalSnapshotAssembler;
use App\Services\TradeIntelligence\Snapshot\SnapshotAssembler;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GarmentIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Historical Year Range
    |--------------------------------------------------------------------------
    */

    private const HISTORICAL_MIN_YEAR = 2019;

    private const HISTORICAL_MAX_YEAR = 2024;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected GarmentTradeIntelligenceService $garmentService,

        protected TradeAvailablePeriodService $periodService,

        protected HistoricalSnapshotAssembler $historicalSnapshotAssembler,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Available Period
        |--------------------------------------------------------------------------
        */

        $available =
            $this->periodService->forSector(
                'garment'
            );


        /*
        |--------------------------------------------------------------------------
        | Requested Selection
        |--------------------------------------------------------------------------
        */

        $latest =
            $available['latest'] ?? null;

        $defaultYear =
            (int) (
                $latest['year']
                ?? date('Y')
            );

        $defaultMonth =
            (int) (
                $latest['month']
                ?? 1
            );

        $year =
            (int) $request->query(
                'year',
                $defaultYear
            );

        $month =
            (int) $request->query(
                'month',
                $defaultMonth
            );

        $compareYear =
            (int) $request->query(
                'compare_year',
                $year - 1
            );

        $compareMonth =
            (int) $request->query(
                'compare_month',
                $month
            );

        $mode =
            (string) $request->query(
                'mode',
                'ytd'
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Mode
        |--------------------------------------------------------------------------
        |
        | Single-period path retains its existing modes.
        |
        */

        if (
            !in_array(
                $mode,
                [
                    'ytd',
                    'monthly',
                    'full_year',
                ],
                true
            )
        ) {
            $mode = 'ytd';
        }


        /*
        |--------------------------------------------------------------------------
        | Available Years
        |--------------------------------------------------------------------------
        */

        $availableYears =
            array_map(
                'intval',
                $available['years'] ?? []
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Current Year
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $year,
                $availableYears,
                true
            )
        ) {
            $year =
                $defaultYear;
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Comparison Year
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $compareYear,
                $availableYears,
                true
            )
        ) {

            $compareYear =
                $year - 1;

            if (
                !in_array(
                    $compareYear,
                    $availableYears,
                    true
                )
            ) {
                $compareYear =
                    $year;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Historical Yearly
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | 2019–2024 is NOT a 12-month-per-year calculation.
        |
        | HistoricalYearlyQueryBuilder directly aggregates:
        |
        |     year + trade_flow
        |
        | from trade_statistics.
        |
        | Therefore month is irrelevant here.
        |
        | We deliberately do NOT use:
        |
        |     mode=full_year
        |
        | to determine historical yearly.
        |
        */

        $isHistoricalYearly =
            $year >= self::HISTORICAL_MIN_YEAR
            &&
            $year <= self::HISTORICAL_MAX_YEAR;


        /*
        |--------------------------------------------------------------------------
        | Historical Yearly Pipeline
        |--------------------------------------------------------------------------
        */

        if (
            $isHistoricalYearly
        ) {

            \Log::info(
                'GARMENT DEBUG: HISTORICAL YEARLY',
                [
                    'year' =>
                        $year,

                    'compare_year' =>
                        $compareYear,

                    'month' =>
                        $month,

                    'mode' =>
                        $mode,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | Build Historical Reporting Period
            |--------------------------------------------------------------------------
            |
            | The historical builder uses the year carried by the
            | reporting period, while the actual SQL aggregation is
            | annual and independent from monthly accumulation.
            |
            | We keep the period object only as the common contract
            | between controller and historical pipeline.
            |
            */

            $period =
                TradeReportingPeriod::forSelection(
                    currentYear:
                        $year,

                    currentMonth:
                        12,

                    comparisonYear:
                        $compareYear,

                    comparisonMonth:
                        12,

                    mode:
                        'full_year',
                );


            /*
            |--------------------------------------------------------------------------
            | Historical Snapshot
            |--------------------------------------------------------------------------
            */

            $garment =
                $this->historicalSnapshotAssembler->assemble(
                    $period
                );

        } else {

            /*
            |--------------------------------------------------------------------------
            | CURRENT / SINGLE PERIOD PATH
            |--------------------------------------------------------------------------
            |
            | DO NOT CHANGE THE EXISTING BUSINESS LOGIC.
            |
            | This remains the proven path for:
            |
            |     2025+
            |
            | including:
            |
            |     monthly
            |     ytd
            |     full_year
            |
            */

            $currentAvailableMonths =
                array_map(
                    'intval',
                    $available['months'][$year] ?? []
                );

            $compareAvailableMonths =
                array_map(
                    'intval',
                    $available['months'][$compareYear] ?? []
                );


            /*
            |--------------------------------------------------------------------------
            | Validate Current Month
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $month,
                    $currentAvailableMonths,
                    true
                )
            ) {
                $month =
                    !empty(
                        $currentAvailableMonths
                    )
                        ? max(
                            $currentAvailableMonths
                        )
                        : 1;
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Comparison Month
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $compareMonth,
                    $compareAvailableMonths,
                    true
                )
            ) {

                if (
                    in_array(
                        $month,
                        $compareAvailableMonths,
                        true
                    )
                ) {

                    $compareMonth =
                        $month;

                } elseif (
                    !empty(
                        $compareAvailableMonths
                    )
                ) {

                    $compareMonth =
                        min(
                            $month,
                            max(
                                $compareAvailableMonths
                            )
                        );

                } else {

                    $compareMonth =
                        $month;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Full Year Availability
            |--------------------------------------------------------------------------
            */

            $currentYearComplete =
                in_array(
                    12,
                    $currentAvailableMonths,
                    true
                );

            $compareYearComplete =
                in_array(
                    12,
                    $compareAvailableMonths,
                    true
                );

            $fullYearAvailable =
                $currentYearComplete
                &&
                $compareYearComplete;


            /*
            |--------------------------------------------------------------------------
            | Enforce Full Year
            |--------------------------------------------------------------------------
            |
            | This remains untouched for the existing
            | current/single-period path.
            |
            */

            if (
                $mode === 'full_year'
            ) {

                if (
                    $fullYearAvailable
                ) {

                    $month =
                        12;

                    $compareMonth =
                        12;

                } else {

                    $mode =
                        'ytd';

                    $month =
                        !empty(
                            $currentAvailableMonths
                        )
                            ? max(
                                $currentAvailableMonths
                            )
                            : 1;

                    if (
                        in_array(
                            $month,
                            $compareAvailableMonths,
                            true
                        )
                    ) {

                        $compareMonth =
                            $month;

                    } elseif (
                        !empty(
                            $compareAvailableMonths
                        )
                    ) {

                        $compareMonth =
                            min(
                                $month,
                                max(
                                    $compareAvailableMonths
                                )
                            );

                    } else {

                        $compareMonth =
                            $month;
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Build Existing Reporting Period
            |--------------------------------------------------------------------------
            */

            $period =
                TradeReportingPeriod::forSelection(
                    currentYear:
                        $year,

                    currentMonth:
                        $month,

                    comparisonYear:
                        $compareYear,

                    comparisonMonth:
                        $compareMonth,

                    mode:
                        $mode,
                );


            /*
            |--------------------------------------------------------------------------
            | Resolve SnapshotAssembler Lazily
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | Do NOT put SnapshotAssembler in the constructor.
            |
            | Laravel would immediately resolve:
            |
            | SnapshotAssembler
            |     -> SnapshotMetadataBuilder
            |         -> string $sector
            |
            | and fail before historical routing occurs.
            |
            */

            $snapshotAssembler =
                app(
                    SnapshotAssembler::class
                );


            /*
            |--------------------------------------------------------------------------
            | Existing Single Period Pipeline
            |--------------------------------------------------------------------------
            */

            $garment =
                $snapshotAssembler->assemble(
                    [],
                    $period
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Render
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Trade/GarmentIntelligence',
            [

                'garment' =>
                    $garment,


                /*
                |--------------------------------------------------------------------------
                | Period Selection
                |--------------------------------------------------------------------------
                */

                'periodSelection' => [

                    'year' =>
                        $year,

                    'month' =>
                        $month,

                    'compare_year' =>
                        $compareYear,

                    'compare_month' =>
                        $compareMonth,

                    'mode' =>
                        $mode,
                ],


                /*
                |--------------------------------------------------------------------------
                | Available Periods
                |--------------------------------------------------------------------------
                */

                'availablePeriods' =>
                    $available,


                /*
                |--------------------------------------------------------------------------
                | Page
                |--------------------------------------------------------------------------
                */

                'page' => [

                    'title' =>
                        'Garment Intelligence',

                    'description' =>
                        'Garment Trade Intelligence',
                ],
            ]
        );
    }
}