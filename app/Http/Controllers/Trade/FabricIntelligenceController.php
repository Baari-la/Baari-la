<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\FabricTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class FabricIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected FabricTradeIntelligenceService $fabricService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        $fabric =
            $this->fabricService->get();

        return Inertia::render(
            'Trade/FabricIntelligence',
            [
                'fabric' => $fabric,

                'page' => [
                    'title' =>
                        'Fabric Intelligence',

                    'description' =>
                        'Fabric Trade Intelligence',
                ],
            ]
        );
    }
}