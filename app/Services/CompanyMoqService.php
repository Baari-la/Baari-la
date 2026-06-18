<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyMoq;

class CompanyMoqService
{
    public static function syncMoqs(
        Company $company,
        array $moqs
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($moqs)) {
            return;
        }

        $processedIds = [];

        foreach ($moqs as $moq) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($moq['product_name']) &&
                empty($moq['minimum_quantity'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($moq['id'])) {

                $record = CompanyMoq::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $moq['id']
                )
                ->first();

                if ($record) {

                    $record->update([

                        'product_name' =>
                            $moq['product_name'] ?? null,

                        'minimum_quantity' =>
                            $moq['minimum_quantity'] ?? 0,

                        'unit' =>
                            $moq['unit'] ?? 'PCS',

                        'notes' =>
                            $moq['notes'] ?? null,
                    ]);

                    $processedIds[] = $record->id;

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW
            |--------------------------------------------------------------------------
            */

            $newMoq = $company->moqs()->create([

                'product_name' =>
                    $moq['product_name'] ?? null,

                'minimum_quantity' =>
                    $moq['minimum_quantity'] ?? 0,

                'unit' =>
                    $moq['unit'] ?? 'PCS',

                'notes' =>
                    $moq['notes'] ?? null,
            ]);

            $processedIds[] = $newMoq->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->moqs()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}