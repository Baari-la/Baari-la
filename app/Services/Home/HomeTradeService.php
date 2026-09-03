<?php

declare(strict_types=1);

namespace App\Services\Home;

use App\Repositories\Trade\Dashboard\HomeTradeRepository;
use App\Services\Trade\GarmentConversionService;

class HomeTradeService
{
    public function __construct(
        protected HomeTradeRepository $repository,
        protected GarmentConversionService $garmentConversionService,
        protected HomeExecutiveSnapshotService $executiveSnapshotService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Home Dashboard Data
     * --------------------------------------------------------------------------
     *
     * IMPORTANT:
     * The Home page must stay lightweight.
     *
     * Full Executive Reports (fiber/yarn/fabric/apparel) belong to
     * the dedicated Trade Intelligence page and must NOT be rebuilt
     * during every Home request.
     */
    public function getData(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Garment Trade
        |--------------------------------------------------------------------------
        */

        $tradeRows = $this->repository->tradeSummary([
            'hs_prefix' => ['61', '62'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Estimated Garment Pieces
        |--------------------------------------------------------------------------
        |
        | Canonical garment conversion:
        |
        | - HS-8 specific
        | - ACTIVE factor only
        | - No legacy multiplier
        | - No fallback multiplier
        | - KG_TO_PCS only
        | - HS-8 without an ACTIVE factor are excluded
        |
        | This ensures Home Dashboard uses exactly the same
        | conversion governance as Garment Trade Intelligence.
        |--------------------------------------------------------------------------
        */

        $totalPieces = 0.0;

        $convertibleHs8Count = 0;
        $nonConvertibleHs8Count = 0;

        foreach ($tradeRows as $row) {

            if ($row->tipe_arus !== 'ekspor') {
                continue;
            }

            $hsCode = trim(
                (string) $row->hs_code
            );

            $tradeVolume = (float) $row->vol_2025;

            $conversion =
                $this->garmentConversionService->kgToPcs(
                    $hsCode,
                    $tradeVolume
                );

            if (
                ($conversion['status'] ?? null)
                === 'CONVERTED'
            ) {
                $totalPieces +=
                    (float) $conversion['output_quantity'];

                $convertibleHs8Count++;
            } else {
                $nonConvertibleHs8Count++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Summary
        |--------------------------------------------------------------------------
        */

        $heroSummary =
            $this->repository->heroSummary();

        /*
        |--------------------------------------------------------------------------
        | Top Products
        |--------------------------------------------------------------------------
        |
        | Keep empty for now.
        | Full product intelligence is handled by Trade Intelligence.
        |--------------------------------------------------------------------------
        */

        $topProducts = [];

        /*
        |--------------------------------------------------------------------------
        | Market Highlights
        |--------------------------------------------------------------------------
        */

        $marketHighlights =
            $this->repository->marketHighlights();

        /*
        |--------------------------------------------------------------------------
        | Fiber Intelligence
        |--------------------------------------------------------------------------
        |
        | Temporary dataset retained for backward compatibility.
        |--------------------------------------------------------------------------
        */

        $fiberData =
            $this->getRawFiberData();

        if (!auth()->check()) {

            $fiberData =
                collect($fiberData)
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
        | Lightweight Executive Trade Snapshot
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Do NOT call ExecutiveReportService here.
        |
        | The Home page should remain lightweight.
        |--------------------------------------------------------------------------
        */

        $executiveSnapshot =
            $this->executiveSnapshotService->getData();

        $executiveTrade = [
            'summary' => $executiveSnapshot,
            'fiber' => [],
            'yarn' => [],
            'fabric' => [],
            'apparel' => [],
        ];

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [

            'heroSummary' =>
                $heroSummary,

            'garmentTrade' =>
                $tradeRows,

            'totalGarment' =>
                $totalPieces,

            /*
            |--------------------------------------------------------------------------
            | Conversion Transparency
            |--------------------------------------------------------------------------
            |
            | These fields allow the frontend / AI layer to know
            | how much of the garment trade volume was actually
            | convertible using governed HS-8 factors.
            |--------------------------------------------------------------------------
            */

            'garmentConversion' => [
                'methodology' =>
                    'KG_PER_PCS',

                'direction' =>
                    'KG_TO_PCS',

                'convertible_hs8_count' =>
                    $convertibleHs8Count,

                'non_convertible_hs8_count' =>
                    $nonConvertibleHs8Count,
            ],

            'marketHighlights' =>
                $marketHighlights,

            'topProducts' =>
                $topProducts,

            'fiberIntelligence' =>
                $fiberData,

            /*
            |--------------------------------------------------------------------------
            | Temporary Backward Compatibility
            |--------------------------------------------------------------------------
            */

            'report' =>
                $executiveTrade['apparel'],

            /*
            |--------------------------------------------------------------------------
            | Executive Trade Intelligence
            |--------------------------------------------------------------------------
            */

            'executiveTrade' =>
                $executiveTrade,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Temporary Fiber Dataset
     * --------------------------------------------------------------------------
     */
    private function getRawFiberData(): array
    {
        return [

            [
                'year' => '2019',
                'cotton_vol' => 320000,
                'cotton_val' => 540000000,
                'syn_vol' => 450000,
                'syn_val' => 620000000,
            ],

            [
                'year' => '2020',
                'cotton_vol' => 290000,
                'cotton_val' => 480000000,
                'syn_vol' => 410000,
                'syn_val' => 580000000,
            ],

            [
                'year' => '2021',
                'cotton_vol' => 340000,
                'cotton_val' => 610000000,
                'syn_vol' => 480000,
                'syn_val' => 690000000,
            ],

            [
                'year' => '2022',
                'cotton_vol' => 310000,
                'cotton_val' => 590000000,
                'syn_vol' => 460000,
                'syn_val' => 660000000,
            ],

            [
                'year' => '2023',
                'cotton_vol' => 330000,
                'cotton_val' => 620000000,
                'syn_vol' => 490000,
                'syn_val' => 710000000,
            ],

            [
                'year' => '2024',
                'cotton_vol' => 350000,
                'cotton_val' => 650000000,
                'syn_vol' => 520000,
                'syn_val' => 750000000,
            ],

            [
                'year' => '2025',
                'cotton_vol' => 370000,
                'cotton_val' => 690000000,
                'syn_vol' => 550000,
                'syn_val' => 800000000,
            ],
        ];
    }
}