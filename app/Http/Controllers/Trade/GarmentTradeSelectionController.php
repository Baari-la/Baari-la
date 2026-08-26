<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\GarmentTradeIntelligenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GarmentTradeSelectionController extends Controller
{
    public function __construct(
        protected GarmentTradeIntelligenceService $garmentTradeIntelligenceService
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Garment Trade Selection
     * --------------------------------------------------------------------------
     *
     * Returns trade intelligence for:
     *
     * - selected year
     * - one or multiple months
     * - import / export
     *
     * IMPORTANT:
     * This controller does NOT calculate trade values or volumes.
     * All calculations remain inside GarmentTradeIntelligenceService.
     */
    public function index(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'year' => [
                'required',
                'integer',
                'min:2019',
                'max:2100',
            ],

            'months' => [
                'required',
                'array',
                'min:1',
            ],

            'months.*' => [
                'integer',
                'between:1,12',
            ],

            'flow' => [
                'required',
                'string',
                Rule::in([
                    'import',
                    'export',
                ]),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalize Selection
        |--------------------------------------------------------------------------
        */

        $year = (int) $validated['year'];

        $months = collect(
            $validated['months']
        )
            ->map(
                fn ($month) => (int) $month
            )
            ->unique()
            ->sort()
            ->values()
            ->all();

        $flow = strtolower(
            trim(
                (string) $validated['flow']
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Single Source of Truth
        |--------------------------------------------------------------------------
        |
        | The controller delegates all trade intelligence calculation
        | to GarmentTradeIntelligenceService.
        |
        */

        $result =
            $this->garmentTradeIntelligenceService
                ->getForSelection(
                    $year,
                    $months,
                    $flow
                );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'data' => $result,
        ]);
    }
}