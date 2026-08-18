<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\HomeTextileTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class HomeTextileIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected HomeTextileTradeIntelligenceService $homeTextileService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        $homeTextile =
            $this->homeTextileService->get();

        return Inertia::render(
            'Trade/HomeTextileIntelligence',
            [
                'homeTextile' => $homeTextile,

                'page' => [
                    'title' =>
                        'Home Textile Intelligence',

                    'description' =>
                        'Home Textile Trade Intelligence',
                ],
            ]
        );
    }
}