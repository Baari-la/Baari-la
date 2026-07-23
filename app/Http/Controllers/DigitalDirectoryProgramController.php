<?php

namespace App\Http\Controllers;

use App\Models\DigitalDirectoryParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DigitalDirectoryProgramController extends Controller
{
    public function step1(): Response
    {
        return Inertia::render(
            'Programs/DigitalDirectory/Step1ProgramInformation'
        );
    }

    public function step2(): Response
    {
        return Inertia::render(
            'Programs/DigitalDirectory/Step2PackageSelection'
        );
    }

    public function step3(): Response
    {
        return Inertia::render(
            'Programs/DigitalDirectory/Step3CompanyInformation'
        );
    }

    public function step4(): Response
    {
        return Inertia::render(
            'Programs/DigitalDirectory/Step4Review',
            [
                'company' => session('program'),
            ]
        );
    }

    public function step5(): Response
    {
        return Inertia::render(
            'Programs/DigitalDirectory/Step5Payment',
            [
                'company' => session('program'),
            ]
        );
    }

    public function step6(): Response
    {
        return Inertia::render(
            'Programs/DigitalDirectory/Step6Welcome',
            [
                'company' => session('program'),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Company Information
    |--------------------------------------------------------------------------
    */

    public function storeCompanyInformation(
        Request $request
    ): RedirectResponse {

        $data = $request->validate([

            'package' =>
                'required|string',

            'company_name' =>
                'required|string|max:255',

            'pic_name' =>
                'required|string|max:255',

            'position' =>
                'nullable|string|max:255',

            'email' =>
                'required|email|max:255',

            'phone' =>
                'nullable|string|max:255',

            'website' =>
                'nullable|string|max:255',

            'company_type' =>
                'nullable|string|max:255',

            'country' =>
                'nullable|string|max:255',

            'city' =>
                'nullable|string|max:255',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Package Price
        |--------------------------------------------------------------------------
        */

        $prices = [

            'Verified Company' =>
                2_500_000,

            'Visibility Partner' =>
                5_000_000,

            'Executive Partner' =>
                10_000_000,
        ];

        /*
        |--------------------------------------------------------------------------
        | Invoice
        |--------------------------------------------------------------------------
        */

        $invoiceNumber =
            'INV-' .
            now()->format(
                'YmdHis'
            );

        $paymentReference =
            'DDVP-' .
            now()->format(
                'YmdHis'
            );

        /*
        |--------------------------------------------------------------------------
        | Create Participant
        |--------------------------------------------------------------------------
        */

        $participant =
    DigitalDirectoryParticipant::create([

        ...$data,

        'amount' =>
            $prices[
                $data['package']
            ] ?? 0,

        'currency' =>
            'IDR',

        'invoice_number' =>
            'INV-' .
            now()->format(
                'YmdHis'
            ),

        'payment_reference' =>
            'DDVP-' .
            now()->format(
                'YmdHis'
            ),

        'payment_status' =>
            'waiting_payment',

        'activation_status' =>
            'draft',
    ]);

        /*
        |--------------------------------------------------------------------------
        | Session
        |--------------------------------------------------------------------------
        */

        session([
            'program' =>
                $participant->toArray(),
        ]);

        return redirect()->route(
            'program.digital-directory.review'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Confirm Payment
    |--------------------------------------------------------------------------
    */

    public function confirmPayment(
        Request $request
    ): RedirectResponse {

        $request->validate([

            'receipt' => [

                'required',

                'mimes:jpg,jpeg,png,pdf',

                'max:5120',
            ],
        ]);

        $participant =
            DigitalDirectoryParticipant::findOrFail(
                session(
                    'program.id'
                )
            );

        $path = $request
            ->file(
                'receipt'
            )
            ->store(
                'payment-receipts',
                'public'
            );

        $participant->update([

            'payment_method' =>
                'Bank Transfer',

            'payment_gateway' =>
                'Manual',

            'payment_receipt' =>
                $path,

            'payment_status' =>
                'pending_verification',

            'paid_at' =>
                now(),
        ]);

        session()->put(
            'program',
            $participant->fresh()
        );

        return redirect()->route(
            'program.digital-directory.welcome'
        );
    }

    public function review()
{
    return Inertia::render(
        'Programs/DigitalDirectory/Step4Review'
    );
}
   
    
}