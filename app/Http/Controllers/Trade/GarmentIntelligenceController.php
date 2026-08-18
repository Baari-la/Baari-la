<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\GarmentTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class GarmentIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected GarmentTradeIntelligenceService $garmentService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        $garment =
            $this->garmentService->get();

        return Inertia::render(
            'Trade/GarmentIntelligence',
            [
                'garment' => $garment,

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