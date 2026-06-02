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

                'destination_country' => $rfq->destination_country,

                'status' => $rfq->status,

                'quotation_count' =>
                    $rfq->quotations->count(),

                'file_count' =>
                    $rfq->files->count(),

                'created_at' =>
                    optional($rfq->created_at)
                        ->format('d M Y'),

                'user' => [
                    'id' => $rfq->user?->id,
                    'name' => $rfq->user?->name,
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

        'attachments.*' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx,xls,xlsx',
            'max:10240',
        ],
    ]);

    $validated['user_id'] =
        auth()->id();

    $validated['rfq_number'] =
        'RFQ-' . now()->format('YmdHis');

    $rfq = Rfq::create(
        $validated
    );

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
    'files',
    'quotations' => fn ($q) => $q->latest(),
    'quotations.company',
]);

    return Inertia::render(
        'RFQ/Show',
        [
            'rfq' => $rfq,
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