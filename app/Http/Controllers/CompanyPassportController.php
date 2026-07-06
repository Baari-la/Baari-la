<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\Company\Intelligence\CompanyIntelligenceOrchestrator;
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
    public function show(Company $company): Response
{
    $company->loadPassportRelations();

    return Inertia::render(
        'Company/Passport/Index',
        [
            'passport' => $this->intelligence->all($company),
        ]
    );
}
    /**
 * --------------------------------------------------------------------------
 * Passport Data API
 * --------------------------------------------------------------------------
 */
public function data(Company $company)
{
    $company->load([

        'products',
        'markets',
        'machines',
        'capacities',
        'moqs',
        'leadTimes',
        'certifications',
        'contacts',
        'links',
        'images',

    ]);

    return response()->json(
        $this->intelligence->all($company)
    );
}

}