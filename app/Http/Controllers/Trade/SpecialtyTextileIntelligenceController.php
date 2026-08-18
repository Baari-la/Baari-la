<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\SpecialtyTextileTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class SpecialtyTextileIntelligenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected SpecialtyTextileTradeIntelligenceService $specialtyTextileService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        $specialtyTextile =
            $this->specialtyTextileService->get();

        return Inertia::render(
            'Trade/SpecialtyTextileIntelligence',
            [
                'specialtyTextile' => $specialtyTextile,

                'page' => [
                    'title' =>
                        'Specialty Textile Intelligence',

                    'description' =>
                        'Specialty Textile Trade Intelligence',
                ],
            ]
        );
    }
}
