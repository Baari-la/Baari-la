<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\Recommendation\RecommendationEngine;
use App\Services\Recommendation\Presentation\RecommendationPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Display recommendations for a company.
     */
    public function index(
        Request $request,
        Company $company,
        RecommendationEngine $engine,
        RecommendationPresenter $presenter,
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'discovery_mode' => [
                'nullable',
                'string',
                'in:supplier_discovery,buyer_discovery,solution_partner_discovery,technology_partner_discovery',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load Intelligence Data
        |--------------------------------------------------------------------------
        */

        $company->loadMissing([
            'products',
            'markets',
            'certifications',
            'machines',
            'capacities',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Build Recommendation Context
        |--------------------------------------------------------------------------
        */

        $context = [];

        if (! empty($validated['discovery_mode'])) {
            $context['discovery_mode'] =
                $validated['discovery_mode'];
        }

        /*
        |--------------------------------------------------------------------------
        | Run DRIE
        |--------------------------------------------------------------------------
        */

        $result = $engine->recommend(
            company: $company,
            context: $context,
        );

        /*
        |--------------------------------------------------------------------------
        | Present API Contract
        |--------------------------------------------------------------------------
        */

        return response()->json(
            $presenter->present($result)
        );
    }
}