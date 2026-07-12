<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\Company\Intelligence\CompanyIntelligenceOrchestrator;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyPassportController extends Controller
{
    public function __construct(
        protected CompanyIntelligenceOrchestrator $intelligence,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Digital Company Passport
     * --------------------------------------------------------------------------
     */
    public function show(
        Company $company
    ): Response {

        $company->loadPassportRelations();

        return Inertia::render(
    'Company/Passport/Index',
    [
        'passport' => $this->intelligence->passport($company),
    ]
);
    }

    /**
     * --------------------------------------------------------------------------
     * Passport API
     * --------------------------------------------------------------------------
     */
    public function data(
        Company $company
    ): JsonResponse {

        $company->loadPassportRelations();

        return response()->json(
    $this->intelligence->passport($company)
);
    }
}