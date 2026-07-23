<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\Trade\SupplyChain\BuildMySupplyChainService;
use App\Services\Trade\SupplyChain\SupplierFinderService;
use App\Services\Trade\SupplyChain\BuyerDiscoveryService;
use App\Services\Trade\SupplyChain\SupplyChainGraphService;
use App\Services\Trade\SupplyChain\ExecutiveSupplyChainReportService;

class BuildMySupplyChainController
    extends Controller
{
    public function product(
        string $product
    ) {

        $supplyChain = app(
            BuildMySupplyChainService::class
        )->buildByProduct(
            $product
        );

        $suppliers = app(
    SupplierFinderService::class
)->find([
    'product' => $product,
]);

        $buyers = app(
            BuyerDiscoveryService::class
        )->discover(
            $product
        );

        $graph = app(
            SupplyChainGraphService::class
        )->build(
            $supplyChain
        );

        $report = app(
            ExecutiveSupplyChainReportService::class
        )->build(
            $supplyChain,
            $suppliers,
            $buyers,
            $graph
        );

        return Inertia::render(
            'SupplyChain/BuildMySupplyChain',

            [

                'supplyChain' =>
                    $supplyChain,

                'suppliers' =>
                    $suppliers,

                'buyers' =>
                    $buyers,

                'graph' =>
                    $graph,

                'report' =>
                    $report,
            ]
        );
    }
 
   public function buildByProduct(
    string $product
): array {

    return [

        'product' => $product,

        'sector' => 'apparel',

        'stages' => [

            [
                'type' => 'raw_material',

                'title' => 'Cotton',
            ],

            [
                'type' => 'yarn',

                'title' => 'Spinning',
            ],

            [
                'type' => 'fabric',

                'title' => 'Knitting',
            ],

            [
                'type' => 'dyeing',

                'title' => 'Dyeing',
            ],

            [
                'type' => 'garment',

                'title' => 'Garment Factory',
            ],

            [
                'type' => 'buyer',

                'title' => 'Global Buyer',
            ],
        ],

        'upstream' => [

            'Cotton',
        ],

        'midstream' => [

            'Spinning',
            'Knitting',
            'Dyeing',
        ],

        'downstream' => [

            'Garment Factory',
        ],

        'buyers' => [

            'Uniqlo',
            'H&M',
            'Decathlon',
        ],

        'supporting' => [

            'Packaging',
            'Logistics',
        ],
    ];
}
}