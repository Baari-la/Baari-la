<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalDirectoryParticipant;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Payment Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        return Inertia::render(
            'Admin/Payments/Index',
            [
                'stats' => [
                    'revenue' => DigitalDirectoryParticipant::sum(
                        'amount'
                    ),

                    'transactions' =>
                        DigitalDirectoryParticipant::count(),

                    'pending' =>
                        DigitalDirectoryParticipant::where(
                            'payment_status',
                            'pending_verification'
                        )->count(),

                    'invoices' =>
                        DigitalDirectoryParticipant::whereNotNull(
                            'invoice_number'
                        )->count(),

                    'qris' =>
                        DigitalDirectoryParticipant::whereNotNull(
                            'qris_reference'
                        )->count(),

                    'manualTransfers' =>
                        DigitalDirectoryParticipant::where(
                            'payment_method',
                            'Bank Transfer'
                        )->count(),
                ],
                'pendingPayments' =>
                    DigitalDirectoryParticipant::query()
                        ->where(
                            'payment_status',
                            'pending_verification'
                        )
                        ->latest('paid_at')
                        ->take(10)
                        ->get(),
                    ]
                );
    }

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */

    public function transactions(): Response
    {
        return Inertia::render(
            'Admin/Payments/Transactions',
            [
                'transactions' =>
                    DigitalDirectoryParticipant::latest()
                        ->paginate(20),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QRIS
    |--------------------------------------------------------------------------
    */

    public function qris(): Response
    {
        return Inertia::render(
            'Admin/Payments/QRIS',
            [
                'payments' =>
                    DigitalDirectoryParticipant::whereNotNull(
                        'qris_reference'
                    )
                    ->latest()
                    ->paginate(20),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Manual Transfer
    |--------------------------------------------------------------------------
    */

    public function manualTransfer(): Response
    {
        return Inertia::render(
            'Admin/Payments/ManualTransfer',
            [
                'payments' =>
                    DigitalDirectoryParticipant::where(
                        'payment_method',
                        'Bank Transfer'
                    )
                    ->latest()
                    ->paginate(20),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Revenue Dashboard
    |--------------------------------------------------------------------------
    */

    public function revenue(): Response
    {
        $revenue = DigitalDirectoryParticipant::sum(
            'amount'
        );

        return Inertia::render(
            'Admin/Payments/Revenue',
            [
                'stats' => [

                    'today' =>
                        DigitalDirectoryParticipant::whereDate(
                            'created_at',
                            today()
                        )->sum(
                            'amount'
                        ),

                    'month' =>
                        DigitalDirectoryParticipant::whereMonth(
                            'created_at',
                            now()->month
                        )->sum(
                            'amount'
                        ),

                    'year' =>
                        DigitalDirectoryParticipant::whereYear(
                            'created_at',
                            now()->year
                        )->sum(
                            'amount'
                        ),

                    'total' =>
                        $revenue,

                    'transactions' =>
                        DigitalDirectoryParticipant::count(),
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice Management
    |--------------------------------------------------------------------------
    */

    public function invoiceManagement(): Response
    {
        return Inertia::render(
            'Admin/Payments/InvoiceManagement',
            [
                'invoices' =>
                    DigitalDirectoryParticipant::whereNotNull(
                        'invoice_number'
                    )
                    ->latest()
                    ->paginate(20),
            ]
        );
    }

    /*
|--------------------------------------------------------------------------
| Verify Payment
|--------------------------------------------------------------------------
*/

public function verify(
    DigitalDirectoryParticipant $participant
)
{
    if (
        $participant->payment_status !==
        'pending_verification'
    ) {
        return back()->with(
            'error',
            'Payment is not awaiting verification.'
        );
    }

    $participant->update([

        'payment_status' =>
            'verified',

        'payment_verified_at' =>
            now(),

        'verified_by' =>
            auth()->id(),
    ]);

    return back()->with(
        'message',
        'Payment verified successfully.'
    );
}
/*
|--------------------------------------------------------------------------
| Reject Payment
|--------------------------------------------------------------------------
*/

public function reject(
    DigitalDirectoryParticipant $participant
)
{
    if (
        $participant->payment_status !==
        'pending_verification'
    ) {
        return back()->with(
            'error',
            'Payment is not awaiting verification.'
        );
    }

    $participant->update([

        'payment_status' =>
            'rejected',

        'payment_verified_at' =>
            null,

        'verified_by' =>
            auth()->id(),
    ]);

    return back()->with(
        'message',
        'Payment rejected.'
    );
}

/*
|--------------------------------------------------------------------------
| Approve Manual Transfer
|--------------------------------------------------------------------------
*/

public function approveManualTransfer(
    DigitalDirectoryParticipant $participant
): RedirectResponse {

    /*
    |--------------------------------------------------------------------------
    | Validate Payment Method
    |--------------------------------------------------------------------------
    */

    if (
        $participant->payment_method !==
        'Bank Transfer'
    ) {
        return back()->with(
            'error',
            'This payment is not a bank transfer.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Current Status
    |--------------------------------------------------------------------------
    */

    if (
        $participant->payment_status !==
        'pending_verification'
    ) {
        return back()->with(
            'error',
            'This payment is not awaiting verification.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approve Payment
    |--------------------------------------------------------------------------
    */

    DB::transaction(
        function () use ($participant) {

            $participant->update([

                'payment_status' =>
                    'verified',

                'payment_verified_at' =>
                    now(),

                'verified_by' =>
                    auth()->id(),
            ]);
        }
    );

    return back()->with(
        'success',
        'Payment verified successfully.'
    );
}


/*
|--------------------------------------------------------------------------
| Reject Manual Transfer
|--------------------------------------------------------------------------
*/

public function rejectManualTransfer(
    DigitalDirectoryParticipant $participant
): RedirectResponse {

    /*
    |--------------------------------------------------------------------------
    | Validate Payment Method
    |--------------------------------------------------------------------------
    */

    if (
        $participant->payment_method !==
        'Bank Transfer'
    ) {
        return back()->with(
            'error',
            'This payment is not a bank transfer.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Current Status
    |--------------------------------------------------------------------------
    */

    if (
        $participant->payment_status !==
        'pending_verification'
    ) {
        return back()->with(
            'error',
            'This payment is not awaiting verification.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Payment
    |--------------------------------------------------------------------------
    */

    DB::transaction(
        function () use ($participant) {

            $participant->update([

                'payment_status' =>
                    'rejected',

                'payment_verified_at' =>
                    null,

                'verified_by' =>
                    auth()->id(),
            ]);
        }
    );

    return back()->with(
        'success',
        'Payment rejected successfully.'
    );
}
/*
|--------------------------------------------------------------------------
| View Manual Transfer Receipt
|--------------------------------------------------------------------------
*/

public function viewManualTransferReceipt(
    DigitalDirectoryParticipant $participant
): SymfonyResponse {

    /*
    |--------------------------------------------------------------------------
    | Validate Payment Method
    |--------------------------------------------------------------------------
    */

    if (
        $participant->payment_method !==
        'Bank Transfer'
    ) {
        abort(
            404,
            'Payment receipt not available.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Receipt Must Exist
    |--------------------------------------------------------------------------
    */

    if (
        !$participant->payment_receipt ||
        !Storage::disk('public')->exists(
            $participant->payment_receipt
        )
    ) {
        abort(
            404,
            'Payment receipt not found.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Return Receipt
    |--------------------------------------------------------------------------
    */

    return Storage::disk('public')->response(
        $participant->payment_receipt
    );
}

}