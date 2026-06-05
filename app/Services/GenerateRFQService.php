<?php

namespace App\Services;

use App\Models\Rfq;
use App\Models\CollectiveSourcingGroup;
use Illuminate\Support\Facades\DB;

class GenerateRFQService
{
    public static function run(
        CollectiveSourcingGroup $group
    ): Rfq {

        return DB::transaction(function () use ($group) {

            if ($group->rfq_id) {
                return Rfq::findOrFail(
                    $group->rfq_id
                );
            }

            $rfq = Rfq::create([
    'user_id' => auth()->id(),

    'company_id' => auth()->user()->company_id,

    'rfq_number' => 'RFQ-' . now()->format('YmdHis'),

    'product_name' => $group->product_name,

    'hs_code' => $group->hs_code,

    'description' => $group->specification,

    'required_quantity' => $group->current_quantity,

    'unit' => $group->unit,

    'destination_country' => $group->destination_country,

    'required_delivery_date' => $group->required_delivery_date,

    'quotation_deadline' => $group->quotation_deadline,

    'incoterm' => $group->incoterm,

    'currency' => $group->currency,

    'status' => 'open',
]);

     $group->update([
    'status' => 'rfq_created',
    'rfq_id' => $rfq->id,
]);

            return $rfq;
        });
    }
}