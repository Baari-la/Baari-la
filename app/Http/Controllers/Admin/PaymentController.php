<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalDirectoryParticipant;
use Inertia\Inertia;
use Inertia\Response;

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
}