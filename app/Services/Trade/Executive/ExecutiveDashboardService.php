<?php

declare(strict_types=1);

namespace App\Services\Trade\Executive;

use App\Services\Trade\Analytics\CountryAnalyticsService;
use App\Services\Trade\Intelligence\AIExecutiveRecommendationService;
use App\Services\Trade\Intelligence\CountryBadgeService;
use App\Services\Trade\Intelligence\CountryScoreService;
use App\Services\Trade\Intelligence\GlobalTextileRadarService;
use App\Services\Trade\Intelligence\GlobalTradeEarlyWarningService;
use App\Services\Trade\Executive\SectorOverviewService;
use App\Services\Trade\Executive\SupplyChainIntelligenceService;
use App\Services\Trade\Executive\ExecutiveExportMonitorService;
use App\Services\Trade\Executive\TopHsLeaderboardService;

class ExecutiveDashboardService
{
    public function __construct(
        protected CountryAnalyticsService $countryService,
        protected CountryBadgeService $badgeService,
        protected CountryScoreService $scoreService,
        protected GlobalTradeEarlyWarningService $warningService,
        protected GlobalTextileRadarService $radarService,
        protected AIExecutiveRecommendationService $aiService,
        protected SectorSummaryService $summaryService,
        protected SectorOverviewService $sectorOverviewService,
        protected SupplyChainIntelligenceService $supplyChainService,
        protected ExecutiveExportMonitorService $exportMonitorService,
        protected TopHsLeaderboardService $hsLeaderboardService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Build Executive Dashboard
     * --------------------------------------------------------------------------
     */
    public function build(
        array $filters = []
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Top Countries
        |--------------------------------------------------------------------------
        */

        $countries = collect(
            $this->countryService
                ->topCountries($filters)
        );

        /*
        |--------------------------------------------------------------------------
        | Country Intelligence
        |--------------------------------------------------------------------------
        */

        $countries = $countries

            ->map(function ($country) {

                /*
                |--------------------------------------------------------------------------
                | Badges
                |--------------------------------------------------------------------------
                */

                $country['badges'] = collect(
                    $this->badgeService
                        ->generate($country)
                )

                ->map(fn ($badge) => [

                    'name' => $badge,

                ])

                ->values()

                ->toArray();

                /*
                |--------------------------------------------------------------------------
                | Score
                |--------------------------------------------------------------------------
                */

                $country['score'] =

                    $this->scoreService
                        ->calculate($country);

                return $country;
            });

        /*
        |--------------------------------------------------------------------------
        | Intelligence Layer
        |--------------------------------------------------------------------------
        */

        $earlyWarning =

            $this->warningService
                ->analyze(
                    $countries->toArray()
                );

        $globalRadar =

            $this->radarService
                ->build(
                    $countries->toArray()
                );

        $recommendations =

            $this->aiService
                ->generate(

                    $countries->toArray(),

                    $earlyWarning,

                    $globalRadar,

                );

        /*
        |--------------------------------------------------------------------------
        | Country Leaderboard
        |--------------------------------------------------------------------------
        */

        $leaderboard = $countries

            ->sortByDesc(
                fn ($country) =>

                    $country['score']['score']
            )

            ->take(10)

            ->values();

        $topCountry = $leaderboard->first();

        /*
        |--------------------------------------------------------------------------
        | Sector Configuration
        |--------------------------------------------------------------------------
        */

        $sector = $filters['sector'] ?? 'textile';

        $sectorConfig = config(
            "textile_sectors.{$sector}"
        );
                /*
        |--------------------------------------------------------------------------
        | Executive Overview
        |--------------------------------------------------------------------------
        */

        $executiveOverview = [

            'sector' => $sector,

            'sector_slug' => $sector,

            'sector_name' =>

                $sectorConfig['title']
                ?? 'All Textile',

            'sector_icon' =>

                $sectorConfig['icon']
                ?? '🌐',

            'sector_hs' =>

                implode(
                    ', ',
                    $sectorConfig['hs'] ?? []
                ),

            'sector_total_hs' =>

                count(
                    $sectorConfig['hs'] ?? []
                ),

            'dataPeriod' =>

                $filters['data_period']

                ?? 'January-April 2026',

            'totalCountries' =>

                $countries->count(),

            'growthMarkets' =>

                $countries

                    ->where(
                        'growth',
                        '>',
                        0
                    )

                    ->count(),

            'criticalAlerts' =>

                data_get(
                    $earlyWarning,
                    'summary.critical',
                    0
                ),

            'executiveHeadline' =>

                sprintf(
                    '%s remains the highest scoring market for %s.',
                    $topCountry['country_name_en']
                        ?? 'N/A',
                    $sectorConfig['title']
                        ?? 'All Textile'
                ),
        ];

$sectorOverview =

    $this->sectorOverviewService
        ->build($sector);
        
    
        /*
        |--------------------------------------------------------------------------
        | Sector Summary
        |--------------------------------------------------------------------------
        */

        $sectorSummary =

            $this->summaryService
                ->build($filters);

        /*
        |--------------------------------------------------------------------------
        | Sector Navigation
        |--------------------------------------------------------------------------
        */

        $sectors = collect(
            config('textile_sectors')
        )

        ->map(function (
            $sector,
            $slug
        ) {

            return [

                'slug' => $slug,

                'title' =>
                    $sector['title'],

                'icon' =>
                    $sector['icon'],

                'hs' =>
                    $sector['hs'],

            ];
        })

        ->values()

        ->toArray();

       $sectorOverview =

    $this->sectorOverviewService
        ->build($sector);

$supplyChain =

    $this->supplyChainService
        ->build($sector); 

    $exportMonitor =

    $this->exportMonitorService
        ->build(
            $filters
        );
    $hsLeaderboard =

    $this->hsLeaderboardService
        ->build(
            $filters
        );
        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [

    'executiveOverview' => $executiveOverview,
    'sectorSummary' => $sectorSummary,

    'sectorOverview' => $sectorOverview,

    'sectors' => $sectors,
    
    'supplyChain' => $supplyChain,

    'exportMonitor' => $exportMonitor,

    'hsLeaderboard' => $hsLeaderboard,

    'earlyWarning' =>

        $earlyWarning,

    'globalRadar' =>

        $globalRadar,

    'recommendations' =>

        $recommendations,

    'countries' =>

        $leaderboard
            ->toArray(),
];
    }
}