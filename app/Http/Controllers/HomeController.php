<?php

namespace App\Http\Controllers;

use App\Services\Home\HomeDirectoryService;
use App\Services\Home\HomeIntelligenceService;
use App\Services\Home\HomeMarketService;
use App\Services\Home\HomePartnerService;
use App\Services\Home\HomeTradeService;

use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{ // <-- Kurung kurawal pembuka class dipindah ke sini agar rapi

    /**
     * Menampilkan dashboard utama Digestex Global dengan pemetaan Multi-Service.
     */
    public function index(
        HomeMarketService $market,
        HomeTradeService $trade,
        HomeDirectoryService $directory,
        HomePartnerService $partner,
        HomeIntelligenceService $intelligence
    ): Response {

    $homeStart = microtime(true);

    $start = microtime(true);
    $marketData = $market->getData();
    logger()->info('HomeMarketService', [
        'time' => round((microtime(true) - $start) * 1000, 2) . ' ms'
    ]);

    $start = microtime(true);
    $tradeData = $trade->getData();
    logger()->info('HomeTradeService', [
        'time' => round((microtime(true) - $start) * 1000, 2) . ' ms'
    ]);

    $start = microtime(true);
    $directoryData = $directory->getData();
    logger()->info('HomeDirectoryService', [
        'time' => round((microtime(true) - $start) * 1000, 2) . ' ms'
    ]);

    $start = microtime(true);
    $partnerData = $partner->getData();
    logger()->info('HomePartnerService', [
        'time' => round((microtime(true) - $start) * 1000, 2) . ' ms'
    ]);

    $start = microtime(true);
    $intelligenceData = $intelligence->getData();
    logger()->info('HomeIntelligenceService', [
        'time' => round((microtime(true) - $start) * 1000, 2) . ' ms'
    ]);

    logger()->info('TOTAL HOME LOAD', [
        'time' => round((microtime(true) - $homeStart) * 1000, 2) . ' ms'
    ]);
        return Inertia::render('Home', array_merge(
            // $market->getData(),
            // $trade->getData(),
            // $directory->getData(),
            // $partner->getData(),
            // $intelligence->getData()
            $marketData,
        $tradeData,
        $directoryData,
        $partnerData,
        $intelligenceData
        ));
    }

    /**
     * Menampilkan halaman About menggunakan Inertia.
     */
    public function about(): Response
    {
        // Pastikan Anda sudah membuat file komponen Vue/React "About" di folder Pages Anda
        return Inertia::render('About/Index');
    }
}