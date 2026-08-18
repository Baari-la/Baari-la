<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\TechnicalTextileTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class TechnicalTextileIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected TechnicalTextileTradeIntelligenceService $technicalTextileService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        $technicalTextile =
            $this->technicalTextileService->get();

        return Inertia::render(
            'Trade/TechnicalTextileIntelligence',
            [
                'technicalTextile' => $technicalTextile,

                'page' => [
                    'title' =>
                        'Technical / Industrial Textile Intelligence',

                    'description' =>
                        'Technical / Industrial Textile Trade Intelligence',
                ],
            ]
        );
    }
}