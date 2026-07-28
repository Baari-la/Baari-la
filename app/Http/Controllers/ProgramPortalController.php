<?php

namespace App\Http\Controllers;

use App\Models\CompanyClaim;
use App\Models\DigitalDirectoryParticipant;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProgramPortalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Program Portal
    |--------------------------------------------------------------------------
    |
    | Halaman utama peserta DIGESTEX Digital Directory & Visibility Program.
    |
    | Portal membaca:
    |
    | - Program participation
    | - Payment status
    | - Account / email verification
    | - Ownership verification
    | - Company connection
    | - Onboarding progress
    | - Package / service access
    |
    */

   public function index(): Response|RedirectResponse
{
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | Program Portal hanya dapat diakses oleh user yang sudah login.
    |
    */

    if (!$user) {
        return redirect()->route('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Digital Directory Participant
    |--------------------------------------------------------------------------
    |
    | Participant dihubungkan ke user ketika akun dibuat.
    |
    */

    $participant =
        DigitalDirectoryParticipant::query()
            ->where(
                'user_id',
                $user->id
            )
            ->latest('id')
            ->first();

    /*
    |--------------------------------------------------------------------------
    | Ownership Claim
    |--------------------------------------------------------------------------
    |
    | Ambil ownership verification terbaru milik user.
    |
    */

    $claim =
        CompanyClaim::query()
            ->with([
                'company:id,nama_perusahaan',
            ])
            ->where(
                'user_id',
                $user->id
            )
            ->latest('submitted_at')
            ->latest('id')
            ->first();

    /*
    |--------------------------------------------------------------------------
    | Company Connection
    |--------------------------------------------------------------------------
    |
    | Company hanya dianggap terhubung apabila users.company_id sudah ada.
    | company_id diberikan setelah ownership verification disetujui admin.
    |
    */

    $companyConnected =
        !is_null(
            $user->company_id
        );

    $company = null;

    if ($companyConnected) {

        $company =
            $user
                ->company()
                ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    |
    | Bedakan:
    |
    | not_started
    | pending_verification
    | paid
    | verified
    |
    | pending_verification berarti pembayaran / bukti pembayaran sudah
    | dikirim tetapi masih menunggu verifikasi DIGESTEX.
    |
    */

    $paymentStatus =
        $participant?->payment_status ??
        'not_started';

    $paymentPendingVerification =
        $paymentStatus ===
        'pending_verification';

    $paymentCompleted =
        $participant &&
        in_array(
            $paymentStatus,
            [
                'paid',
                'verified',
            ],
            true
        );

    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */

    $emailVerified =
        !is_null(
            $user->email_verified_at
        );

    /*
    |--------------------------------------------------------------------------
    | Ownership Verification
    |--------------------------------------------------------------------------
    */

    $ownershipStatus =
        $claim?->status ??
        'not_started';

    $ownershipPending =
        $ownershipStatus ===
        'pending';

    $ownershipVerified =
        $ownershipStatus ===
        'approved';

    $ownershipRejected =
        $ownershipStatus ===
        'rejected';

    /*
    |--------------------------------------------------------------------------
    | Company Onboarding
    |--------------------------------------------------------------------------
    |
    | Onboarding tidak boleh dianggap selesai sebelum company benar-benar
    | terhubung dengan user.
    |
    */

    $onboardingCompleted =
        $companyConnected &&
        (bool) (
            $user->onboarding_completed ??
            false
        );

    /*
    |--------------------------------------------------------------------------
    | Onboarding Step
    |--------------------------------------------------------------------------
    |
    | Jika company belum terhubung, progress onboarding company dianggap
    | belum dimulai untuk Program Portal.
    |
    */

    $onboardingStep =
        $companyConnected
            ? (int) (
                $user->onboarding_step ??
                0
            )
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Program Activation
    |--------------------------------------------------------------------------
    */

    $programActivated =
        $participant &&
        $participant->activation_status ===
            'active';

    /*
    |--------------------------------------------------------------------------
    | Verification In Progress
    |--------------------------------------------------------------------------
    |
    | Berguna untuk portal apabila tidak ada tindakan yang harus dilakukan
    | user karena payment / ownership sedang diperiksa admin.
    |
    */

    $verificationInProgress =
        $paymentPendingVerification ||
        $ownershipPending;

    /*
    |--------------------------------------------------------------------------
    | Determine Next Action
    |--------------------------------------------------------------------------
    */

   $nextAction =
    $this->determineNextAction(
        participant: $participant,

        paymentCompleted:
            $paymentCompleted,

        paymentPendingVerification:
            $paymentPendingVerification,

        emailVerified:
            $emailVerified,

        ownershipStatus:
            $ownershipStatus,

        companyConnected:
            $companyConnected,

        onboardingCompleted:
            $onboardingCompleted,
    );

    /*
    |--------------------------------------------------------------------------
    | Package Services
    |--------------------------------------------------------------------------
    */

    $services =
        $this->buildServices(
            $participant
        );

    /*
    |--------------------------------------------------------------------------
    | Render Portal
    |--------------------------------------------------------------------------
    */

    return Inertia::render(
        'Programs/DigitalDirectory/Portal',
        [

            /*
            |--------------------------------------------------------------------------
            | Participant
            |--------------------------------------------------------------------------
            */

            'participant' =>
                $participant,

            /*
            |--------------------------------------------------------------------------
            | Ownership Claim
            |--------------------------------------------------------------------------
            */

            'claim' =>
                $claim,

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'company' =>
                $company,

            /*
            |--------------------------------------------------------------------------
            | Program Status
            |--------------------------------------------------------------------------
            */

            'programStatus' => [

                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                'payment_status' =>
                    $paymentStatus,

                'payment_completed' =>
                    $paymentCompleted,

                'payment_pending_verification' =>
                    $paymentPendingVerification,

                /*
                |--------------------------------------------------------------------------
                | Account
                |--------------------------------------------------------------------------
                */

                'email_verified' =>
                    $emailVerified,

                /*
                |--------------------------------------------------------------------------
                | Ownership
                |--------------------------------------------------------------------------
                */

                'ownership_status' =>
                    $ownershipStatus,

                'ownership_pending' =>
                    $ownershipPending,

                'ownership_verified' =>
                    $ownershipVerified,

                'ownership_rejected' =>
                    $ownershipRejected,

                /*
                |--------------------------------------------------------------------------
                | Company
                |--------------------------------------------------------------------------
                */

                'company_connected' =>
                    $companyConnected,

                /*
                |--------------------------------------------------------------------------
                | Onboarding
                |--------------------------------------------------------------------------
                */

                'onboarding_step' =>
                    $onboardingStep,

                'onboarding_completed' =>
                    $onboardingCompleted,

                /*
                |--------------------------------------------------------------------------
                | Program
                |--------------------------------------------------------------------------
                */

                'activation_status' =>
                    $participant?->activation_status ??
                    'not_started',

                'program_activated' =>
                    $programActivated,

                /*
                |--------------------------------------------------------------------------
                | Verification
                |--------------------------------------------------------------------------
                */

                'verification_in_progress' =>
                    $verificationInProgress,
            ],

            /*
            |--------------------------------------------------------------------------
            | Next Action
            |--------------------------------------------------------------------------
            */

            'nextAction' =>
                $nextAction,

            /*
            |--------------------------------------------------------------------------
            | Services
            |--------------------------------------------------------------------------
            */

            'services' =>
                $services,
        ]
    );
}

    /*
    |--------------------------------------------------------------------------
    | Determine Next Action
    |--------------------------------------------------------------------------
    */

  private function determineNextAction(
    ?DigitalDirectoryParticipant $participant,
    bool $paymentCompleted,
    bool $paymentPendingVerification,
    bool $emailVerified,
    string $ownershipStatus,
    bool $companyConnected,
    bool $onboardingCompleted,
): array {

    /*
    |--------------------------------------------------------------------------
    | No Program Participant
    |--------------------------------------------------------------------------
    */

      if (!$participant) {

        return [
            'key' => 'program_not_linked',
            'route' =>
                'program.digital-directory.index',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    |
    | Account sudah dibuat, tetapi email belum diverifikasi.
    |
    */

    if (!$emailVerified) {

        return [
            'key' => 'email_verification',
            'route' => null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ownership Rejected
    |--------------------------------------------------------------------------
    |
    | Ini membutuhkan tindakan user sehingga harus menjadi prioritas.
    |
    */

     if ($ownershipStatus === 'rejected') {

        return [
            'key' => 'ownership_rejected',
            'route' =>
                'companies.claim.create-manual',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Payment + Ownership Both Pending
    |--------------------------------------------------------------------------
    |
    | User sudah melakukan semua tindakan yang diperlukan.
    | Payment dan ownership sedang diperiksa DIGESTEX.
    |
    */

      if (
        $paymentPendingVerification &&
        $ownershipStatus === 'pending'
    ) {

        return [
            'key' =>
                'verification_in_progress',

            'route' => null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ownership Pending
    |--------------------------------------------------------------------------
    |
    | Ownership sudah diajukan. User tidak perlu submit ulang.
    |
    */

     if ($ownershipStatus === 'pending') {

        return [
            'key' => 'ownership_pending',
            'route' => null,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Pending Verification
    |--------------------------------------------------------------------------
    |
    | Bukti pembayaran sudah dikirim.
    |
    */

      if ($paymentPendingVerification) {

        return [
            'key' =>
                'payment_pending_verification',

            'route' => null,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Not Completed
    |--------------------------------------------------------------------------
    |
    | Hanya tampil apabila user memang belum melakukan / menyelesaikan
    | proses pembayaran.
    |
    */

    if (!$paymentCompleted) {

        return [
            'key' => 'payment',
            'route' =>
                'program.digital-directory.payment',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ownership Not Started
    |--------------------------------------------------------------------------
    |
    | Payment sudah selesai tetapi user belum mengajukan ownership.
    |
    */

    if ($ownershipStatus === 'not_started') {

        return [
            'key' =>
                'ownership_verification',

            'route' =>
                'onboarding.company-lookup',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ownership Approved But Company Not Connected
    |--------------------------------------------------------------------------
    |
    | Kondisi ini seharusnya hanya sementara / abnormal karena approveClaim
    | nantinya harus menghubungkan users.company_id.
    |
    */

     if (
        $ownershipStatus === 'approved' &&
        !$companyConnected
    ) {

        return [
            'key' => 'company_connection',
            'route' => null,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Company Connected - Onboarding Required
    |--------------------------------------------------------------------------
    */

    if (
        $companyConnected &&
        !$onboardingCompleted
    ) {

        return [
            'key' => 'company_profile',
            'route' =>
                'onboarding.company-information',
        ];
    }

    /*
|--------------------------------------------------------------------------
| Program Active
|--------------------------------------------------------------------------
|
| Seluruh setup telah selesai dan program telah diaktifkan oleh DIGESTEX.
|
*/

if (
    $onboardingCompleted &&
    $participant->activation_status === 'active'
) {
    return [
        'key' => 'program_active',
        'route' => null,
    ];
}

/*
|--------------------------------------------------------------------------
| Program Active
|--------------------------------------------------------------------------
*/

if (
    $onboardingCompleted &&
    $participant->activation_status === 'active'
) {
    return [
        'key' => 'program_active',
        'route' => null,
    ];
}

/*
|--------------------------------------------------------------------------
| Program Inactive
|--------------------------------------------------------------------------
|
| Program pernah / telah diaktifkan tetapi saat ini dinonaktifkan
| oleh administrator DIGESTEX.
|
*/

if (
    $onboardingCompleted &&
    $participant->activation_status === 'inactive'
) {
    return [
        'key' => 'program_inactive',
        'route' => null,
    ];
}

/*
|--------------------------------------------------------------------------
| Program Ready For Activation
|--------------------------------------------------------------------------
|
| Setup selesai tetapi program belum diaktifkan.
|
*/

return [
    'key' => 'program_ready',
    'route' => null,
];
}

    /*
    |--------------------------------------------------------------------------
    | Package Services
    |--------------------------------------------------------------------------
    */

    private function buildServices(
        ?DigitalDirectoryParticipant $participant
    ): array {

        if (!$participant) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Base Services
        |--------------------------------------------------------------------------
        */

        $services = [

            [
                'key' =>
                    'company_passport',

                'name' =>
                    'Digital Company Passport™',

                'available' =>
                    true,

                'active' =>
                    (bool)
                    $participant
                        ->company_passport_active,
            ],

            [
                'key' =>
                    'visibility_score',

                'name' =>
                    'Visibility Score™',

                'available' =>
                    in_array(
                        $participant->package,
                        [
                            'Visibility Partner',
                            'Executive Partner',
                        ],
                        true
                    ),

                'active' =>
                    (bool)
                    $participant
                        ->visibility_score_active,
            ],

            [
                'key' =>
                    'executive_dashboard',

                'name' =>
                    'Executive Dashboard™',

                'available' =>
                    $participant->package ===
                    'Executive Partner',

                'active' =>
                    (bool)
                    $participant
                        ->executive_dashboard_active,
            ],

            [
                'key' =>
                    'smart_matching',

                'name' =>
                    'Smart Business Matching™',

                'available' =>
                    $participant->package ===
                    'Executive Partner',

                'active' =>
                    (bool)
                    $participant
                        ->smart_matching_active,
            ],

            [
                'key' =>
                    'build_supply_chain',

                'name' =>
                    'Build My Supply Chain™',

                'available' =>
                    $participant->package ===
                    'Executive Partner',

                'active' =>
                    (bool)
                    $participant
                        ->build_supply_chain_active,
            ],
        ];

        return $services;
    }
}