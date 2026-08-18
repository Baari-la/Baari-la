<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\YarnTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class YarnIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected YarnTradeIntelligenceService $yarnService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        $yarn =
            $this->yarnService->get();

        return Inertia::render(
            'Trade/YarnIntelligence',
            [
                'yarn' => $yarn,

                'page' => [
                    'title' =>
                        'Yarn Intelligence',

                    'description' =>
                        'Yarn Trade Intelligence',
                ],
            ]
        );
    }
}