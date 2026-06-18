<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyLeadTime;

class CompanyLeadTimeService
{
    public static function syncLeadTimes(
        Company $company,
        array $leadTimes
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($leadTimes)) {
            return;
        }

        $processedIds = [];

        foreach ($leadTimes as $leadTime) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($leadTime['lead_time_type']) &&
                empty($leadTime['days'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($leadTime['id'])) {

                $record = CompanyLeadTime::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $leadTime['id']
                )
                ->first();

                if ($record) {

                    $record->update([

                        'lead_time_type' =>
                            $leadTime['lead_time_type'] ?? null,

                        'days' =>
                            $leadTime['days'] ?? null,

                        'notes' =>
                            $leadTime['notes'] ?? null,
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

            $newLeadTime = $company->leadTimes()->create([

                'lead_time_type' =>
                    $leadTime['lead_time_type'] ?? null,

                'days' =>
                    $leadTime['days'] ?? null,

                'notes' =>
                    $leadTime['notes'] ?? null,
            ]);

            $processedIds[] = $newLeadTime->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->leadTimes()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}