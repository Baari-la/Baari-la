<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalDirectoryParticipant;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DigitalDirectoryParticipantController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * Index
     * --------------------------------------------------------------------------
     */
    public function index(): Response
    {
        return Inertia::render(
            'Admin/DigitalDirectory/Index',
            [
                'participants' =>

                    DigitalDirectoryParticipant::query()

                        ->latest()

                        ->paginate(20),

                'stats' => [

                    'total' =>

                        DigitalDirectoryParticipant::count(),

                    'pending' =>

                        DigitalDirectoryParticipant::where(
                            'payment_status',
                            'pending_verification'
                        )->count(),

                    'verified' =>

                        DigitalDirectoryParticipant::where(
                            'payment_status',
                            'verified'
                        )->count(),

                    'active' =>

                        DigitalDirectoryParticipant::where(
                            'activation_status',
                            'active'
                        )->count(),
                ],
            ]
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Show
     * --------------------------------------------------------------------------
     */
    public function show(
    DigitalDirectoryParticipant $participant
)
{
   
    return Inertia::render(
        'Admin/DigitalDirectory/ParticipantDetails',
        [
            'participant' => [
                'id' => $participant->id,

                'package' =>
                    $participant->package,

                'company_name' =>
                    $participant->company_name,

                'pic_name' =>
                    $participant->pic_name,

                'position' =>
                    $participant->position,

                'email' =>
                    $participant->email,

                'phone' =>
                    $participant->phone,

                'website' =>
                    $participant->website,

                'company_type' =>
                    $participant->company_type,

                'country' =>
                    $participant->country,

                'city' =>
                    $participant->city,

                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                'invoice_number' =>
                    $participant->invoice_number,

                'amount' =>
                    $participant->amount,

                'payment_method' =>
                    $participant->payment_method,

                'payment_gateway' =>
                    $participant->payment_gateway,

                'payment_status' =>
                    $participant->payment_status,

                'payment_verified_at' =>
                    $participant->payment_verified_at,

                /*
                |--------------------------------------------------------------------------
                | Activation
                |--------------------------------------------------------------------------
                */

                'activation_status' =>
                    $participant->activation_status,

                'activated_at' =>
                    $participant->activated_at,

                /*
                |--------------------------------------------------------------------------
                | Services
                |--------------------------------------------------------------------------
                */

                'visibility_score_active' =>
                    $participant->visibility_score_active,

                'company_passport_active' =>
                    $participant->company_passport_active,

                'executive_dashboard_active' =>
                    $participant->executive_dashboard_active,

                'smart_matching_active' =>
                    $participant->smart_matching_active,

                'build_supply_chain_active' =>
                    $participant->build_supply_chain_active,
            ],
        ]
    );
}

    /**
     * --------------------------------------------------------------------------
     * Verify Payment
     * --------------------------------------------------------------------------
     */
    public function verify(
    DigitalDirectoryParticipant $participant
)
{
    $participant->update([

        'payment_status' =>
            'verified',

        'payment_verified_at' =>
            now(),
    ]);

    return back()->with(
        'success',
        'Payment verified.'
    );
}

    /**
     * --------------------------------------------------------------------------
     * Reject Payment
     * --------------------------------------------------------------------------
     */
    public function reject(
        DigitalDirectoryParticipant $participant
    ): RedirectResponse {

        $participant->update([

            'payment_status' =>
                'rejected',

            'activation_status' =>
                'inactive',
        ]);

        return back()->with(
            'success',
            'Payment rejected.'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Activate
     * --------------------------------------------------------------------------
     */
    public function activate(
    DigitalDirectoryParticipant $participant
)
{
    $participant->update([

        'activation_status' =>
            'active',

        'activated_at' =>
            now(),

        'visibility_score_active' =>
            true,

        'company_passport_active' =>
            true,

        'executive_dashboard_active' =>
            true,

        'smart_matching_active' =>
            true,

        'build_supply_chain_active' =>
            true,
    ]);

    return back()->with(
        'success',
        'Company activated.'
    );
}

    /**
     * --------------------------------------------------------------------------
     * Deactivate
     * --------------------------------------------------------------------------
     */
    public function deactivate(
        DigitalDirectoryParticipant $participant
    ): RedirectResponse {

        $participant->update([

            'activation_status' =>
                'inactive',
        ]);

        return back()->with(
            'success',
            'Participant deactivated.'
        );
    }
    public function pendingPayments()
{
    return Inertia::render(
        'Admin/DigitalDirectory/PendingPayments',
        [
            'participants' =>
                DigitalDirectoryParticipant::where(
                    'payment_status',
                    'pending_verification'
                )
                ->latest()
                ->get(),
        ]
    );
} 

public function verified()
{
    return Inertia::render(
        'Admin/DigitalDirectory/VerifiedCompanies',
        [
            'participants' =>
                DigitalDirectoryParticipant::where(
                    'payment_status',
                    'verified'
                )
                ->latest()
                ->get(),
        ]
    );
}
public function revenue()
{
    $totalRevenue =
        DigitalDirectoryParticipant::where(
            'payment_status',
            'verified'
        )->sum('amount');

    return Inertia::render(
        'Admin/DigitalDirectory/RevenueDashboard',
        [

            'stats' => [

                'totalRevenue' =>
                    $totalRevenue,

                'monthlyRevenue' =>
                    DigitalDirectoryParticipant::where(
                        'payment_status',
                        'verified'
                    )
                    ->whereMonth(
                        'created_at',
                        now()->month
                    )
                    ->sum(
                        'amount'
                    ),

                'totalParticipants' =>
                    DigitalDirectoryParticipant::count(),

                'activeCompanies' =>
                    DigitalDirectoryParticipant::where(
                        'activation_status',
                        'active'
                    )->count(),

                'verifiedPayments' =>
                    DigitalDirectoryParticipant::where(
                        'payment_status',
                        'verified'
                    )->count(),

                'pendingPayments' =>
                    DigitalDirectoryParticipant::where(
                        'payment_status',
                        'pending_verification'
                    )->count(),

                'averageTicket' =>
                    DigitalDirectoryParticipant::avg(
                        'amount'
                    ),

                'activationRate' =>
                    round(
                        (
                            DigitalDirectoryParticipant::where(
                                'activation_status',
                                'active'
                            )->count()
                            /
                            max(
                                1,
                                DigitalDirectoryParticipant::count()
                            )
                        ) * 100,
                        2
                    ),

                'packages' =>
                    DigitalDirectoryParticipant::selectRaw(
                        '
                        package,
                        COUNT(*) as total,
                        SUM(amount) as revenue
                        '
                    )
                    ->groupBy(
                        'package'
                    )
                    ->get(),

                'topCountries' =>
                    DigitalDirectoryParticipant::selectRaw(
                        '
                        country,
                        COUNT(*) as total
                        '
                    )
                    ->groupBy(
                        'country'
                    )
                    ->orderByDesc(
                        'total'
                    )
                    ->take(4)
                    ->get(),
            ],
        ]
    );
}
public function packageAnalytics()
{
    $totalRevenue =
        DigitalDirectoryParticipant::sum(
            'amount'
        );

    $packages =
        DigitalDirectoryParticipant::selectRaw(
            '
            package,
            COUNT(*) as total,
            SUM(amount) as revenue,
            AVG(amount) as average
            '
        )
        ->groupBy(
            'package'
        )
        ->get()
        ->map(function ($item) use (
            $totalRevenue
        ) {

            $item->percentage =
                $totalRevenue > 0
                ? round(
                    (
                        $item->revenue
                        / $totalRevenue
                    ) * 100,
                    2
                )
                : 0;

            return $item;
        });

    return Inertia::render(
        'Admin/DigitalDirectory/PackageAnalytics',
        [

            'packages' =>
                $packages,

            'stats' => [

                'totalPackages' =>
                    $packages->count(),

                'totalRevenue' =>
                    $totalRevenue,

                'totalParticipants' =>
                    DigitalDirectoryParticipant::count(),

                'averageTicket' =>
                    DigitalDirectoryParticipant::avg(
                        'amount'
                    ),

                'topPackage' =>
                    optional(
                        $packages
                            ->sortByDesc(
                                'revenue'
                            )
                            ->first()
                    )->package,

                'highestRevenue' =>
                    optional(
                        $packages
                            ->sortByDesc(
                                'revenue'
                            )
                            ->first()
                    )->revenue,

                'mostPopular' =>
                    optional(
                        $packages
                            ->sortByDesc(
                                'total'
                            )
                            ->first()
                    )->package,

                'activationRate' =>
                    round(
                        (
                            DigitalDirectoryParticipant::where(
                                'activation_status',
                                'active'
                            )->count()
                            /
                            max(
                                1,
                                DigitalDirectoryParticipant::count()
                            )
                        ) * 100,
                        2
                    ),
            ],
        ]
    );
}


}