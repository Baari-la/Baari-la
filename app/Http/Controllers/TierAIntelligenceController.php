<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\Trade\Executive\ExecutiveDashboardService;
use App\Services\Trade\LaunchTradeIntelligenceService;
use Illuminate\Http\Request;

class TierAIntelligenceController extends Controller
{
    public function __construct(
        protected ExecutiveDashboardService $dashboardService
    ) {
    }

    public function index(
    Request $request,
    LaunchTradeIntelligenceService $service
) {
    $snapshot = $service->getData();

    return Inertia::render('Trade/Radar', [

        'topTrade' =>
            $snapshot['topTrade'] ?? [],

        'topCountries' =>
            $snapshot['topExportDestinations'] ?? [],

        'yearlyTrends' =>
            $snapshot['yearlyTrends'] ?? [],

        'hscodes' =>
            array_slice(
                $snapshot['topExportProducts'] ?? [],
                0,
                10
            ),

        'partners' =>
            [],

        'launchIntelligence' =>
            $snapshot,

        'filters' =>
            $request->only([
                'category',
                'region',
            ]),
    ]);
}
}