<?php

namespace App\Services\Home;

use App\Repositories\Trade\TradeStatisticsRepository;
use App\Services\Trade\ExecutiveReport\ExecutiveReportService;

class HomeTradeService
{
    public function __construct(
        protected TradeStatisticsRepository $tradeRepository,
        protected ExecutiveReportService $executiveReportService,
    ) {
    }

    public function getData(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Trade Intelligence
        |--------------------------------------------------------------------------
        */

        $garmentTradeData = $this->tradeRepository->garmentTradeSummary();

        $topProducts = $this->tradeRepository->topGarmentProducts();

        /*
        |--------------------------------------------------------------------------
        | Fiber Intelligence
        |--------------------------------------------------------------------------
        */

        $fiberData = $this->getRawFiberData();

        if (!auth()->check()) {
            $fiberData = collect($fiberData)
                ->map(function ($item, $index) {

                    if ($index > 3) {
                        $item['cotton_vol'] = 0;
                        $item['cotton_val'] = 0;
                        $item['syn_vol'] = 0;
                        $item['syn_val'] = 0;
                    }

                    return $item;
                })
                ->all();
        }

        /*
        |--------------------------------------------------------------------------
        | Executive Report
        |--------------------------------------------------------------------------
        */

        $executiveReport = $this->executiveReportService->build([
            'title' => 'Indonesia Apparel & Made-up Textile Export Performance',
            'subtitle' => 'HS 61–63',
            'trade_flow' => 'export',
            'base_year' => 2025,
            'compare_year' => 2026,
            'months' => [1, 2, 3, 4],
            'hs_prefix' => ['61', '62', '63'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [
            'garmentTrade'      => $garmentTradeData,
            'totalGarment'      => (float) ($garmentTradeData->export_pcs ?? 0),
            'topProducts'       => $topProducts,
            'fiberIntelligence' => $fiberData,
            'report'            => $executiveReport,
        ];
    }

    /**
     * Fiber Intelligence Dataset
     */
    private function getRawFiberData(): array
    {
        return [
            ['year' => '2019', 'cotton_vol' => 320000, 'cotton_val' => 540000000, 'syn_vol' => 450000, 'syn_val' => 620000000],
            ['year' => '2020', 'cotton_vol' => 290000, 'cotton_val' => 480000000, 'syn_vol' => 410000, 'syn_val' => 580000000],
            ['year' => '2021', 'cotton_vol' => 340000, 'cotton_val' => 610000000, 'syn_vol' => 480000, 'syn_val' => 690000000],
            ['year' => '2022', 'cotton_vol' => 310000, 'cotton_val' => 590000000, 'syn_vol' => 460000, 'syn_val' => 660000000],
            ['year' => '2023', 'cotton_vol' => 330000, 'cotton_val' => 620000000, 'syn_vol' => 490000, 'syn_val' => 710000000],
            ['year' => '2024', 'cotton_vol' => 350000, 'cotton_val' => 650000000, 'syn_vol' => 520000, 'syn_val' => 750000000],
            ['year' => '2025', 'cotton_vol' => 370000, 'cotton_val' => 690000000, 'syn_vol' => 550000, 'syn_val' => 800000000],
        ];
    }
}