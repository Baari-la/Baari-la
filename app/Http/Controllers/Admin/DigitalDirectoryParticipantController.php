<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalDirectoryParticipant;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\CompanyClaim;
use Illuminate\Support\Facades\DB;

class DigitalDirectoryParticipantController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * Index
     * --------------------------------------------------------------------------
     */
    public function index(): Response
{
    /*
    |--------------------------------------------------------------------------
    | Participants
    |--------------------------------------------------------------------------
    |
    | Load connected master company agar Admin menggunakan nama perusahaan
    | resmi dari database setelah ownership/company connection selesai.
    |
    | participant.company_name tetap tersedia sebagai registration company
    | name dan dapat digunakan sebagai fallback di React.
    |
    */

    $participants =
        DigitalDirectoryParticipant::query()
            ->with([
                'company:id,nama_perusahaan',
            ])
            ->latest()
            ->paginate(20);

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    $stats = [

        /*
        |--------------------------------------------------------------------------
        | Total Participants
        |--------------------------------------------------------------------------
        */

        'total' =>
            DigitalDirectoryParticipant::count(),

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Activation Status
        |--------------------------------------------------------------------------
        */

        'active' =>
            DigitalDirectoryParticipant::where(
                'activation_status',
                'active'
            )->count(),

        /*
        |--------------------------------------------------------------------------
        | Package Statistics
        |--------------------------------------------------------------------------
        */

        'executive' =>
            DigitalDirectoryParticipant::where(
                'package',
                'Executive Partner'
            )->count(),

        'visibility' =>
            DigitalDirectoryParticipant::where(
                'package',
                'Visibility Partner'
            )->count(),

        'verified_company' =>
            DigitalDirectoryParticipant::where(
                'package',
                'Verified Company'
            )->count(),

        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        |
        | Revenue hanya menghitung participant dengan payment yang sudah
        | diverifikasi.
        |
        */

        'revenue' =>
            DigitalDirectoryParticipant::where(
                'payment_status',
                'verified'
            )->sum(
                'amount'
            ),
    ];

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return Inertia::render(
        'Admin/DigitalDirectory/Index',
        [
            'participants' =>
                $participants,

            'stats' =>
                $stats,
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
    
    $participant->load([
        'company',
    ]);
    
    return Inertia::render(
        'Admin/DigitalDirectory/ParticipantDetails',
        [
            'participant' => [
                'id' => $participant->id,
            
                'user_id' =>
                    $participant->user_id,

                'company_id' =>
                    $participant->company_id,
            
                 'connected_company' =>
            $participant->company
                ? [
                    'id' =>
                        $participant->company->id,

                    'name' =>
                        $participant->company->nama_perusahaan,

                    'verification_status' =>
                        $participant->company->status_verifikasi,

                    'membership_type' =>
                        $participant->company->membership_type,
                ]
                : null,   
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

            'currency' =>
                $participant->currency,

            'payment_method' =>
                $participant->payment_method,

            'payment_gateway' =>
                $participant->payment_gateway,

            'payment_reference' =>
                $participant->payment_reference,

            'payment_receipt' =>
                $participant->payment_receipt,

            'payment_status' =>
                $participant->payment_status,

            'paid_at' =>
                $participant->paid_at,

            'payment_verified_at' =>
                $participant->payment_verified_at,

            'verified_by' =>
                $participant->verified_by,

            'admin_notes' =>
                $participant->admin_notes,

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
): RedirectResponse {

    /*
    |--------------------------------------------------------------------------
    | Payment Must Be Verified
    |--------------------------------------------------------------------------
    */

    if ($participant->payment_status !== 'verified') {
        return back()->with(
            'error',
            'Program payment must be verified before activation.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Company Must Be Connected
    |--------------------------------------------------------------------------
    */

    if (!$participant->company_id) {
        return back()->with(
            'error',
            'Company ownership must be verified before activation.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent Duplicate Activation
    |--------------------------------------------------------------------------
    */

    if ($participant->activation_status === 'active') {
        return back()->with(
            'error',
            'Program is already active.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Package Entitlements
    |--------------------------------------------------------------------------
    */

    $services = match ($participant->package) {

        'Verified Company' => [
            'visibility_score_active' => true,
            'company_passport_active' => true,
            'executive_dashboard_active' => false,
            'smart_matching_active' => false,
            'build_supply_chain_active' => false,
        ],

        'Visibility Partner' => [
            'visibility_score_active' => true,
            'company_passport_active' => true,
            'executive_dashboard_active' => true,
            'smart_matching_active' => true,
            'build_supply_chain_active' => false,
        ],

        'Executive Partner' => [
            'visibility_score_active' => true,
            'company_passport_active' => true,
            'executive_dashboard_active' => true,
            'smart_matching_active' => true,
            'build_supply_chain_active' => true,
        ],

        default => null,
    };

    /*
    |--------------------------------------------------------------------------
    | Validate Package
    |--------------------------------------------------------------------------
    */

    if ($services === null) {
        return back()->with(
            'error',
            'Program package is not recognized.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Activate Program
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $participant,
        $services
    ) {
        $participant->update([
            'activation_status' => 'active',
            'activated_at' => now(),

            ...$services,
        ]);
    });

    return back()->with(
        'success',
        'Program activated successfully.'
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

    /*
    |--------------------------------------------------------------------------
    | Program Must Be Active
    |--------------------------------------------------------------------------
    */

    if ($participant->activation_status !== 'active') {
        return back()->with(
            'error',
            'Program is not currently active.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Deactivate Program
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($participant) {

        $participant->update([

            'activation_status' =>
                'inactive',

            'visibility_score_active' =>
                false,

            'company_passport_active' =>
                false,

            'executive_dashboard_active' =>
                false,

            'smart_matching_active' =>
                false,

            'build_supply_chain_active' =>
                false,
        ]);
    });

    return back()->with(
        'success',
        'Program deactivated successfully.'
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
/*
|--------------------------------------------------------------------------
| Ownership Verification
|--------------------------------------------------------------------------
*/

public function ownershipVerification(): Response
{
    $claims = CompanyClaim::query()

        ->with([
            'company',
            'user',
        ])

        ->whereIn(
            'user_id',
            DigitalDirectoryParticipant::query()
                ->whereNotNull('user_id')
                ->select('user_id')
        )

        ->latest()

        ->get();

    return Inertia::render(
        'Admin/DigitalDirectory/OwnershipVerification',
        [
            'claims' => $claims,

            'stats' => [
                'total' =>
                    $claims->count(),

                'pending' =>
                    $claims->where(
                        'status',
                        'pending'
                    )->count(),

                'approved' =>
                    $claims->where(
                        'status',
                        'approved'
                    )->count(),

                'rejected' =>
                    $claims->where(
                        'status',
                        'rejected'
                    )->count(),
            ],
        ]
    );
}

}