<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Home\HomeDirectoryService;
use App\Services\Home\HomeIntelligenceService;
use App\Services\Home\HomeMarketService;
use App\Services\Home\HomePartnerService;
use App\Services\Trade\Cache\TradeDashboardCacheService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * Digestex Home Dashboard
     * --------------------------------------------------------------------------
     */
    public function index(

        HomeMarketService $market,
        TradeDashboardCacheService $tradeCache,
        HomeDirectoryService $directory,
        HomePartnerService $partner,
        HomeIntelligenceService $intelligence,

    ): Response {

        $homeStart = microtime(true);

        /*
        |--------------------------------------------------------------------------
        | Market
        |--------------------------------------------------------------------------
        */

        $start = microtime(true);

        $marketData = $market->getData();

        logger()->info('HomeMarketService', [

            'time' => round(
                (microtime(true) - $start) * 1000,
                2
            ) . ' ms',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Trade (Cached)
        |--------------------------------------------------------------------------
        */

        $start = microtime(true);

        $tradeData = $tradeCache->home();

        logger()->info('TradeDashboardCacheService', [

            'time' => round(
                (microtime(true) - $start) * 1000,
                2
            ) . ' ms',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Directory
        |--------------------------------------------------------------------------
        */

        $start = microtime(true);

        $directoryData = $directory->getData();

        logger()->info('HomeDirectoryService', [

            'time' => round(
                (microtime(true) - $start) * 1000,
                2
            ) . ' ms',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Partner
        |--------------------------------------------------------------------------
        */

        $start = microtime(true);

        $partnerData = $partner->getData();

        logger()->info('HomePartnerService', [

            'time' => round(
                (microtime(true) - $start) * 1000,
                2
            ) . ' ms',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Intelligence
        |--------------------------------------------------------------------------
        */

        $start = microtime(true);

        $intelligenceData = $intelligence->getData();

        logger()->info('HomeIntelligenceService', [

            'time' => round(
                (microtime(true) - $start) * 1000,
                2
            ) . ' ms',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        logger()->info('TOTAL HOME LOAD', [

            'time' => round(
                (microtime(true) - $homeStart) * 1000,
                2
            ) . ' ms',

        ]);

        return Inertia::render(

            'Home',

            array_merge(

                $marketData,

                $tradeData,

                $directoryData,

                $partnerData,

                $intelligenceData,

            )

        );
    }

    /**
     * About Page
     */
    public function about(): Response
    {
        return Inertia::render('About/Index');
    }
}