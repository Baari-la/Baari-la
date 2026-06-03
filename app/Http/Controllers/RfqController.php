<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rfq;
use App\Models\RfqFile;
use Inertia\Inertia;
use Inertia\Response;

class RfqController extends Controller
{
   public function index(): Response
{
    $rfqs = Rfq::with([
            'user',
            'company',
            'files',
            'quotations',
        ])
        ->latest()
        ->paginate(20)
        ->through(function ($rfq) {

            return [

                'id' => $rfq->id,

                'rfq_number' => $rfq->rfq_number,

                'product_name' => $rfq->product_name,

                'hs_code' => $rfq->hs_code,

                'required_quantity' => $rfq->required_quantity,

                'unit' => $rfq->unit,

                'destination_country' =>
                    $rfq->destination_country,

                'status' =>
                    $rfq->status,

                'quotation_deadline' =>
                    $rfq->quotation_deadline,

                'quotation_count' =>
                    $rfq->quotations->count(),

                'file_count' =>
                    $rfq->files->count(),

                'created_at' =>
                    optional($rfq->created_at)
                        ->format('d M Y'),

                'user' => [
                    'id'   => $rfq->user?->id,
                    'name' => $rfq->user?->name,
                ],

                'company' => [
                    'id'   => $rfq->company?->id,
                    'name' => $rfq->company?->nama_perusahaan,
                ],
            ];
        });

    return Inertia::render(
        'RFQ/Index',
        [
            'rfqs' => $rfqs,
        ]
    );
}

    public function create()
    {
        return Inertia::render(
        'RFQ/Create'
    );
    }

   public function store(Request $request)
{
    $validated = $request->validate([

        'product_name' =>
            'required|string|max:255',

        'hs_code' =>
            'nullable|string|max:20',

        'description' =>
            'nullable|string',

        'required_quantity' =>
            'required|numeric|min:0',

        'unit' =>
            'required|string|max:20',

        'required_delivery_date' =>
            'nullable|date',

        'destination_country' =>
            'nullable|string|max:255',

        'incoterm' =>
            'nullable|string|max:20',

        'currency' =>
            'required|string|max:10',

        'quotation_deadline' =>
            'nullable|date',

        'attachments.*' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx,xls,xlsx',
            'max:10240',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | User Validation
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    if (!$user->company_id) {

        return back()->withErrors([
            'company' =>
                'Your account is not linked to a company.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RFQ Data
    |--------------------------------------------------------------------------
    */

    $validated['user_id'] =
        $user->id;

    $validated['company_id'] =
        $user->company_id;

    $validated['rfq_number'] =
        'RFQ-' . now()->format('YmdHis');

    $rfq = Rfq::create(
        $validated
    );

    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('attachments')) {

        foreach (
            $request->file('attachments')
            as $file
        ) {

            $path = $file->store(
                'rfq',
                'public'
            );

            RfqFile::create([
                'rfq_id'    => $rfq->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }
    }

    return redirect()->route(
        'rfqs.show',
        $rfq
    );
}

   public function show(Rfq $rfq)
{
     
    $rfq->load([
        'user',
        'company',
        'files',
        'quotations' => fn ($query) => $query->latest(),
        'quotations.company',
    ]);

    //  dd($rfq->toArray());

    return Inertia::render(
        'RFQ/Show',
        [
            'rfq' => [

                'id' => $rfq->id,

                'user_id' => $rfq->user_id,

                'company_id' => $rfq->company_id,

                'rfq_number' => $rfq->rfq_number,

                'product_name' => $rfq->product_name,

                'hs_code' => $rfq->hs_code,

                'description' => $rfq->description,

                'required_quantity' => $rfq->required_quantity,

                'unit' => $rfq->unit,

                'required_delivery_date' =>
                    $rfq->required_delivery_date,

                'destination_country' =>
                    $rfq->destination_country,

                'incoterm' =>
                    $rfq->incoterm,

                'currency' =>
                    $rfq->currency,

                'quotation_deadline' =>
                    $rfq->quotation_deadline,

                'status' =>
                    $rfq->status,

                'awarded_quotation_id' =>
                    $rfq->awarded_quotation_id,

                /*
                |----------------------------------------------------------
                | BUYER COMPANY
                |----------------------------------------------------------
                */

                'company' => [
                    'id' =>
                        $rfq->company?->id,

                    'nama_perusahaan' =>
                        $rfq->company?->nama_perusahaan,
                ],

                /*
                |----------------------------------------------------------
                | CREATOR
                |----------------------------------------------------------
                */

                'user' => [
                    'id' =>
                        $rfq->user?->id,

                    'name' =>
                        $rfq->user?->name,
                ],

                /*
                |----------------------------------------------------------
                | FILES
                |----------------------------------------------------------
                */

                'files' => $rfq->files->map(
                    fn ($file) => [

                        'id' => $file->id,

                        'file_name' =>
                            $file->file_name,

                        'file_path' =>
                            $file->file_path,
                    ]
                ),

                /*
                |----------------------------------------------------------
                | QUOTATIONS
                |----------------------------------------------------------
                */

                'quotations' => $rfq->quotations->map(
                    fn ($quotation) => [

                        'id' =>
                            $quotation->id,

                        'company_id' =>
                            $quotation->company_id,

                        'unit_price' =>
                            $quotation->unit_price,

                        'minimum_order_quantity' =>
                            $quotation->minimum_order_quantity,

                        'lead_time_days' =>
                            $quotation->lead_time_days,

                        'remarks' =>
                            $quotation->remarks,

                        'status' =>
                            $quotation->status,

                        'created_at' =>
                            optional(
                                $quotation->created_at
                            )->format('d M Y'),

                        'company' => [
                            'id' =>
                                $quotation->company?->id,

                            'nama_perusahaan' =>
                                $quotation->company?->nama_perusahaan,
                        ],
                    ]
                ),
            ],
        ]
    );
}

    public function destroy(Rfq $rfq)
    {
         if (
        $rfq->user_id !== auth()->id()
    ) {
        abort(403);
    }

    $rfq->delete();

    return back()->with(
        'success',
        'RFQ deleted successfully.'
    );
    }
}