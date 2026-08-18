<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\FiberTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class FiberIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected FiberTradeIntelligenceService $fiberService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        $fiber =
            $this->fiberService->get();

        return Inertia::render(
            'Trade/FiberIntelligence',
            [
                'fiber' => $fiber,

                'page' => [
                    'title' =>
                        'Fiber Intelligence',

                    'description' =>
                        'Fiber Trade Intelligence',
                ],
            ]
        );
    }
}