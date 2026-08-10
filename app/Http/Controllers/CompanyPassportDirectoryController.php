<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CompanyIdentity;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class CompanyPassportDirectoryController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * Digital Company Passport Directory
     * --------------------------------------------------------------------------
     *
     * Public-facing directory based on Canonical Identity.
     *
     * Legacy Company records are NOT queried directly here.
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $identities = CompanyIdentity::query()
            ->ready()
            ->with([
                'profile',
                'business',
                'capabilityProfile',
                'locations',
                'mediaAssets',
                'sources.company',
            ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where(
                        'canonical_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'normalized_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'country_name',
                        'like',
                        "%{$search}%"
                    );
                });
            })
            ->orderBy('canonical_name')
            ->paginate(24)
            ->withQueryString();

        return Inertia::render(
            'Company/Passport/Directory',
            [
                'identities' => $identities,
                'filters' => [
                    'search' => $search,
                ],
            ]
        );
    }
}