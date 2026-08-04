<?php

declare(strict_types=1);

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onboarding\StoreFactoryRequest;
use App\Http\Requests\Onboarding\UpdateFactoryRequest;
use App\Services\Canonical\CanonicalCompanyFactoryService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FactoryOnboardingController extends Controller
{
    public function __construct(
        protected CanonicalCompanyFactoryService $factoryService,
    ) {
    }

    /**
     * Display Step 4 - Digital Factory Passport.
     */
    public function edit(): Response
    {
        $user = auth()->user();

        $companyIdentity = $user->companyIdentity;

        $factory = $companyIdentity
            ->factories()
            ->with('machines')
            ->first();

        return Inertia::render(
            'Onboarding/Step4',
            [
                'factory' => $factory,
            ]
        );
    }

    /**
     * Store a new factory.
     */
    public function store(
        StoreFactoryRequest $request
    ): RedirectResponse {

        $companyIdentity = $request
            ->user()
            ->companyIdentity;

        $this->factoryService->createDefaultFactory(
            $companyIdentity,
            $request->validated()
        );

        return redirect()->route(
            'onboarding.step5'
        )->with(
            'success',
            'Digital Factory Passport created successfully.'
        );
    }

    /**
     * Update existing factory.
     */
    public function update(
        UpdateFactoryRequest $request
    ): RedirectResponse {

        $companyIdentity = $request
            ->user()
            ->companyIdentity;

        $factory = $companyIdentity
            ->factories()
            ->firstOrFail();

        $this->factoryService->updateFactory(
            $factory,
            $request->validated()
        );

        return redirect()->route(
            'onboarding.step5'
        )->with(
            'success',
            'Digital Factory Passport updated successfully.'
        );
    }
}