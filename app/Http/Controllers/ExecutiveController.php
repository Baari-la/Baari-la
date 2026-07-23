<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\Trade\Executive\ExecutiveDashboardService;

class ExecutiveController extends Controller
{
    public function index(
        string $sector = 'textile'
    ) {

        $sectorConfig = config(
            "textile_sectors.{$sector}"
        );

        abort_unless(
            $sectorConfig,
            404
        );

        $dashboard = app(
            ExecutiveDashboardService::class
        )->build([

            'sector' => $sector,

            'hs_prefix' =>
                $sectorConfig['hs'],

            'year' => now()->year,

            'months' => [1, 2, 3, 4],

        ]);

        return Inertia::render(
            'Trade/ExecutiveDashboard',
            $dashboard
        );
    }
}